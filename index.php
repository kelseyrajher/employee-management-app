<?php
session_start();
// Include database connection file
include 'config/db.php';

// Declare error variables and set to empty values     
$emailErr = $passwordErr = $emptyFieldsErr = $unknownUserErr = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Validation for email and password
    if (empty($email) || empty($password)) {
        $emptyFieldsErr = "Please fill in all fields";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Invalid email address format";

    } else {
        // Pull the hashed password and user ID from the database based on the email
        $sql = "SELECT user_id, password, role FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            $hashedPasswordFromDatabase = $row["password"];
            $user_id = $row["user_id"];
            $role = $row["role"];

            // Verify the entered password against the stored hashed password
            if (password_verify($password, $hashedPasswordFromDatabase)) {
                // Password is correct, create a session
                $_SESSION["email"] = $email;
                $_SESSION["user_id"] = $user_id; // Store the user ID in the session
                $_SESSION["role"] = $role; // Store the user's role in the session
                header("location: public/dashboard.php"); // Redirect to dashboard.php
                exit;
            } else {
                $passwordErr = "Incorrect password";
            }
        } else {
            $unknownUserErr = "User not found <br> Contact IT to request access to application";
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Login Page</title>
    <link rel="stylesheet" href="public/css/login.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.20.0/jquery.validate.min.js"
        integrity="sha512-WMEKGZ7L5LWgaPeJtw9MBM4i5w5OSBlSjTjCtSnvFJGSVD26gE5+Td12qN5pvWXhuWaWcVwF++F7aqu9cvqP0A=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.20.0/additional-methods.min.js"
        integrity="sha512-TiQST7x/0aMjgVTcep29gi+q5Lk5gVTUPE9XgN0g96rwtjEjLpod4mlBRKWHeBcvGBAEvJBmfDqh2hfMMmg+5A=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>

<body>
    <div class="grid-container">
        <div class="login-form">
            <div class="logo">
                <img src="images/logoipsum-black.svg" alt="Company Logo">
            </div>
            <h3>Login</h3>
            <form id="loginForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <input type="email" id="email" name="email" placeholder="Email" required>
                <span class="error"><br>
                    <?php echo $emailErr; ?>
                </span>
                <input type="password" id="password" name="password" placeholder="Password" required>
                <span class="error"><br>
                    <?php echo $passwordErr; ?>
                </span>
                <button type="submit">Login</button>
                <span class="error"><br>
                    <?php echo $emptyFieldsErr; ?>
                </span>
                <span class="error">
                    <?php echo $unknownUserErr; ?>
                </span>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $("#loginForm").validate({

                rules: {
                    email: {
                        required: true,
                        email: true,
                    },
                    password: {
                        required: true,
                    },
                },

            });
        });

    </script>

</body>

</html>
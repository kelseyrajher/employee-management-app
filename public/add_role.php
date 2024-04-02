<?php
// Include authentication script
include '../config/auth.php';
// Include database connection file
include '../config/db.php';

// Declare error variables and set to empty values   
$roleIdErr = $jobNameErr = $lineCodeErr = "";

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_role'])) {

    // Check the role of the logged-in user from the session
    if ($_SESSION["role"] !== "admin") {
        // Redirect users without admin privileges to a permissions error message page
        header("Location: ../public/permissions_error_message.php");
        exit;

    } else {

        // Declare variables and set to empty values
        $roleId = $jobName = $lineCode = "";

        // Validate and sanitize user input
        function test_input($data)
        {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }


        $roleId = test_input($_POST['role_id']);
        $jobName = test_input($_POST['job_name']);
        $lineCode = test_input($_POST['line_code']);

        // Custom validation checks
        if (!filter_var($roleId, FILTER_VALIDATE_INT)) {
            $roleIdErr = "Invalid role ID format";
        }


        // Validation for job_name to allow only letters and spaces
        if (!preg_match("/^[a-zA-Z\s]+$/", $jobName)) {
            $jobNameErr = "Job name can only contain letters and spaces";
        }


        if (!filter_var($lineCode, FILTER_VALIDATE_INT)) {
            $lineCodeErr = "Invalid line code format";
        }

        if (empty($roleIdErr) && empty($jobNameErr && empty($lineCodeErr))) {

            // Using prepared statement to prevent SQL injection
            $stmtInsertRole = $conn->prepare("INSERT INTO roles (role_id, job_name, line_code) VALUES (?, ?, ?)");
            $stmtInsertRole->bind_param("sss", $roleId, $jobName, $lineCode);

            // Execute the prepared statement
            if ($stmt->execute()) {
                // Redirect back to success message page
                header("Location: form_submission_success.php?type=add_role");
                exit();

            } else {
                // Log the error message to the error log file
                error_log("Error in insert role query: " . $stmtInsertRole->error);
                // Display a generic error message to the user
                echo "An error occurred while adding the role. Please try again later.";
            }

            // Close the prepared statement
            $stmtInsertRole->close();

            // Close the database connection
            $conn->close();
        }
    }
}
?>

<!-- Include header file -->
<?php include '../public/header.php' ?>

<main class="management_form_pages">
    <h1>Role Management</h1>
    <div>
        <h2>Add New Role</h2>
    </div>
    <div class=form_section>
        <span class="error">* required field</span><br><br>
        <form id="addRoleForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <table class="form-table">
                <tr>
                    <td><label for="role_id">Role ID:</label></td>
                    <td><input type="text" id="role_id" name="role_id" required>
                        <span class="error">*<br>
                            <?php echo $roleIdErr; ?>
                        </span>
                    </td>
                    </td>
                <tr>
                    <td><label for="job_name">Job Name:</label></td>
                    <td><input type="text" id="job_name" name="job_name" required>
                        <span class="error">*<br>
                            <?php echo $jobNameErr; ?>
                        </span>
                    </td>
                    </td>
                </tr>

                <tr>
                    <td><label for="line_code">Line Code:</label></td>
                    <td><input type="text" id="line_code" name="line_code" required>
                        <span class="error">*<br>
                            <?php echo $lineCodeErr; ?>
                        </span>
                    </td>
                    </td>
                </tr>
            </table><br>
            <button type="submit" id="line_code" name="add_role">Add Role</button>
        </form>
    </div>
</main>

<footer>&copy; 2023-2024 Kelsey Rajher. All Rights Reserved.</footer>
</div>

<script>
    $(document).ready(function () {
        $("#addRoleForm").validate({

            errorPlacement: function (error, element) {
                error.appendTo(element.parent()); // Append error message to the parent of the input element
            },
            rules: {
                role_id: {
                    required: true,
                    number: true,
                    minlength: 2,
                },
                job_name: {
                    required: true,
                    pattern: /^[a-zA-Z\s'-]+$/
                },
                line_code: {
                    required: true,
                    number: true,
                    minlength: 3,
                },
            },
            messages: {
                job_name: {
                    pattern: "Job name can only contain letters"
                },
            }

        });
    });

</script>

</body>


</html>
<?php
// Include authentication script
include '../config/auth.php';

// Include database connection file
include '../config/db.php';

// Declare error variables and set to empty values 
$jobNameErr = $lineCodeErr = "";

// Handle form submission for role update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_role'])) {

    // Check the role of the logged-in user from the session
    if ($_SESSION["role"] !== "admin") {
        // Redirect users without admin privileges to a permissions error message page
        header("Location: ../public/permissions_error_message.php");
        exit;

    } else {
        // Proceed with the update if the user is an admin

        // Declare variables and set to empty values
        $jobName = $lineCode = "";

        function test_input($data)
        {

            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        $jobName = test_input($_POST['job_name']);
        $lineCode = test_input($_POST['line_code']);

        // Custom validation checks
        // Validation for job_name to allow only letters and spaces
        if (!preg_match("/^[a-zA-Z\s]+$/", $jobName)) {
            $jobNameErr = "Job name can only contain letters and spaces";
        }

        if (!filter_var($lineCode, FILTER_VALIDATE_INT)) {
            $lineCodeErr = "Invalid line code format";
        }

        if (empty($jobNameErr) && empty($lineCodeErr)) {

            // Update role details in the database - using prepared statement to prevent SQL injection
            $stmtUpdateRole = $conn->prepare("UPDATE roles SET job_name = ?, line_code = ? WHERE role_id = ?");
            $stmtUpdateRole->bind_param("ssi", $jobName, $lineCode, $roleId);

            // Execute the prepared statement
            if ($stmtUpdateRole->execute()) {
                // Redirect to success message page
                header("Location: form_submission_success.php?type=edit_role");
                exit();

            } else {
                // Log the error message to the error log file
                error_log("Error in update role query: " . $stmtUpdateRole->error);
                // Display a generic error message to the user
                echo "An error occurred while updating the role. Please try again later.";
            }

            // Close the prepared statement
            $stmtUpdateRole->close();
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
        <h2>Edit Role</h2>
    </div>


    <?php
    include '../config/db.php';

    if (isset($_GET['role_id'])) {
        $role_id = $_GET['role_id'];

        // Fetch role details from the database
        $stmtFetchSelectedRole = $conn->prepare("SELECT * FROM roles WHERE role_id = ?");
        $stmtFetchSelectedRole->bind_param("i", $role_id);
        $stmtFetchSelectedRole->execute();
        $result = $stmtFetchSelectedRole->get_result();
        $role = $result->fetch_assoc();
        $stmtFetchSelectedRole->close();

        if ($role) {
            ?>

            <div class=form_section>
                <span class="error">* required field</span><br><br>
                <form id="editRoleForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                    <table class="form-table">
                        <tr>
                            <td><label for="role_id">Role ID:</label></td>
                            <td>
                                <div>
                                    <?php echo htmlspecialchars($role['role_id']); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="job_name">Job Name:</label></td>
                            <td><input type="text" name="job_name" value="<?php echo htmlspecialchars($role['job_name']) ?>"
                                    required>
                                <span class="error">*<br>
                                    <?php echo $jobNameErr; ?>
                                </span>
                            </td>
                            </td>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="line_code">Line Code:</label></td>
                            <td><input type="text" name="line_code" value="<?php echo htmlspecialchars($role['line_code']) ?>">
                                <span class="error">*<br>
                                    <?php echo $lineCodeErr; ?>
                                </span>
                            </td>
                            </td>
                            </td>
                        </tr>
                    </table><br>
                    <button type="submit" name="update_role">Update Role</button>

                </form>
            </div>

            <?php
        } else {
            echo "Role not found.";
        }
    } else {
        echo "Role ID not provided.";
    }
    ?>
</main>

<footer>&copy; 2023-2024 Kelsey Rajher. All Rights Reserved.</footer>
</div>
<script>
    $(document).ready(function () {
        $("#editRoleForm").validate({

            errorPlacement: function (error, element) {
                error.appendTo(element.parent()); // Append error message to the parent of the input element
            },
            rules: {
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
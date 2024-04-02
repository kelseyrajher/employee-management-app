<?php
// Include authentication script
include '../config/auth.php';

// Include database connection file
include '../config/db.php';

// Declare error variables and set to empty values   
$firstNameErr = $lastNameErr = $startDateErr = $endDateErr = $costCenterIdErr = $positionIdErr = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_employee'])) {

    // Check the role of the logged-in user from the session
    if ($_SESSION["role"] !== "admin") {
        // Redirect users without admin privileges to a permissions error message page
        header("Location: ../public/permissions_error_message.php");
        exit;

    } else {
        // Proceed with the update if the user is an admin

        // Declare variables and set to empty values
        $firstName = $lastName = $startDate = $endDate = $costCenterId = $positionId = "";

        function test_input($data)
        {

            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        $firstName = test_input($_POST['first_name']);
        $lastName = test_input($_POST['last_name']);
        $startDate = test_input($_POST['start_date']);
        $endDate = test_input($_POST['end_date'] != "") ? $_POST['end_date'] : null;
        $costCenterId = test_input($_POST['cost_center_id']);
        $positionId = test_input($_POST['position_id']);

        // Perform custom validation checks
        if (!preg_match("/^[a-zA-Z\s'-]+$/", $firstName)) {
            $firstNameErr = "First name can only contain letters, spaces, ', or -";
        }

        if (!preg_match("/^[a-zA-Z\s'-]+$/", $lastName)) {
            $lastNameErr = "Last name can only contain letters, spaces, ', or -";
        }

        if (!preg_match("/(19[5-9][0-9]|20[0-9][0-9])-(0[1-9]|1[0-2])-(0[1-9]|1[0-9]|2[0-9]|3[01])/", $startDate)) {
            $startDateErr = "Start date must be in the format yyyy-mm-dd";
        }

        if ($endDate !== null && !preg_match("/(19[5-9][0-9]|20[0-9][0-9])-(0[1-9]|1[0-2])-(0[1-9]|1[0-9]|2[0-9]|3[01])/", $endDate)) {
            $endDateErr = "End date must be in the format yyyy-mm-dd";
        }

        if (!filter_var($costCenterId, FILTER_VALIDATE_INT)) {
            $costCenterIdErr = "Invalid cost center ID format";
        }

        if (!filter_var($positionId, FILTER_VALIDATE_INT)) {
            $positionIdErr = "Invalid position ID format";
        }

        // If there are no errors continue with updating entry
        if (empty($firstNameErr) && empty($lastNameErr) && empty($startDateErr) && empty($endDateErr) && empty($costCenterIdErr) && empty($positionIdErr)) {

            // Update employee details in the database - using prepared statement to prevent SQL injection
            $stmtUpdateEmployee = $conn->prepare("UPDATE incumbents SET first_name=?, last_name=?, start_date=?, end_date=?, cost_center_id=?, position_id=? WHERE employee_id=?");
            $stmtUpdateEmployee->bind_param("ssssiii", $firstName, $lastName, $startDate, $endDate, $costCenterId, $positionId, $employeeId);

            // Execute the prepared statement
            if ($stmtUpdateEmployee->execute()) {
                // Redirect to success message page
                header("Location: form_submission_success.php?type=edit_employee");
                exit();

            } else {
                // Log the error message to the error log file
                error_log("Error in update employee query: " . $stmtUpdateEmployee->error);
                // Display a generic error message to the user
                echo "An error occurred while updating the employee. Please try again later.";
            }

            // Close the prepared statement
            $stmtUpdateEmployee->close();

            // Close the database connection
            $conn->close();
        }
    }
}
?>

<!-- Include header file -->
<?php include '../public/header.php' ?>

<main class="management_form_pages">
    <h1>Employee Management </h1>
    <div>
        <h2>Edit Employee</h2>
    </div>

    <?php
    include '../config/db.php';

    // Check if employee_id is provided in the URL
    if (isset($_GET['employee_id'])) {
        $employeeId = $_GET['employee_id'];

        // Fetch the existing employee details from the database
        // Using prepared statement to prevent SQL injection
        $stmtFetchSelectedEmployee = $conn->prepare("SELECT * FROM incumbents WHERE employee_id = ?");
        $stmtFetchSelectedEmployee->bind_param("i", $employeeId);
        $stmtFetchSelectedEmployee->execute();
        $result = $stmtFetchSelectedEmployee->get_result();
        $employee = $result->fetch_assoc();
        $stmtFetchSelectedEmployee->close();

        if ($employee) {
            ?>

            <div class="form_section">
                <span class="error">* required field</span><br><br>
                <form id="editEmployeeForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                    <table class="form-table">
                        <tr>
                            <td><label for="employee_id">Employee ID:</label></td>
                            <td>
                                <div>
                                    <?php echo htmlspecialchars($employee['employee_id']); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="first_name">First Name:</label></td>
                            <td><input type="text" id="first_name " name="first_name"
                                    value="<?php echo htmlspecialchars($employee['first_name']); ?>" required>
                                <span class="error">*<br>
                                    <?php echo $firstNameErr; ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="last_name">Last Name:</label></td>
                            <td><input type="text" id="last_name" name="last_name"
                                    value="<?php echo htmlspecialchars($employee['last_name']); ?>" required>
                                <span class="error">*<br>
                                    <?php echo $lastNameErr; ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="start_date">Start Date:</label></td>
                            <td><input type="date" id="start_date" name="start_date"
                                    value="<?php echo htmlspecialchars($employee['start_date']); ?>">
                                <span class="error">*<br>
                                    <?php echo $startDateErr; ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="end_date">End Date:</label></td>
                            <td><input type="date" id="end_date" name="end_date"
                                    value="<?php echo $employee['end_date'] !== null ? htmlspecialchars($employee['end_date']) : ''; ?>">
                                <span class="error"><br>
                                    <?php echo $endDateErr; ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="cost_center_id">Cost Center ID:</label></td>
                            <td><input type="text" id="cost_center_id" name="cost_center_id"
                                    value="<?php echo htmlspecialchars($employee['cost_center_id']); ?>" required>
                                <span class="error">*<br>
                                    <?php echo $costCenterIdErr; ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="position_id">Position ID:</label></td>
                            <td><input type="text" id="position_id" name="position_id"
                                    value="<?php echo htmlspecialchars($employee['position_id']); ?>" required>
                                <span class="error">*<br>
                                    <?php echo $positionIdErr; ?>
                                </span>
                            </td>
                        <tr>
                    </table><br>
                    <button type="submit" name="update_employee">Update Employee</button>
                </form>
            </div>
            <?php
        } else {
            echo "Employee not found.";
        }
    } else {
        echo "Employee ID not provided in the URL.";
    }
    ?>
</main>

<footer>&copy; 2023-2024 Kelsey Rajher. All Rights Reserved.</footer>
</div>
<script>
    $(document).ready(function () {
        $("#editEmployeeForm").validate({

            errorPlacement: function (error, element) {
                error.appendTo(element.parent()); // Append error message to the parent of the input element
            },
            rules: {
                first_name: {
                    required: true,
                    pattern: /^[a-zA-Z\s'-]+$/
                },
                last_name: {
                    required: true,
                    pattern: /^[a-zA-Z\s'-]+$/
                },
                start_date: {
                    required: true,
                    date: true,
                },
                end_date: {
                    date: true,
                },
                cost_center_id: {
                    required: true,
                    number: true,
                    minlength: 3,
                },
                position_id: {
                    required: true,
                    number: true,
                    minlength: 4,
                },
            },
            messages: {
                first_name: {
                    pattern: "First name can only contain letters"
                },
                last_name: {
                    pattern: "Last name can only contain letters"
                },
            }

        });
    });

</script>
</body>


</html>
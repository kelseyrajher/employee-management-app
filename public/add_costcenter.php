<?php
// Include authentication script
include '../config/auth.php';

// Include database connection file
include '../config/db.php';

// Declare error variables and set to empty values     
$costCenterIdErr = $costCenterNameErr = $locationErr = $expectedHeadcountErr = "";

// Check if the request method is POST and if the 'add_cost_center' parameter is set
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_cost_center'])) {

    // Check the role of the logged-in user from the session
    if ($_SESSION["role"] !== "admin") {
        // Redirect users without admin privileges to a permissions error page
        header("Location: ../public/permissions_error_message.php");
        exit;

    } else {

        // Declare variables and set to empty values
        $costCenterId = $costCenterName = $location = $expectedHeadcount = "";

        function test_input($data)
        {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        // Validate and sanitize user input
        $costCenterId = test_input($_POST["cost_center_id"]);
        $costCenterName = test_input($_POST["cost_center_name"]);
        $location = test_input($_POST["location"]);
        $expectedHeadcount = test_input($_POST["expected_headcount"]) ? $_POST['expected_headcount'] : null;

        // Perform custom validation checks
        if (!filter_var($costCenterId, FILTER_VALIDATE_INT)) {
            $costCenterIdErr = "Invalid cost center ID format";
        }

        // Validation for expected_headcount to allow only numbers
        if ($expectedHeadcount !== null && !is_numeric($expectedHeadcount)) {
            $expectedHeadcountErr = "Expected headcount must be a number";
        }

        // Validation for cost_center_name to allow only letters and spaces
        if (!preg_match("/^[a-zA-Z\s]+$/", $costCenterName)) {
            $costCenterNameErr = "Cost center name can only contain letters and spaces";
        }

        // Validation for cost_center_name to allow only letters and spaces
        if (!preg_match("/^[a-zA-Z\s]+$/", $location)) {
            $locationErr = "Location name can only contain letters and spaces";
        }

        // If no errors are found proceed with database insertion
        if (empty($costCenterIdErr) && empty($costCenterNameErr) && empty($locationErr) && empty($expectedHeadcountErr)) {

            // Using prepared statement to prevent SQL injection
            $stmtInsertCostCenter = $conn->prepare("INSERT INTO costcenter (cost_center_id, cost_center_name, location, expected_headcount) VALUES (?, ?, ?, ?)");
            $stmtInsertCostCenter->bind_param("isss", $costCenterId, $costCenterName, $location, $expectedHeadcount);

            // Execute the prepared statement
            if ($stmtInsertCostCenter->execute()) {

                // Redirect to success message page
                header("Location: form_submission_success.php?type=add_costcenter");
                exit();

            } else {
                // Log the error 
                error_log("Error in insert cost center query: " . $stmtInsertCostCenter->error);
                // Display a generic error message to the user
                echo "An error occurred while adding the cost center. Please try again later.";
            }

            // Close the prepared statement
            $stmtInsertCostCenter->close();

            // Close the database connection
            $conn->close();
        }
    }
}
?>

<!-- Include header file -->
<?php include '../public/header.php' ?>

<main class="management_form_pages">
    <h1>Cost Center Management </h1>
    <div>
        <h2>Add New Cost Center</h2>
    </div>
    <div class="form_section">
        <span class="error">* required field</span><br><br>
        <form id="addCostCenterForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <table class="form-table">
                <tr>
                    <td><label for="cost_center_id">Cost Center ID:</label></td>
                    <td><input type="number" name="cost_center_id"
                            value="<?php echo isset($_POST['cost_center_id']) ? htmlspecialchars($_POST['cost_center_id']) : ''; ?>"
                            required>
                        <span class="error">*<br>
                            <?php echo $costCenterIdErr; ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td><label for="cost_center_name">Cost Center Name:</label></td>
                    <td><input type="text" name="cost_center_name"
                            value="<?php echo isset($_POST['cost_center_name']) ? htmlspecialchars($_POST['cost_center_name']) : ''; ?>"
                            required>
                        <span class="error">*<br>
                            <?php echo $costCenterNameErr; ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td><label for="location">Location:</label></td>
                    <td><input type="text" name="location"
                            value="<?php echo isset($_POST['cost_center_name']) ? htmlspecialchars($_POST['cost_center_name']) : ''; ?>"
                            required>
                        <span class="error">*<br>
                            <?php echo $locationErr; ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td><label for="expected_headcount">Expected Headcount:</label></td>
                    <td><input type="number" name="expected_headcount"
                            value="<?php echo isset($_POST['expected_headcount']) ? htmlspecialchars($_POST['expected_headcount']) : ''; ?>">
                        <span class="error"><br>
                            <?php echo $expectedHeadcountErr; ?>
                        </span>
                    </td>
                </tr>
            </table><br>
            <button type="submit" name="add_cost_center">Add Cost Center</button>
        </form>
    </div>

</main>

<footer>&copy; 2023-2024 Kelsey Rajher. All Rights Reserved.</footer>
</div>
<script>
    $(document).ready(function () {
        $("#addCostCenterForm").validate({

            errorPlacement: function (error, element) {
                error.appendTo(element.parent()); // Append error message to the parent of the input element
            },
            rules: {
                cost_center_id: {
                    required: true,
                    number: true,
                    minlength: 3,
                },

                cost_center_name: {
                    required: true,
                    pattern: /^[a-zA-Z\s]+$/
                },
                location: {
                    required: true,
                    pattern: /^[a-zA-Z\s]+$/
                },
                expected_headcount: {
                    number: true,
                }
            },
            messages: {
                cost_center_name: {
                    pattern: "Cost Center name can only contain letters"
                },
                location: {
                    pattern: "Location name can only contain letters"
                }
            }
        });
    });

</script>
</body>


</html>
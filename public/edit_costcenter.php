<?php
// Include authentication script
include '../config/auth.php';

// Include database connection file
include '../config/db.php';

// Declare error variables and set to empty values     
$costCenterNameErr = $locationErr = $expectedHeadcountErr = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_cost_center'])) {

    // Check the role of the logged-in user from the session
    if ($_SESSION["role"] !== "admin") {
        // Redirect users without admin privileges to a permissions error message page
        header("Location: ../public/permissions_error_message.php");
        exit;

    } else {
        // Proceed with the update if the user is an admin

        // Declare variables and set to empty values
        $costCenterName = $location = $expectedHeadcount = "";

        function test_input($data)
        {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        $costCenterName = test_input($_POST['cost_center_name']);
        $location = test_input($_POST['location']);
        $expectedHeadcount = test_input($_POST["expected_headcount"]) ? $_POST['expected_headcount'] : null;

        // Perform custom validation checks

        // Validation for cost_center_name to allow only letters and spaces
        if (!preg_match("/^[a-zA-Z\s]+$/", $costCenterName)) {
            $costCenterNameErr = "Cost center name can only contain letters";
        }

        // Validation for location name to allow only letters and spaces
        if (!preg_match("/^[a-zA-Z\s]+$/", $location)) {
            $locationErr = "Location name can only contain letters";
        }

        // Validation to check if expected_headcount is present and confirm it is numeric
        if ($expectedHeadcount !== null && !is_numeric($expectedHeadcount)) {
            $expectedHeadcountErr = "Expected headcount must be a number";
        }

        // If no errors are found proceed with database insertion
        if (empty($costCenterNameErr) && empty($locationErr) && empty($expectedHeadcountErr)) {

            // Using prepared statement to prevent SQL injection
            $stmtUpdateCostCenter = $conn->prepare("UPDATE costcenter SET cost_center_name=?, location=?, expected_headcount=? WHERE cost_center_id=?");
            $stmtUpdateCostCenter->bind_param("ssii", $costCenterName, $location, $expectedHeadcount, $costCenterId);

            // Execute the prepared statement
            if ($stmtUpdateCostCenter->execute()) {
                // Redirect to success message page
                header("Location: form_submission_success.php?type=edit_costcenter");
                exit();

            } else {
                // Log the error
                error_log("Error in update cost center query: " . $stmtUpdateCostCenter->error);
                // Display a generic error message to the user
                echo "An error occurred while updating the cost center. Please try again later.";
            }

            // Close the prepared statement
            $stmtUpdateCostCenter->close();

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
        <h2>Edit Cost Center</h2>
    </div>

    <?php
    include '../config/db.php';

    // Check if cost_center_id is provided in the URL
    if (isset($_GET['cost_center_id'])) {
        $costCenterId = $_GET['cost_center_id'];

        // Fetch the existing cost center details from the database
        // Using prepared statement to prevent SQL injection
        $stmtFetchSelectedCostCenter = $conn->prepare("SELECT * FROM costcenter WHERE cost_center_id = ?");
        $stmtFetchSelectedCostCenter->bind_param("i", $costCenterId);
        $stmtFetchSelectedCostCenter->execute();
        $result = $stmtFetchSelectedCostCenter->get_result();
        $costCenter = $result->fetch_assoc();
        $stmtFetchSelectedCostCenter->close();

        if ($costCenter) {
            ?>
            <div class=form_section>
                <form id="editCostCenterForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                    <table class="form-table">
                        <tr>
                            <td><label for="cost_center_id">Cost Center ID:</label></td>
                            <td>
                                <div>
                                    <?php echo htmlspecialchars($costCenter['cost_center_id']); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="cost_center_name">Cost Center Name:</label></td>
                            <td><input type="text" name="cost_center_name"
                                    value="<?php echo htmlspecialchars($costCenter['cost_center_name']); ?>" required>
                                <span class="error">*<br>
                                    <?php echo $costCenterNameErr; ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="location">Location:</label></td>
                            <td><input type="text" name="location"
                                    value="<?php echo htmlspecialchars($costCenter['location']); ?>" required>
                                <span class="error">*<br>
                                    <?php echo $locationErr; ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="expected_headcount">Expected Headcount:</label></td>
                            <td><input type="number" name="expected_headcount"
                                    value="<?php echo htmlspecialchars($costCenter['expected_headcount']); ?>" required>
                                <span class="error"><br>
                                    <?php echo $expectedHeadcountErr; ?>
                                </span>
                            </td>
                        </tr>
                    </table><br>
                    <button type="submit" name="update_cost_center">Update Cost Center</button>
                </form>
            </div>
            <?php
        } else {
            echo "Cost Center not found.";
        }
    } else {
        echo "Cost Center ID not provided in the URL.";
    }
    ?>
</main>

<footer>&copy; 2023-2024 Kelsey Rajher. All Rights Reserved.</footer>
</div>
<script>
    $(document).ready(function () {
        $("#editCostCenterForm").validate({

            errorPlacement: function (error, element) {
                error.appendTo(element.parent()); // Append error message to the parent of the input element
            },
            rules: {
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
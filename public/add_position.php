<?php
// Include authentication script
include '../config/auth.php';

// Include database connection file
include '../config/db.php';

// Declare error variables and set to empty values   
$jobIdErr = $isFilledErr = $costCenterIdErr = "";

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_position'])) {

    // Check the role of the logged-in user from the session
    if ($_SESSION["role"] !== "admin") {
        // Redirect users without admin privileges to a permissions error message page
        header("Location: ../public/permissions_error_message.php");
        exit;

    } else {

        // Declare variables and set to empty values
        $jobId = $isFilled = $costCenterId = "";

        // Validate and sanitize user input
        function test_input($data)
        {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }


        $jobId = test_input($_POST['job_id']);
        $costCenterId = test_input($_POST['cost_center_id']);
        $isFilled = isset($_POST['is_filled']) ? $_POST['is_filled'] : 0;


        if (!filter_var($jobId, FILTER_VALIDATE_INT)) {
            $jobIdErr = "Invalid job ID format";
        }

        if (!filter_var($costCenterId, FILTER_VALIDATE_INT)) {
            $costCenterIdErr = "Invalid cost center ID format";
        }

        if (!filter_var($isFilled, FILTER_VALIDATE_BOOLEAN)) {
            $isFilledErr = "Invalid value for is filled";
        }

        // Using prepared statement to prevent SQL injection
        $stmt = $conn->prepare("INSERT INTO position (job_id, cost_center_id, is_filled) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $jobId, $costCenterId, $isFilled);

        // Execute the prepared statement
        if ($stmt->execute()) {
            // Redirect back to position_management.php
            header("Location: form_submission_success.php?type=add_position");
            exit();
        } else {
            // Log the error message to the error log file
            error_log("SQL error: " . $stmt->error);
            // Display a generic error message to the user
            echo "An error occurred while adding the position. Please try again later.";
        }

        $stmt->close();
    }
}
?>

<!-- Include header file -->
<?php include '../public/header.php' ?>

<main class="management_form_pages">
    <h1>Position Management</h1>
    <div>
        <h2>Add New Position</h2>
    </div>
    <div class=form_section>
        <span class="error">* required field</span><br><br>
        <form id="addPositionForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <table class="form-table">
                <tr>
                    <td> <label for="job_id">Role:</label></td>
                    <td> <select id="job_id" name="job_id" required>
                            <?php
                            // Fetch job name options from the roles table
                            include '../config/db.php';
                            $stmt = $conn->prepare("SELECT role_id, job_name FROM roles");
                            $stmt->execute();
                            $result_roles = $stmt->get_result();

                            echo "<option value=''>Select Role</option>";
                            while ($role = $result_roles->fetch_assoc()) {
                                echo "<option value='{$role['role_id']}'>{$role['job_name']}</option>";
                            }
                            $stmt->close();
                            ?>
                        </select>
                        <span class="error">*<br>
                            <?php echo $jobIdErr; ?>
                        </span>
                    </td>
                </tr>


                <tr>
                    <td><label for="cost_center_id">Cost Center:</label></td>
                    <td>
                        <select id="cost_center_id" name="cost_center_id" required>
                            <?php
                            // Fetch cost center options with their locations from the CostCenter table
                            include '../config/db.php';

                            $stmt = $conn->prepare("SELECT c.cost_center_id, c.cost_center_name, cc.location
                                    FROM CostCenter c
                                    INNER JOIN costcenter cc ON c.cost_center_id = cc.cost_center_id");
                            $stmt->execute();
                            $result_cost_centers = $stmt->get_result();

                            echo "<option value=''>Select Cost Center</option>";
                            while ($cost_center = $result_cost_centers->fetch_assoc()) {
                                echo "<option value='{$cost_center['cost_center_id']}'>{$cost_center['cost_center_name']} - {$cost_center['location']}</option>";
                            }

                            $stmt->close();
                            ?>
                        </select>
                        <span class="error">*<br>
                            <?php echo $costCenterIdErr; ?>
                        </span>
                    </td>
                </tr>


                <tr>
                    <td><label for="is_filled">Is Filled:</label></td>
                    <td><input type="radio" id="is_filled_yes" name="is_filled" value="1" required>
                        <label for="is_filled_yes">Yes</label>

                        <input type="radio" id="is_filled_no" name="is_filled" value="0" required>
                        <label for="is_filled_no">No</label>
                        <span class="error">*<br>
                            <?php echo $isFilledErr; ?>
                        </span>
                    </td>
                </tr>




            </table><br>
            <button type="submit" name="add_position">Add Position</button>
        </form>
    </div>
</main>

<footer>&copy; 2023-2024 Kelsey Rajher. All Rights Reserved.</footer>
</div>
<script>
    $(document).ready(function () {
        $("#addPositionForm").validate({

            errorPlacement: function (error, element) {
                error.appendTo(element.parent()); // Append error message to the parent of the input element
            },
            rules: {
                job_id: {
                    required: true,
                },
                cost_center_id: {
                    required: true,
                },
                is_filled: {
                    required: true,
                },

            },


        });
    });

</script>

</body>


</html>
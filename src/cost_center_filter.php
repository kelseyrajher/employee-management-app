<?php
// Include database connection file
include '../config/db.php';

// Using prepared statement for the SELECT query
$stmtCostCenterFilter = $conn->prepare("SELECT DISTINCT cost_center_name FROM CostCenter");
$stmtCostCenterFilter->execute();
$resultCostCenterFilter = $stmtCostCenterFilter->get_result();

// Check for query execution success
if ($resultCostCenterFilter) {
    while ($rowCostCenter = $resultCostCenterFilter->fetch_assoc()) {
        $costCenter = $rowCostCenter['cost_center_name'];

        $selected = isset($_GET['openRolesCostCenterFilter']) && $_GET['openRolesCostCenterFilter'] === $costCenter ? 'selected'
            : '';

        // Echo the sanitized output
        echo "<option value=\"" . htmlspecialchars($costCenter, ENT_QUOTES, 'UTF-8') . "\" $selected>$costCenter</option>";
    }
} else {
    //Log error
    error_log("Error in fetching cost centers: " . $stmtCostCenterFilter->error);
    // Display error message to user
    echo "An error occurred while fetching cost centers.";
}

// Close the prepared statement
$stmtCostCenterFilter->close();

// Close database connection
$conn->close();
?>
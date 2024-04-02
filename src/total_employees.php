<?php
// Include database connection file
include '../config/db.php';

// Count total employees 
$totalEmployeesQuery = "SELECT COUNT(*) AS total_employees FROM incumbents";
$stmtTotalEmployees = $conn->prepare($totalEmployeesQuery);
$stmtTotalEmployees->execute();
$resultTotalEmployees = $stmtTotalEmployees->get_result();

// Check for error in executing query for total employees
if (!$resultTotalEmployees) {
    // Log Error
    error_log("Error in fetching total employees: " . $stmtTotalEmployees->error);
    // Display error message to user
    echo "Error occurred while fetching total employees.";

} else {

    // Check for a successful query for total employees
    if ($resultTotalEmployees) {
        $rowTotalEmployees = $resultTotalEmployees->fetch_assoc();
        $totalEmployees = $rowTotalEmployees['total_employees'];


        echo '<div id="metric">' . htmlspecialchars($totalEmployees, ENT_QUOTES, 'UTF-8') . '</div>';
    } else {
        // Handle case where there are no employees in the table
        echo "<p>No employees found in the table.</p>";
    }
}

// Close the prepared statement
$stmtTotalEmployees->close();

// Close the database connection
$conn->close();

?>
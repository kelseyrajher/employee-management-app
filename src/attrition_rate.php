<?php
// Include database connection file
include '../config/db.php';

// Count total employees 
$totalEmployeesQuery = "SELECT COUNT(*) AS total_employees FROM incumbents";
$stmtTotalEmployees = $conn->prepare($totalEmployeesQuery);
$stmtTotalEmployees->execute();
$resultTotalEmployees = $stmtTotalEmployees->get_result();

// Check for a successful query for total employees
if ($resultTotalEmployees) {
    $rowTotalEmployees = $resultTotalEmployees->fetch_assoc();
    $totalEmployees = $rowTotalEmployees['total_employees'];
} else {
    // Log error
    error_log("Error in total employees query: " . $stmtTotalEmployees->error);
    // Display error message to user
    echo "Error occurred while fetching total employees.";
}

$stmtTotalEmployees->close();

// Count terminated employees 
$terminatedEmployeesQuery = "SELECT COUNT(*) AS terminated_employees FROM incumbents WHERE end_date IS NOT NULL";
$stmtTerminatedEmployees = $conn->prepare($terminatedEmployeesQuery);
$stmtTerminatedEmployees->execute();
$resultTerminatedEmployees = $stmtTerminatedEmployees->get_result();

// Check for a successful query for terminated employees
if ($resultTerminatedEmployees) {
    $rowTerminatedEmployees = $resultTerminatedEmployees->fetch_assoc();
    $terminatedEmployees = $rowTerminatedEmployees['terminated_employees'];
} else {
    // Log error
    error_log("Error in terminated employees query: " . $stmtTerminatedEmployees->error);
    // Display error message to user
    echo "Error occurred while fetching terminated employees.";
}

// Check if total employees is greater than zero before calculating attrition rate
if ($totalEmployees > 0) {
    // Calculate attrition rate
    $attritionRate = ($terminatedEmployees / $totalEmployees) * 100;

    // Format the attrition rate to display two decimal places
    $formattedAttritionRate = number_format($attritionRate, 2);

    // Display results on web page and sanitize output
    echo '<div id="metric">' . htmlspecialchars($formattedAttritionRate . '%', ENT_QUOTES, 'UTF-8') . '</div>';
} else {
    // Display error message if no employees found on table
    echo "No employees found in the table.";
}

// Close the prepared statement
$stmtTerminatedEmployees->close();

// Close the database connection
$conn->close();
?>
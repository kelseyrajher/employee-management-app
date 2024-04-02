<?php
// Include database connection file
include '../config/db.php';

// Query to fetch all employees and their start and end dates
$stmtAllEmployees = $conn->prepare("SELECT * FROM incumbents");
$stmtAllEmployees->execute();
$resultAllEmployees = $stmtAllEmployees->get_result();

if (!$resultAllEmployees) {
    // Log error
    error_log("Error in fetching all employeees: " . $stmtAllEmployees->error);
    // Display error message to user
    echo "Error occurred while fetching all employees.";

} else {

    // Otherwise if query successful, initialize variables
    $totalDays = 0;
    $allEmployeesCount = 0;

    // Loop through each employee fetched from the database
    while ($employee = $resultAllEmployees->fetch_assoc()) {
        // Convert the start date of the employee to a DateTime object
        $startDate = new DateTime($employee['start_date']);

        // Check if the employee has an end date
        if ($employee['end_date'] !== null) {
            // If the employee has an end date, convert it to a DateTime object
            $endDate = new DateTime($employee['end_date']);
        } else {
            // If the employee does not have an end date, use the current date as the end date (for active employees)
            $endDate = new DateTime();
        }

        // Calculate the tenure interval between the start and end dates
        $tenureInterval = $startDate->diff($endDate);

        // Add the number of days in the tenure interval to the total days
        $totalDays += $tenureInterval->days;

        // Increment the count of all employees
        $allEmployeesCount++;
    }

    // Calculate average tenure
    if ($allEmployeesCount > 0) {
        // Calculate average in years with decimal places
        $averageYears = $totalDays / ($allEmployeesCount * 365);

        // Display results on web page and sanitize output
        echo '<div id="metric">' . htmlspecialchars(number_format($averageYears, 1) . ' yrs', ENT_QUOTES, 'UTF-8') . '</div>';
    } else {
        // Handle case where there are no employees found
        echo "<p>No employees found to calculate average tenure.</p>";
    }
}
// Close the prepared statement
$stmtAllEmployees->close();

// Close the database connection
$conn->close();
?>
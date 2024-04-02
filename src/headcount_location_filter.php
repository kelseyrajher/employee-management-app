<?php
// Include database connection file
include '../config/db.php';

// Using prepared statement for the SELECT query
$stmtLocationFilter = $conn->prepare("SELECT DISTINCT location FROM CostCenter");
$stmtLocationFilter->execute();
$resultLocationFilter = $stmtLocationFilter->get_result();


// Check for query execution success
if ($resultLocationFilter) {
        while ($rowLocation = $resultLocationFilter->fetch_assoc()) {
                $location = $rowLocation['location'];

                $selected = isset($_GET['locationFilter']) && $_GET['locationFilter'] === $location ? 'selected' : '';

                // Echo the sanitized output
                echo "<option value=\"" . htmlspecialchars($location, ENT_QUOTES, 'UTF-8') . "\" $selected>$location</option>";
        }
} else {
        //Log error
        error_log("Error in fetching cost centers: " . $stmtLocationFilter->error);
        // Display error message to user
        echo "An error occurred while fetching cost centers.";
}
// Close the prepared statement
$stmtLocationFilter->close();

// Close database connection
$conn->close();
?>
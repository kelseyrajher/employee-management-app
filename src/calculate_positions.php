<?php
// Include database connection file
include '../config/db.php';

// Query to fetch the total count of open positions
$stmtOpenPositions = $conn->prepare("SELECT COUNT(*) AS total_open_positions FROM Position WHERE is_filled = ?");
$isFilled = 0;
$stmtOpenPositions->bind_param("i", $isFilled);
$stmtOpenPositions->execute();
$resultOpenPositions = $stmtOpenPositions->get_result();

// Check for a successful query for open positions
if ($resultOpenPositions) {
    $rowOpenPositions = $resultOpenPositions->fetch_assoc();
    $totalOpenPositions = $rowOpenPositions['total_open_positions'];

    // Display results on web page and sanitize output
    echo '<div id="metric">' . htmlspecialchars($totalOpenPositions, ENT_QUOTES, 'UTF-8') . '</div>';
} else {
    // Log error
    error_log("Error in open positions query: " . $stmtOpenPositions->error);
    // Display error message to user
    echo "Error occurred while fetching open positions.";
}

// Close the prepared statement
$stmtOpenPositions->close();

// Close the database connection
$conn->close();
?>
<?php
// Start the session
session_start();

// Include database connection file
include '../config/db.php';

// Check if the request method is GET and if the 'cost_center_id' parameter is set
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['cost_center_id'])) {

    // Check the role of the logged-in user from the session
    // Ensure that only users with the 'admin' role have permission to delete cost centers
    if ($_SESSION["role"] !== "admin") {

        // If user's role is not admin, redirect to permissions error message page
        header("Location: ../public/permissions_error_message.php");
        exit;

    } else {

        $cost_center_id = $_GET['cost_center_id'];

        // Delete the role from the database
        $stmtDeleteCostCenter = $conn->prepare("DELETE FROM costcenter WHERE cost_center_id = ?");
        $stmtDeleteCostCenter->bind_param("i", $cost_center_id);

        if ($stmtDeleteCostCenter->execute()) {
            // Redirect back to the same page to display the updated cost centers
            header("Location: ../public/costcenter_management.php");
            exit();
        } else {
            // Log error
            error_log("Error in delete cost center query: " . $stmtDeleteCostCenter->error);
            // Display error message to user
            echo "An error occurred while deleting the cost center. Please try again later.";
        }

        // Close the prepared statement
        $stmtDeleteCostCenter->close();

        // Close the database connection
        $conn->close();
    }
}
?>
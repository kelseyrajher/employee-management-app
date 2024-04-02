<?php
// Start the session
session_start();

// Include database connection file
include '../config/db.php';

// Check if the request method is GET and if the 'role_id' parameter is set
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['role_id'])) {

    // Check the role of the logged-in user from the session
    // Ensure that only users with the 'admin' role have permission to delete roles
    if ($_SESSION["role"] !== "admin") {

        // If user's role is not admin, redirect to permissions error message page
        header("Location: ../public/permissions_error_message.php");
        exit;

    } else {

        $role_id = $_GET['role_id'];

        // Delete the role from the database
        $stmtDeleteRole = $conn->prepare("DELETE FROM roles WHERE role_id = ?");
        $stmtDeleteRole->bind_param("i", $role_id);

        if ($stmtDeleteRole->execute()) {
            // Redirect back to the same page to display the updated roles
            header("Location: ../public/role_management.php");
            exit();
        } else {
            // Log error
            error_log("Error in delete role query: " . $stmtDeleteRole->error);
            // Display error message to user
            echo "An error occurred while deleting the role. Please try again later.";
        }

        // Close the prepared statement
        $stmtDeleteRole->close();

        // Close the database connection
        $conn->close();
    }
}
?>
<?php
session_set_cookie_params([
    'secure' => true,   // Ensure cookies are only sent over HTTPS
    'httponly' => true  // Prevent cookies from being accessed with JS
]);

session_start();

// Check if the user is logged in 
if (!isset($_SESSION["email"])) {
    // Redirect the user back to the login page if login was unsuccessful
    header("Location: ../index.php");
    exit;
}

// Connect to database
include 'db.php';

// Retrieve the user's first name and last name from the database
$email = $_SESSION["email"];
$sql = "SELECT first_name, last_name FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    // Log error message
    error_log("Database error: " . $conn->error);
    // Display message to user
    die("Database error: Please try again later.");
}

$stmt->bind_param("s", $email);

if (!$stmt->execute()) {
    // Log error message
    error_log("Execution failed: " . $stmt->error);
    // Display message to user
    die("Execution failed: Please try again later.");
}

$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();
    $first_name = $row["first_name"];
    $last_name = $row["last_name"];
} else {
    // Handling the case where user's name is not found
    $first_name = "Unknown";
    $last_name = "User";
}

$stmt->close();
$result->close();

?>
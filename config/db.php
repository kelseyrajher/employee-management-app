<?php
// Database connection
$servername = "servername";
$username = "username";
$password = "password";
$dbname = "databasename";

// Creating the connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Checking if connection was successfull
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
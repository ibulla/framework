<?php
// Database configuration
$servername = "localhost"; // Replace with your MySQL server hostname
$username = ""; // Replace with your MySQL username
$password = ""; // Replace with your MySQL password
$database = ""; // Replace with your MySQL database name

// Create a connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set character set to utf8 (if needed)
$conn->set_charset("utf8");

// You can now use $conn to interact with the database
?>
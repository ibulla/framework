<?php
header('Content-Type: text/html; charset=UTF-8');
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

include("includes/db.php"); // Include the database connection script
include("includes/functions.php");


// Get the "title" parameter from the rewritten URL
if (isset($_GET['title'])) {
    $title = $_GET['title'];
} else {
    header("Location: https://" . $_SERVER['HTTP_HOST'] . "/i/works/home");
    exit;
}
// Sanitize the title (assuming it contains only alphanumeric and hyphen characters)
$cleanTitle = preg_replace("/[^a-zA-Z0-9-]/", "", $title);
// Database connection settings
$mysqli = new mysqli($servername, $username, $password, $database);
// Check the connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
// Prepare the SQL statement with a placeholder for user input
$query = "SELECT resource_id FROM url_mapping WHERE title = ?";
$stmt = $mysqli->prepare($query);

if ($stmt) {
    $stmt->bind_param("s", $cleanTitle);
    $stmt->execute();
    $stmt->bind_result($resourceId);
    $stmt->fetch();
    $stmt->close();

    if ($resourceId !== null) {
        // A match was found; use $resourceId to fetch and display content
        // Example: Fetch content using $resourceId
       echo generateBlogPage($resourceId,$servername, $username, $password, $database);
    } else {
        // Handle the case where no match was found (404 or custom error message)
        //http_response_code(404); // Set a 404 HTTP response code
        echo generateBlogHello($servername, $username, $password, $database);
    }
} else {
    // Handle errors with prepared statement
    echo "Error: " . $mysqli->error;
}

// Close the database connection
$mysqli->close();
?>

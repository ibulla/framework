<?php
session_start(); // Start a session to manage user login status

// Check if the administrator is logged in
if (!isset($_SESSION["admin_username"])) {
    // Redirect to the login page if not logged in
    header("Location: https://" . $_SERVER['HTTP_HOST'] . "/framework/admin/admin_login.php");
    exit(); // Stop script execution
}

include("../includes/db.php"); // Include the database connection script

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $title = $_POST["title"];
    $image_url = $_POST["image_url"];
    $description = htmlspecialchars($_POST["description"]);
    $blogID = $_POST["blog_id"];
    $position = $_POST["position"];
    $online = $_POST["online"];

    // Sanitize and validate the data as needed

    // Insert the new card into the database (replace with your INSERT query)
    $insert_query = "INSERT INTO cards (title, image_url, description,blog_id,position,online) VALUES ('$title', '$image_url', '$description','$blogID', '$position', '$online')";

    if ($conn->query($insert_query) === TRUE) {
    // Card created successfully
    $response = array("success" => true);
} else {
    // Error creating the card
    $response = array("success" => false, "error" => "Error: " . $conn->error);
}

header('Content-Type: application/json');
echo json_encode($response);
}
?>

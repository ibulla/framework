<?php

session_start(); // Start a session to manage user login status

// Check if the administrator is logged in
if (!isset($_SESSION["admin_username"])) {
    // Redirect to the login page if not logged in
    header("Location: admin_login.php");
    exit(); // Stop script execution
}

include("../includes/db.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get form data
    $cardId = $_POST["card_id"];
    $newTitle = $_POST["title"];
    $newImageURL = $_POST["image_url"];
    $newDescription = $_POST["description"];
    $newBlogID = $_POST["blog_id"];
    $newPosition = $_POST["position"];
    $newOnline = $_POST["online"];

    // Implement the logic to update the card in the database
    // Replace 'cards' with your actual table name
    $updateQuery = "UPDATE cards SET title = ?, image_url = ?, description = ?, blog_id = ?, position = ?, online = ? WHERE id = ?";

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("sssiiii", $newTitle, $newImageURL, $newDescription,$newBlogID,$newPosition,$newOnline, $cardId);

    if ($stmt->execute()) {
        // Card updated successfully
        $response = array("success" => true);
    } else {
        // Error updating the card
        $response = array("success" => false, "error" => "Error: " . $stmt->error);
    }

    // Close the prepared statement
    $stmt->close();

    // Send a JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    // Invalid request method
    http_response_code(400); // Bad Request
    echo json_encode(array("success" => false, "error" => "Invalid request method"));
}
?>


<?php

session_start(); // Start a session to manage user login status

// Check if the administrator is logged in
if (!isset($_SESSION["admin_username"])) {
    // Redirect to the login page if not logged in
    header("Location: admin_login.php");
    exit(); // Stop script execution
}

include("../includes/db.php"); // Include the database connection script

if ($_SERVER["REQUEST_METHOD"] === "DELETE") {
    // Get the card ID from the query parameters
    $cardId = $_GET["id"];

    // Get the image URL from the query parameters
    $imageURL = "../assets/img/".$_GET["image_url"];

    // Delete the card from the database (modify this based on your database structure)
    $deleteCardQuery = "DELETE FROM uploaded_images WHERE image_id = ?";
    
    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare($deleteCardQuery);
    $stmt->bind_param("i", $cardId);

    if ($stmt->execute()) {
        // Card deleted successfully from the database

        // Delete the associated image from the server
        if (file_exists($imageURL) && unlink($imageURL)) {
            // Image deleted successfully
            $response = array(
                'success' => true,
                'message' => 'Card and associated image deleted successfully.'
            );
        } else {
            // Error in deleting the image
            $response = array(
                'success' => false,
                'error' => 'Error deleting the image file.'
            );
        }
    } else {
        // Error in deleting the card from the database
        $response = array(
            'success' => false,
            'error' => 'Error deleting the card from the database.'
        );
    }

    $stmt->close();
} else {
    // Invalid request method
    $response = array(
        'success' => false,
        'error' => 'Invalid request method.'
    );
}

// Return the JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>

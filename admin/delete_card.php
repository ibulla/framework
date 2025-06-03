<?php

session_start(); // Start a session to manage user login status

// Check if the administrator is logged in
if (!isset($_SESSION["admin_username"])) {
    // Redirect to the login page if not logged in
    header("Location: https://" . $_SERVER['HTTP_HOST'] . "/framework/admin/admin_login.php");
    exit(); // Stop script execution
}

include("../includes/db.php"); // Include the database connection script

// Query the database to retrieve card entries (replace with your query)
$sql = "SELECT * FROM cards";
$result = $conn->query($sql);


// Include your database connection script
include("../includes/db.php");

if ($_SERVER["REQUEST_METHOD"] === "DELETE") {
    // Get the card ID from the URL
    $cardId = $_GET["id"];

    // Implement the logic to delete the card from the database
    // Replace 'cards' with your actual table name
    $deleteQuery = "DELETE FROM cards WHERE id = $cardId";

    if ($conn->query($deleteQuery) === TRUE) {
        // Card deleted successfully
        $response = array("success" => true);
    } else {
        // Error deleting the card
        $response = array("success" => false, "error" => "Error: " . $conn->error);
    }

    // Send a JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    // Invalid request method
    http_response_code(400); // Bad Request
    echo json_encode(array("success" => false, "error" => "Invalid request method"));
}
?>

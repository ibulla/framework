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
    // Get the ID from the AJAX request
    $id = $_POST["id"];

    // Check if the ID exists in the "url_mapping" table
    $urlMappingQuery = "SELECT * FROM url_mapping WHERE resource_id = ?";
    $urlMappingStmt = $conn->prepare($urlMappingQuery);
    $urlMappingStmt->bind_param("i", $id);
    $urlMappingStmt->execute();
    $urlMappingResult = $urlMappingStmt->get_result();

    // Check if the ID exists in the "blog_posts" table
    $blogPostsQuery = "SELECT * FROM blog_posts WHERE id = ?";
    $blogPostsStmt = $conn->prepare($blogPostsQuery);
    $blogPostsStmt->bind_param("i", $id);
    $blogPostsStmt->execute();
    $blogPostsResult = $blogPostsStmt->get_result();

    // Initialize response variables
    $urlMappingDeleted = false;
    $blogPostsDeleted = false;

    // Check and delete from the "url_mapping" table
    if ($urlMappingResult->num_rows > 0) {
        $urlMappingDeleteQuery = "DELETE FROM url_mapping WHERE resource_id = ?";
        $urlMappingStmt = $conn->prepare($urlMappingDeleteQuery);
        $urlMappingStmt->bind_param("i", $id);
        if ($urlMappingStmt->execute()) {
            $urlMappingDeleted = true;
        }
    }

    // Check and delete from the "blog_posts" table
    if ($blogPostsResult->num_rows > 0) {
        $blogPostsDeleteQuery = "DELETE FROM blog_posts WHERE id = ?";
        $blogPostsStmt = $conn->prepare($blogPostsDeleteQuery);
        $blogPostsStmt->bind_param("i", $id);
        if ($blogPostsStmt->execute()) {
            $blogPostsDeleted = true;
        }
    }

    // Prepare the response based on deletion results
    if ($urlMappingDeleted || $blogPostsDeleted) {
        $response = array("success" => true, "message" => "Blog post deleted successfully");
    } else {
        $response = array("success" => false, "message" => "Blog post not found or couldn't be deleted");
    }

    // Send the response as JSON
    header('Content-Type: application/json');
    echo json_encode($response);
}

?>

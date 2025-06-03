<?php
session_start(); // Start a session to manage user login status

// Check if the administrator is logged in
if (!isset($_SESSION["admin_username"])) {
    // Redirect to the login page if not logged in
    header("Location: https://" . $_SERVER['HTTP_HOST'] . "/framework/admin/admin_login.php");
    exit(); // Stop script execution
}

function formatTitle($title) {
    // Convert the title to lowercase
    $lowercaseTitle = trim(strtolower($title)); 
    // Replace whitespace with hyphens
    $formattedTitle = str_replace(' ', '-', $lowercaseTitle);   
    return $formattedTitle;
}

include("../includes/db.php"); // Include the database connection script

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $id = $_POST["id"];
    $title = $_POST["title"];
    $description = $_POST["description"];

    // Prepare an SQL UPDATE query with placeholders
    $update_query = "UPDATE blog_posts SET title = ?, content = ? WHERE id = ?";
    $stmt = $conn->prepare($update_query);

    if ($stmt) {
        // Bind parameters
        $stmt->bind_param("ssi", $title, $description, $id);

        // Execute the statement
        if ($stmt->execute()) {
            // Post updated successfully
            // Update the title in the "url_mapping" table
            $titel_formatiert = formatTitle($title);
            $update_url_mapping_query = "UPDATE url_mapping SET title = '$titel_formatiert' WHERE resource_id = $id";
            if ($conn->query($update_url_mapping_query) === TRUE) {
            $response = array("id"=> $id,"success" => true, "message" => "Post and URL mapping updated successfully");
            }else{
            $response = array("id"=> $id,"success" => false, "message" => "Error updating url_mapping: " . $conn->error);
            }
        } else {
            // Error executing the statement
            $response = array("id"=> $id,"success" => false, "message" => "Error updating post: " . $stmt->error);
        }
        // Close the statement
        $stmt->close();
    } else {
        // Error preparing the statement
        $response = array("id"=> $id,"success" => false, "message" => "Error preparing statement: " . $conn->error);
    }

    // Redirect to the same page with the response as a query parameter
    header("Location: https://" . $_SERVER['HTTP_HOST'] . "/i/admin/manage_blog_posts_update.php?" . http_build_query($response));
}
?>

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
    $title = $_POST["title"];
    $description = $_POST["description"];

    $insert_query = "INSERT INTO blog_posts (title, content) VALUES (?, ?)";
    $stmt = $conn->prepare($insert_query);

    if ($stmt) {
        // Bind parameters
        $stmt->bind_param("ss", $title, $description);

        // Execute the statement
        if ($stmt->execute()) {

        $last_insert_id = $conn->insert_id;
        $titel_formatiert =  formatTitle($title);
        // Perform another INSERT into the "url_mapping" table
        $url_mapping_query = "INSERT INTO url_mapping (title, resource_id) VALUES ('$titel_formatiert', '$last_insert_id')";
        
        if ($conn->query($url_mapping_query) === TRUE) {
            $response = array("id"=> $last_insert_id,"success" => true, "message" => "Post and URL mapping insert successfully");
            }else{
            $response = array("id"=> $last_insert_id,"success" => false, "message" => "Error inserting url_mapping: " . $conn->error);
            }
        } else {
            // Error executing the statement
            $response = array("id"=> $last_insert_id,"success" => false, "message" => "Error updating post: " . $stmt->error);
        }
        // Close the statement
        $stmt->close();
    } else {
        // Error preparing the statement
        $response = array("id"=> $last_insert_id,"success" => false, "message" => "Error preparing statement: " . $conn->error);
    }

    // Redirect to the same page with the response as a query parameter
    header("Location: https://" . $_SERVER['HTTP_HOST'] . "/i/admin/manage_blog_posts_update.php?" . http_build_query($response));
}

?>

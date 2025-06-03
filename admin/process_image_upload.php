<?php
session_start(); // Start a session to manage user login status

// Check if the administrator is logged in
if (!isset($_SESSION["admin_username"])) {
    // Redirect to the login page if not logged in
    header("Location: https://" . $_SERVER['HTTP_HOST'] . "/framework/admin/admin_login.php");
    exit(); // Stop script execution
}

include("../includes/db.php");


// Check if the form has been submitted
if (isset($_FILES['imageFile'])) {
    // Handle the image upload

    // Retrieve user input (imageTitle and imageDescription)
    $imageTitle = $_POST['imageTitle'];
    $imageDescription = $_POST['imageDescription'];

    // Validate and sanitize user input as needed

    // Check if the file is an actual image and perform other validations as before

    // Get the file extension
    $imageFileType = strtolower(pathinfo($_FILES['imageFile']['name'], PATHINFO_EXTENSION));

    // Define the upload directory
    $uploadDirectory = "../assets/img/";

    // Rename the uploaded file with the provided imageTitle
    $newFileName = $uploadDirectory . $imageTitle . '.' . $imageFileType;

    $insertFileName = $imageTitle . '.' . $imageFileType;

    // Check if the file with the same name already exists
    if (file_exists($newFileName)) {
        // Handle the error or redirect with a message
    }

    // Upload the file with the new name
    if (move_uploaded_file($_FILES['imageFile']['tmp_name'], $newFileName)) {
        // File uploaded successfully

        // Insert image information into the database
        $query = "INSERT INTO uploaded_images (image_filename, image_description) VALUES (?, ?)";
        
        // Use prepared statements to prevent SQL injection
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $insertFileName, $imageDescription);

        if ($stmt->execute()) {
            // Image information inserted successfully

            // Return a JSON success response
            $response = array(
                'success' => true,
                'message' => 'Image uploaded and information inserted successfully.'
            );
        } else {
            // Error in database insertion
            // Handle the error or redirect with a message
            $response = array(
                'success' => false,
                'message' => 'Error inserting image information into the database.'
            );
        }

        $stmt->close();
    } else {
        // Error in file upload
        // Handle the error or redirect with a message
        $response = array(
            'success' => false,
            'message' => 'Error uploading the image file.'
        );
    }
} else {
    // Form not submitted, return an error response
    $response = array(
        'success' => false,
        'message' => 'Form not submitted or invalid request.'
    );
}

// Return the JSON response
header('Content-Type: application/json');
echo json_encode($response);

?>

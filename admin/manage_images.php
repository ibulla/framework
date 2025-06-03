<?php
session_start(); // Start a session to manage user login status

// Check if the administrator is logged in
if (!isset($_SESSION["admin_username"])) {
    // Redirect to the login page if not logged in
    header("Location: https://" . $_SERVER['HTTP_HOST'] . "/framework/admin/admin_login.php");
    exit(); // Stop script execution
}

include("../includes/db.php");

// Check if the administrator is logged in
if (!isset($_SESSION["admin_username"])) {
    // Redirect to the login page if not logged in
    header("Location: https://" . $_SERVER['HTTP_HOST'] . "/framework/admin/admin_login.php");
    exit(); // Stop script execution
}

// Query to retrieve image data from the database
$query = "SELECT * FROM uploaded_images ORDER BY image_id DESC";
$result = mysqli_query($conn, $query);

$Bild_Ausgabe = "";
// Check if there are any images
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $Bild_Ausgabe.= "<tr>";
        $Bild_Ausgabe.= "<td><img height='80px' src='../assets/img/{$row['image_filename']}' alt='Image'><br>".$_SERVER['HTTP_HOST']."/i/assets/img/{$row['image_filename']}</td>";
        $Bild_Ausgabe.= "<td>{$row['image_filename']}</td>";
        $Bild_Ausgabe.= "<td>{$row['image_description']}</td>";
        $Bild_Ausgabe.= "<td>{$row['upload_date']}</td>";
        $Bild_Ausgabe .= "<td><button class='btn btn-danger btn-sm delete-card-button' ";
        $Bild_Ausgabe .= "onclick='confirmDelete({$row['image_id']}, \"{$row['image_filename']}\")'>Delete</button></td>";
        $Bild_Ausgabe.= "</tr>";
    }
} else {
    $Bild_Ausgabe.= "<tr><td colspan='3'>No images found.</td></tr>";
}

// Close the database connection
mysqli_close($conn);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Images</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="admin_dashboard.php">Admin Dashboard</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="admin_dashboard.php">HOME</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="manage_cards.php">Manage Cards</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="manage_blog_posts.php">Manage Blogs</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="manage_images.php">Manage Images</a>
            </li>
            <!-- Add more menu items as needed -->
        </ul>
    </div>
</nav>

    <div class="container mt-3">

        <!-- Image Upload Form -->
        <h2>Upload New Image</h2>
        <form id="imageUploadForm" action="process_image_upload.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="imageFile">Select Image:</label>
                <input type="file" class="form-control-file" id="imageFile" name="imageFile" accept="image/*" required>
            </div>
            <div class="form-group">
                <label for="imageTitle">Title:</label>
                <textarea class="form-control" id="imageTitle" name="imageTitle" required></textarea>
            </div>
            <div class="form-group">
                <label for="imageDescription">Description:</label>
                <textarea class="form-control" id="imageDescription" name="imageDescription"></textarea>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Upload Image</button>
            </div>
        </form>
    </div>

    <div class="container mt-5">
<!-- Image List Table -->
        <h2>Uploaded Images</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>File</th>
                    <th>Desc.</th>
                    <th>Upload</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php echo $Bild_Ausgabe; ?>
            </tbody>
        </table>

    </div>

    <!-- Include Bootstrap JS and jQuery (if needed) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<script type="text/javascript">

function confirmDelete(cardId,imageURL) {
    if (confirm("Are you sure you want to delete card # " + cardId + " : " + imageURL)) {
        // User confirmed, send an AJAX request to delete_card.php
        fetch("delete_image.php?id=" + cardId + "&image_url=" + imageURL, {
            method: "DELETE", // Use DELETE HTTP method
        })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                // Card deleted successfully
                alert("Card deleted successfully!");
                window.location.reload(); // Refresh the page
            } else {
                // Handle errors
                alert("Error: " + data.error);
            }
        })
        .catch((error) => {
            console.error("Error:", error);
        });
    }
}


// Function to handle image upload via AJAX
function uploadImage() {
    // Prevent the default form submission
    event.preventDefault();

    // Create a FormData object to send form data
    const formData = new FormData(document.getElementById('imageUploadForm'));

    // Send the AJAX request
    fetch('process_image_upload.php', {
        method: 'POST',
        body: formData,
    })
    .then(response => response.json())
    .then(data => {
        // Handle the JSON response
        if (data.success) {
            // Image uploaded successfully
            //alert(data.message);
            window.location.reload();
            // You can optionally reload the image list or take other actions
        } else {
            // Error in upload
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

// Add an event listener to the form for submission
document.getElementById('imageUploadForm').addEventListener('submit', uploadImage);
</script>

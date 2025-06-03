<?php
session_start(); // Start a session to manage user login status

// Check if the administrator is logged in
if (!isset($_SESSION["admin_username"])) {
    // Redirect to the login page if not logged in
    header("Location: https://" . $_SERVER['HTTP_HOST'] . "/framework/admin/admin_login.php");
    exit(); // Stop script execution
}

include("../includes/db.php"); // Include the database connection script
include("../includes/functions.php");

                    $query = "SELECT image_filename FROM uploaded_images";
                    $result = mysqli_query($conn, $query);
$create_option_list = "";
    // Loop through the query results and generate options
    while ($row = mysqli_fetch_assoc($result)) {
     $create_option_list.= "<option value='" . htmlspecialchars($row['image_filename']) . "'>" . htmlspecialchars($row['image_filename']) . "</option>";
            }


// Query the database to retrieve card entries (replace with your query)
$sql = "SELECT * FROM cards ORDER BY position DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Cards</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Include Bootstrap CSS from a CDN -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
    /* Style for the "Update" and "Delete" buttons in the table */
    .update-card-button,
    .delete-card-button {
        width: 100px; /* Adjust the width as needed */
    }
</style>
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
                <a class="nav-link active" href="#">Manage Cards</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="manage_blog_posts.php">Manage Blogs</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="manage_images.php">Manage Images</a>
            </li>
            <!-- Add more menu items as needed -->
        </ul>
    </div>
</nav>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-4">
                <!-- Card Creation Form -->
                <div class="card">
                    <div class="card-header">
                        <h2>Create a New Card</h2>
                    </div>
                    <div class="card-body">
                        <!-- Add your card creation form here -->
                        <!-- Example: Title, Image URL, Description, Submit button -->
                        <form id="createCardForm" method="post">
                            <div class="form-group">
                                <label id="new_title">Title:</label>
                                <input type="text" class="form-control" name="title" required>
                            </div>
                            <div class="form-group">
                                <label id="new_image_url">Image URL:</label>
                                <!--<input type="text" class="form-control" name="image_url" required>-->
                        <select class="form-control" id="newImageURL" name="image_url" required>
                        <option value="" disabled selected>Select an image</option>
                        <?php echo $create_option_list; ?>
                        </select>
                            </div>
                            <div class="form-group">
                                <label id="new_description">Description:</label>
                                <textarea class="form-control" name="description" rows="4" required></textarea>
                            </div>
                            <div class="form-group">
                                <label id="new_blog_id">Blog ID:</label>
                                <input type="text" class="form-control" name="blog_id">
                            </div>
                            <div class="form-group">
                                <label id="new_position">Position JAHR:</label>
                                <input type="text" class="form-control" name="position">
                            </div>
                            <div class="form-group">
                                <label id="new_online">Online 0/1</label>
                                <input type="text" class="form-control" name="online">
                            </div>
                            <button type="submit" class="btn btn-primary">Create Card</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <!-- List of Existing Cards -->
                        <h2>Existing Cards</h2>
                 
                        <?php while ($row = $result->fetch_assoc()): ?>

        <div class="card mb-3">
            <img src="../assets/img/<?php echo htmlspecialchars($row["image_url"]); ?>" class="card-img-top" alt="Card Image">
            <div class="card-body">
                <h5 class="card-title"><?php echo ($row["position"]); ?> | <?php echo htmlspecialchars($row["title"]); ?></h5>
                <p class="card-text"><?php echo htmlspecialchars($row["description"]); ?></p>
                <div class="btn-group" role="group">
                    <button class="btn btn-primary btn-sm update-card-button"
            data-card-id="<?php echo $row['id']; ?>"
            data-card-title="<?php echo htmlspecialchars($row['title']); ?>"
            data-card-image-url="<?php echo htmlspecialchars($row['image_url']); ?>"
            data-card-description="<?php echo htmlspecialchars($row['description']); ?>"
            data-card-blogID="<?php echo htmlspecialchars($row['blog_id']); ?>"
            data-card-position="<?php echo htmlspecialchars($row['position']); ?>"
            data-card-online="<?php echo htmlspecialchars($row['online']); ?>"
            onclick="openUpdateModal(this)">
            Update</button>
            <button class="btn btn-danger btn-sm delete-card-button" onclick="confirmDelete(<?php echo $row['id'];?>)">Delete</button>
                </div>
            </div>
        </div>

                        <?php endwhile; ?>
            </div>

            <div class="col-md-4">
                 <?php echo generateBlogListe(0,$servername, $username, $password, $database); ?>
            </div>

        </div>
    </div>


<!-- Update Card Modal -->
<div class="modal fade" id="updateCardModal" tabindex="-1" role="dialog" aria-labelledby="updateCardModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateCardModalLabel">Update Card</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Update card form -->
                <form id="updateCardForm">
                    <input type="hidden" id="updateCardId" name="card_id" value="">
                    <div class="form-group">
                        <label for="updateTitle">Title:</label>
                        <input type="text" class="form-control" id="updateTitle" name="title" required>
                    </div>
                    <div class="form-group">
                        <label for="updateImageURL">Image URL:</label>
                        <!--<input type="text" class="form-control" id="updateImageURL" name="image_url" required>-->
                        <select class="form-control" id="updateImageURL" name="image_url" required>
                        <?php echo $create_option_list; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="updateDescription">Description:</label>
                        <textarea class="form-control" id="updateDescription" name="description" rows="4" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="updateBlogID">BlogID:</label>
                        <input type="text" class="form-control" id="updateBlogID" name="blog_id">
                    </div>
                    <div class="form-group">
                        <label for="updatePosition">Position JAHR:</label>
                        <input type="text" class="form-control" id="updatePosition" name="position">
                    </div>
                    <div class="form-group">
                        <label for="updateOnline">online:</label>
                        <input type="text" class="form-control" id="updateOnline" name="online">
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="submitUpdateForm()">Save Changes</button>
            </div>
        </div>
    </div>
</div>


    <!-- Include Bootstrap JavaScript from a CDN -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const createCardForm = document.getElementById("createCardForm");

    createCardForm.addEventListener("submit", function (event) {
        event.preventDefault(); // Prevent the default form submission

        // Get form data
        const formData = new FormData(createCardForm);

        // Send the form data via AJAX
        fetch("create_card.php", {
            method: "POST",
            body: formData,
        })
        .then((response) => response.json()) // Assuming you'll return JSON response from process_create_card.php
        .then((data) => {
            if (data.success) {
                // Card created successfully, you can show a success message or redirect to the management page
                //alert("Card created successfully!");
                window.location.href = "manage_cards.php";
            } else {
                // Handle any errors from the server
                alert("Error: " + data.error);
            }
        })
        .catch((error) => {
            console.error("Error:", error);
        });
    });
});

function confirmDelete(cardId) {
    if (confirm("Are you sure you want to delete card # " + cardId)) {
        // User confirmed, send an AJAX request to delete_card.php
        fetch("delete_card.php?id=" + cardId, {
            method: "DELETE", // Use DELETE HTTP method
        })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                // Card deleted successfully
                //alert("Card deleted successfully!");
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


// Function to open the update modal and pre-fill form fields
function openUpdateModal(cardButton) {
    // Extract card details from data attributes
    const cardId = cardButton.getAttribute("data-card-id");
    const title = cardButton.getAttribute("data-card-title");
    const imageURL = cardButton.getAttribute("data-card-image-url");
    const description = cardButton.getAttribute("data-card-description");
    const blogID = cardButton.getAttribute("data-card-blogID");
    const position = cardButton.getAttribute("data-card-position");
    const online = cardButton.getAttribute("data-card-online");

    // Set values in the update form fields
    document.getElementById("updateCardId").value = cardId;
    document.getElementById("updateTitle").value = title;
    //document.getElementById("updateImageURL").value = imageURL;
    document.getElementById("updateDescription").value = description;
    document.getElementById("updateBlogID").value = blogID;
    document.getElementById("updatePosition").value = position;
    document.getElementById("updateOnline").value = online;

    const selectElement = document.getElementById("updateImageURL");
    const optionElement = document.createElement("option");
    optionElement.value = imageURL;
    optionElement.text = imageURL; // You can use the title as the displayed text
    selectElement.insertBefore(optionElement, selectElement.firstChild);
    optionElement.selected = true;

    // Show the update modal
    $('#updateCardModal').modal('show');
}

// Function to submit the update form via AJAX
function submitUpdateForm() {
    // Get form data
    const formData = new FormData(document.getElementById("updateCardForm"));
console.log("Sending AJAX request...");
    // Send the form data via AJAX to process_update_card.php
    fetch("process_update_card.php", {
        method: "POST",
        body: formData,
    })
    .then((response) => response.json())
    .then((data) => {
        if (data.success) {
            // Card updated successfully
            alert("Card updated successfully!");

            // Use jQuery to hide the modal
            $('#updateCardModal').modal('hide');

            // Delay the page refresh to ensure the modal is hidden before reloading
            setTimeout(function () {
                window.location.reload(); // Refresh the page
            }, 500); // Adjust the delay as needed
        } else {
            // Handle errors
            alert("Error: " + data.error);
        }
    })
    .catch((error) => {
        console.error("Error:", error);
    });
}
</script>



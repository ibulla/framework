<?php
session_start(); // Start a session to manage user login status

// Check if the administrator is logged in
if (!isset($_SESSION["admin_username"])) {
    // Redirect to the login page if not logged in
    header("Location: admin_login.php");
    exit(); // Stop script execution
}

include("../includes/db.php"); // Include the database connection script
include("../includes/functions.php");

$message = "";
$alertClass = "";

if (isset($_GET['success'])) {
    $success = intval($_GET['success']); // Convert to an integer (1 or 0)
    if ($success === 1) {
        // Success case
        $message = $_GET['message'];
        $alertClass = "alert-success";
    } elseif ($success === 0) {
        // Error case
        $message = $_GET['message'];
        $alertClass = "alert-danger";
    }
}

// Initialize variables for the form fields.
$title = "";
$content = "";

$deeplink = "";

// Get the ID from the GET request.
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare a query to retrieve the blog post based on the ID.
    $sql = "SELECT title, content FROM blog_posts WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    // Execute the query.
    if ($stmt->execute()) {
        $stmt->bind_result($title, $content);
        // Fetch the result.
        if ($stmt->fetch()) {
            // Data retrieved successfully.
            $testlink = generateDeepLink($id, $servername, $username, $password, $database);
            if (!empty($testlink)) {
            $deeplink = $testlink;
            } else {
            $deeplink = "No LINK found";
            }
        } else {
            // No matching post found.
            $error_handling = "Post not found.";
            $deeplink = "No LINK found";
        }
    } else {
        // Error executing the query.
        $error_handling = "Database error.";
        $deeplink = "No LINK found";
    }
    // Close the statement.
    $stmt->close();
} else {
    // ID not provided in the GET request.
    $error_handling = "ID not provided.";
    $deeplink = "No LINK found";
}

// Close the database connection.
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Blog Posts</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Include Bootstrap CSS from a CDN -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="tinymce/js/tinymce/tinymce.min.js"></script>
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
                <a class="nav-link" href="manage_cards.php">Manage Cards</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="manage_blog_posts.php">Manage Blogs</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="manage_images.php">Manage Images</a>
            </li>
            <!-- Add more menu items as needed -->
        </ul>
    </div>
</nav>

<div class="container mt-3">
<?php
// Display the message as a Bootstrap alert if it's not empty.
if (!empty($message)) {
    echo '<div class="alert ' . $alertClass . ' alert-dismissible fade show" role="alert">
            ' . $message . '
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
          </div>';
}
?>
</div>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <!-- Card Creation Form -->
                <div class="card">
                    <div class="card-header">
                        <h2>Update Blog Post # <?php echo $id; ?></h2>
                    </div>
                    <div class="card-body">
                        <p>LINK ZUM BEITRAG: <a target='_new' href='<?php echo $deeplink; ?>'><?php echo $deeplink; ?></a></p>
                        <!-- Add your card creation form here -->
                        <!-- Example: Title, Image URL, Description, Submit button -->
                        <form id="UpdateBlogPost" action='process_update_blog.php' method="post">
                                <input for="id" type="hidden" id="id" name="id" value="<?php echo $id; ?>" readonly>
                            <div class="form-group">
                                <label>Title:</label>
                                <input id="new_title" type="text" name="title" value="<?php echo $title; ?>">
                            </div>
                            <div class="form-group">
                                <label>Inhalt:</label>
                                <textarea id="new_description" name="description" rows="10"><?php echo $content; ?></textarea>
                            </div>
                            <input type='submit' value='SPEICHERN' name='eintragen'>
                        </form>
                    </div>
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
tinymce.init({
  selector:'textarea',
  height : '600',
  plugins: [
    'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
    'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
    'insertdatetime', 'media', 'table', 'help', 'wordcount', 'codesample','code',
  ],
  menubar: 'file edit view insert format table help',
  toolbar: "image hr aligncenter alignjustify alignleft alignright| anchor | blockquote blocks | backcolor | bold | fontfamily fontsize forecolor h1 h2 h3 h4 h5 h6 indent | italic | language | lineheight | newdocument | outdent | paste pastetext | print | redo | remove removeformat | selectall | strikethrough | styles | subscript superscript underline | undo | visualaid | a11ycheck advtablerownumbering typopgraphy anchor restoredraft casechange charmap checklist code codesample addcomment showcomments ltr rtl editimage fliph flipv imageoptions rotateleft rotateright emoticons export footnotes footnotesupdate formatpainter fullscreen help image insertdatetime link openlink unlink bullist numlist media mergetags mergetags_list nonbreaking pagebreak pageembed permanentpen preview quickimage quicklink quicktable cancel save searchreplace spellcheckdialog spellchecker | table tablecellprops tablecopyrow tablecutrow tabledelete tabledeletecol tabledeleterow tableinsertdialog tableinsertcolafter tableinsertcolbefore tableinsertrowafter tableinsertrowbefore tablemergecells tablepasterowafter tablepasterowbefore tableprops tablerowprops tablesplitcells tableclass tablecellclass tablecellvalign tablecellborderwidth tablecellborderstyle tablecaption tablecellbackgroundcolor tablecellbordercolor tablerowheader tablecolheader | tableofcontents tableofcontentsupdate | template typography | insertfile | visualblocks visualchars",
  images_file_types: 'jpg,jpeg,png,svg,webp',
  images_upload_url: 'postAcceptor.php',
  image_class_list: [{ title: 'Responsive Image', value: 'img-fluid' }],
});


$('#UpdateBlogPost').on('submit', function (event) {
    
    // Get the TinyMCE content.
    var tiny = tinymce.get();
    var content = tiny.getContent();

     // Prevent the default form submission behavior.
    event.preventDefault();

    // Get the values from the form fields.
    var title = $("#new_title").val();
    var id = $("#id").val();

    // Create an object with the form data.
    var formData = {
        id: id,
        title: title,
        description: content,
    };

    // Send an AJAX request to the server.
 $.ajax({
    type: $(this).attr('method'),  // Get the HTTP method from the form.
    url: $(this).attr('action'),    // Get the URL from the form's action attribute.
    data: formData,                // Send the form data.
    success: function (response) {
        if (response.error) {
            // If there's an error, redirect to the same page with an error message.
            window.location.href = "/manage_blog_posts_update.php?error=" + encodeURIComponent(response.error);
        } else {
            console.log("Success: ", response);
            // If the update was successful, redirect to the same page with a success message.
            window.location.href = "/manage_blog_posts_update.php?success=Update%20successful"; // You can customize the success message.
        }
    },
    error: function (xhr, status, error) {
        // This function is executed if there is an error with the AJAX request.
        console.error("AJAX Error:", status, error);
    }
});

});


</script>

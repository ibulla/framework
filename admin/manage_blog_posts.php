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

// Query the database to retrieve card entries (replace with your query)
$sql = "SELECT * FROM cards";
$result = $conn->query($sql);
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

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <!-- Card Creation Form -->
                <div class="card">
                    <div class="card-header">
                        <h2>Create a New Blog Post</h2>
                    </div>
                    <div class="card-body">
                        <!-- Add your card creation form here -->
                        <!-- Example: Title, Image URL, Description, Submit button -->
                        <form id="createCardForm" action='create_blog.php' method="post">
                            <div class="form-group">
                                <label>Title:</label>
                                <input id="new_title" type="text" name="title">
                            </div>
                            <div class="form-group">
                                <label>Inhalt:</label>
                                <textarea id="new_description" name="description" rows="10"></textarea>
                            </div>
                            <input type='submit' value='SPEICHERN' name='eintragen'>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


<div class="container mt-5">
        <div class="row">
            <div class="col-md-6">

    <?php echo generateBlogListe(1,$servername, $username, $password, $database); ?>

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


$('#createCardForm').on('submit', function(event) {
    var tiny = tinymce.get();
    var content = tiny.getContent();
    var Titel = $("#new_title").val();

    var formData = {
        title: Titel,
        description: content,
    };
    $.ajax({
        type: $(this).attr('method'),
        url: $(this).attr('action'),
        data: formData,
        success: function (antwort) {
            alert(antwort.error);
        }
    });

    event.preventDefault();
});

$(document).ready(function() {
        $(".delete-btn").click(function() {
            var id = $(this).data("id");

            // Send an AJAX request to the "process_blog_delete" script
            $.ajax({
                type: "POST",
                url: "delete_blog.php",
                data: { id: id },
                success: function(response) {
                    // Handle the response (e.g., show a success message or perform further actions)
                    console.log(response);
                    if (response.success) {
                       // Reload the page on success
                       location.reload();
                   }
                },
                error: function(xhr, status, error) {
                    // Handle errors (e.g., show an error message)
                    console.error("AJAX Error:", status, error);
                }
            });
        });
    });

</script>

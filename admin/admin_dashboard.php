<?php
session_start(); // Start a session to manage user login status

// Check if the administrator is logged in
if (!isset($_SESSION["admin_username"])) {
    // Redirect to the login page if not logged in
    header("Location: https://" . $_SERVER['HTTP_HOST'] . "/framework/admin/admin_login.php");
    exit(); // Stop script execution
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
     <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Include Bootstrap CSS from a CDN -->
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
                <a class="nav-link active" href="#">HOME</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="manage_cards.php">Manage Cards</a>
            </li>
             <li class="nav-item">
                <a class="nav-link" href="manage_images.php">Manage Card Images</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="manage_blog_posts.php">Manage Blogs</a>
            </li>
            <!-- Add more menu items as needed -->
        </ul>
    </div>
</nav>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-header">
                        <h2>Welcome to the Admin Dashboard</h2>
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item">
                                <a href="manage_cards.php">Manage Cards</a>
                            </li>
                            <li class="list-group-item">
                                <a href="manage_images.php">Manage Card Images</a>
                            </li>
                            <li class="list-group-item">
                                <a href="manage_blog_posts.php">Manage Blog Posts</a>
                            </li>
                            <li class="list-group-item">
                                <a href="set_psw.php">+SET PASSWORD+</a>
                            </li>
                            <!-- Add more links/buttons for other admin tasks as needed -->
                        </ul>
                    </div>
                    <div class="card-footer">
                        <a class="btn btn-danger" href="logout.php">Logout</a>
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

<?php
session_start(); // Start a session to manage user login status
include("../includes/db.php"); // Include the database connection script

$error_message = ""; // Initialize the error message

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Query the database to retrieve the hashed password
    $sql = "SELECT password FROM admin_users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashed_password = $row["password"];

        // Verify the entered password against the stored hashed password
        if (password_verify($password, $hashed_password)) {
            // Password is correct
            $_SESSION["admin_username"] = $username;
            header("Location: https://" . $_SERVER['HTTP_HOST'] . "/framework/admin/admin_dashboard.php"); // Redirect to the admin dashboard
            exit();
        } else {
            // Password is incorrect
            $error_message = "Invalid username or password";
        }
    } else {
        // User not found
        $error_message = "User not found";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Include Bootstrap CSS from a CDN -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-4 offset-md-4">
                <div class="card">
                    <div class="card-header">
                        <h2 class="text-center">Admin Login</h2>
                    </div>
                    <div class="card-body">
                        <form action="admin_login.php" method="post">
                            <div class="form-group">
                                <label for="username">Username:</label>
                                <input type="text" class="form-control" name="username" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password:</label>
                                <input type="password" class="form-control" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Login</button>
                        </form>
                    </div>
                    <div class="card-footer">
                        <!-- Display the error message if it's not empty -->
                        <?php if (!empty($error_message)): ?>
                            <p class="text-danger text-center"><?php echo $error_message; ?></p>
                        <?php endif; ?>
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
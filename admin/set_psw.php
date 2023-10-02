<?php

session_start(); // Start a session to manage user login status

// Check if the administrator is logged in
if (!isset($_SESSION["admin_username"])) {
    // Redirect to the login page if not logged in
    header("Location: admin_login.php");
    exit(); // Stop script execution
}

include("../includes/db.php"); // Include the database connection script

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $new_username = $_POST["username"];
    $new_password = $_POST["password"];

    // Check if the password is empty
    if (empty($new_password)) {
        echo "Password cannot be empty.";
    } else {
    // Check if the username already exists in the database
    $check_query = "SELECT id FROM admin_users WHERE username = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("s", $new_username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $error_message = "Username already exists. Choose a different username.";
    } else {
        // Hash the new password using password_hash()
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Insert the new user into the database (replace with your insert query)
        $insert_query = "INSERT INTO admin_users (username, password) VALUES (?, ?)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("ss", $new_username, $hashed_password);

        if ($stmt->execute()) {
            $error_message = "User and password set up successfully.";
        } else {
            $error_message = "Error setting up user and password: " . $stmt->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Set Login</title>
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
                        <h2 class="text-center">SET NEW LOGIN</h2>
                    </div>
                    <div class="card-body">
                        <form action="set_psw.php" method="post">
                            <div class="form-group">
                                <label for="username">Username:</label>
                                <input type="text" class="form-control" name="username" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password:</label>
                                <input type="password" class="form-control" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Set Login</button>
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

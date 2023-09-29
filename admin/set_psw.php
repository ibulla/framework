<?php

session_start(); // Start a session to manage user login status

// Check if the administrator is logged in
if (!isset($_SESSION["admin_username"])) {
    // Redirect to the login page if not logged in
    header("Location: admin_login.php");
    exit(); // Stop script execution
}

include("../includes/db.php"); // Include the database connection script

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $new_password = $_POST["password"];

    // Hash the new password using password_hash()
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Update the administrator's password in the database (replace with your update query)
    $admin_username = "spitz"; // Replace with the administrator's username
    $update_query = "UPDATE admin_users SET password = '$hashed_password' WHERE username = '$admin_username'";

    if ($conn->query($update_query) === TRUE) {
        echo "Password set successfully.";
    } else {
        echo "Error setting password: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Set Password</title>
</head>
<body>
    <h2>Set Password</h2>
    <form action="set_psw.php" method="post">
        <label for="password">New Password:</label>
        <input type="password" name="password" required><br><br>

        <input type="submit" value="Set Password">
    </form>
</body>
</html>
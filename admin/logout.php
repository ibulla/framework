<?php
session_start(); // Start a session to manage user login status

// Destroy the session to log out the administrator
session_destroy();

// Redirect to the login page
header("Location: admin_login.php");
exit(); // Stop script execution
?>

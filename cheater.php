<?php
// Initialize the session
session_start();

// Store data in session variables
$_SESSION["loggedin"] = true;
$_SESSION["userId"] = 2;
$_SESSION["email"] = $email;
$_SESSION["user_code"] = 4;


// Redirect to login page
header("location: login.php");
exit;
?>

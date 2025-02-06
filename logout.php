<?php
// Start the session (required to access and destroy it)
session_start();

// Unset all session variables
$_SESSION = [];

// Destroy the session
session_destroy();

// Redirect the user back to the login page
header('Location: index.html'); // Change 'index.html' to your login page URL if different
exit; // Ensure no further code is executed after redirection
?>
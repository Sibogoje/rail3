<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION['userid']) || !isset($_SESSION['role'])) {
    // Redirect to the login page
    header("Location: index.php"); // Adjust the path if needed
    exit();
}

// Unset all session variables
session_unset();

// Destroy the session
session_destroy();

// Redirect to the login page
header("Location: ../../index.php"); // Adjust the path if needed
exit();
?>

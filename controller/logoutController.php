<?php
// Start the session
session_start();

// Unset session variables
session_unset();

// Destroy the session
session_destroy();

// If there's a remember me cookie, delete it by setting an expiry in the past
if (isset($_COOKIE['remember_me'])) {
    setcookie('remember_me', '', time() - 3600, '/'); // Expire the cookie
}

// Redirect the user to the login page (or home page)
header("Location: ../view/login.php"); // Adjust the location as needed
exit();
?>
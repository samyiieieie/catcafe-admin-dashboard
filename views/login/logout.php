<?php

// Start Session
session_start();

// Removes Variables set in Session
session_unset();

// Destroys the session on the server
session_destroy();

// Clear session cookie
setcookie(session_name(), '', time() - 3600, '/');

// Redirect to login
header("Location: login.php");
exit();
?>

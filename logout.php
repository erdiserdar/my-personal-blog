<?php
// Initialize the session.
session_start();

// Unset all of the session variables.
$_SESSION = array();

// Finally, destroy the session.
session_destroy();

// Redirect to the homepage after logging out.
header("Location: index.php");
exit;
?>

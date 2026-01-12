<?php
session_start();

// Unset all of the session variables
$_SESSION = array();

// If a session cookie exists, set its expiration to a time in the past
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destroy the session
session_destroy();

// Delete the persistent "Remember Me" cookie if it exists
if (isset($_COOKIE['email_cookie'])) {
    setcookie('email_cookie', '', time() - 3600, '/');
}

// Redirect to the homepage or login page
header('Location: log.php');
exit;
?>

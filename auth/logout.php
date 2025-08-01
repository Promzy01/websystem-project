<?php
session_start();
session_destroy();

// optially delete sessionss cookies:
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Redirect back to the home and homepage:
header("Location: ../index.php");
exit;
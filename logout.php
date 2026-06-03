<?php
if(session_status() === PHP_SESSION_NONE) session_start();

$redirectTo = 'Login.php';
if(isset($_SESSION['account_type']) && strtolower($_SESSION['account_type']) === 'admin'){
    $redirectTo = 'Login.php';
}

$_SESSION = [];
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
session_destroy();
header('Location: ' . $redirectTo);
exit();
?>
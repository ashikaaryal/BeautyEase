<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function require_login($role = null) {
    // Determine login path depending on current script location
    $isAdminArea = (strpos($_SERVER['PHP_SELF'], '/admin/') !== false);
    $loginPath = $isAdminArea ? '../login.php' : 'login.php';

    if (!isset($_SESSION['user_id'])) {
        header('Location: ' . $loginPath);
        exit();
    }

    if ($role === 'Admin') {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
            header('Location: ' . $loginPath);
            exit();
        }
    }
}

function force_logout_and_redirect($redirect = 'login.php') {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Unset all session variables
    $_SESSION = array();

    // Delete the session cookie
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params['path'], $params['domain'],
            $params['secure'], $params['httponly']
        );
    }

    // Destroy the session
    session_destroy();

    // Prevent caching of the logout page
    header("Cache-Control: no-cache, no-store, must-revalidate");
    header("Pragma: no-cache");
    header("Expires: 0");

    header('Location: ' . $redirect);
    exit();
}

?>

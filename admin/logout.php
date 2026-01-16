<?php
require_once __DIR__ . '/../includes/auth_check.php';
// Securely destroy session and redirect to login (admin area)
force_logout_and_redirect('../login.php');
?>
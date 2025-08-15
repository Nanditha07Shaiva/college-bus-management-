<?php
// includes/auth.php
if (session_status() === PHP_SESSION_NONE) session_start();

function is_logged_in() {
    return !empty($_SESSION['user']);
}
function current_user() {
    return $_SESSION['user'] ?? null;
}
function require_login() {
    if (!is_logged_in()) {
        header("Location: index.php?msg=Please+login");
        exit;
    }
}
function require_role($role) {
    require_login();
    $u = current_user();
    if ($u['role'] !== $role) {
        header("Location: home.php?msg=Access+denied");
        exit;
    }
}
?>

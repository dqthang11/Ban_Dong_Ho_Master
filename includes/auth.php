<?php
// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Check if user is admin
function isAdmin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin';
}

// Require login
function requireLogin() {
    if(!isLoggedIn()) {
        header('Location: index.php?controller=user&action=login');
        exit;
    }
}

// Require admin
function requireAdmin() {
    if(!isAdmin()) {
        header('Location: index.php');
        exit;
    }
}
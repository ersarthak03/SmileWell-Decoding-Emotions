<?php
// Secure session start
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start([
        'cookie_lifetime' => 86400,
        'cookie_secure' => isset($_SERVER['HTTPS']),
        'cookie_httponly' => true,
        'use_strict_mode' => true
    ]);
}

function validateSession() {
    return isset(
        $_SESSION['user_id'],
        $_SESSION['ip_address'],
        $_SESSION['user_agent'],
        $_SESSION['last_activity']
    ) && 
    $_SESSION['ip_address'] === $_SERVER['REMOTE_ADDR'] &&
    $_SESSION['user_agent'] === $_SERVER['HTTP_USER_AGENT'] &&
    time() - $_SESSION['last_activity'] < 3600; // 1 hour timeout
}

function isAdmin() {
    if (!validateSession()) return false;
    
    // Debug role check
    error_log("Role check: " . ($_SESSION['role'] ?? 'not set'));
    
    return ($_SESSION['role'] ?? '') === 'admin';
}

function requireAdmin() {
    if (!isAdmin()) {
        // Detailed debug output
        error_log("Admin access denied for user ID: " . ($_SESSION['user_id'] ?? 'unknown'));
        error_log("Session data: " . print_r($_SESSION, true));
        
        header("Location: /index.php?error=admin_required");
        exit();
    }
}
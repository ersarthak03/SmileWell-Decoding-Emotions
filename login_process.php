<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

// Database config
$host = 'localhost';
$db   = 'smilewell';
$user = 'heroku';
$pass = 'sarthak@123';

try {
    // Create connection
    $conn = new mysqli($host, $user, $pass, $db);
    if ($conn->connect_error) {
        throw new Exception("Database connection failed: " . $conn->connect_error);
    }

    // Validate input
    if (empty($_POST['username']) || empty($_POST['password'])) {
        throw new Exception("Username and password are required");
    }

    // Get form data
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Query database with role information
    $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }
    
    $stmt->bind_param("s", $username);
    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }
    
    $stmt->store_result();
    
    if ($stmt->num_rows === 0) {
        // Don't reveal whether username exists
        throw new Exception("Invalid credentials");
    }

    $stmt->bind_result($id, $db_username, $hashed_password, $role);
    $stmt->fetch();
    $stmt->close();

    // Verify password
    if (!password_verify($password, $hashed_password)) {
        throw new Exception("Invalid credentials");
    }

    // Regenerate session ID to prevent fixation
    session_regenerate_id(true);

    // Set session variables (only once)
    $_SESSION = [
        'user_id' => $id,
        'username' => $db_username,
        'role' => $role,
        'logged_in' => true,
        'last_activity' => time()
    ];

    // Debug output
    error_log("Login successful. User ID: $id, Role: $role");

    // Redirect based on role
    switch ($role) {
        case 'admin':
            $redirect = 'admin/dashboard.php';
            break;
        case 'moderator':
            $redirect = 'moderator.php';
            break;
        default:
            $redirect = 'index.php';
    }
    
    header("Location: $redirect");
    exit();

} catch (Exception $e) {
    // Store error message and preserve username
    $_SESSION['login_error'] = $e->getMessage();
    $_SESSION['login_username'] = $_POST['username'] ?? '';
    
    // Log the error
    error_log("Login failed: " . $e->getMessage());
    
    header("Location: login.php");
    exit();
} finally {
    if (isset($conn) && $conn) {
        $conn->close();
    }
}
<?php
session_start();
require_once 'db_connect.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['smile_score'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit();
}

$user_id = $_SESSION['user_id'];
$smile_score = intval($_POST['smile_score']);
$points_earned = 0;

// Calculate points based on smile score
if ($smile_score >= 80) {
    $points_earned = 50;
} elseif ($smile_score >= 60) {
    $points_earned = 30;
} elseif ($smile_score >= 40) {
    $points_earned = 15;
} else {
    $points_earned = 5;
}

try {
    $stmt = $pdo->prepare("UPDATE users SET points = points + ? WHERE id = ?");
    $stmt->execute([$points_earned, $user_id]);
    
    echo json_encode([
        'success' => true,
        'points' => $points_earned,
        'message' => 'Points updated successfully'
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?>
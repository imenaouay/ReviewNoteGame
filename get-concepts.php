<?php
require 'config.php';
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please log in to view concepts!']);
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    $stmt = $pdo->prepare("SELECT * FROM game_concepts WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$user_id]);
    $concepts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['success' => true, 'concepts' => $concepts]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'An error occurred while fetching concepts!']);
}
?>
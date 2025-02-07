<?php
require 'config.php';
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please log in to delete concepts!']);
    exit;
}

$user_id = $_SESSION['user_id'];
$concept_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($concept_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid concept ID!']);
    exit;
}

try {
    // Check if the concept belongs to the current user
    $stmt = $pdo->prepare("SELECT * FROM game_concepts WHERE id = ? AND user_id = ?");
    $stmt->execute([$concept_id, $user_id]);
    $concept = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$concept) {
        echo json_encode(['success' => false, 'message' => 'Concept not found or unauthorized access!']);
        exit;
    }

    // Delete the concept
    $stmt = $pdo->prepare("DELETE FROM game_concepts WHERE id = ? AND user_id = ?");
    $stmt->execute([$concept_id, $user_id]);

    // Optionally, delete the uploaded image file
    if ($concept['image_url'] && file_exists($concept['image_url'])) {
        unlink($concept['image_url']);
    }

    echo json_encode(['success' => true, 'message' => 'Concept deleted successfully!']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'An error occurred while deleting the concept!']);
}
?>
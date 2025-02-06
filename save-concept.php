<?php
require 'config.php';
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please log in to save concepts!']);
    exit;
}

$user_id = $_SESSION['user_id'];
$data = json_decode(file_get_contents('php://input'), true);

try {
    $stmt = $pdo->prepare("
        INSERT INTO game_concepts (
            user_id, title, game_overview, gameplay_mechanics, characters, 
            weapons_equipment, maps_environments, story_setting, monetization, 
            technical_specifications, image_url
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        $user_id,
        $data['title'],
        $data['gameOverview'] ?? '',
        $data['gameplayMechanics'] ?? '',
        $data['characters'] ?? '',
        $data['weaponsEquipment'] ?? '',
        $data['mapsEnvironments'] ?? '',
        $data['storySetting'] ?? '',
        $data['monetization'] ?? '',
        $data['technicalSpecifications'] ?? '',
        $data['imageUrl'] ?? ''
    ]);
    echo json_encode(['success' => true, 'message' => 'Concept saved successfully!']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'An error occurred while saving the concept!']);
}
?>
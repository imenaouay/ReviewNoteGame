<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    error_log("Received Signup Request: Name: $name, Email: $email");

    if (empty($name) || empty($email) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required!']);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Invalid email format!']);
        exit;
    }

    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    try {
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $hashed_password]);
        echo json_encode(['success' => true, 'message' => 'Signup successful!']);
    } catch (PDOException $e) {
        error_log("Signup Error: " . $e->getMessage());
        if ($e->getCode() == '23000') { // Violation de contrainte d'unicité
            echo json_encode(['success' => false, 'message' => 'Email already exists!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'An error occurred during signup!']);
        }
    }
}
?>

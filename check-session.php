<?php
session_start();

if (isset($_SESSION['user_id'])) {
    echo json_encode(['isLoggedIn' => true, 'name' => $_SESSION['name']]);
} else {
    echo json_encode(['isLoggedIn' => false]);
}
?>
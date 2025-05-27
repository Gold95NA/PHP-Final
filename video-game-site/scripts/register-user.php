<?php
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
    try {
        $stmt->execute([$username, $email, $password]);
        header("Location: ../pages/login.php?success=1");
        exit;
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}
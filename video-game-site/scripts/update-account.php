<?php
require_once('../includes/db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../pages/login.php");
    exit;
}

$userId = $_SESSION['user_id'];
$username = trim($_POST['username']);
$email = trim($_POST['email']);
$password = $_POST['password'];

try {
    if (!empty($password)) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ?, password_hash = ? WHERE id = ?");
        $stmt->execute([$username, $email, $hashed, $userId]);
    } else {
        $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
        $stmt->execute([$username, $email, $userId]);
    }

    $_SESSION['username'] = $username;

    header("Location: ../pages/account.php?updated=1");
    exit;
} catch (PDOException $e) {
    die("Update failed: " . $e->getMessage());
}
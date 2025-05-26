<?php
require_once('../includes/db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../pages/login.php");
    exit;
}

$userId = $_SESSION['user_id'];
$title = trim($_POST['title']);
$content = trim($_POST['content']);

$stmt = $pdo->prepare("INSERT INTO blogs (user_id, title, content) VALUES (?, ?, ?)");

try {
    $stmt->execute([$userId, $title, $content]);
    header("Location: ../pages/my-blogs.php?success=1");
    exit;
} catch (PDOException $e) {
    die("Error posting blog: " . $e->getMessage());
}
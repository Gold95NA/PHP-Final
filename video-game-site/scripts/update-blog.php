<?php
require_once('../includes/db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../pages/login.php");
    exit;
}

$userId = $_SESSION['user_id'];
$postId = $_POST['post_id'];
$title = trim($_POST['title']);
$content = trim($_POST['content']);

$stmt = $pdo->prepare("UPDATE blogs SET title = ?, content = ? WHERE id = ? AND user_id = ?");

try {
    $stmt->execute([$title, $content, $postId, $userId]);
    header("Location: ../pages/my-blogs.php");
    exit;
} catch (PDOException $e) {
    die("Update failed: " . $e->getMessage());
}
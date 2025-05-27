<?php
require_once('../includes/db.php');
session_start();

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: ../pages/login.php");
    exit;
}

$userId = $_SESSION['user_id'];
$postId = $_GET['id'];

$stmt = $pdo->prepare("DELETE FROM blogs WHERE id = ? AND user_id = ?");
$stmt->execute([$postId, $userId]);

header("Location: ../pages/my-blogs.php");
exit;
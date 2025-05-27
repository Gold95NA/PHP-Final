<?php
require_once('../includes/db.php');
session_start();

if (isset($_GET['id']) && isset($_SESSION['user_id'])) {
    $reviewId = $_GET['id'];
    $userId = $_SESSION['user_id'];

    $stmt = $pdo->prepare("DELETE FROM reviews WHERE id = ? AND user_id = ?");
    $stmt->execute([$reviewId, $userId]);

    header("Location: ../pages/my-reviews.php");
    exit;
}
?>
<?php
require_once('../includes/db.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $reviewId = $_POST['review_id'];
    $rating = $_POST['rating'];
    $reviewText = $_POST['review_text'];
    $userId = $_SESSION['user_id'];

    $stmt = $pdo->prepare("UPDATE reviews SET rating = ?, review_text = ? WHERE id = ? AND user_id = ?");
    $stmt->execute([$rating, $reviewText, $reviewId, $userId]);

    header("Location: ../pages/my-reviews.php");
    exit;
}
?>
<?php
require_once('../includes/db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../pages/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user_id'];
    $gameId = $_POST['game_id'];
    $gameTitle = $_POST['game_title'];
    $rating = $_POST['rating'];
    $reviewText = $_POST['review_text'];

    if (!$gameId || !$gameTitle || !$rating || !$reviewText) {
        die("All fields are required.");
    }

    $stmt = $pdo->prepare("INSERT INTO reviews (user_id, game_id, game_title, rating, review_text) VALUES (?, ?, ?, ?, ?)");
    
    try {
        $stmt->execute([$userId, $gameId, $gameTitle, $rating, $reviewText]);
        header("Location: ../pages/my-reviews.php?success=1");
        exit;
    } catch (PDOException $e) {
        die("Error saving review: " . $e->getMessage());
    }
}
?>
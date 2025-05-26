<?php
require_once('../includes/auth.php');
requireLogin();
include('../includes/db.php');
include('../includes/header.php');

$reviewId = $_GET['id'];
$userId = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT * FROM reviews WHERE id = ? AND user_id = ?");
$stmt->execute([$reviewId, $userId]);
$review = $stmt->fetch();

if (!$review) {
    echo "<p>Review not found or unauthorized.</p>";
    include('../includes/footer.php');
    exit;
}

$apiKey = '69f3eef59d5944b7aaa6af836d6a8691'; 
$gameApiUrl = "https://api.rawg.io/api/games/{$review['game_id']}?key=$apiKey";
$gameData = json_decode(file_get_contents($gameApiUrl), true);
$image = $gameData['background_image'] ?? '';
?>

<div class="form-container">
    <h2>Edit Your Review for <?= htmlspecialchars($review['game_title']) ?></h2>

    <?php if (!empty($image)): ?>
        <div class="game-preview-image">
            <img src="<?= $image ?>" alt="Game image">
        </div>
    <?php endif; ?>

    <form action="../scripts/update-review.php" method="POST">
        <input type="hidden" name="review_id" value="<?= $review['id'] ?>">

        <label for="rating">Rating (1–5):</label>
        <input type="number" name="rating" min="1" max="5" value="<?= $review['rating'] ?>" required>

        <label for="review">Your Review:</label>
        <textarea name="review" required><?= htmlspecialchars($review['review_text']) ?></textarea>

        <button type="submit" class="primary-button">Update Review</button>
    </form>
</div>


<?php include('../includes/footer.php'); ?>
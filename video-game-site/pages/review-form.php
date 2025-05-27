<?php
require_once('../includes/auth.php');
requireLogin();
include('../includes/header.php');

$gameId = isset($_GET['id']) ? $_GET['id'] : null;
$gameTitle = isset($_GET['title']) ? $_GET['title'] : null;

if (!$gameId || !$gameTitle) {
    echo "<p>Invalid game selection.</p>";
    include('../includes/footer.php');
    exit;
}

$apiKey = '69f3eef59d5944b7aaa6af836d6a8691'; 
$gameUrl = "https://api.rawg.io/api/games/$gameId?key=$apiKey";
$response = file_get_contents($gameUrl);
$gameData = json_decode($response, true);

$image = $gameData['background_image'] ?? '';
?>

<div class="form-container">
    <h2>Write a Review for <?= htmlspecialchars($_GET['title']) ?></h2>

    <?php if (!empty($image)): ?>
        <div class="game-preview-image">
            <img src="<?= $image ?>" alt="Game image">
        </div>
    <?php endif; ?>

    <form action="../scripts/submit-review.php" method="POST">
        <input type="hidden" name="game_id" value="<?= $_GET['id'] ?>">
        <input type="hidden" name="game_title" value="<?= htmlspecialchars($_GET['title']) ?>">

        <label for="rating">Rating (1–5):</label>
        <input type="number" name="rating" min="1" max="5" required>

        <label for="review">Your Review:</label>
        <textarea name="review" required></textarea>

        <button type="submit" class="primary-button">Submit Review</button>
    </form>
</div>

<?php include('../includes/footer.php'); ?>
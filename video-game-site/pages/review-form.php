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

<h2>Review: <?= htmlspecialchars($gameTitle) ?></h2>

<?php if ($image): ?>
    <img src="<?= $image ?>" alt="<?= htmlspecialchars($gameTitle) ?>" width="300"><br><br>
<?php endif; ?>

<form action="../scripts/submit-review.php" method="POST">
    <input type="hidden" name="game_id" value="<?= htmlspecialchars($gameId) ?>">
    <input type="hidden" name="game_title" value="<?= htmlspecialchars($gameTitle) ?>">

    <label for="rating">Rating (1–5):</label><br>
    <input type="number" name="rating" min="1" max="5" required><br><br>

    <label for="review_text">Your Review:</label><br>
    <textarea name="review_text" rows="6" cols="50" required></textarea><br><br>

    <button type="submit">Submit Review</button>
</form>

<?php include('../includes/footer.php'); ?>
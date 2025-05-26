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

<h2>Edit Review: <?= htmlspecialchars($review['game_title']) ?></h2>

<?php if ($image): ?>
    <img src="<?= $image ?>" alt="<?= htmlspecialchars($review['game_title']) ?>" width="300"><br><br>
<?php endif; ?>

<form action="../scripts/update-review.php" method="POST">
    <input type="hidden" name="review_id" value="<?= $review['id'] ?>">

    <label for="rating">Rating (1–5):</label><br>
    <input type="number" name="rating" min="1" max="5" required value="<?= $review['rating'] ?>"><br><br>

    <label for="review_text">Your Review:</label><br>
    <textarea name="review_text" rows="6" cols="50" required><?= htmlspecialchars($review['review_text']) ?></textarea><br><br>

    <button type="submit">Update Review</button>
</form>

<?php include('../includes/footer.php'); ?>
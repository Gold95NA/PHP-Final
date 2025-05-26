<?php
include('../includes/db.php');
include('../includes/header.php');

$apiKey = '69f3eef59d5944b7aaa6af836d6a8691';
$gameId = isset($_GET['id']) ? $_GET['id'] : null;

if (!$gameId) {
    echo "<p>Invalid game ID.</p>";
    include('../includes/footer.php');
    exit;
}

$gameUrl = "https://api.rawg.io/api/games/$gameId?key=$apiKey";
$response = file_get_contents($gameUrl);
$game = json_decode($response, true);

$stmt = $pdo->prepare("SELECT r.*, u.username FROM reviews r JOIN users u ON r.user_id = u.id WHERE r.game_id = ?");
$stmt->execute([$gameId]);
$reviews = $stmt->fetchAll();
?>

<h2><?= htmlspecialchars($game['name']) ?></h2>

<?php if (!empty($game['background_image'])): ?>
    <img src="<?= $game['background_image'] ?>" width="400"><br><br>
<?php endif; ?>

<?php if (isLoggedIn()): ?>
    <a href="review-form.php?id=<?= $game['id'] ?>&title=<?= urlencode($game['name']) ?>">
        <button>Review This Game</button>
    </a>
<?php endif; ?>

<p><strong>Released:</strong> <?= htmlspecialchars($game['released']) ?></p>
<p><strong>Genres:</strong>
    <?php
    $genres = array_map(fn($g) => $g['name'], $game['genres']);
    echo htmlspecialchars(implode(', ', $genres));
    ?>
</p>
<p><?= $game['description_raw'] ?? '' ?></p>

<hr>
<h3>User Reviews</h3>

<?php if (count($reviews) === 0): ?>
    <p>No reviews yet for this game.</p>
<?php else: ?>
    <ul>
        <?php foreach ($reviews as $review): ?>
            <li style="margin-bottom: 25px;">
                <strong><?= htmlspecialchars($review['username']) ?>:</strong>
                <span><?= $review['rating'] ?>/5</span><br>
                <p><?= nl2br(htmlspecialchars($review['review_text'])) ?></p>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<?php include('../includes/footer.php'); ?>
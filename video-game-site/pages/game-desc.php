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

<div class="game-details-card">
    <h2><?= htmlspecialchars($game['name']) ?></h2>

    <?php if (!empty($game['background_image'])): ?>
        <div style="display: flex; flex-direction: column; align-items: flex-start;">
            <img src="<?= $game['background_image'] ?>" alt="<?= htmlspecialchars($game['name']) ?>" style="width: 100%; max-width: 400px; border-radius: 10px;">
            <?php if (isLoggedIn()): ?>
                <a href="review-form.php?id=<?= $game['id'] ?>&title=<?= urlencode($game['name']) ?>">
                    <button class="primary-button" style="margin-top: 10px;">Review This Game</button>
                </a>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <p><strong class="category">Released:</strong> <?= htmlspecialchars($game['released']) ?></p>

    <p><strong class="category">Genres:</strong>
        <?php
        $genres = array_map(fn($g) => $g['name'], $game['genres']);
        echo htmlspecialchars(implode(', ', $genres));
        ?>
    </p>

    <p><?= htmlspecialchars($game['description_raw']) ?></p>
</div>

<div class="user-reviews-section">
    <h3>User Reviews</h3>

    <?php if (count($reviews) === 0): ?>
        <p>No reviews yet for this game.</p>
    <?php else: ?>
        <?php foreach ($reviews as $review): ?>
            <div class="review-card">
                <div class="review-content">
                    <h3>
                        <span class="author"><?= htmlspecialchars($review['username']) ?></span>
                        <span class="rating">(<?= $review['rating'] ?>/5)</span>
                    </h3>
                    <p><?= nl2br(htmlspecialchars($review['review_text'])) ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php include('../includes/footer.php'); ?>
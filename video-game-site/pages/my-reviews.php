<?php
require_once('../includes/auth.php');
requireLogin();
include('../includes/db.php');
include('../includes/header.php');

$userId = $_SESSION['user_id'];
$apiKey = '69f3eef59d5944b7aaa6af836d6a8691';

$stmt = $pdo->prepare("SELECT * FROM reviews WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$userId]);
$reviews = $stmt->fetchAll();
?>

<div class="search-section">
    <form id="game-search-form">
        <input type="text" id="search-query" placeholder="Search a game..." required>
        <button type="submit">Search</button>
    </form>
</div>

<div id="results"></div>

<h2>My Reviews</h2>

<?php if (isset($_GET['success'])): ?>
    <p style="color: green;">Review submitted successfully!</p>
<?php endif; ?>

<?php if (count($reviews) === 0): ?>
    <p>You haven't submitted any reviews yet.</p>
<?php else: ?>
    <ul>
        <?php foreach ($reviews as $review): ?>
            <?php

            $gameApiUrl = "https://api.rawg.io/api/games/{$review['game_id']}?key=$apiKey";
            $gameData = json_decode(file_get_contents($gameApiUrl), true);
            $image = $gameData['background_image'] ?? '';
            ?>
            <li style="margin-bottom: 30px;">
                <h3>
                    <a href="game-desc.php?id=<?= $review['game_id'] ?>">
                        <?= htmlspecialchars($review['game_title']) ?>
                    </a> (<?= $review['rating'] ?>/5)
                </h3>
                <?php if ($image): ?>
                    <img src="<?= $image ?>" alt="<?= htmlspecialchars($review['game_title']) ?>" width="250"><br>
                <?php endif; ?>
                <p><?= nl2br(htmlspecialchars($review['review_text'])) ?></p>

                <a href="edit-review.php?id=<?= $review['id'] ?>">Edit</a> |
                <a href="../scripts/delete-review.php?id=<?= $review['id'] ?>" onclick="return confirm('Are you sure you want to delete this review?');">Delete</a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<script>
document.getElementById('game-search-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const query = document.getElementById('search-query').value;

    fetch(`../api/fetch-game.php?q=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(data => {
            const results = document.getElementById('results');
            results.innerHTML = '';
            data.forEach(game => {
                const gameDiv = document.createElement('div');
                gameDiv.classList.add('game-card');
                gameDiv.innerHTML = `
                    <h3><a href="game-desc.php?id=${game.id}">${game.name}</a></h3>
                    <img src="${game.background_image}" width="180"><br>
                    <a href="review-form.php?id=${game.id}&title=${encodeURIComponent(game.name)}">Review this game</a>
                `;
                results.appendChild(gameDiv);
            });
        });
});
</script>

<?php include('../includes/footer.php'); ?>
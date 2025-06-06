<?php
include('../includes/db.php');
include('../includes/header.php');

$apiKey = '69f3eef59d5944b7aaa6af836d6a8691'; 

$stmt = $pdo->query("
    SELECT r.*, u.username
    FROM reviews r
    JOIN users u ON r.user_id = u.id
    ORDER BY r.created_at DESC
");
$reviews = $stmt->fetchAll();

?>

<h2 class="reviews-header">Reviews</h2>

<div class="search-section">
    <form id="game-search-form">
        <input type="text" id="search-query" placeholder="Search a game..." required>
        <button type="submit">Search</button>
    </form>
</div>

<br>

<div style="text-align: right; margin-bottom: 20px; margin-right: 30px;">
    <?php if (isLoggedIn()): ?>
        <a href="my-reviews.php"><button class="primary-button" >My Reviews</button></a>
    <?php endif; ?>
</div>

<div id="results"></div>
<button id="clear-results" class="primary-button" style="display:none; margin: 20px auto;">Clear Results</button>

<?php if (count($reviews) === 0): ?>
    <p>No reviews have been posted yet.</p>
<?php else: ?>
    <ul>
        <?php foreach ($reviews as $review): ?>
            <?php
            $gameApiUrl = "https://api.rawg.io/api/games/{$review['game_id']}?key=$apiKey";
            $gameData = json_decode(file_get_contents($gameApiUrl), true);
            $image = $gameData['background_image'] ?? '';
            ?>
            <div class="review-card">
                <div class="review-content">
                    <h3>
                        <a href="game-desc.php?id=<?= $review['game_id'] ?>">
                            <?= htmlspecialchars($review['game_title']) ?>
                        </a>
                        <span class="rating">(<?= $review['rating'] ?>/5)</span>
                    </h3>

                    <?php if (!empty($review['username'])): ?>
                        <p class="author">Reviewed by: <?= htmlspecialchars($review['username']) ?></p>
                    <?php endif; ?>

                    <p><?= nl2br(htmlspecialchars($review['review_text'])) ?></p>

                    <?php if (isLoggedIn() && isset($review['user_id']) && $review['user_id'] === $_SESSION['user_id']): ?>
                        <div class="review-actions">
                            <a href="edit-review.php?id=<?= $review['id'] ?>">Edit</a>
                            <a href="../scripts/delete-review.php?id=<?= $review['id'] ?>" onclick="return confirm('Are you sure you want to delete this review?')">Delete</a>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if (!empty($image)): ?>
                    <img src="<?= $image ?>" alt="<?= htmlspecialchars($review['game_title']) ?>">
                <?php endif; ?>
            </div>
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
                const clearBtn = document.getElementById('clear-results');
                results.innerHTML = '';
                data.forEach(game => {
                    const gameDiv = document.createElement('div');
                    gameDiv.classList.add('search-result-card');
                    gameDiv.innerHTML = `
                        <h3><a href="game-desc.php?id=${game.id}">${game.name}</a></h3>
                        <img src="${game.background_image}" width="180"><br>
                        <a href="review-form.php?id=${game.id}&title=${encodeURIComponent(game.name)}">Review this game</a>
                    `;
                    results.appendChild(gameDiv);
                });

                if (data.length > 0) {
                    clearBtn.style.display = 'block';
                }
            });
    });

    document.getElementById('clear-results').addEventListener('click', function() {
        document.getElementById('results').innerHTML = '';
        this.style.display = 'none';
    });
</script>

<?php include('../includes/footer.php'); ?>
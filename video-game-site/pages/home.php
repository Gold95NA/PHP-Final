<?php
require_once('../includes/auth.php');
requireLogin();
include('../includes/db.php');
include('../includes/header.php');

?>

<div class="search-section">
    <form id="game-search-form">
        <input type="text" id="search-query" placeholder="Search a game..." required>
        <button type="submit">Search</button>
    </form>
</div>

<br>

<?php if (isLoggedIn()): ?>
    <div style="text-align: right; margin-bottom: 20px; margin-right: 30px;">
        <a href="my-reviews.php"><button class="primary-button">My Reviews</button></a>
        <a href="my-blogs.php"><button class="primary-button">My Blogs</button></a>
    </div>
<?php endif; ?>

<div id="results"></div>
<button id="clear-results" class="primary-button" style="display:none; margin: 20px auto; display: block;">Clear Results</button>

<div class="content-columns">
    <div class="recent-reviews">
        <h2>Recent Reviews</h2>

        <?php
        $stmt = $pdo->query("
            SELECT r.*, u.username 
            FROM reviews r 
            JOIN users u ON r.user_id = u.id 
            ORDER BY r.created_at DESC 
            LIMIT 3
        ");
        $reviews = $stmt->fetchAll();
        $apiKey = '69f3eef59d5944b7aaa6af836d6a8691'; 

        foreach ($reviews as $review):
            $gameApiUrl = "https://api.rawg.io/api/games/{$review['game_id']}?key=$apiKey";
            $gameData = json_decode(file_get_contents($gameApiUrl), true);
            $image = $gameData['background_image'] ?? '';
        ?>
            <div class="game-card" style="margin-bottom: 20px;">
                <h3><a href="game-desc.php?id=<?= $review['game_id'] ?>">
                    <?= htmlspecialchars($review['game_title']) ?>
                </a></h3>

                <?php if ($image): ?>
                    <img src="<?= $image ?>" width="180"><br>
                <?php endif; ?>

                <strong>Rating:</strong> <?= $review['rating'] ?>/5<br>
                <p class="author"><strong>By:</strong> <?= htmlspecialchars($review['username']) ?></p>
                <a class="review-link" href="review-form.php?id=<?= $review['game_id'] ?>&title=<?= urlencode($review['game_title']) ?>">Review this game</a>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="recent-posts">
        <h2>Recent Posts</h2>

        <?php
        $stmt = $pdo->query("
            SELECT b.*, u.username 
            FROM blogs b 
            JOIN users u ON b.user_id = u.id 
            ORDER BY b.created_at DESC 
            LIMIT 5
        ");
        $posts = $stmt->fetchAll();

        foreach ($posts as $post):
        ?>
            <div class="post-preview" style="margin-bottom: 20px;">
                <p class="author"><strong><?= htmlspecialchars($post['username']) ?></strong></p>
                <h4><?= htmlspecialchars($post['title']) ?></h4>
                <p><?= nl2br(htmlspecialchars(substr($post['content'], 0, 100))) ?>...</p>
            </div>
        <?php endforeach; ?>
    </div>
</div>
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
<?php
require_once('../includes/auth.php');
include('../includes/db.php');
include('../includes/header.php');

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

if ($search) {
    $stmt = $pdo->prepare("
        SELECT b.*, u.username 
        FROM blogs b 
        JOIN users u ON b.user_id = u.id 
        WHERE b.title LIKE ? OR b.content LIKE ? OR u.username LIKE ?
        ORDER BY b.created_at DESC
    ");
    $stmt->execute(["%$search%", "%$search%", "%$search%"]);
} else {
    $stmt = $pdo->query("
        SELECT b.*, u.username 
        FROM blogs b 
        JOIN users u ON b.user_id = u.id 
        ORDER BY b.created_at DESC
    ");
}
$posts = $stmt->fetchAll();
?>

<h2 class="blog-header">Blog Posts</h2>

<div class="blog-search" style="text-align: center;">
    <form method="GET" action="blog.php" class="search-section">
        <input type="text" name="search" placeholder="Search blog posts..." class="form-input" value="<?= htmlspecialchars($search) ?>">
        <button type="submit" class="primary-button">Search</button>
    </form>
    <button type="button" id="clear-blog-search" class="primary-button" style="display: none; margin-top: 10px; ">Clear Results</button>
</div>

<br>

<?php if (isLoggedIn()): ?>
    <div style="text-align: right; margin-bottom: 20px; margin-right: 30px;">
        <a href="my-blogs.php"><button class="primary-button">My Blogs</button></a>
    </div>
<?php endif; ?>

<?php if (count($posts) === 0): ?>
    <p style="text-align: center; color: #da42cd;">No blog posts yet.</p>
<?php else: ?>
    <div class="post-list">
        <?php foreach ($posts as $post): ?>
            <div class="post-card">
                <p class="author">user: <?= htmlspecialchars($post['username']) ?></p>
                <h4><?= htmlspecialchars($post['title']) ?></h4>
                <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>

                <?php if (isLoggedIn() && $_SESSION['user_id'] == $post['user_id']): ?>
                    <div class="review-actions">
                        <a href="edit-blog.php?id=<?= $post['id'] ?>">Edit</a> |
                        <a href="../scripts/delete-blog.php?id=<?= $post['id'] ?>" onclick="return confirm('Delete this post?');">Delete</a>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>


<?php if (isLoggedIn()): ?>
    <div class="form-container center-card">
        <h2>Write a Blog Post</h2>

        <form action="../scripts/submit-blog.php" method="POST">
            <label for="title">Title:</label>
            <input type="text" name="title" class="form-input" required>

            <label for="content">Content:</label>
            <textarea name="content" rows="6" class="form-input" required></textarea>

            <button type="submit" class="primary-button">Post Blog</button>
        </form>
    </div>
    <br>
<?php endif; ?>

<script>
    const searchInput = document.querySelector('input[name="search"]');
    const clearBtn = document.getElementById('clear-blog-search');

    if (searchInput.value.trim() !== '') {
        clearBtn.style.display = 'inline-block';
    }

    clearBtn.addEventListener('click', () => {
        searchInput.value = '';
        window.location.href = window.location.pathname;
    });
</script>

<?php include('../includes/footer.php'); ?>
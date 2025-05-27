<?php
require_once('../includes/auth.php');
requireLogin();
include('../includes/db.php');
include('../includes/header.php');

$userId = $_SESSION['user_id'];
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

if ($search) {
    $stmt = $pdo->prepare("
        SELECT * FROM blogs 
        WHERE user_id = ? AND (title LIKE ? OR content LIKE ?) 
        ORDER BY created_at DESC
    ");
    $stmt->execute([$userId, "%$search%", "%$search%"]);
} else {
    $stmt = $pdo->prepare("SELECT * FROM blogs WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$userId]);
}

$posts = $stmt->fetchAll();
?>

<h2 style="text-align: center;">My Blog Posts</h2>

<?php if (isset($_GET['success'])): ?>
    <p style="color: #37b978;">Blog post submitted successfully!</p>
<?php endif; ?>

<div class="blog-search" style="text-align: center;">
    <form method="GET" action="my-blogs.php" class="search-section">
        <input type="text" name="search" placeholder="Search blog posts..." class="form-input" value="<?= htmlspecialchars($search) ?>">
        <button type="submit" class="primary-button">Search</button>
    </form>
    <button type="button" id="clear-blog-search" class="primary-button" style="display: none; margin-top: 10px;">Clear Results</button>
</div>
<br>

<?php if (count($posts) === 0): ?>
    <p style="text-align: center; color: #da42cd;">You haven't posted any blogs yet.</p>
<?php else: ?>
    <div class="post-list">
        <?php foreach ($posts as $post): ?>
            <div class="post-preview">
                <h4><?= htmlspecialchars($post['title']) ?></h4>
                <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>
                <div class="review-actions">
                    <a href="edit-blog.php?id=<?= $post['id'] ?>">Edit</a> |
                    <a href="../scripts/delete-blog.php?id=<?= $post['id'] ?>" onclick="return confirm('Are you sure you want to delete this post?');">Delete</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
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
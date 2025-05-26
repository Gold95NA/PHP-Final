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

<?php if (isLoggedIn()): ?>
    <a href="my-blogs.php"><button>My Blogs</button></a>
<?php endif; ?>


<?php if (isLoggedIn()): ?>
    <h2>Write a Blog Post</h2>

    <form action="../scripts/submit-blog.php" method="POST">
        <label for="title">Title:</label><br>
        <input type="text" name="title" required><br><br>

        <label for="content">Content:</label><br>
        <textarea name="content" rows="8" cols="60" required></textarea><br><br>

        <button type="submit">Post Blog</button>
    </form>

    <hr>
<?php endif; ?>

<h2>All Blog Posts</h2>

<form method="GET" action="blog.php">
    <input type="text" name="search" placeholder="Search blog posts..." value="<?= htmlspecialchars($search) ?>">
    <button type="submit">Search</button>
</form>
<br>

<?php if (count($posts) === 0): ?>
    <p>No blog posts yet.</p>
<?php else: ?>
    <ul>
        <?php foreach ($posts as $post): ?>
            <li style="margin-bottom: 30px;">
                <h3><?= htmlspecialchars($post['title']) ?></h3>
                <p class="author"><strong>By:</strong> <?= htmlspecialchars($post['username']) ?></p>
                <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>

                <?php if (isLoggedIn() && $_SESSION['user_id'] == $post['user_id']): ?>
                    <a href="edit-blog.php?id=<?= $post['id'] ?>">Edit</a> |
                    <a href="../scripts/delete-blog.php?id=<?= $post['id'] ?>" onclick="return confirm('Delete this post?');">Delete</a>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<?php include('../includes/footer.php'); ?>
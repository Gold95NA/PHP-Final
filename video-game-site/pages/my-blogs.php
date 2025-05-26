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

<h2>My Blog Posts</h2>

<?php if (isset($_GET['success'])): ?>
    <p style="color: green;">Blog post submitted successfully!</p>
<?php endif; ?>

<!-- Blog Search Form -->
<form method="GET" action="my-blogs.php">
    <input type="text" name="search" placeholder="Search your blog posts..." value="<?= htmlspecialchars($search) ?>">
    <button type="submit">Search</button>
</form>
<br>

<?php if (count($posts) === 0): ?>
    <p>You haven't posted any blogs yet.</p>
<?php else: ?>
    <ul>
        <?php foreach ($posts as $post): ?>
            <li style="margin-bottom: 25px;">
                <h3><?= htmlspecialchars($post['title']) ?></h3>
                <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>
                <a href="edit-blog.php?id=<?= $post['id'] ?>">Edit</a> |
                <a href="../scripts/delete-blog.php?id=<?= $post['id'] ?>" onclick="return confirm('Are you sure you want to delete this post?');">Delete</a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<?php include('../includes/footer.php'); ?>
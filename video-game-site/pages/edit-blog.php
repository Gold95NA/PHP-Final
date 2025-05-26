<?php
require_once('../includes/auth.php');
requireLogin();
include('../includes/db.php');
include('../includes/header.php');

$userId = $_SESSION['user_id'];
$postId = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM blogs WHERE id = ? AND user_id = ?");
$stmt->execute([$postId, $userId]);
$post = $stmt->fetch();

if (!$post) {
    echo "<p>Post not found or access denied.</p>";
    include('../includes/footer.php');
    exit;
}
?>

<h2>Edit Blog Post</h2>

<form action="../scripts/update-blog.php" method="POST">
    <input type="hidden" name="post_id" value="<?= $post['id'] ?>">

    <label for="title">Title:</label><br>
    <input type="text" name="title" value="<?= htmlspecialchars($post['title']) ?>" required><br><br>

    <label for="content">Content:</label><br>
    <textarea name="content" rows="8" cols="60" required><?= htmlspecialchars($post['content']) ?></textarea><br><br>

    <button type="submit">Update Blog</button>
</form>

<?php include('../includes/footer.php'); ?>
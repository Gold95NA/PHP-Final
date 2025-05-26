<?php
require_once('../includes/auth.php');
requireLogin();
include('../includes/header.php');
?>

<h2>My Account</h2>
<p class="author">Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!</p>

<a href="my-reviews.php">
    <button>View My Reviews</button>
</a>
<a href="edit-account.php">
    <button>Update My Account</button>
</a>
<a href="my-blogs.php">
    <button>View My Blogs</button>
</a>

<?php include('../includes/footer.php'); ?>
<?php
require_once('../includes/auth.php');
requireLogin();
include('../includes/header.php');
?>

<main class="page-container">
    <div class="account-box">
        <h2>My Account</h2>
        <p>Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!</p>

        <a href="edit-account.php"><button class="primary-button">Edit Account</button></a>
        <a href="my-reviews.php"><button class="primary-button">My Reviews</button></a>
        <a href="my-blogs.php"><button class="primary-button">My Blogs</button></a>
    </div>
</main>

<?php include('../includes/footer.php'); ?>

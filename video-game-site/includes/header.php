<?php
require_once('auth.php'); 
?>
<!DOCTYPE html>
<html>
<head>
    <title>The Pulse</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header>
        <div class="header-container">

            <div class="logo-card">
                <h1 class="site-title">The Pulse</h1>
                <p class="site-subtitle">Video Game Reviews</p>
            </div>
            
            <nav class="main-nav">
                <a href="home.php">Home</a>
                <a href="blog.php">Blog</a>
                <a href="reviews.php">Reviews</a>
                <?php if (isLoggedIn()): ?>
                    <a href="account.php">Account</a>
                    <a href="../scripts/logout.php">Logout</a>
                <?php else: ?>
                    <a href="login.php">Login</a>
                    <a href="register.php">Register</a>
                <?php endif; ?>
            </nav>
        </div>

        <?php
        $currentPage = basename($_SERVER['PHP_SELF']);
        $noBackPages = ['home.php', 'login.php', 'register.php', 'account.php'];

        if (!in_array($currentPage, $noBackPages) && !empty($_SERVER['HTTP_REFERER'])):
        ?>
            <div class="back-button-container">
                <a href="<?= htmlspecialchars($_SERVER['HTTP_REFERER']) ?>">
                    <button>⬅ Back</button>
                </a>
            </div>
        <?php endif; ?>
    </header>
    
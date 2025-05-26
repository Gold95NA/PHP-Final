<?php include('../includes/header.php'); ?>

<h2>Login</h2>

<form action="../scripts/login.php" method="POST">
    <label for="username">Username:</label>
    <input type="text" name="username" required><br>

    <label for="password">Password:</label>
    <input type="password" name="password" required><br>

    <button type="submit">Login</button>
</form>

<p>Don't have an account? <a href="register.php">Register here</a></p>

<?php include('../includes/footer.php'); ?>

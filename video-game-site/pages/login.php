<?php include('../includes/header.php'); ?>

<div class="form-container">
    <h2>Login</h2>
    <div class="logreg-form">
        <form action="../scripts/login.php" method="POST">
            <label for="username">Username:</label>
            <input type="text" name="username" required>

            <label for="password">Password:</label>
            <input type="password" name="password" required>

            <button type="submit" class="primary-button">Login</button>
        </form>
    </div>
    <p>Don't have an account? <a class="here-link" href="register.php">Register here</a></p>
</div>

<?php include('../includes/footer.php'); ?>

<?php include('../includes/header.php'); ?>

<div class="form-container">
    <h2>Register</h2>
    <div class="logreg-form">
        <form action="../scripts/register-user.php" method="POST">
            <label for="username">Username:</label>
            <input type="text" name="username" required>

            <label for="email">Email:</label>
            <input type="email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" name="password" required>

            <button type="submit" class="primary-button">Register</button>
        </form>
    </div>
    <p>Already have an account? <a class="here-link" href="login.php">Login here</a></p>
</div>

<?php include('../includes/footer.php'); ?>
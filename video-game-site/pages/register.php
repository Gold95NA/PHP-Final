<?php include('../includes/header.php'); ?>

<h2>Register</h2>

<form action="../scripts/register-user.php" method="POST">
    <label for="username">Username:</label>
    <input type="text" name="username" required><br>

    <label for="email">Email:</label>
    <input type="email" name="email" required><br>

    <label for="password">Password:</label>
    <input type="password" name="password" required><br>

    <button type="submit">Register</button>
</form>

<p>Already have an account? <a href="login.php">Login here</a></p>

<?php include('../includes/footer.php'); ?>
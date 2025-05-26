<?php
require_once('../includes/auth.php');
requireLogin();
include('../includes/db.php');
include('../includes/header.php');

$userId = $_SESSION['user_id'];

// Fetch current user data
$stmt = $pdo->prepare("SELECT username, email FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();
?>

<h2>Update My Account</h2>

<form action="../scripts/update-account.php" method="POST">
    <label for="username">Username:</label><br>
    <input type="text" name="username" required value="<?= htmlspecialchars($user['username']) ?>"><br><br>

    <label for="email">Email:</label><br>
    <input type="email" name="email" required value="<?= htmlspecialchars($user['email']) ?>"><br><br>

    <label for="password">New Password (leave blank to keep current):</label><br>
    <input type="password" name="password"><br><br>

    <button type="submit">Save Changes</button>
</form>

<?php include('../includes/footer.php'); ?>
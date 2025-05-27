<?php
require_once('../includes/auth.php');
requireLogin();
include('../includes/db.php');
include('../includes/header.php');

$userId = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT username FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

if (!$user) {
    echo "<p>User not found.</p>";
    include('../includes/footer.php');
    exit;
}
?>

<div class="form-container">
    <div class="account-edit">
        <h2>Edit Account</h2>

        <form action="../scripts/update-account.php" method="POST">
            <label for="username">Username:</label>
            <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>

            <label for="password">New Password:</label>
            <input type="password" name="password">

            <label for="confirm_password">Confirm Password:</label>
            <input type="password" name="confirm_password">

            <button type="submit" class="primary-button">Update Account</button>
        </form>
    </div>
</div>

<?php include('../includes/footer.php'); ?>
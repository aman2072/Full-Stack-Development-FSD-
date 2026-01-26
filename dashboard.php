<?php
require 'db.php';
require 'session.php';

if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit;
}

if (!isset($_SESSION['user_id'])) {
    echo '<a href="login.php">Login</a>';
    exit;
}

$stmt = $pdo->prepare("SELECT email FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
?>

<h2>Dashboard</h2>
<p>Welcome, <?= htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8') ?></p>

<form method="post">
    <button type="submit" name="logout">Logout</button>
</form>

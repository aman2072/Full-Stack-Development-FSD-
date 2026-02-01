<?php
session_start();

if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit();
}

$theme = $_COOKIE['theme'] ?? "light";
$isDark = $theme === "dark";
?>

<!DOCTYPE html>
<html>
<head>
<title>Dashboard</title>
<style>
body {
    margin:0;
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    background: <?= $isDark ? "#222" : "#f4f6f8" ?>;
    color: <?= $isDark ? "#fff" : "#000" ?>;
    font-family: Segoe UI;
}
.box {
    background: <?= $isDark ? "#333" : "#fff" ?>;
    padding:40px;
    border-radius:15px;
    text-align:center;
}
a { display:block; margin:10px; }
</style>
</head>
<body>

<div class="box">
<h2>Welcome <?= htmlspecialchars($_SESSION['name']) ?></h2>
<a href="preference.php">Change Theme</a>
<a href="logout.php">Logout</a>
</div>

</body>
</html>

<?php
session_start();

if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['theme'])) {
    setcookie("theme", $_POST['theme'], time()+86400*30, "/");
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Preference</title>
<style>
body { height:100vh; display:flex; justify-content:center; align-items:center; }
form { padding:30px; border:1px solid #ccc; }
</style>
</head>
<body>

<form method="POST">
<select name="theme">
<option value="light">Light Mode</option>
<option value="dark">Dark Mode</option>
</select>
<button>Save</button>
</form>

</body>
</html>

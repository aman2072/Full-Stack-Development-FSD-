<?php
session_start();
require "db.php";

$error = "";

if (isset($_POST['login'])) {
    $student_id = $_POST['student_id'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM students WHERE student_id = :student_id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':student_id' => $student_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['logged_in'] = true;
        $_SESSION['name'] = $user['name'];
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid login";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Login</title>
<style>
body { background:#764ba2; height:100vh; display:flex; justify-content:center; align-items:center; }
form { background:white; padding:40px; border-radius:15px; width:350px; }
input,button { width:100%; padding:12px; margin:10px 0; }
button { background:#667eea; color:white; border:none; }
.error { color:red; text-align:center; }
</style>
</head>
<body>

<form method="POST">
<h2>Login</h2>
<?php if($error) echo "<div class='error'>$error</div>"; ?>
<input type="text" name="student_id" placeholder="Student ID" required>
<input type="password" name="password" placeholder="Password" required>
<button name="login">Login</button>
</form>

</body>
</html>

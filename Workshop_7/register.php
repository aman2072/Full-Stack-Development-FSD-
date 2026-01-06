<?php
require "db.php";

if (isset($_POST['register'])) {
    $student_id = $_POST['student_id'];
    $name = $_POST['name'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $sql = "INSERT INTO students (student_id, name, password)
            VALUES (:student_id, :name, :password)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':student_id' => $student_id,
        ':name' => $name,
        ':password' => $password
    ]);

    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Register</title>
<style>
body { background:#667eea; height:100vh; display:flex; justify-content:center; align-items:center; }
form { background:white; padding:40px; border-radius:15px; width:350px; }
input,button { width:100%; padding:12px; margin:10px 0; }
button { background:#43cea2; color:white; border:none; }
</style>
</head>
<body>

<form method="POST">
<h2>Register</h2>
<input type="text" name="student_id" placeholder="Student ID" required>
<input type="text" name="name" placeholder="Name" required>
<input type="password" name="password" placeholder="Password" required>
<button name="register">Register</button>
</form>

</body>
</html>

<?php

$errors = [];
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm = $_POST["confirm_password"];

    /* =====================
       VALIDATION
    ===================== */

    if (empty($name)) {
        $errors[] = "Name is required.";
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid email is required.";
    }

    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters.";
    }

    if (!preg_match("/[@$!%*?&]/", $password)) {
        $errors[] = "Password must contain a special character.";
    }

    if ($password !== $confirm) {
        $errors[] = "Passwords do not match.";
    }

    /* =====================
       PROCESS DATA
    ===================== */

    if (empty($errors)) {

        $file = "users.json";

        if (!file_exists($file)) {
            die("User database not found.");
        }

        $users = json_decode(file_get_contents($file), true);

        $newUser = [
            "name" => $name,
            "email" => $email,
            "password" => password_hash($password, PASSWORD_DEFAULT)
        ];

        $users[] = $newUser;

        if (file_put_contents($file, json_encode($users, JSON_PRETTY_PRINT))) {
            $success = "Registration successful!";
        } else {
            $errors[] = "Failed to save user data.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registration Result</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">

<?php
if (!empty($errors)) {
    foreach ($errors as $error) {
        echo "<p class='error'>$error</p>";
    }
} else {
    echo "<p class='success'>$success</p>";
}
?>

<a href="registration.html">Go Back</a>

</div>

</body>
</html>

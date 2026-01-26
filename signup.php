<?php
require 'db.php';
require 'session.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'] ?? '';

    if (!$email || empty($password) || strlen($password) < 8) {
        $message = "Invalid email or password";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        try {
            $stmt = $pdo->prepare(
                "INSERT INTO users (email, password) VALUES (?, ?)"
            );
            $stmt->execute([$email, $hashedPassword]);
            $message = "Signup successful. You can login now.";
        } catch (PDOException $e) {
            error_log("Signup error: " . $e->getMessage());
            $message = "Invalid email or password";
        }
    }
}
?>

<form method="post">
    <h2>Signup</h2>
    <input type="email" name="email" required>
    <input type="password" name="password" required>
    <button type="submit">Signup</button>
    <p><?= htmlspecialchars($message) ?></p>
</form>

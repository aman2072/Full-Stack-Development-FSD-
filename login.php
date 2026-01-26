<?php
require 'db.php';
require 'session.php';

/* Generate CSRF token */
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$error = "";

/* Handle form submission */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    /* CSRF validation */
    if (
        empty($_POST['csrf_token']) ||
        !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
    ) {
        error_log("CSRF validation failed");
        $error = "Invalid email or password";
    } else {

        $email    = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = $_POST['password'] ?? '';

        if (!$email || empty($password)) {
            $error = "Invalid email or password";
        } else {

            $stmt = $pdo->prepare(
                "SELECT id, password FROM users WHERE email = ?"
            );
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {

                /* Prevent session fixation */
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user['id'];

                header("Location: dashboard.php");
                exit;
            } else {
                $error = "Invalid email or password";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

<form method="post" novalidate>
    <h2>Login</h2>

    <input type="hidden" name="csrf_token"
           value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">

    <label>
        Email:
        <input type="email" name="email" required>
    </label>

    <br><br>

    <label>
        Password:
        <input type="password" name="password" required>
    </label>

    <br><br>

    <button type="submit">Login</button>

    <?php if ($error): ?>
        <p style="color:red;">
            <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
        </p>
    <?php endif; ?>
</form>

</body>
</html>

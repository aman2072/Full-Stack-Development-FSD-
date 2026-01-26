<?php
declare(strict_types=1);

$host = "localhost";
$db   = "secure_application";
$user = "app_user";
$pass = "StrongPassword123!";

$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    error_log("DB Error: " . $e->getMessage());
    die("Something went wrong. Please try again later.");
}

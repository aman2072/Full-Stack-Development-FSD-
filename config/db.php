<?php
// Database configuration for XAMPP
//define('DB_HOST', 'localhost');
//define('DB_USER', 'root');
//define('DB_PASS', ''); // Default XAMPP password is empty
//define('DB_NAME', 'restaurant_db');

define('DB_HOST', 'localhost');
define('DB_USER', 'np03cs4s240061');
define('DB_PASS', '0Ogt8YUtFH'); // Default XAMPP for server
define('DB_NAME', 'np03cs4s240061');

// Create database connection
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
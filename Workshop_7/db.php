<?php
$host = "localhost";
$user = "np03cs4s240061";
$pass = "0Ogt8YUtFH";
$db   = "np03cs4s240061";

try {
    $conn = new PDO(
        "mysql:host=$host;dbname=$db;charset=utf8",
        $user,
        $pass
    );
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed");
}
?>

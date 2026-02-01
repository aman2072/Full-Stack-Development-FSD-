<?php
require_once '../config/db.php';
require_once '../includes/functions.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode([]);
    exit;
}

$query = trim($_GET['q'] ?? '');

if (empty($query) || strlen($query) < 2) {
    echo json_encode([]);
    exit;
}

// Search menu items - using prepared statement to prevent SQL injection
$stmt = $pdo->prepare("SELECT id, title, price, category_id 
                       FROM menu_items 
                       WHERE is_available = 1 AND title LIKE ? 
                       LIMIT 10");
$stmt->execute(["%$query%"]);
$items = $stmt->fetchAll();

$results = [];
foreach ($items as $item) {
    $results[] = [
        'id' => $item['id'],
        'title' => $item['title'],
        'price' => formatPrice($item['price'])
    ];
}

echo json_encode($results);
?>

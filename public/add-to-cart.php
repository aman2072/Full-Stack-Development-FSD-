<?php
require_once '../config/db.php';
require_once '../includes/functions.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$item_id = $_POST['item_id'] ?? '';
$quantity = intval($_POST['quantity'] ?? 1);

if (empty($item_id) || $quantity < 1) {
    echo json_encode(['success' => false, 'message' => 'Invalid item or quantity']);
    exit;
}

// Verify item exists and is available - using prepared statement
$stmt = $pdo->prepare("SELECT id, title, price, image FROM menu_items WHERE id = ? AND is_available = 1");
$stmt->execute([$item_id]);
$item = $stmt->fetch();

if (!$item) {
    echo json_encode(['success' => false, 'message' => 'Item not found']);
    exit;
}

// Initialize cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Add or update item in cart
if (isset($_SESSION['cart'][$item_id])) {
    $_SESSION['cart'][$item_id]['quantity'] += $quantity;
} else {
    $_SESSION['cart'][$item_id] = [
        'id' => $item['id'],
        'title' => $item['title'],
        'price' => $item['price'],
        'image' => $item['image'],
        'quantity' => $quantity
    ];
}

echo json_encode([
    'success' => true,
    'message' => 'Item added to cart',
    'cart_count' => getCartCount(),
    'cart_total' => formatPrice(getCartTotal())
]);
?>

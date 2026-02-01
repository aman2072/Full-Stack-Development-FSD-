<?php
require_once '../config/db.php';
require_once '../includes/functions.php';

requireLogin();

$pageTitle = 'Checkout';

// Redirect if cart is empty
if (empty($_SESSION['cart'])) {
    setFlashMessage('Your cart is empty', 'error');
    redirect('index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo->beginTransaction();
        
        $user_id = $_SESSION['user_id'];
        $total = getCartTotal();
        
        // Create order - using prepared statement
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_amount, status) VALUES (?, ?, 'Confirmed')");
        $stmt->execute([$user_id, $total]);
        $order_id = $pdo->lastInsertId();
        
        // Insert order items - using prepared statements
        $stmt = $pdo->prepare("INSERT INTO order_items (order_id, menu_item_id, quantity, unit_price, subtotal) 
                               VALUES (?, ?, ?, ?, ?)");
        
        foreach ($_SESSION['cart'] as $item_id => $item) {
            $subtotal = $item['price'] * $item['quantity'];
            $stmt->execute([$order_id, $item_id, $item['quantity'], $item['price'], $subtotal]);
        }
        
        $pdo->commit();
        
        // Clear cart
        $_SESSION['cart'] = [];
        
        // Redirect to confirmation
        $_SESSION['last_order_id'] = $order_id;
        redirect('order-confirmed.php');
        
    } catch (Exception $e) {
        $pdo->rollBack();
        setFlashMessage('Order failed. Please try again.', 'error');
        redirect('cart.php');
    }
}

$cart = $_SESSION['cart'];
$total = getCartTotal();

include '../includes/header.php';
?>

<div class="container">
    <h1>Checkout</h1>
    
    <div class="checkout-container">
        <div class="order-summary">
            <h2>Order Summary</h2>
            
            <table class="summary-table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Qty</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart as $item): ?>
                        <tr>
                            <td><?php echo e($item['title']); ?></td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td><?php echo formatPrice($item['price'] * $item['quantity']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2"><strong>Total</strong></td>
                        <td><strong><?php echo formatPrice($total); ?></strong></td>
                    </tr>
                </tfoot>
            </table>
            
            <form method="POST" action="checkout.php">
                <button type="submit" class="btn btn-primary btn-block">Confirm Order</button>
            </form>
            
            <a href="cart.php" class="btn btn-secondary btn-block">Back to Cart</a>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>

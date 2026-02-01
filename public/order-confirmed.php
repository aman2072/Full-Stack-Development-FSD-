<?php
require_once '../config/db.php';
require_once '../includes/functions.php';

requireLogin();

$pageTitle = 'Order Confirmed';

// Get the last order
$order_id = $_SESSION['last_order_id'] ?? null;

if (!$order_id) {
    redirect('index.php');
}

// Fetch order details - using prepared statement
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
$stmt->execute([$order_id, $_SESSION['user_id']]);
$order = $stmt->fetch();

if (!$order) {
    redirect('index.php');
}

// Fetch order items - using prepared statement
$stmt = $pdo->prepare("SELECT oi.*, m.title, m.image 
                       FROM order_items oi 
                       JOIN menu_items m ON oi.menu_item_id = m.id 
                       WHERE oi.order_id = ?");
$stmt->execute([$order_id]);
$order_items = $stmt->fetchAll();

// Clear the last order ID
unset($_SESSION['last_order_id']);

include '../includes/header.php';
?>

<div class="container">
    <div class="confirmation-box">
        <div class="success-icon">✓</div>
        <h1>Order Confirmed!</h1>
        <p>Your order has been successfully placed.</p>
        
        <div class="order-details">
            <h2>Order #<?php echo $order_id; ?></h2>
            <p class="order-date">Placed on <?php echo date('F j, Y g:i A', strtotime($order['created_at'])); ?></p>
            
            <table class="summary-table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Quantity</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($order_items as $item): ?>
                        <tr>
                            <td>
                                <div class="order-item">
                                    <img src="<?php echo e($item['image']); ?>" alt="<?php echo e($item['title']); ?>" class="order-item-image">
                                    <span><?php echo e($item['title']); ?></span>
                                </div>
                            </td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td><?php echo formatPrice($item['subtotal']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2"><strong>Total</strong></td>
                        <td><strong><?php echo formatPrice($order['total_amount']); ?></strong></td>
                    </tr>
                </tfoot>
            </table>
            
            <div class="confirmation-actions">
                <a href="orders.php" class="btn btn-primary">View All Orders</a>
                <a href="index.php" class="btn btn-secondary">Back to Menu</a>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>

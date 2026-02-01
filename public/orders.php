<?php
require_once '../config/db.php';
require_once '../includes/functions.php';

requireLogin();

$pageTitle = 'My Orders';

// Fetch user's orders - using prepared statement
$stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$orders = $stmt->fetchAll();

include '../includes/header.php';
?>

<div class="container">
    <h1>My Orders</h1>
    
    <?php if (empty($orders)): ?>
        <div class="no-results">
            <p>You haven't placed any orders yet.</p>
            <a href="index.php" class="btn btn-primary">Browse Menu</a>
        </div>
    <?php else: ?>
        <div class="orders-list">
            <?php foreach ($orders as $order): ?>
                <?php
                // Fetch order items - using prepared statement
                $stmt = $pdo->prepare("SELECT oi.*, m.title, m.image 
                                       FROM order_items oi 
                                       JOIN menu_items m ON oi.menu_item_id = m.id 
                                       WHERE oi.order_id = ?");
                $stmt->execute([$order['id']]);
                $items = $stmt->fetchAll();
                ?>
                
                <div class="order-card">
                    <div class="order-header">
                        <div>
                            <h3>Order #<?php echo $order['id']; ?></h3>
                            <p class="order-date"><?php echo date('F j, Y g:i A', strtotime($order['created_at'])); ?></p>
                        </div>
                        <div>
                            <span class="status-badge status-<?php echo strtolower($order['status']); ?>">
                                <?php echo e($order['status']); ?>
                            </span>
                            <p class="order-total"><?php echo formatPrice($order['total_amount']); ?></p>
                        </div>
                    </div>
                    
                    <div class="order-items">
                        <?php foreach ($items as $item): ?>
                            <div class="order-item-row">
                                <img src="<?php echo e($item['image']); ?>" alt="<?php echo e($item['title']); ?>" class="order-item-thumb">
                                <div class="order-item-details">
                                    <span class="item-name"><?php echo e($item['title']); ?></span>
                                    <span class="item-quantity">Qty: <?php echo $item['quantity']; ?></span>
                                </div>
                                <span class="item-price"><?php echo formatPrice($item['subtotal']); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>

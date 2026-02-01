<?php
require_once '../config/db.php';
require_once '../includes/functions.php';

requireLogin();

$pageTitle = 'Shopping Cart';

// Handle cart updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'update') {
        $item_id = $_POST['item_id'] ?? '';
        $quantity = intval($_POST['quantity'] ?? 0);
        
        if ($quantity > 0 && isset($_SESSION['cart'][$item_id])) {
            $_SESSION['cart'][$item_id]['quantity'] = $quantity;
            setFlashMessage('Cart updated successfully');
        }
        redirect('cart.php');
    }
    
    if ($action === 'remove') {
        $item_id = $_POST['item_id'] ?? '';
        if (isset($_SESSION['cart'][$item_id])) {
            unset($_SESSION['cart'][$item_id]);
            setFlashMessage('Item removed from cart');
        }
        redirect('cart.php');
    }
}

$cart = $_SESSION['cart'] ?? [];
$total = getCartTotal();

include '../includes/header.php';
?>

<div class="container">
    <h1>Shopping Cart</h1>
    
    <?php if (empty($cart)): ?>
        <div class="empty-cart">
            <p>Your cart is empty</p>
            <a href="index.php" class="btn btn-primary">Browse Menu</a>
        </div>
    <?php else: ?>
        <div class="cart-container">
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart as $item_id => $item): ?>
                        <tr>
                            <td>
                                <div class="cart-item">
                                    <img src="<?php echo e($item['image']); ?>" alt="<?php echo e($item['title']); ?>" class="cart-item-image">
                                    <span><?php echo e($item['title']); ?></span>
                                </div>
                            </td>
                            <td><?php echo formatPrice($item['price']); ?></td>
                            <td>
                                <form method="POST" action="cart.php" class="quantity-form">
                                    <input type="hidden" name="action" value="update">
                                    <input type="hidden" name="item_id" value="<?php echo $item_id; ?>">
                                    <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" 
                                           min="1" max="99" class="quantity-input">
                                    <button type="submit" class="btn btn-sm">Update</button>
                                </form>
                            </td>
                            <td><?php echo formatPrice($item['price'] * $item['quantity']); ?></td>
                            <td>
                                <form method="POST" action="cart.php" style="display: inline;">
                                    <input type="hidden" name="action" value="remove">
                                    <input type="hidden" name="item_id" value="<?php echo $item_id; ?>">
                                    <button type="submit" class="btn btn-danger btn-sm" 
                                            onclick="return confirm('Remove this item?')">Remove</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3"><strong>Total</strong></td>
                        <td colspan="2"><strong><?php echo formatPrice($total); ?></strong></td>
                    </tr>
                </tfoot>
            </table>
            
            <div class="cart-actions">
                <a href="index.php" class="btn btn-secondary">Continue Shopping</a>
                <a href="checkout.php" class="btn btn-primary">Proceed to Checkout</a>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? e($pageTitle) : 'Restaurant Ordering System'; ?></title>
    <link rel="stylesheet" href="../public/assets/css/style.css">
</head>
<body>
    <?php if (isLoggedIn()): ?>
    <nav class="navbar">
        <div class="container">
            <a href="index.php" class="logo">🍽️ Gen-Z Restaurant</a>
            <div class="nav-links">
                <a href="index.php">Menu</a>
                <a href="cart.php" class="cart-link">
                    🛒 Cart <span class="cart-badge"><?php echo getCartCount(); ?></span>
                </a>
                <a href="orders.php">My Orders</a>
                <a href="logout.php" class="btn-logout">Logout</a>
            </div>
        </div>
    </nav>
    <?php endif; ?>

    <div class="main-container">
        <?php 
        $flash = getFlashMessage();
        if ($flash): 
        ?>
        <div class="alert alert-<?php echo e($flash['type']); ?>">
            <?php echo e($flash['message']); ?>
        </div>
        <?php endif; ?>
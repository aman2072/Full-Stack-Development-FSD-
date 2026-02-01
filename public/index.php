<?php
require_once '../config/db.php';
require_once '../includes/functions.php';

requireLogin();

$pageTitle = 'Menu';

// Get search parameters
$search = trim($_GET['search'] ?? '');
$category = $_GET['category'] ?? '';
$min_price = $_GET['min_price'] ?? '';
$max_price = $_GET['max_price'] ?? '';

// Build query with prepared statements
$sql = "SELECT m.*, c.name as category_name 
        FROM menu_items m 
        JOIN categories c ON m.category_id = c.id 
        WHERE m.is_available = 1";

$params = [];

// Search by title
if (!empty($search)) {
    $sql .= " AND m.title LIKE ?";
    $params[] = "%$search%";
}

// Filter by category
if (!empty($category)) {
    $sql .= " AND m.category_id = ?";
    $params[] = $category;
}

// Filter by price range
if (!empty($min_price) && is_numeric($min_price)) {
    $sql .= " AND m.price >= ?";
    $params[] = $min_price;
}

if (!empty($max_price) && is_numeric($max_price)) {
    $sql .= " AND m.price <= ?";
    $params[] = $max_price;
}

$sql .= " ORDER BY c.id, m.title";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$menu_items = $stmt->fetchAll();

// Get all categories for filter
$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();

include '../includes/header.php';
?>

<div class="container">
    <h1>Our Menu</h1>
    
    <!-- Search and Filter Section -->
    <div class="search-section">
        <form method="GET" action="index.php" class="search-form">
            <div class="search-row">
                <input type="text" name="search" placeholder="Search menu items..." 
                       value="<?php echo e($search); ?>" class="search-input" id="searchInput">
                
                <select name="category" class="filter-select">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>" 
                                <?php echo $category == $cat['id'] ? 'selected' : ''; ?>>
                            <?php echo e($cat['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                
                <input type="number" name="min_price" placeholder="Min Price" 
                       value="<?php echo e($min_price); ?>" step="0.01" class="price-input">
                
                <input type="number" name="max_price" placeholder="Max Price" 
                       value="<?php echo e($max_price); ?>" step="0.01" class="price-input">
                
                <button type="submit" class="btn btn-primary">Search</button>
                
                <?php if ($search || $category || $min_price || $max_price): ?>
                    <a href="index.php" class="btn btn-secondary">Clear</a>
                <?php endif; ?>
            </div>
        </form>
        
        <!-- Live search suggestions will appear here -->
        <div id="searchSuggestions" class="search-suggestions"></div>
    </div>

    <!-- Menu Items Grid -->
    <?php if (empty($menu_items)): ?>
        <div class="no-results">
            <p>No menu items found matching your search criteria.</p>
        </div>
    <?php else: ?>
        <div class="menu-grid">
            <?php foreach ($menu_items as $item): ?>
                <div class="menu-card">
                    <img src="<?php echo e($item['image']); ?>" alt="<?php echo e($item['title']); ?>" class="menu-image">
                    <div class="menu-content">
                        <span class="category-badge"><?php echo e($item['category_name']); ?></span>
                        <h3><?php echo e($item['title']); ?></h3>
                        <?php if ($item['description']): ?>
                            <p class="description"><?php echo e($item['description']); ?></p>
                        <?php endif; ?>
                        <div class="menu-footer">
                            <span class="price"><?php echo formatPrice($item['price']); ?></span>
                            <button class="btn btn-add-cart" 
                                    data-id="<?php echo $item['id']; ?>"
                                    data-title="<?php echo e($item['title']); ?>"
                                    data-price="<?php echo $item['price']; ?>"
                                    data-image="<?php echo e($item['image']); ?>">
                                Add to Cart
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>

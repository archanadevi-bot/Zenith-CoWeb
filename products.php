<?php
/**
 * Zenith Systems and Services - Products Catalog
 */
$page_title = "Computer Hardware, Laptops & Accessories | Zenith Systems Sulur";
$meta_desc = "Explore our inventory of business laptops, custom desktop PCs, NVMe SSDs, DDR4/DDR5 RAM, HP/Canon printers, and networking gear at Zenith Systems, Sulur, Coimbatore.";

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

$pdo = getDB();

// Available Categories
$categories = ['Desktop', 'Laptop', 'Storage', 'RAM', 'Accessories', 'Printer', 'Networking', 'Other'];

// Request Filters
$selected_category = isset($_GET['category']) && in_array($_GET['category'], $categories) ? $_GET['category'] : '';
$search_query = isset($_GET['q']) ? trim($_GET['q']) : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';

// Build Dynamic SQL Query
$sql = "SELECT * FROM products WHERE status = 'Active'";
$params = [];

if (!empty($selected_category)) {
    $sql .= " AND category = ?";
    $params[] = $selected_category;
}

if (!empty($search_query)) {
    $sql .= " AND (name LIKE ? OR brand LIKE ? OR description LIKE ?)";
    $term = "%{$search_query}%";
    $params[] = $term;
    $params[] = $term;
    $params[] = $term;
}

// Sorting logic
$sql .= match($sort) {
    'price_asc' => " ORDER BY price ASC",
    'price_desc' => " ORDER BY price DESC",
    'name_asc' => " ORDER BY name ASC",
    default => " ORDER BY id DESC"
};

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();
?>

<!-- Page Header Banner -->
<div class="page-hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-7">
                <span class="hero-badge mb-2">
                    <i class="bi bi-box-seam me-1"></i> Authorized Sales &amp; Inventory
                </span>
                <h1 class="page-hero-title">Computer Products &amp; Hardware</h1>
                <p class="page-hero-desc">
                    High-performance laptops, custom workstations, high-speed NVMe SSDs, memory upgrades, and genuine accessories in Sulur, Coimbatore.
                </p>
            </div>
            <div class="col-md-5 mt-3 mt-md-0 text-md-end">
                <a href="<?= whatsapp_url('Hello Zenith Systems, I would like to check availability for a specific hardware component.') ?>" target="_blank" class="btn btn-whatsapp">
                    <i class="bi bi-whatsapp"></i> Inquire Availability
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Main Catalog Container -->
<div class="container py-5">
    <!-- Search & Filter Controls -->
    <div class="bg-white p-3 p-md-4 rounded-4 border border-light-subtle shadow-sm mb-4">
        <form action="<?= BASE_URL ?>/products.php" method="GET" class="row g-3 align-items-center">
            <!-- Search Bar -->
            <div class="col-lg-5 col-md-6">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" id="catalogLiveSearch" name="q" value="<?= e($search_query) ?>" class="form-control bg-light border-start-0" placeholder="Search by model, brand, or spec (e.g. Dell, 512GB, DDR4)...">
                </div>
            </div>
            
            <!-- Category Filter Dropdown -->
            <div class="col-lg-3 col-md-3">
                <select name="category" class="form-select bg-light" onchange="this.form.submit()">
                    <option value="">All Categories (<?= count($products) ?> items)</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= e($cat) ?>" <?= $selected_category === $cat ? 'selected' : '' ?>>
                            <?= e($cat) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <!-- Sort Filter -->
            <div class="col-lg-3 col-md-3">
                <select name="sort" class="form-select bg-light" onchange="this.form.submit()">
                    <option value="newest" <?= $sort === 'newest' ? 'selected' : '' ?>>Newest Arrivals</option>
                    <option value="price_asc" <?= $sort === 'price_asc' ? 'selected' : '' ?>>Price: Low to High</option>
                    <option value="price_desc" <?= $sort === 'price_desc' ? 'selected' : '' ?>>Price: High to Low</option>
                    <option value="name_asc" <?= $sort === 'name_asc' ? 'selected' : '' ?>>Alphabetical (A-Z)</option>
                </select>
            </div>
            
            <!-- Clear Filter Button -->
            <div class="col-lg-1 col-md-12 text-center text-lg-start">
                <?php if (!empty($selected_category) || !empty($search_query) || $sort !== 'newest'): ?>
                    <a href="<?= BASE_URL ?>/products.php" class="btn btn-outline-secondary w-100" title="Reset Filters">
                        <i class="bi bi-arrow-clockwise"></i>
                    </a>
                <?php else: ?>
                    <button type="submit" class="btn btn-primary w-100" title="Filter">
                        <i class="bi bi-funnel"></i>
                    </button>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Category Pills Navigation -->
    <div class="d-flex flex-wrap gap-2 mb-4 pb-2 border-bottom">
        <a href="<?= BASE_URL ?>/products.php<?= !empty($search_query) ? '?q=' . urlencode($search_query) : '' ?>" class="btn btn-sm <?= empty($selected_category) ? 'btn-primary' : 'btn-outline-secondary' ?> rounded-pill px-3">
            All Products
        </a>
        <?php foreach ($categories as $cat): ?>
            <a href="<?= BASE_URL ?>/products.php?category=<?= urlencode($cat) ?><?= !empty($search_query) ? '&q=' . urlencode($search_query) : '' ?>" class="btn btn-sm <?= $selected_category === $cat ? 'btn-primary' : 'btn-outline-secondary' ?> rounded-pill px-3">
                <?= e($cat) ?>
            </a>
        <?php endforeach; ?>
    </div>

    <!-- Products Grid -->
    <?php if (!empty($products)): ?>
        <div class="row g-4" id="productGridContainer">
            <?php foreach ($products as $prod): ?>
                <?php
                // Image resolution
                $img_path = !empty($prod['image']) ? $prod['image'] : 'default-product.svg';
                if (file_exists(__DIR__ . '/uploads/products/' . $img_path)) {
                    $img_url = BASE_URL . '/uploads/products/' . $img_path;
                } elseif (file_exists(__DIR__ . '/assets/images/' . $img_path)) {
                    $img_url = BASE_URL . '/assets/images/' . $img_path;
                } else {
                    $img_url = BASE_URL . '/assets/images/default-product.svg';
                }
                ?>
                <div class="col-md-6 col-lg-4 product-grid-item" 
                     data-name="<?= strtolower(e($prod['name'])) ?>" 
                     data-brand="<?= strtolower(e($prod['brand'])) ?>" 
                     data-category="<?= strtolower(e($prod['category'])) ?>">
                    <div class="product-card">
                        <div class="product-image-wrap">
                            <span class="product-category-tag"><?= e($prod['category']) ?></span>
                            <img src="<?= e($img_url) ?>" alt="<?= e($prod['name']) ?>" loading="lazy">
                        </div>
                        <div class="product-body">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="product-brand"><?= e($prod['brand']) ?></span>
                                <?= get_stock_badge($prod['stock']) ?>
                            </div>
                            <h3 class="product-title">
                                <a href="<?= BASE_URL ?>/product_details.php?id=<?= $prod['id'] ?>" class="text-dark">
                                    <?= e($prod['name']) ?>
                                </a>
                            </h3>
                            <p class="text-muted small mb-3" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; height: 2.5rem;">
                                <?= e($prod['description']) ?>
                            </p>
                            <div class="d-flex justify-content-between align-items-center mt-auto pt-2 border-top">
                                <div class="product-price">
                                    <?= format_inr($prod['price']) ?>
                                </div>
                                <div class="d-flex gap-1.5">
                                    <a href="<?= BASE_URL ?>/product_details.php?id=<?= $prod['id'] ?>" class="btn btn-outline-secondary btn-sm" title="View Specifications">
                                        <i class="bi bi-eye"></i> Specs
                                    </a>
                                    <button onclick="inquireWhatsAppProduct('<?= addslashes(e($prod['name'])) ?>', '<?= $prod['id'] ?>')" class="btn btn-whatsapp btn-sm" title="Inquire on WhatsApp">
                                        <i class="bi bi-whatsapp"></i> Buy
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div id="noProductResults" class="text-center py-5" style="display: none;">
            <i class="bi bi-search display-5 text-muted"></i>
            <h4 class="mt-3 fw-bold">No matching products found</h4>
            <p class="text-muted">Try adjusting your keyword or filter criteria.</p>
        </div>
    <?php else: ?>
        <div class="text-center py-5 bg-white rounded-4 border shadow-sm max-w-700 mx-auto">
            <i class="bi bi-box-seam display-5 text-muted"></i>
            <h3 class="mt-3 fw-bold">No Products Found</h3>
            <p class="text-muted">There are no products currently matching your selected criteria.</p>
            <a href="<?= BASE_URL ?>/products.php" class="btn btn-primary mt-2">
                <i class="bi bi-arrow-clockwise me-1"></i> View All Products
            </a>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<?php
/**
 * Zenith Systems and Services - Product Details View
 */
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';

$pdo = getDB();

$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ? AND status = 'Active'");
$stmt->execute([$product_id]);
$product = $stmt->fetch();

if (!$product) {
    header("Location: " . BASE_URL . "/products.php");
    exit;
}

$page_title = e($product['name']) . " | Zenith Systems Sulur";
$meta_desc = "Buy " . e($product['name']) . " at best price in Sulur, Coimbatore. Genuine " . e($product['brand']) . " product with warranty at Zenith Systems.";

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

// Image path resolution
$img_path = !empty($product['image']) ? $product['image'] : 'default-product.svg';
if (file_exists(__DIR__ . '/uploads/products/' . $img_path)) {
    $img_url = BASE_URL . '/uploads/products/' . $img_path;
} elseif (file_exists(__DIR__ . '/assets/images/' . $img_path)) {
    $img_url = BASE_URL . '/assets/images/' . $img_path;
} else {
    $img_url = BASE_URL . '/assets/images/default-product.svg';
}

// Fetch Related Products in same category
$rel_stmt = $pdo->prepare("SELECT * FROM products WHERE category = ? AND id != ? AND status = 'Active' LIMIT 3");
$rel_stmt->execute([$product['category'], $product['id']]);
$related_products = $rel_stmt->fetchAll();
?>

<!-- Breadcrumb -->
<div class="bg-white py-3 border-bottom">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/index.php">Home</a></li>
                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/products.php">Products</a></li>
                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/products.php?category=<?= urlencode($product['category']) ?>"><?= e($product['category']) ?></a></li>
                <li class="breadcrumb-item active text-truncate" aria-current="page" style="max-width: 300px;"><?= e($product['name']) ?></li>
            </ol>
        </nav>
    </div>
</div>

<div class="container py-5">
    <div class="row g-5">
        <!-- Product Image Showcase -->
        <div class="col-lg-6">
            <div class="bg-white p-4 rounded-4 border border-light-subtle shadow-sm text-center position-relative">
                <span class="position-absolute top-0 start-0 m-3 badge bg-primary fs-6 px-3 py-1.5 rounded-pill">
                    <?= e($product['category']) ?>
                </span>
                <img src="<?= e($img_url) ?>" alt="<?= e($product['name']) ?>" class="img-fluid rounded-3" style="max-height: 380px; width: auto; object-fit: contain;">
            </div>
            
            <!-- Quality & Assurance Badges -->
            <div class="row g-2 mt-2">
                <div class="col-4">
                    <div class="p-3 bg-white rounded-3 text-center border shadow-xs">
                        <i class="bi bi-shield-check text-primary fs-4 d-block mb-1"></i>
                        <span class="small fw-semibold text-dark">100% Genuine</span>
                    </div>
                </div>
                <div class="col-4">
                    <div class="p-3 bg-white rounded-3 text-center border shadow-xs">
                        <i class="bi bi-award text-success fs-4 d-block mb-1"></i>
                        <span class="small fw-semibold text-dark">Brand Warranty</span>
                    </div>
                </div>
                <div class="col-4">
                    <div class="p-3 bg-white rounded-3 text-center border shadow-xs">
                        <i class="bi bi-geo-alt text-danger fs-4 d-block mb-1"></i>
                        <span class="small fw-semibold text-dark">Sulur Pickup</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Product Information & Order Trigger -->
        <div class="col-lg-6">
            <div class="mb-2 d-flex align-items-center gap-2">
                <span class="badge bg-secondary-subtle text-secondary-emphasis px-2.5 py-1 text-uppercase fw-bold">
                    Brand: <?= e($product['brand']) ?>
                </span>
                <?= get_stock_badge($product['stock']) ?>
            </div>
            
            <h1 class="h2 fw-bold text-dark mb-3">
                <?= e($product['name']) ?>
            </h1>
            
            <div class="p-3 bg-white rounded-3 border shadow-xs mb-4 d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small d-block">Special Offer Price (Inclusive of Taxes)</span>
                    <span class="display-6 fw-bold text-primary"><?= format_inr($product['price']) ?></span>
                </div>
                <div class="text-end">
                    <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1.5 rounded-pill">
                        <i class="bi bi-tag-fill me-1"></i> Best Rate in Coimbatore
                    </span>
                </div>
            </div>
            
            <div class="mb-4">
                <h5 class="fw-bold mb-2">Product Overview &amp; Specifications</h5>
                <div class="text-secondary" style="white-space: pre-line; line-height: 1.7;">
                    <?= e($product['description']) ?>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="d-flex flex-column flex-sm-row gap-3 pt-3 border-top">
                <button onclick="inquireWhatsAppProduct('<?= addslashes(e($product['name'])) ?>', '<?= $product['id'] ?>')" class="btn btn-whatsapp btn-lg flex-grow-1">
                    <i class="bi bi-whatsapp me-2"></i> Inquire &amp; Order on WhatsApp
                </button>
                <a href="tel:<?= BUSINESS_PHONE ?>" class="btn btn-outline-primary btn-lg">
                    <i class="bi bi-telephone-fill me-2"></i> Call Store
                </a>
            </div>
            
            <!-- Store Location Note -->
            <div class="mt-4 p-3 bg-primary bg-opacity-10 border border-primary border-opacity-25 rounded-3 text-primary small">
                <i class="bi bi-info-circle-fill me-1.5"></i>
                <strong>Visit Our Sulur Store:</strong> Experience demo units and collect orders directly at <?= BUSINESS_ADDRESS ?>.
            </div>
        </div>
    </div>
    
    <!-- Related Products -->
    <?php if (!empty($related_products)): ?>
        <div class="mt-5 pt-4 border-top">
            <h3 class="fw-bold mb-4">Related Hardware &amp; Upgrades</h3>
            <div class="row g-4">
                <?php foreach ($related_products as $rel): ?>
                    <?php
                    $rel_img = !empty($rel['image']) ? $rel['image'] : 'default-product.svg';
                    $rel_url = file_exists(__DIR__ . '/uploads/products/' . $rel_img) ? BASE_URL . '/uploads/products/' . $rel_img : BASE_URL . '/assets/images/' . $rel_img;
                    ?>
                    <div class="col-md-4">
                        <div class="product-card">
                            <div class="product-image-wrap" style="height: 180px;">
                                <img src="<?= e($rel_url) ?>" alt="<?= e($rel['name']) ?>" loading="lazy">
                            </div>
                            <div class="product-body">
                                <span class="product-brand"><?= e($rel['brand']) ?></span>
                                <h4 class="product-title fs-6">
                                    <a href="<?= BASE_URL ?>/product_details.php?id=<?= $rel['id'] ?>" class="text-dark">
                                        <?= e($rel['name']) ?>
                                    </a>
                                </h4>
                                <div class="d-flex justify-content-between align-items-center mt-auto pt-2 border-top">
                                    <div class="product-price fs-5"><?= format_inr($rel['price']) ?></div>
                                    <a href="<?= BASE_URL ?>/product_details.php?id=<?= $rel['id'] ?>" class="btn btn-outline-primary btn-sm">View</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

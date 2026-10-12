<?php
/**
 * Zenith Systems and Services - Add Product
 */
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

$pdo = getDB();
$error_msg = '';
$categories = ['Desktop', 'Laptop', 'Storage', 'RAM', 'Accessories', 'Printer', 'Networking', 'Other'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validate_csrf()) {
        $error_msg = "Security session expired. Please refresh the page and submit again.";
    } else {
        $name = trim($_POST['name'] ?? '');
        $category = trim($_POST['category'] ?? '');
        $brand = trim($_POST['brand'] ?? '');
        $price = floatval($_POST['price'] ?? 0);
        $stock = intval($_POST['stock'] ?? 0);
        $status = in_array($_POST['status'] ?? '', ['Active', 'Inactive']) ? $_POST['status'] : 'Active';
        $description = trim($_POST['description'] ?? '');

        if (empty($name) || empty($category) || empty($brand) || $price <= 0 || empty($description)) {
            $error_msg = "Please fill in all mandatory fields (Name, Category, Brand, Valid Price, and Description).";
        } elseif (!in_array($category, $categories)) {
            $error_msg = "Please select a valid category.";
        } else {
            $image_filename = null;

            // Handle Image Upload if provided
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['image'];
                $max_size = 2 * 1024 * 1024; // 2MB
                $allowed_extensions = ['jpg', 'jpeg', 'png', 'webp'];
                $allowed_mimes = ['image/jpeg', 'image/png', 'image/webp'];

                $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

                if ($file['size'] > $max_size) {
                    $error_msg = "Image file is too large. Maximum allowed size is 2MB.";
                } elseif (!in_array($ext, $allowed_extensions)) {
                    $error_msg = "Invalid file extension. Only JPG, PNG, and WEBP images are allowed.";
                } else {
                    // Check MIME type securely
                    $finfo = finfo_open(FILEINFO_MIME_TYPE);
                    $mime = finfo_file($finfo, $file['tmp_name']);
                    finfo_close($finfo);

                    if (!in_array($mime, $allowed_mimes)) {
                        $error_msg = "Invalid image content. Please upload a genuine JPG, PNG, or WEBP file.";
                    } else {
                        // Generate unique sanitized filename
                        $image_filename = 'prod_' . time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
                        $upload_dir = __DIR__ . '/../uploads/products/';

                        if (!is_dir($upload_dir)) {
                            mkdir($upload_dir, 0755, true);
                        }

                        $dest_path = $upload_dir . $image_filename;
                        if (!move_uploaded_file($file['tmp_name'], $dest_path)) {
                            $error_msg = "Failed to upload image file to server.";
                        }
                    }
                }
            }

            if (empty($error_msg)) {
                try {
                    $stmt = $pdo->prepare("INSERT INTO products (name, category, brand, description, price, stock, image, status, created_at) 
                                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");
                    $stmt->execute([
                        $name,
                        $category,
                        $brand,
                        $description,
                        $price,
                        $stock,
                        $image_filename,
                        $status
                    ]);

                    set_flash('success', "Product \"{$name}\" successfully added to inventory!");
                    header('Location: ' . BASE_URL . '/admin/products.php');
                    exit;
                } catch (PDOException $e) {
                    error_log("Product add error: " . $e->getMessage());
                    $error_msg = "Database error: Could not save product. " . $e->getMessage();
                }
            }
        }
    }
}

$page_title = "Add New Product";
require_once __DIR__ . '/includes/admin_header.php';
?>

<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="card border-0 rounded-4 shadow-sm bg-white p-4 p-md-5 border border-light-subtle">
            <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                <h4 class="fw-bold text-dark mb-0">
                    <i class="bi bi-plus-circle text-primary me-2"></i> Add New Product to Catalog
                </h4>
                <a href="<?= BASE_URL ?>/admin/products.php" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Back to Inventory
                </a>
            </div>

            <?php if (!empty($error_msg)): ?>
                <div class="alert alert-danger d-flex align-items-center mb-4">
                    <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                    <div><?= e($error_msg) ?></div>
                </div>
            <?php endif; ?>

            <form action="<?= BASE_URL ?>/admin/product_add.php" method="POST" enctype="multipart/form-data">
                <?= csrf_field() ?>

                <div class="row g-3 mb-3">
                    <div class="col-md-8">
                        <label class="form-label">Product Title / Model Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Dell Latitude 5420 Business Laptop" value="<?= e($_POST['name'] ?? '') ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Brand / Manufacturer <span class="text-danger">*</span></label>
                        <input type="text" name="brand" class="form-control" placeholder="e.g. Dell, HP, Samsung, Kingston" value="<?= e($_POST['brand'] ?? '') ?>" required>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Category <span class="text-danger">*</span></label>
                        <select name="category" class="form-select" required>
                            <option value="">-- Select Category --</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= e($cat) ?>" <?= ($_POST['category'] ?? '') === $cat ? 'selected' : '' ?>><?= e($cat) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Price (INR ₹) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">₹</span>
                            <input type="number" step="0.01" name="price" class="form-control" placeholder="0.00" value="<?= e($_POST['price'] ?? '') ?>" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Initial Stock Quantity <span class="text-danger">*</span></label>
                        <input type="number" name="stock" class="form-control" placeholder="0" min="0" value="<?= e($_POST['stock'] ?? '1') ?>" required>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Product Image <span class="text-muted small">(JPG, PNG, WEBP max 2MB)</span></label>
                        <input type="file" name="image" class="form-control" accept="image/jpeg,image/png,image/webp">
                        <small class="text-muted">If omitted, a smart vector placeholder will be used.</small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Catalog Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select">
                            <option value="Active" <?= ($_POST['status'] ?? 'Active') === 'Active' ? 'selected' : '' ?>>Active (Visible on public store)</option>
                            <option value="Inactive" <?= ($_POST['status'] ?? '') === 'Inactive' ? 'selected' : '' ?>>Inactive (Hidden)</option>
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Detailed Description &amp; Specifications <span class="text-danger">*</span></label>
                    <textarea name="description" rows="5" class="form-control" placeholder="Key specs: Processor, RAM, Storage, Screen, Warranty, Interfaces..." required><?= e($_POST['description'] ?? '') ?></textarea>
                </div>

                <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                    <a href="<?= BASE_URL ?>/admin/products.php" class="btn btn-outline-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary fw-bold px-4">
                        <i class="bi bi-check-lg me-1"></i> Save Product to Inventory
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>

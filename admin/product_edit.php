<?php
/**
 * Zenith Systems and Services - Edit Product
 */
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

$pdo = getDB();
$error_msg = '';
$categories = ['Desktop', 'Laptop', 'Storage', 'RAM', 'Accessories', 'Printer', 'Networking', 'Other'];

$product_id = isset($_GET['id']) ? intval($_GET['id']) : (isset($_POST['id']) ? intval($_POST['id']) : 0);

$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch();

if (!$product) {
    set_flash('danger', 'Product not found.');
    header('Location: ' . BASE_URL . '/admin/products.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validate_csrf()) {
        $error_msg = "Security session expired. Please refresh the page and try again.";
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
            $image_filename = $product['image']; // Default to retaining current image

            // Handle New Image Replacement if uploaded
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
                    $finfo = finfo_open(FILEINFO_MIME_TYPE);
                    $mime = finfo_file($finfo, $file['tmp_name']);
                    finfo_close($finfo);

                    if (!in_array($mime, $allowed_mimes)) {
                        $error_msg = "Invalid image content. Please upload a genuine JPG, PNG, or WEBP file.";
                    } else {
                        // Generate new filename
                        $new_filename = 'prod_' . time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
                        $upload_dir = __DIR__ . '/../uploads/products/';

                        if (!is_dir($upload_dir)) {
                            mkdir($upload_dir, 0755, true);
                        }

                        $dest_path = $upload_dir . $new_filename;
                        if (move_uploaded_file($file['tmp_name'], $dest_path)) {
                            // Delete old uploaded image if it exists in uploads folder
                            if (!empty($product['image']) && file_exists($upload_dir . $product['image'])) {
                                @unlink($upload_dir . $product['image']);
                            }
                            $image_filename = $new_filename;
                        } else {
                            $error_msg = "Failed to upload image file to server.";
                        }
                    }
                }
            }

            if (empty($error_msg)) {
                try {
                    $up_stmt = $pdo->prepare("UPDATE products SET name = ?, category = ?, brand = ?, description = ?, price = ?, stock = ?, image = ?, status = ? WHERE id = ?");
                    $up_stmt->execute([
                        $name,
                        $category,
                        $brand,
                        $description,
                        $price,
                        $stock,
                        $image_filename,
                        $status,
                        $product_id
                    ]);

                    set_flash('success', "Product \"{$name}\" updated successfully!");
                    header('Location: ' . BASE_URL . '/admin/products.php');
                    exit;
                } catch (PDOException $e) {
                    error_log("Product edit error: " . $e->getMessage());
                    $error_msg = "Database error: Could not update product. " . $e->getMessage();
                }
            }
        }
    }
}

$page_title = "Edit Product: " . e($product['name']);
require_once __DIR__ . '/includes/admin_header.php';

// Image preview resolution
$img = !empty($product['image']) ? $product['image'] : 'default-product.svg';
$img_src = file_exists(__DIR__ . '/../uploads/products/' . $img) ? BASE_URL . '/uploads/products/' . $img : BASE_URL . '/assets/images/' . $img;
?>

<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="card border-0 rounded-4 shadow-sm bg-white p-4 p-md-5 border border-light-subtle">
            <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                <h4 class="fw-bold text-dark mb-0">
                    <i class="bi bi-pencil-square text-primary me-2"></i> Edit Product Details
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

            <form action="<?= BASE_URL ?>/admin/product_edit.php?id=<?= $product_id ?>" method="POST" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <input type="hidden" name="id" value="<?= $product_id ?>">

                <div class="row g-3 mb-3">
                    <div class="col-md-8">
                        <label class="form-label">Product Title / Model Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="<?= e($_POST['name'] ?? $product['name']) ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Brand / Manufacturer <span class="text-danger">*</span></label>
                        <input type="text" name="brand" class="form-control" value="<?= e($_POST['brand'] ?? $product['brand']) ?>" required>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Category <span class="text-danger">*</span></label>
                        <select name="category" class="form-select" required>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= e($cat) ?>" <?= ($_POST['category'] ?? $product['category']) === $cat ? 'selected' : '' ?>><?= e($cat) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Price (INR ₹) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">₹</span>
                            <input type="number" step="0.01" name="price" class="form-control" value="<?= e($_POST['price'] ?? $product['price']) ?>" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Stock Quantity <span class="text-danger">*</span></label>
                        <input type="number" name="stock" class="form-control" min="0" value="<?= e($_POST['stock'] ?? $product['stock']) ?>" required>
                    </div>
                </div>

                <div class="row g-3 mb-3 align-items-center">
                    <div class="col-md-3 text-center">
                        <span class="small text-muted d-block mb-1">Current Image</span>
                        <img src="<?= e($img_src) ?>" alt="Product Preview" class="rounded-3 border" style="width: 80px; height: 80px; object-fit: contain; background: #f8fafc;">
                    </div>
                    <div class="col-md-5">
                        <label class="form-label">Replace Product Image <span class="text-muted small">(Optional)</span></label>
                        <input type="file" name="image" class="form-control" accept="image/jpeg,image/png,image/webp">
                        <small class="text-muted">Leave empty to keep existing image.</small>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Catalog Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select">
                            <option value="Active" <?= ($_POST['status'] ?? $product['status']) === 'Active' ? 'selected' : '' ?>>Active (Visible on public store)</option>
                            <option value="Inactive" <?= ($_POST['status'] ?? $product['status']) === 'Inactive' ? 'selected' : '' ?>>Inactive (Hidden)</option>
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Detailed Description &amp; Specifications <span class="text-danger">*</span></label>
                    <textarea name="description" rows="5" class="form-control" required><?= e($_POST['description'] ?? $product['description']) ?></textarea>
                </div>

                <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                    <a href="<?= BASE_URL ?>/admin/products.php" class="btn btn-outline-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary fw-bold px-4">
                        <i class="bi bi-save me-1"></i> Update Product
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>

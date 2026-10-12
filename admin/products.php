<?php
/**
 * Zenith Systems and Services - Admin Product Inventory
 */
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

$pdo = getDB();

$categories = ['Desktop', 'Laptop', 'Storage', 'RAM', 'Accessories', 'Printer', 'Networking', 'Other'];

$selected_category = $_GET['category'] ?? '';
$search_query = trim($_GET['q'] ?? '');

$sql = "SELECT * FROM products WHERE 1=1";
$params = [];

if (!empty($selected_category) && in_array($selected_category, $categories)) {
    $sql .= " AND category = ?";
    $params[] = $selected_category;
}

if (!empty($search_query)) {
    $sql .= " AND (name LIKE ? OR brand LIKE ?)";
    $term = "%{$search_query}%";
    $params[] = $term;
    $params[] = $term;
}

$sql .= " ORDER BY id DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();

$page_title = "Product Inventory Management";
require_once __DIR__ . '/includes/admin_header.php';
?>

<!-- Action Bar -->
<div class="card border-0 rounded-4 shadow-sm bg-white p-3 mb-4 border border-light-subtle">
    <div class="row g-3 align-items-center justify-content-between">
        <div class="col-md-8">
            <form action="<?= BASE_URL ?>/admin/products.php" method="GET" class="row g-2">
                <div class="col-sm-7">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="q" value="<?= e($search_query) ?>" class="form-control bg-light border-start-0" placeholder="Search by name or brand...">
                    </div>
                </div>
                <div class="col-sm-4">
                    <select name="category" class="form-select bg-light" onchange="this.form.submit()">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= e($cat) ?>" <?= $selected_category === $cat ? 'selected' : '' ?>><?= e($cat) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-sm-1">
                    <a href="<?= BASE_URL ?>/admin/products.php" class="btn btn-outline-secondary w-100" title="Reset">
                        <i class="bi bi-arrow-clockwise"></i>
                    </a>
                </div>
            </form>
        </div>
        <div class="col-md-3 text-md-end">
            <a href="<?= BASE_URL ?>/admin/product_add.php" class="btn btn-primary w-100 w-md-auto">
                <i class="bi bi-plus-circle me-1"></i> Add New Product
            </a>
        </div>
    </div>
</div>

<!-- Products Table -->
<div class="card border-0 rounded-4 shadow-sm bg-white overflow-hidden border border-light-subtle">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light small text-uppercase text-muted">
                <tr>
                    <th style="width: 70px;">Image</th>
                    <th>Product Name &amp; Brand</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($products)): ?>
                    <?php foreach ($products as $prod): ?>
                        <?php
                        $img = !empty($prod['image']) ? $prod['image'] : 'default-product.svg';
                        $img_src = file_exists(__DIR__ . '/../uploads/products/' . $img) ? BASE_URL . '/uploads/products/' . $img : BASE_URL . '/assets/images/' . $img;
                        ?>
                        <tr>
                            <td>
                                <img src="<?= e($img_src) ?>" alt="<?= e($prod['name']) ?>" class="rounded-3 border" style="width: 52px; height: 52px; object-fit: contain; background: #f8fafc;">
                            </td>
                            <td>
                                <div class="fw-bold text-dark text-truncate" style="max-width: 320px;">
                                    <?= e($prod['name']) ?>
                                </div>
                                <span class="badge bg-light text-secondary border small"><?= e($prod['brand']) ?></span>
                            </td>
                            <td>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2.5 py-1">
                                    <?= e($prod['category']) ?>
                                </span>
                            </td>
                            <td>
                                <strong class="text-dark"><?= format_inr($prod['price']) ?></strong>
                            </td>
                            <td>
                                <?= get_stock_badge($prod['stock']) ?>
                            </td>
                            <td>
                                <?php if ($prod['status'] === 'Active'): ?>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 rounded-pill">Active</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2.5 py-1 rounded-pill">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="<?= BASE_URL ?>/product_details.php?id=<?= $prod['id'] ?>" target="_blank" class="btn btn-outline-secondary" title="Preview Public Page">
                                        <i class="bi bi-box-arrow-up-right"></i>
                                    </a>
                                    <a href="<?= BASE_URL ?>/admin/product_edit.php?id=<?= $prod['id'] ?>" class="btn btn-outline-primary" title="Edit Product">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $prod['id'] ?>" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>

                                <!-- Delete Confirmation Modal -->
                                <div class="modal fade" id="deleteModal<?= $prod['id'] ?>" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0 shadow">
                                            <div class="modal-header border-bottom">
                                                <h5 class="modal-title fw-bold text-danger">Confirm Product Deletion</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body text-start py-4">
                                                <p>Are you sure you want to permanently delete <strong>"<?= e($prod['name']) ?>"</strong>?</p>
                                                <div class="alert alert-warning small mb-0">
                                                    <i class="bi bi-exclamation-triangle me-1"></i> This action cannot be undone. Associated product images will also be removed.
                                                </div>
                                            </div>
                                            <div class="modal-footer border-top">
                                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                                                <form action="<?= BASE_URL ?>/admin/product_delete.php" method="POST">
                                                    <?= csrf_field() ?>
                                                    <input type="hidden" name="id" value="<?= $prod['id'] ?>">
                                                    <button type="submit" class="btn btn-danger btn-sm">Yes, Delete Permanently</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-box-seam display-6 d-block mb-2"></i>
                            No products found in inventory.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>

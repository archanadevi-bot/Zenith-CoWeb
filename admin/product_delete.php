<?php
/**
 * Zenith Systems and Services - Delete Product Handler
 */
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

// Auth check
require_admin_auth();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validate_csrf()) {
        set_flash('danger', 'Security session expired. Please try again.');
        header('Location: ' . BASE_URL . '/admin/products.php');
        exit;
    }

    $product_id = intval($_POST['id'] ?? 0);
    $pdo = getDB();

    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch();

    if ($product) {
        // Unlink image file if present in uploads directory
        if (!empty($product['image'])) {
            $image_path = __DIR__ . '/../uploads/products/' . $product['image'];
            if (file_exists($image_path) && is_file($image_path)) {
                @unlink($image_path);
            }
        }

        $del_stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
        $del_stmt->execute([$product_id]);

        set_flash('success', "Product \"{$product['name']}\" deleted successfully.");
    } else {
        set_flash('danger', 'Product not found.');
    }
}

header('Location: ' . BASE_URL . '/admin/products.php');
exit;

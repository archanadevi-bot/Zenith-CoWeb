<?php
/**
 * Zenith Systems and Services - Admin Header & Navigation
 */
require_once __DIR__ . '/../auth.php';

$current_script = basename($_SERVER['PHP_SELF']);
$admin_name = $_SESSION['admin_name'] ?? 'Administrator';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? 'Admin Dashboard' ?> | Zenith Systems Admin</title>
    <!-- Preconnect to Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Custom Style -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/style.css?v=2.0">
    <link rel="icon" type="image/svg+xml" href="<?= BASE_URL ?>/assets/images/zenith-logo.svg">
</head>
<body class="bg-light">

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar Navigation -->
        <aside class="col-lg-2 col-md-3 p-0 admin-sidebar d-flex flex-column">
            <div class="p-3 border-bottom border-light-subtle text-center">
                <a href="<?= BASE_URL ?>/admin/dashboard.php" class="d-block" title="Zenith Admin Suite">
                    <img src="<?= BASE_URL ?>/assets/images/zenith-logo.svg" alt="Zenith Admin Logo" style="height: 40px;">
                </a>
                <span class="badge bg-primary text-white mt-2 px-2.5 py-1 small">Admin Control Suite</span>
            </div>

            <nav class="my-3 flex-grow-1">
                <a href="<?= BASE_URL ?>/admin/dashboard.php" class="admin-nav-item <?= $current_script === 'dashboard.php' ? 'active' : '' ?>">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
                <a href="<?= BASE_URL ?>/admin/products.php" class="admin-nav-item <?= in_array($current_script, ['products.php', 'product_add.php', 'product_edit.php']) ? 'active' : '' ?>">
                    <i class="bi bi-box-seam"></i> Products Inventory
                </a>
                <a href="<?= BASE_URL ?>/admin/service_requests.php" class="admin-nav-item <?= in_array($current_script, ['service_requests.php', 'service_request_view.php']) ? 'active' : '' ?>">
                    <i class="bi bi-wrench-adjustable"></i> Service Desk
                </a>
                <a href="<?= BASE_URL ?>/admin/enquiries.php" class="admin-nav-item <?= in_array($current_script, ['enquiries.php', 'enquiry_view.php']) ? 'active' : '' ?>">
                    <i class="bi bi-envelope"></i> Customer Enquiries
                </a>
                <div class="border-top border-light-subtle my-3 mx-3"></div>
                <a href="<?= BASE_URL ?>/index.php" target="_blank" class="admin-nav-item text-primary">
                    <i class="bi bi-box-arrow-up-right"></i> View Public Site
                </a>
            </nav>

            <!-- User Info & Logout at bottom of sidebar -->
            <div class="p-3 border-top border-light-subtle bg-light">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold" style="width: 34px; height: 34px; font-size: 0.85rem;">
                        <?= strtoupper(substr($admin_name, 0, 1)) ?>
                    </div>
                    <div class="overflow-hidden">
                        <h6 class="text-dark mb-0 small text-truncate fw-bold"><?= e($admin_name) ?></h6>
                        <span class="text-muted" style="font-size: 0.75rem;">Administrator</span>
                    </div>
                </div>
                <a href="<?= BASE_URL ?>/admin/logout.php" class="btn btn-outline-danger btn-sm w-100 py-1" style="font-size: 0.8rem;">
                    <i class="bi bi-box-arrow-right me-1"></i> Logout
                </a>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="col-lg-10 col-md-9 ms-auto p-4 p-md-5">
            <!-- Admin Top Bar -->
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 pb-3 border-bottom">
                <div>
                    <h3 class="fw-bold mb-0 text-dark"><?= $page_title ?? 'Dashboard' ?></h3>
                    <span class="text-muted small">ZENITH SYSTEMS AND SERVICES • Control Center</span>
                </div>
                <div class="d-flex gap-2 mt-2 mt-sm-0">
                    <a href="<?= BASE_URL ?>/admin/product_add.php" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-lg me-1"></i> Add Product
                    </a>
                    <a href="<?= BASE_URL ?>/admin/service_requests.php" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-wrench me-1"></i> Service Desk
                    </a>
                </div>
            </div>

            <!-- Global Flash message display -->
            <?= display_flash() ?>

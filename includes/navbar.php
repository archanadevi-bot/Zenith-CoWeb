<?php
/**
 * Zenith Systems and Services - Navigation Bar
 */
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!-- Top Business Info Bar -->
<div class="top-info-bar d-none d-md-block">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7 col-md-6 d-flex align-items-center">
                <span class="info-item"><i class="bi bi-geo-alt-fill text-danger me-1"></i> Kannampalayam Road, Sulur, Coimbatore</span>
                <span class="dot-divider"></span>
                <span class="info-item"><i class="bi bi-clock-fill text-warning me-1"></i> Mon - Sat: 9:30 AM - 8:30 PM</span>
            </div>
            <div class="col-lg-5 col-md-6 text-end d-flex align-items-center justify-content-end gap-3">
                <a href="tel:<?= BUSINESS_PHONE ?>" class="info-item fw-semibold">
                    <i class="bi bi-telephone-fill text-success me-1"></i> <?= BUSINESS_PHONE_DISPLAY ?>
                </a>
                <a href="<?= whatsapp_url('Hello Zenith Systems, I would like to inquire about computer sales and repair services in Sulur.') ?>" target="_blank" class="badge-whatsapp-top">
                    <i class="bi bi-whatsapp"></i> WhatsApp Us
                </a>
                <a href="<?= BASE_URL ?>/admin/login.php" class="text-secondary" title="Staff Admin Portal" aria-label="Staff Admin Portal">
                    <i class="bi bi-shield-lock"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Main Sticky Navbar -->
<nav class="navbar navbar-expand-lg main-navbar sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="<?= BASE_URL ?>/index.php" title="Zenith Systems and Services">
            <img src="<?= BASE_URL ?>/assets/images/zenith-logo.svg" alt="Zenith Systems and Services Logo" class="brand-logo-img">
        </a>
        
        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#zenithNavbar" aria-controls="zenithNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="zenithNavbar">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center gap-lg-1">
                <li class="nav-item">
                    <a class="nav-link <?= $current_page === 'index.php' ? 'active' : '' ?>" href="<?= BASE_URL ?>/index.php">
                        <i class="bi bi-house-door me-1 d-lg-none"></i> Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $current_page === 'products.php' || $current_page === 'product_details.php' ? 'active' : '' ?>" href="<?= BASE_URL ?>/products.php">
                        <i class="bi bi-laptop me-1 d-lg-none"></i> Products
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $current_page === 'services.php' ? 'active' : '' ?>" href="<?= BASE_URL ?>/services.php">
                        <i class="bi bi-tools me-1 d-lg-none"></i> Services
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $current_page === 'track_service.php' ? 'active' : '' ?>" href="<?= BASE_URL ?>/track_service.php">
                        <i class="bi bi-search me-1"></i> Track Service
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $current_page === 'about.php' ? 'active' : '' ?>" href="<?= BASE_URL ?>/about.php">
                        <i class="bi bi-info-circle me-1 d-lg-none"></i> About
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $current_page === 'contact.php' ? 'active' : '' ?>" href="<?= BASE_URL ?>/contact.php">
                        <i class="bi bi-envelope me-1 d-lg-none"></i> Contact
                    </a>
                </li>
                <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
                    <a href="<?= BASE_URL ?>/service_request.php" class="btn btn-primary btn-sm px-3 py-2 text-white d-inline-flex align-items-center shadow-sm">
                        <i class="bi bi-wrench-adjustable-circle-fill me-1.5"></i> Book Repair
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

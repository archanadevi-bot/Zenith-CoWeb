<?php
/**
 * Zenith Systems and Services - Homepage
 */
$page_title = "Zenith Systems & Services | Computer Sales & Laptop Repair Sulur, Coimbatore";
$meta_desc = "Zenith Systems and Services in Sulur, Coimbatore. Top destination for Dell/Lenovo/HP laptops, custom PC builds, NVMe SSD & RAM upgrades, chip-level laptop repair, and printer service.";

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

$pdo = getDB();

// Fetch 6 Featured Active Products
$stmt = $pdo->prepare("SELECT * FROM products WHERE status = 'Active' ORDER BY id ASC LIMIT 6");
$stmt->execute();
$featured_products = $stmt->fetchAll();
?>

<!-- Hero Banner Section -->
<section class="hero-section">
    <div class="container position-relative" style="z-index: 2;">
        <div class="row align-items-center g-5">
            <div class="col-lg-7 text-center text-lg-start">
                <div class="hero-badge mb-3">
                    <i class="bi bi-shield-check text-primary"></i> Sulur's Most Trusted Computer Hub
                </div>
                <h1 class="hero-title mb-3">
                    Precision <span class="hero-title-accent">Computer Sales</span> &amp; Chip-Level <span class="hero-title-accent">Repair Lab</span>
                </h1>
                <p class="lead text-secondary mb-4 pe-lg-4 fs-6">
                    From blazing-fast custom gaming rigs and business laptops to motherboard chip-level micro-soldering, SSD speed upgrades, and genuine computer accessories in Sulur, Coimbatore.
                </p>
                
                <div class="d-flex flex-wrap justify-content-center justify-content-lg-start gap-2.5 mb-4">
                    <a href="<?= BASE_URL ?>/products.php" class="btn btn-primary btn-lg">
                        <i class="bi bi-laptop"></i> Browse Products
                    </a>
                    <a href="<?= BASE_URL ?>/service_request.php" class="btn btn-outline-primary btn-lg">
                        <i class="bi bi-wrench-adjustable-circle"></i> Book Repair
                    </a>
                    <a href="<?= whatsapp_url('Hello Zenith Systems, I want to inquire about a computer product or service.') ?>" target="_blank" class="btn btn-whatsapp btn-lg">
                        <i class="bi bi-whatsapp"></i> WhatsApp Us
                    </a>
                </div>

                <div class="d-flex flex-wrap justify-content-center justify-content-lg-start align-items-center gap-4 text-muted small pt-3 border-top border-light-subtle">
                    <div class="d-flex align-items-center"><i class="bi bi-check-circle-fill text-success me-1.5"></i> 100% Genuine Components</div>
                    <div class="d-flex align-items-center"><i class="bi bi-check-circle-fill text-success me-1.5"></i> Same-Day Diagnostic</div>
                    <div class="d-flex align-items-center"><i class="bi bi-check-circle-fill text-success me-1.5"></i> 90-Day Warranty</div>
                </div>
            </div>
            
            <div class="col-lg-5">
                <!-- Interactive Quick Service Tracker Card on Hero -->
                <div class="glass-card">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="service-icon-box mb-0" style="width: 46px; height: 46px; font-size: 1.25rem;">
                            <i class="bi bi-radar"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fs-5 fw-bold text-dark">Live Service Tracker</h4>
                            <span class="text-muted small">Check real-time status of your device</span>
                        </div>
                    </div>
                    
                    <form action="<?= BASE_URL ?>/track_service.php" method="GET" class="mb-3">
                        <div class="input-group mb-2">
                            <span class="input-group-text">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" name="query" class="form-control font-monospace" placeholder="e.g. ZSS-2026-00001 or Mobile" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 fw-bold">
                            <i class="bi bi-arrow-right-circle me-1"></i> Track Device Now
                        </button>
                    </form>
                    
                    <div class="p-2.5 rounded-3 bg-light text-secondary small border">
                        <div class="d-flex justify-content-between align-items-center">
                            <span><i class="bi bi-info-circle text-primary me-1"></i> Need instant help?</span>
                            <a href="tel:<?= BUSINESS_PHONE ?>" class="text-primary fw-bold text-decoration-none">
                                <i class="bi bi-telephone me-1"></i> <?= BUSINESS_PHONE_DISPLAY ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Counter Bar -->
<div class="container">
    <div class="stats-bar">
        <div class="row g-0">
            <div class="col-6 col-lg-3 stat-item">
                <div class="stat-number text-primary">10+</div>
                <div class="stat-label">Years Experience</div>
            </div>
            <div class="col-6 col-lg-3 stat-item">
                <div class="stat-number text-primary">5,000+</div>
                <div class="stat-label">Devices Serviced</div>
            </div>
            <div class="col-6 col-lg-3 stat-item">
                <div class="stat-number text-success">100%</div>
                <div class="stat-label">Genuine OEM Parts</div>
            </div>
            <div class="col-6 col-lg-3 stat-item">
                <div class="stat-number text-warning">4.9 ★</div>
                <div class="stat-label">Customer Rating</div>
            </div>
        </div>
    </div>
</div>

<!-- Core Services Section -->
<section class="py-5 mt-4">
    <div class="container py-4">
        <div class="text-center max-w-700 mx-auto mb-5">
            <span class="section-tag">Expert Computer Care</span>
            <h2 class="section-title">Comprehensive IT &amp; Repair Services</h2>
            <p class="text-muted">Sulur's equipped service lab for chip-level repair, hardware upgrades, and enterprise computer maintenance.</p>
        </div>
        
        <div class="row g-4">
            <!-- Service 1 -->
            <div class="col-md-6 col-lg-3">
                <div class="service-card">
                    <div class="service-icon-box">
                        <i class="bi bi-laptop"></i>
                    </div>
                    <h3 class="fs-5 mb-2">Laptop Chip-Level Repair</h3>
                    <p class="text-muted small flex-grow-1">Motherboard diagnostics, broken hinge fabrication, display replacement, battery and keyboard fixes.</p>
                    <a href="<?= BASE_URL ?>/service_request.php?service=Laptop%20Repair" class="btn btn-outline-primary btn-sm mt-2">
                        Book Repair <i class="bi bi-chevron-right ms-1"></i>
                    </a>
                </div>
            </div>
            
            <!-- Service 2 -->
            <div class="col-md-6 col-lg-3">
                <div class="service-card">
                    <div class="service-icon-box">
                        <i class="bi bi-speedometer2"></i>
                    </div>
                    <h3 class="fs-5 mb-2">SSD &amp; RAM Speed Upgrade</h3>
                    <p class="text-muted small flex-grow-1">Make slow laptops 300% faster with high-speed Gen4 NVMe / SATA SSDs and DDR4/DDR5 RAM upgrades.</p>
                    <a href="<?= BASE_URL ?>/service_request.php?service=SSD%20%26%20RAM%20Upgrade" class="btn btn-outline-primary btn-sm mt-2">
                        Upgrade Now <i class="bi bi-chevron-right ms-1"></i>
                    </a>
                </div>
            </div>
            
            <!-- Service 3 -->
            <div class="col-md-6 col-lg-3">
                <div class="service-card">
                    <div class="service-icon-box">
                        <i class="bi bi-pc-display-horizontal"></i>
                    </div>
                    <h3 class="fs-5 mb-2">Custom PC Assembly</h3>
                    <p class="text-muted small flex-grow-1">Custom build gaming PCs, office accounting systems, and 4K architectural render workstations.</p>
                    <a href="<?= BASE_URL ?>/service_request.php?service=Custom%20PC%20Assembly" class="btn btn-outline-primary btn-sm mt-2">
                        Configure PC <i class="bi bi-chevron-right ms-1"></i>
                    </a>
                </div>
            </div>
            
            <!-- Service 4 -->
            <div class="col-md-6 col-lg-3">
                <div class="service-card">
                    <div class="service-icon-box">
                        <i class="bi bi-printer"></i>
                    </div>
                    <h3 class="fs-5 mb-2">Printer Service &amp; Refill</h3>
                    <p class="text-muted small flex-grow-1">Laser printer repairs, inkjet head unclogging, cartridge toner refills, and continuous ink tank setup.</p>
                    <a href="<?= BASE_URL ?>/service_request.php?service=Printer%20Service" class="btn btn-outline-primary btn-sm mt-2">
                        Service Printer <i class="bi bi-chevron-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-4">
            <a href="<?= BASE_URL ?>/services.php" class="btn btn-outline-secondary px-4 py-2">
                View All Services &amp; Pricing <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
</section>

<!-- Featured Products Section -->
<section class="py-5 bg-white border-top border-bottom border-light-subtle">
    <div class="container py-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-4">
            <div>
                <span class="section-tag">Direct From Authorized Stockists</span>
                <h2 class="section-title mb-0">Featured Hardware &amp; Laptops</h2>
            </div>
            <a href="<?= BASE_URL ?>/products.php" class="btn btn-outline-primary mt-3 mt-md-0">
                View All Products <i class="bi bi-grid-fill ms-1"></i>
            </a>
        </div>
        
        <div class="row g-4">
            <?php foreach ($featured_products as $prod): ?>
                <?php
                // Image resolution helper
                $img_path = !empty($prod['image']) ? $prod['image'] : 'default-product.svg';
                if (file_exists(__DIR__ . '/uploads/products/' . $img_path)) {
                    $img_url = BASE_URL . '/uploads/products/' . $img_path;
                } elseif (file_exists(__DIR__ . '/assets/images/' . $img_path)) {
                    $img_url = BASE_URL . '/assets/images/' . $img_path;
                } else {
                    $img_url = BASE_URL . '/assets/images/default-product.svg';
                }
                ?>
                <div class="col-md-6 col-lg-4">
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
    </div>
</section>

<!-- Why Choose Zenith Section -->
<section class="py-5 bg-light">
    <div class="container py-4">
        <div class="text-center max-w-700 mx-auto mb-5">
            <span class="section-tag">The Zenith Advantage</span>
            <h2 class="section-title">Why Choose Zenith Systems &amp; Services?</h2>
            <p class="text-muted">Delivering reliable computer solutions, transparent billing, and dedicated post-service assistance to Sulur and Coimbatore.</p>
        </div>
        
        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="card border-0 rounded-4 p-4 h-100 border border-light-subtle shadow-sm bg-white">
                    <div class="text-primary fs-2 mb-3">
                        <i class="bi bi-patch-check-fill"></i>
                    </div>
                    <h3 class="fs-5 fw-bold text-dark">100% Genuine Parts</h3>
                    <p class="text-muted small mb-0">We source directly from authorized brand distributors like Samsung, Kingston, Crucial, Dell, and HP with official manufacturer warranties.</p>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-3">
                <div class="card border-0 rounded-4 p-4 h-100 border border-light-subtle shadow-sm bg-white">
                    <div class="text-primary fs-2 mb-3">
                        <i class="bi bi-cpu-fill"></i>
                    </div>
                    <h3 class="fs-5 fw-bold text-dark">Chip-Level Expertise</h3>
                    <p class="text-muted small mb-0">Equipped with BGA rework stations, digital microscopes, and oscilloscope diagnostics for delicate motherboard chip repairs.</p>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-3">
                <div class="card border-0 rounded-4 p-4 h-100 border border-light-subtle shadow-sm bg-white">
                    <div class="text-success fs-2 mb-3">
                        <i class="bi bi-stopwatch-fill"></i>
                    </div>
                    <h3 class="fs-5 fw-bold text-dark">Fast Turnaround</h3>
                    <p class="text-muted small mb-0">Same-day turnaround on SSD/RAM upgrades, OS installations, and virus cleaning. Rapid component replacements.</p>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-3">
                <div class="card border-0 rounded-4 p-4 h-100 border border-light-subtle shadow-sm bg-white">
                    <div class="text-warning fs-2 mb-3">
                        <i class="bi bi-qr-code-scan"></i>
                    </div>
                    <h3 class="fs-5 fw-bold text-dark">Live Code Tracking</h3>
                    <p class="text-muted small mb-0">Every service device receives an online tracking number with multi-stage status visibility and WhatsApp alerts.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call To Action Banner -->
<section class="py-5" style="background: linear-gradient(135deg, #0b192c 0%, #172d47 100%); color: #ffffff;">
    <div class="container py-4 text-center">
        <h2 class="display-6 fw-bold text-white mb-3">Experiencing Laptop or PC Issues in Sulur?</h2>
        <p class="lead text-light text-opacity-75 max-w-700 mx-auto mb-4 fs-6">
            Get an instant diagnostic quote or book a doorstep device pickup anywhere along Kannampalayam Road, Sulur, and surrounding Coimbatore areas.
        </p>
        <div class="d-flex flex-wrap justify-content-center gap-3">
            <a href="<?= BASE_URL ?>/service_request.php" class="btn btn-primary btn-lg px-4 py-2.5 shadow-sm">
                <i class="bi bi-wrench-adjustable me-2"></i> Book Repair Service
            </a>
            <a href="tel:<?= BUSINESS_PHONE ?>" class="btn btn-outline-light btn-lg px-4 py-2.5">
                <i class="bi bi-telephone-fill me-2"></i> Call <?= BUSINESS_PHONE_DISPLAY ?>
            </a>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

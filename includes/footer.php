<?php
/**
 * Zenith Systems and Services - Footer Template
 */
?>
<!-- Main Footer -->
<footer class="main-footer mt-auto">
    <div class="container">
        <div class="row g-4 mb-4">
            <!-- Col 1: Brand & Bio -->
            <div class="col-lg-4 col-md-6">
                <img src="<?= BASE_URL ?>/assets/images/zenith-logo-light.svg" alt="Zenith Systems Logo" class="mb-3" style="height: 42px;">
                <p class="text-secondary small pe-lg-4 text-light text-opacity-75">
                    ZENITH SYSTEMS AND SERVICES is Sulur's premier computer sales, laptop chip-level repair, hardware speed upgrade, and custom workstation assembly center. Dedicated to genuine parts, speedy turnaround, and guaranteed customer satisfaction.
                </p>
                <div class="d-flex gap-2 mt-3">
                    <a href="tel:<?= BUSINESS_PHONE ?>" class="btn btn-outline-light btn-sm rounded-circle d-flex align-items-center justify-content-center p-0" style="width: 36px; height: 36px;" title="Call Store" aria-label="Call Store">
                        <i class="bi bi-telephone-fill"></i>
                    </a>
                    <a href="<?= whatsapp_url('Hello Zenith Systems, I want to inquire about IT services.') ?>" target="_blank" class="btn btn-outline-light btn-sm rounded-circle d-flex align-items-center justify-content-center p-0 text-success" style="width: 36px; height: 36px;" title="WhatsApp" aria-label="WhatsApp">
                        <i class="bi bi-whatsapp"></i>
                    </a>
                    <a href="mailto:<?= BUSINESS_EMAIL ?>" class="btn btn-outline-light btn-sm rounded-circle d-flex align-items-center justify-content-center p-0 text-info" style="width: 36px; height: 36px;" title="Email Us" aria-label="Email Us">
                        <i class="bi bi-envelope-fill"></i>
                    </a>
                    <a href="<?= BUSINESS_MAP_LINK ?>" target="_blank" class="btn btn-outline-light btn-sm rounded-circle d-flex align-items-center justify-content-center p-0 text-warning" style="width: 36px; height: 36px;" title="Google Maps" aria-label="Google Maps">
                        <i class="bi bi-geo-alt-fill"></i>
                    </a>
                </div>
            </div>
            
            <!-- Col 2: Quick Navigation -->
            <div class="col-lg-2 col-md-6 col-6">
                <h5 class="footer-heading">Quick Links</h5>
                <ul class="footer-links">
                    <li><a href="<?= BASE_URL ?>/index.php"><i class="bi bi-chevron-right small me-1"></i> Home</a></li>
                    <li><a href="<?= BASE_URL ?>/products.php"><i class="bi bi-chevron-right small me-1"></i> All Products</a></li>
                    <li><a href="<?= BASE_URL ?>/services.php"><i class="bi bi-chevron-right small me-1"></i> Repair Services</a></li>
                    <li><a href="<?= BASE_URL ?>/service_request.php"><i class="bi bi-chevron-right small me-1"></i> Book Repair</a></li>
                    <li><a href="<?= BASE_URL ?>/track_service.php"><i class="bi bi-chevron-right small me-1"></i> Track Service</a></li>
                    <li><a href="<?= BASE_URL ?>/about.php"><i class="bi bi-chevron-right small me-1"></i> About Us</a></li>
                    <li><a href="<?= BASE_URL ?>/contact.php"><i class="bi bi-chevron-right small me-1"></i> Contact Us</a></li>
                </ul>
            </div>
            
            <!-- Col 3: Core Services -->
            <div class="col-lg-3 col-md-6 col-6">
                <h5 class="footer-heading">Our Services</h5>
                <ul class="footer-links">
                    <li><a href="<?= BASE_URL ?>/service_request.php?service=Laptop%20Repair"><i class="bi bi-tools small me-1"></i> Laptop Chip Repair</a></li>
                    <li><a href="<?= BASE_URL ?>/service_request.php?service=Desktop%20Repair"><i class="bi bi-pc-display small me-1"></i> PC Troubleshooting</a></li>
                    <li><a href="<?= BASE_URL ?>/service_request.php?service=SSD%20%26%20RAM%20Upgrade"><i class="bi bi-lightning-charge small me-1"></i> SSD &amp; RAM Upgrade</a></li>
                    <li><a href="<?= BASE_URL ?>/service_request.php?service=Custom%20PC%20Assembly"><i class="bi bi-cpu small me-1"></i> Custom PC Assembly</a></li>
                    <li><a href="<?= BASE_URL ?>/service_request.php?service=Windows%20Installation"><i class="bi bi-windows small me-1"></i> Windows &amp; OS Setup</a></li>
                    <li><a href="<?= BASE_URL ?>/service_request.php?service=Printer%20Service"><i class="bi bi-printer small me-1"></i> Printer Servicing</a></li>
                    <li><a href="<?= BASE_URL ?>/service_request.php?service=Data%20Backup"><i class="bi bi-hdd-network small me-1"></i> Data Recovery</a></li>
                </ul>
            </div>
            
            <!-- Col 4: Store & Service Center Info -->
            <div class="col-lg-3 col-md-6">
                <h5 class="footer-heading">Service Center</h5>
                <p class="text-light text-opacity-75 small mb-2">
                    <i class="bi bi-geo-alt-fill text-danger me-1.5"></i>
                    <strong>ZENITH SYSTEMS AND SERVICES</strong><br>
                    <?= BUSINESS_ADDRESS ?>
                </p>
                <p class="small mb-2">
                    <i class="bi bi-telephone-fill text-success me-1.5"></i>
                    <a href="tel:<?= BUSINESS_PHONE ?>" class="text-light fw-bold text-decoration-none"><?= BUSINESS_PHONE_DISPLAY ?></a>
                </p>
                <p class="small mb-2">
                    <i class="bi bi-envelope-fill text-info me-1.5"></i>
                    <a href="mailto:<?= BUSINESS_EMAIL ?>" class="text-light text-opacity-75 text-decoration-none"><?= BUSINESS_EMAIL ?></a>
                </p>
                <div class="p-3 mt-3 rounded-3 bg-dark border border-secondary border-opacity-25 small">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <i class="bi bi-clock-history text-warning"></i>
                        <span class="text-white fw-bold">Working Hours</span>
                    </div>
                    <span class="text-light text-opacity-75 d-block">Mon - Sat: 9:30 AM - 8:30 PM</span>
                    <span class="text-light text-opacity-75 d-block">Sunday: 10:00 AM - 2:00 PM</span>
                </div>
            </div>
        </div>
        
        <hr class="border-secondary border-opacity-25 my-3">
        
        <div class="row align-items-center small text-light text-opacity-50">
            <div class="col-md-6 text-center text-md-start mb-2 mb-md-0">
                &copy; <?= date('Y') ?> <strong><?= SITE_NAME ?></strong>. All Rights Reserved. Sulur, Coimbatore.
            </div>
            <div class="col-md-6 text-center text-md-end">
                <span>Certified Computer Sales &amp; IT Service Hub</span>
                <span class="mx-2">|</span>
                <a href="<?= BASE_URL ?>/admin/login.php" class="text-light text-opacity-50 text-decoration-none hover-white">
                    <i class="bi bi-lock me-1"></i> Staff Portal
                </a>
            </div>
        </div>
    </div>
</footer>

<!-- Floating WhatsApp Action Button -->
<a href="<?= whatsapp_url('Hello Zenith Systems, I need assistance with computer sales / service in Sulur.') ?>" target="_blank" class="floating-whatsapp" title="Chat with Zenith on WhatsApp" aria-label="Chat with Zenith on WhatsApp">
    <i class="bi bi-whatsapp"></i>
</a>

<!-- Back to Top Button -->
<button type="button" id="backToTopBtn" class="btn-back-to-top" title="Back to top" aria-label="Back to top">
    <i class="bi bi-chevron-up"></i>
</button>

<!-- Bootstrap 5.3 Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<!-- Custom App JS -->
<script src="<?= BASE_URL ?>/script.js?v=2.0"></script>
</body>
</html>

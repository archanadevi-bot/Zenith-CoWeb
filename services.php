<?php
/**
 * Zenith Systems and Services - Services Showcase
 */
$page_title = "Laptop Repair, Desktop Service & Upgrades | Zenith Systems Sulur";
$meta_desc = "Expert laptop chip-level repair, SSD & RAM upgrade, Windows installation, virus removal, printer servicing, and custom PC assembly in Sulur, Coimbatore. Call 8883332589.";

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

$services_list = [
    [
        'title' => 'Laptop Chip-Level Repair',
        'icon' => 'bi-laptop',
        'badge' => 'Specialist Lab',
        'turnaround' => '24 - 48 Hours',
        'price_estimate' => 'From ₹ 499',
        'desc' => 'Precision motherboard diagnostic and micro-soldering, short circuit resolution, power-IC replacement, GPU reballing, and charging port repair.',
        'features' => ['No Display / Dead Motherboard Fix', 'Water Spill & Liquid Damage Ultrasonic Wash', 'Broken Hinge & Body Fabrication', 'OEM Screen & Battery Replacement']
    ],
    [
        'title' => 'SSD & RAM Speed Upgrade',
        'icon' => 'bi-lightning-charge',
        'badge' => 'Most Popular',
        'turnaround' => '1 - 2 Hours (Same Day)',
        'price_estimate' => 'From ₹ 1,499 (Parts + Labor)',
        'desc' => 'Transform sluggish laptops and desktops into lightning-fast systems. Upgrade from mechanical HDD to high-speed NVMe/SATA SSD and expand DDR4/DDR5 RAM.',
        'features' => ['Up to 3500 MB/s Gen4 NVMe SSDs', 'Zero Data Loss OS Cloning', 'Crucial & Samsung Authorized Memory', 'Boot Time Reduced from 90s to 8s']
    ],
    [
        'title' => 'Desktop PC Troubleshooting & Repair',
        'icon' => 'bi-pc-display',
        'badge' => 'Hardware & Power',
        'turnaround' => 'Same Day / 24 Hours',
        'price_estimate' => 'From ₹ 350',
        'desc' => 'Comprehensive desktop diagnostics including SMPS power supply failures, continuous reboot loops, RAM slot failure, CMOS battery, and processor overheating.',
        'features' => ['Power Supply (SMPS) Testing & Replacement', 'CPU Thermal Paste Re-application (Arctic MX-4)', 'Graphic Card Testing & Artifact Removal', 'Cabinet Dust Cleaning & Cable Management']
    ],
    [
        'title' => 'Custom PC Assembly & Workstations',
        'icon' => 'bi-cpu',
        'badge' => 'Gaming & Workstations',
        'turnaround' => '24 - 48 Hours',
        'price_estimate' => 'Custom Quote Based on Specs',
        'desc' => 'Tailored PC builds for gaming, 4K video rendering, AutoCAD, graphic design, and office billing. Optimized airflow, cable management, and stress testing.',
        'features' => ['Intel 12th/13th/14th Gen & AMD Ryzen AM5 Builds', 'ARGB Sync & Liquid AIO Cooler Installation', 'GeForce RTX & Radeon GPU Tuning', 'Full Component Warranty & Stress Benchmark']
    ],
    [
        'title' => 'Windows & OS Installation',
        'icon' => 'bi-windows',
        'badge' => 'Software Support',
        'turnaround' => '1 - 2 Hours',
        'price_estimate' => 'From ₹ 350',
        'desc' => 'Clean operating system installation (Windows 11 / Windows 10 / Linux) with genuine drivers, Microsoft Office suite, PDF readers, media utilities, and security configuration.',
        'features' => ['Genuine Driver Updates & Chipset Setup', 'Hard Disk Partitioning without Data Loss', 'Essential Productivity Software Bundle', 'System Speed Optimization']
    ],
    [
        'title' => 'Virus, Spyware & Malware Removal',
        'icon' => 'bi-shield-check',
        'badge' => 'Security & Cleanup',
        'turnaround' => '2 - 3 Hours',
        'price_estimate' => 'From ₹ 400',
        'desc' => 'Deep system scanning and removal of ransomware, Trojan horses, cryptominers, spyware, and rogue browser pop-ups with firewall tuning.',
        'features' => ['Complete Rootkit & Adware Eradication', 'Browser Hijack Cleanup', 'Antivirus & Threat Shield Installation', 'Registry & Startup Service Optimization']
    ],
    [
        'title' => 'Printer Service & Cartridge Refill',
        'icon' => 'bi-printer',
        'badge' => 'Laser & InkTank',
        'turnaround' => 'Same Day / 24 Hours',
        'price_estimate' => 'From ₹ 250',
        'desc' => 'Maintenance and repairs for HP, Canon, Epson, and Brother printers. Paper jam fixes, roller replacement, fuser unit overhaul, and high-yield toner refills.',
        'features' => ['Laser Toner Powder Refilling', 'InkTank Printhead Cleaning & Flushing', 'Paper Pickup Roller & Teflon Sleeve Fix', 'Wi-Fi Mobile Print Setup']
    ],
    [
        'title' => 'Data Recovery & Backup Solutions',
        'icon' => 'bi-hdd-network',
        'badge' => 'Critical Data Rescue',
        'turnaround' => '24 - 72 Hours',
        'price_estimate' => 'Diagnostic Required',
        'desc' => 'Safe data extraction from formatted hard drives, corrupted USB drives, crashed Windows partitions, and failing external hard disks.',
        'features' => ['Accidental Format & Deleted File Retrieval', 'Corrupted Partition & RAW Drive Rescue', 'External HDD Bad Sector Recovery', 'Secure Cloud & NAS Backup Configuration']
    ]
];
?>

<!-- Services Banner -->
<div class="page-hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <span class="hero-badge mb-2">
                    <i class="bi bi-gear-wide-connected me-1"></i> Certified Lab &amp; Technicians
                </span>
                <h1 class="page-hero-title">Computer &amp; Laptop Repair Services</h1>
                <p class="page-hero-desc">
                    Precision chip-level diagnosis, same-day SSD speed upgrades, and complete hardware repairs in Sulur, Coimbatore.
                </p>
            </div>
            <div class="col-lg-4 mt-3 mt-lg-0 text-lg-end">
                <a href="<?= BASE_URL ?>/service_request.php" class="btn btn-primary btn-lg">
                    <i class="bi bi-wrench-adjustable-circle me-1"></i> Book Repair Online
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Services Grid -->
<div class="container py-5">
    <div class="row g-4">
        <?php foreach ($services_list as $srv): ?>
            <div class="col-lg-6">
                <div class="service-card shadow-sm h-100 p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="service-icon-box mb-0">
                            <i class="bi <?= e($srv['icon']) ?>"></i>
                        </div>
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-1.5 rounded-pill fw-semibold">
                            <?= e($srv['badge']) ?>
                        </span>
                    </div>
                    
                    <h3 class="fs-4 fw-bold text-dark mb-2"><?= e($srv['title']) ?></h3>
                    <p class="text-secondary small mb-3"><?= e($srv['desc']) ?></p>
                    
                    <div class="row g-2 mb-3 small">
                        <div class="col-6">
                            <div class="p-2 bg-light rounded border">
                                <span class="text-muted d-block" style="font-size: 0.75rem;">ESTIMATED TIME</span>
                                <strong class="text-dark"><i class="bi bi-clock me-1 text-primary"></i> <?= e($srv['turnaround']) ?></strong>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2 bg-light rounded border">
                                <span class="text-muted d-block" style="font-size: 0.75rem;">PRICING</span>
                                <strong class="text-success"><i class="bi bi-currency-rupee me-0.5"></i> <?= e($srv['price_estimate']) ?></strong>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h6 class="fw-bold small text-uppercase text-muted mb-2">Service Highlights:</h6>
                        <ul class="list-unstyled mb-0 small text-secondary">
                            <?php foreach ($srv['features'] as $ft): ?>
                                <li class="mb-1"><i class="bi bi-check2-circle text-success me-1.5"></i> <?= e($ft) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    
                    <div class="d-flex gap-2 mt-auto pt-3 border-top">
                        <a href="<?= BASE_URL ?>/service_request.php?service=<?= urlencode($srv['title']) ?>" class="btn btn-primary btn-sm flex-grow-1">
                            <i class="bi bi-calendar-check me-1"></i> Book This Service
                        </a>
                        <button onclick="inquireWhatsAppService('<?= addslashes(e($srv['title'])) ?>')" class="btn btn-whatsapp btn-sm px-3" title="Chat on WhatsApp">
                            <i class="bi bi-whatsapp"></i> Chat
                        </button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    
    <!-- Service Process Timeline -->
    <div class="mt-5 p-4 p-md-5 bg-white rounded-4 border shadow-sm">
        <div class="text-center max-w-700 mx-auto mb-4">
            <span class="section-tag">Smooth &amp; Transparent</span>
            <h3 class="fw-bold text-dark">How Our Repair Process Works</h3>
            <p class="text-muted small">From submission to delivery, stay updated every step of the way with live tracking.</p>
        </div>
        
        <div class="row g-4 text-center">
            <div class="col-md-3">
                <div class="p-3">
                    <div class="step-circle bg-primary text-white mx-auto mb-2 fw-bold">1</div>
                    <h6 class="fw-bold text-dark">Book or Drop In</h6>
                    <p class="text-muted small mb-0">Submit online form or bring your device to our Sulur lab on Kannampalayam Road.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3">
                    <div class="step-circle bg-info text-white mx-auto mb-2 fw-bold">2</div>
                    <h6 class="fw-bold text-dark">Diagnostic &amp; Quote</h6>
                    <p class="text-muted small mb-0">Technicians inspect device, test components, and share transparent quote with tracking code.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3">
                    <div class="step-circle bg-warning text-dark mx-auto mb-2 fw-bold">3</div>
                    <h6 class="fw-bold text-dark">Precision Repair</h6>
                    <p class="text-muted small mb-0">Chip-level repair, genuine part replacement, and multi-point thermal and speed testing.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3">
                    <div class="step-circle bg-success text-white mx-auto mb-2 fw-bold">4</div>
                    <h6 class="fw-bold text-dark">Testing &amp; Handover</h6>
                    <p class="text-muted small mb-0">Receive completion SMS/WhatsApp alert, collect your revived device with warranty card.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

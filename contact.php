<?php
/**
 * Zenith Systems and Services - Contact & Enquiries
 */
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';

$pdo = getDB();
$enquiry_success = false;
$error_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validate_csrf()) {
        $error_msg = "Security session expired. Please refresh the page and try again.";
    } else {
        $name = trim($_POST['name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $subject = trim($_POST['subject'] ?? '');
        $message = trim($_POST['message'] ?? '');

        if (empty($name) || empty($phone) || empty($message)) {
            $error_msg = "Please provide your Name, Mobile Number, and Message.";
        } elseif (strlen(preg_replace('/[^0-9]/', '', $phone)) < 10) {
            $error_msg = "Please provide a valid 10-digit mobile number.";
        } else {
            try {
                $stmt = $pdo->prepare("INSERT INTO enquiries (name, phone, email, subject, message, status, created_at) VALUES (?, ?, ?, ?, ?, 'New', NOW())");
                $stmt->execute([$name, $phone, $email, $subject, $message]);
                $enquiry_success = true;
                set_flash('success', 'Thank you! Your enquiry has been sent. Our team will contact you shortly.');
            } catch (PDOException $e) {
                error_log("Enquiry insert error: " . $e->getMessage());
                $error_msg = "Unable to submit enquiry at this time. Please call us at " . BUSINESS_PHONE_DISPLAY;
            }
        }
    }
}

$page_title = "Contact Zenith Systems & Services | Sulur, Coimbatore";
$meta_desc = "Contact Zenith Systems and Services for computer sales, laptop repairs, and IT support in Sulur, Coimbatore. Phone: +91 8883332589, Email: info@zenithsystems.in.";

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<!-- Banner -->
<div class="page-hero">
    <div class="container">
        <span class="hero-badge mb-2">
            <i class="bi bi-geo-alt-fill me-1"></i> Sulur, Coimbatore
        </span>
        <h1 class="page-hero-title">Get in Touch with Zenith Systems</h1>
        <p class="page-hero-desc">
            Have questions about laptop repairs, custom PC configurations, or hardware availability? We are here to help.
        </p>
    </div>
</div>

<div class="container py-5">
    <?= display_flash() ?>

    <div class="row g-5">
        <!-- Contact Information & Map -->
        <div class="col-lg-5">
            <div class="bg-white p-4 rounded-4 border border-light-subtle shadow-sm mb-4">
                <h4 class="fw-bold text-dark mb-4">Store &amp; Service Center</h4>
                
                <div class="d-flex gap-3 mb-3">
                    <div class="service-icon-box flex-shrink-0" style="width: 44px; height: 44px; font-size: 1.2rem;">
                        <i class="bi bi-geo-alt-fill text-danger"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1 text-dark">Our Address</h6>
                        <p class="text-secondary small mb-0">
                            <strong><?= SITE_NAME ?></strong><br>
                            <?= BUSINESS_ADDRESS ?>
                        </p>
                    </div>
                </div>
                
                <div class="d-flex gap-3 mb-3">
                    <div class="service-icon-box flex-shrink-0" style="width: 44px; height: 44px; font-size: 1.2rem;">
                        <i class="bi bi-telephone-fill text-success"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1 text-dark">Phone / WhatsApp</h6>
                        <p class="text-secondary small mb-0">
                            <a href="tel:<?= BUSINESS_PHONE ?>" class="text-dark fw-bold text-decoration-none"><?= BUSINESS_PHONE_DISPLAY ?></a><br>
                            <a href="<?= whatsapp_url('Hello Zenith Systems, inquiring for details.') ?>" target="_blank" class="text-success text-decoration-none">
                                <i class="bi bi-whatsapp me-1"></i> Direct WhatsApp Chat
                            </a>
                        </p>
                    </div>
                </div>
                
                <div class="d-flex gap-3 mb-3">
                    <div class="service-icon-box flex-shrink-0" style="width: 44px; height: 44px; font-size: 1.2rem;">
                        <i class="bi bi-envelope-fill text-info"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1 text-dark">Email Address</h6>
                        <p class="text-secondary small mb-0">
                            <a href="mailto:<?= BUSINESS_EMAIL ?>" class="text-dark text-decoration-none"><?= BUSINESS_EMAIL ?></a>
                        </p>
                    </div>
                </div>
                
                <div class="d-flex gap-3">
                    <div class="service-icon-box flex-shrink-0" style="width: 44px; height: 44px; font-size: 1.2rem;">
                        <i class="bi bi-clock-fill text-warning"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1 text-dark">Working Hours</h6>
                        <p class="text-secondary small mb-0">
                            Monday - Saturday: <strong>9:30 AM - 8:30 PM</strong><br>
                            Sunday: <strong>10:00 AM - 2:00 PM</strong>
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Quick Directions Link -->
            <a href="<?= BUSINESS_MAP_LINK ?>" target="_blank" class="btn btn-outline-primary w-100 py-2.5">
                <i class="bi bi-geo-alt me-1"></i> Open Location in Google Maps
            </a>
        </div>
        
        <!-- Enquiry Form -->
        <div class="col-lg-7">
            <div class="bg-white p-4 p-md-5 rounded-4 border border-light-subtle shadow-sm">
                <h4 class="fw-bold text-dark mb-2">Send Us a Direct Message</h4>
                <p class="text-muted small mb-4">Fill out the form below and our team will get back to you with quotes and advice.</p>
                
                <?php if (!empty($error_msg)): ?>
                    <div class="alert alert-danger d-flex align-items-center mb-4">
                        <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                        <div><?= e($error_msg) ?></div>
                    </div>
                <?php endif; ?>

                <form action="<?= BASE_URL ?>/contact.php" method="POST" class="needs-validation">
                    <?= csrf_field() ?>
                    
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Your Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. Saravanan" value="<?= e($_POST['name'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Mobile Number <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">+91</span>
                                <input type="tel" name="phone" class="form-control phone-input" placeholder="10-digit mobile" maxlength="10" value="<?= e($_POST['phone'] ?? '') ?>" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Email Address <span class="text-muted small">(Optional)</span></label>
                            <input type="email" name="email" class="form-control" placeholder="e.g. saravanan@gmail.com" value="<?= e($_POST['email'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Subject / Requirement</label>
                            <input type="text" name="subject" class="form-control" placeholder="e.g. Bulk PC Quote, Screen Replacement" value="<?= e($_POST['subject'] ?? '') ?>">
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label">Your Message / Problem Details <span class="text-danger">*</span></label>
                        <textarea name="message" rows="5" class="form-control" placeholder="Please describe how we can help you..." required><?= e($_POST['message'] ?? '') ?></textarea>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg fw-bold py-3 shadow-sm">
                            <i class="bi bi-send-fill me-2"></i> Send Enquiry
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Map Embed Preview Box -->
    <div class="mt-5 bg-white p-3 rounded-4 border border-light-subtle shadow-sm overflow-hidden">
        <div class="d-flex justify-content-between align-items-center p-2 mb-2">
            <div>
                <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-map-fill text-primary me-2"></i> Location Map: Sulur, Coimbatore - 641402</h6>
                <span class="text-muted small">C5/3 Amarjothi Jai Nagar, Marutham Street, 5th Cross, Kannampalayam Road</span>
            </div>
            <a href="<?= BUSINESS_MAP_LINK ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                Get Directions <i class="bi bi-box-arrow-up-right ms-1"></i>
            </a>
        </div>
        <div class="ratio ratio-21x9 rounded-3 overflow-hidden" style="max-height: 360px;">
            <iframe src="https://maps.google.com/maps?q=Sulur,+Coimbatore+641402&t=&z=14&ie=UTF8&iwloc=&output=embed" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

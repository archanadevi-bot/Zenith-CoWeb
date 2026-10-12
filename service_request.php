<?php
/**
 * Zenith Systems and Services - Book a Repair / Service Request
 */
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';

$pdo = getDB();
$booking_success = false;
$generated_request_no = '';
$error_msg = '';

$preselected_service = isset($_GET['service']) ? trim($_GET['service']) : '';

// Process POST submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validate_csrf()) {
        $error_msg = "Security session expired. Please refresh the page and try again.";
    } else {
        $customer_name = trim($_POST['customer_name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $service_type = trim($_POST['service_type'] ?? '');
        $device_brand = trim($_POST['device_brand'] ?? '');
        $device_model = trim($_POST['device_model'] ?? '');
        $problem_description = trim($_POST['problem_description'] ?? '');
        $pickup_preference = trim($_POST['pickup_preference'] ?? 'Drop at Sulur Lab');

        // Validation
        if (empty($customer_name) || empty($phone) || empty($service_type) || empty($problem_description)) {
            $error_msg = "Please fill in all mandatory fields (Name, Phone, Service Type, and Problem Description).";
        } elseif (strlen(preg_replace('/[^0-9]/', '', $phone)) < 10) {
            $error_msg = "Please enter a valid 10-digit mobile number.";
        } else {
            try {
                // Combine brand + model + pickup preference into device details
                $device_details = trim("{$device_brand} {$device_model} [{$pickup_preference}]");
                
                // Generate Unique Request Number
                $generated_request_no = generate_request_no($pdo);

                $stmt = $pdo->prepare("INSERT INTO service_requests (request_no, customer_name, phone, email, service_type, device_details, problem_description, status, created_at) 
                                       VALUES (?, ?, ?, ?, ?, ?, ?, 'Pending', NOW())");
                $stmt->execute([
                    $generated_request_no,
                    $customer_name,
                    $phone,
                    $email,
                    $service_type,
                    $device_details,
                    $problem_description
                ]);

                $booking_success = true;
            } catch (PDOException $e) {
                error_log("Service request insert error: " . $e->getMessage());
                $error_msg = "Unable to process your service request right now. Please call us directly at " . BUSINESS_PHONE_DISPLAY;
            }
        }
    }
}

$page_title = "Book Computer & Laptop Repair | Zenith Systems Sulur";
$meta_desc = "Book professional laptop repair, SSD upgrade, or desktop service online with Zenith Systems in Sulur, Coimbatore. Receive an instant online tracking number.";

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<!-- Banner -->
<div class="page-hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <span class="hero-badge mb-2">
                    <i class="bi bi-clock-history me-1"></i> Rapid Online Service Desk
                </span>
                <h1 class="page-hero-title">Book a Repair or Upgrade</h1>
                <p class="page-hero-desc">
                    Submit your device repair request to receive an instant Zenith Tracking ID (e.g. <code>ZSS-2026-00001</code>) and prioritized diagnostic intake.
                </p>
            </div>
            <div class="col-lg-4 mt-3 mt-lg-0 text-lg-end">
                <a href="<?= BASE_URL ?>/track_service.php" class="btn btn-outline-secondary">
                    <i class="bi bi-search me-1"></i> Already have a Tracking ID?
                </a>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <?php if ($booking_success): ?>
                <!-- Confirmation Box -->
                <div class="card border-0 rounded-4 shadow-lg overflow-hidden text-center p-4 p-md-5 bg-white border border-light-subtle">
                    <div class="mb-3">
                        <div class="rounded-circle bg-success text-white d-inline-flex align-items-center justify-content-center shadow-md" style="width: 76px; height: 76px; font-size: 2.25rem;">
                            <i class="bi bi-check-lg"></i>
                        </div>
                    </div>
                    
                    <h2 class="fw-bold text-dark mb-2">Service Request Registered!</h2>
                    <p class="text-muted mb-4">
                        Thank you, <strong><?= e($customer_name) ?></strong>. Your request has been assigned a unique Zenith Tracking Code.
                    </p>
                    
                    <div class="p-4 bg-light rounded-4 border max-w-500 mx-auto mb-4">
                        <span class="text-muted small text-uppercase fw-bold letter-spacing-1 d-block mb-1">Your Tracking Request Code</span>
                        <div class="display-6 fw-bold text-primary font-monospace" id="newTrackingCode">
                            <?= e($generated_request_no) ?>
                        </div>
                        <div class="mt-2">
                            <button class="btn btn-sm btn-outline-primary btn-copy-code" data-copy-target="<?= e($generated_request_no) ?>">
                                <i class="bi bi-clipboard me-1"></i> Copy Code
                            </button>
                        </div>
                    </div>
                    
                    <div class="alert alert-info d-flex align-items-center justify-content-center gap-2 mb-4 text-start small">
                        <i class="bi bi-info-circle-fill fs-5"></i>
                        <div>
                            Please bring this tracking number with your device to our service center on <strong>Kannampalayam Road, Sulur</strong>, or show it to our technician upon pickup.
                        </div>
                    </div>
                    
                    <div class="d-flex flex-wrap justify-content-center gap-3">
                        <a href="<?= BASE_URL ?>/track_service.php?query=<?= urlencode($generated_request_no) ?>" class="btn btn-primary px-4 py-2.5">
                            <i class="bi bi-search me-1.5"></i> Track Status Live
                        </a>
                        <a href="<?= whatsapp_url("Hello Zenith Systems, I have booked a service request with ID: {$generated_request_no}. Please confirm reception.") ?>" target="_blank" class="btn btn-whatsapp px-4 py-2.5">
                            <i class="bi bi-whatsapp me-1.5"></i> WhatsApp Zenith Lab
                        </a>
                        <a href="<?= BASE_URL ?>/index.php" class="btn btn-outline-secondary px-4 py-2.5">
                            <i class="bi bi-house me-1.5"></i> Return Home
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <!-- Service Booking Form Card -->
                <div class="card border-0 rounded-4 shadow-sm bg-white p-4 p-md-5 border border-light-subtle">
                    <?php if (!empty($error_msg)): ?>
                        <div class="alert alert-danger d-flex align-items-center mb-4">
                            <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                            <div><?= e($error_msg) ?></div>
                        </div>
                    <?php endif; ?>

                    <form action="<?= BASE_URL ?>/service_request.php" method="POST" class="needs-validation" novalidate>
                        <?= csrf_field() ?>
                        
                        <h4 class="fw-bold text-dark mb-4 pb-2 border-bottom">
                            <i class="bi bi-person-lines-fill text-primary me-2"></i> 1. Customer Details
                        </h4>
                        
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="customer_name" class="form-control" placeholder="e.g. Karthik Raja" value="<?= e($_POST['customer_name'] ?? '') ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Mobile Number <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">+91</span>
                                    <input type="tel" name="phone" class="form-control phone-input" placeholder="10-digit mobile" maxlength="10" value="<?= e($_POST['phone'] ?? '') ?>" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Email Address <span class="text-muted small">(Optional for invoice &amp; reports)</span></label>
                                <input type="email" name="email" class="form-control" placeholder="e.g. karthik@example.com" value="<?= e($_POST['email'] ?? '') ?>">
                            </div>
                        </div>
                        
                        <h4 class="fw-bold text-dark mb-4 pb-2 border-bottom">
                            <i class="bi bi-laptop text-primary me-2"></i> 2. Device &amp; Service Required
                        </h4>
                        
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Service Type <span class="text-danger">*</span></label>
                                <select name="service_type" class="form-select" required>
                                    <option value="">-- Select Required Service --</option>
                                    <?php
                                    $service_options = [
                                        'Laptop Repair',
                                        'Desktop Repair',
                                        'SSD & RAM Upgrade',
                                        'Custom PC Assembly',
                                        'Windows Installation',
                                        'Virus Removal',
                                        'Printer Service',
                                        'Data Backup',
                                        'General Maintenance / Cleaning',
                                        'Other Hardware Issue'
                                    ];
                                    foreach ($service_options as $opt) {
                                        $selected = ($preselected_service === $opt || ($_POST['service_type'] ?? '') === $opt) ? 'selected' : '';
                                        echo "<option value='" . e($opt) . "' {$selected}>" . e($opt) . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Handover / Pickup Preference</label>
                                <select name="pickup_preference" class="form-select">
                                    <option value="Drop at Sulur Lab" <?= ($_POST['pickup_preference'] ?? '') === 'Drop at Sulur Lab' ? 'selected' : '' ?>>I will drop at Sulur Service Center (Recommended)</option>
                                    <option value="Request Doorstep Pickup" <?= ($_POST['pickup_preference'] ?? '') === 'Request Doorstep Pickup' ? 'selected' : '' ?>>Request Doorstep Pickup (Sulur / Kannampalayam area)</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Device Brand</label>
                                <input type="text" name="device_brand" class="form-control" placeholder="e.g. Dell, Lenovo, HP, Asus, Custom" value="<?= e($_POST['device_brand'] ?? '') ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Model / Serial No <span class="text-muted small">(If known)</span></label>
                                <input type="text" name="device_model" class="form-control" placeholder="e.g. Inspiron 15 3511, Pavilion x360" value="<?= e($_POST['device_model'] ?? '') ?>">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Detailed Problem Description <span class="text-danger">*</span></label>
                                <textarea name="problem_description" rows="4" class="form-control" placeholder="Describe symptoms (e.g. No display, slow boot, water spill, broken hinge, blue screen error, paper jam)..." required><?= e($_POST['problem_description'] ?? '') ?></textarea>
                            </div>
                        </div>
                        
                        <div class="p-3 bg-light rounded-3 mb-4 small text-secondary border">
                            <i class="bi bi-shield-check text-success me-1"></i>
                            <strong>Data Privacy &amp; Security:</strong> Zenith Systems adheres to strict data privacy protocols. Your files and personal storage remain strictly confidential during all hardware repairs.
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg fw-bold py-3 shadow-sm">
                                <i class="bi bi-send-check-fill me-2"></i> Submit Service Request &amp; Get Tracking Code
                            </button>
                        </div>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

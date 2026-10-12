<?php
/**
 * Zenith Systems and Services - Real-Time Service Tracker
 */
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';

$pdo = getDB();
$query = isset($_GET['query']) ? trim($_GET['query']) : '';
$requests = [];
$searched = false;

if (!empty($query)) {
    $searched = true;
    // Search by exact request_no or phone number
    $stmt = $pdo->prepare("SELECT * FROM service_requests WHERE request_no = ? OR phone = ? OR phone LIKE ? ORDER BY id DESC LIMIT 5");
    $clean_phone = preg_replace('/[^0-9]/', '', $query);
    $stmt->execute([$query, $clean_phone, "%{$clean_phone}%"]);
    $requests = $stmt->fetchAll();
}

$page_title = "Track Service Request Live | Zenith Systems Sulur";
$meta_desc = "Track the real-time status of your laptop repair or computer service with Zenith Systems using your unique tracking number or registered phone number.";

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<!-- Banner -->
<div class="page-hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <span class="hero-badge mb-2">
                    <i class="bi bi-radar me-1"></i> Live Status Portal
                </span>
                <h1 class="page-hero-title">Service Request Tracker</h1>
                <p class="page-hero-desc">
                    Enter your unique Request Code (e.g. <code>ZSS-2026-00001</code>) or your 10-digit registered mobile number.
                </p>
            </div>
            <div class="col-lg-4 mt-3 mt-lg-0 text-lg-end">
                <a href="<?= BASE_URL ?>/service_request.php" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i> New Service Request
                </a>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <!-- Search Bar Card -->
    <div class="row justify-content-center mb-5">
        <div class="col-lg-8">
            <div class="bg-white p-4 rounded-4 border border-light-subtle shadow-sm">
                <form action="<?= BASE_URL ?>/track_service.php" method="GET" class="row g-2">
                    <div class="col-sm-9">
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-light text-muted border-end-0">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" name="query" value="<?= e($query) ?>" class="form-control bg-light border-start-0 font-monospace" placeholder="Enter ZSS-2026-XXXXX or mobile" required autofocus>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold">
                            <i class="bi bi-arrow-right-circle me-1"></i> Track
                        </button>
                    </div>
                </form>
                
                <div class="d-flex flex-wrap gap-2 align-items-center justify-content-center mt-3 small text-muted">
                    <span>Quick test sample codes:</span>
                    <a href="<?= BASE_URL ?>/track_service.php?query=ZSS-2026-00001" class="badge bg-light text-primary border text-decoration-none font-monospace">ZSS-2026-00001</a>
                    <a href="<?= BASE_URL ?>/track_service.php?query=ZSS-2026-00002" class="badge bg-light text-primary border text-decoration-none font-monospace">ZSS-2026-00002</a>
                    <a href="<?= BASE_URL ?>/track_service.php?query=ZSS-2026-00003" class="badge bg-light text-primary border text-decoration-none font-monospace">ZSS-2026-00003</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Search Results Display -->
    <?php if ($searched): ?>
        <?php if (!empty($requests)): ?>
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <h4 class="fw-bold mb-4 text-dark">
                        <i class="bi bi-check2-circle text-success me-2"></i> Found <?= count($requests) ?> Service Record(s) for "<?= e($query) ?>"
                    </h4>

                    <?php foreach ($requests as $req): ?>
                        <?php
                        $status = $req['status'];
                        // Stage index (1: Pending, 2: Received, 3: In Progress, 4: Completed)
                        $stage = match($status) {
                            'Pending' => 1,
                            'Received' => 2,
                            'In Progress' => 3,
                            'Completed' => 4,
                            default => 0
                        };
                        ?>
                        <div class="card border-0 rounded-4 shadow-sm bg-white overflow-hidden mb-4 border border-light-subtle">
                            <!-- Card Header with Request No & Status -->
                            <div class="card-header bg-light py-3 px-4 d-flex flex-wrap justify-content-between align-items-center gap-2 border-bottom">
                                <div>
                                    <span class="text-muted small text-uppercase d-block">Request Tracking ID</span>
                                    <span class="fs-5 fw-bold font-monospace text-primary"><?= e($req['request_no']) ?></span>
                                </div>
                                <div>
                                    <?= get_service_status_badge($status) ?>
                                </div>
                            </div>
                            
                            <div class="card-body p-4 p-md-4">
                                <!-- Multi-Stage Progress Timeline (If not Cancelled) -->
                                <?php if ($status !== 'Cancelled'): ?>
                                    <div class="tracking-timeline mb-4">
                                        <div class="timeline-track">
                                            <div class="timeline-track-progress" style="width: <?= (($stage - 1) / 3) * 100 ?>%;"></div>
                                        </div>
                                        <div class="timeline-steps">
                                            <!-- Step 1 -->
                                            <div class="timeline-step <?= $stage >= 1 ? ($stage == 1 ? 'active' : 'completed') : '' ?>">
                                                <div class="step-circle">
                                                    <i class="bi <?= $stage > 1 ? 'bi-check-lg' : 'bi-hourglass-split' ?>"></i>
                                                </div>
                                                <div class="step-label">1. Booked</div>
                                                <div class="step-desc">Online entry recorded</div>
                                            </div>
                                            <!-- Step 2 -->
                                            <div class="timeline-step <?= $stage >= 2 ? ($stage == 2 ? 'active' : 'completed') : '' ?>">
                                                <div class="step-circle">
                                                    <i class="bi <?= $stage > 2 ? 'bi-check-lg' : 'bi-box-arrow-in-down' ?>"></i>
                                                </div>
                                                <div class="step-label">2. Received</div>
                                                <div class="step-desc">Intake at Sulur Lab</div>
                                            </div>
                                            <!-- Step 3 -->
                                            <div class="timeline-step <?= $stage >= 3 ? ($stage == 3 ? 'active' : 'completed') : '' ?>">
                                                <div class="step-circle">
                                                    <i class="bi <?= $stage > 3 ? 'bi-check-lg' : 'bi-gear-wide-connected' ?>"></i>
                                                </div>
                                                <div class="step-label">3. In Progress</div>
                                                <div class="step-desc">Diagnostics &amp; Repair</div>
                                            </div>
                                            <!-- Step 4 -->
                                            <div class="timeline-step <?= $stage >= 4 ? 'completed' : '' ?>">
                                                <div class="step-circle">
                                                    <i class="bi bi-patch-check-fill"></i>
                                                </div>
                                                <div class="step-label">4. Ready</div>
                                                <div class="step-desc">Tested &amp; Ready for pickup</div>
                                            </div>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="alert alert-danger mb-4">
                                        <i class="bi bi-x-circle-fill me-2"></i> This service request has been cancelled. Please contact our support desk for details.
                                    </div>
                                <?php endif; ?>

                                <!-- Device & Issue Details Table -->
                                <div class="row g-3 p-3 bg-light rounded-3 border mb-4">
                                    <div class="col-md-4">
                                        <span class="text-muted small d-block">Customer Name</span>
                                        <strong><?= e($req['customer_name']) ?></strong>
                                    </div>
                                    <div class="col-md-4">
                                        <span class="text-muted small d-block">Service Category</span>
                                        <strong class="text-primary"><?= e($req['service_type']) ?></strong>
                                    </div>
                                    <div class="col-md-4">
                                        <span class="text-muted small d-block">Registered Date</span>
                                        <span><?= format_date($req['created_at'], 'd M Y, h:i A') ?></span>
                                    </div>
                                    <div class="col-12 border-top pt-2 mt-2">
                                        <span class="text-muted small d-block">Device Model / Handover Preference</span>
                                        <strong><?= e($req['device_details'] ?: 'Not Specified') ?></strong>
                                    </div>
                                    <div class="col-12 border-top pt-2 mt-2">
                                        <span class="text-muted small d-block">Reported Problem Description</span>
                                        <p class="mb-0 text-secondary"><?= e($req['problem_description']) ?></p>
                                    </div>
                                </div>

                                <!-- Technician Inspection Notes (If added by Admin) -->
                                <?php if (!empty($req['admin_notes'])): ?>
                                    <div class="p-3 bg-info bg-opacity-10 border border-info border-opacity-25 rounded-3 mb-4">
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <i class="bi bi-clipboard2-pulse-fill text-info fs-5"></i>
                                            <h6 class="fw-bold text-dark mb-0">Technician Lab Notes &amp; Status Update:</h6>
                                        </div>
                                        <p class="text-secondary small mb-0 ms-4" style="white-space: pre-line;">
                                            <?= e($req['admin_notes']) ?>
                                        </p>
                                    </div>
                                <?php endif; ?>

                                <!-- Action Buttons -->
                                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 pt-2 border-top">
                                    <span class="small text-muted">
                                        <i class="bi bi-clock-history me-1"></i> Last updated: <?= format_date($req['updated_at'] ?? $req['created_at']) ?>
                                    </span>
                                    <div class="d-flex gap-2">
                                        <a href="<?= whatsapp_url("Hello Zenith Systems, inquiring on the latest status for Service ID: {$req['request_no']}") ?>" target="_blank" class="btn btn-whatsapp btn-sm">
                                            <i class="bi bi-whatsapp me-1"></i> WhatsApp Technician
                                        </a>
                                        <a href="tel:<?= BUSINESS_PHONE ?>" class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-telephone-fill me-1"></i> Call Sulur Lab
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php else: ?>
            <div class="text-center py-5 bg-white rounded-4 border border-light-subtle shadow-sm max-w-700 mx-auto">
                <i class="bi bi-search display-5 text-muted"></i>
                <h3 class="fw-bold mt-3">No Record Found</h3>
                <p class="text-muted px-4">
                    We could not find any active service requests matching <code><?= e($query) ?></code>. Please verify the request number or call our service desk.
                </p>
                <div class="d-flex justify-content-center gap-2 mt-3">
                    <a href="<?= BASE_URL ?>/track_service.php" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-clockwise me-1"></i> Try Another Search
                    </a>
                    <a href="tel:<?= BUSINESS_PHONE ?>" class="btn btn-primary">
                        <i class="bi bi-telephone-fill me-1"></i> Call <?= BUSINESS_PHONE_DISPLAY ?>
                    </a>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

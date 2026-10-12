<?php
/**
 * Zenith Systems and Services - View & Update Service Request
 */
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

$pdo = getDB();
$error_msg = '';

$request_id = isset($_GET['id']) ? intval($_GET['id']) : (isset($_POST['id']) ? intval($_POST['id']) : 0);

$stmt = $pdo->prepare("SELECT * FROM service_requests WHERE id = ?");
$stmt->execute([$request_id]);
$service_req = $stmt->fetch();

if (!$service_req) {
    set_flash('danger', 'Service request record not found.');
    header('Location: ' . BASE_URL . '/admin/service_requests.php');
    exit;
}

$statuses = ['Pending', 'Received', 'In Progress', 'Completed', 'Cancelled'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validate_csrf()) {
        $error_msg = "Security session expired. Please refresh the page and try again.";
    } else {
        $new_status = $_POST['status'] ?? $service_req['status'];
        $admin_notes = trim($_POST['admin_notes'] ?? '');

        if (!in_array($new_status, $statuses)) {
            $error_msg = "Invalid status selected.";
        } else {
            try {
                $up_stmt = $pdo->prepare("UPDATE service_requests SET status = ?, admin_notes = ?, updated_at = NOW() WHERE id = ?");
                $up_stmt->execute([$new_status, $admin_notes, $request_id]);

                set_flash('success', "Service Request {$service_req['request_no']} updated to '{$new_status}' successfully.");
                
                // Refresh local data
                $stmt->execute([$request_id]);
                $service_req = $stmt->fetch();
            } catch (PDOException $e) {
                error_log("Service request update error: " . $e->getMessage());
                $error_msg = "Database update error: " . $e->getMessage();
            }
        }
    }
}

$page_title = "Manage Request: " . e($service_req['request_no']);
require_once __DIR__ . '/includes/admin_header.php';

// Prepare WhatsApp alert text
$wa_msg = "Hello {$service_req['customer_name']}, this is Zenith Systems and Services regarding your service request ({$service_req['request_no']}). Current status: {$service_req['status']}.";
if (!empty($service_req['admin_notes'])) {
    $wa_msg .= " Notes: " . $service_req['admin_notes'];
}
$customer_wa_url = "https://wa.me/91" . preg_replace('/[^0-9]/', '', $service_req['phone']) . "?text=" . urlencode($wa_msg);
?>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <!-- Back Navigation & Status Title -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 pb-2 border-bottom">
            <div>
                <a href="<?= BASE_URL ?>/admin/service_requests.php" class="btn btn-outline-secondary btn-sm mb-2">
                    <i class="bi bi-arrow-left me-1"></i> Back to Service Desk
                </a>
                <h4 class="fw-bold text-dark mb-0">
                    Service Record: <span class="text-primary font-monospace"><?= e($service_req['request_no']) ?></span>
                </h4>
            </div>
            <div class="mt-2 mt-sm-0">
                <?= get_service_status_badge($service_req['status']) ?>
            </div>
        </div>

        <?php if (!empty($error_msg)): ?>
            <div class="alert alert-danger d-flex align-items-center mb-4">
                <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                <div><?= e($error_msg) ?></div>
            </div>
        <?php endif; ?>

        <div class="row g-4">
            <!-- Left Column: Customer & Device Details -->
            <div class="col-lg-6">
                <div class="card border-0 rounded-4 shadow-sm bg-white p-4 h-100 border border-light-subtle">
                    <h5 class="fw-bold text-dark mb-3 pb-2 border-bottom">
                        <i class="bi bi-person-badge text-primary me-2"></i> Customer &amp; Device Information
                    </h5>

                    <table class="table table-sm table-borderless mb-4">
                        <tr>
                            <td class="text-muted" style="width: 140px;">Customer Name:</td>
                            <td><strong class="text-dark"><?= e($service_req['customer_name']) ?></strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Mobile Number:</td>
                            <td>
                                <a href="tel:<?= e($service_req['phone']) ?>" class="fw-bold text-primary font-monospace">
                                    <i class="bi bi-telephone me-1"></i> <?= e($service_req['phone']) ?>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Email:</td>
                            <td><?= e($service_req['email'] ?: 'Not Provided') ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Service Category:</td>
                            <td><span class="badge bg-light text-dark border"><?= e($service_req['service_type']) ?></span></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Device / Handover:</td>
                            <td><strong><?= e($service_req['device_details'] ?: 'Not Specified') ?></strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Date Submitted:</td>
                            <td><?= format_date($service_req['created_at']) ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Last Updated:</td>
                            <td><?= format_date($service_req['updated_at'] ?? $service_req['created_at']) ?></td>
                        </tr>
                    </table>

                    <h6 class="fw-bold text-dark mb-2">Customer Reported Issue:</h6>
                    <div class="p-3 bg-light rounded-3 text-secondary small mb-4" style="white-space: pre-line; min-height: 80px;">
                        <?= e($service_req['problem_description']) ?>
                    </div>

                    <!-- Customer Quick Actions -->
                    <div class="d-flex flex-wrap gap-2 pt-3 border-top mt-auto">
                        <a href="<?= $customer_wa_url ?>" target="_blank" class="btn btn-whatsapp btn-sm flex-grow-1">
                            <i class="bi bi-whatsapp me-1"></i> Send WhatsApp Update
                        </a>
                        <a href="tel:<?= e($service_req['phone']) ?>" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-telephone-fill me-1"></i> Call Customer
                        </a>
                        <a href="<?= BASE_URL ?>/track_service.php?query=<?= urlencode($service_req['request_no']) ?>" target="_blank" class="btn btn-outline-secondary btn-sm" title="View Customer Tracking Page">
                            <i class="bi bi-box-arrow-up-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Right Column: Status Update & Technician Notes -->
            <div class="col-lg-6">
                <div class="card border-0 rounded-4 shadow-sm bg-white p-4 h-100 border border-light-subtle">
                    <h5 class="fw-bold text-dark mb-3 pb-2 border-bottom">
                        <i class="bi bi-sliders text-primary me-2"></i> Update Service Status &amp; Notes
                    </h5>

                    <form action="<?= BASE_URL ?>/admin/service_request_view.php?id=<?= $request_id ?>" method="POST">
                        <?= csrf_field() ?>
                        <input type="hidden" name="id" value="<?= $request_id ?>">

                        <div class="mb-4">
                            <label class="form-label">Service Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select form-select-lg" required>
                                <?php foreach ($statuses as $st): ?>
                                    <option value="<?= e($st) ?>" <?= $service_req['status'] === $st ? 'selected' : '' ?>>
                                        <?= e($st) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <small class="text-muted mt-1 d-block">
                                Changing status will immediately update the customer's live tracking progress timeline.
                            </small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Technician Diagnosis / Inspection Notes</label>
                            <textarea name="admin_notes" rows="6" class="form-control" placeholder="Add diagnosis notes (e.g. Parts replaced, test benchmark results, quote approved by customer, ready for pickup)..."><?= e($_POST['admin_notes'] ?? $service_req['admin_notes']) ?></textarea>
                            <small class="text-muted mt-1 d-block">
                                These notes are visible to the customer on their online status tracker and sent via WhatsApp updates.
                            </small>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg fw-bold py-2.5">
                                <i class="bi bi-save me-1.5"></i> Save Status &amp; Technician Notes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>

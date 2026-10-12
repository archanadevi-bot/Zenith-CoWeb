<?php
/**
 * Zenith Systems and Services - View & Update Customer Enquiry
 */
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

$pdo = getDB();
$error_msg = '';

$enquiry_id = isset($_GET['id']) ? intval($_GET['id']) : (isset($_POST['id']) ? intval($_POST['id']) : 0);

$stmt = $pdo->prepare("SELECT * FROM enquiries WHERE id = ?");
$stmt->execute([$enquiry_id]);
$enquiry = $stmt->fetch();

if (!$enquiry) {
    set_flash('danger', 'Enquiry not found.');
    header('Location: ' . BASE_URL . '/admin/enquiries.php');
    exit;
}

// Auto-mark New enquiries as Read upon viewing if not already updated
if ($enquiry['status'] === 'New' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    $read_stmt = $pdo->prepare("UPDATE enquiries SET status = 'Read' WHERE id = ?");
    $read_stmt->execute([$enquiry_id]);
    $enquiry['status'] = 'Read';
}

$statuses = ['New', 'Read', 'Resolved'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validate_csrf()) {
        $error_msg = "Security session expired. Please refresh the page and try again.";
    } else {
        $new_status = $_POST['status'] ?? $enquiry['status'];
        if (in_array($new_status, $statuses)) {
            $up_stmt = $pdo->prepare("UPDATE enquiries SET status = ? WHERE id = ?");
            $up_stmt->execute([$new_status, $enquiry_id]);
            set_flash('success', "Enquiry status updated to '{$new_status}'.");
            
            $stmt->execute([$enquiry_id]);
            $enquiry = $stmt->fetch();
        } else {
            $error_msg = "Invalid status selected.";
        }
    }
}

$page_title = "View Customer Enquiry #" . $enquiry['id'];
require_once __DIR__ . '/includes/admin_header.php';

// Prepare WhatsApp reply link
$wa_reply_msg = "Hello {$enquiry['name']}, thank you for contacting Zenith Systems and Services regarding '{$enquiry['subject']}'. How can we assist you today?";
$wa_reply_url = "https://wa.me/91" . preg_replace('/[^0-9]/', '', $enquiry['phone']) . "?text=" . urlencode($wa_reply_msg);
?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
            <div>
                <a href="<?= BASE_URL ?>/admin/enquiries.php" class="btn btn-outline-secondary btn-sm mb-2">
                    <i class="bi bi-arrow-left me-1"></i> Back to Enquiries
                </a>
                <h4 class="fw-bold text-dark mb-0">Customer Message #<?= $enquiry['id'] ?></h4>
            </div>
            <div>
                <?= get_enquiry_status_badge($enquiry['status']) ?>
            </div>
        </div>

        <?php if (!empty($error_msg)): ?>
            <div class="alert alert-danger d-flex align-items-center mb-4">
                <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                <div><?= e($error_msg) ?></div>
            </div>
        <?php endif; ?>

        <div class="card border-0 rounded-4 shadow-sm bg-white p-4 p-md-5 mb-4 border border-light-subtle">
            <div class="row g-3 mb-4 pb-3 border-bottom">
                <div class="col-md-6">
                    <span class="text-muted small d-block">Sender Name</span>
                    <h5 class="fw-bold text-dark mb-0"><?= e($enquiry['name']) ?></h5>
                </div>
                <div class="col-md-6 text-md-end">
                    <span class="text-muted small d-block">Date Received</span>
                    <span class="text-secondary"><?= format_date($enquiry['created_at']) ?></span>
                </div>
                <div class="col-md-6">
                    <span class="text-muted small d-block">Mobile Number</span>
                    <a href="tel:<?= e($enquiry['phone']) ?>" class="fw-bold text-primary font-monospace fs-6">
                        <i class="bi bi-telephone me-1"></i> <?= e($enquiry['phone']) ?>
                    </a>
                </div>
                <div class="col-md-6 text-md-end">
                    <span class="text-muted small d-block">Email Address</span>
                    <?php if (!empty($enquiry['email'])): ?>
                        <a href="mailto:<?= e($enquiry['email']) ?>" class="text-dark">
                            <i class="bi bi-envelope me-1"></i> <?= e($enquiry['email']) ?>
                        </a>
                    <?php else: ?>
                        <span class="text-muted">Not Provided</span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="mb-4">
                <span class="text-muted small d-block mb-1">Subject / Requirement</span>
                <h6 class="fw-bold text-primary"><?= e($enquiry['subject'] ?: 'General Information') ?></h6>
            </div>

            <div class="mb-4">
                <span class="text-muted small d-block mb-2">Message Body</span>
                <div class="p-4 bg-light rounded-4 text-dark border" style="white-space: pre-line; line-height: 1.8;">
                    <?= e($enquiry['message']) ?>
                </div>
            </div>

            <!-- Response Quick Triggers -->
            <div class="d-flex flex-wrap gap-2 pt-3 border-top mb-4">
                <a href="<?= $wa_reply_url ?>" target="_blank" class="btn btn-whatsapp flex-grow-1">
                    <i class="bi bi-whatsapp me-1.5"></i> Reply via WhatsApp
                </a>
                <a href="tel:<?= e($enquiry['phone']) ?>" class="btn btn-outline-primary">
                    <i class="bi bi-telephone-fill me-1.5"></i> Call Customer
                </a>
                <?php if (!empty($enquiry['email'])): ?>
                    <a href="mailto:<?= e($enquiry['email']) ?>?subject=<?= urlencode("Re: " . ($enquiry['subject'] ?: "Your Zenith Systems Enquiry")) ?>" class="btn btn-outline-secondary">
                        <i class="bi bi-envelope-fill me-1.5"></i> Reply via Email
                    </a>
                <?php endif; ?>
            </div>

            <!-- Status Updater -->
            <div class="p-3 bg-light rounded-3 border">
                <form action="<?= BASE_URL ?>/admin/enquiry_view.php?id=<?= $enquiry_id ?>" method="POST" class="row g-2 align-items-center">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" value="<?= $enquiry_id ?>">
                    <div class="col-sm-3">
                        <label class="form-label fw-semibold small mb-0">Update Status:</label>
                    </div>
                    <div class="col-sm-5">
                        <select name="status" class="form-select form-select-sm">
                            <?php foreach ($statuses as $st): ?>
                                <option value="<?= e($st) ?>" <?= $enquiry['status'] === $st ? 'selected' : '' ?>><?= e($st) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <button type="submit" class="btn btn-primary btn-sm w-100">
                            <i class="bi bi-check2 me-1"></i> Update Status
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>

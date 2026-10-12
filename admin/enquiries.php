<?php
/**
 * Zenith Systems and Services - Admin Enquiries List
 */
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

$pdo = getDB();

$statuses = ['New', 'Read', 'Resolved'];
$selected_status = $_GET['status'] ?? '';
$search_query = trim($_GET['q'] ?? '');

$sql = "SELECT * FROM enquiries WHERE 1=1";
$params = [];

if (!empty($selected_status) && in_array($selected_status, $statuses)) {
    $sql .= " AND status = ?";
    $params[] = $selected_status;
}

if (!empty($search_query)) {
    $sql .= " AND (name LIKE ? OR phone LIKE ? OR email LIKE ? OR subject LIKE ?)";
    $term = "%{$search_query}%";
    $params[] = $term;
    $params[] = $term;
    $params[] = $term;
    $params[] = $term;
}

$sql .= " ORDER BY id DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$enquiries = $stmt->fetchAll();

// Status counts
$counts = [];
$counts['All'] = $pdo->query("SELECT COUNT(*) FROM enquiries")->fetchColumn();
foreach ($statuses as $st) {
    $c_stmt = $pdo->prepare("SELECT COUNT(*) FROM enquiries WHERE status = ?");
    $c_stmt->execute([$st]);
    $counts[$st] = $c_stmt->fetchColumn();
}

$page_title = "Customer Enquiries & Leads";
require_once __DIR__ . '/includes/admin_header.php';
?>

<!-- Status Filter Pills -->
<div class="d-flex flex-wrap gap-2 mb-4">
    <a href="<?= BASE_URL ?>/admin/enquiries.php" class="btn btn-sm <?= empty($selected_status) ? 'btn-primary' : 'btn-outline-secondary' ?> rounded-pill px-3">
        All Enquiries <span class="badge bg-dark bg-opacity-25 ms-1"><?= $counts['All'] ?></span>
    </a>
    <?php foreach ($statuses as $st): ?>
        <a href="<?= BASE_URL ?>/admin/enquiries.php?status=<?= urlencode($st) ?>" class="btn btn-sm <?= $selected_status === $st ? 'btn-primary' : 'btn-outline-secondary' ?> rounded-pill px-3">
            <?= e($st) ?> <span class="badge bg-dark bg-opacity-25 ms-1"><?= $counts[$st] ?></span>
        </a>
    <?php endforeach; ?>
</div>

<!-- Search Bar -->
<div class="card border-0 rounded-4 shadow-sm bg-white p-3 mb-4 border border-light-subtle">
    <form action="<?= BASE_URL ?>/admin/enquiries.php" method="GET" class="row g-2">
        <?php if (!empty($selected_status)): ?>
            <input type="hidden" name="status" value="<?= e($selected_status) ?>">
        <?php endif; ?>
        <div class="col-sm-10">
            <div class="input-group">
                <span class="input-group-text bg-light text-muted border-end-0">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text" name="q" value="<?= e($search_query) ?>" class="form-control bg-light border-start-0" placeholder="Search by customer name, phone, email, or subject...">
            </div>
        </div>
        <div class="col-sm-2">
            <button type="submit" class="btn btn-primary w-100">
                <i class="bi bi-filter me-1"></i> Search
            </button>
        </div>
    </form>
</div>

<!-- Enquiries Table -->
<div class="card border-0 rounded-4 shadow-sm bg-white overflow-hidden border border-light-subtle">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light small text-uppercase text-muted">
                <tr>
                    <th>Customer</th>
                    <th>Mobile</th>
                    <th>Subject</th>
                    <th>Message Excerpt</th>
                    <th>Status</th>
                    <th>Received On</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($enquiries)): ?>
                    <?php foreach ($enquiries as $enq): ?>
                        <tr class="<?= $enq['status'] === 'New' ? 'table-warning table-opacity-10' : '' ?>">
                            <td>
                                <div class="fw-bold text-dark"><?= e($enq['name']) ?></div>
                                <?php if (!empty($enq['email'])): ?>
                                    <small class="text-muted"><?= e($enq['email']) ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="tel:<?= e($enq['phone']) ?>" class="fw-semibold text-dark text-decoration-none font-monospace">
                                    <?= e($enq['phone']) ?>
                                </a>
                            </td>
                            <td>
                                <span class="fw-semibold text-dark"><?= e($enq['subject'] ?: 'General Enquiry') ?></span>
                            </td>
                            <td>
                                <div class="small text-secondary text-truncate" style="max-width: 250px;">
                                    <?= e($enq['message']) ?>
                                </div>
                            </td>
                            <td>
                                <?= get_enquiry_status_badge($enq['status']) ?>
                            </td>
                            <td>
                                <small class="text-muted"><?= format_date($enq['created_at'], 'd M, h:i A') ?></small>
                            </td>
                            <td class="text-end">
                                <a href="<?= BASE_URL ?>/admin/enquiry_view.php?id=<?= $enq['id'] ?>" class="btn btn-primary btn-sm">
                                    <i class="bi bi-eye me-1"></i> View
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-chat-left-dots display-6 d-block mb-2"></i>
                            No enquiries found matching your selection.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>

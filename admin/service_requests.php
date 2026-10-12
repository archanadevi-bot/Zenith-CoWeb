<?php
/**
 * Zenith Systems and Services - Admin Service Requests List
 */
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

$pdo = getDB();

$statuses = ['Pending', 'Received', 'In Progress', 'Completed', 'Cancelled'];

$selected_status = $_GET['status'] ?? '';
$search_query = trim($_GET['q'] ?? '');

$sql = "SELECT * FROM service_requests WHERE 1=1";
$params = [];

if (!empty($selected_status) && in_array($selected_status, $statuses)) {
    $sql .= " AND status = ?";
    $params[] = $selected_status;
}

if (!empty($search_query)) {
    $sql .= " AND (request_no LIKE ? OR customer_name LIKE ? OR phone LIKE ?)";
    $term = "%{$search_query}%";
    $params[] = $term;
    $params[] = $term;
    $params[] = $term;
}

$sql .= " ORDER BY id DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$requests = $stmt->fetchAll();

// Counts for status pills
$counts = [];
$counts['All'] = $pdo->query("SELECT COUNT(*) FROM service_requests")->fetchColumn();
foreach ($statuses as $st) {
    $c_stmt = $pdo->prepare("SELECT COUNT(*) FROM service_requests WHERE status = ?");
    $c_stmt->execute([$st]);
    $counts[$st] = $c_stmt->fetchColumn();
}

$page_title = "Service Requests Desk";
require_once __DIR__ . '/includes/admin_header.php';
?>

<!-- Status Filter Pills -->
<div class="d-flex flex-wrap gap-2 mb-4">
    <a href="<?= BASE_URL ?>/admin/service_requests.php" class="btn btn-sm <?= empty($selected_status) ? 'btn-primary' : 'btn-outline-secondary' ?> rounded-pill px-3">
        All Requests <span class="badge bg-dark bg-opacity-25 ms-1"><?= $counts['All'] ?></span>
    </a>
    <?php foreach ($statuses as $st): ?>
        <a href="<?= BASE_URL ?>/admin/service_requests.php?status=<?= urlencode($st) ?>" class="btn btn-sm <?= $selected_status === $st ? 'btn-primary' : 'btn-outline-secondary' ?> rounded-pill px-3">
            <?= e($st) ?> <span class="badge bg-dark bg-opacity-25 ms-1"><?= $counts[$st] ?></span>
        </a>
    <?php endforeach; ?>
</div>

<!-- Search & Filter Controls -->
<div class="card border-0 rounded-4 shadow-sm bg-white p-3 mb-4 border border-light-subtle">
    <form action="<?= BASE_URL ?>/admin/service_requests.php" method="GET" class="row g-2">
        <?php if (!empty($selected_status)): ?>
            <input type="hidden" name="status" value="<?= e($selected_status) ?>">
        <?php endif; ?>
        <div class="col-sm-10">
            <div class="input-group">
                <span class="input-group-text bg-light text-muted border-end-0">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text" name="q" value="<?= e($search_query) ?>" class="form-control bg-light border-start-0 font-monospace" placeholder="Search by Tracking Code (ZSS-...), Customer Name, or Mobile...">
            </div>
        </div>
        <div class="col-sm-2">
            <button type="submit" class="btn btn-primary w-100">
                <i class="bi bi-filter me-1"></i> Filter
            </button>
        </div>
    </form>
</div>

<!-- Service Requests Table -->
<div class="card border-0 rounded-4 shadow-sm bg-white overflow-hidden border border-light-subtle">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light small text-uppercase text-muted">
                <tr>
                    <th>Tracking Code</th>
                    <th>Customer Name</th>
                    <th>Mobile</th>
                    <th>Service Category</th>
                    <th>Device Details</th>
                    <th>Status</th>
                    <th>Registered</th>
                    <th class="text-end">Manage</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($requests)): ?>
                    <?php foreach ($requests as $req): ?>
                        <tr>
                            <td>
                                <a href="<?= BASE_URL ?>/admin/service_request_view.php?id=<?= $req['id'] ?>" class="fw-bold font-monospace text-primary">
                                    <?= e($req['request_no']) ?>
                                </a>
                            </td>
                            <td>
                                <div class="fw-bold text-dark"><?= e($req['customer_name']) ?></div>
                                <?php if (!empty($req['email'])): ?>
                                    <small class="text-muted"><?= e($req['email']) ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="tel:<?= e($req['phone']) ?>" class="text-dark fw-semibold text-decoration-none font-monospace">
                                    <?= e($req['phone']) ?>
                                </a>
                            </td>
                            <td>
                                <span class="badge bg-light text-primary border"><?= e($req['service_type']) ?></span>
                            </td>
                            <td>
                                <div class="small text-secondary text-truncate" style="max-width: 180px;">
                                    <?= e($req['device_details'] ?: 'N/A') ?>
                                </div>
                            </td>
                            <td>
                                <?= get_service_status_badge($req['status']) ?>
                            </td>
                            <td>
                                <small class="text-muted"><?= format_date($req['created_at'], 'd M Y') ?></small>
                            </td>
                            <td class="text-end">
                                <a href="<?= BASE_URL ?>/admin/service_request_view.php?id=<?= $req['id'] ?>" class="btn btn-primary btn-sm" title="View & Update Request">
                                    <i class="bi bi-pencil-square me-1"></i> Details
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-wrench display-6 d-block mb-2"></i>
                            No service requests found matching your filter.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>

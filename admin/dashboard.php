<?php
/**
 * Zenith Systems and Services - Admin Dashboard
 */
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

$pdo = getDB();

// 1. Metric Queries
$total_products = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$active_products = $pdo->query("SELECT COUNT(*) FROM products WHERE status = 'Active'")->fetchColumn();

$pending_services = $pdo->query("SELECT COUNT(*) FROM service_requests WHERE status = 'Pending'")->fetchColumn();
$in_progress_services = $pdo->query("SELECT COUNT(*) FROM service_requests WHERE status = 'In Progress'")->fetchColumn();
$completed_services = $pdo->query("SELECT COUNT(*) FROM service_requests WHERE status = 'Completed'")->fetchColumn();

$new_enquiries = $pdo->query("SELECT COUNT(*) FROM enquiries WHERE status = 'New'")->fetchColumn();

// 2. Fetch Recent 5 Service Requests
$stmt_recent_services = $pdo->query("SELECT * FROM service_requests ORDER BY id DESC LIMIT 5");
$recent_services = $stmt_recent_services->fetchAll();

// 3. Fetch Recent 5 Enquiries
$stmt_recent_enquiries = $pdo->query("SELECT * FROM enquiries ORDER BY id DESC LIMIT 5");
$recent_enquiries = $stmt_recent_enquiries->fetchAll();

$page_title = "Overview Dashboard";
require_once __DIR__ . '/includes/admin_header.php';
?>

<!-- KPI Summary Cards -->
<div class="row g-4 mb-4">
    <!-- Active Products -->
    <div class="col-sm-6 col-xl-3">
        <div class="admin-stat-card border-start border-primary border-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted small text-uppercase fw-bold">Active Products</span>
                    <h2 class="fw-bold text-dark my-1"><?= $active_products ?></h2>
                    <span class="small text-muted">of <?= $total_products ?> total catalog items</span>
                </div>
                <div class="service-icon-box bg-primary bg-opacity-10 text-primary mb-0" style="width: 48px; height: 48px; font-size: 1.35rem;">
                    <i class="bi bi-box-seam"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Service Requests -->
    <div class="col-sm-6 col-xl-3">
        <div class="admin-stat-card border-start border-warning border-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted small text-uppercase fw-bold">Pending Services</span>
                    <h2 class="fw-bold text-warning-emphasis my-1"><?= $pending_services ?></h2>
                    <span class="small text-muted">Awaiting intake / triage</span>
                </div>
                <div class="service-icon-box bg-warning bg-opacity-10 text-warning-emphasis mb-0" style="width: 48px; height: 48px; font-size: 1.35rem;">
                    <i class="bi bi-hourglass-split"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- In Progress Services -->
    <div class="col-sm-6 col-xl-3">
        <div class="admin-stat-card border-start border-info border-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted small text-uppercase fw-bold">In Progress Repairs</span>
                    <h2 class="fw-bold text-info my-1"><?= $in_progress_services ?></h2>
                    <span class="small text-muted">On lab workbenches</span>
                </div>
                <div class="service-icon-box bg-info bg-opacity-10 text-info mb-0" style="width: 48px; height: 48px; font-size: 1.35rem;">
                    <i class="bi bi-gear-wide-connected"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- New Enquiries -->
    <div class="col-sm-6 col-xl-3">
        <div class="admin-stat-card border-start border-danger border-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted small text-uppercase fw-bold">New Enquiries</span>
                    <h2 class="fw-bold text-danger my-1"><?= $new_enquiries ?></h2>
                    <span class="small text-muted">Unread customer leads</span>
                </div>
                <div class="service-icon-box bg-danger bg-opacity-10 text-danger mb-0" style="width: 48px; height: 48px; font-size: 1.35rem;">
                    <i class="bi bi-envelope-exclamation"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Recent Service Requests Table -->
    <div class="col-lg-8">
        <div class="card border-0 rounded-4 shadow-sm bg-white overflow-hidden border border-light-subtle">
            <div class="card-header bg-white py-3 px-4 d-flex justify-content-between align-items-center border-bottom">
                <h5 class="fw-bold mb-0 text-dark">
                    <i class="bi bi-wrench-adjustable text-primary me-1"></i> Recent Service Requests
                </h5>
                <a href="<?= BASE_URL ?>/admin/service_requests.php" class="btn btn-outline-primary btn-sm">
                    View All (<?= $pending_services + $in_progress_services + $completed_services ?>)
                </a>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light small text-uppercase text-muted">
                        <tr>
                            <th>Code</th>
                            <th>Customer</th>
                            <th>Service</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($recent_services)): ?>
                            <?php foreach ($recent_services as $srv): ?>
                                <tr>
                                    <td>
                                        <a href="<?= BASE_URL ?>/admin/service_request_view.php?id=<?= $srv['id'] ?>" class="fw-bold font-monospace text-primary">
                                            <?= e($srv['request_no']) ?>
                                        </a>
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-dark"><?= e($srv['customer_name']) ?></div>
                                        <small class="text-muted"><?= e($srv['phone']) ?></small>
                                    </td>
                                    <td>
                                        <span class="small"><?= e($srv['service_type']) ?></span>
                                    </td>
                                    <td>
                                        <?= get_service_status_badge($srv['status']) ?>
                                    </td>
                                    <td>
                                        <small class="text-muted"><?= format_date($srv['created_at'], 'd M, h:i A') ?></small>
                                    </td>
                                    <td class="text-end">
                                        <a href="<?= BASE_URL ?>/admin/service_request_view.php?id=<?= $srv['id'] ?>" class="btn btn-light btn-sm border" title="Manage Request">
                                            <i class="bi bi-pencil-square text-primary"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">No service requests found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Customer Enquiries -->
    <div class="col-lg-4">
        <div class="card border-0 rounded-4 shadow-sm bg-white overflow-hidden border border-light-subtle">
            <div class="card-header bg-white py-3 px-4 d-flex justify-content-between align-items-center border-bottom">
                <h5 class="fw-bold mb-0 text-dark">
                    <i class="bi bi-chat-left-text text-danger me-1"></i> Customer Enquiries
                </h5>
                <a href="<?= BASE_URL ?>/admin/enquiries.php" class="btn btn-outline-secondary btn-sm">
                    View All
                </a>
            </div>

            <div class="list-group list-group-flush">
                <?php if (!empty($recent_enquiries)): ?>
                    <?php foreach ($recent_enquiries as $enq): ?>
                        <a href="<?= BASE_URL ?>/admin/enquiry_view.php?id=<?= $enq['id'] ?>" class="list-group-item list-group-item-action p-3 <?= $enq['status'] === 'New' ? 'bg-light bg-opacity-50' : '' ?>">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <h6 class="fw-bold text-dark mb-0"><?= e($enq['name']) ?></h6>
                                <?= get_enquiry_status_badge($enq['status']) ?>
                            </div>
                            <div class="small text-muted mb-1 text-truncate">
                                <strong><?= e($enq['subject'] ?: 'Website Enquiry') ?></strong>
                            </div>
                            <p class="text-secondary small mb-1 text-truncate">
                                <?= e($enq['message']) ?>
                            </p>
                            <small class="text-muted d-block" style="font-size: 0.75rem;">
                                <i class="bi bi-clock me-1"></i> <?= format_date($enq['created_at']) ?>
                            </small>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="p-4 text-center text-muted">No customer enquiries received yet.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>

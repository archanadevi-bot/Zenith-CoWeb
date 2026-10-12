<?php
/**
 * Zenith Systems and Services - Global Helper & Security Functions
 */

require_once __DIR__ . '/../config/db.php';

/**
 * Sanitize text input for safe processing and display
 * @param mixed $data
 * @return string
 */
function sanitize($data) {
    if (is_array($data)) {
        return array_map('sanitize', $data);
    }
    $data = trim((string)$data);
    $data = stripslashes($data);
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

/**
 * Shortcut for escaping HTML output
 * @param string|null $string
 * @return string
 */
function e($string) {
    return htmlspecialchars((string)$string, ENT_QUOTES, 'UTF-8');
}

/**
 * Generate CSRF Token and store in session
 * @return string
 */
function get_csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Output CSRF Token hidden form field
 * @return string
 */
function csrf_field() {
    $token = get_csrf_token();
    return '<input type="hidden" name="csrf_token" value="' . e($token) . '">';
}

/**
 * Validate submitted CSRF Token
 * @param string|null $token
 * @return bool
 */
function validate_csrf($token = null) {
    if ($token === null) {
        $token = $_POST['csrf_token'] ?? $_GET['csrf_token'] ?? '';
    }
    if (empty($_SESSION['csrf_token']) || empty($token)) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Generate unique Service Request Number (e.g., ZSS-2026-00006)
 * @param PDO $pdo
 * @return string
 */
function generate_request_no($pdo) {
    $year = date('Y');
    $prefix = "ZSS-{$year}-";

    // Query highest request number for current year
    $stmt = $pdo->prepare("SELECT request_no FROM service_requests WHERE request_no LIKE ? ORDER BY id DESC LIMIT 1");
    $stmt->execute([$prefix . '%']);
    $last = $stmt->fetchColumn();

    if ($last) {
        $parts = explode('-', $last);
        $num = intval(end($parts)) + 1;
    } else {
        $num = 1;
    }

    return sprintf("ZSS-%s-%05d", $year, $num);
}

/**
 * Format currency amount into Indian Rupee format (e.g. ₹ 42,999.00)
 * @param float|int|string $amount
 * @param bool $showSymbol
 * @return string
 */
function format_inr($amount, $showSymbol = true) {
    $amount = floatval($amount);
    $formatted = number_format($amount, 2, '.', ',');
    return $showSymbol ? '₹ ' . $formatted : $formatted;
}

/**
 * Format datetime for human-friendly display
 * @param string|null $datetime
 * @param string $format
 * @return string
 */
function format_date($datetime, $format = 'd M Y, h:i A') {
    if (empty($datetime)) return 'N/A';
    try {
        $dt = new DateTime($datetime);
        return $dt->format($format);
    } catch (Exception $e) {
        return (string)$datetime;
    }
}

/**
 * Generate WhatsApp chat link with prefilled text
 * @param string $message
 * @param string $phone
 * @return string
 */
function whatsapp_url($message = '', $phone = BUSINESS_WHATSAPP) {
    $encoded = urlencode(trim($message));
    return "https://wa.me/{$phone}?text={$encoded}";
}

/**
 * Set flash message in session
 * @param string $type success|danger|warning|info
 * @param string $message
 */
function set_flash($type, $message) {
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message
    ];
}

/**
 * Get and clear flash message
 * @return array|null
 */
function get_flash() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

/**
 * Display flash message HTML alert if available
 * @return string
 */
function display_flash() {
    $flash = get_flash();
    if ($flash) {
        $type = e($flash['type']);
        $icon = match($type) {
            'success' => 'bi-check-circle-fill',
            'danger' => 'bi-exclamation-triangle-fill',
            'warning' => 'bi-exclamation-circle-fill',
            default => 'bi-info-circle-fill'
        };
        return "<div class='alert alert-{$type} alert-dismissible fade show d-flex align-items-center mb-4 shadow-sm' role='alert'>
            <i class='bi {$icon} me-2 fs-5'></i>
            <div>" . e($flash['message']) . "</div>
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
    }
    return '';
}

/**
 * Return badge HTML for Service Request status
 * @param string $status
 * @return string
 */
function get_service_status_badge($status) {
    $badge_class = match($status) {
        'Pending' => 'bg-warning text-dark',
        'Received' => 'bg-info text-dark',
        'In Progress' => 'bg-primary text-white',
        'Completed' => 'bg-success text-white',
        'Cancelled' => 'bg-danger text-white',
        default => 'bg-secondary text-white'
    };
    $icon = match($status) {
        'Pending' => 'bi-hourglass-split',
        'Received' => 'bi-box-arrow-in-down',
        'In Progress' => 'bi-gear-wide-connected',
        'Completed' => 'bi-patch-check-fill',
        'Cancelled' => 'bi-x-circle',
        default => 'bi-circle'
    };
    return "<span class='badge {$badge_class} px-3 py-2 rounded-pill font-monospace fw-semibold'><i class='bi {$icon} me-1'></i> " . e($status) . "</span>";
}

/**
 * Return badge HTML for Enquiry status
 * @param string $status
 * @return string
 */
function get_enquiry_status_badge($status) {
    $badge_class = match($status) {
        'New' => 'bg-danger text-white',
        'Read' => 'bg-info text-dark',
        'Resolved' => 'bg-success text-white',
        default => 'bg-secondary text-white'
    };
    return "<span class='badge {$badge_class} px-2 py-1 rounded-pill'>" . e($status) . "</span>";
}

/**
 * Return badge HTML for Stock status
 * @param int $stock
 * @return string
 */
function get_stock_badge($stock) {
    $stock = intval($stock);
    if ($stock > 5) {
        return "<span class='badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 rounded-pill'><i class='bi bi-check2 me-1'></i> In Stock ({$stock})</span>";
    } elseif ($stock > 0) {
        return "<span class='badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2.5 py-1 rounded-pill'><i class='bi bi-exclamation-circle me-1'></i> Low Stock ({$stock} left)</span>";
    } else {
        return "<span class='badge bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1 rounded-pill'><i class='bi bi-x-circle me-1'></i> Out of Stock</span>";
    }
}

/**
 * Check if admin is currently authenticated
 * @return bool
 */
function is_admin_logged_in() {
    return !empty($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true && !empty($_SESSION['admin_id']);
}

/**
 * Enforce admin authentication or redirect
 */
function require_admin_auth() {
    if (!is_admin_logged_in()) {
        set_flash('warning', 'Please login to access the Zenith Systems administration panel.');
        header('Location: ' . BASE_URL . '/admin/login.php');
        exit;
    }
}

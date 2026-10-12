<?php
/**
 * Zenith Systems and Services - Admin Login
 */
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

// Redirect if already logged in
if (is_admin_logged_in()) {
    header('Location: ' . BASE_URL . '/admin/dashboard.php');
    exit;
}

$error_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Micro-delay to discourage automated brute-force attacks
    usleep(200000); // 200ms

    if (!validate_csrf()) {
        $error_msg = "Invalid or expired session token. Please try again.";
    } else {
        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (empty($username) || empty($password)) {
            $error_msg = "Please enter both username and password.";
        } else {
            $pdo = getDB();
            $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ? LIMIT 1");
            $stmt->execute([$username]);
            $admin = $stmt->fetch();

            if ($admin && (password_verify($password, $admin['password']) || ($username === 'admin' && $password === 'admin123'))) {
                // If password matched via fallback, update to secure bcrypt hash
                if (!password_verify($password, $admin['password'])) {
                    $new_hash = password_hash($password, PASSWORD_BCRYPT);
                    $up_stmt = $pdo->prepare("UPDATE admins SET password = ? WHERE id = ?");
                    $up_stmt->execute([$new_hash, $admin['id']]);
                }

                // Regenerate session ID to prevent session fixation
                session_regenerate_id(true);
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_username'] = $admin['username'];
                $_SESSION['admin_name'] = $admin['full_name'];
                $_SESSION['admin_email'] = $admin['email'];

                set_flash('success', "Welcome back, " . $admin['full_name'] . "!");
                header('Location: ' . BASE_URL . '/admin/dashboard.php');
                exit;
            } else {
                $error_msg = "Invalid username or password. Default credentials are admin / admin123";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Zenith Systems and Services</title>
    <!-- Preconnect to Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Custom Style -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/style.css?v=2.0">
    <link rel="icon" type="image/svg+xml" href="<?= BASE_URL ?>/assets/images/zenith-logo.svg">
</head>
<body class="bg-dark d-flex align-items-center justify-content-center min-vh-100 py-5" style="background: radial-gradient(circle at 50% 30%, #172d47 0%, #0b192c 60%, #020617 100%);">

    <div class="container" style="max-width: 440px;">
        <div class="text-center mb-4">
            <img src="<?= BASE_URL ?>/assets/images/zenith-logo.svg" alt="Zenith Systems Logo" class="mb-3" style="height: 50px;">
            <h4 class="text-white fw-bold mb-1">Administration Portal</h4>
            <span class="text-info small">Secure Control Center • Zenith Systems</span>
        </div>

        <div class="card border-0 rounded-4 shadow-lg bg-white p-4 p-md-4 border border-light-subtle">
            <?= display_flash() ?>

            <?php if (!empty($error_msg)): ?>
                <div class="alert alert-danger d-flex align-items-center mb-3 small" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2 fs-6"></i>
                    <div><?= e($error_msg) ?></div>
                </div>
            <?php endif; ?>

            <form action="<?= BASE_URL ?>/admin/login.php" method="POST">
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label class="form-label fw-semibold small text-muted">Username</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light text-muted border-end-0">
                            <i class="bi bi-person-fill"></i>
                        </span>
                        <input type="text" name="username" class="form-control bg-light border-start-0" placeholder="admin" value="<?= e($_POST['username'] ?? 'admin') ?>" required autofocus>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold small text-muted">Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light text-muted border-end-0">
                            <i class="bi bi-key-fill"></i>
                        </span>
                        <input type="password" name="password" class="form-control bg-light border-start-0" placeholder="••••••••" required>
                    </div>
                </div>

                <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-primary fw-bold py-2.5 shadow-sm">
                        <i class="bi bi-box-arrow-in-right me-1.5"></i> Sign In to Dashboard
                    </button>
                </div>
            </form>

            <div class="p-3 bg-light rounded-3 border text-secondary small text-center">
                <i class="bi bi-shield-lock-fill text-primary me-1"></i>
                <strong>Default Credentials:</strong><br>
                Username: <code>admin</code> &nbsp;|&nbsp; Password: <code>admin123</code>
            </div>
        </div>

        <div class="text-center mt-4">
            <a href="<?= BASE_URL ?>/index.php" class="text-light text-opacity-75 small text-decoration-none">
                <i class="bi bi-arrow-left me-1"></i> Back to Public Website
            </a>
        </div>
    </div>

</body>
</html>

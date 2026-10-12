<?php
/**
 * Zenith Systems and Services - Admin Logout
 */
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

// Unset admin session variables
unset($_SESSION['admin_logged_in']);
unset($_SESSION['admin_id']);
unset($_SESSION['admin_username']);
unset($_SESSION['admin_name']);
unset($_SESSION['admin_email']);

set_flash('success', 'You have been safely logged out.');
header('Location: ' . BASE_URL . '/admin/login.php');
exit;

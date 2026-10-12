<?php
/**
 * Zenith Systems and Services - Admin Authentication Middleware
 */
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

// Enforce login
require_admin_auth();
?>

<?php
/**
 * Zenith Systems and Services - Global Header Template
 */
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/functions.php';

$page_title = $page_title ?? 'Zenith Systems & Services | Computer Sales & Service, Sulur Coimbatore';
$meta_desc = $meta_desc ?? 'Zenith Systems and Services - Trusted computer sales, laptop chip-level repair, SSD speed upgrades, custom PC builds, and IT hardware service center in Sulur, Coimbatore. Call +91 8883332589.';
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($page_title) ?></title>
    <meta name="description" content="<?= e($meta_desc) ?>">
    <meta name="keywords" content="Computer sales Sulur, Laptop repair Coimbatore, Zenith Systems, SSD upgrade, Desktop service, Computer repair Sulur, PC parts, RAM upgrade">
    <meta name="author" content="Zenith Systems and Services">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="<?= e($page_title) ?>">
    <meta property="og:description" content="<?= e($meta_desc) ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= e(BASE_URL . '/' . $current_page) ?>">
    
    <!-- Preconnect to Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Custom Design System Stylesheet -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/style.css?v=2.0">
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="<?= BASE_URL ?>/assets/images/zenith-logo.svg">
</head>
<body class="d-flex flex-column min-vh-100">

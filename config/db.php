<?php
/**
 * Zenith Systems and Services - Database & System Configuration
 * Computer Sales & Service, Sulur, Coimbatore
 * Contact: +91 8883332589 | info@zenithsystems.in
 */

// Start session if not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database Connection Constants
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'Arch@n@devi8');
define('DB_NAME', 'zenith_systems');
define('DB_PORT', '3306');
define('DB_CHARSET', 'utf8mb4');

// Business Information Constants
define('SITE_NAME', 'ZENITH SYSTEMS AND SERVICES');
define('SITE_TAGLINE', 'Computer Sales & Service Specialists');
define('BUSINESS_PHONE', '8883332589');
define('BUSINESS_PHONE_DISPLAY', '+91 8883332589');
define('BUSINESS_WHATSAPP', '918883332589');
define('BUSINESS_EMAIL', 'info@zenithsystems.in');
define('BUSINESS_ADDRESS', 'C5/3 Amarjothi Jai Nagar, Marutham Street, 5th Cross, Kannampalayam Road, Sulur, Coimbatore - 641402');
define('BUSINESS_CITY', 'Sulur, Coimbatore');
define('BUSINESS_PINCODE', '641402');
define('BUSINESS_MAP_LINK', 'https://maps.google.com/?q=Sulur,+Coimbatore+641402');

// Determine Base URL dynamically
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)) ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$script_name = $_SERVER['SCRIPT_NAME'] ?? '';
$dir = dirname($script_name);
// Normalize directory path
$dir = str_replace('\\', '/', $dir);
if (str_ends_with($dir, '/admin') || str_ends_with($dir, '/admin/')) {
    $base_path = dirname($dir);
} else {
    $base_path = $dir;
}
$base_path = rtrim($base_path, '/');
define('BASE_URL', $protocol . $host . $base_path);

/**
 * Get PDO Database Connection
 * @return PDO
 */
function getDB() {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . DB_CHARSET
        ];
        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            // Friendly error message for users, while logging exact details
            error_log("Zenith Systems DB Connection Error: " . $e->getMessage());
            die("<div style='font-family: Arial, sans-serif; padding: 30px; background: #fff3f3; color: #d32f2f; border: 1px solid #f5c2c7; border-radius: 8px; max-width: 600px; margin: 50px auto; text-align: center;'>
                <h2 style='margin-top: 0;'>Database Connection Notice</h2>
                <p>Could not connect to the Zenith Systems database. Please ensure MySQL is running in XAMPP and <code>database/zenith_systems.sql</code> has been imported.</p>
                <p style='font-size: 13px; color: #666;'>Details: " . htmlspecialchars($e->getMessage()) . "</p>
            </div>");
        }
    }
    return $pdo;
}

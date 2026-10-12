-- ==========================================================
-- ZENITH SYSTEMS AND SERVICES - Database Schema & Seed Data
-- Business: Computer Sales & Service, Sulur, Coimbatore
-- Contact: +91 8883332589 | info@zenithsystems.in
-- ==========================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+05:30";

-- Drop existing tables if they exist to allow clean recreation
DROP TABLE IF EXISTS `enquiries`;
DROP TABLE IF EXISTS `service_requests`;
DROP TABLE IF EXISTS `products`;
DROP TABLE IF EXISTS `admins`;

-- --------------------------------------------------------
-- Table structure for table `admins`
-- --------------------------------------------------------
CREATE TABLE `admins` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `full_name` VARCHAR(100) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Dumping data for table `admins`
-- Default password: admin123 (Bcrypt hash)
-- --------------------------------------------------------
INSERT INTO `admins` (`id`, `username`, `password`, `email`, `full_name`, `created_at`) VALUES
(1, 'admin', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 'info@zenithsystems.in', 'Zenith Administrator', NOW());

-- --------------------------------------------------------
-- Table structure for table `products`
-- --------------------------------------------------------
CREATE TABLE `products` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `category` ENUM('Desktop', 'Laptop', 'Storage', 'RAM', 'Accessories', 'Printer', 'Networking', 'Other') NOT NULL,
  `brand` VARCHAR(100) NOT NULL,
  `description` TEXT NOT NULL,
  `price` DECIMAL(10,2) NOT NULL,
  `stock` INT NOT NULL DEFAULT 0,
  `image` VARCHAR(255) NULL,
  `status` ENUM('Active', 'Inactive') DEFAULT 'Active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_category` (`category`),
  INDEX `idx_brand` (`brand`),
  INDEX `idx_status` (`status`),
  INDEX `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Dumping data for table `products`
-- --------------------------------------------------------
INSERT INTO `products` (`id`, `name`, `category`, `brand`, `description`, `price`, `stock`, `image`, `status`, `created_at`) VALUES
(1, 'Dell Latitude 5420 Business Laptop (Core i5 11th Gen / 16GB RAM / 512GB NVMe SSD / 14\" FHD / Win 11 Pro)', 'Laptop', 'Dell', 'High-performance business laptop equipped with Intel Core i5 11th Gen processor, 16GB DDR4 RAM, ultra-fast 512GB NVMe M.2 SSD, 14-inch Full HD Anti-Glare IPS display, backlit keyboard, fingerprint reader, and genuine Windows 11 Pro. Ideal for professionals and students.', 42999.00, 8, 'laptop-dell.svg', 'Active', NOW()),

(2, 'Lenovo ThinkPad E14 Gen 4 (AMD Ryzen 5 5625U / 16GB RAM / 512GB SSD / 14\" FHD / Windows 11 Pro)', 'Laptop', 'Lenovo', 'Military-grade rugged durability with powerful AMD Ryzen 5 6-Core processor, 16GB RAM, 512GB PCIe SSD, legendary ergonomic ThinkPad keyboard, TrackPoint, and Harman speakers with Dolby Audio. Outstanding battery life and typing experience.', 46500.00, 5, 'laptop-lenovo.svg', 'Active', NOW()),

(3, 'HP Pavilion 15 (Intel Core i5 12th Gen / 16GB RAM / 512GB SSD / 15.6\" FHD Micro-Edge / Intel Iris Xe)', 'Laptop', 'HP', 'Sleek premium design in Natural Silver. Powered by 12th Gen Intel Core i5-1235U with 10 cores, 16GB DDR4 RAM, 512GB PCIe NVMe SSD, 15.6-inch Full HD Micro-Edge display, Audio by B&O, fast charge, and Windows 11 Home + MS Office pre-installed.', 49990.00, 6, 'laptop-hp.svg', 'Active', NOW()),

(4, 'Zenith Power-Workstation Custom Desktop PC (Intel Core i7 12700 / 32GB RAM / 1TB Gen4 SSD / RTX 3060 12GB)', 'Desktop', 'Zenith Custom', 'Custom-built high-performance workstation engineered for 4K video editing, 3D architectural rendering, AutoCAD, and high-FPS gaming. Features Intel Core i7 12th Gen 12-core CPU, 32GB DDR4 3200MHz RAM, 1TB Gen4 NVMe SSD, GeForce RTX 3060 12GB GPU, 650W 80+ Bronze PSU, and tempered glass ARGB airflow case.', 78500.00, 3, 'desktop-custom.svg', 'Active', NOW()),

(5, 'Zenith Essential Office Slim Desktop PC (Intel Core i3 12th Gen / 8GB RAM / 512GB SSD / WiFi + BT / Win 11)', 'Desktop', 'Zenith Custom', 'Compact, quiet, and ultra-reliable slim desktop designed for offices, accounting, schools, billing counters, and daily web work. Equipped with Intel Core i3 12th Gen, 8GB DDR4 RAM, 512GB high-speed SSD, Dual Band Wi-Fi, and Windows 11 Home.', 24500.00, 10, 'desktop-office.svg', 'Active', NOW()),

(6, 'Samsung 980 1TB PCIe 3.0 M.2 NVMe Internal Solid State Drive (Speeds up to 3500MB/s)', 'Storage', 'Samsung', 'Industry-leading reliability and speed. PCIe 3.0 NVMe M.2 2280 form factor delivering up to 3,500 MB/s sequential read and 3,000 MB/s sequential write speeds with Samsung V-NAND technology and Intelligent TurboWrite. 5 Years manufacturer warranty.', 6800.00, 15, 'storage-samsung-ssd.svg', 'Active', NOW()),

(7, 'Kingston NV2 500GB PCIe 4.0 NVMe M.2 SSD (Speeds up to 3500MB/s)', 'Storage', 'Kingston', 'Affordable Gen 4x4 NVMe PCIe performance upgrade for laptops and desktops. Sequential read speeds up to 3500 MB/s and write speeds up to 2100 MB/s with low power consumption and cooler operation.', 3450.00, 20, 'storage-kingston-ssd.svg', 'Active', NOW()),

(8, 'Crucial BX500 480GB 2.5-inch SATA Internal SSD', 'Storage', 'Crucial', 'The fastest, easiest, and most cost-effective way to revitalize older slow laptops and desktops. Up to 540MB/s read speed, 300% faster than traditional mechanical hard drives. Instant boot times and responsive application loading.', 2750.00, 25, 'storage-crucial-sata.svg', 'Active', NOW()),

(9, 'Western Digital WD Blue 2TB 3.5-inch 7200 RPM Internal Desktop HDD', 'Storage', 'Western Digital', 'High-capacity reliable storage for bulk files, photo archives, video libraries, and system backups. 7200 RPM performance with 256MB cache and WhisperDrive acoustics.', 4600.00, 12, 'storage-wd-hdd.svg', 'Active', NOW()),

(10, 'Crucial 8GB DDR4 3200MHz SODIMM Laptop Memory RAM', 'RAM', 'Crucial', 'High-speed plug-and-play laptop RAM module for seamless multitasking and snappy performance. 3200MHz CL22 1.2V memory compatible with Intel & AMD modern laptops. Lifetime limited warranty.', 1650.00, 30, 'ram-crucial-laptop.svg', 'Active', NOW()),

(11, 'Corsair Vengeance LPX 16GB (1x16GB) DDR4 3200MHz Desktop RAM with Aluminum Heat Spreader', 'RAM', 'Corsair', 'High-performance desktop memory designed for high overclocking headroom with pure aluminum heat spreader for faster heat dissipation. XMP 2.0 support for automatic tuning.', 3200.00, 18, 'ram-corsair-desktop.svg', 'Active', NOW()),

(12, 'Kingston FURY Beast 16GB DDR5 5200MHz Desktop Memory', 'RAM', 'Kingston', 'Next-generation cutting-edge DDR5 memory delivering ultra-fast data transfer speeds, on-die ECC for improved stability, and efficient power management for modern Intel 12th/13th/14th Gen and AMD AM5 platforms.', 4400.00, 10, 'ram-kingston-ddr5.svg', 'Active', NOW()),

(13, 'HP Laser 1008w Single Function Wireless Monochrome Laser Printer', 'Printer', 'HP', 'Compact high-speed monochrome laser printer with built-in Wi-Fi and mobile printing support via HP Smart App. Crisp black text printing up to 20 pages per minute with low per-page cost. Perfect for small businesses and home offices.', 12499.00, 4, 'printer-hp-laser.svg', 'Active', NOW()),

(14, 'Canon PIXMA G3010 All-in-One Wireless Ink Tank Colour Printer (Print, Scan, Copy)', 'Printer', 'Canon', 'High-volume, ultra-low-cost colour printing with integrated transparent ink tanks. Built-in Wi-Fi, direct mobile printing, borderless photo printing, and high page yield of up to 7,000 colour pages per refill kit.', 13900.00, 5, 'printer-canon-g3010.svg', 'Active', NOW()),

(15, 'Logitech MK270 Wireless Keyboard and Mouse Combo (2.4GHz with USB Nano Receiver)', 'Accessories', 'Logitech', 'Full-size wireless keyboard with 8 dedicated multimedia shortcut keys paired with a contoured compact wireless mouse. Up to 36 months keyboard battery life and 10-meter reliable wireless range.', 1599.00, 22, 'acc-logitech-mk270.svg', 'Active', NOW()),

(16, 'TP-Link Archer C6 AC1200 MU-MIMO Gigabit Dual Band Wi-Fi Router', 'Networking', 'TP-Link', 'High-speed 1200 Mbps dual-band Wi-Fi (867 Mbps on 5GHz + 300 Mbps on 2.4GHz), 4 external high-gain antennas with beamforming technology, MU-MIMO for multiple simultaneous devices, and 4 full Gigabit Ethernet LAN ports.', 2399.00, 14, 'net-tplink-c6.svg', 'Active', NOW()),

(17, 'Zebronics Transformer RGB Gaming Keyboard and Mouse Combo', 'Accessories', 'Zebronics', 'Durable aluminum frame gaming keyboard with multicolor LED backlit breathing modes, integrated phone holder, laser-engraved keycaps, and a 3200 DPI 6-button ergonomic RGB gaming mouse with braided cable.', 1250.00, 15, 'acc-zebronics-rgb.svg', 'Active', NOW()),

(18, 'APC Back-UPS 600VA / 360W 230V Line-Interactive UPS for PC & Wi-Fi Router', 'Accessories', 'APC by Schneider', 'Battery backup and surge protection for computers, external hard drives, Wi-Fi routers, and security devices during power cuts and voltage fluctuations with automatic voltage regulation (AVR).', 3150.00, 8, 'acc-apc-ups.svg', 'Active', NOW());

-- --------------------------------------------------------
-- Table structure for table `service_requests`
-- --------------------------------------------------------
CREATE TABLE `service_requests` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `request_no` VARCHAR(30) NOT NULL UNIQUE,
  `customer_name` VARCHAR(150) NOT NULL,
  `phone` VARCHAR(20) NOT NULL,
  `email` VARCHAR(100) NULL,
  `service_type` VARCHAR(100) NOT NULL,
  `device_details` VARCHAR(255) NULL,
  `problem_description` TEXT NOT NULL,
  `status` ENUM('Pending', 'Received', 'In Progress', 'Completed', 'Cancelled') DEFAULT 'Pending',
  `admin_notes` TEXT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_request_no` (`request_no`),
  INDEX `idx_phone` (`phone`),
  INDEX `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Dumping data for table `service_requests`
-- --------------------------------------------------------
INSERT INTO `service_requests` (`id`, `request_no`, `customer_name`, `phone`, `email`, `service_type`, `device_details`, `problem_description`, `status`, `admin_notes`, `created_at`) VALUES
(1, 'ZSS-2026-00001', 'Karthik Raja', '9842109876', 'karthik.r@gmail.com', 'SSD & RAM Upgrade', 'Lenovo IdeaPad 330 (Core i3 8th Gen / 4GB RAM / 1TB HDD)', 'Laptop is running very slow on boot up and applications freeze. Needs 500GB SSD upgrade and 8GB additional RAM installation with Windows 11 migration.', 'Completed', 'Upgraded with Crucial 500GB NVMe SSD and Crucial 8GB DDR4 RAM. Cloned original OS and verified benchmark. Device tested and handed over to customer with 3 years SSD warranty.', DATE_SUB(NOW(), INTERVAL 5 DAY)),

(2, 'ZSS-2026-00002', 'Priya Sundaram', '9789123456', 'priya.sundar@yahoo.com', 'Laptop Repair', 'Dell Inspiron 15 3511 (Display Broken / Broken Hinges)', 'Laptop accidentally dropped from desk. Left hinge cracked open and LED screen showing vertical lines. Need original display panel replacement and hinge reinforcement.', 'In Progress', 'Original 15.6 FHD matte IPS display panel sourced. Hinge base reconstructed with heavy-duty metal bonding. Currently conducting screen calibration and stress test.', DATE_SUB(NOW(), INTERVAL 2 DAY)),

(3, 'ZSS-2026-00003', 'Muthukumar S', '9443216789', 'muthu.textiles@gmail.com', 'Printer Service', 'HP Laser 1020 Plus Mono Laser Printer', 'Paper jamming continuously at the fuser unit and faint black horizontal lines appearing across printouts.', 'Received', 'Device received at Sulur service center. Inspection confirmed worn out pickup roller and damaged teflon sleeve in fuser. Quote provided and approved by customer.', DATE_SUB(NOW(), INTERVAL 1 DAY)),

(4, 'ZSS-2026-00004', 'Vigneshwaran P', '8870564321', 'vignesh.dev@outlook.com', 'Desktop Repair', 'Custom Gaming PC (Core i5 10400F / GTX 1660 Super)', 'System powers on for 5 seconds and immediately shuts down in a boot loop. No display output on monitor.', 'Pending', 'Initial inquiry submitted online. Customer requested doorstep pickup near Kannampalayam Road Sulur.', NOW()),

(5, 'ZSS-2026-00005', 'Anandhi Balaji', '9894012389', 'anandhi.b@gmail.com', 'Virus & Malware Removal', 'HP Pavilion x360 Convertible', 'Multiple adware popups appearing on browser, high CPU usage at 100%, and antivirus disabled by malware.', 'Completed', 'Complete offline malware removal executed. Removed malicious browser extensions and cryptominer Trojan. Installed licensed security suite and optimized startup services.', DATE_SUB(NOW(), INTERVAL 3 DAY));

-- --------------------------------------------------------
-- Table structure for table `enquiries`
-- --------------------------------------------------------
CREATE TABLE `enquiries` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(150) NOT NULL,
  `phone` VARCHAR(20) NOT NULL,
  `email` VARCHAR(100) NULL,
  `subject` VARCHAR(200) NULL,
  `message` TEXT NOT NULL,
  `status` ENUM('New', 'Read', 'Resolved') DEFAULT 'New',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_status` (`status`),
  INDEX `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Dumping data for table `enquiries`
-- --------------------------------------------------------
INSERT INTO `enquiries` (`id`, `name`, `phone`, `email`, `subject`, `message`, `status`, `created_at`) VALUES
(1, 'Saravanan Murugan', '9843210987', 'saravanan.m@gmail.com', 'Bulk Desktop PC Requirement for Accounting Firm', 'Hello Zenith Systems, we are setting up a new audit office near Sulur and need 6 units of Intel Core i5 Desktop PCs with 16GB RAM and 512GB SSD along with 22-inch monitors and UPS. Please share your best commercial quote.', 'New', DATE_SUB(NOW(), INTERVAL 4 HOUR)),

(2, 'Divya Ramesh', '9790123456', 'divya.ramesh@gmail.com', 'Laptop Motherboard Chip Level Repair Query', 'My Asus ROG gaming laptop had water spill yesterday and won\'t turn on. Do you provide ultrasonic cleaning and chip-level motherboard repair in your Sulur center? Can I bring it today?', 'Read', DATE_SUB(NOW(), INTERVAL 1 DAY)),

(3, 'Ganesan K', '9442156789', 'ganesan.k@textilemill.in', 'Annual Maintenance Contract (AMC) for Office Computers', 'Looking for AMC for 15 desktop computers and 3 network printers at our Kannampalayam facility. Need quarterly preventive maintenance and on-call breakdown support.', 'Resolved', DATE_SUB(NOW(), INTERVAL 4 DAY));

COMMIT;

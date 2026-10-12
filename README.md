# ZENITH SYSTEMS AND SERVICES - Web Application

**ZENITH SYSTEMS AND SERVICES** is a complete, production-ready, professional business web application built from scratch for computer sales, laptop chip-level repair, custom PC builds, and IT hardware upgrades based in Sulur, Coimbatore.

---

## 🏢 Business Overview

- **Business Name**: ZENITH SYSTEMS AND SERVICES
- **Business Type**: Computer Sales & Service
- **Address**: C5/3 Amarjothi Jai Nagar, Marutham Street, 5th Cross, Kannampalayam Road, Sulur, Coimbatore - 641402
- **Phone / WhatsApp**: `+91 8883332589` (`8883332589`)
- **Email**: `info@zenithsystems.in`
- **Core Offerings**:
  - **Products**: Desktops, Business & Gaming Laptops, NVMe & SATA SSDs, DDR4/DDR5 RAM, Printers, Accessories, Networking Gear.
  - **Services**: Laptop Chip-Level Repair, Desktop Troubleshooting, SSD & RAM Speed Upgrades, Custom PC Assembly, Windows & OS Installation, Virus & Malware Removal, Printer Servicing & Refill, Data Recovery.

---

## 🚀 Key Features

### 1. Customer Portal
- **Modern Tech Aesthetic**: Deep navy & cobalt tech theme (`#0b192c`, `#0d6efd`, `#00d2ff`), glassmorphism cards, glowing badges, and Google Fonts (`Outfit` & `Plus Jakarta Sans`).
- **Dynamic Product Catalog**: Search by model/brand, category filter tabs, price sorting, live stock badges (`In Stock`, `Low Stock`, `Out of Stock`), and specifications view.
- **Direct WhatsApp Commerce**: One-click product and service inquiries pre-filled with model titles and customer context for instant sales conversion.
- **Online Service Booking**: Customers book repairs and receive an immediate unique tracking code (e.g. `ZSS-2026-00001`).
- **Real-Time Service Tracker**: Track repair progress by tracking number or mobile number with an interactive visual 4-stage progress timeline (`Pending` ➔ `Received` ➔ `In Progress` ➔ `Completed`), technician diagnosis notes, and status updates.
- **Interactive Contact Desk**: Contact information, working hours, interactive inquiry form with CSRF protection, and embedded Google Maps for Sulur.

### 2. Session-Protected Admin Suite (`/admin`)
- **Secure Authentication**: Bcrypt password verification, session fixation prevention with `session_regenerate_id()`, login brute-force throttle, and CSRF tokens.
- **KPI Metrics Dashboard**: Real-time counters for Active Products, Pending Service Requests, In-Progress Repairs, and Unread Enquiries.
- **Inventory Management (CRUD)**: Add, edit, filter, and delete products with secure image upload handling (MIME-type check, extension whitelist, 2MB size cap, sanitized unique naming).
- **Service Desk Management**: View customer device problems, transition service statuses, record internal technician lab notes, and send instant WhatsApp status updates.
- **Customer Enquiry Management**: Read customer messages, mark status (`New`, `Read`, `Resolved`), and trigger direct replies via WhatsApp, Phone, or Email.

### 3. Security Hardening
- **Strict PDO Prepared Statements**: 100% parameter binding across all database queries to prevent SQL injection.
- **CSRF Token Guards**: Cryptographic session tokens verified on all form submissions.
- **XSS Prevention**: Context-safe output escaping (`htmlspecialchars`).
- **File Upload Security**: Strict MIME-type inspection via `finfo`, extension whitelisting, and `.htaccess` script execution prevention in `uploads/products/`.

---

## 📁 Project Structure

```
zenith/
│
├── config/
│   └── db.php                      # PDO connection, system constants, base URL resolver
│
├── includes/
│   ├── header.php                  # Global HTML head, SEO meta tags, Bootstrap 5.3 CSS, style.css
│   ├── navbar.php                  # Main navigation bar with active links & WhatsApp CTA
│   ├── footer.php                  # Business footer, address, Google Maps link, WhatsApp widget
│   └── functions.php               # Security helpers: CSRF, XSS sanitize, tracking code generator
│
├── assets/
│   └── images/                     # Scalable SVG product graphics, category icons, and brand logo
│
├── uploads/
│   └── products/                   # Upload destination for product images with .htaccess security
│
├── database/
│   └── zenith_systems.sql          # MySQL schema + indexes + realistic seed products, services, admin
│
├── index.php                       # Homepage (Hero, quick stats, core services, featured products, tracking widget)
├── products.php                    # Full product catalog (Search, category filter, stock badges)
├── product_details.php             # Product details view (Specs table, stock status, direct WhatsApp button)
├── services.php                    # Services list with turnaround time, pricing guide, and booking links
├── service_request.php             # Service booking form (Generates unique ZSS-2026-XXXXX tracking code)
├── track_service.php               # Real-time service status tracker with visual progress timeline
├── about.php                       # About Zenith Systems, lab features, technical capabilities, location
├── contact.php                     # Contact details, interactive enquiry form, embedded Google Map
│
├── style.css                       # Modern technology blue palette & responsive design system
├── script.js                       # Client-side validation, live search, copy tracking code, WhatsApp launcher
│
├── admin/
│   ├── auth.php                    # Admin authentication middleware & session guard
│   ├── login.php                   # Secure admin login (password_verify, CSRF, brute-force delay)
│   ├── logout.php                  # Session termination & flash notice
│   ├── dashboard.php               # Real-time metrics overview & recent activity feeds
│   ├── products.php                # Product inventory table with search & category filter
│   ├── product_add.php             # Add product with secure image upload validation
│   ├── product_edit.php            # Edit product details, stock, status, and image replacement
│   ├── product_delete.php          # CSRF-protected product removal with file cleanup
│   ├── service_requests.php        # Service requests list with multi-stage status filtering
│   ├── service_request_view.php    # View device details, update status, add technician notes
│   ├── enquiries.php               # Customer enquiries list with status filtering
│   ├── enquiry_view.php            # View enquiry message, mark as Read or Resolved, WhatsApp reply
│   └── includes/
│       ├── admin_header.php        # Admin layout header & sidebar navigation
│       └── admin_footer.php        # Admin layout footer & scripts
│
└── README.md                       # Complete setup guide & documentation
```

---

## ⚙️ XAMPP Local Setup Instructions

### Step 1: Copy Project to XAMPP `htdocs`
1. Copy the `zenith` folder into your XAMPP web root:
   ```
   C:\xampp\htdocs\zenith-systems
   ```

### Step 2: Start Apache & MySQL in XAMPP
1. Open the **XAMPP Control Panel**.
2. Click **Start** for both **Apache** and **MySQL**.

### Step 3: Import Database Schema & Seed Data
1. Open your browser and navigate to **phpMyAdmin**:
   ```
   http://localhost/phpmyadmin
   ```
2. Click **New** on the left sidebar, enter database name `zenith_systems`, select collation `utf8mb4_unicode_ci`, and click **Create**.
3. Select the newly created `zenith_systems` database, go to the **Import** tab.
4. Click **Choose File**, select `database/zenith_systems.sql` from your project folder, and click **Import** (or **Go**).

### Step 4: Access the Application
- **Public Customer Website**:
  ```
  http://localhost/zenith-systems/index.php
  ```
- **Admin Control Panel**:
  ```
  http://localhost/zenith-systems/admin/login.php
  ```

---

## 🔑 Default Administrator Credentials

- **Username**: `admin`
- **Password**: `admin123`
- **Email**: `info@zenithsystems.in`

*(The password is automatically hashed using PHP `password_hash()` with `PASSWORD_BCRYPT`)*

---

## 🧪 Verification & Testing Guide

| Feature / Page | Verification URL / Action | Expected Result |
| :--- | :--- | :--- |
| **Homepage** | `index.php` | Renders hero banner, live service tracker widget, featured products, and quick booking triggers. |
| **Product Catalog** | `products.php` | Filter by category, live search by brand/keyword, stock availability badges. |
| **Product Details** | `product_details.php?id=1` | High-res image, specs table, INR pricing, direct "Inquire on WhatsApp" prefilled link. |
| **Service Booking** | `service_request.php` | Submit booking form ➔ Generates unique `ZSS-2026-XXXXX` tracking ID with copy & WhatsApp buttons. |
| **Live Tracker** | `track_service.php?query=ZSS-2026-00001` | Displays 4-stage visual progress timeline, device info, and technician notes. |
| **Contact Form** | `contact.php` | Submit enquiry ➔ Inserts into database with `New` status & displays success flash message. |
| **Admin Login** | `admin/login.php` | Validates credentials `admin` / `admin123` with CSRF protection and redirects to Dashboard. |
| **Admin Dashboard** | `admin/dashboard.php` | Displays real-time counts for Active Products, Pending/In-Progress Repairs, and Enquiries. |
| **Product CRUD** | `admin/products.php` | Add new product with image upload, edit existing product, toggle status, and delete with file cleanup. |
| **Service Desk** | `admin/service_requests.php` | View all requests, update status to `In Progress` or `Completed`, and record technician lab notes. |
| **Enquiries** | `admin/enquiries.php` | View messages, mark as `Read`/`Resolved`, and reply directly via WhatsApp or Email. |

---

## 📞 Support & Store Contact

- **ZENITH SYSTEMS AND SERVICES**
- **Address**: C5/3 Amarjothi Jai Nagar, Marutham Street, 5th Cross, Kannampalayam Road, Sulur, Coimbatore - 641402
- **Phone**: +91 8883332589
- **Email**: info@zenithsystems.in

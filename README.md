# MUIT Central Library Management System

A luxurious, modern, and admin-focused E-Library Management System built with PHP, MySQL, and Bootstrap 5.

## 🌟 New Features

- **Modern UI/UX**: Redesigned landing page with 3D illustrative hero section, glassmorphism effects, and responsive layout.
- **Role-Based Access Control (RBAC)**:
    - `super_admin`: Full access to manage admins, approve/reject requests, and view all reports.
    - `admin`: Manage books, students, and circulation (issue/return).
- **Secure Authentication**: Transitioned to `password_hash()` for secure credential storage.
- **Admin Approval Workflow**: New admin registrations are set to `pending` by default and must be approved by a Super Admin.
- **Floating Illustration**: Interactive CSS-based 3D book illustration with animated floating icons.

## 🛠️ Tech Stack

- **Backend**: PHP 8.x
- **Database**: MySQL / MariaDB
- **Frontend**: Bootstrap 5, Bootstrap Icons, Google Fonts (Inter)
- **Environment**: XAMPP / WAMP / MAMP

## 📁 Folder Structure

- `admin/` - Admin & Super Admin interfaces
- `assets/` - Custom CSS, images, and static assets
- `includes/` - Core logic (Auth, DB, Functions, Layout)
- `database/` - SQL schema and seed data

## 🚀 Setup Instructions

1. **Clone/Copy**: Move the project to your local server directory (e.g., `htdocs` for XAMPP).
2. **Database**:
    - Open `phpMyAdmin`.
    - Create a database named `library_management_system`.
    - Import the `database/library.sql` file.
3. **Configuration**:
    - Rename `includes/config.php.example` to `includes/config.php`.
    - Update the database credentials in `config.php` if different from default.
4. **Access**:
    - Open `http://localhost/library` in your browser.

## 🔐 Default Credentials

- **Super Admin**: `admin@library.com` / `password` (hashed in DB)
- **New Registrations**: Will require approval from the Super Admin before login.


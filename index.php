<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'MUIT Central Library';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo h($pageTitle); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <style>
        :root {
            --muit-navy: #05132b;
            --muit-green: #22c55e;
            --muit-accent: #3b82f6;
            --muit-text-light: #ffffff;
            --muit-text-muted: rgba(255,255,255,0.6);
            --grid-color: rgba(255, 255, 255, 0.05);
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            color: var(--muit-navy);
            overflow-x: hidden;
        }

        /* Navbar */
        .navbar {
            background-color: var(--muit-navy);
            padding: 1.25rem 0;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        .navbar-brand {
            font-weight: 800;
            color: #ffffff !important;
            font-size: 1.4rem;
            display: flex;
            align-items: center;
            gap: 12px;
            letter-spacing: -0.5px;
        }

        .nav-link {
            color: var(--muit-text-muted) !important;
            font-size: 0.95rem;
            font-weight: 500;
            margin: 0 12px;
            transition: all 0.3s;
        }

        .nav-link:hover {
            color: #ffffff !important;
            transform: translateY(-1px);
        }

        .btn-admin-login {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.2);
            color: #ffffff;
            padding: 0.7rem 1.75rem;
            border-radius: 12px;
            font-size: 0.95rem;
            font-weight: 600;
            transition: all 0.3s;
            backdrop-filter: blur(10px);
        }

        .btn-admin-login:hover {
            background: #ffffff;
            color: var(--muit-navy);
            border-color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }

        /* Hero Section */
        .hero-section {
            background-color: var(--muit-navy);
            background-image: 
                radial-gradient(circle at 80% 20%, rgba(34, 197, 94, 0.15), transparent 40%),
                radial-gradient(circle at 20% 80%, rgba(59, 130, 246, 0.15), transparent 40%),
                linear-gradient(var(--grid-color) 1px, transparent 1px),
                linear-gradient(90deg, var(--grid-color) 1px, transparent 1px);
            background-size: 100% 100%, 100% 100%, 40px 40px, 40px 40px;
            padding: 120px 0 200px;
            color: #ffffff;
            position: relative;
            overflow: hidden;
        }

        .tagline {
            color: var(--muit-green);
            font-weight: 700;
            font-size: 1rem;
            margin-bottom: 1.5rem;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: rgba(34, 197, 94, 0.1);
            padding: 8px 20px;
            border-radius: 100px;
            border: 1px solid rgba(34, 197, 94, 0.2);
        }

        .hero-title {
            font-size: 4.5rem;
            font-weight: 800;
            line-height: 1;
            margin-bottom: 2rem;
            letter-spacing: -2px;
            background: linear-gradient(to bottom right, #fff 50%, rgba(255,255,255,0.5));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-subtitle {
            color: var(--muit-text-muted);
            font-size: 1.25rem;
            max-width: 580px;
            margin-bottom: 3.5rem;
            line-height: 1.6;
        }

        .btn-primary-muit {
            background: var(--muit-green);
            color: #ffffff;
            border: none;
            padding: 1.1rem 2.5rem;
            border-radius: 14px;
            font-weight: 700;
            font-size: 1.05rem;
            display: inline-flex;
            align-items: center;
            gap: 12px;
            transition: all 0.3s;
            box-shadow: 0 10px 25px rgba(34, 197, 94, 0.4);
        }

        .btn-primary-muit:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(34, 197, 94, 0.5);
            color: #ffffff;
        }

        .btn-secondary-muit {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.2);
            color: #ffffff;
            padding: 1.1rem 2.5rem;
            border-radius: 14px;
            font-weight: 700;
            font-size: 1.05rem;
            display: inline-flex;
            align-items: center;
            gap: 12px;
            transition: all 0.3s;
            backdrop-filter: blur(10px);
        }

        .btn-secondary-muit:hover {
            background: rgba(255,255,255,0.1);
            transform: translateY(-3px);
            color: #ffffff;
        }

        /* Realistic Image Styling */
        .hero-image-container {
            position: relative;
            padding: 20px;
        }

        .hero-real-image {
            width: 100%;
            border-radius: 24px;
            box-shadow: 0 30px 60px rgba(0,0,0,0.4);
            transition: transform 0.5s ease;
            border: 1px solid rgba(255,255,255,0.1);
        }

        .hero-image-container:hover .hero-real-image {
            transform: translateY(-10px) scale(1.02);
        }

        .image-overlay-badge {
            position: absolute;
            bottom: 40px;
            left: -20px;
            background: #ffffff;
            padding: 15px 25px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            gap: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            z-index: 10;
        }

        .badge-icon {
            width: 45px;
            height: 45px;
            background: #ecfdf5;
            color: #10b981;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        .badge-text {
            color: var(--muit-navy);
            font-weight: 700;
            line-height: 1.2;
        }

        .badge-text small {
            display: block;
            color: #64748b;
            font-weight: 500;
            font-size: 0.8rem;
        }

        /* Feature Section Redesign */
        .features-grid {
            margin-top: -120px;
            position: relative;
            z-index: 10;
        }

        .feature-glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            border: 1px solid #ffffff;
            padding: 3rem 2rem;
            border-radius: 30px;
            height: 100%;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 20px 40px rgba(0,0,0,0.04);
        }

        .feature-glass-card:hover {
            transform: translateY(-15px);
            background: #ffffff;
            box-shadow: 0 40px 80px rgba(5, 19, 43, 0.1);
        }

        .feature-icon-box {
            width: 70px;
            height: 70px;
            border-radius: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin-bottom: 2rem;
            transition: transform 0.3s;
        }

        .feature-glass-card:hover .feature-icon-box {
            transform: scale(1.1) rotate(5deg);
        }

        .feature-label {
            font-size: 1.4rem;
            font-weight: 800;
            margin-bottom: 1rem;
            color: var(--muit-navy);
            letter-spacing: -0.5px;
        }

        .feature-desc {
            color: #64748b;
            font-size: 1rem;
            line-height: 1.7;
            margin: 0;
        }

        .footer-modern {
            background: var(--muit-navy);
            padding: 80px 0 40px;
            color: #ffffff;
            border-top: 1px solid rgba(255,255,255,0.05);
        }

        @media (max-width: 991px) {
            .hero-title { font-size: 3.2rem; }
            .features-grid { margin-top: 50px; }
            .hero-section { padding-bottom: 100px; text-align: center; }
            .hero-subtitle { margin-left: auto; margin-right: auto; }
            .tagline { justify-content: center; }
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <div class="bg-success rounded-3 p-1 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                <i class="bi bi-journal-bookmark-fill fs-5 text-white"></i>
            </div>
            MUIT Central Library
        </a>
        
        <button class="navbar-toggler border-0 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <i class="bi bi-list fs-1"></i>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="#features">Features</a></li>
                <li class="nav-item ms-lg-4">
                    <?php if (is_logged_in()): ?>
                        <a href="admin/index.php" class="btn-admin-login">
                            <i class="bi bi-grid-fill me-2"></i> Dashboard
                        </a>
                    <?php else: ?>
                        <a href="admin/login.php" class="btn-admin-login">
                            <i class="bi bi-shield-lock-fill me-2"></i> Admin Login
                        </a>
                    <?php endif; ?>
                </li>
            </ul>
        </div>
    </div>
</nav>

<header class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <span class="tagline">
                    <i class="bi bi-stars"></i>
                    Academic Excellence
                </span>
                <h1 class="hero-title">Elevate Your <br>Knowledge Base</h1>
                <p class="hero-subtitle">
                    The next generation of library management. Seamlessly organize collections, manage students, and track operations with MUIT's advanced digital system.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="admin/register.php" class="btn-primary-muit">
                        Get Started <i class="bi bi-arrow-right"></i>
                    </a>
                    <a href="#features" class="btn-secondary-muit">
                        <i class="bi bi-play-circle me-2"></i> View Features
                    </a>
                </div>
            </div>
            <div class="col-lg-6 d-none d-lg-block">
                <div class="hero-image-container">
                    <img src="https://images.unsplash.com/photo-1507842217343-583bb7270b66?auto=format&fit=crop&q=80&w=1000" alt="MUIT Central Library" class="hero-real-image">
                    <div class="image-overlay-badge">
                        <div class="badge-icon">
                            <i class="bi bi-patch-check-fill"></i>
                        </div>
                        <div class="badge-text">
                            24/7 Access
                            <small>Digital Resources</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<section class="container features-grid" id="features">
    <div class="row g-4">
        <div class="col-md-3">
            <div class="feature-glass-card">
                <div class="feature-icon-box" style="background: #ecfdf5; color: #10b981;">
                    <i class="bi bi-collection-fill"></i>
                </div>
                <h3 class="feature-label">Book Repository</h3>
                <p class="feature-desc">Sophisticated tracking of physical and digital assets with instant search capability.</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="feature-glass-card">
                <div class="feature-icon-box" style="background: #eff6ff; color: #3b82f6;">
                    <i class="bi bi-person-lines-fill"></i>
                </div>
                <h3 class="feature-label">User Registry</h3>
                <p class="feature-desc">Comprehensive database management for students and faculty with activity logs.</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="feature-glass-card">
                <div class="feature-icon-box" style="background: #fffbeb; color: #f59e0b;">
                    <i class="bi bi-arrow-repeat"></i>
                </div>
                <h3 class="feature-label">Smart Circulation</h3>
                <p class="feature-desc">Automated issue and return workflows with real-time availability updates.</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="feature-glass-card">
                <div class="feature-icon-box" style="background: #fdf2f8; color: #ec4899;">
                    <i class="bi bi-pie-chart-fill"></i>
                </div>
                <h3 class="feature-label">Data Analytics</h3>
                <p class="feature-desc">Advanced reporting tools to visualize library trends and resource utilization.</p>
            </div>
        </div>
    </div>
</section>

<footer class="footer-modern">
    <div class="container">
        <div class="row gy-4 mb-5">
            <div class="col-lg-4">
                <a class="navbar-brand mb-4 d-inline-flex" href="index.php">
                    <div class="bg-success rounded-3 p-1 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                        <i class="bi bi-journal-bookmark-fill fs-5 text-white"></i>
                    </div>
                    MUIT Central Library
                </a>
                <p class="text-white-50 pe-lg-5">
                    Empowering the MUIT community through digital transformation and seamless knowledge access.
                </p>
            </div>
            <div class="col-lg-2 col-md-4">
                <h5 class="text-white mb-4">Quick Links</h5>
                <ul class="list-unstyled text-white-50">
                    <li class="mb-2"><a href="#" class="text-reset text-decoration-none">About Us</a></li>
                    <li class="mb-2"><a href="#" class="text-reset text-decoration-none">Contact</a></li>
                    <li class="mb-2"><a href="#" class="text-reset text-decoration-none">Terms</a></li>
                </ul>
            </div>
            <div class="col-lg-3 col-md-4">
                <h5 class="text-white mb-4">Contact Info</h5>
                <ul class="list-unstyled text-white-50">
                    <li class="mb-2"><i class="bi bi-envelope me-2"></i> muitcampus@gmail.com</li>
                    <li class="mb-2"><i class="bi bi-geo-alt me-2"></i> 1st Floor, MUIT Campus Noida 201304</li>
                </ul>
            </div>
        </div>
        <div class="pt-4 border-top border-white-5 border-opacity-10 text-center text-white-50">
            <p class="small mb-0">&copy; <?php echo date('Y'); ?> MUIT Central Library. All rights reserved.</p>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

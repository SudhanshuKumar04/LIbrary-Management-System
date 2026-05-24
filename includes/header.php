<?php
$pageTitle = $pageTitle ?? 'Library Management System';
$currentPage = basename($_SERVER['PHP_SELF']);

if (!function_exists('nav_active_class')) {
    function nav_active_class(string $currentPage, string $target): string
    {
        return $currentPage === $target ? 'active' : '';
    }
}

$navItems = [
    ['label' => 'Dashboard', 'icon' => 'bi-speedometer2', 'href' => 'index.php'],
    ['label' => 'Books', 'icon' => 'bi-book', 'href' => 'manage_books.php'],
    ['label' => 'Students', 'icon' => 'bi-people', 'href' => 'manage_students.php'],
    ['label' => 'Issue Book', 'icon' => 'bi-journal-plus', 'href' => 'issue_book.php'],
    ['label' => 'Return Book', 'icon' => 'bi-arrow-counterclockwise', 'href' => 'return_book.php'],
    ['label' => 'Reports', 'icon' => 'bi-graph-up', 'href' => 'reports.php'],
];

if (is_super_admin()) {
    $navItems[] = ['label' => 'Pending Requests', 'icon' => 'bi-person-exclamation', 'href' => 'pending_requests.php'];
    $navItems[] = ['label' => 'Manage Admins', 'icon' => 'bi-person-gear', 'href' => 'manage_admins.php'];
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo h($pageTitle); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-dark bg-dark sticky-top d-lg-none shadow-sm">
    <div class="container-fluid">
        <button class="btn btn-outline-light me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar" aria-controls="mobileSidebar">
            <i class="bi bi-list"></i>
        </button>
        <a class="navbar-brand fw-semibold mb-0" href="index.php">Library Admin</a>
        <a class="btn btn-outline-light btn-sm" href="logout.php"><i class="bi bi-box-arrow-right me-1"></i>Logout</a>
    </div>
</nav>

<div class="offcanvas offcanvas-start d-lg-none" tabindex="-1" id="mobileSidebar" aria-labelledby="mobileSidebarLabel">
    <div class="offcanvas-header bg-dark text-white">
        <h5 class="offcanvas-title" id="mobileSidebarLabel">Library Admin</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body p-0 bg-dark text-white">
        <div class="px-3 py-3 border-bottom border-secondary">
            <div class="fw-semibold"><?php echo h($_SESSION['admin_name'] ?? 'Admin'); ?></div>
            <div class="small text-white-50"><?php echo h(str_replace('_', ' ', $_SESSION['admin_role'] ?? 'Admin')); ?> Panel</div>
        </div>
        <div class="p-3">
            <div class="nav nav-pills flex-column gap-1">
                <?php foreach ($navItems as $item): ?>
                    <a class="nav-link <?php echo nav_active_class($currentPage, $item['href']); ?>" href="<?php echo h($item['href']); ?>">
                        <i class="bi <?php echo h($item['icon']); ?> me-2"></i><?php echo h($item['label']); ?>
                    </a>
                <?php endforeach; ?>
                <a class="nav-link" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a>
            </div>
        </div>
    </div>
</div>

<div class="d-flex min-vh-100">
    <aside class="sidebar bg-dark text-white d-none d-lg-flex flex-column p-3">
        <a href="index.php" class="d-flex align-items-center gap-2 text-white text-decoration-none mb-4">
            <span class="sidebar-brand-icon"><i class="bi bi-book-half"></i></span>
            <div>
                <div class="fw-bold lh-1">Library Admin</div>
                <div class="small text-white-50">Management System</div>
            </div>
        </a>
        <div class="mb-3 px-2">
            <div class="fw-semibold"><?php echo h($_SESSION['admin_name'] ?? 'Admin'); ?></div>
            <div class="small text-white-50"><?php echo h(str_replace('_', ' ', $_SESSION['admin_role'] ?? 'Admin')); ?></div>
        </div>
        <nav class="nav nav-pills flex-column gap-1">
            <?php foreach ($navItems as $item): ?>
                <a class="nav-link <?php echo nav_active_class($currentPage, $item['href']); ?>" href="<?php echo h($item['href']); ?>">
                    <i class="bi <?php echo h($item['icon']); ?> me-2"></i><?php echo h($item['label']); ?>
                </a>
            <?php endforeach; ?>
            <a class="nav-link" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a>
        </nav>
        <div class="mt-auto pt-3 small text-white-50">
            Responsive librarian dashboard
        </div>
    </aside>

    <div class="flex-grow-1">
        <main class="container-fluid py-3 py-lg-4">
            <?php flash_message(); ?>

<?php
require_once __DIR__ . '/../includes/auth.php';
require_admin();
$pageTitle = 'Dashboard';

$totalBooks = (int) fetch_scalar('SELECT COUNT(*) FROM books');
$totalStudents = (int) fetch_scalar('SELECT COUNT(*) FROM students');
$issuedBooks = (int) fetch_scalar("SELECT COUNT(*) FROM issued_books WHERE status = 'issued'");
$returnedBooks = (int) fetch_scalar("SELECT COUNT(*) FROM issued_books WHERE status = 'returned'");
$availableBooks = (int) fetch_scalar('SELECT COALESCE(SUM(available_quantity), 0) FROM books');

// Admin metrics for super admin
$totalAdmins = is_super_admin() ? (int) fetch_scalar("SELECT COUNT(*) FROM admins WHERE status = 'approved'") : 0;
$pendingAdmins = is_super_admin() ? (int) fetch_scalar("SELECT COUNT(*) FROM admins WHERE status = 'pending'") : 0;

require_once __DIR__ . '/../includes/header.php';
?>
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="page-title h3 mb-1">Dashboard</h1>
        <p class="text-muted mb-0">Quick overview of the library operations.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="add_book.php" class="btn btn-dark"><i class="bi bi-plus-circle me-1"></i>Add Book</a>
        <a href="issue_book.php" class="btn btn-outline-dark"><i class="bi bi-journal-plus me-1"></i>Issue Book</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-12 col-md-6 col-xl-4">
        <div class="card card-stat h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="icon-box"><i class="bi bi-book fs-4"></i></div>
                <div>
                    <div class="text-muted small">Total Books</div>
                    <div class="h3 mb-0"><?php echo number_format($totalBooks); ?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-xl-4">
        <div class="card card-stat h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="icon-box"><i class="bi bi-people fs-4"></i></div>
                <div>
                    <div class="text-muted small">Total Students</div>
                    <div class="h3 mb-0"><?php echo number_format($totalStudents); ?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-xl-4">
        <div class="card card-stat h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="icon-box"><i class="bi bi-journal-check fs-4"></i></div>
                <div>
                    <div class="text-muted small">Issued Books</div>
                    <div class="h3 mb-0"><?php echo number_format($issuedBooks); ?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-xl-4">
        <div class="card card-stat h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="icon-box"><i class="bi bi-arrow-counterclockwise fs-4"></i></div>
                <div>
                    <div class="text-muted small">Returned Books</div>
                    <div class="h3 mb-0"><?php echo number_format($returnedBooks); ?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-xl-4">
        <div class="card card-stat h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="icon-box"><i class="bi bi-box-seam fs-4"></i></div>
                <div>
                    <div class="text-muted small">Available Books</div>
                    <div class="h3 mb-0"><?php echo number_format($availableBooks); ?></div>
                </div>
            </div>
        </div>
    </div>
    <?php if (is_super_admin()): ?>
    <div class="col-12 col-md-6 col-xl-4">
        <div class="card card-stat h-100 border-success border-opacity-25">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="icon-box bg-success bg-opacity-10 text-success"><i class="bi bi-person-check fs-4"></i></div>
                <div>
                    <div class="text-muted small">Approved Admins</div>
                    <div class="h3 mb-0"><?php echo number_format($totalAdmins); ?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-xl-4">
        <div class="card card-stat h-100 border-warning border-opacity-25">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="icon-box bg-warning bg-opacity-10 text-warning"><i class="bi bi-person-exclamation fs-4"></i></div>
                <div>
                    <div class="text-muted small">Pending Requests</div>
                    <div class="h3 mb-0"><?php echo number_format($pendingAdmins); ?></div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<div class="row g-3 mt-1">
    <div class="col-12 col-lg-6">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body">
                <h2 class="h5 mb-3">Manage Library</h2>
                <div class="list-group list-group-flush">
                    <a href="manage_books.php" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-book me-2"></i>Books</span>
                        <i class="bi bi-chevron-right"></i>
                    </a>
                    <a href="manage_students.php" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-people me-2"></i>Students</span>
                        <i class="bi bi-chevron-right"></i>
                    </a>
                    <a href="issue_book.php" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-journal-plus me-2"></i>Issue Books</span>
                        <i class="bi bi-chevron-right"></i>
                    </a>
                    <a href="reports.php" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-graph-up me-2"></i>Reports</span>
                        <i class="bi bi-chevron-right"></i>
                    </a>
                    <?php if (is_super_admin()): ?>
                    <a href="pending_requests.php" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-person-exclamation me-2"></i>Pending Admin Requests</span>
                        <span class="badge bg-warning text-dark rounded-pill"><?php echo $pendingAdmins; ?></span>
                    </a>
                    <a href="manage_admins.php" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-person-gear me-2"></i>Manage Administrators</span>
                        <i class="bi bi-chevron-right"></i>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-6">
        <div class="card border-0 shadow-sm rounded-4 h-100 bg-dark text-white">
            <div class="card-body">
                <h2 class="h5">Admin Note</h2>
                <p class="mb-0 text-white-50">This system is built for librarians to manage books, students, issue records, returns, and overdue reports from one responsive dashboard.</p>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>

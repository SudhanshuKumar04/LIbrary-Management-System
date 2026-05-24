<?php
require_once __DIR__ . '/../includes/auth.php';
require_admin();
$pageTitle = 'Reports';

$issuedStmt = db()->prepare('SELECT issued_books.id, books.title AS book_title, students.name AS student_name, issued_books.issue_date, issued_books.return_date FROM issued_books INNER JOIN books ON books.id = issued_books.book_id INNER JOIN students ON students.id = issued_books.student_id WHERE issued_books.status = "issued" ORDER BY issued_books.id DESC');
$issuedStmt->execute();
$issuedList = $issuedStmt->get_result()->fetch_all(MYSQLI_ASSOC);
$issuedStmt->close();

$returnedStmt = db()->prepare('SELECT issued_books.id, books.title AS book_title, students.name AS student_name, issued_books.issue_date, issued_books.return_date FROM issued_books INNER JOIN books ON books.id = issued_books.book_id INNER JOIN students ON students.id = issued_books.student_id WHERE issued_books.status = "returned" ORDER BY issued_books.id DESC');
$returnedStmt->execute();
$returnedList = $returnedStmt->get_result()->fetch_all(MYSQLI_ASSOC);
$returnedStmt->close();

$overdueStmt = db()->prepare('SELECT issued_books.id, books.title AS book_title, students.name AS student_name, issued_books.issue_date, issued_books.return_date FROM issued_books INNER JOIN books ON books.id = issued_books.book_id INNER JOIN students ON students.id = issued_books.student_id WHERE issued_books.status = "issued" AND issued_books.return_date < CURDATE() ORDER BY issued_books.return_date ASC');
$overdueStmt->execute();
$overdueList = $overdueStmt->get_result()->fetch_all(MYSQLI_ASSOC);
$overdueStmt->close();

require_once __DIR__ . '/../includes/header.php';
?>
<div class="mb-4">
    <h1 class="page-title h3 mb-1">Reports</h1>
    <p class="text-muted mb-0">Track issued, returned, and overdue books.</p>
</div>

<div class="row g-3 mb-4">
    <div class="col-12 col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body">
                <div class="text-muted small">Issued Books</div>
                <div class="h2 mb-0"><?php echo count($issuedList); ?></div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body">
                <div class="text-muted small">Returned Books</div>
                <div class="h2 mb-0"><?php echo count($returnedList); ?></div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body">
                <div class="text-muted small">Overdue Books</div>
                <div class="h2 mb-0 text-danger"><?php echo count($overdueList); ?></div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-header bg-white border-0 pt-3">
        <h2 class="h5 mb-0">Issued Books</h2>
    </div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Book</th>
                    <th>Student</th>
                    <th>Issue Date</th>
                    <th>Return Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($issuedList)): ?>
                    <tr><td colspan="4" class="text-center py-4 text-muted">No issued books.</td></tr>
                <?php else: ?>
                    <?php foreach ($issuedList as $row): ?>
                        <tr>
                            <td><?php echo h($row['book_title']); ?></td>
                            <td><?php echo h($row['student_name']); ?></td>
                            <td><?php echo h($row['issue_date']); ?></td>
                            <td><?php echo h($row['return_date']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-header bg-white border-0 pt-3">
        <h2 class="h5 mb-0">Returned Books</h2>
    </div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Book</th>
                    <th>Student</th>
                    <th>Issue Date</th>
                    <th>Return Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($returnedList)): ?>
                    <tr><td colspan="4" class="text-center py-4 text-muted">No returned books.</td></tr>
                <?php else: ?>
                    <?php foreach ($returnedList as $row): ?>
                        <tr>
                            <td><?php echo h($row['book_title']); ?></td>
                            <td><?php echo h($row['student_name']); ?></td>
                            <td><?php echo h($row['issue_date']); ?></td>
                            <td><?php echo h($row['return_date']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white border-0 pt-3">
        <h2 class="h5 mb-0 text-danger">Overdue Books</h2>
    </div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Book</th>
                    <th>Student</th>
                    <th>Issue Date</th>
                    <th>Due Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($overdueList)): ?>
                    <tr><td colspan="4" class="text-center py-4 text-muted">No overdue books.</td></tr>
                <?php else: ?>
                    <?php foreach ($overdueList as $row): ?>
                        <tr>
                            <td><?php echo h($row['book_title']); ?></td>
                            <td><?php echo h($row['student_name']); ?></td>
                            <td><?php echo h($row['issue_date']); ?></td>
                            <td><span class="badge text-bg-danger"><?php echo h($row['return_date']); ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>

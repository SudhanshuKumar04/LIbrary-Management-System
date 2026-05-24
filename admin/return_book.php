<?php
require_once __DIR__ . '/../includes/auth.php';
require_admin();
$pageTitle = 'Return Book';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['return_issue_id'])) {
    $issueId = (int) $_POST['return_issue_id'];
    $connection = db();
    $connection->begin_transaction();

    try {
        $issueStmt = $connection->prepare('SELECT book_id, status FROM issued_books WHERE id = ? LIMIT 1');
        $issueStmt->bind_param('i', $issueId);
        $issueStmt->execute();
        $issue = $issueStmt->get_result()->fetch_assoc();
        $issueStmt->close();

        if (!$issue || $issue['status'] !== 'issued') {
            throw new RuntimeException('Issued record not found.');
        }

        $updateIssue = $connection->prepare('UPDATE issued_books SET status = "returned" WHERE id = ?');
        $updateIssue->bind_param('i', $issueId);
        $updateIssue->execute();
        $updateIssue->close();

        $updateBook = $connection->prepare('UPDATE books SET available_quantity = available_quantity + 1 WHERE id = ?');
        $updateBook->bind_param('i', $issue['book_id']);
        $updateBook->execute();
        $updateBook->close();

        $connection->commit();
        set_flash('success', 'Book marked as returned.');
        redirect('return_book.php');
    } catch (Throwable $exception) {
        $connection->rollback();
        set_flash('danger', 'Return failed. Please try again.');
    }
}

$stmt = db()->prepare('SELECT issued_books.id, issued_books.issue_date, issued_books.return_date, books.title AS book_title, students.name AS student_name, books.author, books.category FROM issued_books INNER JOIN books ON books.id = issued_books.book_id INNER JOIN students ON students.id = issued_books.student_id WHERE issued_books.status = "issued" ORDER BY issued_books.id DESC');
$stmt->execute();
$issuedBooks = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

require_once __DIR__ . '/../includes/header.php';
?>
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="page-title h3 mb-1">Return Book</h1>
        <p class="text-muted mb-0">Mark issued books as returned to restore stock.</p>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th>Book</th>
                    <th>Student</th>
                    <th>Issue Date</th>
                    <th>Return Date</th>
                    <th>Status</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($issuedBooks)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">No issued books found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($issuedBooks as $item): ?>
                        <tr>
                            <td class="fw-semibold"><?php echo h($item['book_title']); ?></td>
                            <td><?php echo h($item['student_name']); ?></td>
                            <td><?php echo h($item['issue_date']); ?></td>
                            <td><?php echo h($item['return_date']); ?></td>
                            <td><span class="badge text-bg-warning">Issued</span></td>
                            <td class="text-end">
                                <form method="post" onsubmit="return confirm('Mark this book as returned?');" class="d-inline">
                                    <input type="hidden" name="return_issue_id" value="<?php echo (int) $item['id']; ?>">
                                    <button type="submit" class="btn btn-sm btn-dark"><i class="bi bi-check2-circle me-1"></i>Return</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>

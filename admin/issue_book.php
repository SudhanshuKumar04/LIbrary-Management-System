<?php
require_once __DIR__ . '/../includes/auth.php';
require_admin();
$pageTitle = 'Issue Book';

$studentsStmt = db()->prepare('SELECT id, name, email, phone FROM students ORDER BY name ASC');
$studentsStmt->execute();
$students = $studentsStmt->get_result()->fetch_all(MYSQLI_ASSOC);
$studentsStmt->close();

$booksStmt = db()->prepare('SELECT id, title, author, available_quantity FROM books WHERE available_quantity > 0 ORDER BY title ASC');
$booksStmt->execute();
$books = $booksStmt->get_result()->fetch_all(MYSQLI_ASSOC);
$booksStmt->close();

$studentId = (int) ($_POST['student_id'] ?? 0);
$bookId = (int) ($_POST['book_id'] ?? 0);
$issueDate = date('Y-m-d');
$returnDate = date('Y-m-d', strtotime($issueDate . ' +7 days'));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $issueDate = date('Y-m-d');
    $returnDate = date('Y-m-d', strtotime($issueDate . ' +7 days'));

    if ($studentId <= 0 || $bookId <= 0) {
        set_flash('danger', 'Please select a student and a book.');
    } else {
        $bookCheck = db()->prepare('SELECT available_quantity FROM books WHERE id = ? LIMIT 1');
        $bookCheck->bind_param('i', $bookId);
        $bookCheck->execute();
        $bookRow = $bookCheck->get_result()->fetch_assoc();
        $bookCheck->close();

        if (!$bookRow || (int) $bookRow['available_quantity'] < 1) {
            set_flash('danger', 'This book is not available for issue.');
        } else {
            $connection = db();
            $connection->begin_transaction();

            try {
                $insert = $connection->prepare('INSERT INTO issued_books (book_id, student_id, issue_date, return_date, status) VALUES (?, ?, ?, ?, "issued")');
                $insert->bind_param('iiss', $bookId, $studentId, $issueDate, $returnDate);
                $insert->execute();
                $insert->close();

                $update = $connection->prepare('UPDATE books SET available_quantity = available_quantity - 1 WHERE id = ? AND available_quantity > 0');
                $update->bind_param('i', $bookId);
                $update->execute();

                if ($update->affected_rows < 1) {
                    $update->close();
                    throw new RuntimeException('Could not update available quantity.');
                }

                $update->close();
                $connection->commit();
                set_flash('success', 'Book issued successfully.');
                redirect('issue_book.php');
            } catch (Throwable $exception) {
                $connection->rollback();
                set_flash('danger', 'Issue failed. Please try again.');
            }
        }
    }
}

require_once __DIR__ . '/../includes/header.php';
?>
<div class="row justify-content-center">
    <div class="col-12 col-lg-8 col-xl-7">
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4 p-lg-5">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-4">
                    <div>
                        <h1 class="page-title h3 mb-1">Issue Book</h1>
                        <p class="text-muted mb-0">Select a student and an available book. The due date is set automatically.</p>
                    </div>
                    <a href="reports.php" class="btn btn-outline-dark"><i class="bi bi-graph-up me-1"></i>Reports</a>
                </div>

                <form method="post">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label">Student</label>
                            <select name="student_id" class="form-select" required>
                                <option value="">Select student</option>
                                <?php foreach ($students as $student): ?>
                                    <option value="<?php echo (int) $student['id']; ?>" <?php echo $studentId === (int) $student['id'] ? 'selected' : ''; ?>><?php echo h($student['name'] . ' | ' . $student['email'] . ' | ' . $student['phone']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Book</label>
                            <select name="book_id" class="form-select" required>
                                <option value="">Select book</option>
                                <?php foreach ($books as $book): ?>
                                    <option value="<?php echo (int) $book['id']; ?>" <?php echo $bookId === (int) $book['id'] ? 'selected' : ''; ?>><?php echo h($book['title'] . ' - ' . $book['author'] . ' (' . $book['available_quantity'] . ' left)'); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Issue Date</label>
                            <div class="border rounded-3 px-3 py-2 bg-light">
                                <div class="fw-semibold"><?php echo h($issueDate); ?></div>
                                <div class="form-text mb-0">Uses the current date.</div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Return Date</label>
                            <div class="border rounded-3 px-3 py-2 bg-light">
                                <div class="fw-semibold"><?php echo h($returnDate); ?></div>
                                <div class="form-text mb-0">Automatically set to 7 days after the issue date.</div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-dark"><i class="bi bi-journal-plus me-1"></i>Issue Book</button>
                        <a href="manage_books.php" class="btn btn-outline-secondary">Back to Books</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>

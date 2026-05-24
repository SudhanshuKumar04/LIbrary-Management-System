<?php
require_once __DIR__ . '/../includes/auth.php';
require_admin();
$pageTitle = 'Manage Books';

$search = trim($_GET['q'] ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_book_id'])) {
    $bookId = (int) $_POST['delete_book_id'];

    $stmt = db()->prepare('SELECT COUNT(*) FROM issued_books WHERE book_id = ?');
    $stmt->bind_param('i', $bookId);
    $stmt->execute();
    $issuedCount = (int) $stmt->get_result()->fetch_row()[0];
    $stmt->close();

    if ($issuedCount > 0) {
        set_flash('danger', 'This book cannot be deleted because it has issue history.');
    } else {
        $stmt = db()->prepare('DELETE FROM books WHERE id = ?');
        $stmt->bind_param('i', $bookId);
        $stmt->execute();
        $stmt->close();
        set_flash('success', 'Book deleted successfully.');
    }

    $redirectUrl = 'manage_books.php';
    if ($search !== '') {
        $redirectUrl .= '?q=' . urlencode($search);
    }
    redirect($redirectUrl);
}

if ($search !== '') {
    $like = '%' . $search . '%';
    $stmt = db()->prepare('SELECT * FROM books WHERE title LIKE ? OR author LIKE ? OR category LIKE ? OR isbn LIKE ? ORDER BY id DESC');
    $stmt->bind_param('ssss', $like, $like, $like, $like);
} else {
    $stmt = db()->prepare('SELECT * FROM books ORDER BY id DESC');
}

$stmt->execute();
$books = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

require_once __DIR__ . '/../includes/header.php';
?>
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="page-title h3 mb-1">Manage Books</h1>
        <p class="text-muted mb-0">Search, edit, update, and remove books.</p>
    </div>
    <a href="add_book.php" class="btn btn-dark"><i class="bi bi-plus-circle me-1"></i>Add Book</a>
</div>

<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body">
        <form class="row g-2 align-items-center" method="get">
            <div class="col-12 col-md-9">
                <input type="search" name="q" class="form-control" placeholder="Search by title, author, category, or ISBN" value="<?php echo h($search); ?>">
            </div>
            <div class="col-12 col-md-3 d-grid d-md-block">
                <button type="submit" class="btn btn-outline-dark w-100"><i class="bi bi-search me-1"></i>Search</button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Category</th>
                    <th>ISBN</th>
                    <th>Total</th>
                    <th>Available</th>
                    <th>Added</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($books)): ?>
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">No books found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($books as $book): ?>
                        <tr>
                            <td class="fw-semibold"><?php echo h($book['title']); ?></td>
                            <td><?php echo h($book['author']); ?></td>
                            <td><?php echo h($book['category']); ?></td>
                            <td><?php echo h($book['isbn']); ?></td>
                            <td><?php echo (int) $book['quantity']; ?></td>
                            <td><span class="badge text-bg-success"><?php echo (int) $book['available_quantity']; ?></span></td>
                            <td><?php echo h($book['added_date']); ?></td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="edit_book.php?id=<?php echo (int) $book['id']; ?>" class="btn btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                    <form method="post" onsubmit="return confirm('Delete this book?');" class="d-inline">
                                        <input type="hidden" name="delete_book_id" value="<?php echo (int) $book['id']; ?>">
                                        <button type="submit" class="btn btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>

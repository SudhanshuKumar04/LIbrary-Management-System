<?php
require_once __DIR__ . '/../includes/auth.php';
require_admin();
$pageTitle = 'Add Book';

$title = $author = $category = $isbn = '';
$quantity = 1;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $author = trim($_POST['author'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $isbn = trim($_POST['isbn'] ?? '');
    $quantity = max(0, (int) ($_POST['quantity'] ?? 0));

    if ($title === '' || $author === '' || $category === '' || $isbn === '' || $quantity < 1) {
        set_flash('danger', 'Please fill in all book details and use a valid quantity.');
    } else {
        $availableQuantity = $quantity;
        $stmt = db()->prepare('INSERT INTO books (title, author, category, isbn, quantity, available_quantity, added_date) VALUES (?, ?, ?, ?, ?, ?, NOW())');
        $stmt->bind_param('ssssii', $title, $author, $category, $isbn, $quantity, $availableQuantity);
        $stmt->execute();
        $stmt->close();

        set_flash('success', 'Book added successfully.');
        redirect('manage_books.php');
    }
}

require_once __DIR__ . '/../includes/header.php';
?>
<div class="row justify-content-center">
    <div class="col-12 col-lg-8 col-xl-7">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4 p-lg-5">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-4">
                    <div>
                        <h1 class="page-title h3 mb-1">Add Book</h1>
                        <p class="text-muted mb-0">Add a new book to the library catalog.</p>
                    </div>
                    <a href="manage_books.php" class="btn btn-outline-dark"><i class="bi bi-arrow-left me-1"></i>Back</a>
                </div>

                <form method="post">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Title</label>
                            <input type="text" name="title" class="form-control" value="<?php echo h($title); ?>" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Author</label>
                            <input type="text" name="author" class="form-control" value="<?php echo h($author); ?>" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Category</label>
                            <input type="text" name="category" class="form-control" value="<?php echo h($category); ?>" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">ISBN</label>
                            <input type="text" name="isbn" class="form-control" value="<?php echo h($isbn); ?>" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Quantity</label>
                            <input type="number" name="quantity" class="form-control" min="1" value="<?php echo h($quantity); ?>" required>
                        </div>
                    </div>
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-dark"><i class="bi bi-check2-circle me-1"></i>Save Book</button>
                        <a href="manage_books.php" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>

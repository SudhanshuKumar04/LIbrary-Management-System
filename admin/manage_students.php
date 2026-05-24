<?php
require_once __DIR__ . '/../includes/auth.php';
require_admin();
$pageTitle = 'Manage Students';

$search = trim($_GET['q'] ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_student_id'])) {
    $studentId = (int) $_POST['delete_student_id'];

    $stmt = db()->prepare('SELECT COUNT(*) FROM issued_books WHERE student_id = ?');
    $stmt->bind_param('i', $studentId);
    $stmt->execute();
    $issuedCount = (int) $stmt->get_result()->fetch_row()[0];
    $stmt->close();

    if ($issuedCount > 0) {
        set_flash('danger', 'This student cannot be deleted because there are issued books linked to the record.');
    } else {
        $stmt = db()->prepare('DELETE FROM students WHERE id = ?');
        $stmt->bind_param('i', $studentId);
        $stmt->execute();
        $stmt->close();
        set_flash('success', 'Student deleted successfully.');
    }

    $redirectUrl = 'manage_students.php';
    if ($search !== '') {
        $redirectUrl .= '?q=' . urlencode($search);
    }
    redirect($redirectUrl);
}

if ($search !== '') {
    $like = '%' . $search . '%';
    $stmt = db()->prepare('SELECT * FROM students WHERE name LIKE ? OR email LIKE ? OR phone LIKE ? ORDER BY id DESC');
    $stmt->bind_param('sss', $like, $like, $like);
} else {
    $stmt = db()->prepare('SELECT * FROM students ORDER BY id DESC');
}

$stmt->execute();
$students = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

require_once __DIR__ . '/../includes/header.php';
?>
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="page-title h3 mb-1">Manage Students</h1>
        <p class="text-muted mb-0">Search, edit, and maintain student records.</p>
    </div>
    <a href="add_student.php" class="btn btn-dark"><i class="bi bi-person-plus me-1"></i>Add Student</a>
</div>

<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body">
        <form class="row g-2 align-items-center" method="get">
            <div class="col-12 col-md-9">
                <input type="search" name="q" class="form-control" placeholder="Search by name, email, or phone" value="<?php echo h($search); ?>">
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
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Created</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($students)): ?>
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">No students found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($students as $student): ?>
                        <tr>
                            <td class="fw-semibold"><?php echo h($student['name']); ?></td>
                            <td><?php echo h($student['email']); ?></td>
                            <td><?php echo h($student['phone']); ?></td>
                            <td><?php echo h($student['created_at']); ?></td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="edit_student.php?id=<?php echo (int) $student['id']; ?>" class="btn btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                    <form method="post" onsubmit="return confirm('Delete this student?');" class="d-inline">
                                        <input type="hidden" name="delete_student_id" value="<?php echo (int) $student['id']; ?>">
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

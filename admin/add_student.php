<?php
require_once __DIR__ . '/../includes/auth.php';
require_admin();
$pageTitle = 'Add Student';

$name = $email = $phone = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');

    if ($name === '' || $email === '' || $phone === '') {
        set_flash('danger', 'Please fill in all student details.');
    } else {
        $stmt = db()->prepare('INSERT INTO students (name, email, phone, created_at) VALUES (?, ?, ?, NOW())');
        $stmt->bind_param('sss', $name, $email, $phone);
        $stmt->execute();
        $stmt->close();

        set_flash('success', 'Student added successfully.');
        redirect('manage_students.php');
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
                        <h1 class="page-title h3 mb-1">Add Student</h1>
                        <p class="text-muted mb-0">Register a new student in the library system.</p>
                    </div>
                    <a href="manage_students.php" class="btn btn-outline-dark"><i class="bi bi-arrow-left me-1"></i>Back</a>
                </div>

                <form method="post">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" value="<?php echo h($name); ?>" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="<?php echo h($email); ?>" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control" value="<?php echo h($phone); ?>" required>
                        </div>
                    </div>
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-dark"><i class="bi bi-check2-circle me-1"></i>Save Student</button>
                        <a href="manage_students.php" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>

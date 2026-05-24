<?php
require_once __DIR__ . '/../includes/auth.php';
require_admin();
require_super_admin();

$pageTitle = 'Manage Administrators';

$db = db();

// Handle deletion
if (isset($_GET['delete'])) {
    $delete_id = (int)$_GET['delete'];
    // Prevent self-deletion
    if ($delete_id === (int)$_SESSION['admin_id']) {
        set_flash('danger', 'You cannot delete your own account.');
    } else {
        $stmt = db()->prepare("DELETE FROM admins WHERE id = ?");
        $stmt->bind_param('i', $delete_id);
        if ($stmt->execute()) {
            set_flash('success', 'Administrator deleted successfully.');
        } else {
            set_flash('danger', 'Failed to delete administrator.');
        }
        $stmt->close();
    }
    redirect('manage_admins.php');
}

$admins = $db->query("SELECT id, name, email, role, status FROM admins WHERE status != 'pending' ORDER BY role DESC, name ASC")->fetch_all(MYSQLI_ASSOC);

require_once __DIR__ . '/../includes/header.php';
?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="page-title h3 mb-1">Administrators</h1>
        <p class="text-muted mb-0">Manage system users and their roles.</p>
    </div>
</div>

<?php flash_message(); ?>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4 py-3">Administrator Name</th>
                        <th class="py-3">Email Address</th>
                        <th class="py-3">Role</th>
                        <th class="py-3">Status</th>
                        <th class="px-4 py-3 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($admins as $admin): ?>
                        <tr>
                            <td class="px-4 py-3 fw-medium">
                                <?php echo h($admin['name']); ?>
                                <?php if ($admin['id'] == $_SESSION['admin_id']): ?>
                                    <span class="badge bg-primary-subtle text-primary ms-1 small">You</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3 text-muted"><?php echo h($admin['email']); ?></td>
                            <td class="py-3">
                                <span class="badge bg-light text-dark border">
                                    <?php echo h(str_replace('_', ' ', $admin['role'])); ?>
                                </span>
                            </td>
                            <td class="py-3">
                                <?php if ($admin['status'] === 'approved'): ?>
                                    <span class="text-success"><i class="bi bi-check-circle-fill me-1"></i>Approved</span>
                                <?php else: ?>
                                    <span class="text-danger"><i class="bi bi-x-circle-fill me-1"></i>Rejected</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 text-end">
                                <?php if ($admin['id'] != $_SESSION['admin_id']): ?>
                                    <a href="manage_admins.php?delete=<?php echo $admin['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this administrator? This action cannot be undone.')">
                                        <i class="bi bi-trash me-1"></i> Delete
                                    </a>
                                <?php else: ?>
                                    <button class="btn btn-sm btn-outline-secondary disabled">
                                        <i class="bi bi-trash me-1"></i> Delete
                                    </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4">
    <a href="index.php" class="text-decoration-none text-muted small">
        <i class="bi bi-arrow-left me-1"></i> Back to Dashboard
    </a>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

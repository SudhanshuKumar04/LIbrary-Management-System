<?php
require_once __DIR__ . '/../includes/auth.php';
require_admin();
require_super_admin();

$pageTitle = 'Pending Admin Requests';

$db = db();
$requests = $db->query("SELECT id, name, email, role FROM admins WHERE status = 'pending' ORDER BY id DESC")->fetch_all(MYSQLI_ASSOC);

require_once __DIR__ . '/../includes/header.php';
?>

<div class="mb-4">
    <h1 class="page-title h3 mb-1">Pending Requests</h1>
    <p class="text-muted mb-0">Review and approve new administrator accounts.</p>
</div>

<?php flash_message(); ?>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <?php if (empty($requests)): ?>
            <div class="p-5 text-center">
                <div class="mb-3 text-muted">
                    <i class="bi bi-person-check fs-1"></i>
                </div>
                <h3 class="h5">No Pending Requests</h3>
                <p class="text-muted">All administrator registration requests have been processed.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4 py-3">Administrator Name</th>
                            <th class="py-3">Email Address</th>
                            <th class="py-3">Requested Role</th>
                            <th class="px-4 py-3 text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($requests as $request): ?>
                            <tr>
                                <td class="px-4 py-3 fw-medium"><?php echo h($request['name']); ?></td>
                                <td class="py-3 text-muted"><?php echo h($request['email']); ?></td>
                                <td class="py-3">
                                    <span class="badge bg-light text-dark border"><?php echo h(str_replace('_', ' ', $request['role'])); ?></span>
                                </td>
                                <td class="px-4 py-3 text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="approve_admin.php?id=<?php echo $request['id']; ?>" class="btn btn-sm btn-success rounded-pill px-3" onclick="return confirm('Are you sure you want to approve this administrator?')">
                                            <i class="bi bi-check-circle me-1"></i> Approve
                                        </a>
                                        <a href="reject_admin.php?id=<?php echo $request['id']; ?>" class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="return confirm('Are you sure you want to reject this administrator?')">
                                            <i class="bi bi-x-circle me-1"></i> Reject
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="mt-4">
    <a href="index.php" class="text-decoration-none text-muted small">
        <i class="bi bi-arrow-left me-1"></i> Back to Dashboard
    </a>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

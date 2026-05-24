<?php
require_once __DIR__ . '/../includes/auth.php';
require_admin();
require_super_admin();

$id = $_GET['id'] ?? null;

if ($id) {
    $stmt = db()->prepare("UPDATE admins SET status = 'approved' WHERE id = ? AND status = 'pending'");
    $stmt->bind_param('i', $id);
    
    if ($stmt->execute() && $stmt->affected_rows > 0) {
        set_flash('success', 'Administrator has been approved successfully.');
    } else {
        set_flash('danger', 'Failed to approve administrator or request not found.');
    }
    $stmt->close();
}

redirect('pending_requests.php');

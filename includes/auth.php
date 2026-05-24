<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';

function require_admin(): void
{
    if (!is_logged_in()) {
        redirect('../admin/login.php');
    }
}

function authenticate_admin(string $email, string $password): array
{
    $stmt = db()->prepare('SELECT id, name, password, role, status FROM admins WHERE email = ? LIMIT 1');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $admin = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$admin) {
        return ['success' => false, 'message' => 'Invalid email or password.'];
    }

    if (!password_verify($password, $admin['password'])) {
        // Fallback for old sha256 passwords during migration (optional but helpful)
        if (!hash_equals($admin['password'], hash('sha256', $password))) {
            return ['success' => false, 'message' => 'Invalid email or password.'];
        }
        
        // If it was an old password, we could re-hash it here if we wanted to
    }

    if ($admin['status'] === 'pending') {
        return ['success' => false, 'message' => 'Your account is pending approval by a super admin.'];
    }

    if ($admin['status'] === 'rejected') {
        return ['success' => false, 'message' => 'Your account has been rejected. Contact support for assistance.'];
    }

    $_SESSION['admin_id'] = $admin['id'];
    $_SESSION['admin_name'] = $admin['name'];
    $_SESSION['admin_role'] = $admin['role'];
    
    return ['success' => true];
}

function logout_admin(): void
{
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }

    session_destroy();
}

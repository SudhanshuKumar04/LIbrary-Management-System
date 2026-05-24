<?php
require_once __DIR__ . '/../includes/auth.php';

if (is_logged_in()) {
    redirect('index.php');
}

$pageTitle = 'Register Admin';

$name = $email = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $captcha_question = generate_captcha();
} else {
    $captcha_question = '';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password2 = $_POST['password2'] ?? '';

    if ($name === '' || $email === '' || $password === '' || $password2 === '' || trim($_POST['captcha'] ?? '') === '') {
        $error = 'Please fill in all the required fields.';
    } elseif (!isset($_SESSION['captcha_answer']) || (int) ($_POST['captcha'] ?? -1) !== (int) $_SESSION['captcha_answer']) {
        $error = 'The CAPTCHA answer is incorrect. Please try again.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'That email address doesn\'t look right. Please check it.';
    } elseif (strlen($password) < 8) {
        $error = 'Your password needs to be at least 8 characters long.';
    } elseif (!preg_match('/[A-Z]/', $password)) {
        $error = 'Don\'t forget to include at least one uppercase letter in your password.';
    } elseif (!preg_match('/[0-9]/', $password)) {
        $error = 'Please add at least one number to your password.';
    } elseif (!preg_match('/[^A-Za-z0-9]/', $password)) {
        $error = 'Your password needs at least one special character (like @, #, or !).';
    } elseif ($password !== $password2) {
        $error = 'The two passwords you entered don\'t match.';
    } else {
        // Check if the email is already in the system
        $stmt = db()->prepare('SELECT id FROM admins WHERE email = ? LIMIT 1');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $exists = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($exists) {
            $error = 'Someone is already using that email address.';
        } else {
            // Check if this is the first admin being registered
            $adminCount = (int) fetch_scalar('SELECT COUNT(*) FROM admins');
            $role = ($adminCount === 0) ? 'super_admin' : 'admin';
            $status = ($adminCount === 0) ? 'approved' : 'pending';

            // All good, let's create the account
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $insert = db()->prepare('INSERT INTO admins (name, email, password, role, status) VALUES (?, ?, ?, ?, ?)');
            $insert->bind_param('sssss', $name, $email, $hash, $role, $status);
            if ($insert->execute()) {
                $insert->close();
                if ($status === 'approved') {
                    set_flash('success', 'Registration successful! As the first administrator, your account is automatically approved as Super Admin.');
                } else {
                    set_flash('success', 'Registration successful! Your account is now pending approval from a super admin.');
                }
                redirect('login.php');
            } else {
                $error = 'Something went wrong on our end. Please try again in a moment.';
            }
        }
    }
}
// regenerate captcha if there was an error or after POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $error !== '') {
    $captcha_question = generate_captcha();
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body class="auth-page">
    <div class="min-vh-100 d-flex align-items-center py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5">
                    <div class="auth-card overflow-hidden bg-white">
                        <div class="auth-header">
                            <div class="d-flex align-items-center gap-3 mb-4">
                                <div class="sidebar-brand-icon">
                                    <i class="bi bi-book-half"></i>
                                </div>
                                <div>
                                    <div class="fw-bold fs-5">Library Admin</div>
                                    <div class="small opacity-75">Join our management team</div>
                                </div>
                            </div>
                            <h1 class="h3 mb-0 fw-bold">Create Account</h1>
                        </div>
                        <div class="card-body p-4 p-md-5">
                            <?php if ($error !== ''): ?>
                                <div class="alert alert-danger border-0 shadow-sm rounded-3"><?php echo h($error); ?></div>
                            <?php endif; ?>

                            <?php if (!empty($_SESSION['flash'])): ?>
                                <?php flash_message(); ?>
                            <?php endif; ?>

                            <form method="post" novalidate class="needs-validation" autocomplete="off">
                                <div class="mb-4">
                                    <label class="form-label fw-medium">Full Name</label>
                                    <input type="text" name="name" class="form-control" placeholder="Enter your full name" value="<?php echo h($name); ?>" required autocomplete="off">
                                </div>
                                <div class="mb-4">
                                    <label class="form-label fw-medium">Email Address</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope"></i></span>
                                        <input type="email" name="email" class="form-control border-start-0" placeholder="name@example.com" value="<?php echo h($email); ?>" required autocomplete="off">
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label fw-medium">Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock"></i></span>
                                        <input type="password" name="password" class="form-control border-start-0 border-end-0" placeholder="Create a strong password" required 
                                               pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W]).{8,}" 
                                               title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters">
                                        <span class="input-group-text toggle-password border-start-0 bg-light" style="cursor: pointer;">
                                            <i class="bi bi-eye"></i>
                                        </span>
                                    </div>
                                    <div class="form-text mt-2">
                                        <i class="bi bi-info-circle me-1"></i> Use 8+ characters with uppercase, numbers & symbols.
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label fw-medium">Confirm Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock-fill"></i></span>
                                        <input type="password" name="password2" class="form-control border-start-0 border-end-0" placeholder="Repeat your password" required>
                                        <span class="input-group-text toggle-password border-start-0 bg-light" style="cursor: pointer;">
                                            <i class="bi bi-eye"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label fw-medium">Human Verification</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-shield-lock"></i></span>
                                        <input type="text" name="captcha" class="form-control border-start-0" placeholder="What is <?php echo h($captcha_question); ?>?" required>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-custom w-100 rounded-3 mt-2">Create Admin Account</button>
                            </form>
                            <div class="text-center mt-4">
                                <span class="text-muted small">Already have an account?</span> 
                                <a href="login.php" class="text-dark fw-bold small text-decoration-none ms-1">Login here</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        /**
         * Simple password visibility toggle. 
         * Shows password on click/hold and hides when released or cursor leaves.
         */
        document.querySelectorAll('.toggle-password').forEach(function(btn) {
            const passInput = btn.parentElement.querySelector('input');
            const eyeIcon = btn.querySelector('i');

            function show() {
                passInput.type = 'text';
                eyeIcon.classList.remove('bi-eye');
                eyeIcon.classList.add('bi-eye-slash');
            }

            function hide() {
                passInput.type = 'password';
                eyeIcon.classList.remove('bi-eye-slash');
                eyeIcon.classList.add('bi-eye');
            }

            // Mouse events for desktop
            btn.addEventListener('mousedown', function(e) {
                e.preventDefault();
                show();
            });
            btn.addEventListener('mouseup', hide);
            btn.addEventListener('mouseleave', hide);
            
            // Touch support for mobile users
            btn.addEventListener('touchstart', function(e) {
                e.preventDefault();
                show();
            });
            btn.addEventListener('touchend', function(e) {
                e.preventDefault();
                hide();
            });
        });
    </script>
</body>
</html>

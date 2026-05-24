<?php
require_once __DIR__ . '/../includes/auth.php';

if (is_logged_in()) {
    redirect('index.php');
}

if (!empty($_GET['logged_out'])) {
    set_flash('success', 'You have been logged out successfully.');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $captcha_question = generate_captcha();
} else {
    $captcha_question = '';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '' || trim($_POST['captcha'] ?? '') === '') {
        $error = 'Please fill in your email and password, and solve the CAPTCHA.';
    } elseif (!isset($_SESSION['captcha_answer']) || (int) ($_POST['captcha'] ?? -1) !== (int) $_SESSION['captcha_answer']) {
        $error = 'The CAPTCHA answer was incorrect. Let\'s try another one.';
    } else {
        $auth = authenticate_admin($email, $password);
        if ($auth['success']) {
            set_flash('success', 'Welcome back! You\'ve successfully logged in.');
            redirect('index.php');
        } else {
            $error = $auth['message'];
        }
    }
}

// regenerate captcha on error after POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $error !== '') {
    $captcha_question = generate_captcha();
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login</title>
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
                                    <div class="small opacity-75">Welcome back to the system</div>
                                </div>
                            </div>
                            <h1 class="h3 mb-0 fw-bold">Administrator Login</h1>
                        </div>
                        <div class="card-body p-4 p-md-5">
                            <?php if ($error !== ''): ?>
                                <div class="alert alert-danger border-0 shadow-sm rounded-3"><?php echo h($error); ?></div>
                            <?php endif; ?>

                            <?php if (!empty($_SESSION['flash'])): ?>
                                <?php flash_message(); ?>
                            <?php endif; ?>

                            <form method="post" novalidate autocomplete="off">
                                <div class="mb-4">
                                    <label class="form-label fw-medium">Email Address</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope"></i></span>
                                        <input type="email" name="email" class="form-control border-start-0" placeholder="Enter your email" value="<?php echo h($_POST['email'] ?? ''); ?>" required autocomplete="off">
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label fw-medium">Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock"></i></span>
                                        <input type="password" name="password" class="form-control border-start-0 border-end-0" placeholder="Enter your password" required>
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
                                <button type="submit" class="btn btn-custom w-100 rounded-3 mt-2">Sign In to Dashboard</button>
                            </form>
                            <div class="text-center mt-4">
                                <span class="text-muted small">Don't have an account?</span> 
                                <a href="register.php" class="text-dark fw-bold small text-decoration-none ms-1">Create one here</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        /**
         * Auto-hide flash messages after 3 seconds
         */
        setTimeout(function() {
            let flashAlert = document.querySelector('.alert');
            if(flashAlert) {
                flashAlert.style.display = 'none';
            }
        }, 3000);

        /**
         * Password visibility toggle script
         */
        document.querySelectorAll('.toggle-password').forEach(function(btn) {
            const passField = btn.parentElement.querySelector('input');
            const icon = btn.querySelector('i');

            function show() {
                passField.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            }

            function hide() {
                passField.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }

            // Desktop click-hold behavior
            btn.addEventListener('mousedown', function(e) {
                e.preventDefault();
                show();
            });
            btn.addEventListener('mouseup', hide);
            btn.addEventListener('mouseleave', hide);
            
            // Mobile touch behavior
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

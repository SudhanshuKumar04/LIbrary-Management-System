<?php
require_once __DIR__ . '/../includes/auth.php';

logout_admin();
redirect('login.php?logged_out=1');

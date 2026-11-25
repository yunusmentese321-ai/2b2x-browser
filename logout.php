<?php
declare(strict_types=1);

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/storage.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/csrf.php';
require_once __DIR__ . '/includes/functions.php';
start_session();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf();
}

logout_user();
header('Location: ' . app_url('index.php'));
exit;

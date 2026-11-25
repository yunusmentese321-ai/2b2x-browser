<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/header.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) {
        $error = 'Your security token is invalid.';
    } else {
        $email = normalize_email((string) ($_POST['email'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');
        $user = user_record($email);
        if (!$user || !password_verify($password, (string) ($user['password_hash'] ?? '')) || ($user['role'] ?? '') !== 'admin') {
            $error = 'Invalid administrator credentials.';
        } elseif (($user['active'] ?? true) !== true) {
            $error = 'This administrator account is inactive.';
        } else {
            login_user($user);
            if (is_ajax_request()) {
                safe_json(['message' => 'Signed in to control center.', 'redirect' => app_url('admin/index.php')]);
            }
            header('Location: ' . app_url('admin/index.php'));
            exit;
        }
    }
    if ($error !== '' && is_ajax_request()) {
        safe_json(['message' => $error], 422);
    }
}
?><main class="admin-login"><section class="admin-login-card"><div class="eyebrow">Administration</div><h1>Operator access.</h1><p>Only accounts with the administrator role can access the control center.</p><form class="auth-form" action="<?= e(app_url('admin/login.php')) ?>" method=post data-validate><input type=hidden name=csrf_token value="<?= e(csrf_token()) ?>"><div class="field"><label for=email>Admin email</label><input id=email name=email type=email autocomplete=username required></div><div class="field"><label for=password>Password</label><div class="password-wrap"><input id=password name=password type=password autocomplete=current-password required><button class="password-toggle" type=button data-password-toggle>Show</button></div></div><?php if($error):?><div class="alert error"><?= e($error) ?></div><?php endif;?><button class="button button-primary" type=submit>Sign in to dashboard <span class=arrow>↗</span></button></form></section></main><?php require_once __DIR__.'/../includes/footer.php'; ?>

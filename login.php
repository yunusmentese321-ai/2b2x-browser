<?php
declare(strict_types=1);

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/storage.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/csrf.php';
require_once __DIR__ . '/includes/functions.php';
start_session();

if (current_user() !== null) {
    header('Location: ' . app_url('account.php'));
    exit;
}

$errors = [];
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) {
        $errors[] = 'Your security token is invalid. Please refresh and try again.';
    } else {
        $email = normalize_email((string) ($_POST['email'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');
        $user = user_record($email);
        if (!$user || !password_verify($password, (string) ($user['password_hash'] ?? ''))) {
            $errors[] = 'Email or password is incorrect.';
        } elseif (($user['active'] ?? true) !== true) {
            $errors[] = 'This account is inactive.';
        } else {
            login_user($user);
            if (is_ajax_request()) {
                safe_json(['message' => 'Signed in successfully.', 'redirect' => app_url('account.php')]);
            }
            header('Location: ' . app_url('account.php'));
            exit;
        }
    }

    if ($errors && is_ajax_request()) {
        safe_json(['message' => implode(' ', array_slice($errors, 0, 2))], 422);
    }
}

require_once __DIR__ . '/includes/header.php';
?>
<main class="auth-page">
  <section class="auth-shell">
    <div class="eyebrow">Secure Access</div>
    <h1>Welcome back.</h1>
    <p>Sign in to view your release history and manage your 2B2X account.</p>

    <form class="auth-form" action="<?= e(app_url('login.php')) ?>" method="post" data-validate>
      <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">

      <div class="field">
        <label for="email">Email address</label>
        <input id="email" name="email" type="email" autocomplete="email" required value="<?= e($email) ?>">
        <span class="field-error"></span>
      </div>

      <div class="field">
        <label for="password">Password</label>
        <div class="password-wrap">
          <input id="password" name="password" type="password" autocomplete="current-password" required>
          <button class="password-toggle" type="button" data-password-toggle>Show</button>
        </div>
        <span class="field-error"></span>
      </div>

      <div data-form-status>
        <?php if ($errors): ?>
          <div class="alert error"><?= e(implode(' ', array_slice($errors, 0, 2))) ?></div>
        <?php endif; ?>
      </div>

      <button class="button button-primary" type="submit" data-ripple>
        <span>Sign in</span>
        <span class="arrow" aria-hidden="true">↗</span>
      </button>
    </form>

    <div class="auth-link">
      New to 2B2X? <a href="<?= e(app_url('register.php')) ?>">Create an account</a>
    </div>
  </section>
</main>
<?php require_once __DIR__ . '/includes/footer.php'; ?>

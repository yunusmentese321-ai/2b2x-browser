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
$username = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) {
        $errors[] = 'Your security token is invalid. Please refresh and try again.';
    } else {
        $username = trim((string) ($_POST['username'] ?? ''));
        $email = normalize_email((string) ($_POST['email'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');
        $confirmation = (string) ($_POST['password_confirmation'] ?? '');

        if (!validate_username($username)) {
            $errors[] = 'Username must be 3–24 characters using letters, numbers, dots, dashes, or underscores.';
        }
        if (!validate_email($email)) {
            $errors[] = 'Enter a valid email address.';
        }
        if (!validate_password($password)) {
            $errors[] = 'Password must be at least 8 characters and include uppercase, lowercase, and a number.';
        }
        if ($password !== $confirmation) {
            $errors[] = 'Passwords do not match.';
        }

        $users = load_json('users.json', []);
        if ($users && array_filter($users, fn ($u) => is_array($u) && strtolower((string) ($u['email'] ?? '')) === $email)) {
            $errors[] = 'An account with this email already exists.';
        }

        if (!$errors) {
            $user = [
                'id' => create_user_id(),
                'username' => $username,
                'email' => $email,
                'password_hash' => hash_password($password),
                'role' => 'user',
                'active' => true,
                'created_at' => date('c'),
            ];
            $users[] = $user;
            if (!save_json('users.json', $users)) {
                $errors[] = 'We could not save your account. Please try again.';
            } else {
                login_user($user);
                if (is_ajax_request()) {
                    safe_json(['message' => 'Account created. Welcome to 2B2X!', 'redirect' => app_url('account.php')]);
                }
                header('Location: ' . app_url('account.php'));
                exit;
            }
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
    <div class="eyebrow">Create Account</div>
    <h1>Join 2B2X.</h1>
    <p>Keep your installer download history organized in one private local account.</p>

    <form class="auth-form" action="<?= e(app_url('register.php')) ?>" method="post" data-validate>
      <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">

      <div class="field">
        <label for="username">Username</label>
        <input id="username" name="username" autocomplete="username" minlength="3" maxlength="24" required value="<?= e($username) ?>">
        <span class="field-error"></span>
      </div>

      <div class="field">
        <label for="email">Email address</label>
        <input id="email" name="email" type="email" autocomplete="email" required value="<?= e($email) ?>">
        <span class="field-error"></span>
      </div>

      <div class="field">
        <label for="password">Password</label>
        <div class="password-wrap">
          <input id="password" name="password" type="password" autocomplete="new-password" minlength="8" required>
          <button class="password-toggle" type="button" data-password-toggle>Show</button>
        </div>
        <span class="field-error"></span>
      </div>

      <div class="field">
        <label for="password_confirmation">Confirm password</label>
        <div class="password-wrap">
          <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" minlength="8" required>
          <button class="password-toggle" type="button" data-password-toggle>Show</button>
        </div>
        <span class="field-error"></span>
      </div>

      <div data-form-status>
        <?php if ($errors): ?>
          <div class="alert error"><?= e(implode(' ', array_slice($errors, 0, 3))) ?></div>
        <?php endif; ?>
      </div>

      <button class="button button-primary" type="submit" data-ripple>
        <span>Create account</span>
        <span class="arrow" aria-hidden="true">↗</span>
      </button>
    </form>

    <div class="auth-link">
      Already have an account? <a href="<?= e(app_url('login.php')) ?>">Sign in</a>
    </div>
  </section>
</main>
<?php require_once __DIR__ . '/includes/footer.php'; ?>

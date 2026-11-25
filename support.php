<?php
declare(strict_types=1);

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/storage.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/csrf.php';
require_once __DIR__ . '/includes/functions.php';
start_session();

$settings = get_settings();
$contactEmail = (string) ($settings['contact_email'] ?? 'hello@2b2x.local');
$errors = [];
$success = false;
$name = '';
$email = '';
$category = '';
$subject = '';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) {
        $errors[] = 'Your security token is invalid. Please refresh and try again.';
    } else {
        $name = trim((string) ($_POST['name'] ?? ''));
        $email = normalize_email((string) ($_POST['email'] ?? ''));
        $category = (string) ($_POST['category'] ?? '');
        $subject = trim((string) ($_POST['subject'] ?? ''));
        $message = trim((string) ($_POST['message'] ?? ''));

        if (strlen($name) < 2) $errors[] = 'Enter your full name.';
        if (!validate_email($email)) $errors[] = 'Enter a valid email address.';
        if (!in_array($category, ['Installation', 'Browser Issue', 'Performance', 'Account', 'Download', 'Other'], true)) {
            $errors[] = 'Select a valid category.';
        }
        if (strlen($subject) < 5) $errors[] = 'Add a clear subject (at least 5 characters).';
        if (strlen($message) < 20) $errors[] = 'Tell us a little more about the issue (at least 20 characters).';

        if (!$errors) {
            $support = load_json('support.json', []);
            $now = date('c');
            $support[] = [
                'id' => bin2hex(random_bytes(16)),
                'name' => $name,
                'email' => $email,
                'category' => $category,
                'subject' => $subject,
                'message' => $message,
                'status' => 'open',
                'created_at' => $now,
                'updated_at' => $now,
            ];
            if (save_json('support.json', $support)) {
                $success = true;
                $name = $email = $category = $subject = $message = '';
            } else {
                $errors[] = 'We could not save your request. Please try again.';
            }
        }
    }

    if (is_ajax_request()) {
        if ($success) {
            safe_json(['message' => 'Your support request was received. Our team will review it shortly.']);
        }
        safe_json(['message' => implode(' ', array_slice($errors, 0, 2))], 422);
    }
}

require_once __DIR__ . '/includes/header.php';
?>
<main>
  <section class="page-hero">
    <div class="container">
      <div class="breadcrumb">
        <a href="<?= e(app_url('index.php')) ?>">Home</a>
        <span>/</span>
        <span>Support</span>
      </div>
      <div class="eyebrow">Help Center</div>
      <h1>We are here to help.</h1>
      <p>Get direct support for Windows installation, browser behavior, account questions, and release verification.</p>
    </div>
  </section>

  <section class="section" style="padding-top:20px;">
    <div class="container contact-layout">
      <aside class="contact-aside reveal" data-reveal>
        <div class="section-kicker">Direct Channels</div>
        <h3>Support that stays clear.</h3>
        <p>Every request is recorded cleanly and tracked through an open/closed resolution workflow by the 2B2X team.</p>
        <div class="contact-meta-list">
          <a href="mailto:<?= e($contactEmail) ?>"><?= e($contactEmail) ?></a>
          <span>Response window: business days</span>
          <span>Platform scope: <?= e((string) ($settings['supported_os'] ?? 'Windows 10 / 11')) ?></span>
        </div>
      </aside>

      <div class="form-card reveal reveal-delay-1" data-reveal>
        <div class="section-kicker">Submit Ticket</div>
        <h2 style="margin:0 0 22px;font-size:32px;letter-spacing:-.04em;">How can we help?</h2>

        <form action="<?= e(app_url('support.php')) ?>" method="post" data-validate>
          <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
          <div class="form-grid">
            <div class="field">
              <label for="name">Name</label>
              <input id="name" name="name" required autocomplete="name" value="<?= e($name) ?>">
              <span class="field-error"></span>
            </div>
            <div class="field">
              <label for="email">Email</label>
              <input id="email" name="email" type="email" required autocomplete="email" value="<?= e($email) ?>">
              <span class="field-error"></span>
            </div>
            <div class="field">
              <label for="category">Category</label>
              <select id="category" name="category" required>
                <option value="">Select category</option>
                <?php foreach (['Installation', 'Browser Issue', 'Performance', 'Account', 'Download', 'Other'] as $opt): ?>
                  <option <?= $category === $opt ? 'selected' : '' ?>><?= e($opt) ?></option>
                <?php endforeach; ?>
              </select>
              <span class="field-error"></span>
            </div>
            <div class="field">
              <label for="subject">Subject</label>
              <input id="subject" name="subject" required value="<?= e($subject) ?>">
              <span class="field-error"></span>
            </div>
            <div class="field full">
              <label for="message">Message</label>
              <textarea id="message" name="message" required placeholder="Describe what happened and your Windows version..."><?= e($message) ?></textarea>
              <span class="field-error"></span>
            </div>
          </div>

          <div data-form-status>
            <?php if ($success): ?>
              <div class="alert success" style="margin-top:16px;">Your request was received. A support specialist will review it.</div>
            <?php elseif ($errors): ?>
              <div class="alert error" style="margin-top:16px;"><?= e(implode(' ', array_slice($errors, 0, 3))) ?></div>
            <?php endif; ?>
          </div>

          <div class="form-submit-row">
            <small>By sending this form you agree to our <a href="<?= e(app_url('privacy.php')) ?>" style="color:var(--accent-2);">privacy policy</a>.</small>
            <button class="button button-primary" type="submit" data-ripple>
              <span>Send request</span>
              <span class="arrow" aria-hidden="true">↗</span>
            </button>
          </div>
        </form>
      </div>
    </div>

    <div class="container support-category" style="padding-top:64px;">
      <div class="support-detail reveal" data-reveal>
        <span>01 / SETUP</span>
        <h3>Installation</h3>
        <p>Guidance for downloading, verifying SHA-256 digests, and installing the Windows 64-bit release.</p>
      </div>
      <div class="support-detail reveal reveal-delay-1" data-reveal>
        <span>02 / EXPERIENCE</span>
        <h3>Browser Issues</h3>
        <p>Report navigation, tab behavior, rendering, or interface problems directly to the studio.</p>
      </div>
      <div class="support-detail reveal reveal-delay-2" data-reveal>
        <span>03 / ACCOUNT</span>
        <h3>Account Support</h3>
        <p>Questions about sign-in, personal download history, or local data deletion requests.</p>
      </div>
    </div>
  </section>
</main>
<?php require_once __DIR__ . '/includes/footer.php'; ?>

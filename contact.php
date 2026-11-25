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
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) {
        $errors[] = 'Your security token is invalid. Please refresh and try again.';
    } else {
        $name = trim((string) ($_POST['name'] ?? ''));
        $email = normalize_email((string) ($_POST['email'] ?? ''));
        $category = (string) ($_POST['category'] ?? '');
        $message = trim((string) ($_POST['message'] ?? ''));

        if (strlen($name) < 2) $errors[] = 'Enter your full name.';
        if (!validate_email($email)) $errors[] = 'Enter a valid email address.';
        if (!in_array($category, ['Business', 'Partnership', 'General Inquiry'], true)) {
            $errors[] = 'Select a valid inquiry type.';
        }
        if (strlen($message) < 20) $errors[] = 'Please provide a bit more detail (at least 20 characters).';

        if (!$errors) {
            $entries = load_json('contact.json', []);
            $entries[] = [
                'id' => bin2hex(random_bytes(16)),
                'name' => $name,
                'email' => $email,
                'category' => $category,
                'message' => $message,
                'created_at' => date('c'),
            ];
            if (save_json('contact.json', $entries)) {
                $success = true;
                $name = $email = $category = $message = '';
            } else {
                $errors[] = 'Your message could not be saved.';
            }
        }
    }

    if (is_ajax_request()) {
        if ($success) {
            safe_json(['message' => 'Your message was received. Thank you for reaching out.']);
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
        <span>Contact</span>
      </div>
      <div class="eyebrow">Connect with 2B2X</div>
      <h1>Start a conversation.</h1>
      <p>For partnerships, press, business inquiries, or general feedback, reach the 2B2X team directly.</p>
    </div>
  </section>

  <section class="section" style="padding-top:20px;">
    <div class="container contact-layout">
      <aside class="contact-aside reveal" data-reveal>
        <div class="section-kicker">Studio Details</div>
        <h3>Built by people who care about the craft.</h3>
        <p>2B2X is an independent Windows browser product focused on thoughtful software and an uncluttered operating experience.</p>
        <div class="contact-meta-list">
          <a href="mailto:<?= e($contactEmail) ?>"><?= e($contactEmail) ?></a>
          <span>Business hours: Monday–Friday</span>
          <span>Windows-first product</span>
        </div>
      </aside>

      <div class="form-card reveal reveal-delay-1" data-reveal>
        <div class="section-kicker">Contact Form</div>
        <h2 style="margin:0 0 22px;font-size:32px;letter-spacing:-.04em;">Tell us what you need.</h2>

        <form action="<?= e(app_url('contact.php')) ?>" method="post" data-validate>
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
            <div class="field full">
              <label for="category">Inquiry type</label>
              <select id="category" name="category" required>
                <option value="">Choose a category</option>
                <?php foreach (['Business', 'Partnership', 'General Inquiry'] as $opt): ?>
                  <option <?= $category === $opt ? 'selected' : '' ?>><?= e($opt) ?></option>
                <?php endforeach; ?>
              </select>
              <span class="field-error"></span>
            </div>
            <div class="field full">
              <label for="message">Message</label>
              <textarea id="message" name="message" required placeholder="Share your inquiry with the 2B2X team..."><?= e($message) ?></textarea>
              <span class="field-error"></span>
            </div>
          </div>

          <div data-form-status>
            <?php if ($success): ?>
              <div class="alert success" style="margin-top:16px;">Your message was received. Thank you for reaching out.</div>
            <?php elseif ($errors): ?>
              <div class="alert error" style="margin-top:16px;"><?= e(implode(' ', array_slice($errors, 0, 3))) ?></div>
            <?php endif; ?>
          </div>

          <div class="form-submit-row">
            <small>We use your details only to respond to this inquiry.</small>
            <button class="button button-primary" type="submit" data-ripple>
              <span>Send message</span>
              <span class="arrow" aria-hidden="true">↗</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </section>
</main>
<?php require_once __DIR__ . '/includes/footer.php'; ?>

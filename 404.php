<?php
declare(strict_types=1);
http_response_code(404);
require_once __DIR__ . '/includes/header.php';
?>
<main class="auth-page" style="text-align:center;">
  <div style="max-width:520px;">
    <span class="status-pill">404</span>
    <h1 style="font-size:clamp(3.2rem,8vw,6rem);line-height:.95;letter-spacing:-.05em;margin:24px 0 16px;">
      Lost in the <span style="color:var(--subtle);">web.</span>
    </h1>
    <p style="color:var(--muted);margin:0 auto 32px;max-width:420px;">
      The page you requested does not exist or may have moved to a new location.
    </p>
    <a class="button button-primary" data-ripple href="<?= e(app_url('index.php')) ?>">
      <span>Return home</span>
      <span class="arrow" aria-hidden="true">↗</span>
    </a>
  </div>
</main>
<?php require_once __DIR__ . '/includes/footer.php'; ?>

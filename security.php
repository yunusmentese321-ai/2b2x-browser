<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/header.php';
?>
<main>
  <section class="page-hero">
    <div class="container">
      <div class="breadcrumb">
        <a href="<?= e(app_url('index.php')) ?>">Home</a>
        <span>/</span>
        <span>Security</span>
      </div>
      <div class="eyebrow">Security Baseline</div>
      <h1>Security by design.</h1>
      <p>Every request is backed by server-side validation, secure session handling, CSRF verification, and SHA-256 release checks.</p>
    </div>
  </section>

  <section class="section" style="padding-top:10px;">
    <div class="container article">
      <div class="article-body" style="margin-top:0;">
        <p>Security controls are enforced across the entire application lifecycle. Public input is validated server-side, HTML output is escaped, and state-changing requests require a verified CSRF token.</p>
        <h2>Passwords &amp; authentication</h2>
        <p>Password hashes are generated through PHP’s <code>password_hash</code> API with bcrypt cost 12. Plain-text passwords are never written to storage.</p>
        <h2>Session integrity</h2>
        <p>Sessions regenerate IDs on authentication and rely on HTTP-only, SameSite=Lax cookies.</p>
        <h2>Installer integrity</h2>
        <p>Every release file is verified against its stored SHA-256 checksum prior to download, and the full digest is published openly on the <a href="<?= e(app_url('download.php')) ?>" style="color:var(--accent-2);">Downloads page</a>.</p>
        <h2>Production checklist</h2>
        <p>Configure a strong administrator credential, enable HTTPS, restrict writable storage permissions, and keep uploaded executables protected.</p>
      </div>
    </div>
  </section>
</main>
<?php require_once __DIR__ . '/includes/footer.php'; ?>

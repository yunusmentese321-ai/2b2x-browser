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
        <span>Privacy Policy</span>
      </div>
      <div class="eyebrow">Trust &amp; Legal</div>
      <h1>Privacy policy.</h1>
      <p>Clear information about local account data, password protection, and download activity.</p>
    </div>
  </section>

  <section class="section" style="padding-top:10px;">
    <div class="container article">
      <div class="article-body" style="margin-top:0;">
        <p>2B2X Browser stores account, download, support, and contact records on its local server. These records are used solely to provide account features, serve verified installers, and respond to support requests.</p>
        <h2>Local storage</h2>
        <p>JSON records are stored in the project’s data directory. Account passwords are hashed using PHP’s password hashing API and are never stored in plain text.</p>
        <h2>Download records</h2>
        <p>Download records include the release identifier, operating system, architecture, timestamp, and a one-way SHA-256 IP hash. An IP hash is not a reversible identifier and is used only for aggregate activity context.</p>
        <h2>Data control</h2>
        <p>Signed-in users can view their personal download history from their account dashboard and may request account deletion through the <a href="<?= e(app_url('support.php')) ?>" style="color:var(--accent-2);">support channel</a>.</p>
        <h2>Security</h2>
        <p>Sessions use HTTP-only cookies and CSRF token verification. Production deployments should always serve traffic over HTTPS with a trusted certificate.</p>
      </div>
    </div>
  </section>
</main>
<?php require_once __DIR__ . '/includes/footer.php'; ?>

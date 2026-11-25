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
        <span>Terms of Service</span>
      </div>
      <div class="eyebrow">Legal</div>
      <h1>Terms of service.</h1>
      <p>Simple, transparent terms for using the 2B2X Browser website, installer downloads, and local account service.</p>
    </div>
  </section>

  <section class="section" style="padding-top:10px;">
    <div class="container article">
      <div class="article-body" style="margin-top:0;">
        <p>By using the 2B2X Browser website and downloading its Windows release packages, you agree to use the software responsibly and only for lawful purposes.</p>
        <h2>Product distribution</h2>
        <p>2B2X Browser releases are published for Windows 10 and Windows 11 (64-bit) with SHA-256 verification metadata provided on the Downloads page.</p>
        <h2>Service records</h2>
        <p>Public contact, support, and download requests create local operational records managed by the site administrator in accordance with the <a href="<?= e(app_url('privacy.php')) ?>" style="color:var(--accent-2);">Privacy Policy</a>.</p>
        <h2>Availability</h2>
        <p>Releases and online services are provided as-is and may be updated over time as new versions ship in the changelog.</p>
      </div>
    </div>
  </section>
</main>
<?php require_once __DIR__ . '/includes/footer.php'; ?>

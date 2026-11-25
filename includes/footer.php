<?php
$scriptFilename = str_replace('\\', '/', $_SERVER['SCRIPT_FILENAME'] ?? '');
$isAdminFooter = str_contains($scriptFilename, '/admin/');
$footerRelease = current_release();
$footerVersion = (string) ($footerRelease['version'] ?? '1.0.0');
?>
<?php if (!$isAdminFooter): ?>
<footer class="site-footer">
  <div class="container">
    <div class="footer-top">
      <div class="footer-brand">
        <a class="brand" href="<?= e(app_url('index.php')) ?>" aria-label="2B2X Browser home">
          <span class="brand-mark" aria-hidden="true"><i></i><i></i></span>
          <span class="brand-wordmark">2B2X<small>Browser</small></span>
        </a>
        <p>A modern Windows browser shaped around performance, clarity, and a calmer way to explore the web.</p>
        <div class="footer-release-badge">
          <span class="status-dot" aria-hidden="true"></span>
          <span>Release <?= e($footerVersion) ?> · Windows 10 / 11 (64-bit)</span>
        </div>
      </div>

      <div class="footer-col">
        <h3>Product</h3>
        <a href="<?= e(app_url('index.php#experience')) ?>">Browser Experience</a>
        <a href="<?= e(app_url('index.php#features')) ?>">Architecture</a>
        <a href="<?= e(app_url('download.php')) ?>">Download</a>
        <a href="<?= e(app_url('changelog.php')) ?>">Changelog</a>
      </div>

      <div class="footer-col">
        <h3>Resources</h3>
        <a href="<?= e(app_url('blog.php')) ?>">2B2X Journal</a>
        <a href="<?= e(app_url('support.php')) ?>">Support Center</a>
        <a href="<?= e(app_url('contact.php')) ?>">Contact</a>
        <a href="<?= e(app_url('account.php')) ?>">Account</a>
      </div>

      <div class="footer-col">
        <h3>Trust &amp; Legal</h3>
        <a href="<?= e(app_url('privacy.php')) ?>">Privacy Policy</a>
        <a href="<?= e(app_url('security.php')) ?>">Security Baseline</a>
        <a href="<?= e(app_url('terms.php')) ?>">Terms of Service</a>
      </div>
    </div>

    <div class="footer-bottom">
      <span>© <?= date('Y') ?> 2B2X Browser. All rights reserved.</span>
      <span class="footer-meta">Windows 10 / 11 · 64-bit · SHA-256 Verified Releases</span>
    </div>
  </div>
</footer>
<?php endif; ?>
<div class="toast-stack" data-toast aria-live="polite" aria-atomic="true"></div>
</body>
</html>

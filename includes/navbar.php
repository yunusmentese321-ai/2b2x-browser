<?php
$current_user = current_user();
$active = basename($_SERVER['SCRIPT_FILENAME'] ?? '');
$latest_nav_release = current_release();
$nav_version = (string) ($latest_nav_release['version'] ?? '1.0.0');
?>
<header class="site-header" data-header>
  <nav class="navbar container" aria-label="Primary navigation">
    <a class="brand" href="<?= e(app_url('index.php')) ?>" aria-label="2B2X Browser home">
      <span class="brand-mark" aria-hidden="true"><i></i><i></i></span>
      <span class="brand-wordmark">2B2X<small>Browser</small></span>
    </a>

    <div class="nav-links" data-nav-links>
      <a href="<?= e(app_url('index.php#experience')) ?>" class="<?= $active === 'index.php' ? 'active' : '' ?>">Experience</a>
      <a href="<?= e(app_url('index.php#features')) ?>">Architecture</a>
      <a href="<?= e(app_url('download.php')) ?>" class="<?= $active === 'download.php' ? 'active' : '' ?>">Download</a>
      <a href="<?= e(app_url('changelog.php')) ?>" class="<?= $active === 'changelog.php' ? 'active' : '' ?>">Changelog</a>
      <a href="<?= e(app_url('blog.php')) ?>" class="<?= in_array($active, ['blog.php', 'blog-post.php'], true) ? 'active' : '' ?>">Journal</a>
      <a href="<?= e(app_url('support.php')) ?>" class="<?= $active === 'support.php' ? 'active' : '' ?>">Support</a>
    </div>

    <div class="nav-actions">
      <?php if ($current_user): ?>
        <a class="nav-account" href="<?= e(app_url('account.php')) ?>" title="Account dashboard">
          <span class="status-dot" aria-hidden="true"></span>
          <span><?= e($current_user['username']) ?></span>
        </a>
        <a class="button button-ghost button-small nav-auth-btn" href="<?= e(app_url('logout.php')) ?>">Logout</a>
      <?php else: ?>
        <a class="button button-ghost button-small nav-auth-btn" href="<?= e(app_url('login.php')) ?>">Sign in</a>
      <?php endif; ?>
      <a class="button button-primary button-small nav-download" data-ripple href="<?= e(app_url('download.php')) ?>">
        <span>Download v<?= e($nav_version) ?></span>
        <span class="arrow" aria-hidden="true">↗</span>
      </a>
      <button class="menu-toggle" data-menu-toggle aria-expanded="false" aria-controls="mobile-menu" aria-label="Toggle navigation menu">
        <span></span>
        <span></span>
      </button>
    </div>
  </nav>

  <div class="mobile-menu" id="mobile-menu" data-mobile-menu aria-hidden="true">
    <div class="mobile-menu-inner">
      <div class="mobile-menu-label">Navigation</div>
      <a href="<?= e(app_url('index.php#experience')) ?>" data-mobile-link>Experience</a>
      <a href="<?= e(app_url('index.php#features')) ?>" data-mobile-link>Architecture</a>
      <a href="<?= e(app_url('download.php')) ?>" data-mobile-link>Download</a>
      <a href="<?= e(app_url('changelog.php')) ?>" data-mobile-link>Changelog</a>
      <a href="<?= e(app_url('blog.php')) ?>" data-mobile-link>Journal</a>
      <a href="<?= e(app_url('support.php')) ?>" data-mobile-link>Support</a>
      <a href="<?= e(app_url('contact.php')) ?>" data-mobile-link>Contact</a>
      <div class="mobile-menu-footer">
        <?php if ($current_user): ?>
          <a class="button button-ghost" href="<?= e(app_url('account.php')) ?>">Account (<?= e($current_user['username']) ?>)</a>
          <a class="button button-ghost" href="<?= e(app_url('logout.php')) ?>">Logout</a>
        <?php else: ?>
          <a class="button button-ghost" href="<?= e(app_url('login.php')) ?>">Sign in</a>
        <?php endif; ?>
        <a class="button button-primary" href="<?= e(app_url('download.php')) ?>">Download for Windows <span class="arrow">↗</span></a>
      </div>
    </div>
  </div>
</header>

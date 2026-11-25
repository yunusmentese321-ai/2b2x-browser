<?php
declare(strict_types=1);

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/storage.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';
start_session();

$slug = sanitize($_GET['slug'] ?? '');
$post = null;
foreach (load_json('blog.json', []) as $candidate) {
    if (is_array($candidate) && ($candidate['slug'] ?? '') === $slug) {
        $post = $candidate;
        break;
    }
}

if (!$post) {
    http_response_code(404);
    $pageTitle = 'Story Not Found — 2B2X Journal';
    require_once __DIR__ . '/includes/header.php';
    ?>
    <main class="auth-page">
      <div class="card empty-state" style="max-width:480px;">
        <span class="status-pill">404</span>
        <h1 style="margin:18px 0 8px;">Story not found.</h1>
        <p style="margin-bottom:24px;">The requested journal entry could not be located.</p>
        <a class="button button-primary" href="<?= e(app_url('blog.php')) ?>">Back to Journal ↗</a>
      </div>
    </main>
    <?php
    require_once __DIR__ . '/includes/footer.php';
    exit;
}

$title = (string) ($post['title'] ?? 'Article');
$pageTitle = $title . ' — 2B2X Journal';
$pageDescription = truncate((string) ($post['excerpt'] ?? ''), 160);
$dateVal = (string) ($post['date'] ?? ($post['published_at'] ?? ''));

require_once __DIR__ . '/includes/header.php';
?>
<main>
  <section class="article-hero">
    <div class="container">
      <div class="breadcrumb">
        <a href="<?= e(app_url('index.php')) ?>">Home</a>
        <span>/</span>
        <a href="<?= e(app_url('blog.php')) ?>">Journal</a>
        <span>/</span>
        <span><?= e(truncate($title, 48)) ?></span>
      </div>

      <div class="article-header">
        <div class="eyebrow"><?= e((string) ($post['category'] ?? 'Product')) ?></div>
        <h1><?= e($title) ?></h1>
        <div class="meta">
          <?= e(format_date($dateVal)) ?> · <?= e((string) ($post['author'] ?? '2B2X Studio')) ?> · <?= e(reading_time((string) ($post['content'] ?? ''))) ?>
        </div>
        <div class="cover" aria-hidden="true"></div>
      </div>
    </div>
  </section>

  <article class="article">
    <div class="article-body">
      <?= (string) ($post['content'] ?? '') ?>
    </div>

    <div class="card" style="margin-top:56px;display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:16px;">
      <div>
        <strong style="display:block;font-size:18px;">Explore 2B2X Browser</strong>
        <span style="color:var(--muted);font-size:14px;">Download the latest Windows release or read more from the studio.</span>
      </div>
      <div style="display:flex;gap:10px;flex-wrap:wrap;">
        <a class="button button-ghost button-small" href="<?= e(app_url('blog.php')) ?>">← All stories</a>
        <a class="button button-primary button-small" href="<?= e(app_url('download.php')) ?>">Download 2B2X ↗</a>
      </div>
    </div>
  </article>
</main>
<?php require_once __DIR__ . '/includes/footer.php'; ?>

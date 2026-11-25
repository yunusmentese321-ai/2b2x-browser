<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/header.php';

$posts = array_reverse(load_json('blog.json', []));
?>
<main>
  <section class="page-hero">
    <div class="container">
      <div class="breadcrumb">
        <a href="<?= e(app_url('index.php')) ?>">Home</a>
        <span>/</span>
        <span>Journal</span>
      </div>
      <div class="eyebrow">2B2X Journal</div>
      <h1>Notes on better browsing.</h1>
      <p>Product thinking, interface craft, and practical ideas for building a calmer, more considered browser experience.</p>
    </div>
  </section>

  <section class="section" style="padding-top:20px;">
    <div class="container">
      <?php if (!$posts): ?>
        <div class="card empty-state">
          <div class="empty-icon">✎</div>
          <h3>No stories yet</h3>
          <p>New articles from the 2B2X studio will appear here.</p>
        </div>
      <?php else: ?>
        <div class="blog-grid">
          <?php foreach ($posts as $post):
            $slug = (string) ($post['slug'] ?? '');
            $dateVal = (string) ($post['date'] ?? ($post['published_at'] ?? ''));
          ?>
            <article class="article-card reveal" data-reveal>
              <div class="image">
                <span class="status-pill"><?= e((string) ($post['category'] ?? 'Product')) ?></span>
                <span class="release-date"><?= e(reading_time((string) ($post['content'] ?? ''))) ?></span>
              </div>
              <div class="body">
                <div class="meta"><?= e(format_date($dateVal)) ?> · <?= e((string) ($post['author'] ?? '2B2X Studio')) ?></div>
                <h3><?= e((string) ($post['title'] ?? '')) ?></h3>
                <p><?= e(truncate((string) ($post['excerpt'] ?? ''), 160)) ?></p>
                <a href="<?= e(app_url('blog-post.php?slug=' . rawurlencode($slug))) ?>">
                  <span>Read story</span>
                  <span aria-hidden="true">↗</span>
                </a>
              </div>
            </article>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </section>
</main>
<?php require_once __DIR__ . '/includes/footer.php'; ?>

<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/header.php';

$entries = array_reverse(load_json('changelog.json', []));
$categories = [];
foreach ($entries as $entry) {
    if (is_array($entry) && !empty($entry['category'])) {
        $cat = (string) $entry['category'];
        $categories[$cat] = $cat;
    }
}
?>
<main>
  <section class="page-hero">
    <div class="container">
      <div class="breadcrumb">
        <a href="<?= e(app_url('index.php')) ?>">Home</a>
        <span>/</span>
        <span>Changelog</span>
      </div>
      <div class="eyebrow">Product History</div>
      <h1>Built in public.</h1>
      <p>Every change, release, and interface refinement is recorded here so the evolution of 2B2X Browser stays visible.</p>
    </div>
  </section>

  <section class="section" style="padding-top:20px;">
    <div class="container content-grid">
      <div class="content-block reveal" data-reveal>
        <div class="section-kicker">Release Cadence</div>
        <h2>See what changed.</h2>
        <p>Release notes are tracked chronologically alongside published installer builds so you always know what shipped in each version.</p>
        <a class="button button-primary" data-ripple href="<?= e(app_url('download.php')) ?>">
          <span>Get latest release</span>
          <span class="arrow" aria-hidden="true">↗</span>
        </a>
      </div>

      <div class="reveal reveal-delay-1" data-reveal>
        <?php if ($categories): ?>
          <div class="filter-pills" role="group" aria-label="Filter changelog by category">
            <button class="filter-pill active" type="button" data-filter-cat="all">All</button>
            <?php foreach ($categories as $cat): ?>
              <button class="filter-pill" type="button" data-filter-cat="<?= e($cat) ?>"><?= e($cat) ?></button>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

        <div class="timeline">
          <?php if (!$entries): ?>
            <div class="card empty-state">
              <div class="empty-icon">✦</div>
              <h3>No releases found</h3>
              <p>The changelog is waiting for its first entry.</p>
            </div>
          <?php else: ?>
            <?php foreach ($entries as $entry):
              $cat = (string) ($entry['category'] ?? 'Release');
            ?>
              <article class="timeline-item" data-category="<?= e($cat) ?>">
                <div class="timeline-dot" aria-hidden="true">v</div>
                <div>
                  <time><?= e(format_date((string) ($entry['date'] ?? ''))) ?></time>
                  <h3><?= e((string) ($entry['title'] ?? '')) ?> · v<?= e((string) ($entry['version'] ?? '')) ?></h3>
                  <p><?= e((string) ($entry['description'] ?? '')) ?></p>
                  <span class="status-badge status-active"><?= e($cat) ?></span>
                </div>
              </article>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </section>
</main>
<?php require_once __DIR__ . '/includes/footer.php'; ?>

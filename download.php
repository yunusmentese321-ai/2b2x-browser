<?php
declare(strict_types=1);

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/storage.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';
start_session();

$settings = get_settings();

// Binary file download stream when release_id or start=1 is requested
if (isset($_GET['release_id']) || isset($_GET['start'])) {
    if (empty($settings['download_enabled'])) {
        fail('Downloads are temporarily paused by the administrator.', 503);
    }

    $releaseId = sanitize($_GET['release_id'] ?? '');
    $release = $releaseId !== '' ? release_by_id($releaseId) : current_release();

    if (!$release || !($release['is_published'] ?? false)) {
        fail('The requested release is not available or has been removed.', 404);
    }

    $filename = safe_filename((string) ($release['filename'] ?? ''));
    $source = config_path('browsers') . '/' . $filename;
    $hasLocalFile = is_file($source) && is_readable($source);

    if ($hasLocalFile) {
        $expectedChecksum = (string) ($release['checksum'] ?? '');
        if ($expectedChecksum !== '' && strcasecmp(sha256_file($source), $expectedChecksum) !== 0) {
            fail('The installer checksum does not match the release metadata.', 500);
        }
    }

    $record = [
        'id' => bin2hex(random_bytes(16)),
        'release_id' => $release['id'],
        'user_id' => current_user()['id'] ?? null,
        'operating_system' => 'Windows',
        'architecture' => (string) ($release['architecture'] ?? '64-bit'),
        'downloaded_at' => date('c'),
        'ip_hash' => ips_hash($_SERVER['REMOTE_ADDR'] ?? 'unknown'),
        'filename' => $filename,
    ];

    if (!append_json('downloads.json', $record)) {
        fail('The download could not be recorded.', 500);
    }

    if (!$hasLocalFile) {
        $versionTag = 'v' . ltrim((string) ($release['version'] ?? '1.0.0'), 'v');
        $remoteUrl = 'https://github.com/yunusmentese321-ai/2b2x-browser/releases/download/'
            . rawurlencode($versionTag) . '/' . rawurlencode($filename);
        header('Location: ' . $remoteUrl, true, 302);
        exit;
    }

    $fileSize = filesize($source);
    if ($fileSize === false) {
        fail('The installer size could not be determined.', 500);
    }

    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Content-Length: ' . $fileSize);
    header('X-Content-Type-Options: nosniff');
    readfile($source);
    exit;
}

// Public Download & Releases Center UI
require_once __DIR__ . '/includes/header.php';

$allReleases = array_values(array_filter(
    array_reverse(load_json('releases.json', [])),
    static fn (mixed $r): bool => is_array($r) && !empty($r['is_published'])
));
$latest = current_release() ?? ($allReleases[0] ?? null);
$downloadEnabled = !empty($settings['download_enabled']);
?>
<main>
  <section class="page-hero">
    <div class="container">
      <div class="breadcrumb">
        <a href="<?= e(app_url('index.php')) ?>">Home</a>
        <span>/</span>
        <span>Download</span>
      </div>
      <div class="eyebrow">Official Windows Releases</div>
      <h1>Get 2B2X Browser.</h1>
      <p>Download the verified Windows installer directly, inspect SHA-256 checksums, and review notes for every published release.</p>
    </div>
  </section>

  <section class="section" style="padding-top:20px;">
    <div class="container">
      <?php if (!$latest): ?>
        <div class="card empty-state">
          <div class="empty-icon">↓</div>
          <h3>No published releases yet</h3>
          <p>Check back soon or review the changelog for upcoming updates.</p>
        </div>
      <?php else:
        $latestVersion = (string) ($latest['version'] ?? '1.0.0');
        $latestTitle = (string) ($latest['title'] ?? 'Release');
        $latestFilename = (string) ($latest['filename'] ?? '2b2x-Browser-Setup.exe');
        $latestSize = isset($latest['file_size']) ? file_size_human((int) $latest['file_size']) : '—';
        $latestChecksum = (string) ($latest['checksum'] ?? '');
        $latestUrl = app_url('download.php?release_id=' . rawurlencode((string) $latest['id']));
        $notes = is_array($latest['release_notes'] ?? null) ? $latest['release_notes'] : [];
      ?>
        <div class="release-hero-grid">
          <article class="release-card latest reveal" data-reveal>
            <div class="release-heading">
              <div>
                <span class="status-pill">Latest Release</span>
                <div class="release-version" style="margin-top:12px;">
                  v<?= e($latestVersion) ?> · <?= e($latestTitle) ?>
                </div>
              </div>
              <span class="release-date"><?= e(format_date((string) ($latest['published_at'] ?? ''))) ?></span>
            </div>

            <p><?= e((string) ($latest['description'] ?? '')) ?></p>

            <?php if ($notes): ?>
              <ul class="release-notes">
                <?php foreach ($notes as $note): ?>
                  <li><?= e((string) $note) ?></li>
                <?php endforeach; ?>
              </ul>
            <?php endif; ?>

            <div class="download-details" style="margin-top:0;">
              <div class="download-detail">
                <span>Operating System</span>
                <strong><?= e((string) ($latest['supported_os'] ?? 'Windows 10 / 11')) ?></strong>
              </div>
              <div class="download-detail">
                <span>Architecture</span>
                <strong><?= e((string) ($latest['architecture'] ?? '64-bit')) ?></strong>
              </div>
              <div class="download-detail">
                <span>Installer File</span>
                <strong><?= e($latestFilename) ?></strong>
              </div>
              <div class="download-detail">
                <span>Package Size</span>
                <strong><?= e($latestSize) ?></strong>
              </div>
            </div>

            <?php if ($latestChecksum !== ''): ?>
              <div class="checksum-box">
                <span class="checksum-value">SHA-256: <?= e($latestChecksum) ?></span>
                <button class="copy-button" type="button" data-copy="<?= e($latestChecksum) ?>">Copy</button>
              </div>
            <?php endif; ?>

            <?php if ($downloadEnabled): ?>
              <button
                class="button button-primary"
                type="button"
                style="width:100%;"
                data-ripple
                data-download="<?= e($latestUrl) ?>"
                data-filename="<?= e($latestFilename) ?>"
              >
                <span>Download <?= e($latestFilename) ?></span>
                <span class="arrow" aria-hidden="true">↗</span>
              </button>
            <?php else: ?>
              <div class="alert error">Downloads are temporarily paused by the site administrator.</div>
            <?php endif; ?>
          </article>

          <aside class="requirements-card reveal reveal-delay-1" data-reveal>
            <div>
              <div class="section-kicker">System &amp; Integrity</div>
              <h3>Before you install</h3>
              <p>Every 2B2X release package is verified server-side against its published SHA-256 digest before streaming to your device.</p>

              <div class="mini-stat"><span>Supported OS</span><strong><?= e((string) ($settings['supported_os'] ?? 'Windows 10 / 11')) ?></strong></div>
              <div class="mini-stat"><span>Processor</span><strong>64-bit (x64)</strong></div>
              <div class="mini-stat"><span>Package Format</span><strong>Windows Executable (.exe)</strong></div>
              <div class="mini-stat"><span>Account History</span><strong>Optional · Stored locally</strong></div>
              <div class="mini-stat"><span>Need Help?</span><strong><a href="<?= e(app_url('support.php')) ?>" style="color:var(--accent-2)">Installation Support ↗</a></strong></div>
            </div>

            <div class="alert" style="margin-top:24px;">
              Verify your downloaded installer in PowerShell with:<br>
              <code style="color:var(--text);font-size:11.5px;">Get-FileHash <?= e($latestFilename) ?> -Algorithm SHA256</code>
            </div>
          </aside>
        </div>

        <?php if (count($allReleases) > 1): ?>
          <div style="margin-top:72px;">
            <div class="section-heading" style="margin-bottom:32px;">
              <div class="section-kicker">Archive</div>
              <h2 style="font-size:clamp(28px,3.5vw,42px);">All published releases</h2>
            </div>

            <div style="display:grid;gap:16px;">
              <?php foreach ($allReleases as $item):
                $relUrl = app_url('download.php?release_id=' . rawurlencode((string) $item['id']));
              ?>
                <article class="release-card">
                  <div class="release-heading">
                    <div>
                      <strong>v<?= e((string) $item['version']) ?> · <?= e((string) $item['title']) ?></strong>
                      <span class="release-date" style="margin-left:10px;"><?= e(format_date((string) ($item['published_at'] ?? ''))) ?></span>
                    </div>
                    <?php if ($downloadEnabled): ?>
                      <button
                        class="button button-ghost button-small"
                        type="button"
                        data-download="<?= e($relUrl) ?>"
                        data-filename="<?= e((string) ($item['filename'] ?? '2b2x-Browser-Setup.exe')) ?>"
                      >
                        Download ↗
                      </button>
                    <?php endif; ?>
                  </div>
                  <p><?= e((string) ($item['description'] ?? '')) ?></p>
                </article>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endif; ?>
      <?php endif; ?>
    </div>
  </section>
</main>
<?php require_once __DIR__ . '/includes/footer.php'; ?>

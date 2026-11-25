<?php
declare(strict_types=1);

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/storage.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';
start_session();
require_login();

$user = current_user();
$downloads = load_json('downloads.json', []);
$history = array_values(array_filter(
    $downloads,
    static fn (mixed $d): bool => is_array($d) && ($d['user_id'] ?? null) === ($user['id'] ?? '')
));
$latest = current_release();
$latestVersion = (string) ($latest['version'] ?? '—');
$latestUrl = app_url('download.php') . (isset($latest['id']) ? '?release_id=' . rawurlencode((string) $latest['id']) : '?start=1');
$latestFilename = (string) ($latest['filename'] ?? '2b2x-Browser-Setup.exe');

require_once __DIR__ . '/includes/header.php';
?>
<main class="account-page">
  <div class="container account-layout">
    <aside class="account-sidebar" aria-label="Account navigation">
      <a class="active" href="<?= e(app_url('account.php')) ?>">Overview</a>
      <a href="<?= e(app_url('download.php')) ?>">Releases</a>
      <a href="<?= e(app_url('support.php')) ?>">Support</a>
      <a href="<?= e(app_url('logout.php')) ?>">Logout</a>
    </aside>

    <div class="account-main">
      <div class="account-welcome">
        <div class="status-pill">Account Active</div>
        <h1>Welcome back, <?= e((string) ($user['username'] ?? 'User')) ?>.</h1>
        <p>Your account and release download records are stored locally on this server.</p>
      </div>

      <div class="metric-grid">
        <div class="metric">
          <span>Total downloads</span>
          <strong><?= count($history) ?></strong>
        </div>
        <div class="metric">
          <span>Latest version</span>
          <strong>v<?= e($latestVersion) ?></strong>
        </div>
        <div class="metric">
          <span>Member since</span>
          <strong><?= e(format_date((string) ($user['created_at'] ?? ''))) ?></strong>
        </div>
        <div class="metric">
          <span>Platform</span>
          <strong>Windows</strong>
        </div>
      </div>

      <section class="card">
        <div class="admin-toolbar" style="padding:0 0 18px;border-bottom:1px solid var(--border);margin-bottom:18px;">
          <div>
            <h2>Download history</h2>
            <p>Every Windows installer request made while signed in is logged here.</p>
          </div>
          <button
            class="button button-primary button-small"
            type="button"
            data-download="<?= e($latestUrl) ?>"
            data-filename="<?= e($latestFilename) ?>"
          >
            Download latest ↗
          </button>
        </div>

        <div class="table-wrap">
          <table>
            <thead>
              <tr>
                <th>Version</th>
                <th>Platform</th>
                <th>Architecture</th>
                <th>Date</th>
                <th>File</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!$history): ?>
                <tr>
                  <td colspan="5">
                    <div class="empty-state">
                      <div class="empty-icon">↓</div>
                      <h3>No downloads yet</h3>
                      <p>Your first installer download will appear here.</p>
                    </div>
                  </td>
                </tr>
              <?php else: ?>
                <?php foreach (array_reverse($history) as $item):
                  $rel = release_by_id((string) ($item['release_id'] ?? ''));
                  $ver = (string) ($rel['version'] ?? 'Unknown');
                ?>
                  <tr>
                    <td><strong>v<?= e($ver) ?></strong></td>
                    <td>Windows</td>
                    <td><?= e((string) ($item['architecture'] ?? '64-bit')) ?></td>
                    <td><?= e(format_date((string) ($item['downloaded_at'] ?? ''))) ?></td>
                    <td class="mono"><?= e((string) ($item['filename'] ?? '')) ?></td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </section>
    </div>
  </div>
</main>
<?php require_once __DIR__ . '/includes/footer.php'; ?>

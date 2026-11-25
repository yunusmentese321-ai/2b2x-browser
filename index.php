<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/header.php';

$release = current_release();
$latest = $release ?? [];
$settings = get_settings();
$changelog = array_slice(array_reverse(load_json('changelog.json', [])), 0, 3);
$posts = load_json('blog.json', []);
$featuredPost = $posts ? $posts[array_key_last($posts)] : null;

$version = (string) ($latest['version'] ?? '1.0.0');
$releaseTitle = (string) ($latest['title'] ?? 'First Light');
$architecture = (string) ($latest['architecture'] ?? '64-bit');
$supportedOs = (string) ($latest['supported_os'] ?? ($settings['supported_os'] ?? 'Windows 10 / 11'));
$filename = (string) ($latest['filename'] ?? '2b2x-Browser-Setup.exe');
$fileSize = isset($latest['file_size']) ? file_size_human((int) $latest['file_size']) : '145 MB';
$checksum = (string) ($latest['checksum'] ?? '');
$publishedAt = format_date((string) ($latest['published_at'] ?? date('Y-m-d')));
$downloadUrl = app_url('download.php') . (isset($latest['id']) ? '?release_id=' . rawurlencode((string) $latest['id']) : '?start=1');
$downloadEnabled = !empty($settings['download_enabled']);
$releaseNotes = is_array($latest['release_notes'] ?? null) ? $latest['release_notes'] : [];
?>
<main>
  <!-- =====================================================
       HERO SECTION — CINEMATIC FIRST IMPRESSION
       ===================================================== -->
  <section class="hero">
    <div class="hero-grid" aria-hidden="true"></div>
    <div class="hero-glow" aria-hidden="true"></div>

    <div class="container hero-inner">
      <div class="hero-copy">
        <div class="eyebrow reveal" data-reveal>
          Release <?= e($version) ?> “<?= e($releaseTitle) ?>” · <?= e($supportedOs) ?>
        </div>

        <h1 class="reveal reveal-delay-1" data-reveal>
          2B2X Browser.
          <span class="headline-accent">Browse Beyond Limits.</span>
        </h1>

        <p class="hero-subtitle reveal reveal-delay-2" data-reveal>
          A modern Windows browser built around performance, clarity, and a calmer workspace — keeping the interface out of the way so your attention stays on the web.
        </p>

        <div class="hero-actions reveal reveal-delay-3" data-reveal>
          <?php if ($downloadEnabled): ?>
            <button
              class="button button-primary"
              type="button"
              data-ripple
              data-download="<?= e($downloadUrl) ?>"
              data-filename="<?= e($filename) ?>"
            >
              <span>Download for Windows</span>
              <span class="arrow" aria-hidden="true">↗</span>
            </button>
          <?php else: ?>
            <a class="button button-primary" data-ripple href="<?= e(app_url('download.php')) ?>">
              <span>View Releases</span>
              <span class="arrow" aria-hidden="true">↗</span>
            </a>
          <?php endif; ?>
          <a class="button button-ghost" data-ripple href="#experience">
            <span>Explore the Browser</span>
            <span class="arrow" aria-hidden="true">↓</span>
          </a>
        </div>

        <div class="hero-meta reveal reveal-delay-4" data-reveal>
          <span><?= e($supportedOs) ?></span>
          <span><?= e($architecture) ?> Installer</span>
          <span><?= e($fileSize) ?> · .exe</span>
          <span>v<?= e($version) ?></span>
        </div>
      </div>

      <!-- Interactive Browser Stage -->
      <div class="hero-stage reveal reveal-delay-2" data-reveal data-tilt>
        <div class="hero-stage-halo" aria-hidden="true"></div>

        <div class="browser-mockup" data-browser-demo>
          <div class="browser-chrome-top">
            <div class="window-dots" aria-hidden="true"><i></i><i></i><i></i></div>

            <div class="browser-tabs" role="tablist" aria-label="Interactive 2B2X browser preview tabs">
              <button class="browser-tab active" type="button" role="tab" aria-selected="true" data-demo-tab="workspace" data-demo-url="https://2b2x.local/workspace">
                <span class="tab-dot"></span>
                <span>Workspace</span>
              </button>
              <button class="browser-tab" type="button" role="tab" aria-selected="false" data-demo-tab="release" data-demo-url="https://2b2x.local/releases/<?= e($version) ?>">
                <span class="tab-dot"></span>
                <span>Release <?= e($version) ?></span>
              </button>
              <button class="browser-tab" type="button" role="tab" aria-selected="false" data-demo-tab="journal" data-demo-url="https://2b2x.local/journal/focus">
                <span class="tab-dot"></span>
                <span>Journal</span>
              </button>
            </div>

            <div class="browser-chrome-actions">
              <button class="chrome-pill-btn" type="button" data-toggle-sidebar aria-pressed="false" title="Toggle compact sidebar">Sidebar</button>
              <button class="chrome-pill-btn" type="button" data-toggle-clarity aria-pressed="false" title="Toggle calm clarity mode">Clarity</button>
            </div>
          </div>

          <div class="browser-toolbar">
            <div class="toolbar-nav" aria-hidden="true">
              <span>←</span>
              <span>→</span>
              <span>↻</span>
            </div>
            <div class="browser-address">
              <span class="lock" aria-hidden="true">●</span>
              <span class="address-url" data-browser-url>https://2b2x.local/workspace</span>
              <span class="address-tag">Focused Session</span>
            </div>
          </div>

          <div class="browser-viewport">
            <aside class="browser-sidebar" aria-label="Preview workspace rail">
              <div class="sidebar-heading">2B2X Spaces</div>
              <button class="sidebar-nav-btn active" type="button" data-demo-tab="workspace" data-demo-url="https://2b2x.local/workspace">
                <span class="dot"></span><span>Workspace</span>
              </button>
              <button class="sidebar-nav-btn" type="button" data-demo-tab="release" data-demo-url="https://2b2x.local/releases/<?= e($version) ?>">
                <span class="dot"></span><span>Release <?= e($version) ?></span>
              </button>
              <button class="sidebar-nav-btn" type="button" data-demo-tab="journal" data-demo-url="https://2b2x.local/journal/focus">
                <span class="dot"></span><span>Journal</span>
              </button>

              <div class="sidebar-footer-card">
                <span>Build Target</span>
                <strong><?= e($supportedOs) ?> · <?= e($architecture) ?></strong>
              </div>
            </aside>

            <div class="browser-canvas">
              <div class="browser-panel active" data-demo-panel="workspace">
                <span class="canvas-kicker">Focused Digital Workspace</span>
                <h3>Focus on what matters.</h3>
                <p>Click the tabs or sidebar controls above to explore how 2B2X balances immediate controls with a quiet, distraction-free surface.</p>
                <div class="canvas-cards">
                  <div class="canvas-card">
                    <span>01 · Navigation</span>
                    <strong>Refined Tabs</strong>
                    <p>Compact tab strip and clean hierarchy keep active pages clear.</p>
                  </div>
                  <div class="canvas-card">
                    <span>02 · Platform</span>
                    <strong><?= e($supportedOs) ?></strong>
                    <p>Built as a dedicated <?= e($architecture) ?> Windows desktop experience.</p>
                  </div>
                  <div class="canvas-card">
                    <span>03 · Control</span>
                    <strong>Local Privacy</strong>
                    <p>Local account storage and hashed download records you control.</p>
                  </div>
                </div>
              </div>

              <div class="browser-panel" data-demo-panel="release">
                <span class="canvas-kicker">Current Release · <?= e($publishedAt) ?></span>
                <h3>Version <?= e($version) ?> “<?= e($releaseTitle) ?>”</h3>
                <p><?= e((string) ($latest['description'] ?? 'A focused first release built for fast, private browsing on Windows.')) ?></p>
                <div class="canvas-cards">
                  <?php foreach (array_slice($releaseNotes, 0, 3) as $idx => $note): ?>
                    <div class="canvas-card">
                      <span>Note 0<?= $idx + 1 ?></span>
                      <strong>Included</strong>
                      <p><?= e((string) $note) ?></p>
                    </div>
                  <?php endforeach; ?>
                </div>
              </div>

              <div class="browser-panel" data-demo-panel="journal">
                <span class="canvas-kicker">Design Philosophy</span>
                <h3>Clarity before decoration.</h3>
                <p>Every browser is an agreement between the user and the interface. 2B2X removes visual clutter so structure comes first.</p>
                <div class="canvas-cards">
                  <div class="canvas-card">
                    <span>Principle 01</span>
                    <strong>Calm Surface</strong>
                    <p>Restrained palette and deliberate spacing reduce eye fatigue.</p>
                  </div>
                  <div class="canvas-card">
                    <span>Principle 02</span>
                    <strong>Predictable Motion</strong>
                    <p>Purposeful transitions clarify state changes without noise.</p>
                  </div>
                  <div class="canvas-card">
                    <span>Principle 03</span>
                    <strong>Transparent Data</strong>
                    <p>Clear release checksums and local session accountability.</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="floating-telemetry" aria-label="Release verification status">
          <div class="telemetry-head">
            <span>Installer Check</span>
            <span class="live"><i class="status-dot"></i> Verified</span>
          </div>
          <strong><?= e($filename) ?></strong>
          <div class="pulse-row">
            <div class="pulse-ring" aria-hidden="true"></div>
            <span>SHA-256 verified · <?= e($fileSize) ?> · <?= e($architecture) ?></span>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- =====================================================
       SPECIFICATION STRIP
       ===================================================== -->
  <div class="spec-strip">
    <div class="container spec-items">
      <div class="spec-item">
        <div class="spec-icon" aria-hidden="true">⊞</div>
        <div>
          <strong><?= e($supportedOs) ?></strong>
          <span>Dedicated desktop target</span>
        </div>
      </div>
      <div class="spec-item">
        <div class="spec-icon" aria-hidden="true">⚡</div>
        <div>
          <strong><?= e($architecture) ?> Native Installer</strong>
          <span><?= e($fileSize) ?> executable package</span>
        </div>
      </div>
      <div class="spec-item">
        <div class="spec-icon" aria-hidden="true">◈</div>
        <div>
          <strong>SHA-256 Verified</strong>
          <span>Integrity-checked downloads</span>
        </div>
      </div>
      <div class="spec-item">
        <div class="spec-icon" aria-hidden="true">✦</div>
        <div>
          <strong>Release <?= e($version) ?></strong>
          <span>Published <?= e($publishedAt) ?></span>
        </div>
      </div>
    </div>
  </div>

  <!-- =====================================================
       INTERACTIVE BROWSER ANATOMY STAGE
       ===================================================== -->
  <section class="section" id="experience">
    <div class="container">
      <div class="section-heading reveal" data-reveal>
        <div class="section-kicker">Meet 2B2X</div>
        <h2>A browser that feels considered.</h2>
        <p>Inspect how each layer of the 2B2X interface works together — compact navigation, clean tabs, calm viewport, and clear local control.</p>
      </div>

      <div class="experience-stage reveal" data-reveal>
        <div class="anatomy-list" role="tablist" aria-label="2B2X browser anatomy layers">
          <button
            class="anatomy-step active"
            type="button"
            data-anatomy="navigation"
            data-anatomy-title="Compact, focused navigation"
            data-anatomy-desc="Essential controls remain anchored at the top edge with zero bloated toolbars competing for vertical space."
            data-anatomy-a="Toolbar Height"
            data-anatomy-av="Minimal single-row chrome"
            data-anatomy-b="Hierarchy"
            data-anatomy-bv="Address & controls unified"
          >
            <div class="anatomy-step-head">
              <span class="anatomy-num">01 / NAVIGATION</span>
              <span aria-hidden="true">↗</span>
            </div>
            <h3>Compact Navigation</h3>
            <p>A clean top rail keeps back, forward, and address access immediate without visual clutter.</p>
          </button>

          <button
            class="anatomy-step"
            type="button"
            data-anatomy="tabs"
            data-anatomy-title="Clean tab architecture"
            data-anatomy-desc="Refined tab and navigation behavior makes switching contexts feel effortless and predictable."
            data-anatomy-a="Active State"
            data-anatomy-av="Clear visual distinction"
            data-anatomy-b="Layout"
            data-anatomy-bv="Balanced, readable spacing"
          >
            <div class="anatomy-step-head">
              <span class="anatomy-num">02 / TABS</span>
              <span aria-hidden="true">↗</span>
            </div>
            <h3>Clean Tabs</h3>
            <p>Structured tab presentation keeps your active session unmistakable at a glance.</p>
          </button>

          <button
            class="anatomy-step"
            type="button"
            data-anatomy="canvas"
            data-anatomy-title="Content-first viewport"
            data-anatomy-desc="The browser frame recedes into deep obsidian neutrals so websites and web apps stay sharp and legible."
            data-anatomy-a="Design Rule"
            data-anatomy-av="Clarity before decoration"
            data-anatomy-b="Contrast"
            data-anatomy-bv="Calm, low-glare framing"
          >
            <div class="anatomy-step-head">
              <span class="anatomy-num">03 / WORKSPACE</span>
              <span aria-hidden="true">↗</span>
            </div>
            <h3>Calm Canvas</h3>
            <p>Every surface is tuned to eliminate distraction and give page content room to breathe.</p>
          </button>

          <button
            class="anatomy-step"
            type="button"
            data-anatomy="account"
            data-anatomy-title="Transparent local control"
            data-anatomy-desc="Optional local accounts keep your download history organized with HTTP-only sessions and one-way IP hashing."
            data-anatomy-a="Auth Security"
            data-anatomy-av="Hashed passwords + CSRF"
            data-anatomy-b="Download Log"
            data-anatomy-bv="SHA-256 verified releases"
          >
            <div class="anatomy-step-head">
              <span class="anatomy-num">04 / CONTROL</span>
              <span aria-hidden="true">↗</span>
            </div>
            <h3>Local Control</h3>
            <p>Personal release tracking and direct support workflows backed by clean local storage.</p>
          </button>
        </div>

        <div class="anatomy-preview" data-anatomy-preview data-focus="navigation">
          <div class="anatomy-bar">
            <div class="window-dots" aria-hidden="true"><i></i><i></i><i></i></div>
            <div class="browser-address" style="max-width:340px;">
              <span class="lock">●</span>
              <span class="address-url">2b2x.local/experience</span>
            </div>
            <span class="status-pill">Windows 64-bit</span>
          </div>

          <div class="anatomy-tabs-demo" aria-hidden="true">
            <div class="demo-tab is-current">2B2X Workspace</div>
            <div class="demo-tab">Release <?= e($version) ?></div>
            <div class="demo-tab">Documentation</div>
          </div>

          <div class="anatomy-body">
            <div class="anatomy-rail" aria-hidden="true">
              <div class="rail-pill accent"></div>
              <div class="rail-pill" style="width:70%"></div>
              <div class="rail-pill" style="width:86%"></div>
              <div class="rail-pill" style="width:60%"></div>
            </div>

            <div class="anatomy-stage-pane">
              <div>
                <span class="canvas-kicker">Interactive Layer Inspector</span>
                <h4 data-anatomy-output-title>Compact, focused navigation</h4>
                <p data-anatomy-output-desc>Essential controls remain anchored at the top edge with zero bloated toolbars competing for vertical space.</p>
              </div>

              <div class="anatomy-highlights">
                <div class="anatomy-highlight">
                  <span data-anatomy-output-la>Toolbar Height</span>
                  <strong data-anatomy-output-va>Minimal single-row chrome</strong>
                </div>
                <div class="anatomy-highlight">
                  <span data-anatomy-output-lb>Hierarchy</span>
                  <strong data-anatomy-output-vb>Address &amp; controls unified</strong>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- =====================================================
       ASYMMETRIC BENTO ARCHITECTURE GRID
       ===================================================== -->
  <section class="section" id="features">
    <div class="container">
      <div class="section-heading reveal" data-reveal>
        <div class="section-kicker">Architecture</div>
        <h2>Less noise.<br>More ability.</h2>
        <p>Every detail of 2B2X is shaped around clarity, predictable behavior, and transparent engineering.</p>
      </div>

      <div class="bento-grid">
        <article class="bento-card span-7 reveal" data-reveal data-spotlight>
          <div>
            <div class="bento-meta">
              <span class="bento-index">01 / RESPONSIVENESS</span>
              <div class="bento-icon" aria-hidden="true">↯</div>
            </div>
            <div class="bento-body">
              <h3>Built for Speed &amp; Immediacy</h3>
              <p>Streamlined navigation and an uncluttered interface keep every tab switch, scroll, and interaction direct and responsive.</p>
            </div>
          </div>
          <div class="bento-visual">
            <div class="signal-bars" aria-hidden="true">
              <i></i><i></i><i></i><i></i><i></i><i></i>
            </div>
          </div>
        </article>

        <article class="bento-card span-5 reveal reveal-delay-1" data-reveal data-spotlight>
          <div>
            <div class="bento-meta">
              <span class="bento-index">02 / PRIVACY &amp; SECURITY</span>
              <div class="bento-icon" aria-hidden="true">◈</div>
            </div>
            <div class="bento-body">
              <h3>Privacy Comes First</h3>
              <p>Local JSON storage, bcrypt password hashing, HTTP-only cookies, CSRF protection, and one-way IP hashing keep your activity clean.</p>
            </div>
          </div>
          <div class="bento-visual">
            <div class="shield-tags">
              <span class="shield-tag">HTTP-only cookies</span>
              <span class="shield-tag">CSRF verified</span>
              <span class="shield-tag">SHA-256 IP hash</span>
              <span class="shield-tag">Local history</span>
            </div>
          </div>
        </article>

        <article class="bento-card reveal" data-reveal data-spotlight>
          <div>
            <div class="bento-meta">
              <span class="bento-index">03 / WINDOWS NATIVE</span>
              <div class="bento-icon" aria-hidden="true">⊞</div>
            </div>
            <div class="bento-body">
              <h3>Made for Windows</h3>
              <p>Crafted specifically for <?= e($supportedOs) ?> (<?= e($architecture) ?>) with a straightforward installer and familiar desktop ergonomics.</p>
            </div>
          </div>
          <div class="bento-visual">
            <div class="mini-stat"><span>Installer</span><strong><?= e($fileSize) ?> (.exe)</strong></div>
            <div class="mini-stat"><span>Architecture</span><strong><?= e($architecture) ?></strong></div>
          </div>
        </article>

        <article class="bento-card reveal reveal-delay-1" data-reveal data-spotlight>
          <div>
            <div class="bento-meta">
              <span class="bento-index">04 / EVOLUTION</span>
              <div class="bento-icon" aria-hidden="true">✦</div>
            </div>
            <div class="bento-body">
              <h3>Built in Public</h3>
              <p>Every update is tracked transparently in the changelog alongside verified release packages.</p>
            </div>
          </div>
          <div class="bento-visual">
            <div class="mini-changelog">
              <?php foreach ($changelog as $entry): ?>
                <div class="mini-changelog-row">
                  <strong>v<?= e((string) ($entry['version'] ?? '')) ?></strong>
                  <span><?= e((string) ($entry['title'] ?? '')) ?></span>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        </article>

        <article class="bento-card reveal reveal-delay-2" data-reveal data-spotlight>
          <div>
            <div class="bento-meta">
              <span class="bento-index">05 / CRAFT</span>
              <div class="bento-icon" aria-hidden="true">◐</div>
            </div>
            <div class="bento-body">
              <h3>Simple by Design</h3>
              <p>Powerful controls stay out of your way until needed, maintaining calm hierarchy across desktop and compact windows.</p>
            </div>
          </div>
          <div class="bento-visual">
            <div class="mini-stat"><span>Principle</span><strong>Clarity first</strong></div>
            <div class="mini-stat"><span>Support</span><strong>Direct ticket flow</strong></div>
          </div>
        </article>
      </div>
    </div>
  </section>

  <!-- =====================================================
       MANIFESTO & JOURNAL PREVIEW
       ===================================================== -->
  <section class="manifesto">
    <div class="container manifesto-grid">
      <div class="reveal" data-reveal>
        <div class="section-kicker">The Product Principle</div>
        <h2 class="manifesto-title">Technology should feel <span>effortless.</span></h2>
      </div>

      <div class="manifesto-copy reveal reveal-delay-1" data-reveal>
        <p>2B2X is built around a simple idea: the browser interface should create space for the content, not compete with it. Deliberate typography, restrained surfaces, and transparent engineering give the product a clear identity.</p>

        <?php if (is_array($featuredPost)): ?>
          <a class="journal-feature-card" href="<?= e(app_url('blog-post.php?slug=' . rawurlencode((string) ($featuredPost['slug'] ?? '')))) ?>">
            <div class="meta">
              <span>From the Journal</span>
              <span>·</span>
              <span><?= e((string) ($featuredPost['category'] ?? 'Product')) ?></span>
              <span>·</span>
              <span><?= e(reading_time((string) ($featuredPost['content'] ?? ''))) ?></span>
            </div>
            <h3><?= e((string) ($featuredPost['title'] ?? 'Designing a Browser Around Focus')) ?></h3>
            <p><?= e(truncate((string) ($featuredPost['excerpt'] ?? ''), 150)) ?></p>
            <span class="read-link">Read article <span class="arrow">↗</span></span>
          </a>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <!-- =====================================================
       FLAGSHIP DOWNLOAD FINALE
       ===================================================== -->
  <section class="download-section" id="download">
    <div class="container">
      <div class="download-panel reveal" data-reveal>
        <div class="download-content">
          <div>
            <div class="section-kicker">Ready to explore</div>
            <h2>Download 2B2X Browser</h2>
            <p>Experience a focused browser built specifically for Windows, backed by SHA-256 release verification and a transparent changelog.</p>
            <div class="download-sublinks">
              <a href="<?= e(app_url('download.php')) ?>">All release details ↗</a>
              <a href="<?= e(app_url('changelog.php')) ?>">View changelog ↗</a>
              <a href="<?= e(app_url('security.php')) ?>">Security baseline ↗</a>
            </div>
          </div>

          <div class="download-card">
            <div class="download-card-top">
              <div>
                <h3>2B2X Browser · <?= e($releaseTitle) ?></h3>
                <p>Version <?= e($version) ?> · <?= e($supportedOs) ?></p>
              </div>
              <span class="status-pill"><?= $downloadEnabled ? 'Available' : 'Paused' ?></span>
            </div>

            <div class="download-details">
              <div class="download-detail">
                <span>Architecture</span>
                <strong><?= e($architecture) ?></strong>
              </div>
              <div class="download-detail">
                <span>Package Size</span>
                <strong><?= e($fileSize) ?> (.exe)</strong>
              </div>
              <div class="download-detail">
                <span>Supported OS</span>
                <strong><?= e($supportedOs) ?></strong>
              </div>
              <div class="download-detail">
                <span>Release Date</span>
                <strong><?= e($publishedAt) ?></strong>
              </div>
            </div>

            <?php if ($checksum !== ''): ?>
              <div class="checksum-box" title="SHA-256 Installer Checksum">
                <span class="checksum-value">SHA-256: <?= e($checksum) ?></span>
                <button class="copy-button" type="button" data-copy="<?= e($checksum) ?>">Copy</button>
              </div>
            <?php endif; ?>

            <?php if ($downloadEnabled): ?>
              <button
                class="button button-primary"
                type="button"
                data-ripple
                data-download="<?= e($downloadUrl) ?>"
                data-filename="<?= e($filename) ?>"
              >
                <span>Download Installer</span>
                <span class="arrow" aria-hidden="true">↗</span>
              </button>
            <?php else: ?>
              <a class="button button-ghost" href="<?= e(app_url('download.php')) ?>">Downloads currently paused</a>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>
<?php require_once __DIR__ . '/includes/footer.php'; ?>

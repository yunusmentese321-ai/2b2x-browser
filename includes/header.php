<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/includes/storage.php';
require_once dirname(__DIR__) . '/includes/auth.php';
require_once dirname(__DIR__) . '/includes/csrf.php';
require_once dirname(__DIR__) . '/includes/functions.php';
start_session();

$scriptFilename = str_replace('\\', '/', $_SERVER['SCRIPT_FILENAME'] ?? '');
$scriptName = basename($scriptFilename ?: 'index.php');
$isAdminRoute = str_contains($scriptFilename, '/admin/');

$defaultTitles = [
    'index.php' => '2B2X Browser — Built for Focused Browsing on Windows',
    'download.php' => 'Download 2B2X Browser for Windows — Official Releases',
    'changelog.php' => 'Changelog — 2B2X Browser',
    'blog.php' => 'Journal — 2B2X Browser',
    'blog-post.php' => 'Article — 2B2X Journal',
    'support.php' => 'Support — 2B2X Browser',
    'contact.php' => 'Contact — 2B2X Browser',
    'login.php' => 'Sign In — 2B2X Browser',
    'register.php' => 'Create Account — 2B2X Browser',
    'account.php' => 'Account — 2B2X Browser',
    'privacy.php' => 'Privacy Policy — 2B2X Browser',
    'security.php' => 'Security — 2B2X Browser',
    'terms.php' => 'Terms of Service — 2B2X Browser',
    '404.php' => 'Page Not Found — 2B2X Browser',
];

$resolvedTitle = $pageTitle ?? ($isAdminRoute ? 'Control Center — 2B2X Browser' : ($defaultTitles[$scriptName] ?? '2B2X Browser'));
$resolvedDescription = $pageDescription ?? '2B2X Browser is a modern, focused browser built for Windows 10 and 11 with a calm workspace, SHA-256 verified releases, and transparent local data control.';
$currentUrl = app_url(ltrim((string) ($_SERVER['REQUEST_URI'] ?? 'index.php'), '/'));
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <meta name="theme-color" content="#050507">
  <title><?= e($resolvedTitle) ?></title>
  <meta name="description" content="<?= e($resolvedDescription) ?>">
  <meta name="robots" content="<?= $isAdminRoute ? 'noindex,nofollow' : 'index,follow' ?>">
  <link rel="canonical" href="<?= e($currentUrl) ?>">
  <meta property="og:type" content="website">
  <meta property="og:title" content="<?= e($resolvedTitle) ?>">
  <meta property="og:description" content="<?= e($resolvedDescription) ?>">
  <meta property="og:url" content="<?= e($currentUrl) ?>">
  <meta name="twitter:card" content="summary_large_image">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&family=Space+Grotesk:wght@500;600;700&display=swap">
  <link rel="stylesheet" href="<?= e(app_url('assets/css/style.css')) ?>">
  <link rel="stylesheet" href="<?= e(app_url('assets/css/animations.css')) ?>">
  <link rel="stylesheet" href="<?= e(app_url('assets/css/components.css')) ?>">
  <link rel="stylesheet" href="<?= e(app_url('assets/css/pages.css')) ?>">
  <link rel="stylesheet" href="<?= e(app_url('assets/css/responsive.css')) ?>">
  <script src="<?= e(app_url('assets/js/main.js')) ?>" defer></script>
  <script src="<?= e(app_url('assets/js/forms.js')) ?>" defer></script>
  <script src="<?= e(app_url('assets/js/download.js')) ?>" defer></script>
</head>
<body class="page-<?= e(pathinfo($scriptName, PATHINFO_FILENAME)) ?><?= $isAdminRoute ? ' is-admin' : '' ?>">
  <div class="ambient-canvas" aria-hidden="true"></div>
  <div class="noise" aria-hidden="true"></div>
  <div class="scroll-progress" data-scroll-progress aria-hidden="true"></div>
<?php
if (!$isAdminRoute && empty($hideNavbar)) {
    require_once __DIR__ . '/navbar.php';
}

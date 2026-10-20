<?php
use App\Config\App;
use App\Helpers\AuthHelper;
use App\Helpers\ViewHelper;

$pageTitle = $pageTitle ?? App::APP_NAME;
$currentUser = AuthHelper::user();
if (!isset($user) || !is_array($user)) {
    $user = $currentUser;
}
$currentTheme = $_COOKIE['theme'] ?? 'light';
?>
<!DOCTYPE html>
<html lang="vi" data-theme="<?= htmlspecialchars($currentTheme) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= ViewHelper::e($pageTitle) ?> - <?= App::UNIVERSITY_SHORT ?> (UTB Sơn La)</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= App::url('pic/Logo-Dai-Hoc-Tay-Bac.png') ?>">
    <link rel="apple-touch-icon" href="<?= App::url('pic/Logo-Dai-Hoc-Tay-Bac.png') ?>">
    
    <!-- Instant Theme Synchronization to prevent FOUC -->
    <script>
        (function() {
            try {
                const localTheme = localStorage.getItem('theme') || 'light';
                document.documentElement.setAttribute('data-theme', localTheme);
            } catch (e) {}
        })();
    </script>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Custom Stylesheet -->
    <link rel="stylesheet" href="<?= App::url('public/css/style.css') ?>">
</head>
<body>
<div class="app-container">
    <?php if (AuthHelper::isLoggedIn()): ?>
        <?php require __DIR__ . '/sidebar.php'; ?>
        <div class="app-main">
            <?php require __DIR__ . '/navbar.php'; ?>
            <div class="content-body">
                <?php $flash = AuthHelper::getFlash(); if ($flash): ?>
                    <div class="alert alert-<?= $flash['type'] ?>">
                        <span>
                            <?= match($flash['type']) {
                                'success' => ViewHelper::icon('check-circle', '', 18),
                                'danger'  => ViewHelper::icon('alert-circle', '', 18),
                                'warning' => ViewHelper::icon('alert-triangle', '', 18),
                                default   => ViewHelper::icon('info', '', 18)
                            } ?>
                        </span>
                        <div><?= ViewHelper::e($flash['message']) ?></div>
                    </div>
                <?php endif; ?>
    <?php else: ?>
        <div style="width: 100%; min-height: 100vh; display: flex; flex-direction: column;">
    <?php endif; ?>

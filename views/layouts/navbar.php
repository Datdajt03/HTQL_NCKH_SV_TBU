<?php
use App\Config\App;
use App\Helpers\AuthHelper;
use App\Helpers\ViewHelper;

$pageTitle = $pageTitle ?? 'Bàn làm việc';
$currentUserId = AuthHelper::id();
?>
<header class="top-navbar">
    <div class="page-title">
        <h2><?= ViewHelper::e($pageTitle) ?></h2>
    </div>

    <div class="top-actions">
        <div class="quick-switcher" title="Chuyển đổi vai trò người dùng nhanh chóng khi kiểm thử">
            <span style="color: var(--text-muted); font-size: 11px; font-weight: 700; margin-left: 6px; text-transform: uppercase;">
                Đổi vai trò:
            </span>
            <a href="<?= App::url('index.php?action=quick-login&user_id=SV001') ?>" class="switch-pill <?= $currentUserId === 'SV001' ? 'active' : '' ?>">
                <?= ViewHelper::icon('user', '', 13) ?> SV (SV001)
            </a>
            <a href="<?= App::url('index.php?action=quick-login&user_id=GV001') ?>" class="switch-pill <?= $currentUserId === 'GV001' ? 'active' : '' ?>">
                <?= ViewHelper::icon('user-check', '', 13) ?> GV (GV001)
            </a>
            <a href="<?= App::url('index.php?action=quick-login&user_id=CB001') ?>" class="switch-pill <?= $currentUserId === 'CB001' ? 'active' : '' ?>">
                <?= ViewHelper::icon('shield', '', 13) ?> Cán bộ (CB001)
            </a>
            <a href="<?= App::url('index.php?action=quick-login&user_id=ADMIN') ?>" class="switch-pill <?= $currentUserId === 'ADMIN' ? 'active' : '' ?>">
                <?= ViewHelper::icon('settings', '', 13) ?> Admin
            </a>
        </div>

        <!-- NÚT CHUYỂN ĐỔI DARK / WHITE MODE -->
        <button type="button" class="theme-toggle-btn" id="theme-toggle" title="Chuyển đổi giao diện Sáng / Tối">
            <span class="icon-sun"><?= ViewHelper::icon('sun', '', 15) ?></span>
            <span class="icon-moon"><?= ViewHelper::icon('moon', '', 15) ?></span>
            <span class="theme-text" id="theme-label" style="font-size: 12px; font-weight: 600;">Chế độ Sáng</span>
        </button>

        <a href="<?= App::url('setup.php') ?>" class="btn btn-sm btn-secondary" style="font-size: 12px; gap: 4px;" title="Cấu hình lại Database">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
            DB Setup
        </a>
    </div>
</header>

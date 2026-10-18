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
        <?php if (AuthHelper::isAdmin()): ?>
            <div class="quick-switcher admin-switcher-bar" title="Chuyển đổi giao diện (Đặc quyền Quản trị viên Admin)">
                <span class="admin-badge-indicator">
                    👑 ADMIN
                </span>
                <span style="color: var(--text-muted); font-size: 11px; font-weight: 700; margin-left: 2px; text-transform: uppercase;">
                    Chuyển sang:
                </span>
                <a href="<?= App::url('index.php?action=admin-switch-role&role=SINH_VIEN') ?>" class="switch-pill <?= $currentUserId === 'SV001' ? 'active' : '' ?>">
                    <?= ViewHelper::icon('user', '', 13) ?> Sinh viên (SV001)
                </a>
                <a href="<?= App::url('index.php?action=admin-switch-role&role=GIANG_VIEN') ?>" class="switch-pill <?= $currentUserId === 'GV001' ? 'active' : '' ?>">
                    <?= ViewHelper::icon('user-check', '', 13) ?> Giảng viên (GV001)
                </a>
                <a href="<?= App::url('index.php?action=admin-switch-role&role=CAN_BO_QL') ?>" class="switch-pill <?= $currentUserId === 'CB001' ? 'active' : '' ?>">
                    <?= ViewHelper::icon('shield', '', 13) ?> Cán bộ QL (CB001)
                </a>
                <a href="<?= App::url('index.php?action=admin-switch-role&role=ADMIN') ?>" class="switch-pill <?= $currentUserId === 'ADMIN' ? 'active' : '' ?>" style="border: 1px solid rgba(245, 158, 11, 0.4);">
                    <?= ViewHelper::icon('settings', '', 13) ?> Về Admin
                </a>
            </div>
        <?php endif; ?>

        <!-- NÚT XEM CỔNG TRỰC TUYẾN TRƯỜNG ĐH TÂY BẮC (SƠN LA) -->
        <a href="<?= App::url('index.php?action=gioi-thieu#cua-so-web') ?>" class="btn btn-sm btn-secondary" style="font-size: 12px; gap: 6px; padding: 6px 12px; border-radius: var(--radius-sm);" title="Cổng Giới thiệu & Cửa Sổ Trực Tuyến utb.edu.vn (Sơn La)">
            <img src="<?= App::url('pic/logo.jpg') ?>" alt="Logo UTB" onerror="this.onerror=null; this.src='<?= App::url('public/images/logo.jpg') ?>';" style="width: 18px; height: 18px; border-radius: 50%; object-fit: cover;">
            <span>Website UTB (Sơn La) ↗</span>
        </a>

        <!-- NÚT CHUYỂN ĐỔI DARK / WHITE MODE -->
        <button type="button" class="theme-toggle-btn" id="theme-toggle" title="Chuyển đổi giao diện Sáng / Tối">
            <span class="icon-sun"><?= ViewHelper::icon('sun', '', 15) ?></span>
            <span class="icon-moon"><?= ViewHelper::icon('moon', '', 15) ?></span>
            <span class="theme-text" id="theme-label" style="font-size: 12px; font-weight: 600;">Chế độ Tối</span>
        </button>

        <?php if (AuthHelper::isAdmin()): ?>
            <a href="<?= App::url('setup.php') ?>" class="btn btn-sm btn-secondary" style="font-size: 12px; gap: 4px;" title="Cấu hình lại Database">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                DB Setup
            </a>
        <?php endif; ?>
    </div>
</header>

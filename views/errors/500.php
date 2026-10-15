<?php
use App\Config\App;
use App\Helpers\ViewHelper;

$pageTitle = "500 - Lỗi Máy Chủ Nội Bộ";
require dirname(__DIR__) . '/layouts/header.php';
?>
<div style="text-align: center; padding: 60px 20px; max-width: 720px; margin: 0 auto;">
    <div style="display: inline-flex; align-items: center; justify-content: center; width: 88px; height: 88px; border-radius: 50%; background: rgba(245, 158, 11, 0.12); color: #f59e0b; margin-bottom: 20px;">
        <svg class="svg-icon" style="width: 44px; height: 44px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
    </div>
    <h1 style="font-size: 28px; font-weight: 800; color: #fff; margin-bottom: 8px;">Đã Xảy Ra Lỗi Hệ Thống (500)</h1>
    <p style="color: var(--text-secondary); font-size: 15px; margin-bottom: 24px;">
        <?= ViewHelper::e($errorMessage ?? 'Đã xảy ra sự cố trong quá trình xử lý yêu cầu.') ?>
    </p>

    <?php if (!empty($errorTrace)): ?>
        <div style="text-align: left; background: #0f172a; padding: 16px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.1); margin-bottom: 24px; max-height: 250px; overflow-y: auto;">
            <pre style="color: #fca5a5; font-size: 12px; font-family: monospace;"><?= ViewHelper::e($errorTrace) ?></pre>
        </div>
    <?php endif; ?>

    <div style="display: flex; gap: 12px; justify-content: center;">
        <a href="<?= App::url('setup.php') ?>" class="btn btn-secondary">
            <?= ViewHelper::icon('settings') ?> Kiểm tra lại CSDL (setup.php)
        </a>
        <a href="<?= App::url('index.php') ?>" class="btn btn-primary">
            Quay Về Trang Chủ
        </a>
    </div>
</div>
<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>

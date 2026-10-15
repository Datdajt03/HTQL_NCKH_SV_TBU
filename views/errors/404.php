<?php
use App\Config\App;

$pageTitle = "404 - Không Tìm Thấy Trang";
require dirname(__DIR__) . '/layouts/header.php';
?>
<div style="text-align: center; padding: 60px 20px; max-width: 600px; margin: 0 auto;">
    <div style="display: inline-flex; align-items: center; justify-content: center; width: 88px; height: 88px; border-radius: 50%; background: rgba(59, 130, 246, 0.12); color: #3b82f6; margin-bottom: 20px;">
        <svg class="svg-icon" style="width: 44px; height: 44px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
    </div>
    <h1 style="font-size: 28px; font-weight: 800; color: #fff; margin-bottom: 8px;">Không Tìm Thấy Trang (404)</h1>
    <p style="color: var(--text-secondary); font-size: 15px; margin-bottom: 24px;">
        Đường dẫn hoặc tác vụ bạn đang truy cập không tồn tại hoặc đã được thay đổi.
    </p>
    <a href="<?= App::url('index.php') ?>" class="btn btn-primary">
        &larr; Quay Về Bàn Làm Việc
    </a>
</div>
<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>

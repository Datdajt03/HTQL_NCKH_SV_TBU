<?php
use App\Config\App;

$pageTitle = "403 - Quyền Truy Cập Bị Từ Chối";
require dirname(__DIR__) . '/layouts/header.php';
?>
<div style="text-align: center; padding: 60px 20px; max-width: 600px; margin: 0 auto;">
    <div style="display: inline-flex; align-items: center; justify-content: center; width: 88px; height: 88px; border-radius: 50%; background: rgba(239, 68, 68, 0.12); color: #ef4444; margin-bottom: 20px;">
        <svg class="svg-icon" style="width: 44px; height: 44px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
    </div>
    <h1 style="font-size: 28px; font-weight: 800; color: #fff; margin-bottom: 8px;">Quyền Truy Cập Bị Từ Chối (403)</h1>
    <p style="color: var(--text-secondary); font-size: 15px; margin-bottom: 24px;">
        Tài khoản hiện tại của bạn không có đặc quyền để truy cập vào phân hệ này. Vui lòng kiểm tra lại vai trò hoặc chuyển đổi tài khoản phù hợp trên thanh công cụ phía trên.
    </p>
    <a href="<?= App::url('index.php') ?>" class="btn btn-primary">
        &larr; Quay Về Trang Chủ Hệ Thống
    </a>
</div>
<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>

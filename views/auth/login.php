<?php
use App\Config\App;
use App\Helpers\ViewHelper;

$pageTitle = "Đăng Nhập Hệ Thống";
require dirname(__DIR__) . '/layouts/header.php';
?>
<div style="min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 24px; width: 100%; position: relative;">
    <div style="position: absolute; top: 24px; right: 28px;">
        <button type="button" class="theme-toggle-btn" id="theme-toggle" title="Chuyển đổi giao diện Sáng / Tối">
            <span class="icon-sun"><?= ViewHelper::icon('sun', '', 15) ?></span>
            <span class="icon-moon"><?= ViewHelper::icon('moon', '', 15) ?></span>
            <span class="theme-text" id="theme-label" style="font-size: 12px; font-weight: 600;">Chế độ Sáng</span>
        </button>
    </div>
    <div style="max-width: 480px; width: 100%;">
        <div style="text-align: center; margin-bottom: 32px;">
            <div style="width: 60px; height: 60px; margin: 0 auto 16px; border-radius: var(--radius-lg); background: var(--gradient-primary); display: flex; align-items: center; justify-content: center; color: white; font-size: 28px; box-shadow: var(--shadow-glow);">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"/><path d="M6 6h10"/><path d="M6 10h10"/><path d="M6 14h6"/></svg>
            </div>
            <h1 style="font-size: 24px; font-weight: 800; color: #fff; letter-spacing: -0.02em; margin-bottom: 6px;">
                NCKH SINH VIÊN
            </h1>
            <p style="color: var(--text-secondary); font-size: 14px;">
                Cổng Quản lý Hoạt động Nghiên cứu Khoa học Sinh viên
            </p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger" style="margin-bottom: 20px;">
                <span><?= ViewHelper::icon('alert-circle', '', 18) ?></span>
                <div><?= ViewHelper::e($error) ?></div>
            </div>
        <?php endif; ?>

        <div class="card" style="padding: 32px; background: rgba(30, 41, 59, 0.85); box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);">
            <form method="POST" action="<?= App::url('index.php?action=login') ?>">
                <div class="form-group">
                    <label class="form-label">Tên đăng nhập hoặc Email</label>
                    <input type="text" name="username" class="form-control" placeholder="Mã SV / Mã GV / Mã Cán bộ / Email" required autofocus>
                </div>

                <div class="form-group">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                        <label class="form-label" style="margin-bottom: 0;">Mật khẩu</label>
                        <span style="font-size: 12px; color: var(--text-muted);">Mặc định: 123456</span>
                    </div>
                    <input type="password" name="password" class="form-control" placeholder="Nhập mật khẩu" required>
                </div>

                <button type="submit" class="btn btn-primary btn-lg" style="width: 100%; margin-top: 10px;">
                    Đăng Nhập Vào Hệ Thống
                </button>
            </form>

            <div style="margin-top: 28px; padding-top: 20px; border-top: 1px solid var(--border-color);">
                <div style="font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 12px; text-align: center;">
                    Đăng nhập nhanh (Tài khoản mẫu)
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                    <a href="<?= App::url('index.php?action=quick-login&user_id=SV001') ?>" class="btn btn-secondary btn-sm" style="text-align: left; justify-content: flex-start; padding: 10px 12px; gap: 10px;">
                        <span class="stat-icon icon-blue" style="width: 32px; height: 32px;"><?= ViewHelper::icon('user', '', 16) ?></span>
                        <div>
                            <div style="font-weight: 700; color: #fff; font-size: 12px;">SV001 (Sinh viên)</div>
                            <div style="font-size: 11px; color: var(--text-muted);">Nguyễn Huy Hoàng</div>
                        </div>
                    </a>
                    <a href="<?= App::url('index.php?action=quick-login&user_id=GV001') ?>" class="btn btn-secondary btn-sm" style="text-align: left; justify-content: flex-start; padding: 10px 12px; gap: 10px;">
                        <span class="stat-icon icon-emerald" style="width: 32px; height: 32px;"><?= ViewHelper::icon('user-check', '', 16) ?></span>
                        <div>
                            <div style="font-weight: 700; color: #fff; font-size: 12px;">GV001 (Giảng viên)</div>
                            <div style="font-size: 11px; color: var(--text-muted);">TS. Lê Hoàng Nam</div>
                        </div>
                    </a>
                    <a href="<?= App::url('index.php?action=quick-login&user_id=CB001') ?>" class="btn btn-secondary btn-sm" style="text-align: left; justify-content: flex-start; padding: 10px 12px; gap: 10px;">
                        <span class="stat-icon icon-purple" style="width: 32px; height: 32px;"><?= ViewHelper::icon('shield', '', 16) ?></span>
                        <div>
                            <div style="font-weight: 700; color: #fff; font-size: 12px;">CB001 (Cán bộ QL)</div>
                            <div style="font-size: 11px; color: var(--text-muted);">Trưởng phòng NCKH</div>
                        </div>
                    </a>
                    <a href="<?= App::url('index.php?action=quick-login&user_id=ADMIN') ?>" class="btn btn-secondary btn-sm" style="text-align: left; justify-content: flex-start; padding: 10px 12px; gap: 10px;">
                        <span class="stat-icon icon-amber" style="width: 32px; height: 32px;"><?= ViewHelper::icon('settings', '', 16) ?></span>
                        <div>
                            <div style="font-weight: 700; color: #fff; font-size: 12px;">ADMIN (Quản trị)</div>
                            <div style="font-size: 11px; color: var(--text-muted);">Toàn quyền hệ thống</div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <div style="text-align: center; margin-top: 20px;">
            <a href="<?= App::url('setup.php') ?>" style="font-size: 13px; color: var(--text-muted); text-decoration: underline; display: inline-flex; align-items: center; gap: 6px;">
                <?= ViewHelper::icon('settings', '', 14) ?> Cấu hình lại Database (setup.php)
            </a>
        </div>
    </div>
</div>
<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>

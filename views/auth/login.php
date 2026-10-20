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
            <span class="theme-text" id="theme-label" style="font-size: 12px; font-weight: 600;">Chế độ Tối</span>
        </button>
    </div>
    <div style="max-width: 500px; width: 100%;">
        <div style="text-align: center; margin-bottom: 28px;">
            <a href="<?= App::url('index.php?action=gioi-thieu') ?>" style="text-decoration: none; display: inline-block;" title="Cổng Thông tin Trường Đại học Tây Bắc (Sơn La)">
                <div style="width: 82px; height: 82px; margin: 0 auto 14px; border-radius: 50%; overflow: hidden; box-shadow: 0 10px 25px -4px rgba(37, 99, 235, 0.3); border: 3px solid rgba(255,255,255,0.9); background: #ffffff; transition: transform 0.25s ease;">
                    <img src="<?= App::url('pic/Logo-Dai-Hoc-Tay-Bac.png') ?>" alt="Logo ĐH Tây Bắc" onerror="this.onerror=null; this.src='<?= App::url('public/images/Logo-Dai-Hoc-Tay-Bac.png') ?>';" style="width: 100%; height: 100%; object-fit: cover; display: block;">
                </div>
            </a>
            <h1 style="font-size: 24px; font-weight: 800; color: var(--text-primary); letter-spacing: -0.02em; margin-bottom: 6px;">
                TRƯỜNG ĐẠI HỌC TÂY BẮC
            </h1>
            <p style="color: var(--text-secondary); font-size: 14px; margin-bottom: 12px;">
                Cổng Quản lý Nghiên cứu Khoa học Sinh viên (UTB - Sơn La)
            </p>
            <div style="display: flex; gap: 8px; justify-content: center; flex-wrap: wrap;">
                <a href="<?= App::url('index.php?action=gioi-thieu#cua-so-web') ?>" class="btn btn-sm btn-secondary" style="display: inline-flex; align-items: center; gap: 6px; font-size: 13px; padding: 6px 16px; border-radius: 20px;">
                    <?= ViewHelper::icon('globe', '', 14) ?> Cửa Sổ utb.edu.vn ↗
                </a>
                <a href="https://utb.edu.vn/gioi-thieu/" target="_blank" rel="noopener" class="btn btn-sm btn-secondary" style="display: inline-flex; align-items: center; gap: 6px; font-size: 13px; padding: 6px 14px; border-radius: 20px;">
                    utb.edu.vn/gioi-thieu/ ↗
                </a>
            </div>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger" style="margin-bottom: 20px;">
                <span><?= ViewHelper::icon('alert-circle', '', 18) ?></span>
                <div><?= ViewHelper::e($error) ?></div>
            </div>
        <?php endif; ?>

        <div class="card" style="padding: 32px; background: var(--bg-card); box-shadow: var(--shadow-lg);">
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

            <!-- NÚT ĐĂNG KÝ TÀI KHOẢN MỚI -->
            <div style="margin-top: 18px; text-align: center;">
                <span style="font-size: 14px; color: var(--text-secondary);">Chưa có tài khoản nghiên cứu?</span>
                <a href="<?= App::url('index.php?action=register') ?>" style="display: inline-block; margin-left: 6px; font-weight: 700; color: var(--accent-blue); text-decoration: none;">
                    Đăng ký ngay &rarr;
                </a>
            </div>

            <div style="margin-top: 24px; padding-top: 20px; border-top: 1px solid var(--border-color);">
                <div style="font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 12px; text-align: center;">
                    Đăng nhập nhanh (Tài khoản mẫu)
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                    <a href="<?= App::url('index.php?action=quick-login&user_id=SV001') ?>" class="btn btn-secondary btn-sm" style="text-align: left; justify-content: flex-start; padding: 10px 12px; gap: 10px;">
                        <span class="stat-icon icon-blue" style="width: 32px; height: 32px;"><?= ViewHelper::icon('user', '', 16) ?></span>
                        <div>
                            <div style="font-weight: 700; color: var(--text-primary); font-size: 12px;">SV001 (Sinh viên)</div>
                            <div style="font-size: 11px; color: var(--text-muted);">Nguyễn Huy Hoàng</div>
                        </div>
                    </a>
                    <a href="<?= App::url('index.php?action=quick-login&user_id=GV001') ?>" class="btn btn-secondary btn-sm" style="text-align: left; justify-content: flex-start; padding: 10px 12px; gap: 10px;">
                        <span class="stat-icon icon-emerald" style="width: 32px; height: 32px;"><?= ViewHelper::icon('user-check', '', 16) ?></span>
                        <div>
                            <div style="font-weight: 700; color: var(--text-primary); font-size: 12px;">GV001 (Giảng viên)</div>
                            <div style="font-size: 11px; color: var(--text-muted);">TS. Lê Hoàng Nam</div>
                        </div>
                    </a>
                    <a href="<?= App::url('index.php?action=quick-login&user_id=CB001') ?>" class="btn btn-secondary btn-sm" style="text-align: left; justify-content: flex-start; padding: 10px 12px; gap: 10px;">
                        <span class="stat-icon icon-purple" style="width: 32px; height: 32px;"><?= ViewHelper::icon('shield', '', 16) ?></span>
                        <div>
                            <div style="font-weight: 700; color: var(--text-primary); font-size: 12px;">CB001 (Cán bộ QL)</div>
                            <div style="font-size: 11px; color: var(--text-muted);">Trưởng phòng NCKH</div>
                        </div>
                    </a>
                    <a href="<?= App::url('index.php?action=quick-login&user_id=ADMIN') ?>" class="btn btn-secondary btn-sm" style="text-align: left; justify-content: flex-start; padding: 10px 12px; gap: 10px;">
                        <span class="stat-icon icon-amber" style="width: 32px; height: 32px;"><?= ViewHelper::icon('settings', '', 16) ?></span>
                        <div>
                            <div style="font-weight: 700; color: var(--text-primary); font-size: 12px;">ADMIN (Quản trị)</div>
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

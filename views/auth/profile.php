<?php
use App\Config\App;
use App\Helpers\ViewHelper;

$pageTitle = "Hồ Sơ Cá Nhân";
require dirname(__DIR__) . '/layouts/header.php';
?>
<div style="max-width: 900px; margin: 0 auto;">
    <?php if (!empty($message)): ?>
        <div class="alert alert-success">
            <span><?= ViewHelper::icon('check-circle', '', 18) ?></span>
            <div><?= ViewHelper::e($message) ?></div>
        </div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger">
            <span><?= ViewHelper::icon('alert-circle', '', 18) ?></span>
            <div><?= ViewHelper::e($error) ?></div>
        </div>
    <?php endif; ?>

    <div class="grid-cols-2">
        <!-- Thông tin tài khoản -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <span><?= ViewHelper::icon('user', '', 18) ?></span>
                    <span>Thông Tin Tài Khoản</span>
                </div>
                <?= ViewHelper::statusBadge($user['TrangThai']) ?>
            </div>
            <div class="card-body">
                <form method="POST" action="<?= App::url('index.php?action=profile') ?>">
                    <input type="hidden" name="sub_action" value="update_profile">

                    <div class="form-group">
                        <label class="form-label">Mã Định Danh (Tên đăng nhập)</label>
                        <input type="text" class="form-control" value="<?= ViewHelper::e($user['MaNguoiDung']) ?>" readonly disabled style="opacity: 0.7;">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Vai Trò Hệ Thống</label>
                        <input type="text" class="form-control" value="<?= ViewHelper::roleTitle($user['VaiTro']) ?>" readonly disabled style="opacity: 0.7;">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Họ và Tên <span class="required">*</span></label>
                        <input type="text" name="hoten" class="form-control" value="<?= ViewHelper::e($user['HoTen']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email Nhà Trường <span class="required">*</span></label>
                        <input type="email" name="email" class="form-control" value="<?= ViewHelper::e($user['Email']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Số Điện Thoại Liên Lạc</label>
                        <input type="text" name="sdt" class="form-control" value="<?= ViewHelper::e($user['SoDienThoai']) ?>">
                    </div>

                    <?php if (!empty($user['extra'])): ?>
                        <div style="background: rgba(15, 23, 42, 0.4); border-radius: var(--radius-md); padding: 14px; margin-bottom: 20px;">
                            <div style="font-size: 12px; font-weight: 700; color: var(--accent-blue); text-transform: uppercase; margin-bottom: 8px;">Thông tin chuyên ngành / Đơn vị</div>
                            <?php foreach ($user['extra'] as $k => $v): ?>
                                <?php if (!in_array($k, ['MaSV', 'MaGV', 'MaCB'])): ?>
                                    <div style="display: flex; justify-content: space-between; font-size: 13px; margin-bottom: 4px;">
                                        <span style="color: var(--text-muted);"><?= ViewHelper::e($k) ?>:</span>
                                        <strong style="color: #fff;"><?= ViewHelper::e((string)$v) ?></strong>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <button type="submit" class="btn btn-primary" style="width: 100%;">
                        Lưu Thay Đổi Thông Tin
                    </button>
                </form>
            </div>
        </div>

        <!-- Đổi mật khẩu -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <span><?= ViewHelper::icon('lock', '', 18) ?></span>
                    <span>Đổi Mật Khẩu</span>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="<?= App::url('index.php?action=profile') ?>">
                    <input type="hidden" name="sub_action" value="change_password">

                    <div class="form-group">
                        <label class="form-label">Mật Khẩu Hiện Tại <span class="required">*</span></label>
                        <input type="password" name="old_password" class="form-control" placeholder="Nhập mật khẩu đang dùng" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Mật Khẩu Mới <span class="required">*</span></label>
                        <input type="password" name="new_password" class="form-control" placeholder="Tối thiểu 6 ký tự" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Xác Nhận Mật Khẩu Mới <span class="required">*</span></label>
                        <input type="password" name="confirm_password" class="form-control" placeholder="Nhập lại mật khẩu mới" required>
                    </div>

                    <button type="submit" class="btn btn-secondary" style="width: 100%; margin-top: 10px;">
                        Cập Nhật Mật Khẩu Mới
                    </button>
                </form>

                <div style="margin-top: 24px; padding: 14px; background: rgba(59, 130, 246, 0.05); border: 1px solid rgba(59, 130, 246, 0.2); border-radius: var(--radius-md); font-size: 13px; color: var(--text-secondary); display: flex; gap: 10px; align-items: flex-start;">
                    <span style="color: var(--accent-blue); flex-shrink: 0;"><?= ViewHelper::icon('shield', '', 18) ?></span>
                    <div>
                        <strong>Lưu ý bảo mật:</strong><br>
                        Để tài khoản được bảo vệ tốt nhất, hãy sử dụng mật khẩu mạnh có chứa chữ hoa, số và ký tự đặc biệt.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>

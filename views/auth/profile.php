<?php
use App\Config\App;
use App\Helpers\ViewHelper;
use App\Helpers\AuthHelper;

$pageTitle = "Hồ Sơ Cá Nhân";
require dirname(__DIR__) . '/layouts/header.php';

// Đảm bảo $user chứa thông tin từ DB mới nhất
$userRole = $user['VaiTro'] ?? AuthHelper::role();
$extra = $user['extra'] ?? [];
$initials = mb_substr($user['HoTen'] ?? AuthHelper::name(), 0, 1, 'UTF-8');
?>

<div style="max-width: 1050px; margin: 0 auto;">
    <?php if (!empty($message)): ?>
        <div class="alert alert-success" style="margin-bottom: 24px;">
            <span><?= ViewHelper::icon('check-circle', '', 20) ?></span>
            <div><?= ViewHelper::e($message) ?></div>
        </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger" style="margin-bottom: 24px;">
            <span><?= ViewHelper::icon('alert-circle', '', 20) ?></span>
            <div><?= ViewHelper::e($error) ?></div>
        </div>
    <?php endif; ?>

    <!-- HERO PROFILE HEADER -->
    <div class="card" style="margin-bottom: 24px; padding: 24px; background: var(--bg-card); position: relative; overflow: hidden; border-color: var(--border-color);">
        <div style="position: absolute; right: -20px; top: -20px; width: 140px; height: 140px; background: radial-gradient(circle, rgba(59, 130, 246, 0.15) 0%, transparent 70%); pointer-events: none;"></div>
        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 20px;">
            <div style="display: flex; align-items: center; gap: 20px;">
                <div style="width: 72px; height: 72px; border-radius: var(--radius-xl); background: var(--gradient-primary); display: flex; align-items: center; justify-content: center; color: #ffffff; font-size: 28px; font-weight: 800; box-shadow: var(--shadow-glow); flex-shrink: 0;">
                    <?= ViewHelper::e($initials) ?>
                </div>
                <div>
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 6px; flex-wrap: wrap;">
                        <h2 style="font-size: 22px; font-weight: 800; color: var(--text-primary); margin: 0;">
                            <?= ViewHelper::e($user['HoTen'] ?? AuthHelper::name()) ?>
                        </h2>
                        <?= ViewHelper::statusBadge($user['TrangThai'] ?? 'HOAT_DONG') ?>
                        <span class="badge badge-primary">
                            <span class="badge-dot dot-primary"></span>
                            <?= ViewHelper::roleTitle($userRole) ?>
                        </span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 18px; font-size: 13px; color: var(--text-secondary); flex-wrap: wrap;">
                        <span style="display: inline-flex; align-items: center; gap: 6px;">
                            <?= ViewHelper::icon('hash', '', 14) ?> Mã: <strong style="color: var(--text-primary);"><?= ViewHelper::e($user['MaNguoiDung'] ?? '') ?></strong>
                        </span>
                        <span style="display: inline-flex; align-items: center; gap: 6px;">
                            <?= ViewHelper::icon('inbox', '', 14) ?> <?= ViewHelper::e($user['Email'] ?? '') ?>
                        </span>
                        <?php if (!empty($user['SoDienThoai'])): ?>
                            <span style="display: inline-flex; align-items: center; gap: 6px;">
                                <?= ViewHelper::icon('clock', '', 14) ?> <?= ViewHelper::e($user['SoDienThoai']) ?>
                            </span>
                        <?php endif; ?>
                        <?php if (!empty($user['NgayTao'])): ?>
                            <span style="display: inline-flex; align-items: center; gap: 6px; color: var(--text-muted);">
                                <?= ViewHelper::icon('calendar', '', 14) ?> Tham gia: <?= ViewHelper::date($user['NgayTao']) ?>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div>
                <a href="<?= App::url('index.php?action=logout') ?>" class="btn btn-secondary btn-sm" style="gap: 6px;">
                    <?= ViewHelper::icon('logout', '', 15) ?> Đăng xuất
                </a>
            </div>
        </div>
    </div>

    <!-- MAIN GRID -->
    <div class="grid-cols-2">
        <!-- CỘT TRÁI: THÔNG TIN CÁ NHÂN & CHUYÊN MÔN -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <span><?= ViewHelper::icon('user', '', 18) ?></span>
                    <span>Thông Tin Tài Khoản & Chuyên Môn</span>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="<?= App::url('index.php?action=profile') ?>">
                    <input type="hidden" name="sub_action" value="update_profile">

                    <div style="font-size: 12px; font-weight: 700; color: var(--accent-blue); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 14px;">
                        1. Thông tin tài khoản định danh
                    </div>

                    <div class="grid-cols-2" style="margin-bottom: 12px; gap: 12px;">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Mã Người Dùng (Không đổi)</label>
                            <input type="text" class="form-control" value="<?= ViewHelper::e($user['MaNguoiDung'] ?? '') ?>" readonly disabled style="opacity: 0.75; background: var(--bg-secondary);">
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Vai Trò Hệ Thống</label>
                            <input type="text" class="form-control" value="<?= ViewHelper::roleTitle($userRole) ?>" readonly disabled style="opacity: 0.75; background: var(--bg-secondary);">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Họ và Tên <span class="required">*</span></label>
                        <input type="text" name="hoten" class="form-control" value="<?= ViewHelper::e($user['HoTen'] ?? '') ?>" required placeholder="Nhập họ và tên...">
                    </div>

                    <div class="grid-cols-2" style="margin-bottom: 16px; gap: 12px;">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Địa Chỉ Email <span class="required">*</span></label>
                            <input type="email" name="email" class="form-control" value="<?= ViewHelper::e($user['Email'] ?? '') ?>" required placeholder="email@school.edu.vn">
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Số Điện Thoại</label>
                            <input type="text" name="sdt" class="form-control" value="<?= ViewHelper::e($user['SoDienThoai'] ?? '') ?>" placeholder="09xxxxxxxx">
                        </div>
                    </div>

                    <!-- THÔNG TIN RIÊNG THEO VAI TRÒ -->
                    <div style="margin-top: 24px; padding-top: 18px; border-top: 1px solid var(--border-color);">
                        <div style="font-size: 12px; font-weight: 700; color: var(--accent-indigo); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 14px;">
                            2. Thông tin học tập & Công tác
                        </div>

                        <?php if ($userRole === App::ROLE_SINH_VIEN): ?>
                            <div class="grid-cols-2" style="gap: 12px; margin-bottom: 12px;">
                                <div class="form-group" style="margin-bottom: 0;">
                                    <label class="form-label">Lớp sinh hoạt</label>
                                    <input type="text" name="sv_lop" class="form-control" value="<?= ViewHelper::e($extra['Lop'] ?? '') ?>" placeholder="VD: K64 CNTT 1">
                                </div>
                                <div class="form-group" style="margin-bottom: 0;">
                                    <label class="form-label">Khóa học</label>
                                    <input type="text" name="sv_khoahoc" class="form-control" value="<?= ViewHelper::e($extra['KhoaHoc'] ?? '') ?>" placeholder="VD: 2023 - 2027">
                                </div>
                            </div>
                            <div class="grid-cols-2" style="gap: 12px; margin-bottom: 16px;">
                                <div class="form-group" style="margin-bottom: 0;">
                                    <label class="form-label">Khoa / Viện quản lý</label>
                                    <input type="text" name="sv_khoa" class="form-control" value="<?= ViewHelper::e($extra['Khoa'] ?? '') ?>" placeholder="VD: Khoa Công nghệ Thông tin">
                                </div>
                                <div class="form-group" style="margin-bottom: 0;">
                                    <label class="form-label">Chuyên ngành đào tạo</label>
                                    <input type="text" name="sv_nganh" class="form-control" value="<?= ViewHelper::e($extra['NganhHoc'] ?? '') ?>" placeholder="VD: Khoa học Máy tính">
                                </div>
                            </div>

                        <?php elseif ($userRole === App::ROLE_GIANG_VIEN): ?>
                            <div class="grid-cols-2" style="gap: 12px; margin-bottom: 12px;">
                                <div class="form-group" style="margin-bottom: 0;">
                                    <label class="form-label">Học hàm</label>
                                    <select name="gv_hocham" class="form-control">
                                        <option value="" <?= empty($extra['HocHam']) ? 'selected' : '' ?>>Không có (Chưa phong)</option>
                                        <option value="Phó Giáo sư" <?= ($extra['HocHam'] ?? '') === 'Phó Giáo sư' ? 'selected' : '' ?>>Phó Giáo sư (PGS)</option>
                                        <option value="Giáo sư" <?= ($extra['HocHam'] ?? '') === 'Giáo sư' ? 'selected' : '' ?>>Giáo sư (GS)</option>
                                    </select>
                                </div>
                                <div class="form-group" style="margin-bottom: 0;">
                                    <label class="form-label">Học vị</label>
                                    <select name="gv_hocvi" class="form-control">
                                        <option value="Cử nhân" <?= ($extra['HocVi'] ?? '') === 'Cử nhân' ? 'selected' : '' ?>>Cử nhân / Kỹ sư</option>
                                        <option value="Thạc sĩ" <?= ($extra['HocVi'] ?? '') === 'Thạc sĩ' ? 'selected' : '' ?>>Thạc sĩ</option>
                                        <option value="Tiến sĩ" <?= ($extra['HocVi'] ?? '') === 'Tiến sĩ' ? 'selected' : '' ?>>Tiến sĩ (TS)</option>
                                        <option value="Tiến sĩ Khoa học" <?= ($extra['HocVi'] ?? '') === 'Tiến sĩ Khoa học' ? 'selected' : '' ?>>Tiến sĩ Khoa học (TSKH)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Đơn vị công tác / Bộ môn</label>
                                <input type="text" name="gv_donvicongtac" class="form-control" value="<?= ViewHelper::e($extra['DonViCongTac'] ?? '') ?>" placeholder="VD: Bộ môn Kỹ thuật Phần mềm - Khoa CNTT">
                            </div>
                            <div class="form-group" style="margin-bottom: 16px;">
                                <label class="form-label">Hướng / Lĩnh vực nghiên cứu</label>
                                <textarea name="gv_linhvuc" class="form-control" rows="2" placeholder="VD: Trí tuệ nhân tạo, Xử lý ảnh, Internet of Things..."><?= ViewHelper::e($extra['LinhVucNghienCuu'] ?? '') ?></textarea>
                            </div>

                        <?php elseif ($userRole === App::ROLE_CAN_BO_QL || $userRole === App::ROLE_ADMIN): ?>
                            <div class="grid-cols-2" style="gap: 12px; margin-bottom: 16px;">
                                <div class="form-group" style="margin-bottom: 0;">
                                    <label class="form-label">Phòng ban công tác</label>
                                    <input type="text" name="cb_phongban" class="form-control" value="<?= ViewHelper::e($extra['PhongBan'] ?? '') ?>" placeholder="VD: Phòng Quản lý Khoa học & HTQT">
                                </div>
                                <div class="form-group" style="margin-bottom: 0;">
                                    <label class="form-label">Chức vụ phụ trách</label>
                                    <input type="text" name="cb_chucvu" class="form-control" value="<?= ViewHelper::e($extra['ChucVu'] ?? '') ?>" placeholder="VD: Trưởng phòng / Chuyên viên QL">
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg" style="width: 100%; margin-top: 8px;">
                        <?= ViewHelper::icon('check', '', 18) ?> Lưu Cập Nhật Thông Tin Hồ Sơ
                    </button>
                </form>
            </div>
        </div>

        <!-- CỘT PHẢI: ĐỔI MẬT KHẨU & BẢO MẬT -->
        <div>
            <!-- CARD ĐỔI MẬT KHẨU -->
            <div class="card" style="margin-bottom: 24px;">
                <div class="card-header">
                    <div class="card-title">
                        <span><?= ViewHelper::icon('lock', '', 18) ?></span>
                        <span>Đổi Mật Khẩu Đăng Nhập</span>
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?= App::url('index.php?action=profile') ?>" id="form-change-password">
                        <input type="hidden" name="sub_action" value="change_password">

                        <div class="form-group">
                            <label class="form-label">Mật khẩu hiện tại <span class="required">*</span></label>
                            <input type="password" name="old_password" class="form-control" placeholder="Nhập mật khẩu đang dùng" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Mật khẩu mới <span class="required">*</span></label>
                            <input type="password" name="new_password" class="form-control" placeholder="Tối thiểu 6 ký tự" minlength="6" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Xác nhận mật khẩu mới <span class="required">*</span></label>
                            <input type="password" name="confirm_password" class="form-control" placeholder="Nhập lại mật khẩu mới" minlength="6" required>
                        </div>

                        <button type="submit" class="btn btn-secondary btn-lg" style="width: 100%; margin-top: 10px;">
                            <?= ViewHelper::icon('shield', '', 18) ?> Cập Nhật Mật Khẩu Mới
                        </button>
                    </form>
                </div>
            </div>

            <!-- CARD HƯỚNG DẪN BẢO MẬT & QUYỀN HẠN -->
            <div class="card" style="border-left: 4px solid var(--accent-blue);">
                <div class="card-header" style="border-bottom: none; padding-bottom: 0;">
                    <div class="card-title" style="font-size: 14px;">
                        <span style="color: var(--accent-blue);"><?= ViewHelper::icon('info', '', 18) ?></span>
                        <span>Quyền hạn & Gợi ý bảo mật</span>
                    </div>
                </div>
                <div class="card-body" style="padding-top: 12px; font-size: 13px; color: var(--text-secondary); line-height: 1.6;">
                    <p style="margin-bottom: 10px;">
                        Tài khoản của bạn được cấp quyền truy cập theo vai trò <strong><?= ViewHelper::roleTitle($userRole) ?></strong> trên Cổng Quản lý NCKH Sinh viên.
                    </p>
                    <ul style="padding-left: 18px; margin: 0 0 12px 0;">
                        <li style="margin-bottom: 6px;">Không chia sẻ mật khẩu tài khoản cho người khác.</li>
                        <li style="margin-bottom: 6px;">Cập nhật chính xác địa chỉ Email để nhận thông báo xét duyệt đề tài và lịch bảo vệ Hội đồng.</li>
                        <li>Đăng xuất khỏi hệ thống khi sử dụng máy tính công cộng.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>

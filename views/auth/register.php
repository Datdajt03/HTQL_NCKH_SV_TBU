<?php
use App\Config\App;
use App\Helpers\ViewHelper;

$pageTitle = "Đăng Ký Tài Khoản Mới";
require dirname(__DIR__) . '/layouts/header.php';

$selectedRole = $formData['vaitro'] ?? App::ROLE_SINH_VIEN;
?>
<div style="min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 32px 16px; width: 100%; position: relative;">
    <!-- NÚT CHUYỂN THEME -->
    <div style="position: absolute; top: 20px; right: 24px; z-index: 10;">
        <button type="button" class="theme-toggle-btn" id="theme-toggle" title="Chuyển đổi giao diện Sáng / Tối">
            <span class="icon-sun"><?= ViewHelper::icon('sun', '', 15) ?></span>
            <span class="icon-moon"><?= ViewHelper::icon('moon', '', 15) ?></span>
            <span class="theme-text" id="theme-label" style="font-size: 12px; font-weight: 600;">Chế độ Tối</span>
        </button>
    </div>

    <div style="max-width: 680px; width: 100%;">
        <!-- HEADER LOGO -->
        <div style="text-align: center; margin-bottom: 24px;">
            <a href="<?= App::url('index.php?action=gioi-thieu') ?>" style="text-decoration: none; display: inline-block;" title="Cổng Thông tin Trường Đại học Tây Bắc (Sơn La)">
                <div style="width: 80px; height: 80px; margin: 0 auto 14px; border-radius: 50%; overflow: hidden; box-shadow: 0 10px 25px -4px rgba(37, 99, 235, 0.3); border: 3px solid rgba(255,255,255,0.9); background: #ffffff; transition: transform 0.25s ease;">
                    <img src="<?= App::url('pic/Logo-Dai-Hoc-Tay-Bac.png') ?>" alt="Logo ĐH Tây Bắc" onerror="this.onerror=null; this.src='<?= App::url('public/images/Logo-Dai-Hoc-Tay-Bac.png') ?>';" style="width: 100%; height: 100%; object-fit: cover; display: block;">
                </div>
            </a>
            <h1 style="font-size: 24px; font-weight: 800; color: var(--text-primary); letter-spacing: -0.02em; margin-bottom: 4px;">
                TRƯỜNG ĐẠI HỌC TÂY BẮC
            </h1>
            <div style="font-size: 15px; font-weight: 800; color: var(--accent-blue); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 6px;">
                ĐĂNG KÝ TÀI KHOẢN MỚI
            </div>
            <p style="color: var(--text-secondary); font-size: 13.5px; margin-bottom: 10px;">
                Cổng Thông Tin NCKH Sinh Viên &bull; Trường Đại học Tây Bắc (Sơn La)
            </p>
            <div style="display: flex; gap: 8px; justify-content: center; flex-wrap: wrap;">
                <a href="<?= App::url('index.php?action=gioi-thieu#cua-so-web') ?>" class="btn btn-sm btn-secondary" style="display: inline-flex; align-items: center; gap: 6px; font-size: 12px; padding: 5px 14px; border-radius: 20px;">
                    <?= ViewHelper::icon('globe', '', 13) ?> Cửa Sổ utb.edu.vn ↗
                </a>
                <a href="https://utb.edu.vn/gioi-thieu/" target="_blank" rel="noopener" class="btn btn-sm btn-secondary" style="display: inline-flex; align-items: center; gap: 6px; font-size: 12px; padding: 5px 14px; border-radius: 20px;">
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

        <!-- FORM CARD -->
        <div class="card" style="padding: 32px; background: var(--bg-card); box-shadow: var(--shadow-lg); border-color: var(--border-color);">
            <form method="POST" action="<?= App::url('index.php?action=register') ?>" id="register-form">
                
                <!-- BƯỚC 1: CHỌN VAI TRÒ -->
                <div class="form-group" style="margin-bottom: 24px;">
                    <label class="form-label" style="font-size: 13px; font-weight: 700; color: var(--text-primary);">
                        1. Bạn là: <span class="required">*</span>
                    </label>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                        <label class="role-selector-card <?= $selectedRole === App::ROLE_SINH_VIEN ? 'active' : '' ?>" style="display: flex; align-items: center; gap: 12px; padding: 14px; border: 2px solid <?= $selectedRole === App::ROLE_SINH_VIEN ? 'var(--accent-blue)' : 'var(--border-color)' ?>; border-radius: var(--radius-md); cursor: pointer; transition: var(--transition);">
                            <input type="radio" name="vaitro" id="role-sinhvien" value="<?= App::ROLE_SINH_VIEN ?>" <?= $selectedRole === App::ROLE_SINH_VIEN ? 'checked' : '' ?> style="accent-color: var(--accent-blue); width: 18px; height: 18px;" onchange="handleRoleChange(this.value)">
                            <div>
                                <div style="font-weight: 700; color: var(--text-primary); font-size: 14px;">Sinh Viên Nghiên Cứu</div>
                                <div style="font-size: 12px; color: var(--text-muted);">Đăng ký, thực hiện đề tài & nộp báo cáo</div>
                            </div>
                        </label>

                        <label class="role-selector-card <?= $selectedRole === App::ROLE_GIANG_VIEN ? 'active' : '' ?>" style="display: flex; align-items: center; gap: 12px; padding: 14px; border: 2px solid <?= $selectedRole === App::ROLE_GIANG_VIEN ? 'var(--accent-blue)' : 'var(--border-color)' ?>; border-radius: var(--radius-md); cursor: pointer; transition: var(--transition);">
                            <input type="radio" name="vaitro" id="role-giangvien" value="<?= App::ROLE_GIANG_VIEN ?>" <?= $selectedRole === App::ROLE_GIANG_VIEN ? 'checked' : '' ?> style="accent-color: var(--accent-blue); width: 18px; height: 18px;" onchange="handleRoleChange(this.value)">
                            <div>
                                <div style="font-weight: 700; color: var(--text-primary); font-size: 14px;">Giảng Viên Hướng Dẫn</div>
                                <div style="font-size: 12px; color: var(--text-muted);">Duyệt hồ sơ, hướng dẫn & đánh giá tiến độ</div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- BƯỚC 2: THÔNG TIN TÀI KHOẢN CƠ BẢN -->
                <div style="font-size: 12px; font-weight: 700; color: var(--accent-blue); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 12px;">
                    2. Thông tin tài khoản đăng nhập
                </div>

                <div class="grid-cols-2" style="gap: 14px; margin-bottom: 14px;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label" id="label-username">
                            Mã Sinh Viên / Tên đăng nhập <span class="required">*</span>
                        </label>
                        <input type="text" name="username" id="input-username" class="form-control" placeholder="VD: SV010" value="<?= ViewHelper::e($formData['username'] ?? '') ?>" required maxlength="20" style="text-transform: uppercase;">
                        <small style="font-size: 11px; color: var(--text-muted); display: block; margin-top: 4px;">Từ 3-20 ký tự (Chữ hoa, số, gạch dưới)</small>
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Họ và Tên Đầy Đủ <span class="required">*</span></label>
                        <input type="text" name="hoten" class="form-control" placeholder="VD: Nguyễn Văn An" value="<?= ViewHelper::e($formData['hoten'] ?? '') ?>" required maxlength="100">
                    </div>
                </div>

                <div class="grid-cols-2" style="gap: 14px; margin-bottom: 18px;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Email Liên Hệ <span class="required">*</span></label>
                        <input type="email" name="email" class="form-control" placeholder="VD: an.nv@school.edu.vn" value="<?= ViewHelper::e($formData['email'] ?? '') ?>" required maxlength="100">
                        <small style="font-size: 11px; color: var(--text-muted); display: block; margin-top: 4px;">Dùng để nhận thông báo xét duyệt đề tài</small>
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Số Điện Thoại</label>
                        <input type="text" name="sdt" class="form-control" placeholder="VD: 0355xxxxxx" value="<?= ViewHelper::e($formData['sdt'] ?? '') ?>" maxlength="15">
                    </div>
                </div>

                <!-- BƯỚC 3: THÔNG TIN THEO VAI TRÒ (DYNAMIC) -->
                <div id="student-fields" style="display: <?= $selectedRole === App::ROLE_SINH_VIEN ? 'block' : 'none' ?>; background: var(--bg-secondary); padding: 16px; border-radius: var(--radius-md); margin-bottom: 20px; border: 1px solid var(--border-color);">
                    <div style="font-size: 12px; font-weight: 700; color: var(--accent-emerald); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 12px; display: flex; align-items: center; gap: 6px;">
                        <?= ViewHelper::icon('book', '', 14) ?> Thông tin học tập của Sinh viên
                    </div>
                    <div class="grid-cols-2" style="gap: 12px; margin-bottom: 12px;">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Lớp Sinh Hoạt <span class="required">*</span></label>
                            <input type="text" name="sv_lop" class="form-control" placeholder="VD: K65 CNTT 1" value="<?= ViewHelper::e($formData['sv_lop'] ?? 'K65 CNTT 1') ?>">
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Khóa Học <span class="required">*</span></label>
                            <input type="text" name="sv_khoahoc" class="form-control" placeholder="VD: 2024 - 2028" value="<?= ViewHelper::e($formData['sv_khoahoc'] ?? '2024 - 2028') ?>">
                        </div>
                    </div>
                    <div class="grid-cols-2" style="gap: 12px;">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Khoa Quản Lý <span class="required">*</span></label>
                            <input type="text" name="sv_khoa" class="form-control" placeholder="VD: Khoa Công nghệ Thông tin" value="<?= ViewHelper::e($formData['sv_khoa'] ?? 'Khoa Công nghệ Thông tin') ?>">
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Ngành Học <span class="required">*</span></label>
                            <input type="text" name="sv_nganh" class="form-control" placeholder="VD: Kỹ thuật Phần mềm" value="<?= ViewHelper::e($formData['sv_nganh'] ?? 'Kỹ thuật Phần mềm') ?>">
                        </div>
                    </div>
                </div>

                <div id="teacher-fields" style="display: <?= $selectedRole === App::ROLE_GIANG_VIEN ? 'block' : 'none' ?>; background: var(--bg-secondary); padding: 16px; border-radius: var(--radius-md); margin-bottom: 20px; border: 1px solid var(--border-color);">
                    <div style="font-size: 12px; font-weight: 700; color: var(--accent-purple); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 12px; display: flex; align-items: center; gap: 6px;">
                        <?= ViewHelper::icon('user-check', '', 14) ?> Thông tin công tác của Giảng viên
                    </div>
                    <div class="grid-cols-2" style="gap: 12px; margin-bottom: 12px;">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Học hàm</label>
                            <select name="gv_hocham" class="form-control">
                                <option value="" <?= empty($formData['gv_hocham']) ? 'selected' : '' ?>>Không có (Chưa phong)</option>
                                <option value="Phó Giáo sư" <?= ($formData['gv_hocham'] ?? '') === 'Phó Giáo sư' ? 'selected' : '' ?>>Phó Giáo sư (PGS)</option>
                                <option value="Giáo sư" <?= ($formData['gv_hocham'] ?? '') === 'Giáo sư' ? 'selected' : '' ?>>Giáo sư (GS)</option>
                            </select>
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Học vị <span class="required">*</span></label>
                            <select name="gv_hocvi" class="form-control">
                                <option value="Thạc sĩ" <?= ($formData['gv_hocvi'] ?? '') === 'Thạc sĩ' ? 'selected' : '' ?>>Thạc sĩ (ThS)</option>
                                <option value="Tiến sĩ" <?= ($formData['gv_hocvi'] ?? '') === 'Tiến sĩ' ? 'selected' : '' ?>>Tiến sĩ (TS)</option>
                                <option value="Tiến sĩ Khoa học" <?= ($formData['gv_hocvi'] ?? '') === 'Tiến sĩ Khoa học' ? 'selected' : '' ?>>Tiến sĩ Khoa học (TSKH)</option>
                                <option value="Cử nhân" <?= ($formData['gv_hocvi'] ?? '') === 'Cử nhân' ? 'selected' : '' ?>>Cử nhân / Kỹ sư</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group" style="margin-bottom: 12px;">
                        <label class="form-label">Đơn vị công tác / Bộ môn <span class="required">*</span></label>
                        <input type="text" name="gv_donvicongtac" class="form-control" placeholder="VD: Bộ môn Trí tuệ Nhân tạo - Khoa CNTT" value="<?= ViewHelper::e($formData['gv_donvicongtac'] ?? 'Khoa Công nghệ Thông tin') ?>">
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Lĩnh vực nghiên cứu chính</label>
                        <input type="text" name="gv_linhvuc" class="form-control" placeholder="VD: Trí tuệ Nhân tạo, Học máy, Xử lý ảnh..." value="<?= ViewHelper::e($formData['gv_linhvuc'] ?? '') ?>">
                    </div>
                </div>

                <!-- BƯỚC 4: MẬT KHẨU -->
                <div style="font-size: 12px; font-weight: 700; color: var(--accent-blue); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 12px;">
                    3. Thiết lập mật khẩu bảo mật
                </div>

                <div class="grid-cols-2" style="gap: 14px; margin-bottom: 24px;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Mật Khẩu <span class="required">*</span></label>
                        <input type="password" name="password" class="form-control" placeholder="Tối thiểu 6 ký tự" minlength="6" required>
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Xác Nhận Mật Khẩu <span class="required">*</span></label>
                        <input type="password" name="confirm_password" class="form-control" placeholder="Nhập lại mật khẩu" minlength="6" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-lg" style="width: 100%; font-size: 15px; font-weight: 700; padding: 13px;">
                    <?= ViewHelper::icon('check-circle', '', 18) ?> Hoàn Tất Đăng Ký Tài Khoản
                </button>
            </form>

            <div style="margin-top: 24px; padding-top: 18px; border-top: 1px solid var(--border-color); text-align: center; font-size: 14px; color: var(--text-secondary);">
                Đã có tài khoản hệ thống?
                <a href="<?= App::url('index.php?action=login') ?>" style="font-weight: 700; color: var(--accent-blue); margin-left: 4px;">
                    Đăng nhập tại đây &rarr;
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function handleRoleChange(role) {
    const studentFields = document.getElementById('student-fields');
    const teacherFields = document.getElementById('teacher-fields');
    const labelUsername = document.getElementById('label-username');
    const inputUsername = document.getElementById('input-username');
    const cards = document.querySelectorAll('.role-selector-card');

    cards.forEach(c => {
        c.style.borderColor = 'var(--border-color)';
    });

    if (role === 'SINH_VIEN') {
        studentFields.style.display = 'block';
        teacherFields.style.display = 'none';
        labelUsername.innerHTML = 'Mã Sinh Viên / Tên đăng nhập <span class="required">*</span>';
        inputUsername.placeholder = 'VD: SV010';
        cards[0].style.borderColor = 'var(--accent-blue)';
    } else {
        studentFields.style.display = 'none';
        teacherFields.style.display = 'block';
        labelUsername.innerHTML = 'Mã Giảng Viên / Tên đăng nhập <span class="required">*</span>';
        inputUsername.placeholder = 'VD: GV010';
        cards[1].style.borderColor = 'var(--accent-blue)';
    }
}
</script>

<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>

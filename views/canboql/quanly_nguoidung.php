<?php
use App\Config\App;
use App\Helpers\ViewHelper;
use App\Helpers\AuthHelper;

$pageTitle = "Quản Lý Người Dùng & Phân Quyền";
require dirname(__DIR__) . '/layouts/header.php';

$currentRoleFilter = $_GET['role'] ?? '';
$currentStatusFilter = $_GET['status'] ?? '';
$currentSearch = $_GET['search'] ?? '';
?>

<!-- HEADER VÀ NÚT TÁC VỤ -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 14px;">
    <div>
        <h2 style="font-size: 20px; font-weight: 800; color: var(--text-primary); margin: 0 0 4px 0;">
            Quản Lý Tài Khoản Người Dùng (<?= count($users) ?>)
        </h2>
        <p style="font-size: 13px; color: var(--text-secondary); margin: 0;">
            Quản lý tài khoản sinh viên, giảng viên hướng dẫn và cán bộ quản lý trong toàn hệ thống.
        </p>
    </div>
    <div style="display: flex; gap: 10px;">
        <button type="button" class="btn btn-primary" onclick="openModal('modal-add-user')">
            <?= ViewHelper::icon('plus', '', 16) ?> Thêm Tài Khoản Mới
        </button>
    </div>
</div>

<!-- THANH LỌC VÀ TÌM KIẾM -->
<div class="card" style="margin-bottom: 24px; padding: 18px;">
    <form method="GET" action="<?= App::url('index.php') ?>" style="display: flex; gap: 14px; flex-wrap: wrap; align-items: center;">
        <input type="hidden" name="action" value="cb-quanly-nguoidung">

        <div style="flex: 1; min-width: 240px; position: relative;">
            <input type="text" name="search" class="form-control" placeholder="Tìm theo Mã người dùng, Họ tên, Email, SĐT..." value="<?= ViewHelper::e($currentSearch) ?>">
        </div>

        <div style="width: 200px;">
            <select name="role" class="form-control" onchange="this.form.submit()">
                <option value="">-- Tất cả vai trò --</option>
                <option value="<?= App::ROLE_SINH_VIEN ?>" <?= $currentRoleFilter === App::ROLE_SINH_VIEN ? 'selected' : '' ?>>Sinh Viên</option>
                <option value="<?= App::ROLE_GIANG_VIEN ?>" <?= $currentRoleFilter === App::ROLE_GIANG_VIEN ? 'selected' : '' ?>>Giảng Viên</option>
                <option value="<?= App::ROLE_CAN_BO_QL ?>" <?= $currentRoleFilter === App::ROLE_CAN_BO_QL ? 'selected' : '' ?>>Cán Bộ Quản Lý</option>
                <option value="<?= App::ROLE_ADMIN ?>" <?= $currentRoleFilter === App::ROLE_ADMIN ? 'selected' : '' ?>>Quản Trị Viên (Admin)</option>
            </select>
        </div>

        <div style="width: 180px;">
            <select name="status" class="form-control" onchange="this.form.submit()">
                <option value="">-- Trạng thái --</option>
                <option value="HOAT_DONG" <?= $currentStatusFilter === 'HOAT_DONG' ? 'selected' : '' ?>>Đang Hoạt Động</option>
                <option value="KHOA" <?= $currentStatusFilter === 'KHOA' ? 'selected' : '' ?>>Đã Bị Khóa</option>
            </select>
        </div>

        <button type="submit" class="btn btn-secondary">
            <?= ViewHelper::icon('search', '', 15) ?> Lọc
        </button>

        <?php if ($currentSearch || $currentRoleFilter || $currentStatusFilter): ?>
            <a href="<?= App::url('index.php?action=cb-quanly-nguoidung') ?>" class="btn btn-sm btn-secondary" title="Đặt lại bộ lọc">
                <?= ViewHelper::icon('refresh', '', 14) ?> Đặt lại
            </a>
        <?php endif; ?>
    </form>
</div>

<!-- BẢNG DANH SÁCH NGƯỜI DÙNG -->
<div class="card">
    <div class="card-body" style="padding: 0;">
        <?php if (empty($users)): ?>
            <div style="padding: 48px; text-align: center; color: var(--text-muted);">
                <div style="margin-bottom: 12px;"><?= ViewHelper::icon('users', '', 44) ?></div>
                <div style="font-size: 16px; font-weight: 700; color: var(--text-primary);">Không tìm thấy người dùng phù hợp</div>
                <p style="font-size: 13px; margin-top: 6px;">Thử thay đổi từ khóa tìm kiếm hoặc bỏ chọn các điều kiện lọc.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Mã Người Dùng</th>
                            <th>Họ và Tên</th>
                            <th>Vai Trò</th>
                            <th>Email / Điện Thoại</th>
                            <th>Đơn Vị / Chuyên Ngành</th>
                            <th>Trạng Thái</th>
                            <th style="text-align: right;">Hành Động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $u): ?>
                            <tr>
                                <td>
                                    <strong style="color: var(--accent-blue); font-size: 13px;">
                                        <?= ViewHelper::e($u['MaNguoiDung']) ?>
                                    </strong>
                                </td>
                                <td>
                                    <div style="font-weight: 700; color: var(--text-primary);">
                                        <?= ViewHelper::e($u['HoTen']) ?>
                                    </div>
                                    <div style="font-size: 11px; color: var(--text-muted);">
                                        Ngày tạo: <?= ViewHelper::date($u['NgayTao']) ?>
                                    </div>
                                </td>
                                <td>
                                    <?= ViewHelper::roleTitle($u['VaiTro']) ?>
                                </td>
                                <td>
                                    <div style="font-size: 13px;"><?= ViewHelper::e($u['Email']) ?></div>
                                    <?php if (!empty($u['SoDienThoai'])): ?>
                                        <div style="font-size: 12px; color: var(--text-muted);"><?= ViewHelper::e($u['SoDienThoai']) ?></div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($u['VaiTro'] === App::ROLE_SINH_VIEN): ?>
                                        <div style="font-size: 13px; font-weight: 600; color: var(--text-primary);"><?= ViewHelper::e($u['Lop'] ?? '-') ?></div>
                                        <div style="font-size: 11px; color: var(--text-muted);"><?= ViewHelper::e($u['NganhHoc'] ?? '') ?></div>
                                    <?php elseif ($u['VaiTro'] === App::ROLE_GIANG_VIEN): ?>
                                        <div style="font-size: 13px; font-weight: 600; color: var(--text-primary);">
                                            <?= ViewHelper::e(($u['HocVi'] ? $u['HocVi'] . ' ' : '') . ($u['DonViCongTac'] ?? '')) ?>
                                        </div>
                                    <?php else: ?>
                                        <div style="font-size: 13px; font-weight: 600; color: var(--text-primary);"><?= ViewHelper::e($u['PhongBan'] ?? '-') ?></div>
                                        <div style="font-size: 11px; color: var(--text-muted);"><?= ViewHelper::e($u['ChucVu'] ?? '') ?></div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?= ViewHelper::statusBadge($u['TrangThai']) ?>
                                </td>
                                <td style="text-align: right; white-space: nowrap;">
                                    <div style="display: flex; gap: 6px; justify-content: flex-end; align-items: center;">
                                        <!-- Nút Sửa -->
                                        <button type="button" class="btn btn-sm btn-secondary" onclick='openEditModal(<?= json_encode($u, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP) ?>)' title="Chỉnh sửa thông tin">
                                            <?= ViewHelper::icon('edit', '', 14) ?> Sửa
                                        </button>

                                        <!-- Nút Khóa / Mở Khóa -->
                                        <?php if ($u['MaNguoiDung'] !== 'ADMIN'): ?>
                                            <form method="POST" action="<?= App::url('index.php?action=cb-quanly-nguoidung') ?>" style="display: inline;" onsubmit="return confirm('Bạn có chắc muốn đổi trạng thái người dùng <?= ViewHelper::e($u['MaNguoiDung']) ?>?')">
                                                <input type="hidden" name="sub_action" value="toggle_status">
                                                <input type="hidden" name="user_id" value="<?= ViewHelper::e($u['MaNguoiDung']) ?>">
                                                <button type="submit" class="btn btn-sm <?= $u['TrangThai'] === 'HOAT_DONG' ? 'btn-secondary' : 'btn-success' ?>" title="<?= $u['TrangThai'] === 'HOAT_DONG' ? 'Khóa tài khoản' : 'Mở khóa tài khoản' ?>">
                                                    <?= $u['TrangThai'] === 'HOAT_DONG' ? ViewHelper::icon('lock', '', 13) : ViewHelper::icon('check', '', 13) ?>
                                                </button>
                                            </form>
                                        <?php endif; ?>

                                        <!-- Nút Xóa -->
                                        <?php if ($u['MaNguoiDung'] !== 'ADMIN'): ?>
                                            <form method="POST" action="<?= App::url('index.php?action=cb-quanly-nguoidung') ?>" style="display: inline;" onsubmit="return confirm('CẢNH BÁO: Bạn có chắc chắn muốn XÓA vĩnh viễn tài khoản <?= ViewHelper::e($u['MaNguoiDung']) ?> - <?= ViewHelper::e($u['HoTen']) ?>? Thao tác này không thể hoàn tác!')">
                                                <input type="hidden" name="sub_action" value="delete_user">
                                                <input type="hidden" name="user_id" value="<?= ViewHelper::e($u['MaNguoiDung']) ?>">
                                                <button type="submit" class="btn btn-sm btn-danger" title="Xóa người dùng">
                                                    <?= ViewHelper::icon('trash', '', 14) ?>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- MODAL THÊM NGƯỜI DÙNG MỚI -->
<div id="modal-add-user" class="modal-backdrop">
    <div class="modal-dialog" style="max-width: 600px;">
        <div class="modal-header">
            <h3 class="modal-title"><?= ViewHelper::icon('plus', '', 18) ?> Thêm Tài Khoản Người Dùng Mới</h3>
            <button type="button" class="btn-close" onclick="closeModal('modal-add-user')">&times;</button>
        </div>
        <form method="POST" action="<?= App::url('index.php?action=cb-quanly-nguoidung') ?>">
            <input type="hidden" name="sub_action" value="create_user">
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Vai Trò Hệ Thống <span class="required">*</span></label>
                    <select name="vaitro" id="add-user-role" class="form-control" onchange="toggleAddRoleFields(this.value)" required>
                        <option value="<?= App::ROLE_SINH_VIEN ?>">Sinh Viên Nghiên Cứu</option>
                        <option value="<?= App::ROLE_GIANG_VIEN ?>">Giảng Viên Hướng Dẫn</option>
                        <option value="<?= App::ROLE_CAN_BO_QL ?>">Cán Bộ Quản Lý NCKH</option>
                    </select>
                </div>

                <div class="grid-cols-2" style="gap: 12px;">
                    <div class="form-group">
                        <label class="form-label">Mã Định Danh (Tên đăng nhập) <span class="required">*</span></label>
                        <input type="text" name="username" class="form-control" placeholder="VD: SV010, GV010" required maxlength="20" style="text-transform: uppercase;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Họ và Tên <span class="required">*</span></label>
                        <input type="text" name="hoten" class="form-control" placeholder="VD: Nguyễn Văn Nam" required maxlength="100">
                    </div>
                </div>

                <div class="grid-cols-2" style="gap: 12px;">
                    <div class="form-group">
                        <label class="form-label">Email <span class="required">*</span></label>
                        <input type="email" name="email" class="form-control" placeholder="nam.nv@school.edu.vn" required maxlength="100">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Số Điện Thoại</label>
                        <input type="text" name="sdt" class="form-control" placeholder="09xxxxxxxx" maxlength="15">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Mật Khẩu Khởi Tạo</label>
                    <input type="password" name="password" class="form-control" value="123456" required>
                    <small style="font-size: 11px; color: var(--text-muted);">Mặc định: 123456</small>
                </div>

                <!-- Fields cho Sinh Viên -->
                <div id="add-sv-fields" style="background: var(--bg-secondary); padding: 14px; border-radius: var(--radius-md); margin-bottom: 14px;">
                    <div style="font-size: 12px; font-weight: 700; color: var(--accent-blue); margin-bottom: 8px;">Thông Tin Sinh Viên</div>
                    <div class="grid-cols-2" style="gap: 10px;">
                        <div class="form-group" style="margin-bottom: 6px;">
                            <label class="form-label" style="font-size: 12px;">Lớp</label>
                            <input type="text" name="sv_lop" class="form-control" value="K64 CNTT 1">
                        </div>
                        <div class="form-group" style="margin-bottom: 6px;">
                            <label class="form-label" style="font-size: 12px;">Khóa học</label>
                            <input type="text" name="sv_khoahoc" class="form-control" value="2023 - 2027">
                        </div>
                    </div>
                    <div class="grid-cols-2" style="gap: 10px;">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label" style="font-size: 12px;">Khoa</label>
                            <input type="text" name="sv_khoa" class="form-control" value="Khoa Công nghệ Thông tin">
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label" style="font-size: 12px;">Ngành</label>
                            <input type="text" name="sv_nganh" class="form-control" value="Khoa học Máy tính">
                        </div>
                    </div>
                </div>

                <!-- Fields cho Giảng Viên -->
                <div id="add-gv-fields" style="display: none; background: var(--bg-secondary); padding: 14px; border-radius: var(--radius-md); margin-bottom: 14px;">
                    <div style="font-size: 12px; font-weight: 700; color: var(--accent-purple); margin-bottom: 8px;">Thông Tin Giảng Viên</div>
                    <div class="grid-cols-2" style="gap: 10px;">
                        <div class="form-group" style="margin-bottom: 6px;">
                            <label class="form-label" style="font-size: 12px;">Học hàm</label>
                            <select name="gv_hocham" class="form-control">
                                <option value="">Không có</option>
                                <option value="Phó Giáo sư">Phó Giáo sư</option>
                                <option value="Giáo sư">Giáo sư</option>
                            </select>
                        </div>
                        <div class="form-group" style="margin-bottom: 6px;">
                            <label class="form-label" style="font-size: 12px;">Học vị</label>
                            <select name="gv_hocvi" class="form-control">
                                <option value="Thạc sĩ">Thạc sĩ</option>
                                <option value="Tiến sĩ" selected>Tiến sĩ</option>
                                <option value="Cử nhân">Cử nhân / Kỹ sư</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label" style="font-size: 12px;">Đơn vị công tác</label>
                        <input type="text" name="gv_donvicongtac" class="form-control" value="Khoa Công nghệ Thông tin">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modal-add-user')">Hủy</button>
                <button type="submit" class="btn btn-primary">Thêm Người Dùng</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL CHỈNH SỬA NGƯỜI DÙNG -->
<div id="modal-edit-user" class="modal-backdrop">
    <div class="modal-dialog" style="max-width: 600px;">
        <div class="modal-header">
            <h3 class="modal-title"><?= ViewHelper::icon('edit', '', 18) ?> Chỉnh Sửa Thông Tin Người Dùng</h3>
            <button type="button" class="btn-close" onclick="closeModal('modal-edit-user')">&times;</button>
        </div>
        <form method="POST" action="<?= App::url('index.php?action=cb-quanly-nguoidung') ?>">
            <input type="hidden" name="sub_action" value="update_user">
            <input type="hidden" name="user_id" id="edit-user-id">

            <div class="modal-body">
                <div class="grid-cols-2" style="gap: 12px;">
                    <div class="form-group">
                        <label class="form-label">Mã Người Dùng</label>
                        <input type="text" id="edit-display-id" class="form-control" readonly disabled style="opacity: 0.75; background: var(--bg-secondary);">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Trạng Thái Tài Khoản <span class="required">*</span></label>
                        <select name="trangthai" id="edit-trangthai" class="form-control">
                            <option value="HOAT_DONG">Đang Hoạt Động (Bình thường)</option>
                            <option value="KHOA">Đã Khóa (Chặn đăng nhập)</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Họ và Tên <span class="required">*</span></label>
                    <input type="text" name="hoten" id="edit-hoten" class="form-control" required>
                </div>

                <div class="grid-cols-2" style="gap: 12px;">
                    <div class="form-group">
                        <label class="form-label">Email <span class="required">*</span></label>
                        <input type="email" name="email" id="edit-email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Số Điện Thoại</label>
                        <input type="text" name="sdt" id="edit-sdt" class="form-control">
                    </div>
                </div>

                <!-- Sinh Viên -->
                <div id="edit-sv-container" style="display: none; background: var(--bg-secondary); padding: 14px; border-radius: var(--radius-md); margin-bottom: 14px;">
                    <div style="font-size: 12px; font-weight: 700; color: var(--accent-blue); margin-bottom: 8px;">Chuyên ngành Sinh Viên</div>
                    <div class="grid-cols-2" style="gap: 10px;">
                        <div class="form-group" style="margin-bottom: 6px;">
                            <label class="form-label" style="font-size: 12px;">Lớp</label>
                            <input type="text" name="sv_lop" id="edit-sv-lop" class="form-control">
                        </div>
                        <div class="form-group" style="margin-bottom: 6px;">
                            <label class="form-label" style="font-size: 12px;">Khóa học</label>
                            <input type="text" name="sv_khoahoc" id="edit-sv-khoahoc" class="form-control">
                        </div>
                    </div>
                    <div class="grid-cols-2" style="gap: 10px;">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label" style="font-size: 12px;">Khoa</label>
                            <input type="text" name="sv_khoa" id="edit-sv-khoa" class="form-control">
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label" style="font-size: 12px;">Ngành</label>
                            <input type="text" name="sv_nganh" id="edit-sv-nganh" class="form-control">
                        </div>
                    </div>
                </div>

                <!-- Giảng Viên -->
                <div id="edit-gv-container" style="display: none; background: var(--bg-secondary); padding: 14px; border-radius: var(--radius-md); margin-bottom: 14px;">
                    <div style="font-size: 12px; font-weight: 700; color: var(--accent-purple); margin-bottom: 8px;">Thông tin Giảng Viên</div>
                    <div class="grid-cols-2" style="gap: 10px;">
                        <div class="form-group" style="margin-bottom: 6px;">
                            <label class="form-label" style="font-size: 12px;">Học hàm</label>
                            <select name="gv_hocham" id="edit-gv-hocham" class="form-control">
                                <option value="">Không có</option>
                                <option value="Phó Giáo sư">Phó Giáo sư</option>
                                <option value="Giáo sư">Giáo sư</option>
                            </select>
                        </div>
                        <div class="form-group" style="margin-bottom: 6px;">
                            <label class="form-label" style="font-size: 12px;">Học vị</label>
                            <select name="gv_hocvi" id="edit-gv-hocvi" class="form-control">
                                <option value="Thạc sĩ">Thạc sĩ</option>
                                <option value="Tiến sĩ">Tiến sĩ</option>
                                <option value="Cử nhân">Cử nhân / Kỹ sư</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label" style="font-size: 12px;">Đơn vị công tác</label>
                        <input type="text" name="gv_donvicongtac" id="edit-gv-donvicongtac" class="form-control">
                    </div>
                </div>

                <!-- Đổi mật khẩu nếu muốn -->
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Đặt lại mật khẩu mới (Bỏ trống nếu không đổi)</label>
                    <input type="password" name="new_password" class="form-control" placeholder="Để trống nếu giữ nguyên mật khẩu cũ">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modal-edit-user')">Hủy</button>
                <button type="submit" class="btn btn-primary">Lưu Thay Đổi</button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleAddRoleFields(role) {
    const svBox = document.getElementById('add-sv-fields');
    const gvBox = document.getElementById('add-gv-fields');
    if (role === 'SINH_VIEN') {
        svBox.style.display = 'block';
        gvBox.style.display = 'none';
    } else if (role === 'GIANG_VIEN') {
        svBox.style.display = 'none';
        gvBox.style.display = 'block';
    } else {
        svBox.style.display = 'none';
        gvBox.style.display = 'none';
    }
}

function openEditModal(user) {
    document.getElementById('edit-user-id').value = user.MaNguoiDung;
    document.getElementById('edit-display-id').value = user.MaNguoiDung + ' (' + user.VaiTro + ')';
    document.getElementById('edit-hoten').value = user.HoTen || '';
    document.getElementById('edit-email').value = user.Email || '';
    document.getElementById('edit-sdt').value = user.SoDienThoai || '';
    document.getElementById('edit-trangthai').value = user.TrangThai || 'HOAT_DONG';

    const svBox = document.getElementById('edit-sv-container');
    const gvBox = document.getElementById('edit-gv-container');

    if (user.VaiTro === 'SINH_VIEN') {
        svBox.style.display = 'block';
        gvBox.style.display = 'none';
        document.getElementById('edit-sv-lop').value = user.Lop || '';
        document.getElementById('edit-sv-khoahoc').value = user.KhoaHoc || '';
        document.getElementById('edit-sv-khoa').value = user.KhoaSV || '';
        document.getElementById('edit-sv-nganh').value = user.NganhHoc || '';
    } else if (user.VaiTro === 'GIANG_VIEN') {
        svBox.style.display = 'none';
        gvBox.style.display = 'block';
        document.getElementById('edit-gv-hocham').value = user.HocHam || '';
        document.getElementById('edit-gv-hocvi').value = user.HocVi || 'Thạc sĩ';
        document.getElementById('edit-gv-donvicongtac').value = user.DonViCongTac || '';
    } else {
        svBox.style.display = 'none';
        gvBox.style.display = 'none';
    }

    openModal('modal-edit-user');
}
</script>

<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>

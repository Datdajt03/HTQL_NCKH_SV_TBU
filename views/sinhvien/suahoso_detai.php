<?php
use App\Config\App;
use App\Helpers\AuthHelper;
use App\Helpers\ViewHelper;
use App\Helpers\NckhFormHelper;

$pageTitle = "Chỉnh Sửa Hồ Sơ Đề Tài Trực Tuyến";
require dirname(__DIR__) . '/layouts/header.php';

$deXuatData = $deXuatData ?? NckhFormHelper::parseDeXuatData($topic['DuLieuDeXuat'] ?? null, $topic);
$thuyetMinhData = $thuyetMinhData ?? NckhFormHelper::parseThuyetMinhData($topic['DuLieuThuyetMinh'] ?? null, $topic);

$studentId = $studentId ?? AuthHelper::id();
$userHoTen = $user['HoTen'] ?? ($user['name'] ?? AuthHelper::name());
$userPhone = $user['SoDienThoai'] ?? ($user['phone'] ?? '');
$userLop   = $user['extra']['Lop'] ?? ($user['Lop'] ?? 'K64 ĐH CNTT-B');
?>
<div style="max-width: 1080px; margin: 0 auto; padding-bottom: 60px;">
    <!-- HEADER TIÊU ĐỀ TRANG -->
    <div style="margin-bottom: 24px;">
        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px;">
            <a href="<?= App::url('index.php?action=sv-chitiet-detai&id=' . $topic['MaDeTai']) ?>" class="btn btn-sm btn-secondary" style="padding: 4px 8px;">
                <?= ViewHelper::icon('arrow-left', '', 14) ?> Quay Lại Chi Tiết
            </a>
            <span class="badge badge-amber"><?= ViewHelper::statusBadge($topic['TrangThai']) ?></span>
            <span style="font-size: 13px; color: var(--text-muted);">Mã đề tài: <strong style="color: #60a5fa;"><?= ViewHelper::e($topic['MaDeTai']) ?></strong></span>
        </div>
        <h2 style="font-size: 22px; font-weight: 800; color: var(--text-primary); margin-bottom: 4px; display: flex; align-items: center; gap: 10px;">
            <span><?= ViewHelper::icon('edit', 'text-warning', 24) ?></span>
            <span>Chỉnh Sửa Hồ Sơ Đề Tài & Biểu Mẫu Trực Tuyến</span>
        </h2>
        <p style="color: var(--text-secondary); font-size: 14px; margin: 0;">
            Cập nhật trực tiếp nội dung <strong>Phiếu Đề Xuất (Mẫu 02)</strong> và <strong>Thuyết Minh (Mẫu 01)</strong> theo ý kiến góp ý của GVHD hoặc Cán bộ thẩm định.
        </p>
    </div>

    <!-- Ý KIẾN GÓP Ý TỪ CÁN BỘ / GVHD -->
    <?php if (!empty($topic['NhanXetCanBo']) || !empty($topic['NhanXetGVHD'])): ?>
        <div class="card" style="background: rgba(245, 158, 11, 0.08); border-color: rgba(245, 158, 11, 0.3); margin-bottom: 24px;">
            <div class="card-header">
                <div class="card-title" style="color: #fde68a;">
                    <span><?= ViewHelper::icon('alert-triangle', '', 18) ?></span>
                    <span>Ý Kiến Góp Ý / Yêu Cầu Chỉnh Sửa Cần Tiếp Thu</span>
                </div>
            </div>
            <div class="card-body">
                <?php if (!empty($topic['NhanXetCanBo'])): ?>
                    <div style="margin-bottom: 12px;">
                        <strong style="color: #f59e0b; font-size: 13.5px; display: flex; align-items: center; gap: 6px;">
                            <?= ViewHelper::icon('shield', '', 14) ?> Ý kiến thẩm định từ Cán bộ Quản lý NCKH:
                        </strong>
                        <div style="background: rgba(15, 23, 42, 0.6); padding: 12px 16px; border-radius: var(--radius-md); margin-top: 6px; color: #fff; line-height: 1.6;">
                            <?= nl2br(ViewHelper::e($topic['NhanXetCanBo'])) ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (!empty($topic['NhanXetGVHD'])): ?>
                    <div>
                        <strong style="color: #60a5fa; font-size: 13.5px; display: flex; align-items: center; gap: 6px;">
                            <?= ViewHelper::icon('user', '', 14) ?> Nhận xét từ GVHD (<?= ViewHelper::e($topic['TenGVHD']) ?>):
                        </strong>
                        <div style="background: rgba(15, 23, 42, 0.6); padding: 12px 16px; border-radius: var(--radius-md); margin-top: 6px; color: #fff; line-height: 1.6;">
                            <?= nl2br(ViewHelper::e($topic['NhanXetGVHD'])) ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger" style="margin-bottom: 24px;">
            <span><?= ViewHelper::icon('alert-circle', '', 20) ?></span>
            <div><?= ViewHelper::e($error) ?></div>
        </div>
    <?php endif; ?>

    <!-- TABS ĐIỀU HƯỚNG TRỰC QUAN -->
    <div class="nckh-form-tabs">
        <button type="button" class="nckh-tab-btn active" data-tab="tab-edit-general">
            <?= ViewHelper::icon('file-text', '', 16) ?>
            <span>1. Thông Tin Chung & Giải Trình</span>
        </button>
        <button type="button" class="nckh-tab-btn" data-tab="tab-edit-dexuat">
            <?= ViewHelper::icon('target', '', 16) ?>
            <span>2. Chỉnh Sửa Phiếu Đề Xuất (Mẫu 02)</span>
        </button>
        <button type="button" class="nckh-tab-btn" data-tab="tab-edit-thuyetminh">
            <?= ViewHelper::icon('book-open', '', 16) ?>
            <span>3. Chỉnh Sửa Thuyết Minh (Mẫu 01)</span>
        </button>
        <button type="button" class="nckh-tab-btn" data-tab="tab-edit-files">
            <?= ViewHelper::icon('paperclip', '', 16) ?>
            <span>4. Tệp Đính Kèm Mới (Tùy Chọn)</span>
        </button>
    </div>

    <form method="POST" action="<?= App::url('index.php?action=sv-suahoso-detai&id=' . $topic['MaDeTai']) ?>" enctype="multipart/form-data" id="form-suahoso-detai">
        
        <!-- =================================================================== -->
        <!-- TAB 1: THÔNG TIN CHUNG & GIẢI TRÌNH TIẾP THU SỬA ĐỔI               -->
        <!-- =================================================================== -->
        <div id="tab-edit-general" class="tab-pane-nckh active">
            <!-- GIẢI TRÌNH SỬA ĐỔI BẮT BUỘC -->
            <div class="card" style="border-color: rgba(59, 130, 246, 0.4); margin-bottom: 20px;">
                <div class="card-header" style="background: rgba(59, 130, 246, 0.08);">
                    <div class="card-title" style="color: #60a5fa;">
                        <?= ViewHelper::icon('edit', '', 18) ?>
                        <span>Tóm Tắt Nội Dung Đã Sửa Đổi Theo Góp Ý <span class="required">*</span></span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-group" style="margin-bottom: 0;">
                        <textarea name="noidungsua" class="form-control" rows="4" placeholder="Mô tả cụ thể những nội dung, các mục, số liệu hoặc giải pháp mà nhóm đã tiếp thu và hoàn thiện..." required></textarea>
                        <div class="form-help">Nội dung giải trình này sẽ được lưu vào lịch sử thẩm định để Cán bộ QL đối chiếu với phiên bản trước.</div>
                    </div>
                </div>
            </div>

            <!-- THÔNG TIN CHÍNH -->
            <div class="card" style="margin-bottom: 20px;">
                <div class="card-header">
                    <div class="card-title">
                        <?= ViewHelper::icon('file-text', '', 18) ?>
                        <span>Thông Tin Đề Tài & Phân Loại</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label">Tên Đề Tài NCKH <span class="required">*</span></label>
                        <input type="text" name="tendetai" id="edit-tendetai" class="form-control" value="<?= ViewHelper::e($topic['TenDeTai']) ?>" required>
                        <div class="form-help">Thay đổi tên tại đây sẽ đồng bộ sang cả 2 biểu mẫu trực tuyến.</div>
                    </div>

                    <div class="grid-cols-2">
                        <div class="form-group">
                            <label class="form-label">Hướng Nghiên Cứu <span class="required">*</span></label>
                            <select name="mahuongnc" class="form-control" required>
                                <?php foreach ($directions as $h): ?>
                                    <option value="<?= ViewHelper::e($h['MaHuongNC']) ?>" <?= $topic['MaHuongNC'] === $h['MaHuongNC'] ? 'selected' : '' ?>>
                                        <?= ViewHelper::e($h['TenHuongNC']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Kinh Phí Dự Trù (VNĐ)</label>
                            <input type="number" name="kinhphi" class="form-control" value="<?= (int)$topic['KinhPhiDuKien'] ?>" min="0" step="500000">
                        </div>
                    </div>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 12px;">
                <button type="button" class="btn btn-primary btn-next-tab" data-next="tab-edit-dexuat">
                    <span>Sang Tab 2: Chỉnh Sửa Phiếu Đề Xuất</span>
                    <?= ViewHelper::icon('arrow-right', '', 16) ?>
                </button>
            </div>
        </div>

        <!-- =================================================================== -->
        <!-- TAB 2: CHỈNH SỬA PHIẾU ĐỀ XUẤT (MẪU 02)                           -->
        <!-- =================================================================== -->
        <div id="tab-edit-dexuat" class="tab-pane-nckh" style="display: none;">
            <div class="card" style="margin-bottom: 20px;">
                <div class="card-header" style="background: rgba(59, 130, 246, 0.05);">
                    <div class="card-title" style="color: #60a5fa;">
                        <?= ViewHelper::icon('target', '', 18) ?>
                        <span>PHIẾU ĐỀ XUẤT ĐỀ TÀI NCKH CỦA SINH VIÊN (MẪU 02)</span>
                    </div>
                    <span class="badge badge-info">6 Đề Mục Chuẩn</span>
                </div>
                <div class="card-body">
                    <!-- MỤC 1: TÊN ĐỀ TÀI -->
                    <div class="form-group">
                        <label class="form-label" style="font-size: 15px; font-weight: 700;">1. Tên đề tài: <span class="required">*</span></label>
                        <input type="text" name="dx_ten_de_tai" id="edit-dx-ten-de-tai" class="form-control" value="<?= ViewHelper::e($deXuatData['ten_de_tai'] ?: $topic['TenDeTai']) ?>" required>
                    </div>

                    <!-- MỤC 2: GIẢI TRÌNH VỀ TÍNH CẤP THIẾT -->
                    <div class="form-group">
                        <label class="form-label" style="font-size: 15px; font-weight: 700;">2. Giải trình về tính cấp thiết: <span class="required">*</span></label>
                        <textarea name="dx_tinh_cap_thiet" class="form-control" rows="8"><?= ViewHelper::e($deXuatData['tinh_cap_thiet']) ?></textarea>
                    </div>

                    <!-- MỤC 3: MỤC TIÊU DỰ KIẾN CỦA ĐỀ TÀI -->
                    <div class="form-group">
                        <label class="form-label" style="font-size: 15px; font-weight: 700;">3. Mục tiêu dự kiến của đề tài: <span class="required">*</span></label>
                        <textarea name="dx_muc_tieu" class="form-control" rows="5"><?= ViewHelper::e($deXuatData['muc_tieu'] ?: $topic['MucTieu']) ?></textarea>
                    </div>

                    <!-- MỤC 4: NỘI DUNG CHỦ YẾU CỦA ĐỀ TÀI -->
                    <div class="form-group">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                            <label class="form-label" style="font-size: 15px; font-weight: 700; margin: 0;">4. Nội dung chủ yếu của đề tài: <span class="required">*</span></label>
                            <button type="button" id="btn-edit-add-dx-nd" class="btn btn-sm btn-secondary" style="display: inline-flex; align-items: center; gap: 4px;">
                                <?= ViewHelper::icon('plus', '', 14) ?> Thêm Nội Dung
                            </button>
                        </div>
                        <div id="edit-dx-noidung-container" style="display: flex; flex-direction: column; gap: 14px;">
                            <?php if (!empty($deXuatData['noi_dung_chu_yeu'])): ?>
                                <?php foreach ($deXuatData['noi_dung_chu_yeu'] as $idx => $nd): ?>
                                    <div class="dx-noidung-card" style="background: rgba(15, 23, 42, 0.4); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 14px;">
                                        <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                            <input type="text" name="dx_nd_tieude[]" class="form-control" style="font-weight: 700; max-width: 80%;" value="<?= ViewHelper::e($nd['tieu_de'] ?? '') ?>">
                                            <?php if ($idx > 0): ?>
                                                <button type="button" class="btn btn-sm btn-danger btn-remove-dx-nd" style="padding: 4px 8px;">Xóa</button>
                                            <?php endif; ?>
                                        </div>
                                        <textarea name="dx_nd_chitiet[]" class="form-control" rows="4"><?= ViewHelper::e($nd['chi_tiet'] ?? '') ?></textarea>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="dx-noidung-card" style="background: rgba(15, 23, 42, 0.4); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 14px;">
                                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                        <input type="text" name="dx_nd_tieude[]" class="form-control" style="font-weight: 700; max-width: 80%;" value="4.1. Nội dung 1: Tìm hiểu lý thuyết và khảo sát">
                                    </div>
                                    <textarea name="dx_nd_chitiet[]" class="form-control" rows="4"><?= ViewHelper::e($topic['NoiDungNC']) ?></textarea>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- MỤC 5: DỰ KIẾN SẢN PHẨM CỦA ĐỀ TÀI -->
                    <div class="form-group">
                        <label class="form-label" style="font-size: 15px; font-weight: 700;">5. Dự kiến sản phẩm của đề tài: <span class="required">*</span></label>
                        <textarea name="dx_san_pham" class="form-control" rows="4"><?= ViewHelper::e($deXuatData['du_kien_san_pham'] ?: $topic['SanPhamDuKien']) ?></textarea>
                    </div>

                    <!-- MỤC 6: KHẢ NĂNG VÀ ĐỊA CHỈ ÁP DỤNG -->
                    <div class="card" style="background: rgba(15, 23, 42, 0.4); border-color: rgba(255, 255, 255, 0.08); margin-bottom: 20px;">
                        <div class="card-header">
                            <label class="form-label" style="font-size: 15px; font-weight: 700; margin: 0;">6. Khả năng và địa chỉ áp dụng: <span class="required">*</span></label>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label class="form-label">Đối tượng áp dụng</label>
                                <input type="text" name="dx_doi_tuong" class="form-control" value="<?= ViewHelper::e($deXuatData['kha_nang_dia_chi']['doi_tuong'] ?? '') ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Địa chỉ áp dụng</label>
                                <input type="text" name="dx_dia_chi" class="form-control" value="<?= ViewHelper::e($deXuatData['kha_nang_dia_chi']['dia_chi'] ?? '') ?>">
                            </div>
                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label">Khả năng mở rộng</label>
                                <textarea name="dx_kha_nang_mo_rong" class="form-control" rows="2"><?= ViewHelper::e($deXuatData['kha_nang_dia_chi']['kha_nang_mo_rong'] ?? '') ?></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- CHỮ KÝ XÁC NHẬN -->
                    <div class="grid-cols-2" style="background: rgba(15, 23, 42, 0.5); padding: 16px; border-radius: var(--radius-md);">
                        <div>
                            <label class="form-label" style="font-weight: 700;">Xác nhận của Giảng viên hướng dẫn</label>
                            <input type="text" name="dx_gvhd" class="form-control" value="<?= ViewHelper::e($deXuatData['xac_nhan_gvhd'] ?: $topic['TenGVHD']) ?>">
                        </div>
                        <div>
                            <label class="form-label" style="font-weight: 700;">Cá nhân đề xuất</label>
                            <input type="text" name="dx_sinh_vien" class="form-control" value="<?= ViewHelper::e($deXuatData['ca_nhan_de_xuat'] ?: $userHoTen) ?>">
                        </div>
                    </div>
                </div>
            </div>

            <div style="display: flex; justify-content: space-between; gap: 12px;">
                <button type="button" class="btn btn-secondary btn-prev-tab" data-prev="tab-edit-general">
                    <?= ViewHelper::icon('arrow-left', '', 16) ?> Quay Lại Tab 1
                </button>
                <button type="button" class="btn btn-primary btn-next-tab" data-next="tab-edit-thuyetminh">
                    <span>Sang Tab 3: Chỉnh Sửa Thuyết Minh</span>
                    <?= ViewHelper::icon('arrow-right', '', 16) ?>
                </button>
            </div>
        </div>

        <!-- =================================================================== -->
        <!-- TAB 3: CHỈNH SỬA THUYẾT MINH (MẪU 01 - 13 ĐỀ MỤC)                  -->
        <!-- =================================================================== -->
        <div id="tab-edit-thuyetminh" class="tab-pane-nckh" style="display: none;">
            <div class="card" style="margin-bottom: 20px;">
                <div class="card-header" style="background: rgba(99, 102, 241, 0.05);">
                    <div class="card-title" style="color: #818cf8;">
                        <?= ViewHelper::icon('book-open', '', 18) ?>
                        <span>THUYẾT MINH ĐỀ TÀI NCKH VÀ CÔNG NGHỆ CẤP TRƯỜNG CỦA SINH VIÊN (MẪU 01)</span>
                    </div>
                    <span class="badge badge-primary">13 Đề Mục Chuẩn</span>
                </div>
                <div class="card-body">
                    <!-- Header năm học & nhóm ngành -->
                    <div class="grid-cols-2" style="background: rgba(15, 23, 42, 0.5); padding: 16px; border-radius: var(--radius-md); margin-bottom: 20px;">
                        <div>
                            <div class="form-group" style="margin-bottom: 10px;">
                                <label class="form-label" style="font-size: 12px;">Năm Học Thực Hiện</label>
                                <input type="text" name="tm_nam_hoc" class="form-control" value="<?= ViewHelper::e($thuyetMinhData['nam_hoc']) ?>">
                            </div>
                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label" style="font-size: 12px;">Địa Điểm & Ngày Thuyết Minh</label>
                                <input type="text" name="tm_dia_diem_ngay" class="form-control" value="<?= ViewHelper::e($thuyetMinhData['dia_diem_ngay']) ?>">
                            </div>
                        </div>
                        <div>
                            <div class="form-group" style="margin-bottom: 10px;">
                                <label class="form-label" style="font-size: 12px;">Nhóm Ngành (Mục 3)</label>
                                <input type="text" name="tm_nhom_nganh" class="form-control" value="<?= ViewHelper::e($thuyetMinhData['nhom_nganh']) ?>">
                            </div>
                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label" style="font-size: 12px;">Thời Gian Thực Hiện (Mục 4)</label>
                                <input type="text" name="tm_thoi_gian" class="form-control" value="<?= ViewHelper::e($thuyetMinhData['thoi_gian_thuc_hien']) ?>">
                            </div>
                        </div>
                    </div>

                    <!-- MỤC 1: TÊN ĐỀ TÀI -->
                    <div class="form-group">
                        <label class="form-label" style="font-size: 15px; font-weight: 700;">1. TÊN ĐỀ TÀI: <span class="required">*</span></label>
                        <input type="text" name="tm_ten_de_tai" id="edit-tm-ten-de-tai" class="form-control" value="<?= ViewHelper::e($thuyetMinhData['ten_de_tai'] ?: $topic['TenDeTai']) ?>" required>
                    </div>

                    <!-- MỤC 2: LOẠI HÌNH NGHIÊN CỨU -->
                    <div class="form-group">
                        <label class="form-label" style="font-size: 15px; font-weight: 700;">2. LOẠI HÌNH NGHIÊN CỨU: <span class="required">*</span></label>
                        <div style="display: flex; gap: 20px; align-items: center; padding: 10px 14px; background: rgba(15, 23, 42, 0.4); border-radius: var(--radius-md); border: 1px solid var(--border-color);">
                            <label style="display: inline-flex; align-items: center; gap: 6px; cursor: pointer; color: #fff;">
                                <input type="radio" name="tm_loai_hinh_nc" value="CO_BAN" <?= ($thuyetMinhData['loai_hinh_nc'] ?? '') === 'CO_BAN' ? 'checked' : '' ?>>
                                <span>Cơ Bản</span>
                            </label>
                            <label style="display: inline-flex; align-items: center; gap: 6px; cursor: pointer; color: #fff;">
                                <input type="radio" name="tm_loai_hinh_nc" value="UNG_DUNG" <?= ($thuyetMinhData['loai_hinh_nc'] ?? '') === 'UNG_DUNG' ? 'checked' : '' ?>>
                                <span>Ứng Dụng</span>
                            </label>
                            <label style="display: inline-flex; align-items: center; gap: 6px; cursor: pointer; color: #fff;">
                                <input type="radio" name="tm_loai_hinh_nc" value="TRIEN_KHAI" <?= ($thuyetMinhData['loai_hinh_nc'] ?? '') === 'TRIEN_KHAI' ? 'checked' : '' ?>>
                                <span>Triển Khai</span>
                            </label>
                        </div>
                    </div>

                    <!-- MỤC 5: SINH VIÊN CHỊU TRÁCH NHIỆM CHÍNH -->
                    <div class="card" style="background: rgba(15, 23, 42, 0.4); border-color: rgba(255, 255, 255, 0.08); margin-bottom: 20px;">
                        <div class="card-header">
                            <label class="form-label" style="font-size: 15px; font-weight: 700; margin: 0;">5. SINH VIÊN CHỊU TRÁCH NHIỆM CHÍNH:</label>
                        </div>
                        <div class="card-body">
                            <div style="display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 14px;">
                                <div>
                                    <label class="form-label">Họ và tên</label>
                                    <input type="text" name="tm_cn_hoten" class="form-control" value="<?= ViewHelper::e($thuyetMinhData['chu_nhiem']['ho_ten'] ?? '') ?>">
                                </div>
                                <div>
                                    <label class="form-label">Lớp</label>
                                    <input type="text" name="tm_cn_lop" class="form-control" value="<?= ViewHelper::e($thuyetMinhData['chu_nhiem']['lop'] ?? '') ?>">
                                </div>
                                <div>
                                    <label class="form-label">Điện thoại</label>
                                    <input type="text" name="tm_cn_sdt" class="form-control" value="<?= ViewHelper::e($thuyetMinhData['chu_nhiem']['dien_thoai'] ?? '') ?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- MỤC 6: NHỮNG NGƯỜI THAM GIA THỰC HIỆN ĐỀ TÀI -->
                    <div class="card" style="background: rgba(15, 23, 42, 0.4); border-color: rgba(255, 255, 255, 0.08); margin-bottom: 20px;">
                        <div class="card-header">
                            <label class="form-label" style="font-size: 15px; font-weight: 700; margin: 0;">6. NHỮNG NGƯỜI THAM GIA THỰC HIỆN ĐỀ TÀI:</label>
                            <button type="button" id="btn-edit-add-tm-tv" class="btn btn-sm btn-secondary" style="display: inline-flex; align-items: center; gap: 4px;">
                                <?= ViewHelper::icon('plus', '', 14) ?> Thêm Thành Viên
                            </button>
                        </div>
                        <div class="card-body" style="padding: 0;">
                            <div class="table-responsive">
                                <table class="data-table" id="table-edit-tm-tv">
                                    <thead>
                                        <tr>
                                            <th style="width: 5%;">TT</th>
                                            <th style="width: 25%;">Họ và tên</th>
                                            <th style="width: 20%;">Lớp</th>
                                            <th style="width: 42%;">Nội dung công việc được giao</th>
                                            <th style="width: 8%; text-align: center;">Xóa</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody-edit-tm-tv">
                                        <?php if (!empty($thuyetMinhData['thanh_vien_tham_gia'])): ?>
                                            <?php foreach ($thuyetMinhData['thanh_vien_tham_gia'] as $idx => $tv): ?>
                                                <tr>
                                                    <td style="text-align: center;"><?= $idx + 1 ?></td>
                                                    <td><input type="text" name="tm_tv_hoten[]" class="form-control" value="<?= ViewHelper::e($tv['ho_ten'] ?? '') ?>"></td>
                                                    <td><input type="text" name="tm_tv_lop[]" class="form-control" value="<?= ViewHelper::e($tv['lop'] ?? '') ?>"></td>
                                                    <td><input type="text" name="tm_tv_congviec[]" class="form-control" value="<?= ViewHelper::e($tv['cong_viec'] ?? '') ?>"></td>
                                                    <td style="text-align: center;">
                                                        <?php if ($idx > 0): ?>
                                                            <button type="button" class="btn btn-sm btn-danger btn-remove-row" style="padding: 2px 6px;">x</button>
                                                        <?php else: ?>-<?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td style="text-align: center;">1</td>
                                                <td><input type="text" name="tm_tv_hoten[]" class="form-control" value="<?= ViewHelper::e($userHoTen) ?>"></td>
                                                <td><input type="text" name="tm_tv_lop[]" class="form-control" value="<?= ViewHelper::e($userLop) ?>"></td>
                                                <td><input type="text" name="tm_tv_congviec[]" class="form-control" value="Trưởng nhóm, chủ nhiệm đề tài"></td>
                                                <td style="text-align: center;">-</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- MỤC 7: GIẢNG VIÊN HƯỚNG DẪN -->
                    <div class="card" style="background: rgba(15, 23, 42, 0.4); border-color: rgba(255, 255, 255, 0.08); margin-bottom: 20px;">
                        <div class="card-header">
                            <label class="form-label" style="font-size: 15px; font-weight: 700; margin: 0;">7. GIẢNG VIÊN HƯỚNG DẪN:</label>
                        </div>
                        <div class="card-body">
                            <div style="display: grid; grid-template-columns: 2fr 1.2fr 1fr; gap: 14px;">
                                <div>
                                    <label class="form-label">Họ và tên</label>
                                    <input type="text" name="tm_gv_hoten" class="form-control" value="<?= ViewHelper::e($thuyetMinhData['gvhd']['ho_ten'] ?? $topic['TenGVHD']) ?>">
                                </div>
                                <div>
                                    <label class="form-label">Chức danh KH, học vị</label>
                                    <input type="text" name="tm_gv_hocvi" class="form-control" value="<?= ViewHelper::e($thuyetMinhData['gvhd']['chuc_danh_hoc_vi'] ?? ($topic['HocHam'] . ' ' . $topic['HocVi'])) ?>">
                                </div>
                                <div>
                                    <label class="form-label">Điện thoại liên hệ</label>
                                    <input type="text" name="tm_gv_sdt" class="form-control" value="<?= ViewHelper::e($thuyetMinhData['gvhd']['dien_thoai'] ?? $topic['SdtGVHD']) ?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- MỤC 8: TÌNH HÌNH NGHIÊN CỨU -->
                    <div class="card" style="background: rgba(15, 23, 42, 0.4); border-color: rgba(255, 255, 255, 0.08); margin-bottom: 20px;">
                        <div class="card-header">
                            <label class="form-label" style="font-size: 15px; font-weight: 700; margin: 0;">8. TÌNH HÌNH NGHIÊN CỨU:</label>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label class="form-label" style="font-weight: 600;">8.1. Tổng quan tình hình nghiên cứu thuộc lĩnh vực của đề tài:</label>
                                <textarea name="tm_tong_quan" class="form-control" rows="7"><?= ViewHelper::e($thuyetMinhData['tinh_hinh_nc']['tong_quan'] ?? '') ?></textarea>
                            </div>
                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label" style="font-weight: 600;">8.2. Danh mục các công trình liên quan:</label>
                                <textarea name="tm_cong_trinh" class="form-control" rows="5"><?= ViewHelper::e($thuyetMinhData['tinh_hinh_nc']['cong_trinh_lien_quan'] ?? '') ?></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- MỤC 9: TÍNH CẤP THIẾT CỦA ĐỀ TÀI -->
                    <div class="form-group">
                        <label class="form-label" style="font-size: 15px; font-weight: 700;">9. TÍNH CẤP THIẾT CỦA ĐỀ TÀI: <span class="required">*</span></label>
                        <textarea name="tm_tinh_cap_thiet" class="form-control" rows="7"><?= ViewHelper::e($thuyetMinhData['tinh_cap_thiet']) ?></textarea>
                    </div>

                    <!-- MỤC 10: MỤC TIÊU ĐỀ TÀI -->
                    <div class="card" style="background: rgba(15, 23, 42, 0.4); border-color: rgba(255, 255, 255, 0.08); margin-bottom: 20px;">
                        <div class="card-header">
                            <label class="form-label" style="font-size: 15px; font-weight: 700; margin: 0;">10. MỤC TIÊU ĐỀ TÀI: <span class="required">*</span></label>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label class="form-label">Mục tiêu chung:</label>
                                <textarea name="tm_muc_tieu_chung" class="form-control" rows="3"><?= ViewHelper::e($thuyetMinhData['muc_tieu']['muc_tieu_chung'] ?? $topic['MucTieu']) ?></textarea>
                            </div>
                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label">Các mục tiêu cụ thể:</label>
                                <textarea name="tm_muc_tieu_cu_the" class="form-control" rows="5"><?= ViewHelper::e($thuyetMinhData['muc_tieu']['muc_tieu_cu_the'] ?? '') ?></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- MỤC 11: ĐỐI TƯỢNG, PHẠM VI, PHƯƠNG PHÁP -->
                    <div class="card" style="background: rgba(15, 23, 42, 0.4); border-color: rgba(255, 255, 255, 0.08); margin-bottom: 20px;">
                        <div class="card-header">
                            <label class="form-label" style="font-size: 15px; font-weight: 700; margin: 0;">11. ĐỐI TƯỢNG, PHẠM VI , PHƯƠNG PHÁP NGHIÊN CỨU: <span class="required">*</span></label>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label class="form-label">1) Đối tượng nghiên cứu:</label>
                                <textarea name="tm_doi_tuong" class="form-control" rows="2"><?= ViewHelper::e($thuyetMinhData['doi_tuong_pham_vi_phuong_phap']['doi_tuong'] ?? '') ?></textarea>
                            </div>
                            <div class="form-group">
                                <label class="form-label">2) Phạm vi nghiên cứu:</label>
                                <textarea name="tm_pham_vi" class="form-control" rows="3"><?= ViewHelper::e($thuyetMinhData['doi_tuong_pham_vi_phuong_phap']['pham_vi'] ?? '') ?></textarea>
                            </div>
                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label">3) Phương pháp nghiên cứu:</label>
                                <textarea name="tm_phuong_phap" class="form-control" rows="3"><?= ViewHelper::e($thuyetMinhData['doi_tuong_pham_vi_phuong_phap']['phuong_phap'] ?? '') ?></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- MỤC 12: TIẾN ĐỘ THỰC HIỆN -->
                    <div class="card" style="background: rgba(15, 23, 42, 0.4); border-color: rgba(255, 255, 255, 0.08); margin-bottom: 20px;">
                        <div class="card-header">
                            <label class="form-label" style="font-size: 15px; font-weight: 700; margin: 0;">12. NỘI DUNG NGHIÊN CỨU VÀ TIẾN ĐỘ THỰC HIỆN: <span class="required">*</span></label>
                            <button type="button" id="btn-edit-add-tm-td" class="btn btn-sm btn-secondary" style="display: inline-flex; align-items: center; gap: 4px;">
                                <?= ViewHelper::icon('plus', '', 14) ?> Thêm Tiến Độ
                            </button>
                        </div>
                        <div class="card-body" style="padding: 0;">
                            <div class="table-responsive">
                                <table class="data-table" id="table-edit-tm-td">
                                    <thead>
                                        <tr>
                                            <th style="width: 5%;">TT</th>
                                            <th style="width: 35%;">Các nội dung, công việc thực hiện chủ yếu</th>
                                            <th style="width: 25%;">Dự kiến sản phẩm</th>
                                            <th style="width: 20%;">Thời gian (Bắt đầu – Kết thúc)</th>
                                            <th style="width: 15%;">Người thực hiện</th>
                                            <th style="width: 5%; text-align: center;">Xóa</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody-edit-tm-td">
                                        <?php if (!empty($thuyetMinhData['tien_do_thuc_hien'])): ?>
                                            <?php foreach ($thuyetMinhData['tien_do_thuc_hien'] as $idx => $td): ?>
                                                <tr>
                                                    <td style="text-align: center;"><?= $idx + 1 ?></td>
                                                    <td><input type="text" name="tm_td_noidung[]" class="form-control" value="<?= ViewHelper::e($td['noi_dung'] ?? '') ?>"></td>
                                                    <td><input type="text" name="tm_td_sanpham[]" class="form-control" value="<?= ViewHelper::e($td['san_pham'] ?? '') ?>"></td>
                                                    <td><input type="text" name="tm_td_thoigian[]" class="form-control" value="<?= ViewHelper::e($td['thoi_gian'] ?? '') ?>"></td>
                                                    <td><input type="text" name="tm_td_nguoith[]" class="form-control" value="<?= ViewHelper::e($td['nguoi_thuc_hien'] ?? 'Nhóm') ?>"></td>
                                                    <td style="text-align: center;"><button type="button" class="btn btn-sm btn-danger btn-remove-row" style="padding: 2px 6px;">x</button></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td style="text-align: center;">1</td>
                                                <td><input type="text" name="tm_td_noidung[]" class="form-control" value="Khảo sát và thực hiện đề tài"></td>
                                                <td><input type="text" name="tm_td_sanpham[]" class="form-control" value="Báo cáo hoàn chỉnh"></td>
                                                <td><input type="text" name="tm_td_thoigian[]" class="form-control" value="Học kỳ 1"></td>
                                                <td><input type="text" name="tm_td_nguoith[]" class="form-control" value="Nhóm"></td>
                                                <td style="text-align: center;">-</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- MỤC 13: SẢN PHẨM VÀ ĐỊA CHỈ ỨNG DỤNG -->
                    <div class="card" style="background: rgba(15, 23, 42, 0.4); border-color: rgba(255, 255, 255, 0.08); margin-bottom: 20px;">
                        <div class="card-header">
                            <label class="form-label" style="font-size: 15px; font-weight: 700; margin: 0;">13. SẢN PHẨM VÀ ĐỊA CHỈ ỨNG DỤNG: <span class="required">*</span></label>
                        </div>
                        <div class="card-body">
                            <!-- 13.1 Loại sản phẩm -->
                            <div class="form-group">
                                <label class="form-label" style="font-weight: 600;">- Loại sản phẩm (Đánh dấu chọn):</label>
                                <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; background: rgba(15, 23, 42, 0.5); padding: 14px; border-radius: var(--radius-md); border: 1px solid var(--border-color);">
                                    <?php 
                                    $currentChecked = (array)($thuyetMinhData['san_pham_ung_dung']['loai_san_pham'] ?? ['Báo cáo phân tích']);
                                    foreach (NckhFormHelper::DANH_MUC_LOAI_SAN_PHAM as $spOpt): 
                                        $isChecked = in_array($spOpt, $currentChecked);
                                    ?>
                                        <label style="display: flex; align-items: center; gap: 8px; font-size: 13.5px; color: #fff; cursor: pointer;">
                                            <input type="checkbox" name="tm_loai_san_pham[]" value="<?= ViewHelper::e($spOpt) ?>" <?= $isChecked ? 'checked' : '' ?>>
                                            <span><?= ViewHelper::e($spOpt) ?></span>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <!-- 13.2 Bảng chi tiết sản phẩm -->
                            <div class="form-group">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                                    <label class="form-label" style="font-weight: 600; margin: 0;">- Tên sản phẩm, số lượng và yêu cầu khoa học:</label>
                                    <button type="button" id="btn-edit-add-tm-sp" class="btn btn-sm btn-secondary" style="display: inline-flex; align-items: center; gap: 4px;">
                                        <?= ViewHelper::icon('plus', '', 14) ?> Thêm Sản Phẩm
                                    </button>
                                </div>
                                <div class="table-responsive">
                                    <table class="data-table" id="table-edit-tm-sp">
                                        <thead>
                                            <tr>
                                                <th style="width: 6%;">TT</th>
                                                <th style="width: 44%;">Tên sản phẩm</th>
                                                <th style="width: 14%;">Số lượng</th>
                                                <th style="width: 30%;">Yêu cầu khoa học</th>
                                                <th style="width: 6%; text-align: center;">Xóa</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbody-edit-tm-sp">
                                            <?php if (!empty($thuyetMinhData['san_pham_ung_dung']['danh_sach'])): ?>
                                                <?php foreach ($thuyetMinhData['san_pham_ung_dung']['danh_sach'] as $idx => $sp): ?>
                                                    <tr>
                                                        <td style="text-align: center;"><?= $idx + 1 ?></td>
                                                        <td><input type="text" name="tm_sp_ten[]" class="form-control" value="<?= ViewHelper::e($sp['ten_san_pham'] ?? '') ?>"></td>
                                                        <td><input type="text" name="tm_sp_soluong[]" class="form-control" value="<?= ViewHelper::e($sp['so_luong'] ?? '01') ?>"></td>
                                                        <td><input type="text" name="tm_sp_yeucau[]" class="form-control" value="<?= ViewHelper::e($sp['yeu_cau_khoa_hoc'] ?? '') ?>"></td>
                                                        <td style="text-align: center;">
                                                            <?php if ($idx > 0): ?>
                                                                <button type="button" class="btn btn-sm btn-danger btn-remove-row" style="padding: 2px 6px;">x</button>
                                                            <?php else: ?>-<?php endif; ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td style="text-align: center;">1</td>
                                                    <td><input type="text" name="tm_sp_ten[]" class="form-control" value="Báo cáo tổng kết đề tài"></td>
                                                    <td><input type="text" name="tm_sp_soluong[]" class="form-control" value="01"></td>
                                                    <td><input type="text" name="tm_sp_yeucau[]" class="form-control" value="Đúng quy chuẩn Đại học Tây Bắc"></td>
                                                    <td style="text-align: center;">-</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- 13.3 Địa chỉ có thể ứng dụng -->
                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label" style="font-weight: 600;">- Địa chỉ có thể ứng dụng:</label>
                                <textarea name="tm_dia_chi_ap_dung" class="form-control" rows="3"><?= ViewHelper::e($thuyetMinhData['san_pham_ung_dung']['dia_chi_ap_dung'] ?? '') ?></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- CHỮ KÝ XÁC NHẬN -->
                    <div class="grid-cols-2" style="background: rgba(15, 23, 42, 0.5); padding: 16px; border-radius: var(--radius-md);">
                        <div>
                            <label class="form-label" style="font-weight: 700;">Giảng viên hướng dẫn ký xác nhận</label>
                            <input type="text" name="tm_gvhd_ky" class="form-control" value="<?= ViewHelper::e($thuyetMinhData['gvhd_ky'] ?: $topic['TenGVHD']) ?>">
                        </div>
                        <div>
                            <label class="form-label" style="font-weight: 700;">Sinh viên chịu trách nhiệm chính</label>
                            <input type="text" name="tm_sv_ky" class="form-control" value="<?= ViewHelper::e($thuyetMinhData['sv_ky'] ?: $userHoTen) ?>">
                        </div>
                    </div>
                </div>
            </div>

            <div style="display: flex; justify-content: space-between; gap: 12px;">
                <button type="button" class="btn btn-secondary btn-prev-tab" data-prev="tab-edit-dexuat">
                    <?= ViewHelper::icon('arrow-left', '', 16) ?> Quay Lại Tab 2
                </button>
                <button type="button" class="btn btn-primary btn-next-tab" data-next="tab-edit-files">
                    <span>Sang Tab 4: Tệp Đính Kèm & Lưu</span>
                    <?= ViewHelper::icon('arrow-right', '', 16) ?>
                </button>
            </div>
        </div>

        <!-- =================================================================== -->
        <!-- TAB 4: TỆP ĐÍNH KÈM CẬP NHẬT (TÙY CHỌN)                           -->
        <!-- =================================================================== -->
        <div id="tab-edit-files" class="tab-pane-nckh" style="display: none;">
            <div class="card" style="margin-bottom: 24px;">
                <div class="card-header">
                    <div class="card-title">
                        <?= ViewHelper::icon('paperclip', '', 18) ?>
                        <span>Tải Lên File Scan Hoặc Tài Liệu Bổ Trợ Mới (Tùy Chọn)</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="grid-cols-2">
                        <div class="form-group">
                            <label class="form-label">File Phiếu Thuyết Minh Mới</label>
                            <input type="file" name="file_thuyet_minh_moi" class="form-control" accept=".pdf,.doc,.docx">
                            <div class="form-help">
                                <?php if (!empty($topic['FilePhieuThuyetMinh'])): ?>
                                    File hiện tại: <a href="<?= App::url('public/' . $topic['FilePhieuThuyetMinh']) ?>" target="_blank" style="color: #60a5fa;">Tải file cũ</a>.
                                <?php else: ?>
                                    Hiện tại chưa có file đính kèm (hệ thống dùng dữ liệu trực tuyến).
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">File Phiếu Đề Xuất Mới</label>
                            <input type="file" name="file_de_xuat_moi" class="form-control" accept=".pdf,.doc,.docx">
                            <div class="form-help">
                                <?php if (!empty($topic['FilePhieuDeXuat'])): ?>
                                    File hiện tại: <a href="<?= App::url('public/' . $topic['FilePhieuDeXuat']) ?>" target="_blank" style="color: #60a5fa;">Tải file cũ</a>.
                                <?php else: ?>
                                    Hiện tại chưa có file đính kèm (hệ thống dùng dữ liệu trực tuyến).
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- NÚT LƯU THAY ĐỔI -->
            <div style="display: flex; justify-content: space-between; align-items: center; background: var(--bg-card); border: 1px solid var(--border-color); padding: 16px 20px; border-radius: var(--radius-lg);">
                <button type="button" class="btn btn-secondary btn-prev-tab" data-prev="tab-edit-thuyetminh">
                    <?= ViewHelper::icon('arrow-left', '', 16) ?> Quay Lại Tab 3
                </button>
                <div style="display: flex; gap: 12px;">
                    <a href="<?= App::url('index.php?action=sv-chitiet-detai&id=' . $topic['MaDeTai']) ?>" class="btn btn-secondary">
                        Hủy Bỏ
                    </a>
                    <button type="submit" class="btn btn-primary btn-lg" style="display: inline-flex; align-items: center; gap: 8px; font-weight: 700; padding: 12px 24px; box-shadow: 0 4px 14px 0 rgba(37, 99, 235, 0.4);">
                        <?= ViewHelper::icon('send', '', 18) ?> LƯU VÀ GỬI HỒ SƠ ĐÃ SỬA ĐỔI
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Chuyển đổi tab trực quan
    const tabBtns = document.querySelectorAll('.nckh-tab-btn');
    const tabPanes = document.querySelectorAll('.tab-pane-nckh');

    function switchTab(targetTabId) {
        tabBtns.forEach(btn => {
            btn.classList.toggle('active', btn.getAttribute('data-tab') === targetTabId);
        });
        tabPanes.forEach(pane => {
            pane.style.display = (pane.id === targetTabId) ? 'block' : 'none';
        });
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    tabBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            switchTab(this.getAttribute('data-tab'));
        });
    });

    document.querySelectorAll('.btn-next-tab').forEach(btn => {
        btn.addEventListener('click', function() {
            switchTab(this.getAttribute('data-next'));
        });
    });

    document.querySelectorAll('.btn-prev-tab').forEach(btn => {
        btn.addEventListener('click', function() {
            switchTab(this.getAttribute('data-prev'));
        });
    });

    // 2. Đồng bộ tên đề tài
    const editTenDeTai = document.getElementById('edit-tendetai');
    const editDxTenDeTai = document.getElementById('edit-dx-ten-de-tai');
    const editTmTenDeTai = document.getElementById('edit-tm-ten-de-tai');

    editTenDeTai.addEventListener('input', function() {
        editDxTenDeTai.value = this.value;
        editTmTenDeTai.value = this.value;
    });
    editDxTenDeTai.addEventListener('input', function() {
        editTenDeTai.value = this.value;
        editTmTenDeTai.value = this.value;
    });
    editTmTenDeTai.addEventListener('input', function() {
        editTenDeTai.value = this.value;
        editDxTenDeTai.value = this.value;
    });

    // 3. Thêm/xóa nội dung Phiếu Đề Xuất
    const editDxContainer = document.getElementById('edit-dx-noidung-container');
    document.getElementById('btn-edit-add-dx-nd').addEventListener('click', function() {
        const count = editDxContainer.querySelectorAll('.dx-noidung-card').length + 1;
        const card = document.createElement('div');
        card.className = 'dx-noidung-card';
        card.style = 'background: rgba(15, 23, 42, 0.4); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 14px;';
        card.innerHTML = `
            <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                <input type="text" name="dx_nd_tieude[]" class="form-control" style="font-weight: 700; max-width: 80%;" value="4.${count}. Nội dung ${count}: ">
                <button type="button" class="btn btn-sm btn-danger btn-remove-dx-nd" style="padding: 4px 8px;">Xóa</button>
            </div>
            <textarea name="dx_nd_chitiet[]" class="form-control" rows="4" placeholder="Chi tiết nội dung..."></textarea>
        `;
        editDxContainer.appendChild(card);
        card.querySelector('.btn-remove-dx-nd').addEventListener('click', () => card.remove());
    });

    editDxContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-remove-dx-nd')) {
            e.target.closest('.dx-noidung-card').remove();
        }
    });

    // 4. Thêm hàng bảng Thuyết Minh
    const tbodyEditTv = document.getElementById('tbody-edit-tm-tv');
    document.getElementById('btn-edit-add-tm-tv').addEventListener('click', function() {
        const count = tbodyEditTv.querySelectorAll('tr').length + 1;
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td style="text-align: center;">${count}</td>
            <td><input type="text" name="tm_tv_hoten[]" class="form-control" placeholder="Họ và tên..."></td>
            <td><input type="text" name="tm_tv_lop[]" class="form-control" placeholder="Lớp..."></td>
            <td><input type="text" name="tm_tv_congviec[]" class="form-control" placeholder="Công việc phân công..."></td>
            <td style="text-align: center;"><button type="button" class="btn btn-sm btn-danger btn-remove-row" style="padding: 2px 6px;">x</button></td>
        `;
        tbodyEditTv.appendChild(tr);
    });

    const tbodyEditTd = document.getElementById('tbody-edit-tm-td');
    document.getElementById('btn-edit-add-tm-td').addEventListener('click', function() {
        const count = tbodyEditTd.querySelectorAll('tr').length + 1;
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td style="text-align: center;">${count}</td>
            <td><input type="text" name="tm_td_noidung[]" class="form-control" placeholder="Nội dung, công việc chủ yếu..."></td>
            <td><input type="text" name="tm_td_sanpham[]" class="form-control" placeholder="Dự kiến sản phẩm..."></td>
            <td><input type="text" name="tm_td_thoigian[]" class="form-control" placeholder="Thời gian..."></td>
            <td><input type="text" name="tm_td_nguoith[]" class="form-control" value="Nhóm"></td>
            <td style="text-align: center;"><button type="button" class="btn btn-sm btn-danger btn-remove-row" style="padding: 2px 6px;">x</button></td>
        `;
        tbodyEditTd.appendChild(tr);
    });

    const tbodyEditSp = document.getElementById('tbody-edit-tm-sp');
    document.getElementById('btn-edit-add-tm-sp').addEventListener('click', function() {
        const count = tbodyEditSp.querySelectorAll('tr').length + 1;
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td style="text-align: center;">${count}</td>
            <td><input type="text" name="tm_sp_ten[]" class="form-control" placeholder="Tên sản phẩm..."></td>
            <td><input type="text" name="tm_sp_soluong[]" class="form-control" value="01"></td>
            <td><input type="text" name="tm_sp_yeucau[]" class="form-control" placeholder="Yêu cầu khoa học đạt được..."></td>
            <td style="text-align: center;"><button type="button" class="btn btn-sm btn-danger btn-remove-row" style="padding: 2px 6px;">x</button></td>
        `;
        tbodyEditSp.appendChild(tr);
    });

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-remove-row')) {
            e.target.closest('tr').remove();
        }
    });
});
</script>

<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>

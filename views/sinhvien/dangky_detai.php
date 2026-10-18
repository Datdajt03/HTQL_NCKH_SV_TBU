<?php
use App\Config\App;
use App\Helpers\AuthHelper;
use App\Helpers\ViewHelper;
use App\Helpers\NckhFormHelper;

$pageTitle = "Đăng Ký Đề Tài NCKH Sinh Viên Trực Tuyến";
require dirname(__DIR__) . '/layouts/header.php';
$sampleDX = NckhFormHelper::getSampleDeXuatData();
$sampleTM = NckhFormHelper::getSampleThuyetMinhData();

$studentId = $studentId ?? AuthHelper::id();
$userHoTen = $user['HoTen'] ?? ($user['name'] ?? AuthHelper::name());
$userPhone = $user['SoDienThoai'] ?? ($user['phone'] ?? '');
$userLop   = $user['extra']['Lop'] ?? ($user['Lop'] ?? 'K64 ĐH CNTT-B');
?>
<div style="max-width: 1080px; margin: 0 auto; padding-bottom: 60px;">
    <!-- HEADER TIÊU ĐỀ TRANG -->
    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
        <div>
            <h2 style="font-size: 22px; font-weight: 800; color: var(--text-primary); margin-bottom: 6px; display: flex; align-items: center; gap: 10px;">
                <span><?= ViewHelper::icon('file-plus', 'text-primary', 24) ?></span>
                <span>Đăng Ký Đề Tài NCKH Trực Tuyến</span>
            </h2>
            <p style="color: var(--text-secondary); font-size: 14px; margin: 0;">
                Số hóa toàn diện <strong>Phiếu Đề Xuất (Mẫu 02)</strong> và <strong>Thuyết Minh Đề Tài (Mẫu 01)</strong>. Điền và chỉnh sửa trực tiếp trên web không cần tải file docx.
            </p>
        </div>
        <div style="display: flex; gap: 10px;">
            <button type="button" id="btn-fill-sample" class="btn btn-secondary" style="background: rgba(59, 130, 246, 0.15); border-color: rgba(59, 130, 246, 0.4); color: #93c5fd; display: inline-flex; align-items: center; gap: 8px;">
                <?= ViewHelper::icon('refresh', '', 16) ?>
                <span>Điền Mẫu s-Club Chuẩn ĐH Tây Bắc</span>
            </button>
        </div>
    </div>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger" style="margin-bottom: 24px;">
            <span><?= ViewHelper::icon('alert-circle', '', 20) ?></span>
            <div><?= ViewHelper::e($error) ?></div>
        </div>
    <?php endif; ?>

    <!-- TABS ĐIỀU HƯỚNG TRỰC QUAN -->
    <div class="nckh-form-tabs">
        <button type="button" class="nckh-tab-btn active" data-tab="tab-general">
            <?= ViewHelper::icon('file-text', '', 16) ?>
            <span>1. Thông Tin Chung & Nhóm</span>
        </button>
        <button type="button" class="nckh-tab-btn" data-tab="tab-dexuat">
            <?= ViewHelper::icon('target', '', 16) ?>
            <span>2. Phiếu Đề Xuất (Mẫu 02 - 6 Mục)</span>
        </button>
        <button type="button" class="nckh-tab-btn" data-tab="tab-thuyetminh">
            <?= ViewHelper::icon('book-open', '', 16) ?>
            <span>3. Thuyết Minh Đề Tài (Mẫu 01 - 13 Mục)</span>
        </button>
        <button type="button" class="nckh-tab-btn" data-tab="tab-files">
            <?= ViewHelper::icon('paperclip', '', 16) ?>
            <span>4. Tệp Đính Kèm (Tùy Chọn)</span>
        </button>
    </div>

    <form method="POST" action="<?= App::url('index.php?action=sv-dangky-detai') ?>" enctype="multipart/form-data" id="form-dangky-detai">
        
        <!-- =================================================================== -->
        <!-- TAB 1: THÔNG TIN CHUNG & NHÓM NGHIÊN CỨU                           -->
        <!-- =================================================================== -->
        <div id="tab-general" class="tab-pane-nckh active">
            <div class="card" style="margin-bottom: 20px;">
                <div class="card-header">
                    <div class="card-title">
                        <?= ViewHelper::icon('file-text', '', 18) ?>
                        <span>Thông Tin Đề Tài & Đơn Vị Quản Lý</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label">Tên Đề Tài Nghiên Cứu Khoa Học <span class="required">*</span></label>
                        <input type="text" name="tendetai" id="input-tendetai" class="form-control" placeholder="Nhập tên đề tài nghiên cứu đầy đủ, rõ ràng..." required>
                        <div class="form-help">Tên đề tài sẽ tự động đồng bộ vào Phiếu Đề Xuất và Thuyết Minh Đề Tài.</div>
                    </div>

                    <div class="grid-cols-2">
                        <div class="form-group">
                            <label class="form-label">Đợt Phát Động NCKH Áp Dụng <span class="required">*</span></label>
                            <select name="madot" id="select-madot" class="form-control" required>
                                <option value="">-- Chọn đợt NCKH đang mở --</option>
                                <?php foreach ($activeDots as $d): ?>
                                    <option value="<?= ViewHelper::e($d['MaDot']) ?>" <?= $d['TrangThai'] === 'MO_DANG_KY' ? 'selected' : '' ?>>
                                        <?= ViewHelper::e($d['TenDot']) ?> (Hạn ĐK: <?= ViewHelper::date($d['HanDangKy']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Hướng Nghiên Cứu Phù Hợp <span class="required">*</span></label>
                            <select name="mahuongnc" id="select-mahuongnc" class="form-control" required>
                                <option value="">-- Chọn hướng nghiên cứu --</option>
                                <?php foreach ($directions as $h): ?>
                                    <option value="<?= ViewHelper::e($h['MaHuongNC']) ?>">
                                        <?= ViewHelper::e($h['TenHuongNC']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="grid-cols-2">
                        <div class="form-group">
                            <label class="form-label">Giảng Viên Hướng Dẫn (GVHD) <span class="required">*</span></label>
                            <select name="magvhd" id="select-magvhd" class="form-control" required>
                                <option value="">-- Chọn Giảng viên hướng dẫn --</option>
                                <?php foreach ($teachers as $gv): ?>
                                    <option value="<?= ViewHelper::e($gv['MaNguoiDung']) ?>" data-hoten="<?= ViewHelper::e($gv['HoTen']) ?>" data-hocvi="<?= ViewHelper::e(($gv['HocHam'] ? $gv['HocHam'] . ' ' : '') . $gv['HocVi']) ?>" data-sdt="<?= ViewHelper::e($gv['SoDienThoai']) ?>">
                                        <?= ViewHelper::e(($gv['HocHam'] ? $gv['HocHam'] . ' ' : '') . $gv['HocVi'] . ' ' . $gv['HoTen']) ?> (<?= ViewHelper::e($gv['DonViCongTac']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="form-help">Hồ sơ đăng ký sẽ được chuyển đến GVHD duyệt bước đầu tiên.</div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Kinh Phí Dự Trù (VNĐ)</label>
                            <input type="number" name="kinhphi" id="input-kinhphi" class="form-control" placeholder="VD: 5000000" min="0" step="500000" value="5000000">
                            <div class="form-help">Dự toán kinh phí hỗ trợ từ nhà trường và các nguồn khác (nếu có).</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- NHÓM NGHIÊN CỨU -->
            <div class="card" style="margin-bottom: 20px;">
                <div class="card-header">
                    <div class="card-title">
                        <?= ViewHelper::icon('users', '', 18) ?>
                        <span>Nhóm Sinh Viên Thực Hiện Đề Tài</span>
                    </div>
                    <button type="button" id="btn-add-system-member" class="btn btn-sm btn-secondary" style="border-color: rgba(59, 130, 246, 0.4); display: inline-flex; align-items: center; gap: 6px;">
                        <?= ViewHelper::icon('plus', '', 14) ?> Thêm Thành Viên Khác
                    </button>
                </div>
                <div class="card-body">
                    <!-- Trưởng nhóm -->
                    <div style="background: rgba(59, 130, 246, 0.08); border: 1px solid rgba(59, 130, 246, 0.25); border-radius: var(--radius-md); padding: 16px; margin-bottom: 16px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                            <span class="badge badge-amber">Chủ Nhiệm Đề Tài (Trưởng Nhóm)</span>
                            <span style="font-size: 13px; color: var(--text-muted);">Mã SV: <strong><?= ViewHelper::e($studentId) ?></strong></span>
                        </div>
                        <div class="grid-cols-2" style="margin-bottom: 0;">
                            <div>
                                <label class="form-label" style="font-size: 12px;">Họ và Tên Sinh Viên</label>
                                <input type="text" class="form-control" value="<?= ViewHelper::e($userHoTen) ?>" readonly disabled style="opacity: 0.85;">
                            </div>
                            <div>
                                <label class="form-label" style="font-size: 12px;">Nhiệm Vụ Cụ Thể Trong Đề Tài</label>
                                <input type="text" name="nhiemvu_truongnhom" id="input-truongnhom-nhiemvu" class="form-control" value="Trưởng nhóm, tổng hợp lý thuyết, xây dựng mô hình mô phỏng thuật toán." required>
                            </div>
                        </div>
                    </div>

                    <!-- Danh sách thành viên thêm -->
                    <div id="system-members-container"></div>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 12px;">
                <button type="button" class="btn btn-primary btn-next-tab" data-next="tab-dexuat">
                    <span>Sang Bước 2: Điền Phiếu Đề Xuất</span>
                    <?= ViewHelper::icon('arrow-right', '', 16) ?>
                </button>
            </div>
        </div>

        <!-- =================================================================== -->
        <!-- TAB 2: PHIẾU ĐỀ XUẤT (MẪU 02 - CHUẨN 6 MỤC ĐẠI HỌC TÂY BẮC)    -->
        <!-- =================================================================== -->
        <div id="tab-dexuat" class="tab-pane-nckh" style="display: none;">
            <div class="card" style="margin-bottom: 20px;">
                <div class="card-header" style="background: rgba(59, 130, 246, 0.05); border-bottom: 1px solid rgba(59, 130, 246, 0.2);">
                    <div class="card-title" style="color: #60a5fa;">
                        <?= ViewHelper::icon('file-text', '', 18) ?>
                        <span>PHIẾU ĐỀ XUẤT ĐỀ TÀI NCKH CỦA SINH VIÊN (MẪU 02)</span>
                    </div>
                    <span class="badge badge-info">Chuẩn 6 đề mục theo văn bản nhà trường</span>
                </div>
                <div class="card-body">
                    <!-- Cơ quan & Quốc hiệu -->
                    <div class="grid-cols-2" style="background: rgba(15, 23, 42, 0.5); padding: 16px; border-radius: var(--radius-md); margin-bottom: 20px;">
                        <div>
                            <div class="form-group" style="margin-bottom: 10px;">
                                <label class="form-label" style="font-size: 12px;">Cơ Quan Chủ Quản</label>
                                <input type="text" name="dx_co_quan" class="form-control" value="BỘ GIÁO DỤC VÀ ĐÀO TẠO">
                            </div>
                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label" style="font-size: 12px;">Đơn Vị Thực Hiện</label>
                                <input type="text" name="dx_don_vi" class="form-control" value="TRƯỜNG ĐẠI HỌC TÂY BẮC">
                            </div>
                        </div>
                        <div>
                            <div class="form-group" style="margin-bottom: 10px;">
                                <label class="form-label" style="font-size: 12px;">Quốc Hiệu & Tiêu Ngữ</label>
                                <input type="text" class="form-control" value="CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM" readonly disabled>
                            </div>
                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label" style="font-size: 12px;">Địa Điểm & Ngày Đề Xuất</label>
                                <input type="text" name="dx_dia_diem_ngay" id="dx-dia-diem-ngay" class="form-control" value="Sơn La, ngày <?= date('d') ?> tháng <?= date('m') ?> năm <?= date('Y') ?>">
                            </div>
                        </div>
                    </div>

                    <!-- MỤC 1: TÊN ĐỀ TÀI -->
                    <div class="form-group">
                        <label class="form-label" style="font-size: 15px; font-weight: 700;">1. Tên đề tài: <span class="required">*</span></label>
                        <input type="text" name="dx_ten_de_tai" id="dx-ten-de-tai" class="form-control" placeholder="Tên đề tài..." required>
                    </div>

                    <!-- MỤC 2: GIẢI TRÌNH VỀ TÍNH CẤP THIẾT -->
                    <div class="form-group">
                        <label class="form-label" style="font-size: 15px; font-weight: 700;">2. Giải trình về tính cấp thiết: <span class="required">*</span></label>
                        <textarea name="dx_tinh_cap_thiet" id="dx-tinh-cap-thiet" class="form-control" rows="8" placeholder="Trình bày lý do, bối cảnh, sự cần thiết và khoảng trống nghiên cứu..."></textarea>
                    </div>

                    <!-- MỤC 3: MỤC TIÊU DỰ KIẾN CỦA ĐỀ TÀI -->
                    <div class="form-group">
                        <label class="form-label" style="font-size: 15px; font-weight: 700;">3. Mục tiêu dự kiến của đề tài: <span class="required">*</span></label>
                        <textarea name="dx_muc_tieu" id="dx-muc-tieu" class="form-control" rows="5" placeholder="Các mục tiêu tổng quát và cụ thể mà đề tài hướng tới..."></textarea>
                    </div>

                    <!-- MỤC 4: NỘI DUNG CHỦ YẾU CỦA ĐỀ TÀI -->
                    <div class="form-group">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                            <label class="form-label" style="font-size: 15px; font-weight: 700; margin: 0;">4. Nội dung chủ yếu của đề tài: <span class="required">*</span></label>
                            <button type="button" id="btn-add-dx-noidung" class="btn btn-sm btn-secondary" style="display: inline-flex; align-items: center; gap: 4px;">
                                <?= ViewHelper::icon('plus', '', 14) ?> Thêm Nội Dung
                            </button>
                        </div>
                        <div id="dx-noidung-container" style="display: flex; flex-direction: column; gap: 14px;">
                            <!-- Dòng nội dung 1 -->
                            <div class="dx-noidung-card" style="background: rgba(15, 23, 42, 0.4); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 14px;">
                                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                    <input type="text" name="dx_nd_tieude[]" class="form-control dx-nd-tt" style="font-weight: 700; max-width: 80%;" value="4.1. Nội dung 1: Tìm hiểu lý thuyết và các thuật toán cơ bản">
                                </div>
                                <textarea name="dx_nd_chitiet[]" class="form-control dx-nd-ct" rows="4" placeholder="Chi tiết các công việc, phương pháp của nội dung 1..."></textarea>
                            </div>
                            <!-- Dòng nội dung 2 -->
                            <div class="dx-noidung-card" style="background: rgba(15, 23, 42, 0.4); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 14px;">
                                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                    <input type="text" name="dx_nd_tieude[]" class="form-control dx-nd-tt" style="font-weight: 700; max-width: 80%;" value="4.2. Nội dung 2: Đề xuất và đánh giá thuật toán di truyền nhóm (GGA)">
                                    <button type="button" class="btn btn-sm btn-danger btn-remove-dx-nd" style="padding: 4px 8px;">Xóa</button>
                                </div>
                                <textarea name="dx_nd_chitiet[]" class="form-control dx-nd-ct" rows="4" placeholder="Chi tiết các công việc, phương pháp của nội dung 2..."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- MỤC 5: DỰ KIẾN SẢN PHẨM CỦA ĐỀ TÀI -->
                    <div class="form-group">
                        <label class="form-label" style="font-size: 15px; font-weight: 700;">5. Dự kiến sản phẩm của đề tài: <span class="required">*</span></label>
                        <textarea name="dx_san_pham" id="dx-san-pham" class="form-control" rows="4" placeholder="Liệt kê các báo cáo, phần mềm, bộ dữ liệu, mô hình..."></textarea>
                    </div>

                    <!-- MỤC 6: KHẢ NĂNG VÀ ĐỊA CHỈ ÁP DỤNG -->
                    <div class="card" style="background: rgba(15, 23, 42, 0.4); border-color: rgba(255, 255, 255, 0.08); margin-bottom: 20px;">
                        <div class="card-header">
                            <label class="form-label" style="font-size: 15px; font-weight: 700; margin: 0;">6. Khả năng và địa chỉ áp dụng: <span class="required">*</span></label>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label class="form-label">Đối tượng áp dụng</label>
                                <input type="text" name="dx_doi_tuong" id="dx-doi-tuong" class="form-control" value="Giảng viên, sinh viên và nhà nghiên cứu trong lĩnh vực Công nghệ thông tin, Trí tuệ nhân tạo và Tối ưu hóa.">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Địa chỉ áp dụng</label>
                                <input type="text" name="dx_dia_chi" id="dx-dia-chi" class="form-control" value="Khoa Công nghệ Thông tin, Trường Đại học Tây Bắc, tỉnh Sơn La.">
                            </div>
                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label">Khả năng mở rộng</label>
                                <textarea name="dx_kha_nang_mo_rong" id="dx-kha-nang-mo-rong" class="form-control" rows="2">Kết quả nghiên cứu có thể áp dụng cho các bài toán phân tích mạng xã hội, tối ưu mạng cảm biến, phát hiện cộng đồng trong dữ liệu lớn hoặc bài toán phân vùng đồ thị.</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- CHỮ KÝ XÁC NHẬN -->
                    <div class="grid-cols-2" style="background: rgba(15, 23, 42, 0.5); padding: 16px; border-radius: var(--radius-md);">
                        <div>
                            <label class="form-label" style="font-weight: 700;">Xác nhận của Giảng viên hướng dẫn</label>
                            <input type="text" name="dx_gvhd" id="dx-gvhd" class="form-control" placeholder="Họ tên GVHD..." value="TS. Phạm Đình Thành">
                        </div>
                        <div>
                            <label class="form-label" style="font-weight: 700;">Cá nhân đề xuất</label>
                            <input type="text" name="dx_sinh_vien" id="dx-sinh-vien" class="form-control" placeholder="Họ tên sinh viên đề xuất..." value="<?= ViewHelper::e($userHoTen) ?>">
                        </div>
                    </div>
                </div>
            </div>

            <div style="display: flex; justify-content: space-between; gap: 12px;">
                <button type="button" class="btn btn-secondary btn-prev-tab" data-prev="tab-general">
                    <?= ViewHelper::icon('arrow-left', '', 16) ?> Quay Lại Tab 1
                </button>
                <button type="button" class="btn btn-primary btn-next-tab" data-next="tab-thuyetminh">
                    <span>Sang Bước 3: Điền Thuyết Minh Đề Tài</span>
                    <?= ViewHelper::icon('arrow-right', '', 16) ?>
                </button>
            </div>
        </div>

        <!-- =================================================================== -->
        <!-- TAB 3: THUYẾT MINH ĐỀ TÀI (MẪU 01 - CHUẨN 13 MỤC ĐẠI HỌC TÂY BẮC) -->
        <!-- =================================================================== -->
        <div id="tab-thuyetminh" class="tab-pane-nckh" style="display: none;">
            <div class="card" style="margin-bottom: 20px;">
                <div class="card-header" style="background: rgba(99, 102, 241, 0.05); border-bottom: 1px solid rgba(99, 102, 241, 0.2);">
                    <div class="card-title" style="color: #818cf8;">
                        <?= ViewHelper::icon('book-open', '', 18) ?>
                        <span>THUYẾT MINH ĐỀ TÀI NCKH VÀ CÔNG NGHỆ CẤP TRƯỜNG CỦA SINH VIÊN (MẪU 01)</span>
                    </div>
                    <span class="badge badge-primary">Chuẩn 13 đề mục theo văn bản nhà trường</span>
                </div>
                <div class="card-body">
                    <!-- Cơ quan & Năm học -->
                    <div class="grid-cols-2" style="background: rgba(15, 23, 42, 0.5); padding: 16px; border-radius: var(--radius-md); margin-bottom: 20px;">
                        <div>
                            <div class="form-group" style="margin-bottom: 10px;">
                                <label class="form-label" style="font-size: 12px;">Cơ Quan & Đơn Vị</label>
                                <input type="text" name="tm_don_vi" class="form-control" value="BỘ GIÁO DỤC VÀ ĐÀO TẠO - TRƯỜNG ĐẠI HỌC TÂY BẮC">
                            </div>
                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label" style="font-size: 12px;">Năm Học Thực Hiện</label>
                                <input type="text" name="tm_nam_hoc" id="tm-nam-hoc" class="form-control" value="2025 - 2026">
                            </div>
                        </div>
                        <div>
                            <div class="form-group" style="margin-bottom: 10px;">
                                <label class="form-label" style="font-size: 12px;">Địa Điểm & Ngày Thuyết Minh</label>
                                <input type="text" name="tm_dia_diem_ngay" id="tm-dia-diem-ngay" class="form-control" value="Sơn La, ngày <?= date('d') ?> tháng <?= date('m') ?> năm <?= date('Y') ?>">
                            </div>
                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label" style="font-size: 12px;">Nhóm Ngành Nghiên Cứu (Mục 3)</label>
                                <input type="text" name="tm_nhom_nganh" id="tm-nhom-nganh" class="form-control" value="Công nghệ thông tin">
                            </div>
                        </div>
                    </div>

                    <!-- MỤC 1: TÊN ĐỀ TÀI -->
                    <div class="form-group">
                        <label class="form-label" style="font-size: 15px; font-weight: 700;">1. TÊN ĐỀ TÀI: <span class="required">*</span></label>
                        <input type="text" name="tm_ten_de_tai" id="tm-ten-de-tai" class="form-control" placeholder="Tên đề tài..." required>
                    </div>

                    <!-- MỤC 2: LOẠI HÌNH NGHIÊN CỨU & MỤC 4: THỜI GIAN -->
                    <div class="grid-cols-2">
                        <div class="form-group">
                            <label class="form-label" style="font-size: 15px; font-weight: 700;">2. LOẠI HÌNH NGHIÊN CỨU: <span class="required">*</span></label>
                            <div style="display: flex; gap: 20px; align-items: center; padding: 10px 14px; background: rgba(15, 23, 42, 0.4); border-radius: var(--radius-md); border: 1px solid var(--border-color);">
                                <label style="display: inline-flex; align-items: center; gap: 6px; cursor: pointer; color: #fff;">
                                    <input type="radio" name="tm_loai_hinh_nc" value="CO_BAN" checked>
                                    <span>Cơ Bản</span>
                                </label>
                                <label style="display: inline-flex; align-items: center; gap: 6px; cursor: pointer; color: #fff;">
                                    <input type="radio" name="tm_loai_hinh_nc" value="UNG_DUNG">
                                    <span>Ứng Dụng</span>
                                </label>
                                <label style="display: inline-flex; align-items: center; gap: 6px; cursor: pointer; color: #fff;">
                                    <input type="radio" name="tm_loai_hinh_nc" value="TRIEN_KHAI">
                                    <span>Triển Khai</span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" style="font-size: 15px; font-weight: 700;">4. THỜI GIAN THỰC HIỆN: <span class="required">*</span></label>
                            <input type="text" name="tm_thoi_gian" id="tm-thoi-gian" class="form-control" value="Từ tháng 10 năm 2025 đến tháng 11 năm 2026">
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
                                    <input type="text" name="tm_cn_hoten" id="tm-cn-hoten" class="form-control" value="<?= ViewHelper::e($userHoTen) ?>">
                                </div>
                                <div>
                                    <label class="form-label">Lớp</label>
                                    <input type="text" name="tm_cn_lop" id="tm-cn-lop" class="form-control" placeholder="VD: K64 ĐH CNTT-B" value="<?= ViewHelper::e($userLop) ?>">
                                </div>
                                <div>
                                    <label class="form-label">Điện thoại</label>
                                    <input type="text" name="tm_cn_sdt" id="tm-cn-sdt" class="form-control" placeholder="Số ĐT..." value="<?= ViewHelper::e($userPhone ?: '0982 943 664') ?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- MỤC 6: NHỮNG NGƯỜI THAM GIA THỰC HIỆN ĐỀ TÀI -->
                    <div class="card" style="background: rgba(15, 23, 42, 0.4); border-color: rgba(255, 255, 255, 0.08); margin-bottom: 20px;">
                        <div class="card-header">
                            <label class="form-label" style="font-size: 15px; font-weight: 700; margin: 0;">6. NHỮNG NGƯỜI THAM GIA THỰC HIỆN ĐỀ TÀI:</label>
                            <button type="button" id="btn-add-tm-thanhvien" class="btn btn-sm btn-secondary" style="display: inline-flex; align-items: center; gap: 4px;">
                                <?= ViewHelper::icon('plus', '', 14) ?> Thêm Thành Viên
                            </button>
                        </div>
                        <div class="card-body" style="padding: 0;">
                            <div class="table-responsive">
                                <table class="data-table" id="table-tm-thanhvien">
                                    <thead>
                                        <tr>
                                            <th style="width: 5%;">TT</th>
                                            <th style="width: 25%;">Họ và tên</th>
                                            <th style="width: 20%;">Lớp</th>
                                            <th style="width: 42%;">Nội dung công việc được giao</th>
                                            <th style="width: 8%; text-align: center;">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody-tm-thanhvien">
                                        <!-- Mặc định sinh viên 1 -->
                                        <tr>
                                            <td style="text-align: center;">1</td>
                                            <td><input type="text" name="tm_tv_hoten[]" class="form-control" value="<?= ViewHelper::e($userHoTen) ?>"></td>
                                            <td><input type="text" name="tm_tv_lop[]" class="form-control" value="<?= ViewHelper::e($userLop) ?>"></td>
                                            <td><input type="text" name="tm_tv_congviec[]" class="form-control" value="Trưởng nhóm, tổng hợp lý thuyết, xây dựng mô hình mô phỏng thuật toán."></td>
                                            <td style="text-align: center;">-</td>
                                        </tr>
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
                                    <input type="text" name="tm_gv_hoten" id="tm-gv-hoten" class="form-control" placeholder="Họ tên GV..." value="Phạm Đình Thành">
                                </div>
                                <div>
                                    <label class="form-label">Chức danh KH, học vị</label>
                                    <input type="text" name="tm_gv_hocvi" id="tm-gv-hocvi" class="form-control" placeholder="Tiến sĩ / ThS..." value="Tiến sĩ">
                                </div>
                                <div>
                                    <label class="form-label">Điện thoại liên hệ</label>
                                    <input type="text" name="tm_gv_sdt" id="tm-gv-sdt" class="form-control" placeholder="Số ĐT..." value="0988 811 127">
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
                                <textarea name="tm_tong_quan" id="tm-tong-quan" class="form-control" rows="7" placeholder="Trình bày các nghiên cứu trong và ngoài nước..."></textarea>
                            </div>
                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label" style="font-weight: 600;">8.2. Danh mục các công trình liên quan (Họ và tên tác giả; Nhan đề bài báo, ấn phẩm, nguồn tài liệu):</label>
                                <textarea name="tm_cong_trinh" id="tm-cong-trinh" class="form-control" rows="5" placeholder="[1] Tác giả, Tên bài báo, Nguồn...&#10;[2]..."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- MỤC 9: TÍNH CẤP THIẾT CỦA ĐỀ TÀI -->
                    <div class="form-group">
                        <label class="form-label" style="font-size: 15px; font-weight: 700;">9. TÍNH CẤP THIẾT CỦA ĐỀ TÀI: <span class="required">*</span></label>
                        <textarea name="tm_tinh_cap_thiet" id="tm-tinh-cap-thiet" class="form-control" rows="7" placeholder="Tính cấp thiết của đề tài..."></textarea>
                    </div>

                    <!-- MỤC 10: MỤC TIÊU ĐỀ TÀI -->
                    <div class="card" style="background: rgba(15, 23, 42, 0.4); border-color: rgba(255, 255, 255, 0.08); margin-bottom: 20px;">
                        <div class="card-header">
                            <label class="form-label" style="font-size: 15px; font-weight: 700; margin: 0;">10. MỤC TIÊU ĐỀ TÀI: <span class="required">*</span></label>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label class="form-label">Mục tiêu chung:</label>
                                <textarea name="tm_muc_tieu_chung" id="tm-muc-tieu-chung" class="form-control" rows="3" placeholder="Mục tiêu tổng quát..."></textarea>
                            </div>
                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label">Các mục tiêu cụ thể (1), (2), (3)...:</label>
                                <textarea name="tm_muc_tieu_cu_the" id="tm-muc-tieu-cu-the" class="form-control" rows="5" placeholder="(1)...&#10;(2)..."></textarea>
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
                                <textarea name="tm_doi_tuong" id="tm-doi-tuong" class="form-control" rows="2" placeholder="Đối tượng nghiên cứu..."></textarea>
                            </div>
                            <div class="form-group">
                                <label class="form-label">2) Phạm vi nghiên cứu:</label>
                                <textarea name="tm_pham_vi" id="tm-pham-vi" class="form-control" rows="3" placeholder="Phạm vi nghiên cứu..."></textarea>
                            </div>
                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label">3) Phương pháp nghiên cứu:</label>
                                <textarea name="tm_phuong_phap" id="tm-phuong-phap" class="form-control" rows="3" placeholder="Phương pháp nghiên cứu..."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- MỤC 12: NỘI DUNG NGHIÊN CỨU VÀ TIẾN ĐỘ THỰC HIỆN -->
                    <div class="card" style="background: rgba(15, 23, 42, 0.4); border-color: rgba(255, 255, 255, 0.08); margin-bottom: 20px;">
                        <div class="card-header">
                            <label class="form-label" style="font-size: 15px; font-weight: 700; margin: 0;">12. NỘI DUNG NGHIÊN CỨU VÀ TIẾN ĐỘ THỰC HIỆN: <span class="required">*</span></label>
                            <button type="button" id="btn-add-tm-tiendo" class="btn btn-sm btn-secondary" style="display: inline-flex; align-items: center; gap: 4px;">
                                <?= ViewHelper::icon('plus', '', 14) ?> Thêm Tiến Độ
                            </button>
                        </div>
                        <div class="card-body" style="padding: 0;">
                            <div class="table-responsive">
                                <table class="data-table" id="table-tm-tiendo">
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
                                    <tbody id="tbody-tm-tiendo">
                                        <!-- Dòng tiến độ 1 -->
                                        <tr>
                                            <td style="text-align: center;">1</td>
                                            <td><input type="text" name="tm_td_noidung[]" class="form-control" value="Tổng quan tài liệu, tìm hiểu bài toán s-Club và các thuật toán gần đúng"></td>
                                            <td><input type="text" name="tm_td_sanpham[]" class="form-control" value="Báo cáo tổng hợp"></td>
                                            <td><input type="text" name="tm_td_thoigian[]" class="form-control" value="Tháng 10/2025 đến tháng 12/2025"></td>
                                            <td><input type="text" name="tm_td_nguoith[]" class="form-control" value="Nhóm"></td>
                                            <td style="text-align: center;"><button type="button" class="btn btn-sm btn-danger btn-remove-row" style="padding: 2px 6px;">x</button></td>
                                        </tr>
                                        <!-- Dòng tiến độ 2 -->
                                        <tr>
                                            <td style="text-align: center;">2</td>
                                            <td><input type="text" name="tm_td_noidung[]" class="form-control" value="Nghiên cứu, cài đặt thử nghiệm các thuật toán CONSTELLATION, DROP, IDROP, GRASP"></td>
                                            <td><input type="text" name="tm_td_sanpham[]" class="form-control" value="Mã nguồn và bản mô tả thuật toán"></td>
                                            <td><input type="text" name="tm_td_thoigian[]" class="form-control" value="Đầu tháng 01/2026 đến tháng 04/2026"></td>
                                            <td><input type="text" name="tm_td_nguoith[]" class="form-control" value="Nhóm"></td>
                                            <td style="text-align: center;"><button type="button" class="btn btn-sm btn-danger btn-remove-row" style="padding: 2px 6px;">x</button></td>
                                        </tr>
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
                                <label class="form-label" style="font-weight: 600;">- Loại sản phẩm (Đánh dấu chọn các loại hình):</label>
                                <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; background: rgba(15, 23, 42, 0.5); padding: 14px; border-radius: var(--radius-md); border: 1px solid var(--border-color);">
                                    <?php 
                                    $defaultChecked = ['Báo cáo phân tích', 'Phần mềm máy tính'];
                                    foreach (NckhFormHelper::DANH_MUC_LOAI_SAN_PHAM as $spOpt): 
                                        $isChecked = in_array($spOpt, $defaultChecked);
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
                                    <label class="form-label" style="font-weight: 600; margin: 0;">- Tên sản phẩm, số lượng và yêu cầu khoa học đối với sản phẩm:</label>
                                    <button type="button" id="btn-add-tm-sanpham" class="btn btn-sm btn-secondary" style="display: inline-flex; align-items: center; gap: 4px;">
                                        <?= ViewHelper::icon('plus', '', 14) ?> Thêm Sản Phẩm
                                    </button>
                                </div>
                                <div class="table-responsive">
                                    <table class="data-table" id="table-tm-sanpham">
                                        <thead>
                                            <tr>
                                                <th style="width: 6%;">TT</th>
                                                <th style="width: 44%;">Tên sản phẩm</th>
                                                <th style="width: 14%;">Số lượng</th>
                                                <th style="width: 30%;">Yêu cầu khoa học</th>
                                                <th style="width: 6%; text-align: center;">Xóa</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbody-tm-sanpham">
                                            <tr>
                                                <td style="text-align: center;">1</td>
                                                <td><input type="text" name="tm_sp_ten[]" class="form-control" value="Báo cáo tổng kết đề tài “Nghiên cứu các thuật toán gần đúng giải các bài toán s-Club”"></td>
                                                <td><input type="text" name="tm_sp_soluong[]" class="form-control" value="01"></td>
                                                <td><input type="text" name="tm_sp_yeucau[]" class="form-control" value="Có đầy đủ cơ sở lý thuyết, kết quả thực nghiệm, biểu đồ và đánh giá khoa học."></td>
                                                <td style="text-align: center;">-</td>
                                            </tr>
                                            <tr>
                                                <td style="text-align: center;">2</td>
                                                <td><input type="text" name="tm_sp_ten[]" class="form-control" value="Chương trình mô phỏng cài đặt thuật toán"></td>
                                                <td><input type="text" name="tm_sp_soluong[]" class="form-control" value="01"></td>
                                                <td><input type="text" name="tm_sp_yeucau[]" class="form-control" value="Chạy ổn định trên máy tính, giao diện trực quan và hướng dẫn sử dụng."></td>
                                                <td style="text-align: center;"><button type="button" class="btn btn-sm btn-danger btn-remove-row" style="padding: 2px 6px;">x</button></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- 13.3 Địa chỉ có thể ứng dụng -->
                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label" style="font-weight: 600;">- Địa chỉ có thể ứng dụng (tên địa phương, đơn vị ứng dụng):</label>
                                <textarea name="tm_dia_chi_ap_dung" id="tm-dia-chi-ap-dung" class="form-control" rows="3">+ Khoa Công nghệ Thông tin, Trường Đại học Tây Bắc, tỉnh Sơn La.&#10;+ Kết quả nghiên cứu có thể ứng dụng trong giảng dạy và nghiên cứu các học phần, chuyên đề liên quan đến thuật toán tối ưu, trí tuệ nhân tạo và khoa học máy tính tại Trường Đại học Tây Bắc.</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- CHỮ KÝ XÁC NHẬN -->
                    <div class="grid-cols-2" style="background: rgba(15, 23, 42, 0.5); padding: 16px; border-radius: var(--radius-md);">
                        <div>
                            <label class="form-label" style="font-weight: 700;">Giảng viên hướng dẫn ký xác nhận</label>
                            <input type="text" name="tm_gvhd_ky" id="tm-gvhd-ky" class="form-control" value="TS. Phạm Đình Thành">
                        </div>
                        <div>
                            <label class="form-label" style="font-weight: 700;">Sinh viên chịu trách nhiệm chính</label>
                            <input type="text" name="tm_sv_ky" id="tm-sv-ky" class="form-control" value="<?= ViewHelper::e($userHoTen) ?>">
                        </div>
                    </div>
                </div>
            </div>

            <div style="display: flex; justify-content: space-between; gap: 12px;">
                <button type="button" class="btn btn-secondary btn-prev-tab" data-prev="tab-dexuat">
                    <?= ViewHelper::icon('arrow-left', '', 16) ?> Quay Lại Tab 2
                </button>
                <button type="button" class="btn btn-primary btn-next-tab" data-next="tab-files">
                    <span>Sang Bước 4: Tùy Chọn Tệp & Gửi</span>
                    <?= ViewHelper::icon('arrow-right', '', 16) ?>
                </button>
            </div>
        </div>

        <!-- =================================================================== -->
        <!-- TAB 4: TỆP ĐÍNH KÈM BỔ TRỢ (TÙY CHỌN)                              -->
        <!-- =================================================================== -->
        <div id="tab-files" class="tab-pane-nckh" style="display: none;">
            <div class="card" style="margin-bottom: 24px;">
                <div class="card-header">
                    <div class="card-title">
                        <?= ViewHelper::icon('paperclip', '', 18) ?>
                        <span>Tệp Đính Kèm Bổ Trợ (Không Bắt Buộc)</span>
                    </div>
                    <span class="badge badge-success">Đã tự động lưu trữ biểu mẫu trực tuyến</span>
                </div>
                <div class="card-body">
                    <div style="background: rgba(16, 185, 129, 0.08); border: 1px solid rgba(16, 185, 129, 0.25); border-radius: var(--radius-md); padding: 16px; margin-bottom: 20px;">
                        <div style="display: flex; align-items: center; gap: 10px; color: #34d399; font-weight: 600; margin-bottom: 4px;">
                            <?= ViewHelper::icon('check-circle', '', 18) ?>
                            <span>Hệ thống đã chuẩn hóa số hóa 2 biểu mẫu trực tiếp trên Web!</span>
                        </div>
                        <p style="color: #cbd5e1; font-size: 13.5px; margin: 0; line-height: 1.5;">
                            Bạn <strong>không bắt buộc</strong> phải tải lên file Word/PDF. Toàn bộ nội dung Phiếu Đề Xuất (Mẫu 02) và Thuyết Minh (Mẫu 01) đã được hệ thống lưu trực tiếp, Giảng viên và Cán bộ có thể xem, phê duyệt và in ấn văn bản chuẩn A4 ngay trên phần mềm.
                        </p>
                    </div>

                    <div class="grid-cols-2">
                        <div class="form-group">
                            <label class="form-label">Tệp Scan Phiếu Thuyết Minh Có Chữ Ký (Nếu có)</label>
                            <input type="file" name="file_thuyet_minh" class="form-control" accept=".pdf,.doc,.docx">
                            <div class="form-help">Tùy chọn: PDF hoặc Word tối đa 50MB.</div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Tệp Scan Phiếu Đề Xuất Có Chữ Ký (Nếu có)</label>
                            <input type="file" name="file_de_xuat" class="form-control" accept=".pdf,.doc,.docx">
                            <div class="form-help">Tùy chọn: PDF hoặc Word tối đa 50MB.</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- NÚT GỬI HỒ SƠ -->
            <div style="display: flex; justify-content: space-between; align-items: center; background: var(--bg-card); border: 1px solid var(--border-color); padding: 16px 20px; border-radius: var(--radius-lg);">
                <button type="button" class="btn btn-secondary btn-prev-tab" data-prev="tab-thuyetminh">
                    <?= ViewHelper::icon('arrow-left', '', 16) ?> Quay Lại Tab 3
                </button>
                <div style="display: flex; gap: 12px;">
                    <a href="<?= App::url('index.php?action=sv-dashboard') ?>" class="btn btn-secondary">
                        Hủy Bỏ
                    </a>
                    <button type="submit" class="btn btn-primary btn-lg" style="display: inline-flex; align-items: center; gap: 8px; font-weight: 700; padding: 12px 24px; box-shadow: 0 4px 14px 0 rgba(37, 99, 235, 0.4);">
                        <?= ViewHelper::icon('send', '', 18) ?> NỘP ĐỀ TÀI VÀ LƯU BIỂU MẪU LÊN HỆ THỐNG
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- JAVASCRIPT ĐIỀU KHIỂN GIAO DIỆN VÀ TÍNH NĂNG ĐIỀN MẪU -->
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

    // 2. Đồng bộ tên đề tài tự động giữa các tab
    const inputTenDeTai = document.getElementById('input-tendetai');
    const dxTenDeTai = document.getElementById('dx-ten-de-tai');
    const tmTenDeTai = document.getElementById('tm-ten-de-tai');

    inputTenDeTai.addEventListener('input', function() {
        dxTenDeTai.value = this.value;
        tmTenDeTai.value = this.value;
    });
    dxTenDeTai.addEventListener('input', function() {
        inputTenDeTai.value = this.value;
        tmTenDeTai.value = this.value;
    });
    tmTenDeTai.addEventListener('input', function() {
        inputTenDeTai.value = this.value;
        dxTenDeTai.value = this.value;
    });

    // 3. Đồng bộ Giảng viên hướng dẫn khi chọn dropdown
    const selectGVHD = document.getElementById('select-magvhd');
    selectGVHD.addEventListener('change', function() {
        const opt = this.options[this.selectedIndex];
        if (opt && opt.value) {
            const hoten = opt.getAttribute('data-hoten') || '';
            const hocvi = opt.getAttribute('data-hocvi') || '';
            const sdt = opt.getAttribute('data-sdt') || '';
            
            document.getElementById('dx-gvhd').value = (hocvi ? hocvi + ' ' : '') + hoten;
            document.getElementById('tm-gv-hoten').value = hoten;
            document.getElementById('tm-gv-hocvi').value = hocvi;
            document.getElementById('tm-gv-sdt').value = sdt;
            document.getElementById('tm-gvhd-ky').value = (hocvi ? hocvi + ' ' : '') + hoten;
        }
    });

    // 4. Thêm thành viên hệ thống Tab 1
    const btnAddSysMember = document.getElementById('btn-add-system-member');
    const sysContainer = document.getElementById('system-members-container');
    let memberIndex = 1;

    btnAddSysMember.addEventListener('click', function() {
        memberIndex++;
        const div = document.createElement('div');
        div.className = 'system-member-item';
        div.style = 'background: rgba(15, 23, 42, 0.4); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 14px; margin-bottom: 12px;';
        div.innerHTML = `
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                <span class="badge badge-secondary">Thành Viên Nhóm ${memberIndex}</span>
                <button type="button" class="btn btn-sm btn-danger btn-remove-sys-m" style="padding: 2px 8px;">Xóa</button>
            </div>
            <div class="grid-cols-2">
                <div>
                    <label class="form-label" style="font-size: 12px;">Mã Sinh Viên (Tài khoản) <span class="required">*</span></label>
                    <input type="text" name="extra_members[${memberIndex}][masv]" class="form-control" placeholder="VD: SV002, SV003..." required>
                </div>
                <div>
                    <label class="form-label" style="font-size: 12px;">Nhiệm Vụ Được Phân Công</label>
                    <input type="text" name="extra_members[${memberIndex}][nhiemvu]" class="form-control" value="Nghiên cứu và cài đặt thuật toán theo phân công">
                </div>
            </div>
        `;
        sysContainer.appendChild(div);

        div.querySelector('.btn-remove-sys-m').addEventListener('click', function() {
            div.remove();
        });
    });

    // 5. Thêm/xóa nội dung Phiếu Đề Xuất (Mục 4)
    const btnAddDxNd = document.getElementById('btn-add-dx-noidung');
    const dxNdContainer = document.getElementById('dx-noidung-container');
    let dxCount = 2;

    btnAddDxNd.addEventListener('click', function() {
        dxCount++;
        const card = document.createElement('div');
        card.className = 'dx-noidung-card';
        card.style = 'background: rgba(15, 23, 42, 0.4); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 14px;';
        card.innerHTML = `
            <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                <input type="text" name="dx_nd_tieude[]" class="form-control dx-nd-tt" style="font-weight: 700; max-width: 80%;" value="4.${dxCount}. Nội dung ${dxCount}: Thử nghiệm và hoàn thiện">
                <button type="button" class="btn btn-sm btn-danger btn-remove-dx-nd" style="padding: 4px 8px;">Xóa</button>
            </div>
            <textarea name="dx_nd_chitiet[]" class="form-control dx-nd-ct" rows="4" placeholder="Chi tiết nội dung..."></textarea>
        `;
        dxNdContainer.appendChild(card);
        card.querySelector('.btn-remove-dx-nd').addEventListener('click', () => card.remove());
    });

    dxNdContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-remove-dx-nd')) {
            e.target.closest('.dx-noidung-card').remove();
        }
    });

    // 6. Thêm/xóa hàng Thuyết Minh (Thành viên mục 6, Tiến độ mục 12, Sản phẩm mục 13.2)
    const tbodyThanhVien = document.getElementById('tbody-tm-thanhvien');
    document.getElementById('btn-add-tm-thanhvien').addEventListener('click', function() {
        const count = tbodyThanhVien.querySelectorAll('tr').length + 1;
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td style="text-align: center;">${count}</td>
            <td><input type="text" name="tm_tv_hoten[]" class="form-control" placeholder="Họ và tên..."></td>
            <td><input type="text" name="tm_tv_lop[]" class="form-control" placeholder="Lớp..."></td>
            <td><input type="text" name="tm_tv_congviec[]" class="form-control" placeholder="Công việc được giao..."></td>
            <td style="text-align: center;"><button type="button" class="btn btn-sm btn-danger btn-remove-row" style="padding: 2px 6px;">x</button></td>
        `;
        tbodyThanhVien.appendChild(tr);
    });

    const tbodyTienDo = document.getElementById('tbody-tm-tiendo');
    document.getElementById('btn-add-tm-tiendo').addEventListener('click', function() {
        const count = tbodyTienDo.querySelectorAll('tr').length + 1;
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td style="text-align: center;">${count}</td>
            <td><input type="text" name="tm_td_noidung[]" class="form-control" placeholder="Nội dung, công việc chủ yếu..."></td>
            <td><input type="text" name="tm_td_sanpham[]" class="form-control" placeholder="Dự kiến sản phẩm..."></td>
            <td><input type="text" name="tm_td_thoigian[]" class="form-control" placeholder="Thời gian..."></td>
            <td><input type="text" name="tm_td_nguoith[]" class="form-control" value="Nhóm"></td>
            <td style="text-align: center;"><button type="button" class="btn btn-sm btn-danger btn-remove-row" style="padding: 2px 6px;">x</button></td>
        `;
        tbodyTienDo.appendChild(tr);
    });

    const tbodySanPham = document.getElementById('tbody-tm-sanpham');
    document.getElementById('btn-add-tm-sanpham').addEventListener('click', function() {
        const count = tbodySanPham.querySelectorAll('tr').length + 1;
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td style="text-align: center;">${count}</td>
            <td><input type="text" name="tm_sp_ten[]" class="form-control" placeholder="Tên sản phẩm..."></td>
            <td><input type="text" name="tm_sp_soluong[]" class="form-control" value="01"></td>
            <td><input type="text" name="tm_sp_yeucau[]" class="form-control" placeholder="Yêu cầu khoa học đạt được..."></td>
            <td style="text-align: center;"><button type="button" class="btn btn-sm btn-danger btn-remove-row" style="padding: 2px 6px;">x</button></td>
        `;
        tbodySanPham.appendChild(tr);
    });

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-remove-row')) {
            e.target.closest('tr').remove();
        }
    });

    // 7. NẠP DỮ LIỆU MẪU s-Club CHUẨN TỰ ĐỘNG
    const sampleDX = <?= json_encode($sampleDX, JSON_UNESCAPED_UNICODE) ?>;
    const sampleTM = <?= json_encode($sampleTM, JSON_UNESCAPED_UNICODE) ?>;

    document.getElementById('btn-fill-sample').addEventListener('click', function() {
        if (!confirm('Bạn có muốn tự động điền toàn bộ dữ liệu mẫu chuẩn đề tài s-Club (ĐH Tây Bắc) vào cả 2 biểu mẫu trực tuyến không?')) {
            return;
        }

        // Tab 1 & Thông tin chung
        inputTenDeTai.value = sampleDX.ten_de_tai;
        dxTenDeTai.value = sampleDX.ten_de_tai;
        tmTenDeTai.value = sampleDX.ten_de_tai;
        document.getElementById('input-kinhphi').value = 5000000;

        // Chọn GVHD TS. Phạm Đình Thành nếu có
        for (let i = 0; i < selectGVHD.options.length; i++) {
            if (selectGVHD.options[i].text.includes('Phạm Đình Thành')) {
                selectGVHD.selectedIndex = i;
                break;
            }
        }

        // Tab 2: Phiếu đề xuất
        document.getElementById('dx-tinh-cap-thiet').value = sampleDX.tinh_cap_thiet;
        document.getElementById('dx-muc-tieu').value = sampleDX.muc_tieu;
        document.getElementById('dx-san-pham').value = sampleDX.du_kien_san_pham;
        document.getElementById('dx-doi-tuong').value = sampleDX.kha_nang_dia_chi.doi_tuong;
        document.getElementById('dx-dia-chi').value = sampleDX.kha_nang_dia_chi.dia_chi;
        document.getElementById('dx-kha-nang-mo-rong').value = sampleDX.kha_nang_dia_chi.kha_nang_mo_rong;
        document.getElementById('dx-gvhd').value = sampleDX.xac_nhan_gvhd;
        document.getElementById('dx-sinh-vien').value = sampleDX.ca_nhan_de_xuat;

        // Nội dung mục 4 đề xuất
        dxNdContainer.innerHTML = '';
        sampleDX.noi_dung_chu_yeu.forEach((nd, i) => {
            const card = document.createElement('div');
            card.className = 'dx-noidung-card';
            card.style = 'background: rgba(15, 23, 42, 0.4); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 14px;';
            card.innerHTML = `
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                    <input type="text" name="dx_nd_tieude[]" class="form-control dx-nd-tt" style="font-weight: 700; max-width: 80%;" value="${nd.tieu_de}">
                    ${i > 0 ? '<button type="button" class="btn btn-sm btn-danger btn-remove-dx-nd" style="padding: 4px 8px;">Xóa</button>' : ''}
                </div>
                <textarea name="dx_nd_chitiet[]" class="form-control dx-nd-ct" rows="4">${nd.chi_tiet}</textarea>
            `;
            dxNdContainer.appendChild(card);
        });

        // Tab 3: Thuyết minh đề tài
        document.getElementById('tm-nam-hoc').value = sampleTM.nam_hoc;
        document.getElementById('tm-nhom-nganh').value = sampleTM.nhom_nganh;
        document.getElementById('tm-thoi-gian').value = sampleTM.thoi_gian_thuc_hien;
        document.getElementById('tm-cn-hoten').value = sampleTM.chu_nhiem.ho_ten;
        document.getElementById('tm-cn-lop').value = sampleTM.chu_nhiem.lop;
        document.getElementById('tm-cn-sdt').value = sampleTM.chu_nhiem.dien_thoai;

        document.getElementById('tm-gv-hoten').value = sampleTM.gvhd.ho_ten;
        document.getElementById('tm-gv-hocvi').value = sampleTM.gvhd.chuc_danh_hoc_vi;
        document.getElementById('tm-gv-sdt').value = sampleTM.gvhd.dien_thoai;

        document.getElementById('tm-tong-quan').value = sampleTM.tinh_hinh_nc.tong_quan;
        document.getElementById('tm-cong-trinh').value = sampleTM.tinh_hinh_nc.cong_trinh_lien_quan;
        document.getElementById('tm-tinh-cap-thiet').value = sampleTM.tinh_cap_thiet;
        document.getElementById('tm-muc-tieu-chung').value = sampleTM.muc_tieu.muc_tieu_chung;
        document.getElementById('tm-muc-tieu-cu_the').value = sampleTM.muc_tieu.muc_tieu_cu_the;

        document.getElementById('tm-doi-tuong').value = sampleTM.doi_tuong_pham_vi_phuong_phap.doi_tuong;
        document.getElementById('tm-pham-vi').value = sampleTM.doi_tuong_pham_vi_phuong_phap.pham_vi;
        document.getElementById('tm-phuong-phap').value = sampleTM.doi_tuong_pham_vi_phuong_phap.phuong_phap;

        // Thành viên mục 6
        tbodyThanhVien.innerHTML = '';
        sampleTM.thanh_vien_tham_gia.forEach((tv, i) => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td style="text-align: center;">${tv.tt}</td>
                <td><input type="text" name="tm_tv_hoten[]" class="form-control" value="${tv.ho_ten}"></td>
                <td><input type="text" name="tm_tv_lop[]" class="form-control" value="${tv.lop}"></td>
                <td><input type="text" name="tm_tv_congviec[]" class="form-control" value="${tv.cong_viec}"></td>
                <td style="text-align: center;">${i > 0 ? '<button type="button" class="btn btn-sm btn-danger btn-remove-row" style="padding: 2px 6px;">x</button>' : '-'}</td>
            `;
            tbodyThanhVien.appendChild(tr);
        });

        // Tiến độ mục 12
        tbodyTienDo.innerHTML = '';
        sampleTM.tien_do_thuc_hien.forEach(td => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td style="text-align: center;">${td.tt}</td>
                <td><input type="text" name="tm_td_noidung[]" class="form-control" value="${td.noi_dung}"></td>
                <td><input type="text" name="tm_td_sanpham[]" class="form-control" value="${td.san_pham}"></td>
                <td><input type="text" name="tm_td_thoigian[]" class="form-control" value="${td.thoi_gian}"></td>
                <td><input type="text" name="tm_td_nguoith[]" class="form-control" value="${td.nguoi_thuc_hien}"></td>
                <td style="text-align: center;"><button type="button" class="btn btn-sm btn-danger btn-remove-row" style="padding: 2px 6px;">x</button></td>
            `;
            tbodyTienDo.appendChild(tr);
        });

        // Sản phẩm mục 13.2
        tbodySanPham.innerHTML = '';
        sampleTM.san_pham_ung_dung.danh_sach.forEach(sp => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td style="text-align: center;">${sp.tt}</td>
                <td><input type="text" name="tm_sp_ten[]" class="form-control" value="${sp.ten_san_pham}"></td>
                <td><input type="text" name="tm_sp_soluong[]" class="form-control" value="${sp.so_luong}"></td>
                <td><input type="text" name="tm_sp_yeucau[]" class="form-control" value="${sp.yeu_cau_khoa_hoc}"></td>
                <td style="text-align: center;"><button type="button" class="btn btn-sm btn-danger btn-remove-row" style="padding: 2px 6px;">x</button></td>
            `;
            tbodySanPham.appendChild(tr);
        });

        document.getElementById('tm-dia-chi-ap-dung').value = sampleTM.san_pham_ung_dung.dia_chi_ap_dung;
        document.getElementById('tm-gvhd-ky').value = sampleTM.gvhd_ky;
        document.getElementById('tm-sv-ky').value = sampleTM.sv_ky;

        alert('Đã nạp toàn bộ dữ liệu mẫu chuẩn đề tài s-Club vào các biểu mẫu! Bạn có thể kiểm tra ở các tab 2, 3 và chỉnh sửa theo ý muốn.');
    });
});
</script>

<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>

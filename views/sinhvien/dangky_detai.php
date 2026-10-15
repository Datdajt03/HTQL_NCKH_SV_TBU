<?php
use App\Config\App;
use App\Helpers\ViewHelper;

$pageTitle = "Đăng Ký Đề Tài NCKH Sinh Viên";
require dirname(__DIR__) . '/layouts/header.php';
?>
<div style="max-width: 960px; margin: 0 auto;">
    <div style="margin-bottom: 24px;">
        <h2 style="font-size: 20px; font-weight: 800; color: #fff; margin-bottom: 4px;">2. Đăng Ký Đề Tài Nghiên Cứu Khoa Học Mới</h2>
        <p style="color: var(--text-secondary); font-size: 14px; margin: 0;">
            Điền đầy đủ thông tin đề tài, lập nhóm nghiên cứu, chọn Giảng viên hướng dẫn và tải lên Phiếu thuyết minh, Phiếu đề xuất.
        </p>
    </div>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger">
            <span><?= ViewHelper::icon('alert-circle', '', 18) ?></span>
            <div><?= ViewHelper::e($error) ?></div>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?= App::url('index.php?action=sv-dangky-detai') ?>" enctype="multipart/form-data">
        <!-- PHẦN 1: THÔNG TIN CHUNG ĐỀ TÀI -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <span><?= ViewHelper::icon('file-text', '', 18) ?></span>
                    <span>Thông Tin Chung Đề Tài</span>
                </div>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Tên Đề Tài NCKH <span class="required">*</span></label>
                    <input type="text" name="tendetai" class="form-control" placeholder="Nhập tên đề tài nghiên cứu đầy đủ, rõ ràng..." required>
                </div>

                <div class="grid-cols-2">
                    <div class="form-group">
                        <label class="form-label">Đợt Phát Động NCKH <span class="required">*</span></label>
                        <select name="madot" class="form-control" required>
                            <option value="">-- Chọn đợt NCKH áp dụng --</option>
                            <?php foreach ($activeDots as $d): ?>
                                <option value="<?= ViewHelper::e($d['MaDot']) ?>">
                                    <?= ViewHelper::e($d['TenDot']) ?> (Hạn ĐK: <?= ViewHelper::date($d['HanDangKy']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Hướng Nghiên Cứu <span class="required">*</span></label>
                        <select name="mahuongnc" class="form-control" required>
                            <option value="">-- Chọn hướng nghiên cứu phù hợp --</option>
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
                        <select name="magvhd" class="form-control" required>
                            <option value="">-- Chọn Giảng viên hướng dẫn --</option>
                            <?php foreach ($teachers as $gv): ?>
                                <option value="<?= ViewHelper::e($gv['MaNguoiDung']) ?>">
                                    <?= ViewHelper::e(($gv['HocHam'] ? $gv['HocHam'] . ' ' : '') . $gv['HocVi'] . ' ' . $gv['HoTen']) ?> (<?= ViewHelper::e($gv['DonViCongTac']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="form-help">Sau khi nộp, đề tài sẽ được gửi trực tiếp đến GVHD để xét duyệt.</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Kinh Phí Dự Trù (VNĐ)</label>
                        <input type="number" name="kinhphi" class="form-control" placeholder="VD: 5000000" min="0" step="500000" value="5000000">
                        <div class="form-help">Dự trù kinh phí hỗ trợ từ nhà trường và các nguồn khác (nếu có).</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- PHẦN 2: NỘI DUNG VÀ SẢN PHẨM DỰ KIẾN -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <span><?= ViewHelper::icon('target', '', 18) ?></span>
                    <span>Mục Tiêu & Sản Phẩm Dự Kiến</span>
                </div>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Mục Tiêu Nghiên Cứu (Tóm tắt Phiếu đề xuất) <span class="required">*</span></label>
                    <textarea name="muctieu" class="form-control" rows="3" placeholder="Mục tiêu tổng quát và mục tiêu cụ thể mà đề tài hướng tới giải quyết..." required></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Nội Dung Nghiên Cứu Chính <span class="required">*</span></label>
                    <textarea name="noidungnc" class="form-control" rows="4" placeholder="Các công việc, phương pháp, kỹ thuật, công nghệ thực hiện nghiên cứu..." required></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Sản Phẩm Dự Kiến Đạt Được <span class="required">*</span></label>
                    <textarea name="sanphamdukien" class="form-control" rows="3" placeholder="Phần mềm chạy thử nghiệm, bài báo hội nghị, mô hình phần cứng, tài liệu kỹ thuật..." required></textarea>
                </div>
            </div>
        </div>

        <!-- PHẦN 3: FILE ĐÍNH KÈM -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <span><?= ViewHelper::icon('paperclip', '', 18) ?></span>
                    <span>Đính Kèm Phiếu Thuyết Minh & Phiếu Đề Xuất</span>
                </div>
            </div>
            <div class="card-body">
                <div class="grid-cols-2">
                    <div class="form-group">
                        <label class="form-label">File Phiếu Thuyết Minh (Mẫu 01) <span class="required">*</span></label>
                        <input type="file" name="file_thuyet_minh" class="form-control" accept=".pdf,.doc,.docx" required>
                        <div class="form-help">Định dạng chấp nhận: PDF, Word (.docx) tối đa 50MB.</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">File Phiếu Đề Xuất (Mẫu 02) <span class="required">*</span></label>
                        <input type="file" name="file_de_xuat" class="form-control" accept=".pdf,.doc,.docx" required>
                        <div class="form-help">Định dạng chấp nhận: PDF, Word (.docx) tối đa 50MB.</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- PHẦN 4: NHÓM SINH VIÊN THỰC HIỆN -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <span><?= ViewHelper::icon('users', '', 18) ?></span>
                    <span>Nhóm Sinh Viên Thực Hiện Đề Tài</span>
                </div>
                <button type="button" id="btn-add-member" class="btn btn-sm btn-secondary" style="border-color: rgba(59, 130, 246, 0.4); display: inline-flex; align-items: center; gap: 6px;">
                    <?= ViewHelper::icon('plus', '', 14) ?> Thêm Thành Viên Nhóm
                </button>
            </div>
            <div class="card-body">
                <!-- Trưởng nhóm cố định -->
                <div style="background: rgba(59, 130, 246, 0.08); border: 1px solid rgba(59, 130, 246, 0.25); border-radius: var(--radius-md); padding: 16px; margin-bottom: 16px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                        <span class="badge badge-amber">Chủ Nhiệm Đề Tài (Trưởng Nhóm)</span>
                        <span style="font-size: 13px; color: var(--text-muted);">Mã SV: <strong><?= ViewHelper::e($studentId) ?></strong></span>
                    </div>
                    <div class="grid-cols-2" style="margin-bottom: 0;">
                        <div>
                            <label class="form-label" style="font-size: 12px;">Họ và Tên</label>
                            <input type="text" class="form-control" value="<?= ViewHelper::e($user['HoTen']) ?>" readonly disabled style="opacity: 0.8;">
                        </div>
                        <div>
                            <label class="form-label" style="font-size: 12px;">Nhiệm Vụ Cụ Thể</label>
                            <input type="text" name="nhiemvu_truongnhom" class="form-control" value="Chủ nhiệm đề tài, quản lý tiến độ và điều phối chung" required>
                        </div>
                    </div>
                </div>

                <!-- Danh sách thành viên thêm động -->
                <div id="member-list-container"></div>
            </div>
        </div>

        <div style="display: flex; gap: 14px; justify-content: flex-end; margin-top: 24px; margin-bottom: 40px;">
            <a href="<?= App::url('index.php?action=sv-dashboard') ?>" class="btn btn-secondary btn-lg">
                Hủy Bỏ
            </a>
            <button type="submit" class="btn btn-primary btn-lg" style="display: inline-flex; align-items: center; gap: 8px;">
                <?= ViewHelper::icon('send', '', 18) ?> Gửi Đăng Ký Đề Tài Lên Hệ Thống
            </button>
        </div>
    </form>
</div>
<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>

<?php
use App\Config\App;
use App\Helpers\ViewHelper;

$pageTitle = "Báo Cáo Tổng Kết & Sản Phẩm Nghiên Cứu";
require dirname(__DIR__) . '/layouts/header.php';
?>
<div style="max-width: 960px; margin: 0 auto;">
    <div style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h2 style="font-size: 20px; font-weight: 800; color: #fff; margin-bottom: 4px;">
                5. Nộp Báo Cáo Tổng Kết & Sản Phẩm Nghiên Cứu
            </h2>
            <p style="color: var(--text-secondary); font-size: 14px; margin: 0;">
                Đề tài: <strong style="color: #60a5fa;"><?= ViewHelper::e($topic['MaDeTai']) ?></strong> - <?= ViewHelper::e($topic['TenDeTai']) ?>
            </p>
        </div>
        <a href="<?= App::url('index.php?action=sv-dashboard') ?>" class="btn btn-secondary btn-sm">
            &larr; Về Dashboard
        </a>
    </div>

    <!-- TRẠNG THÁI ĐÁNH GIÁ SƠ BỘ TỪ GVHD NẾU ĐÃ NỘP -->
    <?php if ($existingReport): ?>
        <div class="card" style="background: rgba(30, 41, 59, 0.9); border-color: rgba(59, 130, 246, 0.3);">
            <div class="card-header">
                <div class="card-title">
                    <span><?= ViewHelper::icon('check-circle', '', 18) ?></span>
                    <span>Tình Trạng Đánh Giá Sơ Bộ Nghiệm Thu Từ GVHD</span>
                </div>
                <?= ViewHelper::statusBadge($existingReport['DanhGiaSoBoGVHD']) ?>
            </div>
            <div class="card-body">
                <div class="grid-cols-2" style="margin-bottom: 12px;">
                    <div>
                        <span style="color: var(--text-muted); font-size: 13px;">Thời điểm nộp:</span>
                        <strong style="color: #fff; margin-left: 6px;"><?= ViewHelper::datetime($existingReport['NgayNop']) ?></strong>
                    </div>
                    <div>
                        <span style="color: var(--text-muted); font-size: 13px;">GVHD xác nhận:</span>
                        <strong style="color: #fff; margin-left: 6px;"><?= ViewHelper::datetime($existingReport['NgayGVHDDanhGia']) ?></strong>
                    </div>
                </div>

                <?php if (!empty($existingReport['NhanXetSoBoGVHD'])): ?>
                    <div style="background: rgba(15, 23, 42, 0.6); padding: 14px 18px; border-radius: var(--radius-md); border-left: 4px solid var(--accent-blue);">
                        <strong style="color: #60a5fa; font-size: 13px; display: block; margin-bottom: 4px;">Ý kiến nhận xét của Giảng viên hướng dẫn:</strong>
                        <div style="color: #e2e8f0; font-size: 14px;"><?= nl2br(ViewHelper::e($existingReport['NhanXetSoBoGVHD'])) ?></div>
                    </div>
                <?php endif; ?>

                <?php if ($existingReport['DanhGiaSoBoGVHD'] === 'DONG_Y_NGHIEM_THU'): ?>
                    <div style="margin-top: 14px; font-size: 14px; color: #34d399; font-weight: 600; display: flex; align-items: center; gap: 6px;">
                        <?= ViewHelper::icon('check-circle', '', 16) ?> Giảng viên hướng dẫn đã chấp thuận cho phép nghiệm thu! Cán bộ quản lý NCKH sẽ thành lập Hội đồng và xếp lịch bảo vệ cho đề tài của bạn.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger">
            <span><?= ViewHelper::icon('alert-circle', '', 18) ?></span>
            <div><?= ViewHelper::e($error) ?></div>
        </div>
    <?php endif; ?>

    <!-- FORM NỘP BÁO CÁO TỔNG KẾT -->
    <form method="POST" action="<?= App::url('index.php?action=sv-baocao-tongket&id=' . $topic['MaDeTai']) ?>" enctype="multipart/form-data">
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <span><?= ViewHelper::icon('file-text', '', 18) ?></span>
                    <span>Toàn Văn Báo Cáo Tổng Kết</span>
                </div>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Tóm Tắt Kết Quả Nghiên Cứu Đạt Được <span class="required">*</span></label>
                    <textarea name="tomtat" class="form-control" rows="5" placeholder="Tóm tắt ngắn gọn các phát hiện, mô hình đã xây dựng, kết quả đo lường, tính ứng dụng thực tiễn của đề tài..." required><?= ViewHelper::e($existingReport['TomTatKetQua'] ?? '') ?></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">File Toàn Văn Báo Cáo Tổng Kết (PDF / Word) <span class="required">*</span></label>
                    <input type="file" name="file_baocaotongket" class="form-control" accept=".pdf,.doc,.docx" <?= empty($existingReport['FileBaoCaoTongKet']) ? 'required' : '' ?>>
                    <?php if (!empty($existingReport['FileBaoCaoTongKet'])): ?>
                        <div class="form-help">
                            File hiện tại: <a href="<?= App::url('public/' . $existingReport['FileBaoCaoTongKet']) ?>" target="_blank" style="color: #60a5fa;">Tải file báo cáo cũ</a> (Chọn file mới nếu muốn thay đổi).
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- DANH SÁCH SẢN PHẨM NGHIÊN CỨU KÈM THEO -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <span><?= ViewHelper::icon('folder', '', 18) ?></span>
                    <span>Sản Phẩm Nghiên Cứu Kèm Theo (Phần mềm, Bài báo, Mô hình...)</span>
                </div>
                <button type="button" id="btn-add-product" class="btn btn-sm btn-secondary" style="border-color: rgba(139, 92, 246, 0.4); color: #c4b5fd; display: inline-flex; align-items: center; gap: 6px;">
                    <?= ViewHelper::icon('plus', '', 14) ?> Thêm Sản Phẩm Khác
                </button>
            </div>
            <div class="card-body">
                <div id="product-list-container">
                    <?php 
                    $products = $existingReport['san_pham'] ?? [];
                    if (empty($products)) {
                        $products = [
                            ['TenSanPham' => '', 'LoaiSanPham' => 'PHAN_MEM', 'MoTaSanPham' => '', 'LienKetMinhChung' => '', 'FileMinhChung' => null]
                        ];
                    }
                    foreach ($products as $idx => $p): 
                    ?>
                        <div class="product-row card" style="padding: 16px; margin-bottom: 16px; background: rgba(15, 23, 42, 0.4);">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                                <strong style="color: #a78bfa; font-size: 13px;">Sản phẩm nghiên cứu #<?= $idx + 1 ?></strong>
                                <?php if ($idx > 0): ?>
                                    <button type="button" class="btn btn-sm btn-danger btn-remove-row" onclick="this.closest('.product-row').remove();" style="padding: 4px 10px; font-size: 11px; display: inline-flex; align-items: center; gap: 4px;">
                                        <?= ViewHelper::icon('trash', '', 12) ?> Xóa
                                    </button>
                                <?php endif; ?>
                            </div>
                            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 12px; margin-bottom: 10px;">
                                <div>
                                    <label class="form-label" style="font-size: 12px;">Tên sản phẩm <span class="required">*</span></label>
                                    <input type="text" name="products[<?= $idx ?>][ten]" class="form-control" value="<?= ViewHelper::e($p['TenSanPham']) ?>" placeholder="VD: Phần mềm SmartParking, Bài báo IEEE..." required>
                                </div>
                                <div>
                                    <label class="form-label" style="font-size: 12px;">Loại sản phẩm <span class="required">*</span></label>
                                    <select name="products[<?= $idx ?>][loai]" class="form-control" required>
                                        <option value="PHAN_MEM" <?= $p['LoaiSanPham'] === 'PHAN_MEM' ? 'selected' : '' ?>>Phần mềm ứng dụng</option>
                                        <option value="BAI_BAO_KHOA_HOC" <?= $p['LoaiSanPham'] === 'BAI_BAO_KHOA_HOC' ? 'selected' : '' ?>>Bài báo khoa học</option>
                                        <option value="MO_HINH" <?= $p['LoaiSanPham'] === 'MO_HINH' ? 'selected' : '' ?>>Mô hình / Thiết bị</option>
                                        <option value="QUY_TRINH" <?= $p['LoaiSanPham'] === 'QUY_TRINH' ? 'selected' : '' ?>>Quy trình công nghệ</option>
                                    </select>
                                </div>
                            </div>
                            <div style="margin-bottom: 10px;">
                                <label class="form-label" style="font-size: 12px;">Mô tả chi tiết sản phẩm</label>
                                <textarea name="products[<?= $idx ?>][mota]" class="form-control" rows="2" placeholder="Tính năng chính, công nghệ ứng dụng..."><?= ViewHelper::e($p['MoTaSanPham']) ?></textarea>
                            </div>
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                                <div>
                                    <label class="form-label" style="font-size: 12px;">Liên kết minh chứng (URL Demo / GitHub / Bài báo online)</label>
                                    <input type="url" name="products[<?= $idx ?>][link]" class="form-control" value="<?= ViewHelper::e($p['LienKetMinhChung']) ?>" placeholder="https://...">
                                </div>
                                <div>
                                    <label class="form-label" style="font-size: 12px;">File minh chứng (PDF/ZIP)</label>
                                    <input type="file" name="product_file_<?= $idx ?>" class="form-control">
                                    <?php if (!empty($p['FileMinhChung'])): ?>
                                        <div class="form-help">
                                            <a href="<?= App::url('public/' . $p['FileMinhChung']) ?>" target="_blank" style="color: #60a5fa;">Tải file minh chứng hiện tại</a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div style="display: flex; gap: 14px; justify-content: flex-end; margin-top: 24px; margin-bottom: 40px;">
            <a href="<?= App::url('index.php?action=sv-dashboard') ?>" class="btn btn-secondary btn-lg">
                Hủy Bỏ
            </a>
            <button type="submit" class="btn btn-primary btn-lg" style="display: inline-flex; align-items: center; gap: 8px;">
                <?= ViewHelper::icon('send', '', 18) ?> Nộp Báo Cáo Tổng Kết & Sản Phẩm
            </button>
        </div>
    </form>
</div>
<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>

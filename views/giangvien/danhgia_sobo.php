<?php
use App\Config\App;
use App\Helpers\ViewHelper;

$pageTitle = "Đánh Giá Sơ Bộ Sản Phẩm & Nghiệm Thu";
require dirname(__DIR__) . '/layouts/header.php';
?>
<div style="margin-bottom: 24px;">
    <h2 style="font-size: 20px; font-weight: 800; color: #fff; margin-bottom: 4px;">
        10. Đánh Giá Sơ Bộ Sản Phẩm & Cho Phép Nghiệm Thu
    </h2>
    <p style="color: var(--text-secondary); font-size: 14px; margin: 0;">
        Xem xét toàn văn Báo cáo tổng kết và các sản phẩm nghiên cứu thực tế của nhóm sinh viên trước khi đưa ra Hội đồng.
    </p>
</div>

<?php if (empty($summaryReviewList)): ?>
    <div class="card" style="text-align: center; padding: 48px; color: var(--text-muted);">
        <div style="margin-bottom: 12px; color: var(--text-muted);"><?= ViewHelper::icon('folder', '', 44) ?></div>
        <h3 style="color: #fff; font-size: 16px;">Chưa có đề tài nào nộp Báo cáo tổng kết</h3>
        <p style="font-size: 14px; margin-top: 6px;">Khi nhóm sinh viên hoàn thành nghiên cứu và nộp báo cáo tổng kết, hồ sơ sẽ hiển thị tại đây.</p>
    </div>
<?php else: ?>
    <?php foreach ($summaryReviewList as $item): 
        $t = $item['topic'];
        $report = $item['report'];
    ?>
        <div class="card" style="margin-bottom: 28px;">
            <div class="card-header">
                <div>
                    <span style="color: #60a5fa; font-weight: 700; font-size: 13px;"><?= ViewHelper::e($t['MaDeTai']) ?></span>
                    <h3 style="font-size: 17px; font-weight: 800; color: #fff; margin-top: 2px;">
                        <?= ViewHelper::e($t['TenDeTai']) ?>
                    </h3>
                </div>
                <?= ViewHelper::statusBadge($report['DanhGiaSoBoGVHD']) ?>
            </div>
            <div class="card-body">
                <!-- TÓM TẮT KẾT QUẢ & FILE -->
                <div style="background: rgba(15, 23, 42, 0.6); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 18px; margin-bottom: 20px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                        <strong style="color: #60a5fa; font-size: 14px;">Tóm tắt kết quả nghiên cứu của nhóm:</strong>
                        <a href="<?= App::url('public/' . $report['FileBaoCaoTongKet']) ?>" target="_blank" class="btn btn-sm btn-primary" style="display: inline-flex; align-items: center; gap: 6px;">
                            <?= ViewHelper::icon('download', '', 14) ?> Tải Báo Cáo Toàn Văn (PDF/Word)
                        </a>
                    </div>
                    <div style="color: #e2e8f0; font-size: 14px; line-height: 1.6;">
                        <?= nl2br(ViewHelper::e($report['TomTatKetQua'])) ?>
                    </div>
                </div>

                <!-- DANH SÁCH SẢN PHẨM KÈM THEO -->
                <div style="margin-bottom: 24px;">
                    <strong style="color: #fff; font-size: 14px; display: block; margin-bottom: 10px;">
                        Danh Sách Sản Phẩm Nghiên Cứu Đính Kèm (<?= count($report['san_pham']) ?> sản phẩm):
                    </strong>
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Tên Sản Phẩm</th>
                                    <th>Phân Loại</th>
                                    <th>Mô Tả Chức Năng</th>
                                    <th>Minh Chứng (Link / File)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($report['san_pham'] as $sp): ?>
                                    <tr>
                                        <td><strong style="color: #fff;"><?= ViewHelper::e($sp['TenSanPham']) ?></strong></td>
                                        <td><span class="badge badge-purple"><?= ViewHelper::e($sp['LoaiSanPham']) ?></span></td>
                                        <td style="max-width: 300px;"><?= ViewHelper::e($sp['MoTaSanPham']) ?></td>
                                        <td>
                                            <?php if (!empty($sp['LienKetMinhChung'])): ?>
                                                <a href="<?= ViewHelper::e($sp['LienKetMinhChung']) ?>" target="_blank" style="color: #38bdf8; display: block; margin-bottom: 4px;"><?= ViewHelper::icon('external-link', '', 13) ?> Link Demo/Bài báo</a>
                                            <?php endif; ?>
                                            <?php if (!empty($sp['FileMinhChung'])): ?>
                                                <a href="<?= App::url('public/' . $sp['FileMinhChung']) ?>" target="_blank" class="btn btn-sm btn-secondary"><?= ViewHelper::icon('file-text', '', 13) ?> File minh chứng</a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- FORM ĐÁNH GIÁ SƠ BỘ VÀ CHO PHÉP NGHIỆM THU -->
                <div style="background: rgba(15, 23, 42, 0.8); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 20px;">
                    <h4 style="color: #fff; font-size: 15px; margin-bottom: 14px; display: flex; align-items: center; gap: 8px;">
                        <span><?= ViewHelper::icon('check-circle', '', 18) ?></span>
                        <span>Đánh Giá Sơ Bộ Của Giảng Viên Hướng Dẫn</span>
                    </h4>

                    <form method="POST" action="<?= App::url('index.php?action=gv-danhgia-sobo') ?>">
                        <input type="hidden" name="report_id" value="<?= $report['MaBaoCaoTK'] ?>">

                        <div class="form-group">
                            <label class="form-label">Ý Kiến Nhận Xét Sơ Bộ Về Sản Phẩm & Báo Cáo <span class="required">*</span></label>
                            <textarea name="nhan_xet_so_bo" class="form-control" rows="3" placeholder="Đánh giá tính hoàn thiện của sản phẩm, khối lượng đạt được so với đề xuất ban đầu và khả năng bảo vệ..." required><?= ViewHelper::e($report['NhanXetSoBoGVHD'] ?? '') ?></textarea>
                        </div>

                        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 16px;">
                            <div style="font-size: 13px; color: var(--text-muted);">
                                <?php if ($report['NgayGVHDDanhGia']): ?>
                                    Lần đánh giá gần nhất: <strong><?= ViewHelper::datetime($report['NgayGVHDDanhGia']) ?></strong>
                                <?php endif; ?>
                            </div>

                            <div style="display: flex; gap: 12px;">
                                <button type="submit" name="danh_gia_so_bo" value="KHONG_DONG_Y" class="btn btn-danger" onclick="return confirm('Xác nhận đề tài chưa đủ điều kiện nghiệm thu và yêu cầu sinh viên chỉnh sửa lại?');">
                                    <?= ViewHelper::icon('x', '', 14) ?> Chưa Đạt - Yêu Cầu Chỉnh Sửa
                                </button>
                                <button type="submit" name="danh_gia_so_bo" value="DONG_Y_NGHIEM_THU" class="btn btn-success">
                                    <?= ViewHelper::icon('check', '', 14) ?> Đồng Ý Cho Phép Nghiệm Thu
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>

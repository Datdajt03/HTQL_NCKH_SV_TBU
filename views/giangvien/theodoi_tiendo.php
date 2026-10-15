<?php
use App\Config\App;
use App\Helpers\ViewHelper;

$pageTitle = "Theo Dõi Tiến Độ Đề Tài NCKH";
require dirname(__DIR__) . '/layouts/header.php';
?>
<div style="margin-bottom: 24px;">
    <h2 style="font-size: 20px; font-weight: 800; color: #fff; margin-bottom: 4px;">
        9. Theo Dõi & Ghi Nhận Xét Tiến Độ Thực Hiện Đề Tài
    </h2>
    <p style="color: var(--text-secondary); font-size: 14px; margin: 0;">
        Giám sát khối lượng công việc, đánh giá tiến độ định kỳ và đưa ra định hướng hỗ trợ nhóm nghiên cứu kịp thời.
    </p>
</div>

<?php foreach ($topicsWithReports as $item): 
    $t = $item['topic'];
    $reports = $item['reports'];
?>
    <div class="card" style="margin-bottom: 28px;">
        <div class="card-header">
            <div>
                <span style="color: #60a5fa; font-weight: 700; font-size: 13px;"><?= ViewHelper::e($t['MaDeTai']) ?></span>
                <h3 style="font-size: 17px; font-weight: 800; color: #fff; margin-top: 2px;">
                    <?= ViewHelper::e($t['TenDeTai']) ?>
                </h3>
                <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">
                    Trưởng nhóm: <strong style="color: #fff;"><?= ViewHelper::e($t['TenTruongNhom']) ?></strong> | 
                    Hướng NC: <?= ViewHelper::e($t['TenHuongNC']) ?>
                </div>
            </div>
            <?= ViewHelper::statusBadge($t['TrangThai']) ?>
        </div>
        <div class="card-body">
            <?php if (empty($reports)): ?>
                <div style="text-align: center; padding: 24px; color: var(--text-muted); font-size: 14px;">
                    Nhóm sinh viên chưa nộp đợt báo cáo tiến độ nào.
                </div>
            <?php else: ?>
                <div style="display: flex; flex-direction: column; gap: 16px;">
                    <?php foreach ($reports as $r): ?>
                        <div style="background: rgba(15, 23, 42, 0.6); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 18px;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <strong style="color: #fff; font-size: 15px;"><?= ViewHelper::e($r['DotBaoCao']) ?></strong>
                                    <span style="font-size: 13px; color: var(--text-muted);">(Nộp ngày: <?= ViewHelper::datetime($r['NgayNop']) ?>)</span>
                                </div>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <span style="font-weight: 800; color: #38bdf8; font-size: 16px;"><?= $r['PhanTramHoanThanh'] ?>%</span>
                                    <a href="<?= App::url('public/' . $r['FileBaoCao']) ?>" target="_blank" class="btn btn-sm btn-secondary" style="display: inline-flex; align-items: center; gap: 6px;">
                                        <?= ViewHelper::icon('file-text', '', 14) ?> Tải file báo cáo
                                    </a>
                                </div>
                            </div>

                            <div class="progress-track" style="margin-bottom: 14px;">
                                <div class="progress-fill" style="width: <?= $r['PhanTramHoanThanh'] ?>%;"></div>
                            </div>

                            <div style="margin-bottom: 12px; font-size: 14px; color: #e2e8f0;">
                                <strong style="color: #60a5fa; font-size: 13px; display: block; margin-bottom: 4px;">Công việc nhóm đã làm:</strong>
                                <?= nl2br(ViewHelper::e($r['CongViecDaLam'])) ?>
                            </div>

                            <?php if (!empty($r['KhoKhanVuongMac'])): ?>
                                <div style="background: rgba(245, 158, 11, 0.08); border-left: 3px solid #f59e0b; padding: 10px 14px; border-radius: 4px; font-size: 13px; color: #fde68a; margin-bottom: 14px;">
                                    <strong>Khó khăn, vướng mắc của sinh viên:</strong><br>
                                    <?= nl2br(ViewHelper::e($r['KhoKhanVuongMac'])) ?>
                                </div>
                            <?php endif; ?>

                            <!-- FORM ĐÁNH GIÁ CỦA GVHD -->
                            <div style="border-top: 1px solid var(--border-color); padding-top: 14px; margin-top: 14px;">
                                <?php if ($r['DanhGiaTienDo']): ?>
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                                        <div style="display: flex; align-items: center; gap: 8px;">
                                            <span style="font-size: 13px; color: var(--text-muted);">Đã đánh giá:</span>
                                            <?= ViewHelper::statusBadge($r['DanhGiaTienDo']) ?>
                                        </div>
                                        <span style="font-size: 12px; color: var(--text-muted);">Ngày đánh giá: <?= ViewHelper::datetime($r['NgayDanhGia']) ?></span>
                                    </div>
                                    <div style="font-size: 13px; color: #cbd5e1; background: rgba(15, 23, 42, 0.4); padding: 10px 14px; border-radius: var(--radius-sm);">
                                        <strong>Nhận xét:</strong> <?= nl2br(ViewHelper::e($r['NhanXetGVHD'])) ?>
                                    </div>
                                <?php else: ?>
                                    <form method="POST" action="<?= App::url('index.php?action=gv-theodoi-tiendo') ?>">
                                        <input type="hidden" name="report_id" value="<?= $r['MaBaoCaoTD'] ?>">
                                        
                                        <div class="grid-cols-2" style="margin-bottom: 12px;">
                                            <div>
                                                <label class="form-label" style="font-size: 12px;">Đánh Giá Tiến Độ <span class="required">*</span></label>
                                                <select name="danh_gia" class="form-control" required>
                                                    <option value="">-- Chọn kết luận tiến độ --</option>
                                                    <option value="DAT_TIEN_DO">Đạt tiến độ theo kế hoạch</option>
                                                    <option value="CHAM_TIEN_DO">Chậm tiến độ, cần đẩy nhanh</option>
                                                    <option value="CAN_NHAC_NHO">Chậm nghiêm trọng, cần nhắc nhở</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="form-label" style="font-size: 12px;">Nhận Xét & Chỉ Đạo Của GVHD <span class="required">*</span></label>
                                                <input type="text" name="nhan_xet" class="form-control" placeholder="Ghi nhận xét, góp ý hướng giải quyết..." required>
                                            </div>
                                        </div>

                                        <div style="display: flex; justify-content: flex-end;">
                                            <button type="submit" class="btn btn-sm btn-primary" style="display: inline-flex; align-items: center; gap: 6px;">
                                                <?= ViewHelper::icon('check', '', 14) ?> Lưu Đánh Giá Tiến Độ
                                            </button>
                                        </div>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php endforeach; ?>
<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>

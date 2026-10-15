<?php
use App\Config\App;
use App\Helpers\ViewHelper;

$pageTitle = "Tra Cứu Kết Quả Đánh Giá & Khen Thưởng";
require dirname(__DIR__) . '/layouts/header.php';
?>
<div style="max-width: 960px; margin: 0 auto;">
    <div style="margin-bottom: 24px;">
        <h2 style="font-size: 20px; font-weight: 800; color: #fff; margin-bottom: 4px;">
            6. Kết Quả Đánh Giá, Nghiệm Thu & Khen Thưởng NCKH
        </h2>
        <p style="color: var(--text-secondary); font-size: 14px; margin: 0;">
            Tra cứu điểm tổng kết chính thức từ Hội đồng, xếp loại chất lượng và danh hiệu khen thưởng cấp trường.
        </p>
    </div>

    <?php if (empty($results)): ?>
        <div class="card" style="text-align: center; padding: 48px; color: var(--text-muted);">
            <div style="margin-bottom: 12px; color: var(--text-muted);"><?= ViewHelper::icon('award', '', 44) ?></div>
            <h3 style="color: #fff; font-size: 16px; font-weight: 700;">Chưa có kết quả nghiệm thu hoặc khen thưởng</h3>
            <p style="font-size: 14px; margin-top: 6px;">Đề tài của bạn đang trong quá trình thực hiện hoặc chờ hội đồng nghiệm thu đánh giá.</p>
        </div>
    <?php else: ?>
        <?php foreach ($results as $item): 
            $t = $item['topic'];
            $award = $item['award'];
        ?>
            <div class="card" style="margin-bottom: 28px; <?= ($award && $award['DanhHieuKhenThuong'] !== 'KHONG') ? 'border-color: rgba(245, 158, 11, 0.4); box-shadow: 0 0 25px -5px rgba(245, 158, 11, 0.2);' : '' ?>">
                <div class="card-header" style="background: <?= ($award && $award['DanhHieuKhenThuong'] !== 'KHONG') ? 'linear-gradient(135deg, rgba(245, 158, 11, 0.12), rgba(217, 119, 6, 0.05))' : 'inherit' ?>;">
                    <div>
                        <span style="color: #60a5fa; font-weight: 700; font-size: 13px;"><?= ViewHelper::e($t['MaDeTai']) ?></span>
                        <h3 style="font-size: 17px; font-weight: 800; color: #fff; margin-top: 2px;">
                            <?= ViewHelper::e($t['TenDeTai']) ?>
                        </h3>
                    </div>
                    <div>
                        <?= ViewHelper::statusBadge($t['TrangThai']) ?>
                    </div>
                </div>
                <div class="card-body">
                    <div class="grid-cols-2" style="margin-bottom: 16px;">
                        <div>
                            <span style="color: var(--text-muted); font-size: 13px;">Đợt NCKH:</span>
                            <strong style="color: #fff; margin-left: 6px;"><?= ViewHelper::e($t['TenDot']) ?></strong>
                        </div>
                        <div>
                            <span style="color: var(--text-muted); font-size: 13px;">Giảng viên hướng dẫn:</span>
                            <strong style="color: #fff; margin-left: 6px;"><?= ViewHelper::e($t['TenGVHD']) ?></strong>
                        </div>
                    </div>

                    <?php if ($award): ?>
                        <div style="background: rgba(15, 23, 42, 0.6); border: 1px solid var(--border-color); border-radius: var(--radius-lg); padding: 24px; margin-top: 16px;">
                            <div class="grid-cols-4" style="margin-bottom: 20px;">
                                <div style="text-align: center; border-right: 1px solid var(--border-color); padding-right: 12px;">
                                    <div style="font-size: 12px; color: var(--text-muted); text-transform: uppercase; font-weight: 700; margin-bottom: 6px;">Điểm Tổng Kết</div>
                                    <div style="font-size: 32px; font-weight: 800; color: #38bdf8;">
                                        <?= number_format((float)$award['DiemTongKet'], 2) ?>
                                    </div>
                                    <div style="font-size: 11px; color: var(--text-muted);">Thang điểm 10/100</div>
                                </div>

                                <div style="text-align: center; border-right: 1px solid var(--border-color); padding-right: 12px;">
                                    <div style="font-size: 12px; color: var(--text-muted); text-transform: uppercase; font-weight: 700; margin-bottom: 6px;">Xếp Loại Chung Cuộc</div>
                                    <div style="margin-top: 6px;">
                                        <?= ViewHelper::statusBadge($award['XepLoai']) ?>
                                    </div>
                                </div>

                                <div style="text-align: center; border-right: 1px solid var(--border-color); padding-right: 12px;">
                                    <div style="font-size: 12px; color: var(--text-muted); text-transform: uppercase; font-weight: 700; margin-bottom: 6px;">Danh Hiệu Khen Thưởng</div>
                                    <div style="margin-top: 6px;">
                                        <?= ViewHelper::statusBadge($award['DanhHieuKhenThuong']) ?>
                                    </div>
                                </div>

                                <div style="text-align: center;">
                                    <div style="font-size: 12px; color: var(--text-muted); text-transform: uppercase; font-weight: 700; margin-bottom: 6px;">Mức Tiền Thưởng</div>
                                    <div style="font-size: 20px; font-weight: 800; color: #f59e0b; margin-top: 4px;">
                                        <?= ViewHelper::money($award['TienThuong']) ?>
                                    </div>
                                </div>
                            </div>

                            <div style="border-top: 1px solid var(--border-color); padding-top: 16px; font-size: 13px; color: var(--text-secondary); display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    Số quyết định khen thưởng: <strong style="color: #fff;"><?= ViewHelper::e($award['SoQuyetDinh'] ?? 'Chưa cập nhật số QĐ') ?></strong>
                                    <?php if (!empty($award['NgayQuyetDinh'])): ?>
                                        (Ngày ký: <?= ViewHelper::date($award['NgayQuyetDinh']) ?>)
                                    <?php endif; ?>
                                </div>
                                <?php if (!empty($award['GhiChu'])): ?>
                                    <div style="font-style: italic; color: #cbd5e1;">
                                        "<?= ViewHelper::e($award['GhiChu']) ?>"
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <div style="background: rgba(15, 23, 42, 0.4); border-radius: var(--radius-md); padding: 16px; font-size: 13px; color: var(--text-muted); display: flex; align-items: center; gap: 8px;">
                            <span class="badge-dot dot-warning"></span>
                            <span>Đề tài đang được Hội đồng xem xét và chưa có quyết định xếp loại chính thức.</span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>

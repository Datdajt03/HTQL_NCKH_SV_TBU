<?php
use App\Config\App;
use App\Helpers\ViewHelper;

$pageTitle = "Quản Lý Tiến Độ NCKH Toàn Trường";
require dirname(__DIR__) . '/layouts/header.php';
?>
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h2 style="font-size: 20px; font-weight: 800; color: #fff; margin-bottom: 4px;">
            13. Giám Sát & Quản Lý Tiến Độ Đề Tài NCKH Toàn Trường
        </h2>
        <p style="color: var(--text-secondary); font-size: 14px; margin: 0;">
            Theo dõi tỷ lệ hoàn thành, phát hiện các đề tài chậm tiến độ để nhắc nhở và đôn đốc các khoa chuyên môn.
        </p>
    </div>

    <!-- FILTER THEO ĐỢT -->
    <form method="GET" action="<?= App::url('index.php') ?>" style="display: flex; gap: 8px;">
        <input type="hidden" name="action" value="cb-quanly-tiendo">
        <select name="dot" class="form-control" onchange="this.form.submit()" style="padding: 8px 12px; font-size: 13px;">
            <option value="">-- Tất cả các đợt --</option>
            <?php foreach ($dots as $d): ?>
                <option value="<?= ViewHelper::e($d['MaDot']) ?>" <?= $filterDot === $d['MaDot'] ? 'selected' : '' ?>>
                    <?= ViewHelper::e($d['TenDot']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">
            <?= ViewHelper::icon('bar-chart') ?>
            <span>Bảng Theo Dõi Tiến Độ Thực Hiện Đề Tài (<?= count($progressList) ?> đề tài)</span>
        </div>
    </div>
    <div class="card-body" style="padding: 0;">
        <?php if (empty($progressList)): ?>
            <div style="text-align: center; padding: 48px; color: var(--text-muted);">
                Chưa có đề tài nào bước vào giai đoạn thực hiện trong đợt này.
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Mã Đề Tài</th>
                            <th>Tên Đề Tài</th>
                            <th>GVHD</th>
                            <th>Trưởng Nhóm</th>
                            <th>Tiến Độ Hiện Tại (%)</th>
                            <th>Đợt Báo Cáo Gần Nhất</th>
                            <th>Đánh Giá Của GVHD</th>
                            <th>Báo Cáo</th>
                            <th>Chi Tiết</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($progressList as $p): ?>
                            <tr>
                                <td><strong style="color: #60a5fa;"><?= ViewHelper::e($p['MaDeTai']) ?></strong></td>
                                <td style="max-width: 260px;">
                                    <strong style="color: #fff; display: block; margin-bottom: 2px;">
                                        <?= ViewHelper::e($p['TenDeTai']) ?>
                                    </strong>
                                    <span style="font-size: 11px; color: var(--text-muted);"><?= ViewHelper::e($p['TenDot']) ?></span>
                                </td>
                                <td><?= ViewHelper::e($p['TenGVHD']) ?></td>
                                <td><?= ViewHelper::e($p['TenTruongNhom']) ?></td>
                                <td style="min-width: 150px;">
                                    <?php if ($p['PhanTramHoanThanh'] !== null): ?>
                                        <div style="display: flex; justify-content: space-between; font-size: 12px; margin-bottom: 4px;">
                                            <strong style="color: #38bdf8;"><?= $p['PhanTramHoanThanh'] ?>%</strong>
                                            <?php if ($p['PhanTramHoanThanh'] >= 100): ?>
                                                <span style="color: #34d399; font-size: 11px;">Hoàn tất</span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="progress-track">
                                            <div class="progress-fill" style="width: <?= $p['PhanTramHoanThanh'] ?>%;"></div>
                                        </div>
                                    <?php else: ?>
                                        <span style="color: #f59e0b; font-size: 12px; display: inline-flex; align-items: center; gap: 4px;"><?= ViewHelper::icon('alert-circle', '', 14) ?> Chưa nộp báo cáo</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?= ViewHelper::e($p['DotBaoCao'] ?: '-') ?>
                                </td>
                                <td>
                                    <?php if ($p['DanhGiaTienDo']): ?>
                                        <?= ViewHelper::statusBadge($p['DanhGiaTienDo']) ?>
                                    <?php else: ?>
                                        <span style="color: var(--text-muted); font-size: 12px;">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($p['FileBaoCao'])): ?>
                                        <a href="<?= App::url('public/' . $p['FileBaoCao']) ?>" target="_blank" class="btn btn-sm btn-secondary" style="padding: 4px 8px; font-size: 11px;">
                                            File
                                        </a>
                                    <?php else: ?>
                                        <span style="color: var(--text-muted); font-size: 12px;">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?= App::url('index.php?action=cb-chitiet-detai&id=' . $p['MaDeTai']) ?>" class="btn btn-sm btn-secondary">
                                        Xem
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>

<?php
use App\Config\App;
use App\Helpers\ViewHelper;

$pageTitle = "Thống Kê & Báo Cáo NCKH";
require dirname(__DIR__) . '/layouts/header.php';
?>
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h2 style="font-size: 20px; font-weight: 800; color: #fff; margin-bottom: 4px;">
            Báo Cáo Thống Kê & Tổng Kết Phong Trào NCKH Sinh Viên
        </h2>
        <p style="color: var(--text-secondary); font-size: 14px; margin: 0;">
            Tổng hợp dữ liệu theo đợt phát động, phân bố chất lượng đề tài và danh mục giải thưởng toàn trường.
        </p>
    </div>
    <div style="display: flex; gap: 10px;">
        <a href="<?= App::url('index.php?action=cb-in-danhsach' . ($filterDot ? '&dot=' . $filterDot : '')) ?>" target="_blank" class="btn btn-secondary">
            <?= ViewHelper::icon('printer') ?> Bản In Chuẩn Báo Cáo
        </a>
        <a href="<?= App::url('index.php?action=export-awards' . ($filterDot ? '&dot=' . $filterDot : '')) ?>" class="btn btn-primary">
            <?= ViewHelper::icon('download') ?> Xuất File Báo Cáo Excel/CSV
        </a>
    </div>
</div>

<!-- STATS WIDGETS -->
<div class="grid-cols-4">
    <div class="stat-card">
        <div class="stat-icon blue"><?= ViewHelper::icon('book') ?></div>
        <div class="stat-content">
            <div class="label">Tổng số đề tài</div>
            <div class="value"><?= $stats['totalTopics'] ?></div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon emerald"><?= ViewHelper::icon('trending-up') ?></div>
        <div class="stat-content">
            <div class="label">Đang thực hiện</div>
            <div class="value"><?= $stats['totalApproved'] ?></div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon purple"><?= ViewHelper::icon('award') ?></div>
        <div class="stat-content">
            <div class="label">Đã nghiệm thu</div>
            <div class="value"><?= $stats['totalFinished'] ?></div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon amber"><?= ViewHelper::icon('building') ?></div>
        <div class="stat-content">
            <div class="label">Hội đồng bảo vệ</div>
            <div class="value"><?= $stats['totalCouncils'] ?></div>
        </div>
    </div>
</div>

<div class="grid-cols-2">
    <!-- BIỂU ĐỒ / PHÂN BỐ TRẠNG THÁI ĐỀ TÀI -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <?= ViewHelper::icon('bar-chart') ?>
                <span>Phân Bố Trạng Thái Đề Tài NCKH</span>
            </div>
        </div>
        <div class="card-body">
            <div style="display: flex; flex-direction: column; gap: 14px;">
                <?php foreach ($stats['statusDistribution'] as $st): 
                    $percent = $stats['totalTopics'] > 0 ? round(($st['SoLuong'] / $stats['totalTopics']) * 100, 1) : 0;
                ?>
                    <div>
                        <div style="display: flex; justify-content: space-between; font-size: 13px; margin-bottom: 6px;">
                            <span><?= ViewHelper::statusBadge($st['TrangThai']) ?></span>
                            <strong style="color: #fff;"><?= $st['SoLuong'] ?> đề tài (<?= $percent ?>%)</strong>
                        </div>
                        <div class="progress-track">
                            <div class="progress-fill" style="width: <?= $percent ?>%;"></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- PHÂN BỐ XẾP LOẠI CHẤT LƯỢNG -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <?= ViewHelper::icon('award') ?>
                <span>Phân Bố Xếp Loại Kết Quả Nghiệm Thu</span>
            </div>
        </div>
        <div class="card-body">
            <div style="display: flex; flex-direction: column; gap: 14px;">
                <?php if (empty($stats['awardDistribution'])): ?>
                    <div style="text-align: center; padding: 24px; color: var(--text-muted);">
                        Chưa có dữ liệu xếp loại trong đợt này.
                    </div>
                <?php else: ?>
                    <?php 
                    $totalAwarded = array_sum(array_column($stats['awardDistribution'], 'SoLuong'));
                    foreach ($stats['awardDistribution'] as $aw): 
                        $pct = $totalAwarded > 0 ? round(($aw['SoLuong'] / $totalAwarded) * 100, 1) : 0;
                    ?>
                        <div>
                            <div style="display: flex; justify-content: space-between; font-size: 13px; margin-bottom: 6px;">
                                <span><?= ViewHelper::statusBadge($aw['XepLoai']) ?></span>
                                <strong style="color: #fff;"><?= $aw['SoLuong'] ?> đề tài (<?= $pct ?>%)</strong>
                            </div>
                            <div class="progress-track">
                                <div class="progress-fill" style="width: <?= $pct ?>%; background: linear-gradient(135deg, #f59e0b, #d97706);"></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- BẢNG TOÀN VĂN DANH SÁCH KHEN THƯỞNG -->
<div class="card">
    <div class="card-header">
        <div class="card-title">
            <?= ViewHelper::icon('file-text') ?>
            <span>Chi Tiết Danh Sách Đề Tài Đạt Thành Tích Xuất Sắc</span>
        </div>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Mã Đề Tài</th>
                        <th>Tên Đề Tài</th>
                        <th>Chủ Nhiệm (Trưởng Nhóm)</th>
                        <th>GVHD</th>
                        <th>Điểm Số</th>
                        <th>Xếp Loại</th>
                        <th>Giải Thưởng</th>
                        <th>Tiền Thưởng</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($awards as $a): ?>
                        <tr>
                            <td><strong style="color: #60a5fa;"><?= ViewHelper::e($a['MaDeTai']) ?></strong></td>
                            <td style="max-width: 260px; font-weight: 600; color: #fff;">
                                <?= ViewHelper::e($a['TenDeTai']) ?>
                            </td>
                            <td>
                                <strong style="color: #fff;"><?= ViewHelper::e($a['TenTruongNhom']) ?></strong>
                                <div style="font-size: 11px; color: var(--text-muted);"><?= ViewHelper::e($a['LopTruongNhom']) ?></div>
                            </td>
                            <td><?= ViewHelper::e($a['TenGVHD']) ?></td>
                            <td><strong style="color: #38bdf8;"><?= number_format((float)$a['DiemTongKet'], 2) ?></strong></td>
                            <td><?= ViewHelper::statusBadge($a['XepLoai']) ?></td>
                            <td><?= ViewHelper::statusBadge($a['DanhHieuKhenThuong']) ?></td>
                            <td><strong style="color: #f59e0b;"><?= ViewHelper::money($a['TienThuong']) ?></strong></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>

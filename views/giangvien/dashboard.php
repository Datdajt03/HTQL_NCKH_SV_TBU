<?php
use App\Config\App;
use App\Helpers\ViewHelper;

$pageTitle = "Bàn Làm Việc Giảng Viên Hướng Dẫn";
require dirname(__DIR__) . '/layouts/header.php';
?>
<!-- STATS CARDS -->
<div class="grid-cols-3">
    <div class="stat-card">
        <div class="stat-icon icon-amber"><?= ViewHelper::icon('inbox', '', 22) ?></div>
        <div class="stat-content">
            <div class="label">Chờ duyệt hướng dẫn</div>
            <div class="value"><?= count($pendingTopics) ?></div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon icon-blue"><?= ViewHelper::icon('book-open', '', 22) ?></div>
        <div class="stat-content">
            <div class="label">Đang hướng dẫn</div>
            <div class="value"><?= count($activeTopics) ?></div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon icon-emerald"><?= ViewHelper::icon('check-circle', '', 22) ?></div>
        <div class="stat-content">
            <div class="label">Đã nghiệm thu</div>
            <div class="value"><?= count($completedTopics) ?></div>
        </div>
    </div>
</div>

<?php if (!empty($pendingTopics)): ?>
    <div class="card" style="background: linear-gradient(135deg, rgba(245, 158, 11, 0.15) 0%, rgba(217, 119, 6, 0.1) 100%); border-color: rgba(245, 158, 11, 0.35);">
        <div class="card-body" style="display: flex; align-items: center; justify-content: space-between; gap: 20px;">
            <div style="display: flex; align-items: center; gap: 14px;">
                <span style="color: #f59e0b; flex-shrink: 0;"><?= ViewHelper::icon('alert-triangle', '', 28) ?></span>
                <div>
                    <h3 style="color: #fef08a; font-size: 16px; font-weight: 800; margin-bottom: 4px;">
                        Bạn có <?= count($pendingTopics) ?> đề tài mới đăng ký đang chờ xét duyệt hướng dẫn!
                    </h3>
                    <p style="color: var(--text-secondary); font-size: 13px; margin: 0;">
                        Vui lòng xem xét hồ sơ, phiếu thuyết minh của nhóm sinh viên để đồng ý hoặc phản hồi từ chối.
                    </p>
                </div>
            </div>
            <a href="<?= App::url('index.php?action=gv-tiepnhan-hoso') ?>" class="btn btn-primary" style="white-space: nowrap;">
                Xét Duyệt Ngay &rarr;
            </a>
        </div>
    </div>
<?php endif; ?>

<!-- DANH SÁCH TẤT CẢ ĐỀ TÀI PHỤ TRÁCH -->
<div class="card">
    <div class="card-header">
        <div class="card-title">
            <span><?= ViewHelper::icon('file-text', '', 18) ?></span>
            <span>Danh Sách Đề Tài Giảng Viên Đang Phụ Trách Hướng Dẫn</span>
        </div>
        <div style="display: flex; gap: 8px;">
            <a href="<?= App::url('index.php?action=gv-theodoi-tiendo') ?>" class="btn btn-sm btn-secondary" style="display: inline-flex; align-items: center; gap: 6px;">
                <?= ViewHelper::icon('trending-up', '', 14) ?> Theo Dõi Tiến Độ
            </a>
            <a href="<?= App::url('index.php?action=gv-danhgia-sobo') ?>" class="btn btn-sm btn-secondary" style="display: inline-flex; align-items: center; gap: 6px;">
                <?= ViewHelper::icon('check-circle', '', 14) ?> Đánh Giá Sơ Bộ
            </a>
        </div>
    </div>
    <div class="card-body" style="padding: 0;">
        <?php if (empty($allTopics)): ?>
            <div style="text-align: center; padding: 48px; color: var(--text-muted);">
                Hiện tại chưa có đề tài NCKH nào đăng ký với Thầy/Cô.
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Mã Đề Tài</th>
                            <th>Tên Đề Tài</th>
                            <th>Đợt NCKH</th>
                            <th>Nhóm Trưởng</th>
                            <th>Kinh Phí</th>
                            <th>Trạng Thái</th>
                            <th>Hành Động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($allTopics as $t): ?>
                            <tr>
                                <td>
                                    <strong style="color: #60a5fa;"><?= ViewHelper::e($t['MaDeTai']) ?></strong>
                                </td>
                                <td style="max-width: 320px;">
                                    <div style="font-weight: 700; color: #fff; margin-bottom: 2px;">
                                        <?= ViewHelper::e($t['TenDeTai']) ?>
                                    </div>
                                    <div style="font-size: 12px; color: var(--text-muted);">
                                        <?= ViewHelper::e($t['TenHuongNC']) ?>
                                    </div>
                                </td>
                                <td><?= ViewHelper::e($t['TenDot']) ?></td>
                                <td>
                                    <strong style="color: #fff;"><?= ViewHelper::e($t['TenTruongNhom']) ?></strong>
                                    <div style="font-size: 11px; color: var(--text-muted);">(Nhóm <?= $t['SoThanhVien'] ?> SV)</div>
                                </td>
                                <td><?= ViewHelper::money($t['KinhPhiDuKien']) ?></td>
                                <td><?= ViewHelper::statusBadge($t['TrangThai']) ?></td>
                                <td>
                                    <div style="display: flex; gap: 6px;">
                                        <a href="<?= App::url('index.php?action=gv-chitiet-detai&id=' . $t['MaDeTai']) ?>" class="btn btn-sm btn-secondary">
                                            Chi tiết
                                        </a>
                                        <?php if ($t['TrangThai'] === 'CHO_GVHD_DUYET'): ?>
                                            <a href="<?= App::url('index.php?action=gv-tiepnhan-hoso') ?>" class="btn btn-sm btn-primary">
                                                Duyệt
                                            </a>
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
<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>

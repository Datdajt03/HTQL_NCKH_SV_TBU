<?php
use App\Config\App;
use App\Helpers\ViewHelper;

$pageTitle = "Bàn Làm Việc Cán Bộ Quản Lý NCKH";
require dirname(__DIR__) . '/layouts/header.php';
?>
<!-- STATS CARDS -->
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
            <div class="label">Hội đồng nghiệm thu</div>
            <div class="value"><?= $stats['totalCouncils'] ?></div>
        </div>
    </div>
</div>

<!-- ACTIONS / PENDING TASKS NOTIFICATION -->
<div class="grid-cols-2">
    <!-- ĐỀ TÀI CHỜ THẨM ĐỊNH -->
    <div class="card" style="border-color: rgba(59, 130, 246, 0.4);">
        <div class="card-header">
            <div class="card-title">
                <?= ViewHelper::icon('search') ?>
                <span>Đề Tài Đang Chờ Thẩm Định (<?= count($pendingApprove) ?>)</span>
            </div>
            <a href="<?= App::url('index.php?action=cb-thamdinh-detai') ?>" class="btn btn-sm btn-primary">
                Xem tất cả &rarr;
            </a>
        </div>
        <div class="card-body" style="padding: 0;">
            <?php if (empty($pendingApprove)): ?>
                <div style="padding: 24px; text-align: center; color: var(--text-muted); display: flex; align-items: center; justify-content: center; gap: 8px;">
                    <?= ViewHelper::icon('check-circle') ?> Không có đề tài nào đang chờ thẩm định.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Mã Đề Tài</th>
                                <th>Tên Đề Tài</th>
                                <th>GVHD</th>
                                <th>Tác Vụ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (array_slice($pendingApprove, 0, 4) as $t): ?>
                                <tr>
                                    <td><strong style="color: #60a5fa;"><?= ViewHelper::e($t['MaDeTai']) ?></strong></td>
                                    <td style="max-width: 220px; font-weight: 600; color: #fff;">
                                        <?= ViewHelper::e($t['TenDeTai']) ?>
                                    </td>
                                    <td><?= ViewHelper::e($t['TenGVHD']) ?></td>
                                    <td>
                                        <a href="<?= App::url('index.php?action=cb-thamdinh-detai') ?>" class="btn btn-sm btn-primary">
                                            Thẩm định
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

    <!-- ĐỀ TÀI CHỜ XẾP LỊCH NGHIỆM THU -->
    <div class="card" style="border-color: rgba(139, 92, 246, 0.4);">
        <div class="card-header">
            <div class="card-title">
                <?= ViewHelper::icon('award') ?>
                <span>Đề Tài Đã Xong Sơ Bộ, Chờ Nghiệm Thu (<?= count($readyForDefense) ?>)</span>
            </div>
            <a href="<?= App::url('index.php?action=cb-tochuc-nghiemthu') ?>" class="btn btn-sm btn-success">
                Tổ chức nghiệm thu &rarr;
            </a>
        </div>
        <div class="card-body" style="padding: 0;">
            <?php if (empty($readyForDefense)): ?>
                <div style="padding: 24px; text-align: center; color: var(--text-muted); display: flex; align-items: center; justify-content: center; gap: 8px;">
                    <?= ViewHelper::icon('inbox') ?> Hiện chưa có đề tài nào cần xếp hội đồng bảo vệ.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Mã Đề Tài</th>
                                <th>Tên Đề Tài</th>
                                <th>Trưởng Nhóm</th>
                                <th>Tác Vụ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (array_slice($readyForDefense, 0, 4) as $t): ?>
                                <tr>
                                    <td><strong style="color: #c4b5fd;"><?= ViewHelper::e($t['MaDeTai']) ?></strong></td>
                                    <td style="max-width: 220px; font-weight: 600; color: #fff;">
                                        <?= ViewHelper::e($t['TenDeTai']) ?>
                                    </td>
                                    <td><?= ViewHelper::e($t['TenTruongNhom']) ?></td>
                                    <td>
                                        <a href="<?= App::url('index.php?action=cb-tochuc-nghiemthu') ?>" class="btn btn-sm btn-success">
                                            Xếp HĐ
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
</div>

<!-- LỐI TẮT QUẢN TRỊ 5 USE CASES -->
<div class="card">
    <div class="card-header">
        <div class="card-title">
            <?= ViewHelper::icon('settings') ?>
            <span>Các Chức Năng Quản Lý NCKH Toàn Trường (5 Use Cases Cốt Lõi)</span>
        </div>
    </div>
    <div class="card-body">
        <div class="grid-cols-3" style="margin-bottom: 0;">
            <a href="<?= App::url('index.php?action=cb-quanly-dot') ?>" class="card" style="margin-bottom: 0; padding: 20px; text-decoration: none; border-color: rgba(59, 130, 246, 0.2);">
                <div style="color: #60a5fa; margin-bottom: 12px;"><?= ViewHelper::icon('calendar', '', 28) ?></div>
                <h4 style="color: #fff; font-size: 15px; margin-bottom: 4px;">11. Quản Lý Đợt NCKH</h4>
                <p style="font-size: 12px; color: var(--text-secondary); margin: 0;">Tạo đợt phát động, thiết lập hạn nộp, quản lý danh mục hướng nghiên cứu và biểu mẫu.</p>
            </a>

            <a href="<?= App::url('index.php?action=cb-thamdinh-detai') ?>" class="card" style="margin-bottom: 0; padding: 20px; text-decoration: none; border-color: rgba(59, 130, 246, 0.2);">
                <div style="color: #a78bfa; margin-bottom: 12px;"><?= ViewHelper::icon('scale', '', 28) ?></div>
                <h4 style="color: #fff; font-size: 15px; margin-bottom: 4px;">12. Thẩm Định Đề Tài</h4>
                <p style="font-size: 12px; color: var(--text-secondary); margin: 0;">Kiểm tra hồ sơ đã qua GVHD duyệt, phê duyệt giao đề tài hoặc yêu cầu sửa đổi.</p>
            </a>

            <a href="<?= App::url('index.php?action=cb-quanly-tiendo') ?>" class="card" style="margin-bottom: 0; padding: 20px; text-decoration: none; border-color: rgba(59, 130, 246, 0.2);">
                <div style="color: #34d399; margin-bottom: 12px;"><?= ViewHelper::icon('clock', '', 28) ?></div>
                <h4 style="color: #fff; font-size: 15px; margin-bottom: 4px;">13. Quản Lý Tiến Độ</h4>
                <p style="font-size: 12px; color: var(--text-secondary); margin: 0;">Bảng điều khiển theo dõi tiến độ toàn trường, phát hiện chậm tiến độ để đôn đốc.</p>
            </a>

            <a href="<?= App::url('index.php?action=cb-tochuc-nghiemthu') ?>" class="card" style="margin-bottom: 0; padding: 20px; text-decoration: none; border-color: rgba(59, 130, 246, 0.2);">
                <div style="color: #f59e0b; margin-bottom: 12px;"><?= ViewHelper::icon('building', '', 28) ?></div>
                <h4 style="color: #fff; font-size: 15px; margin-bottom: 4px;">14. Tổ Chức Nghiệm Thu</h4>
                <p style="font-size: 12px; color: var(--text-secondary); margin: 0;">Thành lập hội đồng, phân công phòng bảo vệ, nhập điểm 4 tiêu chí và tính điểm TB.</p>
            </a>

            <a href="<?= App::url('index.php?action=cb-xeploai-khenthuong') ?>" class="card" style="margin-bottom: 0; padding: 20px; text-decoration: none; border-color: rgba(59, 130, 246, 0.2);">
                <div style="color: #fbbf24; margin-bottom: 12px;"><?= ViewHelper::icon('award', '', 28) ?></div>
                <h4 style="color: #fff; font-size: 15px; margin-bottom: 4px;">15. Xếp Loại & Khen Thưởng</h4>
                <p style="font-size: 12px; color: var(--text-secondary); margin: 0;">Tổng hợp kết quả, xét Giải Nhất/Nhì/Ba, ra số quyết định khen thưởng và tiền thưởng.</p>
            </a>

            <a href="<?= App::url('index.php?action=cb-thongke-baocao') ?>" class="card" style="margin-bottom: 0; padding: 20px; text-decoration: none; border-color: rgba(59, 130, 246, 0.2);">
                <div style="color: #38bdf8; margin-bottom: 12px;"><?= ViewHelper::icon('bar-chart', '', 28) ?></div>
                <h4 style="color: #fff; font-size: 15px; margin-bottom: 4px;">Thống Kê & Báo Cáo</h4>
                <p style="font-size: 12px; color: var(--text-secondary); margin: 0;">Xem biểu đồ phân bố chất lượng, xuất danh sách khen thưởng ra CSV/Excel và in văn bản.</p>
            </a>
        </div>
    </div>
</div>
<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>

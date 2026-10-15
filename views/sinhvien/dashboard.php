<?php
use App\Config\App;
use App\Helpers\ViewHelper;

$pageTitle = "Bàn Làm Việc Sinh Viên";
require dirname(__DIR__) . '/layouts/header.php';
?>
<!-- STATS CARDS -->
<div class="grid-cols-3">
    <div class="stat-card">
        <div class="stat-icon icon-blue"><?= ViewHelper::icon('book-open', '', 22) ?></div>
        <div class="stat-content">
            <div class="label">Đề tài của tôi</div>
            <div class="value"><?= count($myTopics) ?></div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon icon-emerald"><?= ViewHelper::icon('calendar', '', 22) ?></div>
        <div class="stat-content">
            <div class="label">Đợt NCKH đang mở</div>
            <div class="value"><?= count($activeDots) ?></div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon icon-amber"><?= ViewHelper::icon('bell', '', 22) ?></div>
        <div class="stat-content">
            <div class="label">Thông báo / Biểu mẫu</div>
            <div class="value"><?= count($recentNotices) ?></div>
        </div>
    </div>
</div>

<!-- ACTIONS BANNER -->
<div class="card" style="background: linear-gradient(135deg, rgba(37, 99, 235, 0.15) 0%, rgba(99, 102, 241, 0.15) 100%); border-color: rgba(59, 130, 246, 0.3);">
    <div class="card-body" style="display: flex; align-items: center; justify-content: space-between; gap: 20px;">
        <div>
            <h3 style="color: #fff; font-size: 18px; font-weight: 800; margin-bottom: 6px;">
                Bắt Đầu Đăng Ký Đề Tài Nghiên Cứu Khoa Học Mới
            </h3>
            <p style="color: var(--text-secondary); font-size: 14px; margin: 0;">
                Tìm hiểu các đợt phát động, quy định, biểu mẫu và lập nhóm cùng Giảng viên hướng dẫn để nộp hồ sơ.
            </p>
        </div>
        <div style="display: flex; gap: 12px; white-space: nowrap;">
            <a href="<?= App::url('index.php?action=sv-thongtin-nckh') ?>" class="btn btn-secondary">
                <?= ViewHelper::icon('book-open', '', 15) ?> Xem Thông Tin & Biểu Mẫu
            </a>
            <a href="<?= App::url('index.php?action=sv-dangky-detai') ?>" class="btn btn-primary">
                <?= ViewHelper::icon('file-plus', '', 15) ?> Đăng Ký Đề Tài Mới
            </a>
        </div>
    </div>
</div>

<!-- DANH SÁCH ĐỀ TÀI CỦA TÔI -->
<div class="card">
    <div class="card-header">
        <div class="card-title">
            <span><?= ViewHelper::icon('file-text', '', 18) ?></span>
            <span>Danh Sách Đề Tài Nghiên Cứu Của Bạn</span>
        </div>
        <span class="badge badge-primary"><?= count($myTopics) ?> đề tài</span>
    </div>
    <div class="card-body" style="padding: 0;">
        <?php if (empty($myTopics)): ?>
            <div style="text-align: center; padding: 48px; color: var(--text-muted);">
                <div style="margin-bottom: 12px; color: var(--text-muted);"><?= ViewHelper::icon('folder', '', 44) ?></div>
                <div style="font-size: 16px; font-weight: 700; color: #fff;">Bạn chưa tham gia đề tài NCKH nào</div>
                <p style="font-size: 14px; margin-top: 6px;">Hãy kiểm tra các đợt phát động và nộp phiếu đăng ký đề tài ngay nhé!</p>
                <a href="<?= App::url('index.php?action=sv-dangky-detai') ?>" class="btn btn-primary" style="margin-top: 16px;">
                    <?= ViewHelper::icon('file-plus', '', 15) ?> Đăng Ký Đề Tài Ngay
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Mã Đề Tài</th>
                            <th>Tên Đề Tài</th>
                            <th>Đợt NCKH</th>
                            <th>GVHD</th>
                            <th>Vai Trò</th>
                            <th>Trạng Thái</th>
                            <th>Hành Động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($myTopics as $t): ?>
                            <tr>
                                <td>
                                    <strong style="color: #60a5fa;"><?= ViewHelper::e($t['MaDeTai']) ?></strong>
                                </td>
                                <td style="max-width: 320px;">
                                    <div style="font-weight: 700; color: #fff; margin-bottom: 4px;">
                                        <?= ViewHelper::e($t['TenDeTai']) ?>
                                    </div>
                                    <div style="font-size: 12px; color: var(--text-muted);">
                                        Lĩnh vực: <?= ViewHelper::e($t['TenHuongNC']) ?>
                                    </div>
                                </td>
                                <td><?= ViewHelper::e($t['TenDot']) ?></td>
                                <td><?= ViewHelper::e($t['TenGVHD']) ?></td>
                                <td>
                                    <span class="badge <?= $t['VaiTroCuaSV'] === 'TRUONG_NHOM' ? 'badge-amber' : 'badge-secondary' ?>">
                                         <?= $t['VaiTroCuaSV'] === 'TRUONG_NHOM' ? 'Trưởng Nhóm' : 'Thành Viên' ?>
                                     </span>
                                </td>
                                <td>
                                    <?= ViewHelper::statusBadge($t['TrangThai']) ?>
                                    <?php if ($t['TrangThai'] === 'YEU_CAU_CHINH_SUA'): ?>
                                        <div style="font-size: 11px; color: #f59e0b; margin-top: 4px; display: flex; align-items: center; gap: 4px;">
                                            <?= ViewHelper::icon('alert-triangle', '', 12) ?> Cần sửa lại hồ sơ
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                                        <a href="<?= App::url('index.php?action=sv-chitiet-detai&id=' . $t['MaDeTai']) ?>" class="btn btn-sm btn-secondary" title="Chi tiết">
                                            Chi tiết
                                        </a>

                                        <?php if ($t['TrangThai'] === 'YEU_CAU_CHINH_SUA'): ?>
                                            <a href="<?= App::url('index.php?action=sv-suahoso-detai&id=' . $t['MaDeTai']) ?>" class="btn btn-sm btn-primary">
                                                <?= ViewHelper::icon('edit', '', 13) ?> Sửa hồ sơ
                                            </a>
                                        <?php endif; ?>

                                        <?php if (in_array($t['TrangThai'], ['DA_PHE_DUYET', 'DANG_THUC_HIEN'])): ?>
                                            <a href="<?= App::url('index.php?action=sv-baocao-tiendo&id=' . $t['MaDeTai']) ?>" class="btn btn-sm btn-secondary" style="border-color: rgba(59, 130, 246, 0.4);">
                                                <?= ViewHelper::icon('clock', '', 13) ?> Báo cáo tiến độ
                                            </a>
                                            <a href="<?= App::url('index.php?action=sv-baocao-tongket&id=' . $t['MaDeTai']) ?>" class="btn btn-sm btn-success">
                                                <?= ViewHelper::icon('folder', '', 13) ?> Báo cáo tổng kết
                                            </a>
                                        <?php endif; ?>

                                        <?php if (in_array($t['TrangThai'], ['CHO_NGHIEM_THU', 'DA_NGHIEM_THU'])): ?>
                                            <a href="<?= App::url('index.php?action=sv-xem-ketqua') ?>" class="btn btn-sm btn-secondary" style="border-color: rgba(245, 158, 11, 0.4);">
                                                <?= ViewHelper::icon('award', '', 13) ?> Kết quả
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

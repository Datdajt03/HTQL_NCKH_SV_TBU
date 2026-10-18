<?php
use App\Config\App;
use App\Helpers\ViewHelper;

$pageTitle = "Bàn Làm Việc Cán Bộ Quản Lý NCKH";
require dirname(__DIR__) . '/layouts/header.php';
?>

<!-- ============================================================== -->
<!-- GIAO DIỆN CHÍNH: CHUYỂN ĐỔI CHẾ ĐỘ XEM (MAIN VIEW MODE TABS)    -->
<!-- ============================================================== -->
<div class="utb-interface-tabs" style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; background: var(--bg-card); padding: 10px 14px; border-radius: var(--radius-md); border: 1px solid var(--border-color); box-shadow: var(--shadow-sm);">
    <div style="display: flex; gap: 8px; flex-wrap: wrap;" class="dash-mode-tabs" data-role="cb">
        <button type="button" class="btn btn-sm btn-primary mode-tab-btn active" data-target="tab-utb-main" style="font-weight: 700; gap: 6px;">
            🖥️ Cửa Sổ UTB Sơn La (utb.edu.vn) &bull; Main Chính
        </button>
        <button type="button" class="btn btn-sm btn-secondary mode-tab-btn" data-target="tab-nckh-tasks" style="font-weight: 700; gap: 6px;">
            📊 Trung Tâm Điều Hành NCKH Toàn Trường
        </button>
        <button type="button" class="btn btn-sm btn-secondary mode-tab-btn" data-target="tab-combined" style="font-weight: 600; gap: 6px;">
            🔲 Hiển Thị Song Song (Toàn Diện)
        </button>
    </div>
    
    <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
        <span style="font-size: 12.5px; color: var(--text-secondary);">
            Tổng đề tài: <strong style="color: var(--accent-blue);"><?= $stats['totalTopics'] ?></strong>
        </span>
        <span style="color: var(--border-color);">&bull;</span>
        <span style="font-size: 12.5px; color: var(--text-secondary);">
            Chờ thẩm định: <strong style="color: #f59e0b;"><?= count($pendingApprove) ?></strong>
        </span>
        <span style="color: var(--border-color);">&bull;</span>
        <span style="font-size: 12.5px; color: var(--text-secondary);">
            Sinh viên: <strong style="color: #10b981;"><?= $userStats['sinhVien'] ?? 0 ?></strong>
        </span>
    </div>
</div>

<!-- ============================================================== -->
<!-- 1. PHÂN HỆ MAIN CHÍNH: CỬA SỔ TRỰC TUYẾN UTB SƠN LA (utb.edu.vn) -->
<!-- ============================================================== -->
<section id="cb-utb-section" style="display: block;">
    <?php
    $viewerId = 'cb';
    $title = 'Cửa Sổ Website Trường Đại Học Tây Bắc (utb.edu.vn) • Ban Giám Hiệu & Quản Lý NCKH';
    $badgeText = 'SƠN LA • TRỰC TUYẾN';
    $height = '660px';
    $showQuickNav = true;
    $isMainHero = true;
    require dirname(__DIR__) . '/components/utb_web_viewer.php';
    ?>

    <!-- QUICK ACTIONS DÀNH CHO CÁN BỘ QUẢN LÝ DƯỚI CỬA SỔ UTB -->
    <div class="card" style="margin-bottom: 24px; background: linear-gradient(135deg, rgba(37, 99, 235, 0.08) 0%, rgba(99, 102, 241, 0.08) 100%); border-color: rgba(59, 130, 246, 0.25);">
        <div class="card-body" style="padding: 14px 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 14px;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <span style="color: var(--accent-blue);"><?= ViewHelper::icon('shield', '', 22) ?></span>
                <div>
                    <strong style="color: var(--text-primary); font-size: 14px;">Trung tâm điều hành Quản lý NCKH Trường Đại học Tây Bắc (Sơn La)</strong>
                    <div style="font-size: 12px; color: var(--text-secondary); margin-top: 2px;">
                        Phê duyệt đề tài, giám sát 4 mốc tiến độ toàn trường, tổ chức Hội đồng nghiệm thu và công nhận khen thưởng.
                    </div>
                </div>
            </div>
            <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                <a href="<?= App::url('index.php?action=cb-thamdinh-detai') ?>" class="btn btn-primary btn-sm" style="font-size: 12px;">
                    <?= ViewHelper::icon('check', '', 14) ?> Thẩm Định Đề Tài (<?= count($pendingApprove) ?>)
                </a>
                <a href="<?= App::url('index.php?action=cb-quanly-dot') ?>" class="btn btn-secondary btn-sm" style="font-size: 12px;">
                    <?= ViewHelper::icon('calendar', '', 14) ?> Quản Lý Đợt NCKH
                </a>
                <a href="<?= App::url('index.php?action=cb-quanly-tiendo') ?>" class="btn btn-secondary btn-sm" style="font-size: 12px;">
                    <?= ViewHelper::icon('clock', '', 14) ?> Giám Sát Tiến Độ
                </a>
                <button type="button" class="btn btn-secondary btn-sm" onclick="document.querySelector('[data-target=\'tab-nckh-tasks\']').click();" style="font-size: 12px;">
                    <?= ViewHelper::icon('dashboard', '', 14) ?> Xem Dashboard NCKH &darr;
                </button>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================== -->
<!-- 2. PHÂN HỆ ĐIỀU HÀNH & GIÁM SÁT TIẾN ĐỘ NCKH TOÀN TRƯỜNG        -->
<!-- ============================================================== -->
<section id="cb-nckh-section" style="display: none;">
    <!-- QUICK ACTION BAR -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 12px;">
        <div>
            <h2 style="font-size: 20px; font-weight: 800; color: var(--text-primary); margin-bottom: 4px;">
                Bàn Làm Việc Cán Bộ Quản Lý Nghiên Cứu Khoa Học
            </h2>
            <p style="color: var(--text-secondary); font-size: 14px; margin: 0;">
                Tổng quan chu trình NCKH sinh viên toàn trường, giám sát hồ sơ, tiến độ và khen thưởng.
            </p>
        </div>
        <div style="display: flex; gap: 8px; flex-wrap: wrap;">
            <button type="button" class="btn btn-secondary" onclick="document.querySelector('[data-target=\'tab-utb-main\']').click();" style="font-size: 13px; gap: 6px;" title="Cửa Sổ Trực Tuyến Website Trường ĐH Tây Bắc (utb.edu.vn)">
                <img src="<?= App::url('pic/logo.jpg') ?>" alt="Logo UTB" onerror="this.onerror=null; this.src='<?= App::url('public/images/logo.jpg') ?>';" style="width: 16px; height: 16px; border-radius: 50%; object-fit: cover;"> Website UTB (Sơn La) ↗
            </button>
            <a href="<?= App::url('index.php?action=cb-quanly-nguoidung') ?>" class="btn btn-secondary" style="font-size: 13px;">
                <?= ViewHelper::icon('users') ?> Quản lý Tài khoản (<?= $userStats['total'] ?? 0 ?>)
            </a>
            <a href="<?= App::url('index.php?action=cb-quanly-dot') ?>" class="btn btn-secondary" style="font-size: 13px;">
                <?= ViewHelper::icon('calendar') ?> Quản lý Đợt
            </a>
            <a href="<?= App::url('index.php?action=cb-thamdinh-detai') ?>" class="btn btn-primary" style="font-size: 13px;">
                <?= ViewHelper::icon('check') ?> Thẩm định đề tài (<?= count($pendingApprove) ?>)
            </a>
        </div>
    </div>

    <!-- STATS CARDS ROW 1: CORE TOPIC METRICS -->
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

    <!-- STATS CARDS ROW 2: SYSTEM & FINANCE METRICS -->
    <div class="grid-cols-4" style="margin-top: -10px;">
        <div class="stat-card">
            <div class="stat-icon emerald"><?= ViewHelper::icon('credit-card') ?></div>
            <div class="stat-content">
                <div class="label">Tổng kinh phí dự kiến</div>
                <div class="value" style="font-size: 20px;"><?= ViewHelper::money($financialStats['TongKinhPhi'] ?? 0) ?></div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon blue"><?= ViewHelper::icon('users') ?></div>
            <div class="stat-content">
                <div class="label">Sinh viên tham gia</div>
                <div class="value"><?= $userStats['sinhVien'] ?? 0 ?> <span style="font-size: 13px; font-weight: normal; color: var(--text-muted);">người</span></div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon purple"><?= ViewHelper::icon('user-check') ?></div>
            <div class="stat-content">
                <div class="label">Giảng viên hướng dẫn</div>
                <div class="value"><?= $userStats['giangVien'] ?? 0 ?> <span style="font-size: 13px; font-weight: normal; color: var(--text-muted);">người</span></div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon amber"><?= ViewHelper::icon('shield') ?></div>
            <div class="stat-content">
                <div class="label">Tài khoản hoạt động</div>
                <div class="value"><?= $userStats['active'] ?? 0 ?> / <?= $userStats['total'] ?? 0 ?></div>
            </div>
        </div>
    </div>

    <!-- PROJECT LIFECYCLE STAGE PIPELINE -->
    <div class="card" style="margin-bottom: 24px;">
        <div class="card-header">
            <div class="card-title">
                <?= ViewHelper::icon('trending-up') ?>
                <span>Chu Trình Giám Sát Đề Tài NCKH Sinh Viên</span>
            </div>
            <span class="badge badge-primary">Quy trình 5 bước</span>
        </div>
        <div class="card-body">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 14px;">
                <div style="background: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 14px; text-align: center;">
                    <div style="color: #60a5fa; font-size: 11px; font-weight: 700; text-transform: uppercase; margin-bottom: 4px;">Bước 1</div>
                    <div style="font-weight: 700; color: #fff; font-size: 14px; margin-bottom: 6px;">Đăng ký & GVHD duyệt</div>
                    <span class="badge badge-amber" style="font-size: 11px;">Hồ sơ ban đầu</span>
                </div>
                <div style="background: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 14px; text-align: center; border-color: rgba(59, 130, 246, 0.4);">
                    <div style="color: #60a5fa; font-size: 11px; font-weight: 700; text-transform: uppercase; margin-bottom: 4px;">Bước 2</div>
                    <div style="font-weight: 700; color: #fff; font-size: 14px; margin-bottom: 6px;">Thẩm định & Phê duyệt</div>
                    <span class="badge badge-primary" style="font-size: 11px;"><?= count($pendingApprove) ?> chờ duyệt</span>
                </div>
                <div style="background: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 14px; text-align: center;">
                    <div style="color: #10b981; font-size: 11px; font-weight: 700; text-transform: uppercase; margin-bottom: 4px;">Bước 3</div>
                    <div style="font-weight: 700; color: #fff; font-size: 14px; margin-bottom: 6px;">Triển khai & Báo cáo tiến độ</div>
                    <span class="badge badge-success" style="font-size: 11px;"><?= $stats['totalApproved'] ?> đang làm</span>
                </div>
                <div style="background: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 14px; text-align: center;">
                    <div style="color: #f59e0b; font-size: 11px; font-weight: 700; text-transform: uppercase; margin-bottom: 4px;">Bước 4</div>
                    <div style="font-weight: 700; color: #fff; font-size: 14px; margin-bottom: 6px;">Báo cáo tổng kết & Sơ bộ</div>
                    <span class="badge badge-amber" style="font-size: 11px;"><?= count($readyForDefense) ?> chờ HĐ</span>
                </div>
                <div style="background: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 14px; text-align: center;">
                    <div style="color: #a78bfa; font-size: 11px; font-weight: 700; text-transform: uppercase; margin-bottom: 4px;">Bước 5</div>
                    <div style="font-weight: 700; color: #fff; font-size: 14px; margin-bottom: 6px;">Nghiệm thu & Khen thưởng</div>
                    <span class="badge badge-purple" style="font-size: 11px;"><?= $stats['totalFinished'] ?> hoàn tất</span>
                </div>
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
                                    <th>Khoa / Hướng NC</th>
                                    <th>Kinh Phí</th>
                                    <th>Hành Động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach (array_slice($pendingApprove, 0, 5) as $t): ?>
                                    <tr>
                                        <td><strong><?= ViewHelper::e($t['MaDeTai']) ?></strong></td>
                                        <td style="max-width: 200px;"><?= ViewHelper::e($t['TenDeTai']) ?></td>
                                        <td><?= ViewHelper::e($t['TenHuongNC']) ?></td>
                                        <td><?= ViewHelper::money($t['KinhPhiDuKien'] ?? 0) ?></td>
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

        <!-- ĐỀ TÀI SẴN SÀNG NGHIỆM THU -->
        <div class="card" style="border-color: rgba(16, 185, 129, 0.4);">
            <div class="card-header">
                <div class="card-title">
                    <?= ViewHelper::icon('award') ?>
                    <span>Đề Tài Đủ Điều Kiện Nghiệm Thu (<?= count($readyForDefense) ?>)</span>
                </div>
                <a href="<?= App::url('index.php?action=cb-tochuc-nghiemthu') ?>" class="btn btn-sm btn-success">
                    Tổ chức HĐ &rarr;
                </a>
            </div>
            <div class="card-body" style="padding: 0;">
                <?php if (empty($readyForDefense)): ?>
                    <div style="padding: 24px; text-align: center; color: var(--text-muted); display: flex; align-items: center; justify-content: center; gap: 8px;">
                        <?= ViewHelper::icon('info') ?> Chưa có đề tài nào hoàn thành đánh giá sơ bộ chờ nghiệm thu.
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Mã Đề Tài</th>
                                    <th>Tên Đề Tài</th>
                                    <th>Trưởng Nhóm</th>
                                    <th>Hành Động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach (array_slice($readyForDefense, 0, 5) as $t): ?>
                                    <tr>
                                        <td><strong><?= ViewHelper::e($t['MaDeTai']) ?></strong></td>
                                        <td style="max-width: 220px;"><?= ViewHelper::e($t['TenDeTai']) ?></td>
                                        <td><?= ViewHelper::e($t['MaNguoiTao']) ?></td>
                                        <td>
                                            <a href="<?= App::url('index.php?action=cb-tochuc-nghiemthu') ?>" class="btn btn-sm btn-success">
                                                Lập Hội đồng
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

    <!-- CORE MANAGEMENT MODULES SHORTCUTS -->
    <div class="card" style="margin-top: 24px;">
        <div class="card-header">
            <div class="card-title">
                <?= ViewHelper::icon('settings') ?>
                <span>Các Phân Hệ Quản Lý Khoa Học Trọng Tâm (Trường ĐH Tây Bắc)</span>
            </div>
        </div>
        <div class="card-body">
            <div class="grid-cols-3" style="margin-bottom: 0;">
                <a href="<?= App::url('index.php?action=cb-quanly-dot') ?>" class="card" style="margin-bottom: 0; padding: 20px; text-decoration: none; border-color: rgba(59, 130, 246, 0.2);">
                    <div style="color: #60a5fa; margin-bottom: 12px;"><?= ViewHelper::icon('calendar', '', 28) ?></div>
                    <h4 style="color: #fff; font-size: 15px; margin-bottom: 4px;">11. Quản Lý Đợt & Hướng NC</h4>
                    <p style="font-size: 12px; color: var(--text-secondary); margin: 0;">Thiết lập đợt phát động NCKH, hạn đăng ký/tiến độ/nghiệm thu và danh mục hướng nghiên cứu.</p>
                </a>

                <a href="<?= App::url('index.php?action=cb-thamdinh-detai') ?>" class="card" style="margin-bottom: 0; padding: 20px; text-decoration: none; border-color: rgba(59, 130, 246, 0.2);">
                    <div style="color: #818cf8; margin-bottom: 12px;"><?= ViewHelper::icon('file-text', '', 28) ?></div>
                    <h4 style="color: #fff; font-size: 15px; margin-bottom: 4px;">12. Thẩm Định & Phê Duyệt</h4>
                    <p style="font-size: 12px; color: var(--text-secondary); margin: 0;">Xét duyệt hồ sơ, yêu cầu bổ sung chỉnh sửa hoặc cấp kinh phí và ban hành quyết định giao đề tài.</p>
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

                <a href="<?= App::url('index.php?action=cb-quanly-nguoidung') ?>" class="card" style="margin-bottom: 0; padding: 20px; text-decoration: none; border-color: rgba(59, 130, 246, 0.2);">
                    <div style="color: #ec4899; margin-bottom: 12px;"><?= ViewHelper::icon('users', '', 28) ?></div>
                    <h4 style="color: #fff; font-size: 15px; margin-bottom: 4px;">16. Quản Lý Tài Khoản</h4>
                    <p style="font-size: 12px; color: var(--text-secondary); margin: 0;">Phân quyền người dùng, quản trị tài khoản sinh viên/giảng viên, khóa/mở và cấp phát.</p>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- SCRIPT ĐIỀU KHIỂN CHẾ ĐỘ XEM GIAO DIỆN KÉP CÁN BỘ QUẢN LÝ -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.dash-mode-tabs[data-role="cb"] .mode-tab-btn');
    const utbSection = document.getElementById('cb-utb-section');
    const nckhSection = document.getElementById('cb-nckh-section');

    function applyMode(mode) {
        tabs.forEach(btn => {
            if (btn.getAttribute('data-target') === mode) {
                btn.classList.remove('btn-secondary');
                btn.classList.add('btn-primary', 'active');
            } else {
                btn.classList.remove('btn-primary', 'active');
                btn.classList.add('btn-secondary');
            }
        });

        if (mode === 'tab-utb-main') {
            if (utbSection) utbSection.style.display = 'block';
            if (nckhSection) nckhSection.style.display = 'none';
        } else if (mode === 'tab-nckh-tasks') {
            if (utbSection) utbSection.style.display = 'none';
            if (nckhSection) nckhSection.style.display = 'block';
        } else { // combined
            if (utbSection) utbSection.style.display = 'block';
            if (nckhSection) nckhSection.style.display = 'block';
        }
    }

    tabs.forEach(btn => {
        btn.addEventListener('click', function() {
            const target = this.getAttribute('data-target');
            applyMode(target);
            try { localStorage.setItem('cb_dash_view_mode', target); } catch(e) {}
        });
    });

    try {
        const savedMode = localStorage.getItem('cb_dash_view_mode') || 'tab-utb-main';
        applyMode(savedMode);
    } catch(e) {
        applyMode('tab-utb-main');
    }
});
</script>

<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>

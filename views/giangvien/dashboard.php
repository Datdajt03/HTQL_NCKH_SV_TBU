<?php
use App\Config\App;
use App\Helpers\ViewHelper;

$pageTitle = "Bàn Làm Việc Giảng Viên Hướng Dẫn";
require dirname(__DIR__) . '/layouts/header.php';
?>

<!-- ============================================================== -->
<!-- GIAO DIỆN CHÍNH: CHUYỂN ĐỔI CHẾ ĐỘ XEM (MAIN VIEW MODE TABS)    -->
<!-- ============================================================== -->
<div class="utb-interface-tabs" style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; background: var(--bg-card); padding: 10px 14px; border-radius: var(--radius-md); border: 1px solid var(--border-color); box-shadow: var(--shadow-sm);">
    <div style="display: flex; gap: 8px; flex-wrap: wrap;" class="dash-mode-tabs" data-role="gv">
        <button type="button" class="btn btn-sm btn-primary mode-tab-btn active" data-target="tab-utb-main" style="font-weight: 700; gap: 6px;">
            🖥️ Cửa Sổ UTB Sơn La (utb.edu.vn) &bull; Main Chính
        </button>
        <button type="button" class="btn btn-sm btn-secondary mode-tab-btn" data-target="tab-nckh-tasks" style="font-weight: 700; gap: 6px;">
            📊 Quản Lý & Hướng Dẫn Đề Tài NCKH
        </button>
        <button type="button" class="btn btn-sm btn-secondary mode-tab-btn" data-target="tab-combined" style="font-weight: 600; gap: 6px;">
            🔲 Hiển Thị Song Song (Toàn Diện)
        </button>
    </div>
    
    <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
        <span style="font-size: 12.5px; color: var(--text-secondary);">
            Chờ duyệt: <strong style="color: #f59e0b;"><?= count($pendingTopics) ?></strong>
        </span>
        <span style="color: var(--border-color);">&bull;</span>
        <span style="font-size: 12.5px; color: var(--text-secondary);">
            Đang HD: <strong style="color: var(--accent-blue);"><?= count($activeTopics) ?></strong>
        </span>
        <span style="color: var(--border-color);">&bull;</span>
        <span style="font-size: 12.5px; color: var(--text-secondary);">
            Nghiệm thu: <strong style="color: #10b981;"><?= count($completedTopics) ?></strong>
        </span>
    </div>
</div>

<!-- ============================================================== -->
<!-- 1. PHÂN HỆ MAIN CHÍNH: CỬA SỔ TRỰC TUYẾN UTB SƠN LA (utb.edu.vn) -->
<!-- ============================================================== -->
<section id="gv-utb-section" style="display: block;">
    <?php
    $viewerId = 'gv';
    $title = 'Cửa Sổ Website Trường Đại Học Tây Bắc (utb.edu.vn) • Phân Hệ Giảng Viên Hướng Dẫn';
    $badgeText = 'SƠN LA • TRỰC TUYẾN';
    $height = '660px';
    $showQuickNav = true;
    $isMainHero = true;
    require dirname(__DIR__) . '/components/utb_web_viewer.php';
    ?>

    <!-- QUICK ACTIONS CHO GIẢNG VIÊN DƯỚI CỬA SỔ UTB -->
    <div class="card" style="margin-bottom: 24px; background: linear-gradient(135deg, rgba(37, 99, 235, 0.08) 0%, rgba(99, 102, 241, 0.08) 100%); border-color: rgba(59, 130, 246, 0.25);">
        <div class="card-body" style="padding: 14px 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 14px;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <span style="color: var(--accent-blue);"><?= ViewHelper::icon('user-check', '', 22) ?></span>
                <div>
                    <strong style="color: var(--text-primary); font-size: 14px;">Tác vụ trọng tâm Giảng viên Hướng dẫn & Đánh giá NCKH</strong>
                    <div style="font-size: 12px; color: var(--text-secondary); margin-top: 2px;">
                        Tiếp nhận duyệt phiếu đề xuất/thuyết minh, theo dõi 4 mốc tiến độ và chấm điểm Hội đồng cấp Trường ĐH Tây Bắc.
                    </div>
                </div>
            </div>
            <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                <a href="<?= App::url('index.php?action=gv-tiepnhan-hoso') ?>" class="btn btn-secondary btn-sm" style="font-size: 12px;">
                    <?= ViewHelper::icon('inbox', '', 14) ?> Tiếp Nhận Hồ Sơ (<?= count($pendingTopics) ?>)
                </a>
                <a href="<?= App::url('index.php?action=gv-theodoi-tiendo') ?>" class="btn btn-secondary btn-sm" style="font-size: 12px;">
                    <?= ViewHelper::icon('clock', '', 14) ?> Theo Dõi Tiến Độ
                </a>
                <button type="button" class="btn btn-primary btn-sm" onclick="document.querySelector('[data-target=\'tab-nckh-tasks\']').click();" style="font-size: 12px;">
                    <?= ViewHelper::icon('dashboard', '', 14) ?> Xem Bàn Làm Việc GV &darr;
                </button>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================== -->
<!-- 2. PHÂN HỆ QUẢN LÝ NHIỆM VỤ NCKH CỦA GIẢNG VIÊN                 -->
<!-- ============================================================== -->
<section id="gv-nckh-section" style="display: none;">
    <!-- QUICK ACTION & INSTITUTION BAR -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 12px; padding: 14px 20px; background: var(--bg-card); border-radius: var(--radius-md); border: 1px solid var(--border-color);">
        <div>
            <h2 style="font-size: 18px; font-weight: 800; color: var(--text-primary); margin: 0 0 4px 0;">Bàn Làm Việc Giảng Viên Hướng Dẫn & Đánh Giá NCKH</h2>
            <p style="font-size: 13px; color: var(--text-secondary); margin: 0;">Trường Đại học Tây Bắc (Sơn La) &bull; Quản lý hồ sơ, hướng dẫn đề tài và tham gia Hội đồng nghiệm thu.</p>
        </div>
        <div style="display: flex; gap: 8px; flex-wrap: wrap; align-items: center;">
            <button type="button" class="btn btn-secondary btn-sm" onclick="document.querySelector('[data-target=\'tab-utb-main\']').click();" style="font-size: 12.5px; gap: 6px;" title="Cửa Sổ Trực Tuyến utb.edu.vn">
                <img src="<?= App::url('pic/logo.jpg') ?>" alt="Logo UTB" onerror="this.onerror=null; this.src='<?= App::url('public/images/logo.jpg') ?>';" style="width: 16px; height: 16px; border-radius: 50%; object-fit: cover;">
                <span>Cửa Sổ UTB (Sơn La) ↗</span>
            </button>
            <a href="https://utb.edu.vn/gioi-thieu/" target="_blank" rel="noopener" class="btn btn-primary btn-sm" style="font-size: 12.5px; gap: 6px;">
                <?= ViewHelper::icon('external-link', '', 14) ?> Cổng Trường utb.edu.vn
            </a>
        </div>
    </div>

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
        <div class="card" style="background: linear-gradient(135deg, rgba(245, 158, 11, 0.15) 0%, rgba(217, 119, 6, 0.1) 100%); border-color: rgba(245, 158, 11, 0.35); margin-bottom: 24px;">
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

    <!-- GIẢNG VIÊN USE CASE SHORTCUTS -->
    <div class="card" style="margin-bottom: 24px;">
        <div class="card-header">
            <div class="card-title">
                <?= ViewHelper::icon('settings', '', 18) ?>
                <span>Nhiệm Vụ Giảng Viên Hướng Dẫn & Hội Đồng</span>
            </div>
        </div>
        <div class="card-body">
            <div class="grid-cols-4" style="margin-bottom: 0;">
                <a href="<?= App::url('index.php?action=gv-tiepnhan-hoso') ?>" class="card" style="margin-bottom: 0; padding: 16px; text-decoration: none; border-color: rgba(59, 130, 246, 0.2);">
                    <div style="color: #60a5fa; margin-bottom: 8px;"><?= ViewHelper::icon('inbox', '', 24) ?></div>
                    <h4 style="color: var(--text-primary); font-size: 14px; margin-bottom: 4px;">6. Tiếp Nhận Hồ Sơ</h4>
                    <p style="font-size: 11px; color: var(--text-secondary); margin: 0;">Xem xét phiếu thuyết minh, đồng ý hoặc từ chối hướng dẫn.</p>
                </a>

                <a href="<?= App::url('index.php?action=gv-theodoi-tiendo') ?>" class="card" style="margin-bottom: 0; padding: 16px; text-decoration: none; border-color: rgba(59, 130, 246, 0.2);">
                    <div style="color: #10b981; margin-bottom: 8px;"><?= ViewHelper::icon('clock', '', 24) ?></div>
                    <h4 style="color: var(--text-primary); font-size: 14px; margin-bottom: 4px;">7. Theo Dõi Tiến Độ</h4>
                    <p style="font-size: 11px; color: var(--text-secondary); margin: 0;">Kiểm tra báo cáo định kỳ và nhận xét đánh giá sinh viên.</p>
                </a>

                <a href="<?= App::url('index.php?action=gv-danhgia-sobo') ?>" class="card" style="margin-bottom: 0; padding: 16px; text-decoration: none; border-color: rgba(59, 130, 246, 0.2);">
                    <div style="color: #f59e0b; margin-bottom: 8px;"><?= ViewHelper::icon('file-text', '', 24) ?></div>
                    <h4 style="color: var(--text-primary); font-size: 14px; margin-bottom: 4px;">8. Đánh Giá Sơ Bộ</h4>
                    <p style="font-size: 11px; color: var(--text-secondary); margin: 0;">Nhận xét báo cáo tổng kết, đồng ý cho phép bảo vệ hội đồng.</p>
                </a>

                <a href="<?= App::url('index.php?action=gv-chamdiem-hoidong') ?>" class="card" style="margin-bottom: 0; padding: 16px; text-decoration: none; border-color: rgba(59, 130, 246, 0.2);">
                    <div style="color: #a78bfa; margin-bottom: 8px;"><?= ViewHelper::icon('award', '', 24) ?></div>
                    <h4 style="color: var(--text-primary); font-size: 14px; margin-bottom: 4px;">9 & 10. Chấm Điểm HĐ</h4>
                    <p style="font-size: 11px; color: var(--text-secondary); margin: 0;">Tham gia hội đồng nghiệm thu, chấm điểm theo 4 tiêu chuẩn.</p>
                </a>
            </div>
        </div>
    </div>

    <!-- DANH SÁCH TẤT CẢ ĐỀ TÀI PHỤ TRÁCH -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <span><?= ViewHelper::icon('file-text', '', 18) ?></span>
                <span>Danh Sách Đề Tài Giảng Viên Đang Phụ Trách Hướng Dẫn</span>
            </div>
            <div style="display: flex; gap: 8px;">
                <button type="button" class="btn btn-sm btn-secondary" onclick="document.querySelector('[data-target=\'tab-utb-main\']').click();" style="display: inline-flex; align-items: center; gap: 6px;" title="Cổng Giới thiệu Trường ĐH Tây Bắc (Sơn La)">
                    <img src="<?= App::url('pic/logo.jpg') ?>" alt="Logo UTB" onerror="this.onerror=null; this.src='<?= App::url('public/images/logo.jpg') ?>';" style="width: 16px; height: 16px; border-radius: 50%; object-fit: cover;"> Giới Thiệu UTB (Sơn La)
                </button>
                <a href="<?= App::url('index.php?action=gv-theodoi-tiendo') ?>" class="btn btn-sm btn-secondary" style="display: inline-flex; align-items: center; gap: 6px;">
                    <?= ViewHelper::icon('trending-up', '', 14) ?> Theo Dõi Tiến Độ
                </a>
            </div>
        </div>
        <div class="card-body" style="padding: 0;">
            <?php 
            $allTopics = array_merge($pendingTopics, $activeTopics, $completedTopics);
            ?>
            <?php if (empty($allTopics)): ?>
                <div style="text-align: center; padding: 48px; color: var(--text-muted);">
                    <div style="margin-bottom: 12px; color: var(--text-muted);"><?= ViewHelper::icon('inbox', '', 44) ?></div>
                    <div style="font-size: 16px; font-weight: 700; color: #fff;">Hiện tại chưa có đề tài nào đăng ký bạn hướng dẫn</div>
                    <p style="font-size: 14px; margin-top: 6px;">Khi sinh viên đăng ký đề tài và chọn bạn làm GVHD, thông tin sẽ hiển thị tại đây.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Mã Đề Tài</th>
                                <th>Tên Đề Tài</th>
                                <th>Sinh Viên Đại Diện</th>
                                <th>Đợt NCKH</th>
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
                                        <div style="font-weight: 700; color: #fff; margin-bottom: 4px;">
                                            <?= ViewHelper::e($t['TenDeTai']) ?>
                                        </div>
                                        <div style="font-size: 12px; color: var(--text-muted);">
                                            Kinh phí đề xuất: <strong style="color: #34d399;"><?= ViewHelper::money($t['KinhPhiDuKien'] ?? 0) ?></strong>
                                        </div>
                                    </td>
                                    <td>
                                        <div style="font-weight: 600; color: #cbd5e1;"><?= ViewHelper::e($t['TenSinhVien'] ?? 'N/A') ?></div>
                                        <div style="font-size: 12px; color: var(--text-muted);"><?= ViewHelper::e($t['MaNguoiTao']) ?></div>
                                    </td>
                                    <td><?= ViewHelper::e($t['TenDot'] ?? '') ?></td>
                                    <td>
                                        <?= ViewHelper::statusBadge($t['TrangThai']) ?>
                                    </td>
                                    <td>
                                        <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                                            <a href="<?= App::url('index.php?action=gv-chitiet-detai&id=' . $t['MaDeTai']) ?>" class="btn btn-sm btn-secondary">
                                                Chi tiết
                                            </a>
                                            <?php if ($t['TrangThai'] === 'CHO_GVHD_DUYET'): ?>
                                                <a href="<?= App::url('index.php?action=gv-tiepnhan-hoso') ?>" class="btn btn-sm btn-primary">
                                                    <?= ViewHelper::icon('check', '', 13) ?> Duyệt
                                                </a>
                                            <?php endif; ?>
                                            <?php if (in_array($t['TrangThai'], ['DA_PHE_DUYET', 'DANG_THUC_HIEN'])): ?>
                                                <a href="<?= App::url('index.php?action=gv-theodoi-tiendo') ?>" class="btn btn-sm btn-secondary" style="border-color: rgba(59, 130, 246, 0.4);">
                                                    <?= ViewHelper::icon('clock', '', 13) ?> Tiến độ
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
</section>

<!-- SCRIPT ĐIỀU KHIỂN CHẾ ĐỘ XEM GIAO DIỆN KÉP GIẢNG VIÊN -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.dash-mode-tabs[data-role="gv"] .mode-tab-btn');
    const utbSection = document.getElementById('gv-utb-section');
    const nckhSection = document.getElementById('gv-nckh-section');

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
            try { localStorage.setItem('gv_dash_view_mode', target); } catch(e) {}
        });
    });

    try {
        const savedMode = localStorage.getItem('gv_dash_view_mode') || 'tab-utb-main';
        applyMode(savedMode);
    } catch(e) {
        applyMode('tab-utb-main');
    }
});
</script>

<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>

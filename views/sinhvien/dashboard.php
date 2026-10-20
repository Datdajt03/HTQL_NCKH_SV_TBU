<?php
use App\Config\App;
use App\Helpers\ViewHelper;

$pageTitle = "Bàn Làm Việc Sinh Viên";
require dirname(__DIR__) . '/layouts/header.php';
?>

<!-- ============================================================== -->
<!-- GIAO DIỆN CHÍNH: CHUYỂN ĐỔI CHẾ ĐỘ XEM (MAIN VIEW MODE TABS)    -->
<!-- ============================================================== -->
<div class="utb-interface-tabs" style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; background: var(--bg-card); padding: 10px 14px; border-radius: var(--radius-md); border: 1px solid var(--border-color); box-shadow: var(--shadow-sm);">
    <div style="display: flex; gap: 8px; flex-wrap: wrap;" class="dash-mode-tabs" data-role="sv">
        <button type="button" class="btn btn-sm btn-primary mode-tab-btn active" data-target="tab-utb-main" style="font-weight: 700; gap: 6px;">
            🖥️ Cửa Sổ UTB Sơn La (utb.edu.vn) &bull; Main Chính
        </button>
        <button type="button" class="btn btn-sm btn-secondary mode-tab-btn" data-target="tab-nckh-tasks" style="font-weight: 700; gap: 6px;">
            📊 Bàn Làm Việc & Đề Tài NCKH
        </button>
        <button type="button" class="btn btn-sm btn-secondary mode-tab-btn" data-target="tab-combined" style="font-weight: 600; gap: 6px;">
            🔲 Hiển Thị Song Song (Toàn Diện)
        </button>
    </div>
    
    <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
        <span style="font-size: 12.5px; color: var(--text-secondary);">
            Đề tài: <strong style="color: var(--accent-blue);"><?= count($myTopics) ?></strong>
        </span>
        <span style="color: var(--border-color);">&bull;</span>
        <span style="font-size: 12.5px; color: var(--text-secondary);">
            Đợt mở: <strong style="color: #10b981;"><?= count($activeDots) ?></strong>
        </span>
        <span style="color: var(--border-color);">&bull;</span>
        <a href="https://utb.edu.vn/gioi-thieu/" target="_blank" rel="noopener" class="btn btn-sm btn-secondary" style="font-size: 11.5px; padding: 3px 8px; gap: 4px;">
            <?= ViewHelper::icon('external-link', '', 12) ?> Tab Mới
        </a>
    </div>
</div>

<!-- ============================================================== -->
<!-- 1. PHÂN HỆ MAIN CHÍNH: CỬA SỔ TRỰC TUYẾN UTB SƠN LA (utb.edu.vn) -->
<!-- ============================================================== -->
<section id="sv-utb-section" style="display: block;">
    <?php
    $viewerId = 'sv';
    $title = 'Cửa Sổ Website Trường Đại Học Tây Bắc (utb.edu.vn) • Cổng Thông Tin Sơn La';
    $badgeText = 'SƠN LA • TRỰC TUYẾN';
    $height = '660px';
    $showQuickNav = true;
    $isMainHero = true;
    require dirname(__DIR__) . '/components/utb_web_viewer.php';
    ?>

    <!-- QUICK ACTIONS NGAY DƯỚI CỬA SỔ UTB -->
    <div class="card" style="margin-bottom: 24px; background: linear-gradient(135deg, rgba(37, 99, 235, 0.08) 0%, rgba(99, 102, 241, 0.08) 100%); border-color: rgba(59, 130, 246, 0.25);">
        <div class="card-body" style="padding: 14px 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 14px;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <span style="color: var(--accent-blue);"><?= ViewHelper::icon('trending-up', '', 22) ?></span>
                <div>
                    <strong style="color: var(--text-primary); font-size: 14px;">Thao tác nhanh Nghiên Cứu Khoa Học Sinh Viên ĐH Tây Bắc</strong>
                    <div style="font-size: 12px; color: var(--text-secondary); margin-top: 2px;">
                        Tiếp cận thông báo, nộp hồ sơ trực tuyến, cập nhật tiến độ theo hướng dẫn chuẩn UTB Sơn La.
                    </div>
                </div>
            </div>
            <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                <a href="<?= App::url('index.php?action=sv-thongtin-nckh') ?>" class="btn btn-secondary btn-sm" style="font-size: 12px;">
                    <?= ViewHelper::icon('book-open', '', 14) ?> Hướng dẫn & Biểu mẫu
                </a>
                <a href="<?= App::url('index.php?action=sv-dangky-detai') ?>" class="btn btn-primary btn-sm" style="font-size: 12px;">
                    <?= ViewHelper::icon('file-plus', '', 14) ?> Đăng Ký Đề Tài Mới
                </a>
                <button type="button" class="btn btn-secondary btn-sm" onclick="document.querySelector('[data-target=\'tab-nckh-tasks\']').click();" style="font-size: 12px;">
                    <?= ViewHelper::icon('dashboard', '', 14) ?> Xem Bàn Làm Việc &darr;
                </button>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================== -->
<!-- 2. PHÂN HỆ QUẢN LÝ NHIỆM VỤ NCKH SINH VIÊN                      -->
<!-- ============================================================== -->
<section id="sv-nckh-section" style="display: none;">
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
    <div class="card" style="background: linear-gradient(135deg, rgba(37, 99, 235, 0.12) 0%, rgba(99, 102, 241, 0.12) 100%); border-color: rgba(59, 130, 246, 0.3);">
        <div class="card-body" style="display: flex; align-items: center; justify-content: space-between; gap: 20px; flex-wrap: wrap;">
            <div>
                <h3 style="color: var(--text-primary); font-size: 18px; font-weight: 800; margin-bottom: 6px;">
                    Bắt Đầu Đăng Ký Đề Tài Nghiên Cứu Khoa Học Mới
                </h3>
                <p style="color: var(--text-secondary); font-size: 14px; margin: 0;">
                    Tìm hiểu các đợt phát động, quy định, biểu mẫu và lập nhóm cùng Giảng viên hướng dẫn để nộp hồ sơ.
                </p>
            </div>
            <div style="display: flex; gap: 10px; white-space: nowrap; flex-wrap: wrap;">
                <button type="button" class="btn btn-secondary" onclick="document.querySelector('[data-target=\'tab-utb-main\']').click();" title="Xem Cửa Sổ Trực Tuyến Trường ĐH Tây Bắc (utb.edu.vn)">
                    <img src="<?= App::url('pic/Logo-Dai-Hoc-Tay-Bac.png') ?>" alt="Logo UTB" onerror="this.onerror=null; this.src='<?= App::url('public/images/Logo-Dai-Hoc-Tay-Bac.png') ?>';" style="width: 16px; height: 16px; border-radius: 50%; object-fit: cover;"> Cửa Sổ UTB (Sơn La) ↗
                </button>
                <a href="<?= App::url('index.php?action=sv-thongtin-nckh') ?>" class="btn btn-secondary">
                    <?= ViewHelper::icon('book-open', '', 15) ?> Biểu Mẫu & Hướng Dẫn
                </a>
                <a href="<?= App::url('index.php?action=sv-dangky-detai') ?>" class="btn btn-primary">
                    <?= ViewHelper::icon('file-plus', '', 15) ?> Đăng Ký Đề Tài Mới
                </a>
            </div>
        </div>
    </div>

    <?php if (!empty($activeDots)): ?>
        <!-- ACTIVE ROUNDS DEADLINE ALERT -->
        <div class="card" style="background: rgba(16, 185, 129, 0.08); border-color: rgba(16, 185, 129, 0.3); margin-bottom: 24px;">
            <div class="card-body" style="padding: 16px 20px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <span style="color: #10b981;"><?= ViewHelper::icon('calendar', '', 24) ?></span>
                    <div>
                        <strong style="color: #6ee7b7; font-size: 14px;">Đợt NCKH Đang Mở Đăng Ký: <?= ViewHelper::e($activeDots[0]['TenDot']) ?></strong>
                        <div style="font-size: 12px; color: var(--text-secondary); margin-top: 2px;">
                            Hạn chót đăng ký: <strong style="color: #f87171;"><?= ViewHelper::date($activeDots[0]['HanDangKy']) ?></strong> | 
                            Hạn nộp tiến độ: <strong style="color: #cbd5e1;"><?= ViewHelper::date($activeDots[0]['HanNopBaoCaoTD']) ?></strong> | 
                            Hạn nghiệm thu: <strong style="color: #cbd5e1;"><?= ViewHelper::date($activeDots[0]['HanNopNghiemThu']) ?></strong>
                        </div>
                    </div>
                </div>
                <a href="<?= App::url('index.php?action=sv-dangky-detai') ?>" class="btn btn-sm btn-success" style="white-space: nowrap;">
                    Nộp Hồ Sơ Ngay &rarr;
                </a>
            </div>
        </div>
    <?php endif; ?>

    <!-- 5-STAGE STUDENT RESEARCH ROADMAP -->
    <div class="card" style="margin-bottom: 24px;">
        <div class="card-header">
            <div class="card-title">
                <?= ViewHelper::icon('trending-up', '', 18) ?>
                <span>Lộ Trình Nghiên Cứu Khoa Học Sinh Viên Toàn Diện</span>
            </div>
            <span class="badge badge-primary">5 Giai Đoạn</span>
        </div>
        <div class="card-body">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(170px, 1fr)); gap: 12px;">
                <div style="background: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 12px 14px;">
                    <div style="font-size: 11px; font-weight: 700; color: #60a5fa; text-transform: uppercase;">1. Đăng Ký</div>
                    <div style="font-weight: 700; color: #fff; font-size: 13px; margin: 4px 0;">Lập Nhóm & Chọn Đề Tài</div>
                    <div style="font-size: 11px; color: var(--text-muted);">Mời GVHD xác nhận duyệt</div>
                </div>
                <div style="background: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 12px 14px;">
                    <div style="font-size: 11px; font-weight: 700; color: #38bdf8; text-transform: uppercase;">2. Thẩm Định</div>
                    <div style="font-weight: 700; color: #fff; font-size: 13px; margin: 4px 0;">Phê Duyệt Hồ Sơ</div>
                    <div style="font-size: 11px; color: var(--text-muted);">Cán bộ QL thẩm định giao đề tài</div>
                </div>
                <div style="background: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 12px 14px;">
                    <div style="font-size: 11px; font-weight: 700; color: #10b981; text-transform: uppercase;">3. Tiến Độ</div>
                    <div style="font-weight: 700; color: #fff; font-size: 13px; margin: 4px 0;">Báo Cáo Định Kỳ</div>
                    <div style="font-size: 11px; color: var(--text-muted);">Cập nhật % hoàn thành & minh chứng</div>
                </div>
                <div style="background: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 12px 14px;">
                    <div style="font-size: 11px; font-weight: 700; color: #f59e0b; text-transform: uppercase;">4. Tổng Kết</div>
                    <div style="font-weight: 700; color: #fff; font-size: 13px; margin: 4px 0;">Báo Cáo & Sơ Bộ</div>
                    <div style="font-size: 11px; color: var(--text-muted);">Nộp báo cáo chính thức & GVHD đánh giá</div>
                </div>
                <div style="background: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 12px 14px;">
                    <div style="font-size: 11px; font-weight: 700; color: #a78bfa; text-transform: uppercase;">5. Nghiệm Thu</div>
                    <div style="font-weight: 700; color: #fff; font-size: 13px; margin: 4px 0;">Bảo Vệ & Khen Thưởng</div>
                    <div style="font-size: 11px; color: var(--text-muted);">Hội đồng chấm điểm & xếp giải thưởng</div>
                </div>
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

                                            <?php if (in_array($t['TrangThai'], ['CHO_GVHD_DUYET', 'YEU_CAU_CHINH_SUA'])): ?>
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

                                            <?php if (($t['VaiTroCuaSV'] ?? '') === 'TRUONG_NHOM' && in_array($t['TrangThai'], ['CHO_GVHD_DUYET', 'GVHD_TU_CHOI', 'YEU_CAU_CHINH_SUA', 'HUY'])): ?>
                                                <a href="<?= App::url('index.php?action=sv-xoa-detai&id=' . $t['MaDeTai']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa/hủy hồ sơ đề tài <?= ViewHelper::e($t['MaDeTai']) ?>?')" title="Xóa đề tài">
                                                    <?= ViewHelper::icon('trash', '', 13) ?> Xóa
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

<!-- SCRIPT ĐIỀU KHIỂN CHẾ ĐỘ XEM GIAO DIỆN KÉP SINH VIÊN -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.dash-mode-tabs[data-role="sv"] .mode-tab-btn');
    const utbSection = document.getElementById('sv-utb-section');
    const nckhSection = document.getElementById('sv-nckh-section');

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
            try { localStorage.setItem('sv_dash_view_mode', target); } catch(e) {}
        });
    });

    // Khôi phục tùy chọn đã lưu hoặc mặc định là Cửa Sổ UTB Main Chính
    try {
        const savedMode = localStorage.getItem('sv_dash_view_mode') || 'tab-utb-main';
        applyMode(savedMode);
    } catch(e) {
        applyMode('tab-utb-main');
    }
});
</script>

<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>

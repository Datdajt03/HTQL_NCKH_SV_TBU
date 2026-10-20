<?php
use App\Config\App;
use App\Helpers\AuthHelper;
use App\Helpers\ViewHelper;

$pageTitle = "Cổng Thông Tin & Website Trường Đại Học Tây Bắc (Sơn La) - UTB";
require dirname(__DIR__) . '/layouts/header.php';

$isLoggedIn = AuthHelper::isLoggedIn();
$currentUser = AuthHelper::user();
$defaultEmbedUrl = "https://utb.edu.vn/gioi-thieu/";
?>

<?php if (!$isLoggedIn): ?>
    <!-- PUBLIC NAVIGATION BAR CHO KHÁCH VÀ SINH VIÊN CHƯA ĐĂNG NHẬP -->
    <header class="portal-public-nav" style="position: sticky; top: 0; z-index: 100; backdrop-filter: blur(12px); background: rgba(15, 23, 42, 0.92); border-bottom: 1px solid var(--border-color); padding: 12px 24px; display: flex; justify-content: space-between; align-items: center; gap: 16px;">
        <a href="<?= App::url('index.php?action=gioi-thieu') ?>" class="portal-brand-block" style="display: flex; align-items: center; gap: 12px; text-decoration: none;">
            <div class="portal-brand-avatar" style="width: 44px; height: 44px; border-radius: 50%; overflow: hidden; background: #fff; border: 2px solid rgba(37,99,235,0.4); display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(37,99,235,0.25);">
                <img src="<?= App::url('pic/Logo-Dai-Hoc-Tay-Bac.png') ?>" alt="Logo Trường Đại học Tây Bắc" onerror="this.onerror=null; this.src='<?= App::url('public/images/Logo-Dai-Hoc-Tay-Bac.png') ?>';" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
            <div class="portal-brand-text">
                <h1 style="font-size: 15px; font-weight: 800; color: #fff; margin: 0; letter-spacing: 0.02em;">TRƯỜNG ĐẠI HỌC TÂY BẮC</h1>
                <span style="font-size: 11.5px; color: #60a5fa; font-weight: 600; display: block;">CỔNG THÔNG TIN & NCKH SINH VIÊN (UTB - SƠN LA)</span>
            </div>
        </a>

        <ul class="portal-nav-links" style="display: flex; list-style: none; gap: 16px; margin: 0; padding: 0; align-items: center;">
            <li><a href="#cua-so-web" style="color: #60a5fa; font-weight: 700; text-decoration: none; font-size: 13.5px; display: flex; align-items: center; gap: 5px;">🖥️ Cửa Sổ Trực Tuyến UTB</a></li>
            <li><a href="#tong-quan" style="color: var(--text-secondary); text-decoration: none; font-size: 13px;">Tổng Quan</a></li>
            <li><a href="#su-mang" style="color: var(--text-secondary); text-decoration: none; font-size: 13px;">Sứ Mạng & Tầm Nhìn</a></li>
            <li><a href="#cac-khoa" style="color: var(--text-secondary); text-decoration: none; font-size: 13px;">7 Khoa Sơn La</a></li>
            <li><a href="#huong-nc" style="color: var(--text-secondary); text-decoration: none; font-size: 13px;">Hướng NC</a></li>
            <li><a href="#vinh-danh" style="color: var(--text-secondary); text-decoration: none; font-size: 13px;">Giải Thưởng</a></li>
            <li><a href="#quy-trinh" style="color: var(--text-secondary); text-decoration: none; font-size: 13px;">5 Bước NCKH</a></li>
        </ul>

        <div style="display: flex; align-items: center; gap: 10px;">
            <button type="button" class="theme-toggle-btn" id="theme-toggle" title="Chuyển đổi giao diện Sáng / Tối">
                <span class="icon-sun"><?= ViewHelper::icon('sun', '', 15) ?></span>
                <span class="icon-moon"><?= ViewHelper::icon('moon', '', 15) ?></span>
                <span class="theme-text" id="theme-label" style="font-size: 12px; font-weight: 600;">Chế độ Tối</span>
            </button>
            <a href="<?= App::url('index.php?action=login') ?>" class="btn btn-secondary btn-sm" style="font-size: 13px;">
                <?= ViewHelper::icon('user', '', 14) ?> Đăng Nhập
            </a>
            <a href="<?= App::url('index.php?action=register') ?>" class="btn btn-primary btn-sm" style="font-size: 13px;">
                <?= ViewHelper::icon('file-plus', '', 14) ?> Đăng Ký
            </a>
        </div>
    </header>
<?php else: ?>
    <!-- TOP QUICK BAR KHI ĐÃ ĐĂNG NHẬP -->
    <div style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; padding: 12px 18px; background: var(--bg-card); border-radius: var(--radius-md); border: 1px solid var(--border-color); box-shadow: var(--shadow-sm);">
        <div style="display: flex; align-items: center; gap: 10px;">
            <span style="display: inline-flex; width: 10px; height: 10px; border-radius: 50%; background: #10b981; box-shadow: 0 0 10px #10b981;"></span>
            <span style="font-size: 13px; font-weight: 600; color: var(--text-secondary);">
                Đang xem với tư cách: <strong style="color: var(--text-primary);"><?= ViewHelper::e(AuthHelper::name()) ?></strong> (<?= ViewHelper::roleTitle(AuthHelper::role()) ?>) &bull; <span style="color: var(--accent-blue); font-weight: 700;">TRƯỜNG ĐẠI HỌC TÂY BẮC - SƠN LA</span>
            </span>
        </div>
        <div style="display: flex; gap: 8px; flex-wrap: wrap; align-items: center;">
            <a href="#cua-so-web" class="btn btn-primary btn-sm" style="font-size: 12px; padding: 5px 12px; gap: 5px; font-weight: 700;">
                🖥️ Cửa Sổ utb.edu.vn
            </a>
            <a href="#tong-quan" class="btn btn-secondary btn-sm" style="font-size: 12px; padding: 4px 10px;">Tổng quan</a>
            <a href="#cac-khoa" class="btn btn-secondary btn-sm" style="font-size: 12px; padding: 4px 10px;">7 Khoa Sơn La</a>
            <a href="#huong-nc" class="btn btn-secondary btn-sm" style="font-size: 12px; padding: 4px 10px;">Hướng NC</a>
            <a href="#vinh-danh" class="btn btn-secondary btn-sm" style="font-size: 12px; padding: 4px 10px;">Giải thưởng</a>
            <a href="<?= App::url('index.php?action=dashboard') ?>" class="btn btn-secondary btn-sm" style="font-size: 12px; padding: 4px 12px; gap: 6px;">
                <?= ViewHelper::icon('dashboard', '', 13) ?> Về Bàn Làm Việc
            </a>
        </div>
    </div>
<?php endif; ?>

<div style="max-width: 1380px; margin: 0 auto; padding: <?= $isLoggedIn ? '0' : '20px 20px 48px' ?>; width: 100%;">

    <!-- 0. BANNER GIỚI THIỆU CHÍNH VÀ ĐIỀU HƯỚNG NHANH -->
    <div style="margin-bottom: 24px; padding: 20px 24px; background: linear-gradient(135deg, rgba(37,99,235,0.12) 0%, rgba(124,58,237,0.12) 100%); border: 1px solid rgba(37,99,235,0.25); border-radius: var(--radius-lg); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
        <div style="display: flex; align-items: center; gap: 16px;">
            <div style="width: 52px; height: 52px; border-radius: 50%; overflow: hidden; background: #fff; border: 2px solid rgba(37,99,235,0.5); box-shadow: 0 4px 14px rgba(37,99,235,0.3); flex-shrink: 0;">
                <img src="<?= App::url('pic/Logo-Dai-Hoc-Tay-Bac.png') ?>" alt="Logo UTB" onerror="this.onerror=null; this.src='<?= App::url('public/images/Logo-Dai-Hoc-Tay-Bac.png') ?>';" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
            <div>
                <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                    <span style="font-size: 11px; font-weight: 800; background: #2563eb; color: #fff; padding: 3px 8px; border-radius: 4px; text-transform: uppercase;">Bộ Giáo dục & Đào tạo</span>
                    <span style="font-size: 11px; font-weight: 700; background: rgba(16,185,129,0.15); color: #059669; padding: 3px 8px; border-radius: 4px;">📍 Sơn La, Việt Nam</span>
                    <span style="font-size: 11px; font-weight: 700; background: rgba(245,158,11,0.15); color: #d97706; padding: 3px 8px; border-radius: 4px;">KĐCL Quốc Gia</span>
                </div>
                <h1 style="font-size: 20px; font-weight: 800; color: var(--text-primary); margin: 6px 0 2px 0;">TRƯỜNG ĐẠI HỌC TÂY BẮC (TAY BAC UNIVERSITY - UTB)</h1>
                <p style="font-size: 13px; color: var(--text-secondary); margin: 0;">
                    Địa chỉ: Đường Chu Văn An, Phường Quyết Tâm, Thành phố Sơn La, Tỉnh Sơn La &bull; Hotline: (+84) 212.3.751.700 &bull; Website: <a href="https://utb.edu.vn" target="_blank" rel="noopener" style="color: var(--accent-blue); text-decoration: none; font-weight: 600;">https://utb.edu.vn</a>
                </p>
            </div>
        </div>

        <div style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
            <a href="https://utb.edu.vn/gioi-thieu/" target="_blank" rel="noopener" class="btn btn-secondary btn-sm" style="font-size: 12.5px; gap: 6px;" title="Mở trang gốc https://utb.edu.vn/gioi-thieu/ trong tab mới">
                <?= ViewHelper::icon('globe', '', 14) ?> Mở Tab utb.edu.vn ↗
            </a>
            <?php if (!$isLoggedIn): ?>
                <a href="<?= App::url('index.php?action=login') ?>" class="btn btn-primary btn-sm" style="font-size: 12.5px; gap: 6px;">
                    <?= ViewHelper::icon('user', '', 14) ?> Đăng Nhập Hệ Thống NCKH
                </a>
            <?php else: ?>
                <a href="<?= App::url('index.php?action=dashboard') ?>" class="btn btn-primary btn-sm" style="font-size: 12.5px; gap: 6px;">
                    <?= ViewHelper::icon('dashboard', '', 14) ?> Bàn Làm Việc
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- 1. CỬA SỔ VIEW HIỂN THỊ SANG TRANG WEB TRƯỜNG (UTB WEB VIEWER) -->
    <!-- ============================================================== -->
    <section id="cua-so-web" style="margin-bottom: 40px; scroll-margin-top: 80px;">
        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 12px; flex-wrap: wrap; gap: 10px;">
            <div>
                <div style="display: inline-flex; align-items: center; gap: 6px; font-size: 12px; font-weight: 800; color: var(--accent-blue); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px;">
                    <span style="display: inline-block; width: 8px; height: 8px; border-radius: 50%; background: #10b981; animation: pulse 2s infinite;"></span>
                    CỬA SỔ TRỰC TUYẾN CHÍNH THỨC &bull; TRƯỜNG ĐẠI HỌC TÂY BẮC
                </div>
                <h2 style="font-size: 22px; font-weight: 800; color: var(--text-primary); margin: 0;">
                    Cửa Sổ Trực Tiếp Trang Giới Thiệu Trường: <a href="https://utb.edu.vn/gioi-thieu/" target="_blank" rel="noopener" style="color: var(--accent-blue); text-decoration: none;">utb.edu.vn/gioi-thieu/</a>
                </h2>
                <p style="color: var(--text-secondary); font-size: 13.5px; margin: 4px 0 0 0;">
                    Tương tác trực tiếp với Cổng Thông tin Điện tử Trường Đại học Tây Bắc (Sơn La) ngay trong giao diện hệ thống.
                </p>
            </div>
        </div>

        <?php
        $viewerId = 'portal';
        $title = 'Cửa Sổ Website Trường Đại Học Tây Bắc (utb.edu.vn/gioi-thieu/)';
        $badgeText = 'SƠN LA • CHÍNH THỨC';
        $height = '720px';
        $showQuickNav = true;
        $isMainHero = true;
        require dirname(__DIR__) . '/components/utb_web_viewer.php';
        ?>
    </section>

    <!-- ============================================================== -->
    <!-- 2. LIVE STATISTICS KPI NCKH TRƯỜNG ĐH TÂY BẮC (SƠN LA) -->
    <!-- ============================================================== -->
    <div class="grid-cols-3" style="margin-bottom: 36px;">
        <div class="stat-card">
            <div class="stat-icon icon-blue"><?= ViewHelper::icon('book-open', '', 24) ?></div>
            <div class="stat-content">
                <div class="label">Tổng Đề tài NCKH Sinh viên</div>
                <div class="value"><?= number_format($totalTopics) ?></div>
                <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">
                    Đang thực hiện: <strong style="color: var(--accent-blue);"><?= $executingTopics ?></strong> &bull; Đã nghiệm thu: <strong style="color: #059669;"><?= $completedTopics ?></strong>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon icon-emerald"><?= ViewHelper::icon('award', '', 24) ?></div>
            <div class="stat-content">
                <div class="label">Công trình Đạt Giải Thưởng</div>
                <div class="value"><?= count($awards) ?></div>
                <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">
                    Giải Nhất, Nhì, Ba & Khuyến khích cấp Trường, Tỉnh Sơn La & Bộ GD&ĐT
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon icon-purple"><?= ViewHelper::icon('dollar-sign', '', 24) ?></div>
            <div class="stat-content">
                <div class="label">Tổng Kinh phí Đề tài Cấp</div>
                <div class="value"><?= number_format($totalBudget / 1000000, 1) ?> <span style="font-size: 16px; font-weight: 600;">Tr VNĐ</span></div>
                <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">
                    Nguồn kinh phí NCKH Trường ĐH Tây Bắc & Hợp tác doanh nghiệp
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon icon-amber"><?= ViewHelper::icon('users', '', 24) ?></div>
            <div class="stat-content">
                <div class="label">Sinh viên Nghiên cứu</div>
                <div class="value"><?= number_format($totalStudents) ?></div>
                <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">
                    Tham gia các nhóm NCKH 7 Khoa tại TP. Sơn La
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon icon-blue"><?= ViewHelper::icon('user-check', '', 24) ?></div>
            <div class="stat-content">
                <div class="label">Giảng viên Hướng dẫn</div>
                <div class="value"><?= number_format($totalLecturers) ?></div>
                <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">
                    Tiến sĩ, Thạc sĩ, Giảng viên chuyên môn ĐH Tây Bắc
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon icon-emerald"><?= ViewHelper::icon('calendar', '', 24) ?></div>
            <div class="stat-content">
                <div class="label">Đợt Phát động NCKH Đang Mở</div>
                <div class="value"><?= count($activeDots) ?></div>
                <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">
                    Sẵn sàng tiếp nhận hồ sơ đề tài mới trực tuyến
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- 3. SỨ MẠNG, TẦM NHÌN, GIÁ TRỊ CỐT LÕI & LỊCH SỬ ĐH TÂY BẮC -->
    <!-- ============================================================== -->
    <section id="su-mang" style="margin-bottom: 40px; scroll-margin-top: 80px;">
        <div style="margin-bottom: 20px;">
            <div style="display: inline-block; font-size: 12px; font-weight: 700; color: var(--accent-blue); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px;">BẢN SẮC & TRUYỀN THỐNG VÙNG TÂY BẮC</div>
            <h2 style="font-size: 22px; font-weight: 800; color: var(--text-primary); margin: 0;">Sứ Mạng, Tầm Nhìn & Lịch Sử Hơn 65 Năm ĐH Tây Bắc</h2>
            <p style="color: var(--text-secondary); font-size: 14px; margin-top: 4px;">Thông tin chính thống trích xuất từ Cổng Thông tin Điện tử Trường Đại học Tây Bắc (<a href="https://utb.edu.vn/gioi-thieu/" target="_blank" rel="noopener" style="color: var(--accent-blue);">https://utb.edu.vn/gioi-thieu/</a>).</p>
        </div>

        <div class="grid-cols-2" style="gap: 24px;">
            <!-- CỘT TRÁI: SỨ MẠNG & TẦM NHÌN & GIÁ TRỊ -->
            <div style="display: flex; flex-direction: column; gap: 16px;">
                <div class="card" style="padding: 24px; border-left: 4px solid var(--accent-blue);">
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                        <span style="color: var(--accent-blue);"><?= ViewHelper::icon('flag', '', 20) ?></span>
                        <h3 style="font-size: 16px; font-weight: 700; color: var(--text-primary); margin: 0;">Sứ Mạng (Mission)</h3>
                    </div>
                    <p style="font-size: 14px; line-height: 1.7; color: var(--text-secondary); margin: 0;">
                        <?= ViewHelper::e($universityInfo['mission']) ?>
                    </p>
                </div>

                <div class="card" style="padding: 24px; border-left: 4px solid var(--accent-purple);">
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                        <span style="color: var(--accent-purple);"><?= ViewHelper::icon('eye', '', 20) ?></span>
                        <h3 style="font-size: 16px; font-weight: 700; color: var(--text-primary); margin: 0;">Tầm Nhìn Đến Năm 2030 (Vision 2030)</h3>
                    </div>
                    <p style="font-size: 14px; line-height: 1.7; color: var(--text-secondary); margin: 0;">
                        <?= ViewHelper::e($universityInfo['vision_2030']) ?>
                    </p>
                </div>

                <div class="card" style="padding: 24px; border-left: 4px solid #10b981;">
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 12px;">
                        <span style="color: #10b981;"><?= ViewHelper::icon('check-circle', '', 20) ?></span>
                        <h3 style="font-size: 16px; font-weight: 700; color: var(--text-primary); margin: 0;">Giá Trị Cốt Lõi & Triết Lý Giáo Dục</h3>
                    </div>
                    <div style="margin-bottom: 12px;">
                        <div style="font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Giá trị cốt lõi:</div>
                        <div style="font-size: 15px; font-weight: 800; color: var(--accent-blue); margin-top: 2px;">
                            "<?= ViewHelper::e($universityInfo['core_values']) ?>"
                        </div>
                    </div>
                    <div>
                        <div style="font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Triết lý giáo dục:</div>
                        <div style="font-size: 13.5px; font-style: italic; color: var(--text-secondary); margin-top: 2px; line-height: 1.6;">
                            "<?= ViewHelper::e($universityInfo['philosophy']) ?>"
                        </div>
                    </div>
                    <div style="margin-top: 14px; padding-top: 12px; border-top: 1px dashed var(--border-color); font-size: 12px; color: var(--text-muted);">
                        🏛️ <strong>Pháp lý thành lập:</strong> <?= ViewHelper::e($universityInfo['decision']) ?>
                    </div>
                </div>
            </div>

            <!-- CỘT PHẢI: LỊCH SỬ HÌNH THÀNH & PHÁT TRIỂN (TIMELINE) -->
            <div class="card" style="padding: 24px;">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <span style="color: var(--accent-amber);"><?= ViewHelper::icon('clock', '', 20) ?></span>
                        <h3 style="font-size: 16px; font-weight: 700; color: var(--text-primary); margin: 0;">Mốc Son Lịch Sử ĐH Tây Bắc (1960 — Nay)</h3>
                    </div>
                    <span style="font-size: 12px; font-weight: 700; color: var(--accent-blue); background: rgba(37,99,235,0.1); padding: 4px 10px; border-radius: 12px;">
                        Sơn La, Việt Nam
                    </span>
                </div>

                <div class="timeline-list">
                    <?php foreach ($historyMilestones as $m): ?>
                        <div class="timeline-item">
                            <div class="timeline-dot"></div>
                            <div class="timeline-content">
                                <span class="timeline-year"><?= ViewHelper::e($m['year']) ?></span>
                                <h4 class="timeline-title"><?= ViewHelper::e($m['title']) ?></h4>
                                <p class="timeline-desc"><?= ViewHelper::e($m['desc']) ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================================== -->
    <!-- 4. CƠ CẤU 7 KHOA ĐÀO TẠO & NGHIÊN CỨU TẠI ĐH TÂY BẮC (SƠN LA) -->
    <!-- ============================================================== -->
    <section id="cac-khoa" style="margin-bottom: 40px; scroll-margin-top: 80px;">
        <div style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 12px;">
            <div>
                <div style="display: inline-block; font-size: 12px; font-weight: 700; color: var(--accent-blue); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px;">ĐÀO TẠO & NCKH ĐA NGÀNH</div>
                <h2 style="font-size: 22px; font-weight: 800; color: var(--text-primary); margin: 0;">Cơ Cấu Các Khoa Chuyên Môn & Mũi Nhọn NCKH Tại Sơn La</h2>
                <p style="color: var(--text-secondary); font-size: 14px; margin-top: 4px;">7 Khoa đào tạo chuyên sâu phụ trách các hướng nghiên cứu công nghệ, nông lâm nghiệp công nghệ cao, kinh tế, xã hội và sư phạm.</p>
            </div>
            <a href="https://utb.edu.vn/gioi-thieu/cac-khoa/" target="_blank" rel="noopener" class="btn btn-secondary btn-sm" style="font-size: 13px; gap: 6px;">
                <?= ViewHelper::icon('globe', '', 14) ?> Xem Chi Tiết Trên utb.edu.vn ↗
            </a>
        </div>

        <div class="faculty-grid">
            <?php foreach ($faculties as $fac): ?>
                <div class="faculty-card">
                    <div>
                        <div class="faculty-icon-box" style="background: <?= $fac['color'] ?>1a; color: <?= $fac['color'] ?>;">
                            <?= ViewHelper::icon($fac['icon'], '', 24) ?>
                        </div>
                        <h3 class="faculty-title"><?= ViewHelper::e($fac['name']) ?></h3>
                        <div style="font-size: 12px; font-weight: 600; color: <?= $fac['color'] ?>; margin-bottom: 8px;">
                            <?= ViewHelper::e($fac['short']) ?>
                        </div>
                        <p class="faculty-desc"><?= ViewHelper::e($fac['description']) ?></p>
                    </div>

                    <div style="border-top: 1px dashed var(--border-color); padding-top: 12px; margin-top: 12px;">
                        <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 8px;">Hướng NCKH mũi nhọn:</div>
                        <div class="faculty-tags">
                            <?php foreach ($fac['highlights'] as $hl): ?>
                                <span class="faculty-tag"><?= ViewHelper::e($hl) ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- ============================================================== -->
    <!-- 5. CÁC HƯỚNG NGHIÊN CỨU & ĐỢT PHÁT ĐỘNG HIỆN TẠI -->
    <!-- ============================================================== -->
    <section id="huong-nc" style="margin-bottom: 40px; scroll-margin-top: 80px;">
        <div style="margin-bottom: 20px;">
            <div style="display: inline-block; font-size: 12px; font-weight: 700; color: var(--accent-blue); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px;">KẾ HOẠCH & ĐỊNH HƯỚNG NCKH</div>
            <h2 style="font-size: 22px; font-weight: 800; color: var(--text-primary); margin: 0;">Đợt Phát Động NCKH & Các Hướng Nghiên Cứu Ưu Tiên</h2>
            <p style="color: var(--text-secondary); font-size: 14px; margin-top: 4px;">Sinh viên đăng ký theo đúng tiến độ các đợt phát động và bám sát các hướng nghiên cứu trọng điểm năm học.</p>
        </div>

        <div class="grid-cols-2" style="gap: 24px;">
            <!-- CÁC ĐỢT PHÁT ĐỘNG HIỆN TẠI -->
            <div class="card" style="padding: 24px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <span style="color: var(--accent-blue);"><?= ViewHelper::icon('calendar', '', 18) ?></span>
                        <h3 style="font-size: 16px; font-weight: 700; color: var(--text-primary); margin: 0;">Đợt NCKH Đang Tiếp Nhận</h3>
                    </div>
                    <?php if ($isLoggedIn && AuthHelper::isStudent()): ?>
                        <a href="<?= App::url('index.php?action=sv-dangky-detai') ?>" class="btn btn-primary btn-sm" style="font-size: 12px;">
                            Đăng Ký Đề Tài
                        </a>
                    <?php endif; ?>
                </div>

                <?php if (!empty($activeDots)): ?>
                    <div style="display: flex; flex-direction: column; gap: 12px;">
                        <?php foreach ($activeDots as $dot): ?>
                            <div style="padding: 16px; border-radius: var(--radius-md); background: var(--bg-secondary); border: 1px solid var(--border-color);">
                                <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 10px; margin-bottom: 6px;">
                                    <div style="font-weight: 700; font-size: 14px; color: var(--text-primary);">
                                        <?= ViewHelper::e($dot['TenDot']) ?>
                                    </div>
                                    <?= ViewHelper::statusBadge($dot['TrangThai']) ?>
                                </div>
                                <div style="font-size: 12px; color: var(--text-secondary); display: flex; gap: 16px; flex-wrap: wrap; margin-top: 8px;">
                                    <span>📅 <strong>Năm học:</strong> <?= ViewHelper::e($dot['NamHoc']) ?></span>
                                    <span>⏰ <strong>Hạn nộp hồ sơ:</strong> <strong style="color: #ef4444;"><?= ViewHelper::date($dot['HanDangKy']) ?></strong></span>
                                    <span>📊 <strong>Hạn báo cáo TD:</strong> <?= ViewHelper::date($dot['HanNopBaoCaoTD']) ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div style="text-align: center; padding: 32px; color: var(--text-muted); font-size: 14px;">
                        Hiện chưa có đợt phát động mới mở cổng. Vui lòng theo dõi thông báo từ Phòng QL Khoa học ĐH Tây Bắc.
                    </div>
                <?php endif; ?>
            </div>

            <!-- DANH SÁCH CÁC HƯỚNG NGHIÊN CỨU TRỌNG ĐIỂM -->
            <div class="card" style="padding: 24px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <span style="color: var(--accent-purple);"><?= ViewHelper::icon('compass', '', 18) ?></span>
                        <h3 style="font-size: 16px; font-weight: 700; color: var(--text-primary); margin: 0;">Hướng Nghiên Cứu Ưu Tiên</h3>
                    </div>
                    <span style="font-size: 12px; color: var(--text-muted);"><?= count($directions) ?> hướng NC</span>
                </div>

                <div style="display: flex; flex-direction: column; gap: 10px; max-height: 380px; overflow-y: auto;">
                    <?php if (!empty($directions)): ?>
                        <?php foreach ($directions as $dir): ?>
                            <div style="padding: 12px 14px; border-radius: var(--radius-sm); background: var(--bg-secondary); border: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center; gap: 10px;">
                                <div>
                                    <div style="font-weight: 700; font-size: 13.5px; color: var(--text-primary);">
                                        <?= ViewHelper::e($dir['TenHuongNC']) ?>
                                    </div>
                                    <?php if (!empty($dir['MoTa'])): ?>
                                        <div style="font-size: 12px; color: var(--text-secondary); margin-top: 2px;">
                                            <?= ViewHelper::e($dir['MoTa']) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <span class="badge" style="background: rgba(37,99,235,0.1); color: var(--accent-blue); font-size: 11px; white-space: nowrap;">
                                    <?= ViewHelper::e($dir['LinhVuc'] ?? 'Khoa học & Công nghệ') ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div style="text-align: center; padding: 32px; color: var(--text-muted); font-size: 14px;">
                            Đang cập nhật danh mục hướng nghiên cứu ưu tiên của Trường Đại học Tây Bắc.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================================== -->
    <!-- 6. BẢNG VINH DANH GIẢI THƯỞNG NCKH SINH VIÊN UTB -->
    <!-- ============================================================== -->
    <section id="vinh-danh" style="margin-bottom: 40px; scroll-margin-top: 80px;">
        <div style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 12px;">
            <div>
                <div style="display: inline-block; font-size: 12px; font-weight: 700; color: var(--accent-amber); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px;">THÀNH TÍCH XUẤT SẮC</div>
                <h2 style="font-size: 22px; font-weight: 800; color: var(--text-primary); margin: 0;">Bảng Vinh Danh Các Công Trình NCKH Đạt Giải Cao</h2>
                <p style="color: var(--text-secondary); font-size: 14px; margin-top: 4px;">Các công trình nghiên cứu của sinh viên Trường Đại học Tây Bắc được đánh giá cao và vinh danh qua các năm học.</p>
            </div>
            <?php if ($isLoggedIn): ?>
                <a href="<?= App::url('index.php?action=sv-xem-ketqua') ?>" class="btn btn-secondary btn-sm" style="font-size: 13px; gap: 6px;">
                    <?= ViewHelper::icon('award', '', 14) ?> Xem Tất Cả Giải Thưởng
                </a>
            <?php endif; ?>
        </div>

        <?php if (!empty($awards)): ?>
            <div class="award-grid">
                <?php foreach ($awards as $aw): ?>
                    <?php
                        $hang = $aw['DanhHieuKhenThuong'] ?? ($aw['HangKhenThuong'] ?? ($aw['XepLoai'] ?? ''));
                        $medalIcon = match($hang) {
                            'NHAT', 'GIAI_NHAT', 'XUAT_SAC' => '🥇',
                            'NHI', 'GIAI_NHI', 'TOT'  => '🥈',
                            'BA', 'GIAI_BA', 'KHA'   => '🥉',
                            default => '🎖️'
                        };
                        $hangText = match($hang) {
                            'NHAT', 'GIAI_NHAT' => 'Giải Nhất',
                            'NHI', 'GIAI_NHI'  => 'Giải Nhì',
                            'BA', 'GIAI_BA'   => 'Giải Ba',
                            'KHUYEN_KHICH' => 'Giải Khuyến khích',
                            'XUAT_SAC' => 'Đạt Xếp Loại Xuất Sắc',
                            'TOT' => 'Đạt Xếp Loại Tốt',
                            'KHA' => 'Đạt Xếp Loại Khá',
                            default => 'Khen thưởng NCKH'
                        };
                        $soTien = $aw['TienThuong'] ?? ($aw['SoTienThuong'] ?? 0);
                    ?>
                    <div class="award-card">
                        <div class="award-medal">
                            <?= $medalIcon ?>
                        </div>
                        <div class="award-info" style="flex: 1;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                                <span style="font-size: 11px; font-weight: 800; color: #d97706; text-transform: uppercase;">
                                    <?= $hangText ?> &bull; <?= ViewHelper::e($aw['NamHoc'] ?? '2025-2026') ?>
                                </span>
                                <span style="font-size: 11px; color: var(--text-muted); font-weight: 600;">
                                    Mã: <?= ViewHelper::e($aw['MaDeTai'] ?? '') ?>
                                </span>
                            </div>
                            <h4 style="font-size: 15px; font-weight: 700; color: var(--text-primary); margin: 4px 0 8px 0; line-height: 1.4;"><?= ViewHelper::e($aw['TenDeTai'] ?? 'Đề tài nghiên cứu khoa học sinh viên') ?></h4>
                            <div class="award-meta" style="font-size: 12.5px; color: var(--text-secondary); line-height: 1.6;">
                                <div>👤 <strong>Sinh viên:</strong> <?= ViewHelper::e($aw['TenTruongNhom'] ?? ($aw['TenSinhVien'] ?? ($aw['ChuNhiem'] ?? 'Nhóm sinh viên UTB'))) ?></div>
                                <div>👨‍🏫 <strong>GV hướng dẫn:</strong> <?= ViewHelper::e($aw['TenGVHD'] ?? ($aw['GVHD'] ?? 'Giảng viên ĐH Tây Bắc')) ?></div>
                                <?php if (!empty($soTien)): ?>
                                    <div style="margin-top: 4px; color: #059669; font-weight: 700;">
                                        💰 Thưởng: <?= number_format((float)$soTien) ?> VNĐ
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="card" style="padding: 32px; text-align: center; color: var(--text-muted);">
                Chưa có dữ liệu khen thưởng được công bố.
            </div>
        <?php endif; ?>
    </section>

    <!-- ============================================================== -->
    <!-- 7. QUY TRÌNH 5 BƯỚC NCKH SINH VIÊN ĐH TÂY BẮC -->
    <!-- ============================================================== -->
    <section id="quy-trinh" style="margin-bottom: 40px; scroll-margin-top: 80px;">
        <div style="margin-bottom: 20px;">
            <div style="display: inline-block; font-size: 12px; font-weight: 700; color: var(--accent-blue); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px;">HƯỚNG DẪN THỰC HIỆN</div>
            <h2 style="font-size: 22px; font-weight: 800; color: var(--text-primary); margin: 0;">Quy Trình 5 Bước Nghiên Cứu Khoa Học Sinh Viên UTB</h2>
            <p style="color: var(--text-secondary); font-size: 14px; margin-top: 4px;">Từ ý tưởng ban đầu đến nghiệm thu và công bố công trình khoa học chính thức.</p>
        </div>

        <div class="roadmap-grid">
            <?php foreach ($roadmapSteps as $st): ?>
                <div class="roadmap-card">
                    <div>
                        <div class="roadmap-step-badge">
                            <?= ViewHelper::e($st['step']) ?>
                        </div>
                        <h3 style="font-size: 15px; font-weight: 700; color: var(--text-primary); margin-bottom: 8px;">
                            <?= ViewHelper::e($st['title']) ?>
                        </h3>
                        <p style="font-size: 13px; line-height: 1.6; color: var(--text-secondary); margin: 0 0 16px 0;">
                            <?= ViewHelper::e($st['desc']) ?>
                        </p>
                    </div>
                    <div>
                        <a href="<?= $st['action_url'] ?>" class="btn btn-secondary btn-sm" style="width: 100%; font-size: 12px; justify-content: center;">
                            <?= ViewHelper::e($st['action_label']) ?> &rarr;
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- ============================================================== -->
    <!-- 8. TẢI BIỂU MẪU CHUẨN TRƯỜNG ĐH TÂY BẮC -->
    <!-- ============================================================== -->
    <section id="bieu-mau" style="margin-bottom: 40px;">
        <div class="card" style="padding: 28px; background: linear-gradient(135deg, rgba(37, 99, 235, 0.08) 0%, rgba(99, 102, 241, 0.08) 100%); border-color: rgba(37, 99, 235, 0.25);">
            <div style="display: flex; justify-content: space-between; align-items: center; gap: 20px; flex-wrap: wrap;">
                <div>
                    <h3 style="font-size: 18px; font-weight: 800; color: var(--text-primary); margin-bottom: 6px;">
                        Hệ Thống Biểu Mẫu Chuẩn Hóa NCKH Trường Đại Học Tây Bắc (Sơn La)
                    </h3>
                    <p style="font-size: 14px; color: var(--text-secondary); margin: 0; max-width: 760px; line-height: 1.6;">
                        Tất cả hồ sơ được số hóa và điền trực tuyến theo chuẩn quy định hiện hành của Trường Đại học Tây Bắc: 
                        <strong>Mẫu 02 (Phiếu đề xuất - 6 mục)</strong> và <strong>Mẫu 01 (Thuyết minh đề tài - 13 mục chuẩn Bộ GD&ĐT)</strong> kèm hỗ trợ xuất file in tự động.
                    </p>
                </div>
                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <?php if ($isLoggedIn): ?>
                        <a href="<?= App::url('index.php?action=sv-thongtin-nckh') ?>" class="btn btn-primary" style="gap: 6px;">
                            <?= ViewHelper::icon('download', '', 15) ?> Xem Danh Mục Biểu Mẫu & Tải File
                        </a>
                    <?php else: ?>
                        <a href="<?= App::url('index.php?action=login') ?>" class="btn btn-primary" style="gap: 6px;">
                            <?= ViewHelper::icon('user', '', 15) ?> Đăng Nhập Để Lập Biểu Mẫu
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================================== -->
    <!-- 9. THÔNG TIN LIÊN HỆ TRƯỜNG ĐẠI HỌC TÂY BẮC (SƠN LA) -->
    <!-- ============================================================== -->
    <section class="portal-footer-strip" style="border-radius: var(--radius-lg); border: 1px solid var(--border-color); margin-top: 20px; background: var(--bg-card); padding: 24px 28px;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 32px; flex-wrap: wrap;">
            <div style="max-width: 520px;">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                    <div style="width: 48px; height: 48px; border-radius: 50%; overflow: hidden; background: #fff; border: 2px solid rgba(37,99,235,0.4); box-shadow: var(--shadow-sm); flex-shrink: 0;">
                        <img src="<?= App::url('pic/Logo-Dai-Hoc-Tay-Bac.png') ?>" alt="Logo UTB" onerror="this.onerror=null; this.src='<?= App::url('public/images/Logo-Dai-Hoc-Tay-Bac.png') ?>';" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <div>
                        <div style="font-weight: 800; font-size: 16px; color: var(--text-primary);"><?= ViewHelper::e($universityInfo['name']) ?></div>
                        <div style="font-size: 12px; color: var(--accent-blue); font-weight: 600;"><?= ViewHelper::e($universityInfo['english_name']) ?></div>
                    </div>
                </div>
                <p style="font-size: 13.5px; line-height: 1.6; color: var(--text-secondary); margin-bottom: 14px;">
                    Cơ sở giáo dục đại học công lập trực thuộc Bộ Giáo dục và Đào tạo. Trung tâm đào tạo nhân lực, nghiên cứu khoa học và chuyển giao công nghệ hàng đầu vùng Tây Bắc.
                </p>
                <div style="font-size: 13px; color: var(--text-muted);">
                    &copy; <?= date('Y') ?> Trường Đại học Tây Bắc. Cổng Quản lý NCKH Sinh viên chuẩn 3NF.
                </div>
            </div>

            <div style="display: flex; flex-direction: column; gap: 8px; font-size: 13.5px; color: var(--text-secondary);">
                <div style="font-size: 13px; font-weight: 800; color: var(--text-primary); text-transform: uppercase; margin-bottom: 4px;">Thông tin liên hệ & Hỗ trợ</div>
                <div>📍 <strong>Địa chỉ:</strong> <?= ViewHelper::e($universityInfo['address']) ?></div>
                <div>📞 <strong>Điện thoại:</strong> <?= ViewHelper::e($universityInfo['phone']) ?> (Tổng đài)</div>
                <div>📠 <strong>Fax:</strong> <?= ViewHelper::e($universityInfo['fax']) ?></div>
                <div>☎️ <strong>Hotline NCKH:</strong> <?= ViewHelper::e($universityInfo['hotline_nckh']) ?> (Phòng QL Khoa học)</div>
                <div>✉️ <strong>Email:</strong> <a href="mailto:<?= ViewHelper::e($universityInfo['email']) ?>" style="color: var(--accent-blue); text-decoration: none;"><?= ViewHelper::e($universityInfo['email']) ?></a></div>
                <div>🌐 <strong>Website chính thức:</strong> <a href="https://utb.edu.vn" target="_blank" rel="noopener" style="color: var(--accent-blue); text-decoration: none;">https://utb.edu.vn</a></div>
                <div>🔗 <strong>Trang giới thiệu:</strong> <a href="https://utb.edu.vn/gioi-thieu/" target="_blank" rel="noopener" style="color: var(--accent-blue); text-decoration: none;">https://utb.edu.vn/gioi-thieu/</a></div>
            </div>
        </div>
    </section>

    <!-- NÚT LÊN ĐẦU TRANG FLOATING BACK-TO-TOP -->
    <button type="button" class="portal-back-to-top" id="btn-portal-back-to-top" title="Cuộn lên đầu trang" onclick="window.scrollTo({top: 0, behavior: 'smooth'});">
        <?= ViewHelper::icon('arrow-up', '', 20) ?>
    </button>

</div>

<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>

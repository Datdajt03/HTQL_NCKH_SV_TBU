<?php
use App\Config\App;
use App\Helpers\AuthHelper;
use App\Helpers\ViewHelper;

$role = AuthHelper::role();
$action = $_GET['action'] ?? '';
?>
<aside class="app-sidebar">
    <a href="<?= App::url('index.php?action=gioi-thieu') ?>" class="sidebar-header" title="Cổng Thông tin Trường Đại học Tây Bắc (Sơn La)">
        <div class="brand-logo">
            <img src="<?= App::url('pic/Logo-Dai-Hoc-Tay-Bac.png') ?>" alt="Logo ĐH Tây Bắc" class="brand-logo-img" onerror="this.onerror=null; this.src='<?= App::url('public/images/Logo-Dai-Hoc-Tay-Bac.png') ?>';">
        </div>
        <div class="brand-text">
            <h1>ĐẠI HỌC TÂY BẮC</h1>
            <span>NCKH SINH VIÊN - UTB SƠN LA</span>
        </div>
    </a>

    <nav class="sidebar-nav">
        <?php if ($role === App::ROLE_SINH_VIEN): ?>
            <div class="nav-section-title">PHÂN HỆ SINH VIÊN</div>
            
            <a href="<?= App::url('index.php?action=sv-dashboard') ?>" class="nav-item <?= $action === 'sv-dashboard' ? 'active' : '' ?>">
                <span class="nav-icon"><?= ViewHelper::icon('dashboard') ?></span>
                <span>Bàn làm việc</span>
            </a>
            
            <a href="<?= App::url('index.php?action=sv-thongtin-nckh') ?>" class="nav-item <?= $action === 'sv-thongtin-nckh' ? 'active' : '' ?>">
                <span class="nav-icon"><?= ViewHelper::icon('book-open') ?></span>
                <span>1. Xem thông tin NCKH</span>
            </a>

            <a href="<?= App::url('index.php?action=sv-dangky-detai') ?>" class="nav-item <?= $action === 'sv-dangky-detai' ? 'active' : '' ?>">
                <span class="nav-icon"><?= ViewHelper::icon('file-plus') ?></span>
                <span>2. Đăng ký đề tài NCKH</span>
            </a>

            <a href="<?= App::url('index.php?action=sv-baocao-tiendo') ?>" class="nav-item <?= $action === 'sv-baocao-tiendo' ? 'active' : '' ?>">
                <span class="nav-icon"><?= ViewHelper::icon('clock') ?></span>
                <span>4. Báo cáo tiến độ</span>
            </a>

            <a href="<?= App::url('index.php?action=sv-baocao-tongket') ?>" class="nav-item <?= $action === 'sv-baocao-tongket' ? 'active' : '' ?>">
                <span class="nav-icon"><?= ViewHelper::icon('folder') ?></span>
                <span>5. Báo cáo tổng kết & SP</span>
            </a>

            <a href="<?= App::url('index.php?action=sv-xem-ketqua') ?>" class="nav-item <?= $action === 'sv-xem-ketqua' ? 'active' : '' ?>">
                <span class="nav-icon"><?= ViewHelper::icon('award') ?></span>
                <span>6. Tra cứu kết quả & Giải</span>
            </a>

        <?php elseif ($role === App::ROLE_GIANG_VIEN): ?>
            <div class="nav-section-title">PHÂN HỆ GIẢNG VIÊN</div>

            <a href="<?= App::url('index.php?action=gv-dashboard') ?>" class="nav-item <?= $action === 'gv-dashboard' ? 'active' : '' ?>">
                <span class="nav-icon"><?= ViewHelper::icon('dashboard') ?></span>
                <span>Bàn làm việc</span>
            </a>

            <a href="<?= App::url('index.php?action=gv-tiepnhan-hoso') ?>" class="nav-item <?= $action === 'gv-tiepnhan-hoso' ? 'active' : '' ?>">
                <span class="nav-icon"><?= ViewHelper::icon('inbox') ?></span>
                <span>7, 8. Tiếp nhận & Duyệt hồ sơ</span>
            </a>

            <a href="<?= App::url('index.php?action=gv-theodoi-tiendo') ?>" class="nav-item <?= $action === 'gv-theodoi-tiendo' ? 'active' : '' ?>">
                <span class="nav-icon"><?= ViewHelper::icon('trending-up') ?></span>
                <span>9. Theo dõi tiến độ đề tài</span>
            </a>

            <a href="<?= App::url('index.php?action=gv-danhgia-sobo') ?>" class="nav-item <?= $action === 'gv-danhgia-sobo' ? 'active' : '' ?>">
                <span class="nav-icon"><?= ViewHelper::icon('check-circle') ?></span>
                <span>10. Đánh giá sơ bộ nghiệm thu</span>
            </a>

        <?php elseif ($role === App::ROLE_CAN_BO_QL || $role === App::ROLE_ADMIN): ?>
            <div class="nav-section-title">QUẢN LÝ KHOA HỌC</div>

            <a href="<?= App::url('index.php?action=cb-dashboard') ?>" class="nav-item <?= $action === 'cb-dashboard' ? 'active' : '' ?>">
                <span class="nav-icon"><?= ViewHelper::icon('dashboard') ?></span>
                <span>Bàn làm việc</span>
            </a>

            <a href="<?= App::url('index.php?action=cb-quanly-dot') ?>" class="nav-item <?= $action === 'cb-quanly-dot' ? 'active' : '' ?>">
                <span class="nav-icon"><?= ViewHelper::icon('calendar') ?></span>
                <span>11. Quản lý đợt & Hướng NC</span>
            </a>

            <a href="<?= App::url('index.php?action=cb-thamdinh-detai') ?>" class="nav-item <?= $action === 'cb-thamdinh-detai' ? 'active' : '' ?>">
                <span class="nav-icon"><?= ViewHelper::icon('file-text') ?></span>
                <span>12. Thẩm định & Phê duyệt</span>
            </a>

            <a href="<?= App::url('index.php?action=cb-quanly-tiendo') ?>" class="nav-item <?= $action === 'cb-quanly-tiendo' ? 'active' : '' ?>">
                <span class="nav-icon"><?= ViewHelper::icon('clock') ?></span>
                <span>13. Quản lý tiến độ toàn trường</span>
            </a>

            <a href="<?= App::url('index.php?action=cb-tochuc-nghiemthu') ?>" class="nav-item <?= $action === 'cb-tochuc-nghiemthu' ? 'active' : '' ?>">
                <span class="nav-icon"><?= ViewHelper::icon('users') ?></span>
                <span>14. Tổ chức HĐ Nghiệm thu</span>
            </a>

            <a href="<?= App::url('index.php?action=cb-xeploai-khenthuong') ?>" class="nav-item <?= $action === 'cb-xeploai-khenthuong' ? 'active' : '' ?>">
                <span class="nav-icon"><?= ViewHelper::icon('award') ?></span>
                <span>15. Xếp loại & Khen thưởng</span>
            </a>

            <a href="<?= App::url('index.php?action=cb-thongke-baocao') ?>" class="nav-item <?= $action === 'cb-thongke-baocao' ? 'active' : '' ?>">
                <span class="nav-icon"><?= ViewHelper::icon('bar-chart') ?></span>
                <span>Thống kê & Xuất báo cáo</span>
            </a>

            <a href="<?= App::url('index.php?action=cb-quanly-nguoidung') ?>" class="nav-item <?= $action === 'cb-quanly-nguoidung' ? 'active' : '' ?>">
                <span class="nav-icon"><?= ViewHelper::icon('users') ?></span>
                <span>Quản lý tài khoản người dùng</span>
            </a>
        <?php endif; ?>

        <div class="nav-section-title">CỔNG THÔNG TIN TRƯỜNG</div>
        <a href="<?= App::url('index.php?action=gioi-thieu#cua-so-web') ?>" class="nav-item <?= in_array($action, ['gioi-thieu', 'utb-web', 'intro', 'about']) ? 'active' : '' ?>">
            <span class="nav-icon"><?= ViewHelper::icon('globe') ?></span>
            <span>Cửa sổ UTB (utb.edu.vn)</span>
            <span class="badge" style="margin-left: auto; font-size: 10px; background: rgba(37,99,235,0.25); color: #60a5fa; font-weight: 700; padding: 2px 6px; border-radius: 4px;">SƠN LA</span>
        </a>

        <div class="nav-section-title">HỆ THỐNG</div>
        <a href="<?= App::url('index.php?action=profile') ?>" class="nav-item <?= $action === 'profile' ? 'active' : '' ?>">
            <span class="nav-icon"><?= ViewHelper::icon('user') ?></span>
            <span>Hồ sơ cá nhân</span>
        </a>
    </nav>

    <div class="sidebar-user">
        <div class="user-card">
            <div class="user-avatar">
                <?= mb_substr(AuthHelper::name(), 0, 1, 'UTF-8') ?>
            </div>
            <div class="user-info">
                <div class="name" title="<?= ViewHelper::e(AuthHelper::name()) ?>"><?= ViewHelper::e(AuthHelper::name()) ?></div>
                <div class="role"><?= ViewHelper::roleTitle($role) ?></div>
            </div>
            <a href="<?= App::url('index.php?action=logout') ?>" class="btn-logout" title="Đăng xuất">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
            </a>
        </div>
    </div>
</aside>

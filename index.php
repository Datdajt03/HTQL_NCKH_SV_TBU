<?php
/**
 * HỆ THỐNG QUẢN LÝ HOẠT ĐỘNG NGHIÊN CỨU KHOA HỌC SINH VIÊN
 * Front Controller & Routing Entry Point
 */

declare(strict_types=1);

// Bật hiển thị lỗi trong môi trường phát triển
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Khởi tạo Autoloader tự động nạp class theo chuẩn PSR-4
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/app/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

use App\Config\App;
use App\Helpers\AuthHelper;
use App\Controllers\AuthController;
use App\Controllers\SinhVienController;
use App\Controllers\GiangVienController;
use App\Controllers\CanBoQLController;
use App\Controllers\ExportController;
use App\Controllers\PortalController;

AuthHelper::startSession();

$action = $_GET['action'] ?? '';

// Xử lý action mặc định và action=dashboard
if ($action === '') {
    // Khách vãng lai truy cập giao diện chính: hiển thị Cổng Giới thiệu & Cửa Sổ Trực Tuyến Trường ĐH Tây Bắc (Sơn La)
    if (!AuthHelper::isLoggedIn()) {
        $action = 'gioi-thieu';
    } else {
        $role = AuthHelper::role();
        $action = match ($role) {
            App::ROLE_SINH_VIEN  => 'sv-dashboard',
            App::ROLE_GIANG_VIEN => 'gv-dashboard',
            App::ROLE_CAN_BO_QL, App::ROLE_ADMIN => 'cb-dashboard',
            default => 'gioi-thieu'
        };
    }
} elseif ($action === 'dashboard') {
    if (!AuthHelper::isLoggedIn()) {
        $action = 'login';
    } else {
        $role = AuthHelper::role();
        $action = match ($role) {
            App::ROLE_SINH_VIEN  => 'sv-dashboard',
            App::ROLE_GIANG_VIEN => 'gv-dashboard',
            App::ROLE_CAN_BO_QL, App::ROLE_ADMIN => 'cb-dashboard',
            default => 'login'
        };
    }
}

try {
    switch ($action) {
        case 'gioi-thieu':
        case 'intro':
        case 'about':
        case 'portal':
        case 'home':
        case 'trang-chu':
        case 'utb-web':
        case 'web-viewer':
        case 'dai-hoc-tay-bac':
            (new PortalController())->gioiThieu();
            break;
        case 'login':
            (new AuthController())->login();
            break;
        case 'register':
            (new AuthController())->register();
            break;
        case 'quick-login':
            (new AuthController())->quickLogin();
            break;
        case 'admin-switch-role':
            (new AuthController())->adminSwitchRole();
            break;
        case 'logout':
            (new AuthController())->logout();
            break;
        case 'profile':
            (new AuthController())->profile();
            break;
        case 'dashboard':
            if (!AuthHelper::isLoggedIn()) {
                (new AuthController())->login();
            } else {
                $role = AuthHelper::role();
                match ($role) {
                    App::ROLE_SINH_VIEN  => (new SinhVienController())->dashboard(),
                    App::ROLE_GIANG_VIEN => (new GiangVienController())->dashboard(),
                    App::ROLE_CAN_BO_QL, App::ROLE_ADMIN => (new CanBoQLController())->dashboard(),
                    default => (new AuthController())->login()
                };
            }
            break;

        // --- PHÂN HỆ SINH VIÊN (6 Use Cases) ---
        case 'sv-dashboard':
            (new SinhVienController())->dashboard();
            break;
        case 'sv-thongtin-nckh':
            (new SinhVienController())->thongTinNCKH();
            break;
        case 'sv-dangky-detai':
            (new SinhVienController())->dangKyDeTai();
            break;
        case 'sv-suahoso-detai':
            (new SinhVienController())->suaHoSoDeTai();
            break;
        case 'sv-baocao-tiendo':
            (new SinhVienController())->baoCaoTienDo();
            break;
        case 'sv-baocao-tongket':
            (new SinhVienController())->baoCaoTongKet();
            break;
        case 'sv-xem-ketqua':
            (new SinhVienController())->xemKetQua();
            break;
        case 'sv-chitiet-detai':
            (new SinhVienController())->chiTietDeTai();
            break;
        case 'sv-xoa-detai':
            (new SinhVienController())->xoaDeTai();
            break;
        case 'sv-xoa-baocao-tiendo':
            (new SinhVienController())->xoaBaoCaoTienDo();
            break;

        // --- PHÂN HỆ GIẢNG VIÊN HƯỚNG DẪN (4 Use Cases) ---
        case 'gv-dashboard':
            (new GiangVienController())->dashboard();
            break;
        case 'gv-tiepnhan-hoso':
            (new GiangVienController())->tiepNhanHoSo();
            break;
        case 'gv-theodoi-tiendo':
            (new GiangVienController())->theodoiTienDo();
            break;
        case 'gv-danhgia-sobo':
            (new GiangVienController())->danhGiaSoBo();
            break;
        case 'gv-chitiet-detai':
            (new GiangVienController())->chiTietDeTai();
            break;

        // --- PHÂN HỆ CÁN BỘ QUẢN LÝ NCKH (5 Use Cases + Quản lý người dùng) ---
        case 'cb-dashboard':
            (new CanBoQLController())->dashboard();
            break;
        case 'cb-quanly-nguoidung':
            (new CanBoQLController())->quanLyNguoiDung();
            break;
        case 'cb-quanly-dot':
            (new CanBoQLController())->quanLyDot();
            break;
        case 'cb-thamdinh-detai':
            (new CanBoQLController())->thamDinhDeTai();
            break;
        case 'cb-xoa-detai':
            (new CanBoQLController())->xoaDeTai();
            break;
        case 'cb-quanly-tiendo':
            (new CanBoQLController())->quanLyTienDo();
            break;
        case 'cb-duyet-tiendo':
            (new CanBoQLController())->duyetTienDo();
            break;
        case 'cb-tochuc-nghiemthu':
            (new CanBoQLController())->toChucNghiemThu();
            break;
        case 'cb-xeploai-khenthuong':
            (new CanBoQLController())->xepLoaiKhenThuong();
            break;
        case 'cb-thongke-baocao':
            (new CanBoQLController())->thongKeBaoCao();
            break;
        case 'cb-in-danhsach':
            (new CanBoQLController())->inDanhSach();
            break;
        case 'cb-chitiet-detai':
            (new CanBoQLController())->chiTietDeTai();
            break;

        // --- XUẤT BÁO CÁO CSV / EXCEL ---
        case 'export-awards':
            (new ExportController())->exportAwards();
            break;
        case 'export-topics':
            (new ExportController())->exportTopics();
            break;

        // --- LỖI HỆ THỐNG ---
        case '403':
            http_response_code(403);
            require __DIR__ . '/views/errors/403.php';
            break;
        default:
            http_response_code(404);
            require __DIR__ . '/views/errors/404.php';
            break;
    }
} catch (Throwable $e) {
    http_response_code(500);
    $errorMessage = $e->getMessage();
    $errorTrace = $e->getTraceAsString();
    require __DIR__ . '/views/errors/500.php';
}

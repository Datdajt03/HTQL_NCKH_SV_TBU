<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    @session_start();
}

error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
ini_set('display_errors', '1');

$baseDir = dirname(__DIR__);
spl_autoload_register(function ($class) use ($baseDir) {
    $prefix = 'App\\';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    $relative_class = substr($class, $len);
    $file = $baseDir . '/app/' . str_replace('\\', '/', $relative_class) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

use App\Config\App;
use App\Helpers\AuthHelper;
use App\Helpers\NckhFormHelper;
use App\Controllers\PortalController;
use App\Controllers\AuthController;
use App\Controllers\SinhVienController;
use App\Controllers\GiangVienController;
use App\Controllers\CanBoQLController;

echo "=== BẮT ĐẦU KIỂM THỬ TOÀN DIỆN HỆ THỐNG TRƯỜNG ĐH TÂY BẮC (SƠN LA) ===" . PHP_EOL;

$passed = 0;
$failed = 0;

function check(bool $condition, string $desc): void {
    global $passed, $failed;
    if ($condition) {
        $passed++;
        echo "  [PASS] {$desc}" . PHP_EOL;
    } else {
        $failed++;
        echo "  [FAIL] {$desc}" . PHP_EOL;
    }
}

// 1. Kiểm tra App Config Constants
echo PHP_EOL . "1. KIỂM TRA HẰNG SỐ ĐỊNH DANH TRƯỜNG (App.php):" . PHP_EOL;
check(App::UNIVERSITY_NAME === 'TRƯỜNG ĐẠI HỌC TÂY BẮC', "UNIVERSITY_NAME là TRƯỜNG ĐẠI HỌC TÂY BẮC");
check(App::UNIVERSITY_SHORT === 'ĐH TÂY BẮC', "UNIVERSITY_SHORT là ĐH TÂY BẮC");
check(App::UNIVERSITY_ACRONYM === 'UTB', "UNIVERSITY_ACRONYM là UTB");
check(App::UNIVERSITY_LOCATION === 'Sơn La', "UNIVERSITY_LOCATION là Sơn La");
check(App::UNIVERSITY_GOVERNING_BODY === 'BỘ GIÁO DỤC VÀ ĐÀO TẠO', "UNIVERSITY_GOVERNING_BODY là BỘ GIÁO DỤC VÀ ĐÀO TẠO");
check(App::UNIVERSITY_DOMAIN === 'https://utb.edu.vn', "UNIVERSITY_DOMAIN là https://utb.edu.vn");
check(App::UNIVERSITY_INTRO_URL === 'https://utb.edu.vn/gioi-thieu/', "UNIVERSITY_INTRO_URL là https://utb.edu.vn/gioi-thieu/");

// 2. Kiểm tra Cổng Giới thiệu PortalController
echo PHP_EOL . "2. KIỂM TRA PORTALCONTROLLER -> GIOITHIEU():" . PHP_EOL;
try {
    $_SERVER['REQUEST_URI'] = '/web1/HTQL_NCKH_SV_TBU/index.php?action=gioi-thieu';
    $_SERVER['SCRIPT_NAME'] = '/web1/HTQL_NCKH_SV_TBU/index.php';
    $_SERVER['HTTP_HOST'] = 'localhost';
    $_SESSION = [];
    
    ob_start();
    (new PortalController())->gioiThieu();
    $portalHtml = ob_get_clean();

    check(strlen($portalHtml) > 5000, "gioiThieu() kết xuất HTML thành công (" . strlen($portalHtml) . " bytes)");
    check(strpos($portalHtml, 'TRƯỜNG ĐẠI HỌC TÂY BẮC') !== false, "HTML chứa TRƯỜNG ĐẠI HỌC TÂY BẮC");
    check(strpos($portalHtml, 'Sơn La') !== false, "HTML chứa Sơn La");
    check(strpos($portalHtml, 'https://utb.edu.vn/gioi-thieu/') !== false, "HTML chứa link nhúng https://utb.edu.vn/gioi-thieu/");
    check(strpos($portalHtml, 'utb-browser-container') !== false, "HTML chứa khung trình duyệt utb-browser-container");
    check(strpos($portalHtml, 'utb_web_iframe') !== false, "HTML chứa iframe utb_web_iframe");
    check(strpos($portalHtml, 'btn-toggle-fullscreen') !== false, "HTML có nút toàn màn hình fullscreen");
    check(strpos($portalHtml, 'btn-utb-reload') !== false, "HTML có nút reload iframe");
    check(strpos($portalHtml, 'Khoa Công nghệ Thông tin') !== false, "HTML chứa Khoa CNTT");
    check(strpos($portalHtml, 'Khoa Nông - Lâm') !== false, "HTML chứa Khoa Nông - Lâm");
    check(strpos($portalHtml, 'Khoa Sư phạm') !== false, "HTML chứa Khoa Sư phạm");
    check(strpos($portalHtml, 'Thái Bình') === false, "HTML hoàn toàn KHÔNG chứa Thái Bình");
} catch (\Throwable $e) {
    if (ob_get_level() > 0) ob_end_clean();
    check(false, "Lỗi ngoại lệ khi gọi PortalController::gioiThieu(): " . $e->getMessage());
}

// 3. Kiểm tra NckhFormHelper & Biểu mẫu Mẫu 01 / Mẫu 02
echo PHP_EOL . "3. KIỂM TRA NCKHFORMHELPER & DỮ LIỆU ĐẠI HỌC TÂY BẮC (SƠN LA):" . PHP_EOL;
try {
    $emptyDX = NckhFormHelper::getEmptyDeXuatData();
    check($emptyDX['don_vi'] === 'TRƯỜNG ĐẠI HỌC TÂY BẮC', "getEmptyDeXuatData đơn vị là TRƯỜNG ĐẠI HỌC TÂY BẮC");
    check($emptyDX['co_quan_chu_quan'] === 'BỘ GIÁO DỤC VÀ ĐÀO TẠO', "getEmptyDeXuatData cơ quan là BỘ GIÁO DỤC VÀ ĐÀO TẠO");
    check(strpos($emptyDX['dia_diem_ngay'], 'Sơn La') !== false, "getEmptyDeXuatData địa điểm là Sơn La");

    $emptyTM = NckhFormHelper::getEmptyThuyetMinhData();
    check($emptyTM['don_vi'] === 'TRƯỜNG ĐẠI HỌC TÂY BẮC', "getEmptyThuyetMinhData đơn vị là TRƯỜNG ĐẠI HỌC TÂY BẮC");
    check($emptyTM['co_quan_chu_quan'] === 'BỘ GIÁO DỤC VÀ ĐÀO TẠO', "getEmptyThuyetMinhData cơ quan là BỘ GIÁO DỤC VÀ ĐÀO TẠO");
    check(strpos($emptyTM['dia_diem_ngay'], 'Sơn La') !== false, "getEmptyThuyetMinhData địa điểm là Sơn La");
    check(strpos($emptyTM['san_pham_ung_dung']['dia_chi_ap_dung'], 'Tây Bắc') !== false, "getEmptyThuyetMinhData địa chỉ áp dụng là Tây Bắc");

    $renderedDX = NckhFormHelper::renderPhieuDeXuatHtml($emptyDX);
    check(strpos($renderedDX, 'TRƯỜNG ĐẠI HỌC TÂY BẮC') !== false, "renderPhieuDeXuatHtml hiển thị TRƯỜNG ĐẠI HỌC TÂY BẮC");
    check(strpos($renderedDX, 'Thái Bình') === false, "renderPhieuDeXuatHtml KHÔNG chứa Thái Bình");

    $renderedTM = NckhFormHelper::renderThuyetMinhHtml($emptyTM);
    check(strpos($renderedTM, 'TRƯỜNG ĐẠI HỌC TÂY BẮC') !== false, "renderThuyetMinhHtml hiển thị TRƯỜNG ĐẠI HỌC TÂY BẮC");
    check(strpos($renderedTM, 'Thái Bình') === false, "renderThuyetMinhHtml KHÔNG chứa Thái Bình");
} catch (\Throwable $e) {
    check(false, "Lỗi NckhFormHelper: " . $e->getMessage());
}

// 4. Kiểm tra Sinh viên Dashboard có Embedded Web Viewer
echo PHP_EOL . "4. KIỂM TRA PHÂN HỆ SINH VIÊN DASHBOARD (EMBEDDED WEB VIEWER):" . PHP_EOL;
try {
    AuthHelper::login([
        'MaNguoiDung' => 'SV001',
        'HoTen' => 'Hoàng Văn Nam',
        'VaiTro' => App::ROLE_SINH_VIEN,
        'Email' => 'sv_hoangnam@utb.edu.vn'
    ]);
    ob_start();
    (new SinhVienController())->dashboard();
    $svHtml = ob_get_clean();

    check(strlen($svHtml) > 3000, "SinhVienController::dashboard() kết xuất HTML thành công");
    check(strpos($svHtml, 'sv_utb_iframe') !== false, "Dashboard Sinh viên có nhúng iframe sv_utb_iframe");
    check(strpos($svHtml, 'https://utb.edu.vn/gioi-thieu/') !== false, "Dashboard Sinh viên nhúng https://utb.edu.vn/gioi-thieu/");
    check(strpos($svHtml, 'Cửa Sổ Website Trường Đại Học Tây Bắc') !== false, "Dashboard Sinh viên có tiêu đề Cửa Sổ UTB");
    check(strpos($svHtml, 'Thái Bình') === false, "Dashboard Sinh viên KHÔNG chứa Thái Bình");
} catch (\Throwable $e) {
    if (ob_get_level() > 0) ob_end_clean();
    check(false, "Lỗi SinhVienController::dashboard(): " . $e->getMessage());
}

// 5. Kiểm tra Giảng viên Dashboard có Embedded Web Viewer
echo PHP_EOL . "5. KIỂM TRA PHÂN HỆ GIẢNG VIÊN DASHBOARD (EMBEDDED WEB VIEWER):" . PHP_EOL;
try {
    AuthHelper::login([
        'MaNguoiDung' => 'GV001',
        'HoTen' => 'TS. Nguyễn Tuấn Anh',
        'VaiTro' => App::ROLE_GIANG_VIEN,
        'Email' => 'gv_nguyenanh@utb.edu.vn'
    ]);
    ob_start();
    (new GiangVienController())->dashboard();
    $gvHtml = ob_get_clean();

    check(strlen($gvHtml) > 3000, "GiangVienController::dashboard() kết xuất HTML thành công");
    check(strpos($gvHtml, 'gv_utb_iframe') !== false, "Dashboard Giảng viên có nhúng iframe gv_utb_iframe");
    check(strpos($gvHtml, 'https://utb.edu.vn/gioi-thieu/') !== false, "Dashboard Giảng viên nhúng https://utb.edu.vn/gioi-thieu/");
    check(strpos($gvHtml, 'Cửa Sổ Website Trường Đại Học Tây Bắc') !== false, "Dashboard Giảng viên có tiêu đề Cửa Sổ UTB");
    check(strpos($gvHtml, 'Thái Bình') === false, "Dashboard Giảng viên KHÔNG chứa Thái Bình");
} catch (\Throwable $e) {
    if (ob_get_level() > 0) ob_end_clean();
    check(false, "Lỗi GiangVienController::dashboard(): " . $e->getMessage());
}

// 6. Kiểm tra Cán bộ QL Dashboard có Embedded Web Viewer
echo PHP_EOL . "6. KIỂM TRA PHÂN HỆ CÁN BỘ QL / ADMIN DASHBOARD (EMBEDDED WEB VIEWER):" . PHP_EOL;
try {
    AuthHelper::login([
        'MaNguoiDung' => 'CB001',
        'HoTen' => 'ThS. Trần Thị Bích',
        'VaiTro' => App::ROLE_CAN_BO_QL,
        'Email' => 'cb_tranthib@utb.edu.vn'
    ]);
    ob_start();
    (new CanBoQLController())->dashboard();
    $cbHtml = ob_get_clean();

    check(strlen($cbHtml) > 3000, "CanBoQLController::dashboard() kết xuất HTML thành công");
    check(strpos($cbHtml, 'cb_utb_iframe') !== false, "Dashboard Cán bộ QL có nhúng iframe cb_utb_iframe");
    check(strpos($cbHtml, 'https://utb.edu.vn/gioi-thieu/') !== false, "Dashboard Cán bộ QL nhúng https://utb.edu.vn/gioi-thieu/");
    check(strpos($cbHtml, 'Cửa Sổ Website Trường Đại Học Tây Bắc') !== false, "Dashboard Cán bộ QL có tiêu đề Cửa Sổ UTB");
    check(strpos($cbHtml, 'Thái Bình') === false, "Dashboard Cán bộ QL KHÔNG chứa Thái Bình");
} catch (\Throwable $e) {
    if (ob_get_level() > 0) ob_end_clean();
    check(false, "Lỗi CanBoQLController::dashboard(): " . $e->getMessage());
}

// 7. Kiểm tra Auth Login & Register Views
echo PHP_EOL . "7. KIỂM TRA AUTH (LOGIN & REGISTER):" . PHP_EOL;
try {
    $_SESSION = [];
    ob_start();
    (new AuthController())->login();
    $loginHtml = ob_get_clean();

    check(strpos($loginHtml, 'TRƯỜNG ĐẠI HỌC TÂY BẮC') !== false, "Login page có tên TRƯỜNG ĐẠI HỌC TÂY BẮC");
    check(strpos($loginHtml, 'Sơn La') !== false, "Login page có địa danh Sơn La");
    check(strpos($loginHtml, 'Cửa Sổ utb.edu.vn ↗') !== false, "Login page có link Cửa Sổ utb.edu.vn");
    check(strpos($loginHtml, 'Thái Bình') === false, "Login page KHÔNG chứa Thái Bình");

    ob_start();
    (new AuthController())->register();
    $regHtml = ob_get_clean();

    check(strpos($regHtml, 'TRƯỜNG ĐẠI HỌC TÂY BẮC') !== false, "Register page có tên TRƯỜNG ĐẠI HỌC TÂY BẮC");
    check(strpos($regHtml, 'Sơn La') !== false, "Register page có địa danh Sơn La");
    check(strpos($regHtml, 'Cửa Sổ utb.edu.vn ↗') !== false, "Register page có link Cửa Sổ utb.edu.vn");
    check(strpos($regHtml, 'Thái Bình') === false, "Register page KHÔNG chứa Thái Bình");
} catch (\Throwable $e) {
    if (ob_get_level() > 0) ob_end_clean();
    check(false, "Lỗi AuthController: " . $e->getMessage());
}

// 8. TỔNG KẾT
echo PHP_EOL . "==========================================" . PHP_EOL;
echo "KẾT QUẢ KIỂM THỬ: {$passed} PASSED, {$failed} FAILED." . PHP_EOL;
if ($failed === 0) {
    echo ">>> TẤT CẢ CÁC BÀI TEST ĐỀU VƯỢT QUA 100% <<<" . PHP_EOL;
    exit(0);
} else {
    echo ">>> CÓ {$failed} BÀI TEST BỊ THẤT BẠI! <<<" . PHP_EOL;
    exit(1);
}

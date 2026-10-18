<?php
declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once __DIR__ . '/../app/Config/Database.php';
require_once __DIR__ . '/../app/Config/App.php';
require_once __DIR__ . '/../app/Helpers/AuthHelper.php';
require_once __DIR__ . '/../app/Helpers/ViewHelper.php';
require_once __DIR__ . '/../app/Helpers/NckhFormHelper.php';

use App\Config\App;
use App\Helpers\NckhFormHelper;

echo "=== TEST BẮT ĐẦU: KIỂM THỬ LOGO & CỔNG GIỚI THIỆU TRƯỜNG ĐH TÂY BẮC (SƠN LA) ===" . PHP_EOL;

$passCount = 0;
$failCount = 0;

function assertTest(bool $condition, string $message): void {
    global $passCount, $failCount;
    if (!$condition) {
        echo "  [FAIL] $message" . PHP_EOL;
        $failCount++;
        exit(1);
    }
    echo "  [PASS] $message" . PHP_EOL;
    $passCount++;
}

$baseUrl = 'http://localhost/web1/HTQL_NCKH_SV_TBU/index.php';

function httpGet(string $url, string $cookieFile = ''): array {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    if (!empty($cookieFile) && file_exists($cookieFile)) {
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
    }
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $body = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return ['code' => $httpCode, 'body' => (string)$body];
}

function httpLogin(string $username, string $password, string $cookieFile): bool {
    global $baseUrl;
    $ch = curl_init($baseUrl . '?action=login');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
        'username' => $username,
        'password' => $password
    ]));
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $body = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return $httpCode === 200 && strpos((string)$body, 'Đăng xuất') !== false;
}

// 1. Kiểm tra tồn tại và tính hợp lệ của Logo
echo PHP_EOL . "--- 1. Kiểm tra File Logo Trường ĐH Tây Bắc ---" . PHP_EOL;
$logoPath = dirname(__DIR__) . '/pic/logo.jpg';
assertTest(file_exists($logoPath), "File pic/logo.jpg tồn tại");
assertTest(filesize($logoPath) > 50000, "File pic/logo.jpg có dung lượng hợp lệ: " . number_format(filesize($logoPath)) . " bytes");

$publicLogoPath = dirname(__DIR__) . '/public/images/logo.jpg';
assertTest(file_exists($publicLogoPath), "File backup public/images/logo.jpg tồn tại");

// 2. Kiểm tra Giao diện chính mặc định (Root URL / index.php không tham số)
echo PHP_EOL . "--- 2. Kiểm tra Giao diện chính (Default Landing Page) ---" . PHP_EOL;
$resDefault = httpGet($baseUrl);
assertTest($resDefault['code'] === 200, "Truy cập index.php mặc định trả về HTTP 200 OK");
assertTest(strpos($resDefault['body'], 'TRƯỜNG ĐẠI HỌC TÂY BẮC') !== false, "Giao diện chính mặc định hiển thị Cổng Giới thiệu & Cửa Sổ Trực Tuyến ĐH Tây Bắc");
assertTest(strpos($resDefault['body'], 'pic/logo.jpg') !== false, "Giao diện chính có logo Trường ĐH Tây Bắc");
assertTest(strpos($resDefault['body'], 'utb_web_iframe') !== false, "Giao diện chính có iframe nhúng web trường");

// 2.1 Kiểm tra các alias portal & home
$resPortal = httpGet($baseUrl . '?action=portal');
assertTest($resPortal['code'] === 200 && strpos($resPortal['body'], 'TRƯỜNG ĐẠI HỌC TÂY BẮC') !== false, "GET action=portal hiển thị đúng Cổng Giới thiệu UTB");

$resHome = httpGet($baseUrl . '?action=home');
assertTest($resHome['code'] === 200 && strpos($resHome['body'], 'TRƯỜNG ĐẠI HỌC TÂY BẮC') !== false, "GET action=home hiển thị đúng Cổng Giới thiệu UTB");

$resUtb = httpGet($baseUrl . '?action=utb-web');
assertTest($resUtb['code'] === 200 && strpos($resUtb['body'], 'utb_web_iframe') !== false, "GET action=utb-web hiển thị đúng Cửa Sổ UTB Browser Window");

// 2.2 Kiểm tra nội dung chi tiết Cổng Giới thiệu UTB (action=gioi-thieu)
echo PHP_EOL . "--- 3. Kiểm tra Nội dung Cổng Giới thiệu UTB (action=gioi-thieu) ---" . PHP_EOL;
$resPublic = httpGet($baseUrl . '?action=gioi-thieu');
assertTest($resPublic['code'] === 200, "GET action=gioi-thieu trả về HTTP 200 OK");
assertTest(strpos($resPublic['body'], 'TRƯỜNG ĐẠI HỌC TÂY BẮC') !== false, "Có tên đầy đủ Trường ĐH Tây Bắc");
assertTest(strpos($resPublic['body'], 'TAY BAC UNIVERSITY') !== false, "Có tên tiếng Anh TAY BAC UNIVERSITY (UTB)");
assertTest(strpos($resPublic['body'], 'Sơn La') !== false, "Có địa danh Sơn La chính xác");
assertTest(strpos($resPublic['body'], 'Sứ Mạng') !== false, "Có thông tin Sứ Mạng chính thức");
assertTest(strpos($resPublic['body'], 'Tầm Nhìn Đến Năm 2030') !== false, "Có thông tin Tầm Nhìn 2030");
assertTest(strpos($resPublic['body'], '39/2001/QĐ-TTg') !== false, "Có trích dẫn QĐ số 39/2001/QĐ-TTg của Thủ tướng Chính phủ");
assertTest(strpos($resPublic['body'], 'Khoa Công nghệ Thông tin') !== false, "Có thông tin Khoa Công nghệ Thông tin");
assertTest(strpos($resPublic['body'], 'Khoa Nông - Lâm') !== false, "Có thông tin Khoa Nông - Lâm thế mạnh Sơn La");
assertTest(strpos($resPublic['body'], 'Khoa Sư phạm') !== false, "Có thông tin Khoa Sư phạm truyền thống 1960");
assertTest(strpos($resPublic['body'], 'Bảng Vinh Danh Các Công Trình') !== false, "Có Bảng vinh danh công trình NCKH đạt giải");
assertTest(strpos($resPublic['body'], 'Quy Trình 5 Bước') !== false, "Có Quy trình 5 bước NCKH sinh viên UTB");
assertTest(strpos($resPublic['body'], 'https://utb.edu.vn/gioi-thieu/') !== false, "Có liên kết tham chiếu chính thức utb.edu.vn/gioi-thieu/");
assertTest(strpos($resPublic['body'], 'btn-portal-back-to-top') !== false, "Có nút cuộn nhanh lên đầu trang Back to Top");
assertTest(strpos($resPublic['body'], 'Thái Bình') === false, "Tuyệt đối KHÔNG chứa chuỗi Thái Bình");

// 3. Kiểm tra nhúng Logo trên trang Login & Register
echo PHP_EOL . "--- 4. Kiểm tra Logo trên trang Login & Register ---" . PHP_EOL;
$resLogin = httpGet($baseUrl . '?action=login');
assertTest($resLogin['code'] === 200, "Trang Login trả về 200 OK");
assertTest(strpos($resLogin['body'], 'pic/logo.jpg') !== false, "Trang Login có logo pic/logo.jpg");
assertTest(strpos($resLogin['body'], 'TRƯỜNG ĐẠI HỌC TÂY BẮC') !== false, "Trang Login có tên Trường ĐH Tây Bắc");
assertTest(strpos($resLogin['body'], 'action=gioi-thieu') !== false, "Trang Login có nút liên kết tới Cổng Giới thiệu UTB");

$resRegister = httpGet($baseUrl . '?action=register');
assertTest($resRegister['code'] === 200, "Trang Register trả về 200 OK");
assertTest(strpos($resRegister['body'], 'pic/logo.jpg') !== false, "Trang Register có logo pic/logo.jpg");
assertTest(strpos($resRegister['body'], 'TRƯỜNG ĐẠI HỌC TÂY BẮC') !== false, "Trang Register có tên Trường ĐH Tây Bắc");
assertTest(strpos($resRegister['body'], 'action=gioi-thieu') !== false, "Trang Register có nút liên kết tới Cổng Giới thiệu UTB");

// 4. Kiểm tra nhúng Logo trên Sidebar & Navbar khi người dùng đăng nhập
echo PHP_EOL . "--- 5. Kiểm tra Sidebar & Navbar khi đăng nhập ---" . PHP_EOL;
$cookieFile = __DIR__ . '/cookie_test_portal.txt';
if (file_exists($cookieFile)) unlink($cookieFile);
$logged = httpLogin('SV001', '123456', $cookieFile);
assertTest($logged, "Đăng nhập SV001 thành công");

$resSvDash = httpGet($baseUrl . '?action=sv-dashboard', $cookieFile);
assertTest($resSvDash['code'] === 200, "Dashboard Sinh viên trả về 200 OK");
assertTest(strpos($resSvDash['body'], 'pic/logo.jpg') !== false, "Sidebar Sinh viên có logo pic/logo.jpg");
assertTest(strpos($resSvDash['body'], 'ĐẠI HỌC TÂY BẮC') !== false, "Sidebar Sinh viên có tên ĐẠI HỌC TÂY BẮC");
assertTest(strpos($resSvDash['body'], 'sv_utb_iframe') !== false, "Dashboard Sinh viên có Cửa Sổ UTB Web iframe");
assertTest(strpos($resSvDash['body'], 'dash-mode-tabs') !== false, "Dashboard Sinh viên có thanh chuyển đổi giao diện chính kép");

// Xem trang gioi-thieu khi đã đăng nhập (chế độ tích hợp dashboard)
$resPortalLoggedIn = httpGet($baseUrl . '?action=gioi-thieu', $cookieFile);
assertTest($resPortalLoggedIn['code'] === 200, "action=gioi-thieu khi đã đăng nhập trả về 200 OK");
assertTest(strpos($resPortalLoggedIn['body'], 'utb_web_iframe') !== false, "Hiển thị Cửa Sổ UTB Browser Window cho người dùng đăng nhập");

if (file_exists($cookieFile)) unlink($cookieFile);

// 5. Kiểm tra biểu mẫu NckhFormHelper (Mẫu 02 & Mẫu 01)
echo PHP_EOL . "--- 6. Kiểm tra biểu mẫu NCKH (Phiếu Đề Xuất & Thuyết Minh) ---" . PHP_EOL;
$sampleDX = NckhFormHelper::getSampleDeXuatData();
$htmlDX = NckhFormHelper::renderPhieuDeXuatHtml($sampleDX);
assertTest(strpos($htmlDX, 'pic/logo.jpg') !== false, "Phiếu Đề Xuất (Mẫu 02) có logo pic/logo.jpg");
assertTest(strpos($htmlDX, 'TRƯỜNG ĐẠI HỌC TÂY BẮC') !== false, "Phiếu Đề Xuất có tên TRƯỜNG ĐẠI HỌC TÂY BẮC");
assertTest(strpos($htmlDX, 'Sơn La, ngày') !== false, "Phiếu Đề Xuất ghi nhận địa danh Sơn La");

$sampleTM = NckhFormHelper::getSampleThuyetMinhData();
$htmlTM = NckhFormHelper::renderThuyetMinhHtml($sampleTM);
assertTest(strpos($htmlTM, 'pic/logo.jpg') !== false, "Thuyết Minh Đề Tài (Mẫu 01) có logo pic/logo.jpg");
assertTest(strpos($htmlTM, 'TRƯỜNG ĐẠI HỌC TÂY BẮC') !== false, "Thuyết Minh Đề Tài có tên TRƯỜNG ĐẠI HỌC TÂY BẮC");
assertTest(strpos($htmlTM, 'Sơn La, ngày') !== false, "Thuyết Minh Đề Tài ghi nhận địa danh Sơn La");

// 6. Kiểm tra Edge Case: Render biểu mẫu khi dữ liệu trống ([])
echo PHP_EOL . "--- 7. Kiểm tra Edge Case: Render biểu mẫu trống không có cảnh báo PHP ---" . PHP_EOL;
$emptyHtmlDX = NckhFormHelper::renderPhieuDeXuatHtml([]);
assertTest(strlen($emptyHtmlDX) > 1000 && strpos($emptyHtmlDX, 'TRƯỜNG ĐẠI HỌC TÂY BẮC') !== false, "renderPhieuDeXuatHtml([]) chạy thành công không có lỗi/warning");

$emptyHtmlTM = NckhFormHelper::renderThuyetMinhHtml([]);
assertTest(strlen($emptyHtmlTM) > 1000 && strpos($emptyHtmlTM, 'TRƯỜNG ĐẠI HỌC TÂY BẮC') !== false, "renderThuyetMinhHtml([]) chạy thành công không có lỗi/warning");

// 7. Kiểm tra loại bỏ hoàn toàn địa danh cũ trong form đăng ký
echo PHP_EOL . "--- 8. Kiểm tra loại bỏ địa danh cũ trong Form Đăng Ký ---" . PHP_EOL;
$dkdtContent = file_get_contents(dirname(__DIR__) . '/views/sinhvien/dangky_detai.php');
assertTest(strpos($dkdtContent, 'Sơn La, ngày') !== false, "views/sinhvien/dangky_detai.php mặc định 'Sơn La, ngày'");
assertTest(strpos($dkdtContent, 'Thái Bình') === false, "views/sinhvien/dangky_detai.php hoàn toàn KHÔNG chứa 'Thái Bình'");

echo PHP_EOL . "========================================================" . PHP_EOL;
echo "TỔNG KẾT: Pass: $passCount | Fail: $failCount (100% PASS)" . PHP_EOL;
echo "========================================================" . PHP_EOL;

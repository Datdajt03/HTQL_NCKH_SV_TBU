<?php
declare(strict_types=1);

error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
ini_set('display_errors', '1');

require_once __DIR__ . '/../app/Config/Database.php';
require_once __DIR__ . '/../app/Config/App.php';
require_once __DIR__ . '/../app/Helpers/AuthHelper.php';
require_once __DIR__ . '/../app/Helpers/NckhFormHelper.php';
require_once __DIR__ . '/../app/Models/BaseModel.php';
require_once __DIR__ . '/../app/Models/UserModel.php';
require_once __DIR__ . '/../app/Models/DeTaiModel.php';
require_once __DIR__ . '/../app/Models/BaoCaoTienDoModel.php';

use App\Config\App;
use App\Config\Database;
use App\Helpers\AuthHelper;
use App\Helpers\NckhFormHelper;
use App\Models\UserModel;
use App\Models\DeTaiModel;
use App\Models\BaoCaoTienDoModel;

if (session_status() === PHP_SESSION_NONE) {
    @session_start();
}

echo "=== TEST BẮT ĐẦU: KIỂM TRA TOÀN BỘ YÊU CẦU MỚI ===" . PHP_EOL;

// 1. KIỂM TRA PHÂN QUYỀN VÀ ROLE SWITCHER DÀNH RIÊNG CHO ADMIN
echo PHP_EOL . "--- 1. Kiểm tra Role Switcher & Admin Privileges ---" . PHP_EOL;
$userModel = new UserModel();

// Case 1.1: Đăng nhập sinh viên SV001 thường
AuthHelper::logout();
$sv = $userModel->findById('SV001');
AuthHelper::login($sv);
assert(!AuthHelper::isAdmin(), "FAIL: Sinh viên SV001 thường không được là Admin!");
echo "✓ SV001 login: isAdmin() = " . (AuthHelper::isAdmin() ? 'TRUE' : 'FALSE') . " (Chính xác: không hiển thị switcher)" . PHP_EOL;

// Case 1.2: Đăng nhập giảng viên GV001 thường
AuthHelper::logout();
$gv = $userModel->findById('GV001');
AuthHelper::login($gv);
assert(!AuthHelper::isAdmin(), "FAIL: Giảng viên GV001 thường không được là Admin!");
echo "✓ GV001 login: isAdmin() = " . (AuthHelper::isAdmin() ? 'TRUE' : 'FALSE') . " (Chính xác: không hiển thị switcher)" . PHP_EOL;

// Case 1.3: Đăng nhập Cán bộ CB001 thường
AuthHelper::logout();
$cb = $userModel->findById('CB001');
AuthHelper::login($cb);
assert(!AuthHelper::isAdmin(), "FAIL: Cán bộ CB001 thường không được là Admin!");
echo "✓ CB001 login: isAdmin() = " . (AuthHelper::isAdmin() ? 'TRUE' : 'FALSE') . " (Chính xác: không hiển thị switcher)" . PHP_EOL;

// Case 1.4: Đăng nhập ADMIN
AuthHelper::logout();
$admin = $userModel->findById('ADMIN');
AuthHelper::login($admin);
assert(AuthHelper::isAdmin(), "FAIL: ADMIN phải là Admin!");
echo "✓ ADMIN login: isAdmin() = TRUE (Chính xác: hiển thị switcher)" . PHP_EOL;

// Case 1.5: ADMIN đóng vai Sinh viên (SV001)
$_SESSION['admin_root'] = true;
$_SESSION['user'] = [
    'id' => $sv['MaNguoiDung'],
    'name' => $sv['HoTen'],
    'email' => $sv['Email'],
    'phone' => $sv['SoDienThoai'] ?? '',
    'role' => $sv['VaiTro'],
    'extra' => $sv['extra'] ?? []
];
assert(AuthHelper::isAdmin(), "FAIL: Admin khi chuyển sang Sinh viên vẫn phải có isAdmin() = TRUE!");
assert(AuthHelper::id() === 'SV001', "FAIL: Current user id phải là SV001!");
assert(AuthHelper::role() === App::ROLE_SINH_VIEN, "FAIL: Current role phải là SINH_VIEN!");
echo "✓ Admin switch to Sinh viên: id={$sv['MaNguoiDung']}, role={$sv['VaiTro']}, isAdmin()=" . (AuthHelper::isAdmin() ? 'TRUE' : 'FALSE') . " (Chính xác: full quyền & giữ switcher)" . PHP_EOL;

// Case 1.6: ADMIN đóng vai Giảng viên (GV001)
$_SESSION['user'] = [
    'id' => $gv['MaNguoiDung'],
    'name' => $gv['HoTen'],
    'email' => $gv['Email'],
    'phone' => $gv['SoDienThoai'] ?? '',
    'role' => $gv['VaiTro'],
    'extra' => $gv['extra'] ?? []
];
assert(AuthHelper::isAdmin(), "FAIL: Admin khi chuyển sang GV vẫn phải có isAdmin() = TRUE!");
assert(AuthHelper::id() === 'GV001', "FAIL: Current user id phải là GV001!");
echo "✓ Admin switch to Giảng viên: id={$gv['MaNguoiDung']}, role={$gv['VaiTro']}, isAdmin()=" . (AuthHelper::isAdmin() ? 'TRUE' : 'FALSE') . " (Chính xác: full quyền & giữ switcher)" . PHP_EOL;

// Case 1.7: ADMIN đóng vai Cán bộ QL (CB001)
$_SESSION['user'] = [
    'id' => $cb['MaNguoiDung'],
    'name' => $cb['HoTen'],
    'email' => $cb['Email'],
    'phone' => $cb['SoDienThoai'] ?? '',
    'role' => $cb['VaiTro'],
    'extra' => $cb['extra'] ?? []
];
assert(AuthHelper::isAdmin(), "FAIL: Admin khi chuyển sang Cán bộ vẫn phải có isAdmin() = TRUE!");
assert(AuthHelper::id() === 'CB001', "FAIL: Current user id phải là CB001!");
echo "✓ Admin switch to Cán bộ QL: id={$cb['MaNguoiDung']}, role={$cb['VaiTro']}, isAdmin()=" . (AuthHelper::isAdmin() ? 'TRUE' : 'FALSE') . " (Chính xác: full quyền & giữ switcher)" . PHP_EOL;


// 2. KIỂM TRA BÁO CÁO TIẾN ĐỘ THEO MỤC 12 VÀ PHÊ DUYỆT CỦA CÁN BỘ QUẢN LÝ
echo PHP_EOL . "--- 2. Kiểm tra Báo Cáo Tiến Độ Theo Mục 12 Thuyết Minh ---" . PHP_EOL;
$deTaiModel = new DeTaiModel();
$tienDoModel = new BaoCaoTienDoModel();

// Tìm 1 đề tài đang thực hiện hoặc duyệt
$topic = $deTaiModel->findById('DT_2026_001');

echo "✓ Đang test với đề tài: {$topic['MaDeTai']} - {$topic['TenDeTai']} (Trạng thái: {$topic['TrangThai']})" . PHP_EOL;

// Tạo dữ liệu mục 12 theo 4 mức độ:
// 1: Hoàn thành (100%)
// 2: Đã hoàn thành 1 nửa (50%)
// 3: Sắp hoàn thành (75%)
// 4: Chưa hoàn thành (0%)
$tasks = [
    [
        'tt' => 1,
        'noi_dung' => 'Tổng quan tài liệu, tìm hiểu bài toán s-Club và các thuật toán gần đúng',
        'san_pham_ke_hoach' => 'Báo cáo tổng hợp',
        'thoi_gian' => 'Tháng 10/2025 đến tháng 12/2025',
        'nguoi_thuc_hien' => 'Nhóm',
        'muc_do' => 'HOAN_THANH',
        'phan_tram' => 100,
        'san_pham_nop' => 'https://github.com/tbu/s-club-algorithms/doc/report-overview.pdf',
        'ghi_chu' => 'Đã hoàn tất toàn bộ tài liệu nghiên cứu'
    ],
    [
        'tt' => 2,
        'noi_dung' => 'Nghiên cứu, cài đặt thử nghiệm các thuật toán CONSTELLATION, DROP, IDROP, GRASP',
        'san_pham_ke_hoach' => 'Mã nguồn và bản mô tả thuật toán',
        'thoi_gian' => 'Đầu tháng 01/2026 đến tháng 04/2026',
        'nguoi_thuc_hien' => 'Nhóm',
        'muc_do' => 'HOAN_THANH_MOT_NUA',
        'phan_tram' => 50,
        'san_pham_nop' => 'https://github.com/tbu/s-club-algorithms/tree/develop',
        'ghi_chu' => 'Đã xong DROP và CONSTELLATION, đang viết IDROP'
    ],
    [
        'tt' => 3,
        'noi_dung' => 'Thiết kế và mô phỏng thuật toán',
        'san_pham_ke_hoach' => 'Mã nguồn và báo cáo kết quả mô phỏng',
        'thoi_gian' => 'Đầu tháng 05/2026 đến tháng 09/2026',
        'nguoi_thuc_hien' => 'Nhóm',
        'muc_do' => 'SAP_HOAN_THANH',
        'phan_tram' => 75,
        'san_pham_nop' => 'Bộ dữ liệu thực nghiệm SNAP và kết quả ban đầu',
        'ghi_chu' => 'Đang chạy benchmark trên máy chủ'
    ],
    [
        'tt' => 4,
        'noi_dung' => 'Viết báo cáo tổng kết, chỉnh sửa và nộp đề tài',
        'san_pham_ke_hoach' => 'Báo cáo hoàn chỉnh',
        'thoi_gian' => 'Đầu tháng 10/2026',
        'nguoi_thuc_hien' => 'Nhóm',
        'muc_do' => 'CHUA_HOAN_THANH',
        'phan_tram' => 0,
        'san_pham_nop' => '',
        'ghi_chu' => 'Chưa đến thời điểm thực hiện'
    ]
];

// Tính toán phần trăm tổng thể tự động: (100 + 50 + 75 + 0) / 4 = 56.25 => round = 56%
$calculatedPercent = (int)round((100 + 50 + 75 + 0) / 4);
echo "✓ Tính toán tiến độ tự động từ 4 mục: 56% (Thực tế: {$calculatedPercent}%)" . PHP_EOL;

// Lưu báo cáo tiến độ vào DB
$dotBaoCao = "Báo cáo thử nghiệm Mục 12 - Ngày " . date('d/m/Y H:i:s');
$ok = $tienDoModel->createReport([
    'MaDeTai' => $topic['MaDeTai'],
    'DotBaoCao' => $dotBaoCao,
    'PhanTramHoanThanh' => $calculatedPercent,
    'CongViecDaLam' => "Hoàn thành 3/4 nội dung mục 12 Thuyết minh",
    'ChiTietTienDo' => json_encode($tasks, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT),
    'KhoKhanVuongMac' => "Cần thêm bộ nhớ RAM cho phòng máy",
    'FileBaoCao' => null
]);
assert($ok, "FAIL: Không thể lưu báo cáo tiến độ!");
echo "✓ Đã lưu thành công Báo cáo tiến độ với ChiTietTienDo JSON vào CSDL!" . PHP_EOL;

// Lấy lại báo cáo vừa tạo
$reports = $tienDoModel->getByTopic($topic['MaDeTai']);
$latestReport = $reports[0];
$reportId = (int)$latestReport['MaBaoCaoTD'];
echo "✓ Báo cáo ID {$reportId}: Dot={$latestReport['DotBaoCao']}, %={$latestReport['PhanTramHoanThanh']}%" . PHP_EOL;

$savedTasks = json_decode($latestReport['ChiTietTienDo'], true);
assert(is_array($savedTasks) && count($savedTasks) === 4, "FAIL: Chi tiết tiến độ phải có 4 mục!");
echo "✓ Chi tiết tiến độ giải mã JSON thành công, gồm 4 mục với các mức độ hoàn thành và link sản phẩm nộp." . PHP_EOL;

// 3. GVHD ĐÁNH GIÁ TIẾN ĐỘ
echo PHP_EOL . "--- 3. Kiểm tra Giảng viên Hướng dẫn Đánh Giá ---" . PHP_EOL;
$okGV = $tienDoModel->teacherReview($reportId, 'DAT_TIEN_DO', 'Nhóm bám sát tiến độ mục 1 và 3 rất tốt, tiếp tục phát huy!');
assert($okGV, "FAIL: GVHD đánh giá thất bại!");
$updatedReport = $tienDoModel->findById($reportId);
assert($updatedReport['DanhGiaTienDo'] === 'DAT_TIEN_DO', "FAIL: Đánh giá GVHD không đúng!");
echo "✓ GVHD đánh giá tiến độ thành công: {$updatedReport['DanhGiaTienDo']} - {$updatedReport['NhanXetGVHD']}" . PHP_EOL;

// 4. CÁN BỘ QUẢN LÝ PHÊ DUYỆT TIẾN ĐỘ
echo PHP_EOL . "--- 4. Kiểm tra Cán Bộ Quản Lý Phê Duyệt Tiến Độ ---" . PHP_EOL;
$okCB = $tienDoModel->canBoReview($reportId, 'DAT_TIEN_DO', 'Phòng Quản lý NCKH đã thẩm định sản phẩm nộp của mục 1 & 3, đề tài đạt tiến độ.');
assert($okCB, "FAIL: Cán bộ QL thẩm định thất bại!");
$updatedReport = $tienDoModel->findById($reportId);
assert($updatedReport['DanhGiaCanBo'] === 'DAT_TIEN_DO', "FAIL: Thẩm định Cán bộ QL không đúng!");
echo "✓ Cán bộ QL phê duyệt tiến độ thành công: {$updatedReport['DanhGiaCanBo']} - {$updatedReport['NhanXetCanBo']}" . PHP_EOL;

// 5. OVERVIEW TOÀN TRƯỜNG
echo PHP_EOL . "--- 5. Kiểm tra Danh Sách Giám Sát Tiến Độ Toàn Trường ---" . PHP_EOL;
$overview = $tienDoModel->getAllProgressOverview();
$found = false;
foreach ($overview as $item) {
    if ($item['MaDeTai'] === $topic['MaDeTai']) {
        $found = true;
        assert($item['PhanTramHoanThanh'] == $calculatedPercent, "FAIL: Tiến độ overview không khớp!");
        assert($item['DanhGiaCanBo'] === 'DAT_TIEN_DO', "FAIL: Cán bộ QL review không có trong overview!");
        echo "✓ Overview hiển thị chính xác đề tài {$item['MaDeTai']}: Tiến độ {$item['PhanTramHoanThanh']}%, GVHD: {$item['DanhGiaTienDo']}, Cán bộ QL: {$item['DanhGiaCanBo']}" . PHP_EOL;
        break;
    }
}
assert($found, "FAIL: Không tìm thấy đề tài trong overview!");

echo PHP_EOL . "=== TOÀN BỘ 5 BƯỚC KIỂM THỬ ĐÃ PASS 100%! ===" . PHP_EOL;

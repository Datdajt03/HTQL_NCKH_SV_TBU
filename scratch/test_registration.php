<?php
require_once __DIR__ . '/../app/Config/Database.php';
require_once __DIR__ . '/../app/Config/App.php';
require_once __DIR__ . '/../app/Helpers/AuthHelper.php';
require_once __DIR__ . '/../app/Models/BaseModel.php';
require_once __DIR__ . '/../app/Models/UserModel.php';

use App\Config\App;
use App\Config\Database;
use App\Helpers\AuthHelper;
use App\Models\UserModel;

$baseUrl = 'http://localhost/web1/HTQL_NCKH_SV_TBU/index.php';
$cookieFile = __DIR__ . '/cookie_register_test.txt';
if (file_exists($cookieFile)) unlink($cookieFile);

function postReq($url, $data, $cookieFile) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $res = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $finalUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
    curl_close($ch);
    return ['body' => $res, 'code' => $code, 'url' => $finalUrl];
}

echo "=== TEST ACCOUNT REGISTRATION (ĐĂNG KÝ TÀI KHOẢN) ===" . PHP_EOL;

$userModel = new UserModel();

$db = Database::getConnection();

// Clean up test accounts if previously created
$testSvId = 'SV999';
$testGvId = 'GV999';
$db->prepare("DELETE FROM SinhVien WHERE MaSV = :id")->execute(['id' => $testSvId]);
$db->prepare("DELETE FROM GiangVien WHERE MaGV = :id")->execute(['id' => $testGvId]);
$db->prepare("DELETE FROM NguoiDung WHERE MaNguoiDung IN (:s1, :s2) OR Email IN ('sv999@school.edu.vn', 'gv999@school.edu.vn')")->execute(['s1' => $testSvId, 's2' => $testGvId]);

// 1. Kiểm tra tải trang đăng ký HTTP 200
$ch = curl_init($baseUrl . '?action=register');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$regPage = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
assert($code === 200, "FAIL: Trang đăng ký phải trả về HTTP 200!");
assert(strpos($regPage, 'ĐĂNG KÝ TÀI KHOẢN MỚI') !== false, "FAIL: Phải có tiêu đề đăng ký!");
echo "✓ Test 1: Truy cập trang đăng ký register.php thành công (HTTP 200)!" . PHP_EOL;

// 2. Kiểm tra validation khi mật khẩu không khớp
$resMismatch = postReq($baseUrl . '?action=register', [
    'vaitro' => App::ROLE_SINH_VIEN,
    'username' => 'SV999',
    'hoten' => 'Sinh Viên Test',
    'email' => 'sv999@school.edu.vn',
    'sdt' => '0999888777',
    'sv_lop' => 'K66 CNTT',
    'sv_khoa' => 'Khoa CNTT',
    'sv_nganh' => 'Khoa học Máy tính',
    'sv_khoahoc' => '2024 - 2028',
    'password' => '123456',
    'confirm_password' => '654321'
], $cookieFile);
assert(strpos($resMismatch['body'], 'Xác nhận mật khẩu mới không khớp') !== false, "FAIL: Phải báo lỗi khi mật khẩu không khớp");
echo "✓ Test 2: Validation mật khẩu không khớp hoạt động chính xác!" . PHP_EOL;

// 3. Đăng ký thành công tài khoản Sinh viên SV999
$resSv = postReq($baseUrl . '?action=register', [
    'vaitro' => App::ROLE_SINH_VIEN,
    'username' => 'SV999',
    'hoten' => 'Sinh Viên Thử Nghiệm SV999',
    'email' => 'sv999@school.edu.vn',
    'sdt' => '0999888777',
    'sv_lop' => 'K66 CNTT Kíp 1',
    'sv_khoa' => 'Khoa Công nghệ Thông tin',
    'sv_nganh' => 'Trí Tuệ Nhân Tạo',
    'sv_khoahoc' => '2024 - 2028',
    'password' => '123456',
    'confirm_password' => '123456'
], $cookieFile);

// Kiểm tra DB
$svInDb = $userModel->findById('SV999');
assert($svInDb !== null, "FAIL: Sinh viên SV999 phải được lưu vào bảng NguoiDung!");
assert($svInDb['HoTen'] === 'Sinh Viên Thử Nghiệm SV999', "FAIL: Họ tên phải khớp!");
assert($svInDb['VaiTro'] === App::ROLE_SINH_VIEN, "FAIL: Vai trò phải là SINH_VIEN!");
assert(!empty($svInDb['extra']), "FAIL: Phải có bảng SinhVien liên kết!");
assert($svInDb['extra']['Lop'] === 'K66 CNTT Kíp 1', "FAIL: Lớp phải khớp!");
echo "✓ Test 3: Đăng ký thành công Sinh viên SV999 và lưu chuẩn xác vào database MySQL!" . PHP_EOL;

// 4. Đăng nhập ngay với tài khoản Sinh viên SV999 vừa tạo
$resLoginSv = postReq($baseUrl . '?action=login', [
    'username' => 'SV999',
    'password' => '123456'
], $cookieFile);
assert(strpos($resLoginSv['url'], 'sv-dashboard') !== false, "FAIL: Đăng nhập SV999 phải chuyển hướng vào sv-dashboard!");
echo "✓ Test 4: Đăng nhập thành công với tài khoản Sinh viên SV999 vừa tạo!" . PHP_EOL;

// 5. Đăng ký thành công tài khoản Giảng viên GV999
$cookieGv = __DIR__ . '/cookie_gv_test.txt';
if (file_exists($cookieGv)) unlink($cookieGv);

$resGv = postReq($baseUrl . '?action=register', [
    'vaitro' => App::ROLE_GIANG_VIEN,
    'username' => 'GV999',
    'hoten' => 'TS. Giảng Viên Thử Nghiệm',
    'email' => 'gv999@school.edu.vn',
    'sdt' => '0988999888',
    'gv_hocham' => 'Phó Giáo sư',
    'gv_hocvi' => 'Tiến sĩ',
    'gv_donvicongtac' => 'Viện Trí Tuệ Nhân Tạo',
    'gv_linhvuc' => 'Xử lý ngôn ngữ tự nhiên, Thị giác máy tính',
    'password' => '123456',
    'confirm_password' => '123456'
], $cookieGv);

// Kiểm tra DB
$gvInDb = $userModel->findById('GV999');
assert($gvInDb !== null, "FAIL: Giảng viên GV999 phải được lưu vào bảng NguoiDung!");
assert($gvInDb['VaiTro'] === App::ROLE_GIANG_VIEN, "FAIL: Vai trò phải là GIANG_VIEN!");
assert(!empty($gvInDb['extra']), "FAIL: Phải có bảng GiangVien liên kết!");
assert($gvInDb['extra']['HocHam'] === 'Phó Giáo sư', "FAIL: Học hàm phải là Phó Giáo sư!");
assert($gvInDb['extra']['HocVi'] === 'Tiến sĩ', "FAIL: Học vị phải là Tiến sĩ!");
echo "✓ Test 5: Đăng ký thành công Giảng viên GV999 và lưu chuẩn xác vào database MySQL!" . PHP_EOL;

// 6. Đăng nhập với tài khoản Giảng viên GV999 vừa tạo
$resLoginGv = postReq($baseUrl . '?action=login', [
    'username' => 'gv999@school.edu.vn', // Thử đăng nhập bằng Email
    'password' => '123456'
], $cookieGv);
assert(strpos($resLoginGv['url'], 'gv-dashboard') !== false, "FAIL: Đăng nhập GV999 bằng Email phải chuyển hướng vào gv-dashboard!");
echo "✓ Test 6: Đăng nhập bằng Email thành công với Giảng viên GV999 vừa tạo!" . PHP_EOL;

// Dọn dẹp tài khoản test
$db->prepare("DELETE FROM SinhVien WHERE MaSV = :id")->execute(['id' => $testSvId]);
$db->prepare("DELETE FROM GiangVien WHERE MaGV = :id")->execute(['id' => $testGvId]);
$db->prepare("DELETE FROM NguoiDung WHERE MaNguoiDung IN (:s1, :s2)")->execute(['s1' => $testSvId, 's2' => $testGvId]);
echo "✓ Dọn dẹp tài khoản test hoàn tất!" . PHP_EOL;

echo "=== TẤT CẢ 6 KIỂM THỬ ĐĂNG KÝ TÀI KHOẢN ĐÃ PASS 100%! ===" . PHP_EOL;

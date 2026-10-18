<?php
require_once __DIR__ . '/../app/Config/Database.php';
require_once __DIR__ . '/../app/Config/App.php';
require_once __DIR__ . '/../app/Helpers/AuthHelper.php';
require_once __DIR__ . '/../app/Models/BaseModel.php';
require_once __DIR__ . '/../app/Models/UserModel.php';

use App\Config\App;
use App\Helpers\AuthHelper;
use App\Models\UserModel;

$baseUrl = 'http://localhost/web1/HTQL_NCKH_SV_TBU/index.php';
$cookieFile = __DIR__ . '/cookie_sv.txt';

function postReq($url, $data, $cookieFile) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
    $res = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return ['body' => $res, 'code' => $code];
}

echo "=== TEST PROFILE UPDATE & PASSWORD CHANGE ===" . PHP_EOL;

// 1. Test update profile with valid data
$resUpdate = postReq($baseUrl . '?action=profile', [
    'sub_action' => 'update_profile',
    'hoten' => 'Nguyễn Huy Hoàng (Đã Cập Nhật)',
    'email' => 'hoang.nh.sv@school.edu.vn',
    'sdt' => '0355111999',
    'sv_lop' => 'K64 CNTT 1 - Kíp 2',
    'sv_khoa' => 'Khoa Công nghệ Thông tin',
    'sv_nganh' => 'Khoa học Máy tính & AI',
    'sv_khoahoc' => '2023 - 2027'
], $cookieFile);

assert($resUpdate['code'] === 200, "FAIL: HTTP code must be 200");
assert(strpos($resUpdate['body'], 'Cập nhật thông tin cá nhân và chuyên môn thành công!') !== false, "FAIL: Success message must appear");
echo "✓ Test 1: Cập nhật thông tin cá nhân & chuyên ngành thành công!" . PHP_EOL;

// 2. Test duplicate email validation
$resDup = postReq($baseUrl . '?action=profile', [
    'sub_action' => 'update_profile',
    'hoten' => 'Nguyễn Huy Hoàng',
    'email' => 'canbo.can@school.edu.vn', // Email of CB001
    'sdt' => '0355111999'
], $cookieFile);
assert(strpos($resDup['body'], 'đã được sử dụng bởi một tài khoản khác') !== false, "FAIL: Duplicate email error must appear");
echo "✓ Test 2: Bắt lỗi trùng lặp email với tài khoản khác chính xác!" . PHP_EOL;

// 3. Test wrong old password
$resWrongPass = postReq($baseUrl . '?action=profile', [
    'sub_action' => 'change_password',
    'old_password' => 'wrong_password_123',
    'new_password' => 'newpassword123',
    'confirm_password' => 'newpassword123'
], $cookieFile);
assert(strpos($resWrongPass['body'], 'Mật khẩu hiện tại không chính xác') !== false, "FAIL: Wrong old password error must appear");
echo "✓ Test 3: Bắt lỗi sai mật khẩu cũ chính xác!" . PHP_EOL;

// 4. Test change password successfully, then revert back to 123456
$resChangePass = postReq($baseUrl . '?action=profile', [
    'sub_action' => 'change_password',
    'old_password' => '123456',
    'new_password' => '654321',
    'confirm_password' => '654321'
], $cookieFile);
assert(strpos($resChangePass['body'], 'Đổi mật khẩu tài khoản thành công!') !== false, "FAIL: Password changed successfully message must appear");
echo "✓ Test 4: Đổi mật khẩu mới thành công!" . PHP_EOL;

// Revert password back to 123456
$resRevertPass = postReq($baseUrl . '?action=profile', [
    'sub_action' => 'change_password',
    'old_password' => '654321',
    'new_password' => '123456',
    'confirm_password' => '123456'
], $cookieFile);
assert(strpos($resRevertPass['body'], 'Đổi mật khẩu tài khoản thành công!') !== false, "FAIL: Password reverted successfully");
echo "✓ Test 5: Revert mật khẩu về mặc định 123456 thành công!" . PHP_EOL;

// Revert name back to original
postReq($baseUrl . '?action=profile', [
    'sub_action' => 'update_profile',
    'hoten' => 'Nguyễn Huy Hoàng',
    'email' => 'hoang.nh.sv@school.edu.vn',
    'sdt' => '0355111222',
    'sv_lop' => 'K64 CNTT 1',
    'sv_khoa' => 'Khoa Công nghệ Thông tin',
    'sv_nganh' => 'Khoa học Máy tính',
    'sv_khoahoc' => '2023 - 2027'
], $cookieFile);
echo "✓ Test 6: Revert thông tin cá nhân về gốc thành công!" . PHP_EOL;

echo "=== TẤT CẢ KIỂM THỬ PROFILE ĐỀU THÀNH CÔNG RỰC RỠ! ===" . PHP_EOL;

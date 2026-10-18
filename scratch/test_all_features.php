<?php
/**
 * Comprehensive Automated Verification Suite
 * Tests Profile, Registration, Edit/Delete, Dashboards, and HTTP Endpoints
 */

require_once __DIR__ . '/../app/Config/App.php';
require_once __DIR__ . '/../app/Config/Database.php';

// PSR-4 Autoloading
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $baseDir = __DIR__ . '/../app/';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) return;
    $relativeClass = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
    if (file_exists($file)) require $file;
});

use App\Config\Database;
use App\Models\UserModel;
use App\Models\DotNCKHModel;
use App\Models\DeTaiModel;
use App\Models\KhenThuongModel;

$db = Database::getConnection();

$passed = 0;
$failed = 0;

function assertTest(bool $condition, string $title, ?string $detail = null) {
    global $passed, $failed;
    if ($condition) {
        $passed++;
        echo "  [PASS] {$title}\n";
    } else {
        $failed++;
        echo "  [FAIL] {$title}\n";
        if ($detail) echo "         Detail: {$detail}\n";
    }
}

echo "\n=== 1. TESTING USER REGISTRATION & INTEGRATION ===\n";
$userModel = new UserModel();

// Cleanup prior test artifacts
$db->exec("DELETE FROM SinhVien WHERE MaSV IN ('TEST_SV_99', 'TEST_SV_98')");
$db->exec("DELETE FROM GiangVien WHERE MaGV IN ('TEST_GV_99')");
$db->exec("DELETE FROM NguoiDung WHERE MaNguoiDung IN ('TEST_SV_99', 'TEST_SV_98', 'TEST_GV_99')");

// Test SV registration
$svRegSuccess = $userModel->registerUser([
    'MaNguoiDung' => 'TEST_SV_99',
    'MatKhauHash' => password_hash('123456', PASSWORD_BCRYPT),
    'HoTen'       => 'Nguyen Van Test SV',
    'Email'       => 'test_sv99@tbu.edu.vn',
    'SoDienThoai' => '0912345678',
    'VaiTro'      => 'SINH_VIEN',
    'TrangThai'   => 'HOAT_DONG'
], [
    'Lop'      => 'K64-CNTT1',
    'Khoa'     => 'Cong Nghe Thong Tin',
    'NganhHoc' => 'Khoa Hoc May Tinh',
    'KhoaHoc'  => '2023 - 2027'
]);
assertTest($svRegSuccess, "SinhVien account registration inserts successfully");

$svUser = $userModel->findById('TEST_SV_99');
assertTest(
    $svUser !== null && $svUser['HoTen'] === 'Nguyen Van Test SV' && ($svUser['extra']['Lop'] ?? '') === 'K64-CNTT1',
    "SinhVien record & extra table data accurately linked in DB"
);

// Test duplicate username & email prevention
assertTest($userModel->isUsernameTaken('TEST_SV_99'), "isUsernameTaken correctly detects existing username");
assertTest($userModel->isEmailTaken('test_sv99@tbu.edu.vn'), "isEmailTaken correctly detects existing email");
assertTest(!$userModel->isUsernameTaken('NON_EXISTENT_USER'), "isUsernameTaken returns false for available username");

// Test GV registration
$gvRegSuccess = $userModel->registerUser([
    'MaNguoiDung' => 'TEST_GV_99',
    'MatKhauHash' => password_hash('123456', PASSWORD_BCRYPT),
    'HoTen'       => 'TS. Tran Van Test GV',
    'Email'       => 'test_gv99@tbu.edu.vn',
    'SoDienThoai' => '0987654321',
    'VaiTro'      => 'GIANG_VIEN',
    'TrangThai'   => 'HOAT_DONG'
], [
    'HocHam'        => 'Phó Giáo Sư',
    'HocVi'         => 'Tiến sĩ',
    'DonViCongTac'  => 'Khoa CNTT & TT',
    'HuongNghienCuu'=> 'AI, Machine Learning'
]);
assertTest($gvRegSuccess, "GiangVien account registration inserts successfully");

$gvUser = $userModel->findById('TEST_GV_99');
assertTest(
    $gvUser !== null && ($gvUser['extra']['HocHam'] ?? '') === 'Phó Giáo Sư',
    "GiangVien record & extra table data accurately linked in DB"
);

echo "\n=== 2. TESTING USER MANAGEMENT (CRUD & STATUS TOGGLE) ===\n";
// Update detailed user profile
$updateResult = $userModel->updateProfileDetailed('TEST_SV_99', [
    'HoTen'       => 'Nguyen Van Test SV (Cap Nhat)',
    'Email'       => 'test_sv99_updated@tbu.edu.vn',
    'SoDienThoai' => '0999888777',
    'TrangThai'   => 'HOAT_DONG'
], [
    'Lop'      => 'K64-CNTT2',
    'Khoa'     => 'CNTT',
    'NganhHoc' => 'Ky Thuat Phan Mem',
    'KhoaHoc'  => '2024 - 2028'
]);
assertTest($updateResult, "Admin/Manager can update user detailed profile and role tables");

$updatedSV = $userModel->findById('TEST_SV_99');
assertTest(
    $updatedSV['HoTen'] === 'Nguyen Van Test SV (Cap Nhat)' && ($updatedSV['extra']['Lop'] ?? '') === 'K64-CNTT2',
    "Updated user information verified in database"
);

// Lock/Unlock toggle
$userModel->toggleUserStatus('TEST_SV_99', 'KHOA');
$lockedUser = $userModel->findById('TEST_SV_99');
assertTest($lockedUser['TrangThai'] === 'KHOA', "Account lock status toggles to KHOA");

$userModel->toggleUserStatus('TEST_SV_99', 'HOAT_DONG');
$unlockedUser = $userModel->findById('TEST_SV_99');
assertTest($unlockedUser['TrangThai'] === 'HOAT_DONG', "Account lock status toggles back to HOAT_DONG");

// Delete user
$delResult = $userModel->deleteUser('TEST_SV_99');
assertTest($delResult && $userModel->findById('TEST_SV_99') === null, "Delete user removes account and role record completely");

// Clean up test GV
$userModel->deleteUser('TEST_GV_99');

// Test getUserStats
$userStats = $userModel->getUserStats();
assertTest(
    isset($userStats['total'], $userStats['sinhVien'], $userStats['giangVien'], $userStats['canBo']) && $userStats['total'] > 0,
    "getUserStats provides comprehensive user role counts (Total: {$userStats['total']})"
);


echo "\n=== 3. TESTING RESEARCH ROUNDS, DIRECTIONS, NOTICES (CRUD) ===\n";
$dotModel = new DotNCKHModel();

// Clean up prior test dot
$db->exec("DELETE FROM DotNCKH WHERE MaDot = 'DOT_TEST_2027'");
$db->exec("DELETE FROM HuongNghienCuu WHERE MaHuongNC = 'HNC_TEST_ROBOT'");
$db->exec("DELETE FROM ThongBaoBieuMau WHERE TieuDe LIKE 'Test Thong Bao%'");

// 3.1 Test Round CRUD
$createDot = $dotModel->create([
    'MaDot'          => 'DOT_TEST_2027',
    'TenDot'         => 'Dot NCKH Test 2027',
    'NamHoc'         => '2026 - 2027',
    'NgayBatDau'     => '2026-09-01',
    'HanDangKy'      => '2026-10-30',
    'HanNopBaoCaoTD' => '2027-01-15',
    'HanNopNghiemThu'=> '2027-04-30',
    'NgayKetThuc'    => '2027-05-31',
    'TrangThai'      => 'MO_DANG_KY',
    'MaCanBoTao'     => 'CB001'
]);
assertTest($createDot, "Create new research round DOT_TEST_2027");

$updateDot = $dotModel->update('DOT_TEST_2027', [
    'TenDot'         => 'Dot NCKH Test 2027 (Da Cap Nhat)',
    'NamHoc'         => '2026 - 2027',
    'NgayBatDau'     => '2026-09-01',
    'HanDangKy'      => '2026-11-15',
    'HanNopBaoCaoTD' => '2027-01-20',
    'HanNopNghiemThu'=> '2027-05-10',
    'NgayKetThuc'    => '2027-06-01',
    'TrangThai'      => 'DANG_THUC_HIEN'
]);
assertTest($updateDot, "Update research round DOT_TEST_2027");

$delDot = $dotModel->delete('DOT_TEST_2027');
assertTest($delDot && $dotModel->findById('DOT_TEST_2027') === null, "Delete research round DOT_TEST_2027");

// 3.2 Test Direction CRUD
$createDir = $dotModel->createResearchDirection('HNC_TEST_ROBOT', 'Robotics & Tu Dong Hoa', 'Mo ta nghien cuu robot');
assertTest($createDir, "Create research direction HNC_TEST_ROBOT");

$updateDir = $dotModel->updateResearchDirection('HNC_TEST_ROBOT', 'Robotics & Tu Dong Hoa Thong Minh', 'Mo ta robot cap nhat');
assertTest($updateDir, "Update research direction HNC_TEST_ROBOT");

$delDir = $dotModel->deleteResearchDirection('HNC_TEST_ROBOT');
assertTest($delDir, "Delete research direction HNC_TEST_ROBOT");

// 3.3 Test Notification / Form CRUD
$addNotice = $dotModel->addNotification([
    'MaDot'      => null,
    'TieuDe'     => 'Test Thong Bao Mau 99',
    'Loai'       => 'BIEU_MAU',
    'NoiDung'    => 'Noi dung bieu mau test',
    'FileDinhKem'=> null,
    'NguoiDang'  => 'CB001'
]);
assertTest($addNotice, "Create notification / form entry");

$stmt = $db->prepare("SELECT MaThongBao FROM ThongBaoBieuMau WHERE TieuDe = 'Test Thong Bao Mau 99'");
$stmt->execute();
$noticeRow = $stmt->fetch(PDO::FETCH_ASSOC);
$noticeId = (int)($noticeRow['MaThongBao'] ?? 0);

$updateNotice = $dotModel->updateNotification($noticeId, [
    'MaDot'      => null,
    'TieuDe'     => 'Test Thong Bao Mau 99 (Da sua)',
    'Loai'       => 'QUY_DINH',
    'NoiDung'    => 'Noi dung quy dinh da cap nhat',
    'FileDinhKem'=> null
]);
assertTest($updateNotice, "Update notification / form entry");

$delNotice = $dotModel->deleteNotification($noticeId);
assertTest($delNotice, "Delete notification / form entry");


echo "\n=== 4. TESTING TOPIC MANAGEMENT (QUICK EDIT & DELETE) ===\n";
$deTaiModel = new DeTaiModel();

// Clean up prior test topic
$db->exec("DELETE FROM ThanhVienDeTai WHERE MaDeTai = 'DT_TEST_999'");
$db->exec("DELETE FROM DeTai WHERE MaDeTai = 'DT_TEST_999'");

// Create dummy topic
$dotActive = $dotModel->getActive()[0] ?? null;
$maDot = $dotActive ? $dotActive['MaDot'] : 'DOT2026_01';
$dirs = $dotModel->getAllResearchDirections();
$firstHNC = $dirs[0]['MaHuongNC'] ?? 'HNC_AI';
$secondHNC = $dirs[1]['MaHuongNC'] ?? $firstHNC;

$stmtTopic = $db->prepare("INSERT INTO DeTai (MaDeTai, TenDeTai, MaDot, MaHuongNC, MaGVHD, KinhPhiDuKien, MucTieu, SanPhamDuKien, TrangThai, NgayDangKy)
    VALUES ('DT_TEST_999', 'De Tai Test Delete & Edit', :madot, :hnc, 'GV001', 5000000, 'Muc tieu ban dau', 'San pham ban dau', 'CHO_GVHD_DUYET', NOW())");
$stmtTopic->execute(['madot' => $maDot, 'hnc' => $firstHNC]);
assertTest($deTaiModel->findById('DT_TEST_999') !== null, "Created test topic DT_TEST_999");

// Test quick edit
$editTopic = $deTaiModel->updateTopicBasic('DT_TEST_999', [
    'TenDeTai'      => 'De Tai Test Delete & Edit (Da Sua)',
    'MaHuongNC'     => $secondHNC,
    'KinhPhiDuKien' => 7500000,
    'MucTieu'       => 'Muc tieu da cap nhat',
    'SanPhamDuKien' => 'San pham moi'
]);
assertTest($editTopic, "updateTopicBasic modifies title, direction, and budget");

$checkTopic = $deTaiModel->findById('DT_TEST_999');
assertTest(
    $checkTopic['TenDeTai'] === 'De Tai Test Delete & Edit (Da Sua)' && (float)$checkTopic['KinhPhiDuKien'] === 7500000.0,
    "Updated topic values reflected in database"
);

// Test delete topic
$delTopic = $deTaiModel->deleteTopic('DT_TEST_999');
assertTest($delTopic && $deTaiModel->findById('DT_TEST_999') === null, "deleteTopic successfully removes topic and associated records");

// Test financial overview
$finStats = $deTaiModel->getFinancialOverview();
assertTest(
    isset($finStats['TongKinhPhi'], $finStats['KinhPhiTrungBinh']) && $finStats['TongKinhPhi'] > 0,
    "getFinancialOverview computes total and average funding (Total: " . number_format($finStats['TongKinhPhi']) . " VND)"
);


echo "\n=== 5. TESTING HTTP ENDPOINTS & VIEWS RENDERING ===\n";
$baseUrl = 'http://localhost/web1/HTQL_NCKH_SV_TBU/index.php';

function httpGet(string $url, ?string $cookieFile = null): array {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    if ($cookieFile) {
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
    }
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
    return $httpCode === 200 && strpos($body, 'Đăng xuất') !== false;
}

// 5.1 Public endpoints
$resLogin = httpGet($baseUrl . '?action=login');
assertTest($resLogin['code'] === 200 && strpos($resLogin['body'], 'Đăng nhập') !== false, "GET action=login returns 200 OK");

$resRegister = httpGet($baseUrl . '?action=register');
assertTest(
    $resRegister['code'] === 200 && strpos($resRegister['body'], 'Đăng Ký Tài Khoản Mới') !== false && strpos($resRegister['body'], 'role-sinhvien') !== false,
    "GET action=register returns 200 OK with dynamic SV/GV role form"
);

// 5.2 Admin / CanBoQL Session endpoints
$adminCookie = __DIR__ . '/cookie_admin_full.txt';
if (file_exists($adminCookie)) unlink($adminCookie);
$loginAdmin = httpLogin('CB001', '123456', $adminCookie);
assertTest($loginAdmin, "Admin/Manager CB001 logged in via HTTP session");

$resCBDashboard = httpGet($baseUrl . '?action=dashboard', $adminCookie);
assertTest(
    $resCBDashboard['code'] === 200 && strpos($resCBDashboard['body'], 'Chu Trình Giám Sát') !== false && strpos($resCBDashboard['body'], 'Quản lý Tài khoản') !== false,
    "Manager Dashboard renders 200 OK with rich metrics & pipeline stage tracker"
);

$resUserMgmt = httpGet($baseUrl . '?action=cb-quanly-nguoidung', $adminCookie);
assertTest(
    $resUserMgmt['code'] === 200 && strpos($resUserMgmt['body'], 'Quản Lý Tài Khoản Người Dùng') !== false && strpos($resUserMgmt['body'], 'modal-add-user') !== false,
    "User Management view renders 200 OK with add/edit/lock/delete UI"
);

$resDotMgmt = httpGet($baseUrl . '?action=cb-quanly-dot', $adminCookie);
assertTest(
    $resDotMgmt['code'] === 200 && strpos($resDotMgmt['body'], 'modal-edit-dot') !== false && strpos($resDotMgmt['body'], 'modal-edit-direction') !== false,
    "Round & Forms Management view renders 200 OK with edit modals"
);

$resTopicReview = httpGet($baseUrl . '?action=cb-thamdinh-detai', $adminCookie);
assertTest(
    $resTopicReview['code'] === 200 && strpos($resTopicReview['body'], 'modal-edit-topic') !== false,
    "Topic Review view renders 200 OK with quick edit modal and delete button"
);

$resProfile = httpGet($baseUrl . '?action=profile', $adminCookie);
assertTest(
    $resProfile['code'] === 200 && strpos($resProfile['body'], 'Hồ Sơ Cá Nhân') !== false && strpos($resProfile['body'], 'form-change-password') !== false,
    "User Profile view renders 200 OK with password change and role details"
);

// 5.3 Student Session endpoints
$svCookie = __DIR__ . '/cookie_sv_full.txt';
if (file_exists($svCookie)) unlink($svCookie);
$loginSV = httpLogin('SV001', '123456', $svCookie);
assertTest($loginSV, "Student SV001 logged in via HTTP session");

$resSVDashboard = httpGet($baseUrl . '?action=dashboard', $svCookie);
assertTest(
    $resSVDashboard['code'] === 200 && strpos($resSVDashboard['body'], 'Lộ Trình Nghiên Cứu') !== false,
    "Student Dashboard renders 200 OK with 5-stage roadmap and action buttons"
);

// 5.4 Lecturer Session endpoints
$gvCookie = __DIR__ . '/cookie_gv_full.txt';
if (file_exists($gvCookie)) unlink($gvCookie);
$loginGV = httpLogin('GV001', '123456', $gvCookie);
assertTest($loginGV, "Lecturer GV001 logged in via HTTP session");

$resGVDashboard = httpGet($baseUrl . '?action=dashboard', $gvCookie);
assertTest(
    $resGVDashboard['code'] === 200 && strpos($resGVDashboard['body'], 'Nhiệm Vụ Giảng Viên') !== false,
    "Lecturer Dashboard renders 200 OK with shortcuts and topic list"
);

// Cleanup cookie files
if (file_exists($adminCookie)) unlink($adminCookie);
if (file_exists($svCookie)) unlink($svCookie);
if (file_exists($gvCookie)) unlink($gvCookie);

echo "\n============================================\n";
echo "SUMMARY: Passed: {$passed} | Failed: {$failed}\n";
echo "============================================\n";

if ($failed > 0) {
    exit(1);
} else {
    exit(0);
}

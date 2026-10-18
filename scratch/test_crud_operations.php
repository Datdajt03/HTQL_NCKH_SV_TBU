<?php
declare(strict_types=1);

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
use App\Models\DotNCKHModel;
use App\Models\DeTaiModel;
use App\Models\HoiDongModel;
use App\Models\KhenThuongModel;
use App\Models\UserModel;
use App\Models\BaoCaoTienDoModel;
use App\Models\BaoCaoTongKetModel;

$db = Database::getConnection();

$passed = 0;
$failed = 0;

function assertCondition(bool $cond, string $message) {
    global $passed, $failed;
    if ($cond) {
        echo "  [PASS] {$message}\n";
        $passed++;
    } else {
        echo "  [FAIL] {$message}\n";
        $failed++;
    }
}

echo "=== STARTING COMPREHENSIVE CRUD VERIFICATION ===\n";

$dotModel = new DotNCKHModel();
$deTaiModel = new DeTaiModel();
$hoiDongModel = new HoiDongModel();
$khenThuongModel = new KhenThuongModel();
$userModel = new UserModel();
$tienDoModel = new BaoCaoTienDoModel();
$tongKetModel = new BaoCaoTongKetModel();

// -------------------------------------------------------------
// TEST 1: Batch (DotNCKH) cascading delete with topics & councils
// -------------------------------------------------------------
echo "\n--- TEST 1: Batch (DotNCKH) cascading delete ---\n";
$testDot = 'DOT_TEST_CRUD_' . time();
$dotModel->create([
    'MaDot' => $testDot,
    'TenDot' => 'Đợt Test CRUD Cascade',
    'NamHoc' => '2026-2027',
    'NgayBatDau' => '2026-01-01',
    'HanDangKy' => '2026-02-01',
    'HanNopBaoCaoTD' => '2026-05-01',
    'HanNopNghiemThu' => '2026-08-01',
    'NgayKetThuc' => '2026-09-01',
    'TrangThai' => 'MO_DANG_KY'
]);
assertCondition($dotModel->findById($testDot) !== null, "Created test batch {$testDot}");

// Create a council in this batch
$testHd = 'HD_TEST_' . time();
$hoiDongModel->createCouncil([
    'MaHoiDong' => $testHd,
    'TenHoiDong' => 'Hội đồng Test Cascade',
    'MaDot' => $testDot,
    'LoaiHoiDong' => 'NGHIEM_THU',
    'NgayThanhLap' => date('Y-m-d'),
    'SoQuyetDinh' => 'QĐ-TEST-01',
    'TrangThai' => 'HOAT_DONG'
], [
    ['MaNguoiDung' => 'GV001', 'VaiTroHoiDong' => 'CHU_TICH'],
    ['MaNguoiDung' => 'GV002', 'VaiTroHoiDong' => 'THU_KY'],
    ['MaNguoiDung' => 'GV003', 'VaiTroHoiDong' => 'PHAN_BIEN_1'],
]);
assertCondition($hoiDongModel->findById($testHd) !== null, "Created test council {$testHd} in batch");

// Create topic in this batch
$testDt = 'DT_TEST_' . time();
$deTaiModel->register([
    'MaDeTai' => $testDt,
    'TenDeTai' => 'Đề tài Test Cascade Batch',
    'MaDot' => $testDot,
    'MaHuongNC' => 'HNC_AI',
    'MaGVHD' => 'GV004',
    'KinhPhiDuKien' => 5000000,
    'MucTieu' => 'Mục tiêu test',
    'SanPhamDuKien' => 'Sản phẩm test',
    'ChiTietDeXuat' => json_encode(['tinh_cap_thiet' => 'Test']),
    'ChiTietThuyetMinh' => json_encode(['tong_quan' => 'Test'])
], [
    ['MaSV' => 'SV001', 'VaiTro' => 'TRUONG_NHOM']
]);
assertCondition($deTaiModel->findById($testDt) !== null, "Created topic {$testDt} in batch");

// Assign topic to council
$hoiDongModel->assignTopic([
    'MaHoiDong' => $testHd,
    'MaDeTai' => $testDt,
    'ThoiGianBaoCao' => date('Y-m-d H:i:s'),
    'DiaDiem' => 'Phòng A1-101'
]);
$assign = $hoiDongModel->getAssignmentByTopic($testDt);
assertCondition($assign !== null, "Assigned topic {$testDt} to council {$testHd}");

// Add a score sheet
$hoiDongModel->saveScoreSheet([
    'MaPhanCong' => $assign['MaPhanCong'],
    'MaGiamKhao' => 'GV001',
    'DiemTongQuan' => 18,
    'DiemPhuongPhap' => 27,
    'DiemSanPham' => 28,
    'DiemBaoCao' => 19,
    'NhanXetChiTiet' => 'Rất tốt'
]);
$sheets = $hoiDongModel->getScoreSheets((int)$assign['MaPhanCong']);
assertCondition(count($sheets) === 1, "Added scoresheet for assignment");

// Add notification in batch
$dotModel->addNotification([
    'MaDot' => $testDot,
    'TieuDe' => 'Thông báo test batch',
    'Loai' => 'THONG_BAO',
    'NoiDung' => 'Nội dung test'
]);

// Now delete the batch - should delete councils, topics, scoresheets, assignments, notices
$delBatchRes = $dotModel->delete($testDot);
assertCondition($delBatchRes === true, "dotModel->delete executed successfully");
assertCondition($dotModel->findById($testDot) === null, "Batch {$testDot} was deleted");
assertCondition($hoiDongModel->findById($testHd) === null, "Council {$testHd} was cascade-deleted");
assertCondition($deTaiModel->findById($testDt) === null, "Topic {$testDt} was cascade-deleted");
assertCondition($hoiDongModel->getAssignmentByTopic($testDt) === null, "Assignment was deleted");

// -------------------------------------------------------------
// TEST 2: Research Direction (HuongNghienCuu) deletion & reassignment
// -------------------------------------------------------------
echo "\n--- TEST 2: Research Direction (HuongNghienCuu) deletion ---\n";
$testHnc = 'HNC_TEST_' . time();
$dotModel->createResearchDirection($testHnc, 'Hướng Nghiên Cứu Test CRUD', 'Mô tả test');
assertCondition($db->query("SELECT * FROM HuongNghienCuu WHERE MaHuongNC = '$testHnc'")->fetch() !== false, "Created direction {$testHnc}");

// Create a topic referencing this direction
$testDtHnc = 'DT_HNC_' . time();
$activeDot = $dotModel->getActive()[0]['MaDot'] ?? 'DOT_2026_01';
$deTaiModel->register([
    'MaDeTai' => $testDtHnc,
    'TenDeTai' => 'Đề tài gắn HNC Test',
    'MaDot' => $activeDot,
    'MaHuongNC' => $testHnc,
    'MaGVHD' => 'GV001',
    'KinhPhiDuKien' => 3000000,
    'MucTieu' => 'Mục tiêu',
    'SanPhamDuKien' => 'Sản phẩm',
    'ChiTietDeXuat' => json_encode(['tinh_cap_thiet' => 'Test']),
    'ChiTietThuyetMinh' => json_encode(['tong_quan' => 'Test'])
], [
    ['MaSV' => 'SV001', 'VaiTro' => 'TRUONG_NHOM']
]);
assertCondition($deTaiModel->findById($testDtHnc) !== null, "Created topic referencing {$testHnc}");

// Delete direction -> topic should be reassigned to HNC_KHAC, direction deleted
$delHncRes = $dotModel->deleteResearchDirection($testHnc);
assertCondition($delHncRes === true, "dotModel->deleteResearchDirection executed successfully");
assertCondition($db->query("SELECT * FROM HuongNghienCuu WHERE MaHuongNC = '$testHnc'")->fetch() === false, "Direction {$testHnc} was deleted");
$reassignedTopic = $deTaiModel->findById($testDtHnc);
assertCondition($reassignedTopic !== null && $reassignedTopic['MaHuongNC'] === 'HNC_KHAC', "Topic {$testDtHnc} safely reassigned to HNC_KHAC");

// Clean up test topic
$deTaiModel->deleteTopic($testDtHnc);

// -------------------------------------------------------------
// TEST 3: Council CRUD: updateCouncil, unassignTopic, deleteScoreSheet, deleteCouncil
// -------------------------------------------------------------
echo "\n--- TEST 3: Council CRUD operations ---\n";
$testHd2 = 'HD_CRUD_' . time();
$hoiDongModel->createCouncil([
    'MaHoiDong' => $testHd2,
    'TenHoiDong' => 'Hội đồng Test CRUD',
    'MaDot' => $activeDot,
    'LoaiHoiDong' => 'NGHIEM_THU',
    'NgayThanhLap' => date('Y-m-d'),
    'SoQuyetDinh' => 'QĐ-01',
    'TrangThai' => 'HOAT_DONG'
], [
    ['MaNguoiDung' => 'GV001', 'VaiTroHoiDong' => 'CHU_TICH'],
    ['MaNguoiDung' => 'GV002', 'VaiTroHoiDong' => 'THU_KY'],
    ['MaNguoiDung' => 'GV003', 'VaiTroHoiDong' => 'PHAN_BIEN_1'],
]);

// Update council
$updateHdRes = $hoiDongModel->updateCouncil($testHd2, [
    'TenHoiDong' => 'Hội đồng Test CRUD (Đã Cập Nhật)',
    'LoaiHoiDong' => 'NGHIEM_THU',
    'NgayThanhLap' => date('Y-m-d'),
    'SoQuyetDinh' => 'QĐ-999-NEW',
    'TrangThai' => 'HOAT_DONG'
]);
assertCondition($updateHdRes === true, "hoiDongModel->updateCouncil returned true");
$updatedHd = $hoiDongModel->findById($testHd2);
assertCondition($updatedHd['TenHoiDong'] === 'Hội đồng Test CRUD (Đã Cập Nhật)' && $updatedHd['SoQuyetDinh'] === 'QĐ-999-NEW', "Council info updated in DB");

// Assign a topic
$testDtCouncil = 'DT_HD_' . time();
$deTaiModel->register([
    'MaDeTai' => $testDtCouncil,
    'TenDeTai' => 'Đề tài Phân công HĐ',
    'MaDot' => $activeDot,
    'MaHuongNC' => 'HNC_AI',
    'MaGVHD' => 'GV004',
    'KinhPhiDuKien' => 2000000,
    'MucTieu' => 'Test',
    'SanPhamDuKien' => 'Test',
    'ChiTietDeXuat' => json_encode([]),
    'ChiTietThuyetMinh' => json_encode([])
], [
    ['MaSV' => 'SV001', 'VaiTro' => 'TRUONG_NHOM']
]);
$hoiDongModel->assignTopic([
    'MaHoiDong' => $testHd2,
    'MaDeTai' => $testDtCouncil,
    'ThoiGianBaoCao' => date('Y-m-d H:i:s'),
    'DiaDiem' => 'Phòng 204'
]);
$assign2 = $hoiDongModel->getAssignmentByTopic($testDtCouncil);
assertCondition($assign2 !== null, "Assigned topic to {$testHd2}");

// Add a scoresheet
$hoiDongModel->saveScoreSheet([
    'MaPhanCong' => $assign2['MaPhanCong'],
    'MaGiamKhao' => 'GV001',
    'DiemTongQuan' => 19,
    'DiemPhuongPhap' => 28,
    'DiemSanPham' => 29,
    'DiemBaoCao' => 19,
    'NhanXetChiTiet' => 'Đạt chuẩn'
]);
$sheets2 = $hoiDongModel->getScoreSheets((int)$assign2['MaPhanCong']);
assertCondition(count($sheets2) === 1, "Added 1 scoresheet");

// Test deleteScoreSheet
$delSheetRes = $hoiDongModel->deleteScoreSheet((int)$sheets2[0]['MaPhieu']);
assertCondition($delSheetRes === true, "deleteScoreSheet returned true");
assertCondition(count($hoiDongModel->getScoreSheets((int)$assign2['MaPhanCong'])) === 0, "Scoresheet deleted from DB");

// Test unassignTopic
$unassignRes = $hoiDongModel->unassignTopic((int)$assign2['MaPhanCong']);
assertCondition($unassignRes === true, "unassignTopic returned true");
assertCondition($hoiDongModel->getAssignmentByTopic($testDtCouncil) === null, "Topic unassigned from council");

// Test deleteCouncil
$delHdRes = $hoiDongModel->deleteCouncil($testHd2);
assertCondition($delHdRes === true, "deleteCouncil returned true");
assertCondition($hoiDongModel->findById($testHd2) === null, "Council deleted from DB");

$deTaiModel->deleteTopic($testDtCouncil);

// -------------------------------------------------------------
// TEST 4: Award CRUD (KhenThuongModel)
// -------------------------------------------------------------
echo "\n--- TEST 4: Award CRUD (KhenThuongModel) ---\n";
$testDtAward = 'DT_AWD_' . time();
$deTaiModel->register([
    'MaDeTai' => $testDtAward,
    'TenDeTai' => 'Đề tài Test Khen Thưởng',
    'MaDot' => $activeDot,
    'MaHuongNC' => 'HNC_AI',
    'MaGVHD' => 'GV001',
    'KinhPhiDuKien' => 1000000,
    'MucTieu' => 'Test',
    'SanPhamDuKien' => 'Test',
    'ChiTietDeXuat' => json_encode([]),
    'ChiTietThuyetMinh' => json_encode([])
], [
    ['MaSV' => 'SV001', 'VaiTro' => 'TRUONG_NHOM']
]);

$khenThuongModel->saveAward([
    'MaDeTai' => $testDtAward,
    'DiemTongKet' => 9.5,
    'XepLoai' => 'XUAT_SAC',
    'DanhHieuKhenThuong' => 'GIAI_NHAT',
    'SoQuyetDinh' => 'QĐ-KT-001',
    'TienThuong' => 2000000,
    'GhiChu' => 'Khen thưởng thử nghiệm'
]);
$awd = $khenThuongModel->getByTopic($testDtAward);
assertCondition($awd !== null && $awd['XepLoai'] === 'XUAT_SAC', "Saved award for {$testDtAward}");

// Test deleteAward
$delAwdRes = $khenThuongModel->deleteAward($testDtAward);
assertCondition($delAwdRes === true, "deleteAward returned true");
assertCondition($khenThuongModel->getByTopic($testDtAward) === null, "Award deleted from DB");

$deTaiModel->deleteTopic($testDtAward);

// -------------------------------------------------------------
// TEST 5: Progress Report & Final Report CRUD
// -------------------------------------------------------------
echo "\n--- TEST 5: Progress Report & Final Report CRUD ---\n";
$testDtReport = 'DT_REP_' . time();
$deTaiModel->register([
    'MaDeTai' => $testDtReport,
    'TenDeTai' => 'Đề tài Test Báo Cáo',
    'MaDot' => $activeDot,
    'MaHuongNC' => 'HNC_AI',
    'MaGVHD' => 'GV001',
    'KinhPhiDuKien' => 1000000,
    'MucTieu' => 'Test',
    'SanPhamDuKien' => 'Test',
    'ChiTietDeXuat' => json_encode([]),
    'ChiTietThuyetMinh' => json_encode([])
], [
    ['MaSV' => 'SV001', 'VaiTro' => 'TRUONG_NHOM']
]);

// Progress Report
$tienDoModel->createReport([
    'MaDeTai' => $testDtReport,
    'DotBaoCao' => 'Báo cáo đợt 1',
    'PhanTramHoanThanh' => 40,
    'CongViecDaLam' => 'Thu thập tài liệu',
    'ChiTietTienDo' => json_encode([['tt' => 1, 'muc_do' => 'HOAN_THANH_MOT_NUA']]),
    'KhoKhanVuongMac' => 'Không có',
    'FileBaoCao' => null
]);
$reports = $tienDoModel->getByTopic($testDtReport);
assertCondition(count($reports) === 1, "Created progress report");
$delRepRes = $tienDoModel->deleteReport((int)$reports[0]['MaBaoCaoTD']);
assertCondition($delRepRes === true, "deleteReport returned true");
assertCondition(count($tienDoModel->getByTopic($testDtReport)) === 0, "Progress report deleted");

// Final Report
$reportTkId = $tongKetModel->submitFinalReport([
    'MaDeTai' => $testDtReport,
    'TomTatKetQua' => 'Tóm tắt kết quả',
    'FileBaoCaoTongKet' => 'test_report.pdf'
], [
    ['TenSanPham' => 'Phần mềm demo', 'LoaiSanPham' => 'PHAN_MEM', 'MoTaSanPham' => 'Mô tả']
]);
assertCondition($reportTkId > 0, "Submitted final report ID {$reportTkId}");
$delTkRes = $tongKetModel->deleteFinalReport($reportTkId);
assertCondition($delTkRes === true, "deleteFinalReport returned true");
assertCondition($tongKetModel->findByTopic($testDtReport) === null, "Final report and products deleted");

$deTaiModel->deleteTopic($testDtReport);

// -------------------------------------------------------------
// TEST 6: User Deletion (UserModel::deleteUser)
// -------------------------------------------------------------
echo "\n--- TEST 6: User Deletion with safe cascading ---\n";
$testUser = 'TEST_USER_' . time();
$userModel->registerUser([
    'MaNguoiDung' => $testUser,
    'MatKhauHash' => password_hash('123456', PASSWORD_BCRYPT),
    'HoTen' => 'Người Dùng Test CRUD',
    'Email' => 'test_' . time() . '@example.com',
    'SoDienThoai' => '0987654321',
    'VaiTro' => 'SINH_VIEN',
    'TrangThai' => 'HOAT_DONG'
], [
    'Lop' => 'K64 CNTT',
    'Khoa' => 'Khoa CNTT',
    'NganhHoc' => 'KTPM',
    'KhoaHoc' => '2023-2027'
]);
assertCondition($userModel->findById($testUser) !== null, "Created test student user {$testUser}");

// Create a topic where this test student is TRUONG_NHOM
$testDtUser = 'DT_U_' . time();
$deTaiModel->register([
    'MaDeTai' => $testDtUser,
    'TenDeTai' => 'Đề tài của test user',
    'MaDot' => $activeDot,
    'MaHuongNC' => 'HNC_AI',
    'MaGVHD' => 'GV001',
    'KinhPhiDuKien' => 1000000,
    'MucTieu' => 'Test',
    'SanPhamDuKien' => 'Test',
    'ChiTietDeXuat' => json_encode([]),
    'ChiTietThuyetMinh' => json_encode([])
], [
    ['MaSV' => $testUser, 'VaiTro' => 'TRUONG_NHOM']
]);
assertCondition($deTaiModel->findById($testDtUser) !== null, "Created topic with {$testUser} as leader");

// Delete user with cascading
$delUserRes = $userModel->deleteUser($testUser, true);
assertCondition($delUserRes === true, "userModel->deleteUser executed successfully");
assertCondition($userModel->findById($testUser) === null, "User {$testUser} deleted from NguoiDung");
assertCondition($deTaiModel->findById($testDtUser) === null, "User's topic was safely cleaned up");

// Verify ADMIN user cannot be deleted
$adminBlocked = false;
try {
    $userModel->deleteUser('ADMIN');
} catch (\Throwable $e) {
    $adminBlocked = true;
}
assertCondition($adminBlocked, "Protection check: ADMIN account deletion is strictly blocked");

echo "\n=== SUMMARY: Passed: {$passed}, Failed: {$failed} ===\n";
if ($failed > 0) {
    exit(1);
}

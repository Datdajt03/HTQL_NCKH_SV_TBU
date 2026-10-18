<?php
// Test HTTP sessions and HTML output
$baseUrl = 'http://localhost/web1/HTQL_NCKH_SV_TBU/index.php';

function makeRequest($url, $postData = null, $cookieFile = null) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    
    if ($cookieFile) {
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
    }
    
    if ($postData !== null) {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
    }

    $response = curl_exec($ch);
    $info = curl_getinfo($ch);
    curl_close($ch);
    return ['body' => $response, 'status' => $info['http_code'], 'url' => $info['url']];
}

echo "=== TEST HTTP ENDPOINTS ===" . PHP_EOL;

// 1. Đăng nhập Sinh viên SV001
$svCookies = [];
$svCookieFile = __DIR__ . '/cookie_sv.txt';
if (file_exists($svCookieFile)) unlink($svCookieFile);

$res = makeRequest($baseUrl . '?action=login', [
    'username' => 'SV001',
    'password' => '123456'
], $svCookieFile);

echo "1. Đăng nhập Sinh viên SV001: HTTP " . $res['status'] . " URL = " . $res['url'] . PHP_EOL;
$hasSwitcherInSV = strpos($res['body'], 'admin-switcher-bar') !== false || strpos($res['body'], 'switch-pill') !== false;
assert(!$hasSwitcherInSV, "FAIL: Giao diện Sinh viên thường tuyệt đối không được có role switcher!");
echo "✓ Sinh viên SV001: Switcher KHÔNG xuất hiện trên thanh navbar (Đúng yêu cầu!)" . PHP_EOL;

$hasDbSetupInSV = strpos($res['body'], 'title="Cấu hình lại Database"') !== false;
assert(!$hasDbSetupInSV, "FAIL: Giao diện Sinh viên không được có nút DB Setup!");
echo "✓ Sinh viên SV001: Nút DB Setup KHÔNG xuất hiện (Đúng yêu cầu!)" . PHP_EOL;

// 2. Kiểm tra màn hình Báo cáo tiến độ của Sinh viên
$resBC = makeRequest($baseUrl . '?action=sv-baocao-tiendo&id=DT_2026_001', null, $svCookieFile);
$hasSlider = strpos($resBC['body'], 'type="range"') !== false;
assert(!$hasSlider, "FAIL: Form nộp tiến độ không được còn slider kéo thả!");
echo "✓ Form Báo cáo tiến độ: Thanh kéo slider (input range) ĐÃ ĐƯỢC XÓA BỎ HOÀN TOÀN!" . PHP_EOL;

$hasSection12 = strpos($resBC['body'], '12. NỘI DUNG NGHIÊN CỨU VÀ TIẾN ĐỘ THỰC HIỆN') !== false;
assert($hasSection12, "FAIL: Form nộp tiến độ phải có Mục 12 Thuyết minh!");
echo "✓ Form Báo cáo tiến độ: Đã hiển thị bảng đánh giá chi tiết theo Mục 12 Thuyết minh!" . PHP_EOL;

$has4Statuses = strpos($resBC['body'], 'Chưa hoàn thành (0%)') !== false &&
                strpos($resBC['body'], 'Đã hoàn thành 1 nửa (50%)') !== false &&
                strpos($resBC['body'], 'Sắp hoàn thành (75%)') !== false &&
                strpos($resBC['body'], 'Hoàn thành (100%)') !== false;
assert($has4Statuses, "FAIL: Form phải có đủ 4 lựa chọn mức độ hoàn thành!");
echo "✓ Form Báo cáo tiến độ: Đầy đủ 4 mức độ lựa chọn: Chưa hoàn thành, Đã hoàn thành 1 nửa, Sắp hoàn thành, Hoàn thành!" . PHP_EOL;

// 3. Đăng nhập ADMIN
$adminCookieFile = __DIR__ . '/cookie_admin.txt';
if (file_exists($adminCookieFile)) unlink($adminCookieFile);

$resAdmin = makeRequest($baseUrl . '?action=login', [
    'username' => 'ADMIN',
    'password' => '123456'
], $adminCookieFile);

echo PHP_EOL . "2. Đăng nhập Quản trị viên (ADMIN): HTTP " . $resAdmin['status'] . PHP_EOL;
$hasSwitcherInAdmin = strpos($resAdmin['body'], 'admin-switcher-bar') !== false;
assert($hasSwitcherInAdmin, "FAIL: Giao diện ADMIN phải có admin-switcher-bar!");
echo "✓ ADMIN: Switcher xuất hiện trên navbar với đầy đủ các nút chuyển đổi (Đúng yêu cầu!)" . PHP_EOL;

// 4. Admin chuyển vai trò sang Sinh viên qua admin-switch-role
$resSwitch = makeRequest($baseUrl . '?action=admin-switch-role&role=SINH_VIEN', null, $adminCookieFile);
echo "3. Admin chuyển sang Sinh viên (SV001): Redirect URL = " . $resSwitch['url'] . PHP_EOL;
$hasSwitcherAfterSwitch = strpos($resSwitch['body'], 'admin-switcher-bar') !== false;
assert($hasSwitcherAfterSwitch, "FAIL: Khi Admin đóng vai Sinh viên, switcher vẫn phải hiển thị để Admin chuyển đổi tiếp!");
echo "✓ Admin đóng vai Sinh viên: Switcher VẪN HIỂN THỊ trên navbar để Admin chuyển tiếp sang GV hoặc CB!" . PHP_EOL;

// 5. Kiểm tra Cán bộ QL thẩm định tiến độ
$cbCookieFile = __DIR__ . '/cookie_cb.txt';
if (file_exists($cbCookieFile)) unlink($cbCookieFile);

$resCB = makeRequest($baseUrl . '?action=login', [
    'username' => 'CB001',
    'password' => '123456'
], $cbCookieFile);
$resCBTienDo = makeRequest($baseUrl . '?action=cb-quanly-tiendo', null, $cbCookieFile);
$hasReviewModal = strpos($resCBTienDo['body'], 'review-modal') !== false;
assert($hasReviewModal, "FAIL: Cán bộ QL phải có modal phê duyệt tiến độ!");
echo "✓ Cán bộ Quản lý: Có modal thẩm định & phê duyệt tiến độ cho từng đề tài Mục 12!" . PHP_EOL;

echo PHP_EOL . "=== TẤT CẢ CÁC KIỂM THỬ HTTP ĐÃ HOÀN TOÀN THÀNH CÔNG! ===" . PHP_EOL;

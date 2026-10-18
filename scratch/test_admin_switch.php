<?php
$baseUrl = 'http://localhost/web1/HTQL_NCKH_SV_TBU/index.php';
$cookie = __DIR__ . '/cookie_admin_switch.txt';
if (file_exists($cookie)) unlink($cookie);

// 1. Quick login as ADMIN
$ch = curl_init($baseUrl . '?action=quick-login&user_id=ADMIN');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$res = curl_exec($ch);
curl_close($ch);

// 2. Switch to SinhVien
$ch = curl_init($baseUrl . '?action=admin-switch-role&role=SINH_VIEN');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$res = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP CODE: " . $code . PHP_EOL;
echo "HAS ADMIN BADGE: " . (strpos($res, 'ADMIN') !== false ? 'YES' : 'NO') . PHP_EOL;
echo "HAS SV DASHBOARD: " . (strpos($res, 'Đăng Ký Đề Tài Mới') !== false || strpos($res, 'Bàn Làm Việc Sinh Viên') !== false ? 'YES' : 'NO') . PHP_EOL;

if (file_exists($cookie)) unlink($cookie);

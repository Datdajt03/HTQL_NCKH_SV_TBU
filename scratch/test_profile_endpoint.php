<?php
$ch = curl_init('http://localhost/web1/HTQL_NCKH_SV_TBU/index.php?action=profile');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEFILE, __DIR__ . '/cookie_sv.txt');
$res = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
echo "Profile HTTP Code: " . $code . PHP_EOL;
if ($code >= 400 || strpos($res, 'Fatal error') !== false || strpos($res, 'Notice:') !== false || strpos($res, 'Warning:') !== false) {
    echo "ERROR DETECTED:" . PHP_EOL;
    echo substr($res, 0, 1500) . PHP_EOL;
} else {
    echo "SUCCESS: Profile rendered with HTTP 200!" . PHP_EOL;
    $pos = strpos($res, 'Undefined');
    if ($pos !== false) {
        echo "Found 'Undefined' at position $pos: " . substr($res, max(0, $pos - 50), 150) . PHP_EOL;
    } else {
        echo "No 'Undefined' substring found." . PHP_EOL;
    }
    // Check if status badge and user info is in output
    if (strpos($res, 'Thông Tin Tài Khoản') !== false) {
        echo "Found: 'Thông Tin Tài Khoản' in output." . PHP_EOL;
    }
}

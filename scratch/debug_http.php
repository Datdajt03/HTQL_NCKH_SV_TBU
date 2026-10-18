<?php
$baseUrl = 'http://localhost/web1/HTQL_NCKH_SV_TBU/index.php';

function httpLogin(string $username, string $password, string $cookieFile): array {
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
    return ['code' => $httpCode, 'body' => (string)$body];
}

function httpGet(string $url, string $cookieFile): array {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $body = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return ['code' => $httpCode, 'body' => (string)$body];
}

$cookie = __DIR__ . '/debug_cookie.txt';
if (file_exists($cookie)) unlink($cookie);

httpLogin('CB001', '123456', $cookie);

$prof = httpGet($baseUrl . '?action=profile', $cookie);
file_put_contents(__DIR__ . '/prof_output.html', $prof['body']);
echo "PROF SAVED TO scratch/prof_output.html, length: " . strlen($prof['body']) . "\n";

<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    @session_start();
}

$routes = ['gioi-thieu', 'utb-web', 'web-viewer', 'dai-hoc-tay-bac', 'intro', 'about'];
$allOk = true;

foreach ($routes as $route) {
    $_GET['action'] = $route;
    $_SERVER['REQUEST_URI'] = '/web1/HTQL_NCKH_SV_TBU/index.php?action=' . $route;
    $_SERVER['SCRIPT_NAME'] = '/web1/HTQL_NCKH_SV_TBU/index.php';
    $_SERVER['HTTP_HOST'] = 'localhost';
    
    ob_start();
    try {
        // Run index.php buffer
        include dirname(__DIR__) . '/index.php';
        $out = ob_get_clean();
        $hasBrand = strpos($out, 'TRƯỜNG ĐẠI HỌC TÂY BẮC') !== false;
        $hasIframe = strpos($out, 'utb_web_iframe') !== false;
        if ($hasBrand && $hasIframe) {
            echo "[PASS] Route action={$route} rendered UTB portal with iframe successfully (" . strlen($out) . " bytes)\n";
        } else {
            echo "[FAIL] Route action={$route} missing brand or iframe\n";
            $allOk = false;
        }
    } catch (\Throwable $e) {
        ob_end_clean();
        echo "[FAIL] Route action={$route} exception: " . $e->getMessage() . "\n";
        $allOk = false;
    }
}

if ($allOk) {
    echo "ALL ROUTE ALIASES PASSED!\n";
    exit(0);
} else {
    echo "SOME ROUTE ALIASES FAILED!\n";
    exit(1);
}

<?php
/**
 * Trình cài đặt / Khởi tạo cơ sở dữ liệu tự động cho Hệ thống NCKH Sinh viên
 * Có thể chạy qua trình duyệt: http://localhost/web1/setup.php
 * Hoặc qua lệnh CLI: php setup.php
 */

$isCli = (php_sapi_name() === 'cli');

$dbHost = $_POST['db_host'] ?? '127.0.0.1';
$dbPort = $_POST['db_port'] ?? '3306';
$dbUser = $_POST['db_user'] ?? 'root';
$dbPass = $_POST['db_pass'] ?? '';
$dbName = 'ql_nckh_sv';

$messages = [];
$status = null;

if ($isCli || (!empty($_POST['action']) && $_POST['action'] === 'install')) {
    try {
        $dsn = "mysql:host={$dbHost};port={$dbPort};charset=utf8mb4";
        $pdo = new PDO($dsn, $dbUser, $dbPass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);

        $messages[] = ["type" => "info", "text" => "Kết nối máy chủ MySQL thành công!"];

        // 1. Tạo Database
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
        $pdo->exec("USE `{$dbName}`;");
        $messages[] = ["type" => "info", "text" => "Đã khởi tạo Database `{$dbName}` (utf8mb4)."];

        // 2. Chạy Schema
        $schemaPath = __DIR__ . '/database/schema.sql';
        if (!file_exists($schemaPath)) {
            throw new Exception("Không tìm thấy file schema tại: " . $schemaPath);
        }
        $schemaSql = file_get_contents($schemaPath);
        $pdo->exec($schemaSql);
        $messages[] = ["type" => "success", "text" => "Đã tạo thành công 18 bảng dữ liệu chuẩn 3NF!"];

        // 3. Chạy Sample Data
        $samplePath = __DIR__ . '/database/sample_data.sql';
        if (file_exists($samplePath)) {
            $sampleSql = file_get_contents($samplePath);
            $pdo->exec($sampleSql);
            $messages[] = ["type" => "success", "text" => "Đã nạp thành công bộ dữ liệu mẫu (12 tài khoản, 6 đề tài các giai đoạn, hội đồng nghiệm thu, điểm số và khen thưởng)!"];
        }

        // 4. Tạo thư mục upload
        $uploadDir = __DIR__ . '/public/uploads';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Tạo file config nếu cần
        $configContent = "<?php\n// Tự động sinh bởi setup.php\nreturn [\n    'db' => [\n        'host' => '{$dbHost}',\n        'port' => '{$dbPort}',\n        'database' => '{$dbName}',\n        'username' => '{$dbUser}',\n        'password' => '{$dbPass}',\n        'charset' => 'utf8mb4'\n    ]\n];\n";
        @file_put_contents(__DIR__ . '/app/Config/database_custom.php', $configContent);

        $status = "success";
        $messages[] = ["type" => "success", "text" => "CẤU HÌNH HỆ THỐNG THÀNH CÔNG! Bạn có thể bắt đầu sử dụng ngay."];

    } catch (Throwable $e) {
        $status = "error";
        $messages[] = ["type" => "danger", "text" => "Lỗi cài đặt: " . $e->getMessage()];
    }
}

if ($isCli) {
    echo "\n=== KẾT QUẢ CÀI ĐẶT DATABASE NCKH ===\n";
    foreach ($messages as $msg) {
        echo "[{$msg['type']}] {$msg['text']}\n";
    }
    exit($status === 'success' ? 0 : 1);
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cấu Hình Database - Hệ Thống NCKH Sinh Viên</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="public/css/style.css">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            background: radial-gradient(circle at 10% 20%, rgba(37, 99, 235, 0.08) 0%, transparent 40%),
                        radial-gradient(circle at 90% 80%, rgba(99, 102, 241, 0.08) 0%, transparent 40%),
                        #0f172a;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #f1f5f9;
        }
        .setup-card {
            max-width: 680px;
            width: 100%;
            background: rgba(30, 41, 59, 0.85);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
        .badge-step {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(59, 130, 246, 0.15);
            border: 1px solid rgba(59, 130, 246, 0.3);
            color: #60a5fa;
            padding: 6px 14px;
            border-radius: 9999px;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 16px;
        }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }
        .btn-install {
            width: 100%;
            padding: 14px;
            font-size: 16px;
            font-weight: 700;
            border-radius: 12px;
            border: none;
            background: linear-gradient(135deg, #2563eb, #4f46e5);
            color: white;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 10px 20px -5px rgba(37, 99, 235, 0.4);
            margin-top: 20px;
        }
        .btn-install:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 25px -5px rgba(37, 99, 235, 0.5);
        }
        .account-box {
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 12px;
            padding: 16px;
            margin-top: 24px;
        }
        .account-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-top: 10px;
        }
        .acc-item {
            background: rgba(255, 255, 255, 0.03);
            border-radius: 8px;
            padding: 10px;
            font-size: 13px;
        }
    </style>
</head>
<body>
    <div class="setup-card">
        <div class="badge-step">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
            Cấu Hình Hệ Thống XAMPP
        </div>
        <h1 style="font-size: 26px; font-weight: 800; margin-bottom: 8px; color: #fff;">Khởi Tạo Cơ Sở Dữ Liệu NCKH</h1>
        <p style="color: #94a3b8; font-size: 14px; margin-bottom: 24px;">Hệ thống Quản lý Hoạt động Nghiên cứu Khoa học Sinh viên (Chuẩn CSDL 3NF, 15 Use Cases, 3 Phân hệ)</p>

        <?php if (!empty($messages)): ?>
            <div style="margin-bottom: 24px;">
                <?php foreach ($messages as $m): ?>
                    <div style="padding: 12px 16px; border-radius: 10px; margin-bottom: 8px; font-size: 14px;
                        background: <?= $m['type'] === 'danger' ? 'rgba(239, 68, 68, 0.15)' : ($m['type'] === 'success' ? 'rgba(34, 197, 94, 0.15)' : 'rgba(59, 130, 246, 0.15)') ?>;
                        border: 1px solid <?= $m['type'] === 'danger' ? 'rgba(239, 68, 68, 0.3)' : ($m['type'] === 'success' ? 'rgba(34, 197, 94, 0.3)' : 'rgba(59, 130, 246, 0.3)') ?>;
                        color: <?= $m['type'] === 'danger' ? '#fca5a5' : ($m['type'] === 'success' ? '#86efac' : '#93c5fd') ?>;">
                        <?= htmlspecialchars($m['text']) ?>
                    </div>
                <?php endforeach; ?>

                <?php if ($status === 'success'): ?>
                    <a href="index.php" class="btn-install" style="display: block; text-align: center; text-decoration: none; margin-top: 16px;">
                        Truy Cập Hệ Thống Ngay &rarr;
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if ($status !== 'success'): ?>
        <form method="POST">
            <input type="hidden" name="action" value="install">
            <div class="form-row">
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #cbd5e1; margin-bottom: 6px;">MySQL Host</label>
                    <input type="text" name="db_host" value="<?= htmlspecialchars($dbHost) ?>" style="width: 100%; padding: 10px 14px; border-radius: 10px; background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(255, 255, 255, 0.1); color: #fff; box-sizing: border-box;" required>
                </div>
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #cbd5e1; margin-bottom: 6px;">Cổng (Port)</label>
                    <input type="text" name="db_port" value="<?= htmlspecialchars($dbPort) ?>" style="width: 100%; padding: 10px 14px; border-radius: 10px; background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(255, 255, 255, 0.1); color: #fff; box-sizing: border-box;" required>
                </div>
            </div>

            <div class="form-row">
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #cbd5e1; margin-bottom: 6px;">Tài khoản MySQL</label>
                    <input type="text" name="db_user" value="<?= htmlspecialchars($dbUser) ?>" style="width: 100%; padding: 10px 14px; border-radius: 10px; background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(255, 255, 255, 0.1); color: #fff; box-sizing: border-box;" required>
                </div>
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #cbd5e1; margin-bottom: 6px;">Mật khẩu MySQL (Mặc định XAMPP để trống)</label>
                    <input type="password" name="db_pass" value="<?= htmlspecialchars($dbPass) ?>" style="width: 100%; padding: 10px 14px; border-radius: 10px; background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(255, 255, 255, 0.1); color: #fff; box-sizing: border-box;" placeholder="Để trống nếu không có">
                </div>
            </div>

            <button type="submit" class="btn-install" style="display: flex; align-items: center; justify-content: center; gap: 8px;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><ellipse cx="12" cy="5" rx="9" ry="3"></ellipse><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path></svg>
                Thực Thi Khởi Tạo CSDL & Dữ Liệu Mẫu
            </button>
        </form>
        <?php endif; ?>

        <div class="account-box">
            <div style="font-size: 13px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em;">Tài khoản mẫu thử nghiệm (Mật khẩu: 123456)</div>
            <div class="account-grid">
                <div class="acc-item">
                    <span style="color: #38bdf8; font-weight: 700; display: block;">Sinh viên</span>
                    <code>SV001</code> / <code>SV002</code><br>
                    <small style="color: #94a3b8;">Nguyễn Huy Hoàng</small>
                </div>
                <div class="acc-item">
                    <span style="color: #a78bfa; font-weight: 700; display: block;">Giảng viên</span>
                    <code>GV001</code> / <code>GV002</code><br>
                    <small style="color: #94a3b8;">TS. Lê Hoàng Nam</small>
                </div>
                <div class="acc-item">
                    <span style="color: #34d399; font-weight: 700; display: block;">Cán bộ QL</span>
                    <code>CB001</code> / <code>ADMIN</code><br>
                    <small style="color: #94a3b8;">Trưởng phòng NCKH</small>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<?php
namespace App\Config;

use PDO;
use PDOException;

class Database {
    private static ?PDO $instance = null;

    public static function getConnection(): PDO {
        if (self::$instance === null) {
            $host = '127.0.0.1';
            $port = '3306';
            $database = 'ql_nckh_sv';
            $username = 'root';
            $password = '';
            $charset = 'utf8mb4';

            // Đọc cấu hình tùy chỉnh nếu có
            $customConfig = __DIR__ . '/database_custom.php';
            if (file_exists($customConfig)) {
                $config = require $customConfig;
                if (!empty($config['db'])) {
                    $host = $config['db']['host'] ?? $host;
                    $port = $config['db']['port'] ?? $port;
                    $database = $config['db']['database'] ?? $database;
                    $username = $config['db']['username'] ?? $username;
                    $password = $config['db']['password'] ?? $password;
                    $charset = $config['db']['charset'] ?? $charset;
                }
            }

            $dsn = "mysql:host={$host};port={$port};dbname={$database};charset={$charset}";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES {$charset} COLLATE utf8mb4_unicode_ci"
            ];

            try {
                self::$instance = new PDO($dsn, $username, $password, $options);
            } catch (PDOException $e) {
                // Nếu DB chưa tồn tại, thử kết nối không db để chuyển hướng cài đặt
                if (php_sapi_name() !== 'cli') {
                    header("Location: " . App::url('setup.php'));
                    exit;
                }
                throw new PDOException("Không thể kết nối đến cơ sở dữ liệu: " . $e->getMessage(), (int)$e->getCode());
            }
        }

        return self::$instance;
    }
}

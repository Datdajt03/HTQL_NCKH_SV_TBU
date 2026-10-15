<?php
namespace App\Helpers;

use App\Config\App;

class AuthHelper {
    public static function startSession(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function login(array $user): void {
        self::startSession();
        $_SESSION['user'] = [
            'id' => $user['MaNguoiDung'],
            'name' => $user['HoTen'],
            'email' => $user['Email'],
            'phone' => $user['SoDienThoai'] ?? '',
            'role' => $user['VaiTro'],
            'extra' => $user['extra'] ?? []
        ];
    }

    public static function logout(): void {
        self::startSession();
        unset($_SESSION['user']);
        session_destroy();
    }

    public static function user(): ?array {
        self::startSession();
        return $_SESSION['user'] ?? null;
    }

    public static function id(): ?string {
        $u = self::user();
        return $u['id'] ?? null;
    }

    public static function role(): ?string {
        $u = self::user();
        return $u['role'] ?? null;
    }

    public static function name(): string {
        $u = self::user();
        return $u['name'] ?? 'Khách';
    }

    public static function isLoggedIn(): bool {
        return self::user() !== null;
    }

    public static function requireLogin(): void {
        if (!self::isLoggedIn()) {
            self::setFlash('warning', 'Vui lòng đăng nhập để tiếp tục sử dụng hệ thống.');
            header("Location: " . App::url('index.php?action=login'));
            exit;
        }
    }

    public static function requireRole(array|string $roles): void {
        self::requireLogin();
        $allowed = is_array($roles) ? $roles : [$roles];
        $currentRole = self::role();
        
        // ADMIN có quyền truy cập tất cả
        if ($currentRole === App::ROLE_ADMIN) {
            return;
        }

        if (!in_array($currentRole, $allowed)) {
            header("Location: " . App::url('index.php?action=403'));
            exit;
        }
    }

    public static function setFlash(string $type, string $message): void {
        self::startSession();
        $_SESSION['flash'] = [
            'type' => $type, // success, danger, warning, info
            'message' => $message
        ];
    }

    public static function getFlash(): ?array {
        self::startSession();
        if (isset($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            unset($_SESSION['flash']);
            return $flash;
        }
        return null;
    }
}

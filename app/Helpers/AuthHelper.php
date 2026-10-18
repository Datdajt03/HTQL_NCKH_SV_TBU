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
        $isAdmin = ($user['VaiTro'] === App::ROLE_ADMIN || ($user['MaNguoiDung'] ?? '') === 'ADMIN');
        if ($isAdmin) {
            $_SESSION['admin_root'] = true;
        }
        $_SESSION['user'] = [
            'id' => $user['MaNguoiDung'],
            'MaNguoiDung' => $user['MaNguoiDung'],
            'name' => $user['HoTen'] ?? ($user['name'] ?? ''),
            'HoTen' => $user['HoTen'] ?? ($user['name'] ?? ''),
            'email' => $user['Email'] ?? ($user['email'] ?? ''),
            'Email' => $user['Email'] ?? ($user['email'] ?? ''),
            'phone' => $user['SoDienThoai'] ?? ($user['phone'] ?? ''),
            'SoDienThoai' => $user['SoDienThoai'] ?? ($user['phone'] ?? ''),
            'role' => $user['VaiTro'] ?? ($user['role'] ?? ''),
            'VaiTro' => $user['VaiTro'] ?? ($user['role'] ?? ''),
            'TrangThai' => $user['TrangThai'] ?? 'HOAT_DONG',
            'NgayTao' => $user['NgayTao'] ?? date('Y-m-d H:i:s'),
            'extra' => $user['extra'] ?? []
        ];
    }

    public static function updateSessionUser(array $user): void {
        self::startSession();
        if (isset($_SESSION['user'])) {
            foreach ($user as $k => $v) {
                $_SESSION['user'][$k] = $v;
            }
            if (isset($user['HoTen'])) {
                $_SESSION['user']['name'] = $user['HoTen'];
            }
            if (isset($user['Email'])) {
                $_SESSION['user']['email'] = $user['Email'];
            }
            if (isset($user['SoDienThoai'])) {
                $_SESSION['user']['phone'] = $user['SoDienThoai'];
            }
            if (isset($user['VaiTro'])) {
                $_SESSION['user']['role'] = $user['VaiTro'];
            }
        }
    }

    public static function logout(): void {
        self::startSession();
        unset($_SESSION['user']);
        unset($_SESSION['admin_root']);
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

    public static function isAdmin(): bool {
        self::startSession();
        return !empty($_SESSION['admin_root']) || self::role() === App::ROLE_ADMIN;
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
        
        // ADMIN hoặc phiên Admin chuyển vai trò có quyền truy cập tất cả
        if ($currentRole === App::ROLE_ADMIN || !empty($_SESSION['admin_root'])) {
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

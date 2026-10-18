<?php
namespace App\Config;

class App {
    const APP_NAME = "Hệ Thống Quản Lý NCKH Sinh Viên";
    const APP_SHORT_NAME = "QL-NCKH";
    const VERSION = "1.0.0";
    // Thông tin cơ sở đào tạo: TRƯỜNG ĐẠI HỌC TÂY BẮC (Sơn La)
    const UNIVERSITY_NAME = "TRƯỜNG ĐẠI HỌC TÂY BẮC";
    const UNIVERSITY_NAME_EN = "TAY BAC UNIVERSITY (UTB)";
    const UNIVERSITY_SHORT = "ĐH TÂY BẮC";
    const UNIVERSITY_ACRONYM = "UTB";
    const UNIVERSITY_GOVERNING_BODY = "BỘ GIÁO DỤC VÀ ĐÀO TẠO";
    const UNIVERSITY_LOCATION = "Sơn La";
    const UNIVERSITY_ADDRESS = "Đường Chu Văn An, Phường Quyết Tâm, Thành phố Sơn La, Tỉnh Sơn La";
    const UNIVERSITY_PHONE = "(+84) 212.3.751.700";
    const UNIVERSITY_FAX = "0212.3.751.701";
    const UNIVERSITY_EMAIL = "contact@utb.edu.vn";
    const UNIVERSITY_DOMAIN = "https://utb.edu.vn";
    const UNIVERSITY_INTRO_URL = "https://utb.edu.vn/gioi-thieu/";

    // Vai trò
    const ROLE_SINH_VIEN = "SINH_VIEN";
    const ROLE_GIANG_VIEN = "GIANG_VIEN";
    const ROLE_CAN_BO_QL = "CAN_BO_QL";
    const ROLE_ADMIN = "ADMIN";

    // Trạng thái đề tài
    const STATUS_CHO_GVHD_DUYET = "CHO_GVHD_DUYET";
    const STATUS_GVHD_TU_CHOI = "GVHD_TU_CHOI";
    const STATUS_CHO_THAM_DINH = "CHO_THAM_DINH";
    const STATUS_YEU_CAU_CHINH_SUA = "YEU_CAU_CHINH_SUA";
    const STATUS_DA_PHE_DUYET = "DA_PHE_DUYET";
    const STATUS_DANG_THUC_HIEN = "DANG_THUC_HIEN";
    const STATUS_CHO_NGHIEM_THU = "CHO_NGHIEM_THU";
    const STATUS_DA_NGHIEM_THU = "DA_NGHIEM_THU";
    const STATUS_HUY = "HUY";

    public static function getBaseUrl(): string {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        
        // Tính toán thư mục gốc của project (web1)
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        $dir = dirname($scriptName);
        $dir = str_replace('\\', '/', $dir);
        if ($dir === '/' || $dir === '.') {
            $dir = '';
        }
        
        return rtrim($protocol . $host . $dir, '/');
    }

    public static function url(string $path = ''): string {
        $base = self::getBaseUrl();
        $path = ltrim($path, '/');
        return $path === '' ? $base : $base . '/' . $path;
    }
}

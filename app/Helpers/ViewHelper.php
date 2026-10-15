<?php
namespace App\Helpers;

use App\Config\App;

class ViewHelper {
    public static function e(?string $string): string {
        return htmlspecialchars((string)$string, ENT_QUOTES, 'UTF-8');
    }

    public static function money($amount): string {
        return number_format((float)$amount, 0, ',', '.') . ' VNĐ';
    }

    public static function date(?string $date, string $format = 'd/m/Y'): string {
        if (!$date) return '-';
        $time = strtotime($date);
        return $time ? date($format, $time) : '-';
    }

    public static function datetime(?string $date, string $format = 'd/m/Y H:i'): string {
        if (!$date) return '-';
        $time = strtotime($date);
        return $time ? date($format, $time) : '-';
    }

    /**
     * Thư viện Icon SVG Vector sắc nét, tối giản, chuẩn UI/UX Enterprise
     */
    public static function icon(string $name, string $class = '', int $size = 18): string {
        $attrs = sprintf('width="%d" height="%d" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="svg-icon %s"', $size, $size, self::e($class));

        return match ($name) {
            'dashboard' => "<svg $attrs><rect width=\"7\" height=\"9\" x=\"3\" y=\"3\" rx=\"1\"/><rect width=\"7\" height=\"5\" x=\"14\" y=\"3\" rx=\"1\"/><rect width=\"7\" height=\"9\" x=\"14\" y=\"12\" rx=\"1\"/><rect width=\"7\" height=\"5\" x=\"3\" y=\"16\" rx=\"1\"/></svg>",
            'book'      => "<svg $attrs><path d=\"M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z\"/><path d=\"M6 6h10\"/><path d=\"M6 10h10\"/><path d=\"M6 14h6\"/></svg>",
            'book-open' => "<svg $attrs><path d=\"M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z\"/><path d=\"M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z\"/></svg>",
            'file-text' => "<svg $attrs><path d=\"M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z\"/><path d=\"M14 2v4a2 2 0 0 0 2 2h4\"/><path d=\"M10 9H8\"/><path d=\"M16 13H8\"/><path d=\"M16 17H8\"/></svg>",
            'file-plus' => "<svg $attrs><path d=\"M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z\"/><path d=\"M14 2v4a2 2 0 0 0 2 2h4\"/><line x1=\"12\" x2=\"12\" y1=\"11\" y2=\"17\"/><line x1=\"9\" x2=\"15\" y1=\"14\" y2=\"14\"/></svg>",
            'edit'      => "<svg $attrs><path d=\"M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z\"/><path d=\"m15 5 4 4\"/></svg>",
            'clock'     => "<svg $attrs><circle cx=\"12\" cy=\"12\" r=\"10\"/><polyline points=\"12 6 12 12 16 14\"/></svg>",
            'award'     => "<svg $attrs><circle cx=\"12\" cy=\"8\" r=\"6\"/><path d=\"m15.477 12.89 1.515 8.526a.5.5 0 0 1-.81.47l-3.58-2.687a1 1 0 0 0-1.197 0l-3.586 2.686a.5.5 0 0 1-.81-.469l1.514-8.526\"/></svg>",
            'inbox'     => "<svg $attrs><polyline points=\"22 12 16 12 14 15 10 15 8 12 2 12\"/><path d=\"M5.45 5.11 2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z\"/></svg>",
            'check'     => "<svg $attrs><polyline points=\"20 6 9 17 4 12\"/></svg>",
            'check-circle' => "<svg $attrs><path d=\"M22 11.08V12a10 10 0 1 1-5.93-9.14\"/><polyline points=\"22 4 12 14.01 9 11.01\"/></svg>",
            'x'         => "<svg $attrs><line x1=\"18\" y1=\"6\" x2=\"6\" y2=\"18\"/><line x1=\"6\" y1=\"6\" x2=\"18\" y2=\"18\"/></svg>",
            'x-circle'  => "<svg $attrs><circle cx=\"12\" cy=\"12\" r=\"10\"/><line x1=\"15\" x2=\"9\" y1=\"9\" y2=\"15\"/><line x1=\"9\" x2=\"15\" y1=\"9\" y2=\"15\"/></svg>",
            'alert-triangle' => "<svg $attrs><path d=\"m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z\"/><line x1=\"12\" x2=\"12\" y1=\"9\" y2=\"13\"/><line x1=\"12\" x2=\"12.01\" y1=\"17\" y2=\"17\"/></svg>",
            'alert-circle' => "<svg $attrs><circle cx=\"12\" cy=\"12\" r=\"10\"/><line x1=\"12\" y1=\"8\" x2=\"12\" y2=\"12\"/><line x1=\"12\" y1=\"16\" x2=\"12.01\" y2=\"16\"/></svg>",
            'users'     => "<svg $attrs><path d=\"M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2\"/><circle cx=\"9\" cy=\"7\" r=\"4\"/><path d=\"M22 21v-2a4 4 0 0 0-3-3.87\"/><path d=\"M16 3.13a4 4 0 0 1 0 7.75\"/></svg>",
            'user'      => "<svg $attrs><path d=\"M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2\"/><circle cx=\"12\" cy=\"7\" r=\"4\"/></svg>",
            'user-check'=> "<svg $attrs><path d=\"M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2\"/><circle cx=\"9\" cy=\"7\" r=\"4\"/><polyline points=\"16 11 18 13 22 9\"/></svg>",
            'calendar'  => "<svg $attrs><rect width=\"18\" height=\"18\" x=\"3\" y=\"4\" rx=\"2\" ry=\"2\"/><line x1=\"16\" x2=\"16\" y1=\"2\" y2=\"6\"/><line x1=\"8\" x2=\"8\" y1=\"2\" y2=\"6\"/><line x1=\"3\" x2=\"21\" y1=\"10\" y2=\"10\"/></svg>",
            'search'    => "<svg $attrs><circle cx=\"11\" cy=\"11\" r=\"8\"/><line x1=\"21\" x2=\"16.65\" y1=\"21\" y2=\"16.65\"/></svg>",
            'bar-chart' => "<svg $attrs><line x1=\"12\" x2=\"12\" y1=\"20\" y2=\"10\"/><line x1=\"18\" x2=\"18\" y1=\"20\" y2=\"4\"/><line x1=\"6\" x2=\"6\" y1=\"20\" y2=\"16\"/></svg>",
            'trending-up' => "<svg $attrs><polyline points=\"22 7 13.5 15.5 8.5 10.5 2 17\"/><polyline points=\"16 7 22 7 22 13\"/></svg>",
            'download'  => "<svg $attrs><path d=\"M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4\"/><polyline points=\"7 10 12 15 17 10\"/><line x1=\"12\" x2=\"12\" y1=\"15\" y2=\"3\"/></svg>",
            'upload'    => "<svg $attrs><path d=\"M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4\"/><polyline points=\"17 8 12 3 7 8\"/><line x1=\"12\" x2=\"12\" y1=\"3\" y2=\"15\"/></svg>",
            'printer'   => "<svg $attrs><polyline points=\"6 9 6 2 18 2 18 9\"/><path d=\"M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2\"/><rect width=\"12\" height=\"8\" x=\"6\" y=\"14\"/></svg>",
            'external-link' => "<svg $attrs><path d=\"M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6\"/><polyline points=\"15 3 21 3 21 9\"/><line x1=\"10\" x2=\"21\" y1=\"14\" y2=\"3\"/></svg>",
            'logout'    => "<svg $attrs><path d=\"M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4\"/><polyline points=\"16 17 21 12 16 7\"/><line x1=\"21\" x2=\"9\" y1=\"12\" y2=\"12\"/></svg>",
            'scale'     => "<svg $attrs><path d=\"m16 16 3-8 3 8c-.87.65-1.92 1-3 1s-2.13-.35-3-1Z\"/><path d=\"m2 16 3-8 3 8c-.87.65-1.92 1-3 1s-2.13-.35-3-1Z\"/><path d=\"M7 21h10\"/><path d=\"M12 3v18\"/><path d=\"M3 7h2c2 0 5-1 7-2 2 1 5 2 7 2h2\"/></svg>",
            'building'  => "<svg $attrs><rect width=\"16\" height=\"20\" x=\"4\" y=\"2\" rx=\"2\" ry=\"2\"/><path d=\"M9 22v-4h6v4\"/><path d=\"M8 6h.01\"/><path d=\"M16 6h.01\"/><path d=\"M12 6h.01\"/><path d=\"M12 10h.01\"/><path d=\"M12 14h.01\"/><path d=\"M16 10h.01\"/><path d=\"M16 14h.01\"/><path d=\"M8 10h.01\"/><path d=\"M8 14h.01\"/></svg>",
            'bell'      => "<svg $attrs><path d=\"M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9\"/><path d=\"M10.3 21a1.94 1.94 0 0 0 3.4 0\"/></svg>",
            'tag'       => "<svg $attrs><path d=\"M12 2H2v10l9.29 9.29c.94.94 2.48.94 3.42 0l6.58-6.58c.94-.94.94-2.48 0-3.42L12 2Z\"/><circle cx=\"7\" cy=\"7\" r=\".5\" fill=\"currentColor\"/></svg>",
            'eye'       => "<svg $attrs><path d=\"M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z\"/><circle cx=\"12\" cy=\"12\" r=\"3\"/></svg>",
            'folder'    => "<svg $attrs><path d=\"M4 20h16a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-7.93a2 2 0 0 1-1.66-.9l-.82-1.2A2 2 0 0 0 7.93 3H4a2 2 0 0 0-2 2v13c0 1.1.9 2 2 2Z\"/></svg>",
            'folder-open' => "<svg $attrs><path d=\"m6 14 1.45-2.9A2 2 0 0 1 9.24 10H20a2 2 0 0 1 1.94 2.5l-1.55 6a2 2 0 0 1-1.94 1.5H4a2 2 0 0 1-2-2V5c0-1.1.9-2 2-2h3.93a2 2 0 0 1 1.66.9l.82 1.2a2 2 0 0 0 1.66.9H18a2 2 0 0 1 2 2v2\"/></svg>",
            'settings'  => "<svg $attrs><path d=\"M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z\"/><circle cx=\"12\" cy=\"12\" r=\"3\"/></svg>",
            'plus'      => "<svg $attrs><line x1=\"12\" y1=\"5\" x2=\"12\" y2=\"19\"/><line x1=\"5\" y1=\"12\" x2=\"19\" y2=\"12\"/></svg>",
            'trash'     => "<svg $attrs><polyline points=\"3 6 5 6 21 6\"/><path d=\"M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2\"/></svg>",
            'filter'    => "<svg $attrs><polygon points=\"22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3\"/></svg>",
            'info'      => "<svg $attrs><circle cx=\"12\" cy=\"12\" r=\"10\"/><line x1=\"12\" y1=\"16\" x2=\"12\" y2=\"12\"/><line x1=\"12\" y1=\"8\" x2=\"12.01\" y2=\"8\"/></svg>",
            'shield'    => "<svg $attrs><path d=\"M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z\"/></svg>",
            'lock'      => "<svg $attrs><rect width=\"18\" height=\"11\" x=\"3\" y=\"11\" rx=\"2\" ry=\"2\"/><path d=\"M7 11V7a5 5 0 0 1 10 0v4\"/></svg>",
            'send'      => "<svg $attrs><line x1=\"22\" y1=\"2\" x2=\"11\" y2=\"13\"/><polygon points=\"22 2 15 22 11 13 2 9 22 2\"/></svg>",
            'paperclip' => "<svg $attrs><path d=\"m21.44 11.05-9.19 9.19a6 6 0 0 1-8.49-8.49l8.57-8.57A4 4 0 1 1 18 8.84l-8.59 8.57a2 2 0 0 1-2.83-2.83l8.49-8.48\"/></svg>",
            'star'      => "<svg $attrs><polygon points=\"12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2\"/></svg>",
            'arrow-right'=> "<svg $attrs><line x1=\"5\" y1=\"12\" x2=\"19\" y2=\"12\"/><polyline points=\"12 5 19 12 12 19\"/></svg>",
            'arrow-left'=> "<svg $attrs><line x1=\"19\" y1=\"12\" x2=\"5\" y2=\"12\"/><polyline points=\"12 19 5 12 12 5\"/></svg>",
            'refresh'   => "<svg $attrs><path d=\"M21.5 2v6h-6M2.5 22v-6h6M2 11.5a10 10 0 0 1 18.8-4.3M22 12.5a10 10 0 0 1-18.8 4.2\"/></svg>",
            'hash'      => "<svg $attrs><line x1=\"4\" y1=\"9\" x2=\"20\" y2=\"9\"/><line x1=\"4\" y1=\"15\" x2=\"20\" y2=\"15\"/><line x1=\"10\" y1=\"3\" x2=\"8\" y2=\"21\"/><line x1=\"16\" y1=\"3\" x2=\"14\" y2=\"21\"/></svg>",
            'target'    => "<svg $attrs><circle cx=\"12\" cy=\"12\" r=\"10\"/><circle cx=\"12\" cy=\"12\" r=\"6\"/><circle cx=\"12\" cy=\"12\" r=\"2\"/></svg>",
            'sun'       => "<svg $attrs><circle cx=\"12\" cy=\"12\" r=\"5\"/><line x1=\"12\" y1=\"1\" x2=\"12\" y2=\"3\"/><line x1=\"12\" y1=\"21\" x2=\"12\" y2=\"23\"/><line x1=\"4.22\" y1=\"4.22\" x2=\"5.64\" y2=\"5.64\"/><line x1=\"18.36\" y1=\"18.36\" x2=\"19.78\" y2=\"19.78\"/><line x1=\"1\" y1=\"12\" x2=\"3\" y2=\"12\"/><line x1=\"21\" y1=\"12\" x2=\"23\" y2=\"12\"/><line x1=\"4.22\" y1=\"19.78\" x2=\"5.64\" y2=\"18.36\"/><line x1=\"18.36\" y1=\"5.64\" x2=\"19.78\" y2=\"4.22\"/></svg>",
            'moon'      => "<svg $attrs><path d=\"M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z\"/></svg>",
            default     => "<svg $attrs><circle cx=\"12\" cy=\"12\" r=\"10\"/></svg>"
        };
    }

    /**
     * Badge trạng thái thanh lịch, tinh tế (không dùng emoji)
     */
    public static function statusBadge(string $status): string {
        $map = [
            'CHO_GVHD_DUYET'    => ['text' => 'Chờ GVHD duyệt', 'class' => 'badge-warning', 'dot' => 'dot-warning'],
            'GVHD_TU_CHOI'      => ['text' => 'GVHD từ chối', 'class' => 'badge-danger', 'dot' => 'dot-danger'],
            'CHO_THAM_DINH'     => ['text' => 'Chờ thẩm định', 'class' => 'badge-info', 'dot' => 'dot-info'],
            'YEU_CAU_CHINH_SUA' => ['text' => 'Yêu cầu sửa đổi', 'class' => 'badge-amber', 'dot' => 'dot-amber'],
            'DA_PHE_DUYET'      => ['text' => 'Đã phê duyệt', 'class' => 'badge-primary', 'dot' => 'dot-primary'],
            'DANG_THUC_HIEN'    => ['text' => 'Đang thực hiện', 'class' => 'badge-success', 'dot' => 'dot-success'],
            'CHO_NGHIEM_THU'    => ['text' => 'Chờ nghiệm thu', 'class' => 'badge-purple', 'dot' => 'dot-purple'],
            'DA_NGHIEM_THU'     => ['text' => 'Đã nghiệm thu', 'class' => 'badge-emerald', 'dot' => 'dot-emerald'],
            'HUY'               => ['text' => 'Đã hủy', 'class' => 'badge-secondary', 'dot' => 'dot-secondary'],
            // Trạng thái đợt
            'MO_DANG_KY'        => ['text' => 'Đang mở đăng ký', 'class' => 'badge-success', 'dot' => 'dot-success'],
            'KET_THUC'          => ['text' => 'Đã kết thúc', 'class' => 'badge-secondary', 'dot' => 'dot-secondary'],
            // Trạng thái báo cáo tiến độ
            'DAT_TIEN_DO'       => ['text' => 'Đạt tiến độ', 'class' => 'badge-success', 'dot' => 'dot-success'],
            'CHAM_TIEN_DO'      => ['text' => 'Chậm tiến độ', 'class' => 'badge-amber', 'dot' => 'dot-amber'],
            'CAN_NHAC_NHO'      => ['text' => 'Cần nhắc nhở', 'class' => 'badge-danger', 'dot' => 'dot-danger'],
            // Trạng thái đánh giá sơ bộ
            'CHO_DANH_GIA'      => ['text' => 'Chờ đánh giá sơ bộ', 'class' => 'badge-warning', 'dot' => 'dot-warning'],
            'DONG_Y_NGHIEM_THU' => ['text' => 'Đủ điều kiện nghiệm thu', 'class' => 'badge-success', 'dot' => 'dot-success'],
            'KHONG_DONG_Y'      => ['text' => 'Chưa đủ điều kiện', 'class' => 'badge-danger', 'dot' => 'dot-danger'],
            // Xếp loại
            'XUAT_SAC'          => ['text' => 'Xuất Sắc', 'class' => 'badge-gold', 'dot' => 'dot-gold'],
            'TOT'               => ['text' => 'Tốt', 'class' => 'badge-success', 'dot' => 'dot-success'],
            'KHA'               => ['text' => 'Khá', 'class' => 'badge-primary', 'dot' => 'dot-primary'],
            'DAT'               => ['text' => 'Đạt', 'class' => 'badge-info', 'dot' => 'dot-info'],
            'KHONG_DAT'         => ['text' => 'Không Đạt', 'class' => 'badge-danger', 'dot' => 'dot-danger'],
            // Danh hiệu
            'GIAI_NHAT'         => ['text' => 'Giải Nhất', 'class' => 'badge-gold', 'dot' => 'dot-gold'],
            'GIAI_NHI'          => ['text' => 'Giải Nhì', 'class' => 'badge-silver', 'dot' => 'dot-silver'],
            'GIAI_BA'           => ['text' => 'Giải Ba', 'class' => 'badge-bronze', 'dot' => 'dot-bronze'],
            'GIAI_KHUYEN_KHICH' => ['text' => 'Giải Khuyến Khích', 'class' => 'badge-cyan', 'dot' => 'dot-cyan'],
            'KHONG'             => ['text' => 'Không xét giải', 'class' => 'badge-secondary', 'dot' => 'dot-secondary']
        ];

        $info = $map[$status] ?? ['text' => $status, 'class' => 'badge-secondary', 'dot' => 'dot-secondary'];
        return sprintf(
            '<span class="badge %s"><span class="badge-dot %s"></span>%s</span>',
            $info['class'],
            $info['dot'],
            self::e($info['text'])
        );
    }

    public static function roleTitle(string $role): string {
        return match ($role) {
            App::ROLE_SINH_VIEN  => 'Sinh Viên Nghiên Cứu',
            App::ROLE_GIANG_VIEN => 'Giảng Viên Hướng Dẫn',
            App::ROLE_CAN_BO_QL  => 'Cán Bộ Quản Lý NCKH',
            App::ROLE_ADMIN      => 'Quản Trị Viên Hệ Thống',
            default => 'Người Dùng'
        };
    }
}

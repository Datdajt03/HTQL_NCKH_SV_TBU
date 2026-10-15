<?php
namespace App\Controllers;

use App\Helpers\AuthHelper;
use App\Models\DeTaiModel;
use App\Models\KhenThuongModel;

class ExportController {
    public function exportAwards(): void {
        AuthHelper::requireLogin();
        $khenThuongModel = new KhenThuongModel();
        $dotId = $_GET['dot'] ?? null;
        $awards = $khenThuongModel->getAllAwards($dotId);

        $filename = "Danh_sach_khen_thuong_NCKH_" . date('Ymd_His') . ".csv";

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');
        // UTF-8 BOM cho Excel mở tiếng Việt không bị lỗi font
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

        fputcsv($output, [
            'STT', 'Mã Đề Tài', 'Tên Đề Tài', 'Trưởng Nhóm', 'Lớp', 'GVHD',
            'Đợt NCKH', 'Điểm Tổng Kết', 'Xếp Loại', 'Danh Hiệu Khen Thưởng',
            'Số Quyết Định', 'Tiền Thưởng (VNĐ)', 'Ngày Quyết Định'
        ]);

        $stt = 1;
        foreach ($awards as $row) {
            fputcsv($output, [
                $stt++,
                $row['MaDeTai'],
                $row['TenDeTai'],
                $row['TenTruongNhom'] ?? '',
                $row['LopTruongNhom'] ?? '',
                $row['TenGVHD'] ?? '',
                $row['TenDot'] ?? '',
                $row['DiemTongKet'],
                $row['XepLoai'],
                $row['DanhHieuKhenThuong'],
                $row['SoQuyetDinh'] ?? '',
                number_format($row['TienThuong'], 0, ',', '.'),
                $row['NgayQuyetDinh'] ?? ''
            ]);
        }

        fclose($output);
        exit;
    }

    public function exportTopics(): void {
        AuthHelper::requireLogin();
        $deTaiModel = new DeTaiModel();
        $dotId = $_GET['dot'] ?? null;
        $status = $_GET['status'] ?? null;
        $topics = $deTaiModel->getAllTopics($status, $dotId);

        $filename = "Danh_sach_de_tai_NCKH_" . date('Ymd_His') . ".csv";

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

        fputcsv($output, [
            'STT', 'Mã Đề Tài', 'Tên Đề Tài', 'Đợt NCKH', 'Hướng Nghiên Cứu',
            'GVHD', 'Trưởng Nhóm', 'Kinh Phí (VNĐ)', 'Trạng Thái', 'Ngày Đăng Ký'
        ]);

        $stt = 1;
        foreach ($topics as $row) {
            fputcsv($output, [
                $stt++,
                $row['MaDeTai'],
                $row['TenDeTai'],
                $row['TenDot'],
                $row['TenHuongNC'],
                $row['TenGVHD'],
                $row['TenTruongNhom'] ?? '',
                number_format($row['KinhPhiDuKien'], 0, ',', '.'),
                $row['TrangThai'],
                $row['NgayDangKy']
            ]);
        }

        fclose($output);
        exit;
    }
}

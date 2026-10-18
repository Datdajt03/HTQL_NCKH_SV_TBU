<?php
namespace App\Models;

class KhenThuongModel extends BaseModel {
    public function getByTopic(string $maDeTai): ?array {
        return $this->fetchOne(
            "SELECT kq.*, dt.TenDeTai, dt.MaDot, d.TenDot, d.NamHoc
             FROM KetQuaKhenThuong kq
             JOIN DeTai dt ON kq.MaDeTai = dt.MaDeTai
             JOIN DotNCKH d ON dt.MaDot = d.MaDot
             WHERE kq.MaDeTai = :id",
            ['id' => $maDeTai]
        );
    }

    public function getAllAwards(?string $maDot = null): array {
        $sql = "SELECT kq.*, dt.TenDeTai, dt.MaDot, d.TenDot, d.NamHoc, hnc.TenHuongNC,
                       nd_gv.HoTen AS TenGVHD,
                       (SELECT nd.HoTen FROM ThanhVienDeTai tv JOIN NguoiDung nd ON tv.MaSV = nd.MaNguoiDung WHERE tv.MaDeTai = dt.MaDeTai AND tv.VaiTro = 'TRUONG_NHOM' LIMIT 1) AS TenTruongNhom,
                       (SELECT sv.Lop FROM ThanhVienDeTai tv JOIN SinhVien sv ON tv.MaSV = sv.MaSV WHERE tv.MaDeTai = dt.MaDeTai AND tv.VaiTro = 'TRUONG_NHOM' LIMIT 1) AS LopTruongNhom
                FROM KetQuaKhenThuong kq
                JOIN DeTai dt ON kq.MaDeTai = dt.MaDeTai
                JOIN DotNCKH d ON dt.MaDot = d.MaDot
                JOIN HuongNghienCuu hnc ON dt.MaHuongNC = hnc.MaHuongNC
                JOIN NguoiDung nd_gv ON dt.MaGVHD = nd_gv.MaNguoiDung";
        $params = [];
        if ($maDot) {
            $sql .= " WHERE dt.MaDot = :dot";
            $params['dot'] = $maDot;
        }
        $sql .= " ORDER BY kq.DiemTongKet DESC, kq.NgayQuyetDinh DESC";
        return $this->fetchAll($sql, $params);
    }

    // Tự động tính xếp loại theo quy chế trong ThietKeDatabase.md
    public static function calculateClassification(float $score): string {
        // Hỗ trợ cả thang điểm 10 hoặc thang 100
        $normalized = ($score > 10) ? ($score / 10.0) : $score;
        if ($normalized >= 9.0) return 'XUAT_SAC';
        if ($normalized >= 8.0) return 'TOT';
        if ($normalized >= 7.0) return 'KHA';
        if ($normalized >= 5.0) return 'DAT';
        return 'KHONG_DAT';
    }

    // UC 15: Lưu kết quả xếp loại và khen thưởng
    public function saveAward(array $data): bool {
        $score = (float)$data['DiemTongKet'];
        $xepLoai = !empty($data['XepLoai']) ? $data['XepLoai'] : self::calculateClassification($score);

        $sql = "INSERT INTO KetQuaKhenThuong (
                    MaDeTai, DiemTongKet, XepLoai, DanhHieuKhenThuong, SoQuyetDinh, TienThuong, GhiChu, NgayQuyetDinh
                ) VALUES (
                    :madetai, :diem, :xeploai, :danhhieu, :soqd, :tienthuong, :ghichu, :ngayqd
                )
                ON DUPLICATE KEY UPDATE
                    DiemTongKet = VALUES(DiemTongKet),
                    XepLoai = VALUES(XepLoai),
                    DanhHieuKhenThuong = VALUES(DanhHieuKhenThuong),
                    SoQuyetDinh = VALUES(SoQuyetDinh),
                    TienThuong = VALUES(TienThuong),
                    GhiChu = VALUES(GhiChu),
                    NgayQuyetDinh = VALUES(NgayQuyetDinh)";

        return $this->execute($sql, [
            'madetai'    => $data['MaDeTai'],
            'diem'       => $score,
            'xeploai'    => $xepLoai,
            'danhhieu'   => $data['DanhHieuKhenThuong'] ?? 'KHONG',
            'soqd'       => $data['SoQuyetDinh'] ?? null,
            'tienthuong' => (float)($data['TienThuong'] ?? 0),
            'ghichu'     => $data['GhiChu'] ?? null,
            'ngayqd'     => !empty($data['NgayQuyetDinh']) ? $data['NgayQuyetDinh'] : date('Y-m-d')
        ]);
    }

    // Thống kê tổng hợp cho Dashboard và Báo cáo
    public function getSystemStatistics(?string $maDot = null): array {
        $filterDot = $maDot ? "WHERE MaDot = " . $this->db->quote($maDot) : "";
        $filterDotDt = $maDot ? "WHERE dt.MaDot = " . $this->db->quote($maDot) : "";

        $totalTopics = $this->fetchOne("SELECT COUNT(*) AS total FROM DeTai dt {$filterDotDt}")['total'] ?? 0;
        $totalApproved = $this->fetchOne("SELECT COUNT(*) AS total FROM DeTai dt WHERE dt.TrangThai IN ('DANG_THUC_HIEN', 'CHO_NGHIEM_THU', 'DA_NGHIEM_THU') " . ($maDot ? "AND dt.MaDot = " . $this->db->quote($maDot) : ""))['total'] ?? 0;
        $totalFinished = $this->fetchOne("SELECT COUNT(*) AS total FROM DeTai dt WHERE dt.TrangThai = 'DA_NGHIEM_THU' " . ($maDot ? "AND dt.MaDot = " . $this->db->quote($maDot) : ""))['total'] ?? 0;
        $totalCouncils = $this->fetchOne("SELECT COUNT(*) AS total FROM HoiDong {$filterDot}")['total'] ?? 0;

        // Phân bố trạng thái
        $statusDistribution = $this->fetchAll(
            "SELECT TrangThai, COUNT(*) AS SoLuong FROM DeTai dt {$filterDotDt} GROUP BY TrangThai"
        );

        // Phân bố xếp loại khen thưởng
        $awardDistribution = $this->fetchAll(
            "SELECT kq.XepLoai, COUNT(*) AS SoLuong
             FROM KetQuaKhenThuong kq
             JOIN DeTai dt ON kq.MaDeTai = dt.MaDeTai
             {$filterDotDt}
             GROUP BY kq.XepLoai"
        );

        return [
            'totalTopics' => $totalTopics,
            'totalApproved' => $totalApproved,
            'totalFinished' => $totalFinished,
            'totalCouncils' => $totalCouncils,
            'statusDistribution' => $statusDistribution,
            'awardDistribution' => $awardDistribution
        ];
    }

    public function deleteAward(string $maDeTai): bool {
        return $this->execute("DELETE FROM KetQuaKhenThuong WHERE MaDeTai = :id", ['id' => $maDeTai]);
    }

    public function deleteAwardById(int $maKetQua): bool {
        return $this->execute("DELETE FROM KetQuaKhenThuong WHERE MaKetQua = :id", ['id' => $maKetQua]);
    }
}

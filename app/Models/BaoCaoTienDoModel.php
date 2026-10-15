<?php
namespace App\Models;

class BaoCaoTienDoModel extends BaseModel {
    public function getByTopic(string $maDeTai): array {
        return $this->fetchAll(
            "SELECT * FROM BaoCaoTienDo WHERE MaDeTai = :id ORDER BY NgayNop DESC",
            ['id' => $maDeTai]
        );
    }

    public function findById(int $id): ?array {
        return $this->fetchOne(
            "SELECT bc.*, dt.TenDeTai, dt.MaGVHD
             FROM BaoCaoTienDo bc
             JOIN DeTai dt ON bc.MaDeTai = dt.MaDeTai
             WHERE bc.MaBaoCaoTD = :id",
            ['id' => $id]
        );
    }

    // UC 4: Nộp báo cáo tiến độ
    public function createReport(array $data): bool {
        $sql = "INSERT INTO BaoCaoTienDo (
                    MaDeTai, DotBaoCao, PhanTramHoanThanh, CongViecDaLam, KhoKhanVuongMac, FileBaoCao
                ) VALUES (
                    :madetai, :dotbaocao, :phantram, :congviec, :khokhan, :filebaocao
                )";
        return $this->execute($sql, [
            'madetai'     => $data['MaDeTai'],
            'dotbaocao'   => $data['DotBaoCao'],
            'phantram'    => (int)$data['PhanTramHoanThanh'],
            'congviec'    => $data['CongViecDaLam'],
            'khokhan'     => $data['KhoKhanVuongMac'] ?? null,
            'filebaocao'  => $data['FileBaoCao']
        ]);
    }

    // UC 9: GVHD theo dõi và ghi nhận xét tiến độ
    public function teacherReview(int $reportId, string $danhGia, ?string $nhanXet): bool {
        return $this->execute(
            "UPDATE BaoCaoTienDo SET
                DanhGiaTienDo = :danhgia,
                NhanXetGVHD = :nhanxet,
                NgayDanhGia = CURRENT_TIMESTAMP
             WHERE MaBaoCaoTD = :id",
            [
                'danhgia' => $danhGia,
                'nhanxet' => $nhanXet,
                'id'      => $reportId
            ]
        );
    }

    // UC 13: Cán bộ QL quản lý tiến độ toàn trường
    public function getAllProgressOverview(?string $maDot = null): array {
        $sql = "SELECT dt.MaDeTai, dt.TenDeTai, dt.TrangThai, d.TenDot, nd_gv.HoTen AS TenGVHD,
                       (SELECT nd.HoTen FROM ThanhVienDeTai tv JOIN NguoiDung nd ON tv.MaSV = nd.MaNguoiDung WHERE tv.MaDeTai = dt.MaDeTai AND tv.VaiTro = 'TRUONG_NHOM' LIMIT 1) AS TenTruongNhom,
                       bc.MaBaoCaoTD, bc.DotBaoCao, bc.PhanTramHoanThanh, bc.DanhGiaTienDo, bc.NgayNop, bc.FileBaoCao
                FROM DeTai dt
                JOIN DotNCKH d ON dt.MaDot = d.MaDot
                JOIN NguoiDung nd_gv ON dt.MaGVHD = nd_gv.MaNguoiDung
                LEFT JOIN BaoCaoTienDo bc ON bc.MaBaoCaoTD = (
                    SELECT b2.MaBaoCaoTD FROM BaoCaoTienDo b2 WHERE b2.MaDeTai = dt.MaDeTai ORDER BY b2.NgayNop DESC LIMIT 1
                )
                WHERE dt.TrangThai IN ('DANG_THUC_HIEN', 'CHO_NGHIEM_THU', 'DA_NGHIEM_THU')";
        $params = [];
        if ($maDot) {
            $sql .= " AND dt.MaDot = :madot";
            $params['madot'] = $maDot;
        }
        $sql .= " ORDER BY dt.MaDeTai ASC";
        return $this->fetchAll($sql, $params);
    }
}

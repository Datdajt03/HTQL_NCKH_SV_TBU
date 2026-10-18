<?php
namespace App\Models;

class DotNCKHModel extends BaseModel {
    public function getAll(): array {
        return $this->fetchAll(
            "SELECT d.*, cb.PhongBan, nd.HoTen AS TenCanBoTao,
                    (SELECT COUNT(*) FROM DeTai dt WHERE dt.MaDot = d.MaDot) AS TongSoDeTai
             FROM DotNCKH d
             LEFT JOIN CanBoQL cb ON d.MaCanBoTao = cb.MaCB
             LEFT JOIN NguoiDung nd ON cb.MaCB = nd.MaNguoiDung
             ORDER BY d.NgayBatDau DESC"
        );
    }

    public function getActive(): array {
        return $this->fetchAll(
            "SELECT * FROM DotNCKH WHERE TrangThai IN ('MO_DANG_KY', 'DANG_THUC_HIEN') ORDER BY NgayBatDau DESC"
        );
    }

    public function findById(string $maDot): ?array {
        return $this->fetchOne("SELECT * FROM DotNCKH WHERE MaDot = :id", ['id' => $maDot]);
    }

    public function create(array $data): bool {
        $sql = "INSERT INTO DotNCKH (MaDot, TenDot, NamHoc, NgayBatDau, HanDangKy, HanNopBaoCaoTD, HanNopNghiemThu, NgayKetThuc, TrangThai, MaCanBoTao)
                VALUES (:madot, :tendot, :namhoc, :batdau, :handk, :hantd, :hannt, :ketthuc, :trangthai, :canbo)";
        return $this->execute($sql, [
            'madot'      => $data['MaDot'],
            'tendot'     => $data['TenDot'],
            'namhoc'     => $data['NamHoc'],
            'batdau'     => $data['NgayBatDau'],
            'handk'      => $data['HanDangKy'],
            'hantd'      => $data['HanNopBaoCaoTD'],
            'hannt'      => $data['HanNopNghiemThu'],
            'ketthuc'    => $data['NgayKetThuc'],
            'trangthai'  => $data['TrangThai'] ?? 'MO_DANG_KY',
            'canbo'      => $data['MaCanBoTao'] ?? null
        ]);
    }

    public function update(string $maDot, array $data): bool {
        $sql = "UPDATE DotNCKH SET
                TenDot = :tendot, NamHoc = :namhoc, NgayBatDau = :batdau, HanDangKy = :handk,
                HanNopBaoCaoTD = :hantd, HanNopNghiemThu = :hannt, NgayKetThuc = :ketthuc, TrangThai = :trangthai
                WHERE MaDot = :madot";
        return $this->execute($sql, [
            'tendot'     => $data['TenDot'],
            'namhoc'     => $data['NamHoc'],
            'batdau'     => $data['NgayBatDau'],
            'handk'      => $data['HanDangKy'],
            'hantd'      => $data['HanNopBaoCaoTD'],
            'hannt'      => $data['HanNopNghiemThu'],
            'ketthuc'    => $data['NgayKetThuc'],
            'trangthai'  => $data['TrangThai'],
            'madot'      => $maDot
        ]);
    }

    public function delete(string $maDot): bool {
        $count = $this->fetchOne("SELECT COUNT(*) AS total FROM DeTai WHERE MaDot = :id", ['id' => $maDot]);
        if (($count['total'] ?? 0) > 0) {
            throw new \Exception("Không thể xóa đợt NCKH đang có {$count['total']} đề tài tham gia. Vui lòng xử lý các đề tài trước.");
        }
        $this->execute("DELETE FROM ThongBaoBieuMau WHERE MaDot = :id", ['id' => $maDot]);
        return $this->execute("DELETE FROM DotNCKH WHERE MaDot = :id", ['id' => $maDot]);
    }

    // Hướng nghiên cứu
    public function getAllResearchDirections(): array {
        return $this->fetchAll(
            "SELECT h.*, (SELECT COUNT(*) FROM DeTai dt WHERE dt.MaHuongNC = h.MaHuongNC) AS SoDeTai
             FROM HuongNghienCuu h ORDER BY h.TenHuongNC ASC"
        );
    }

    public function createResearchDirection(string $ma, string $ten, ?string $mota): bool {
        return $this->execute(
            "INSERT INTO HuongNghienCuu (MaHuongNC, TenHuongNC, MoTa) VALUES (:ma, :ten, :mota)",
            ['ma' => $ma, 'ten' => $ten, 'mota' => $mota]
        );
    }

    public function updateResearchDirection(string $ma, string $ten, ?string $mota): bool {
        return $this->execute(
            "UPDATE HuongNghienCuu SET TenHuongNC = :ten, MoTa = :mota WHERE MaHuongNC = :ma",
            ['ten' => $ten, 'mota' => $mota, 'ma' => $ma]
        );
    }

    public function deleteResearchDirection(string $ma): bool {
        $count = $this->fetchOne("SELECT COUNT(*) AS total FROM DeTai WHERE MaHuongNC = :id", ['id' => $ma]);
        if (($count['total'] ?? 0) > 0) {
            throw new \Exception("Không thể xóa hướng nghiên cứu đang có {$count['total']} đề tài lựa chọn.");
        }
        return $this->execute("DELETE FROM HuongNghienCuu WHERE MaHuongNC = :id", ['id' => $ma]);
    }

    // Thông báo & Biểu mẫu
    public function getNotifications(?string $maDot = null): array {
        $sql = "SELECT tb.*, nd.HoTen AS TenNguoiDang, d.TenDot
                FROM ThongBaoBieuMau tb
                LEFT JOIN DotNCKH d ON tb.MaDot = d.MaDot
                LEFT JOIN CanBoQL cb ON tb.NguoiDang = cb.MaCB
                LEFT JOIN NguoiDung nd ON cb.MaCB = nd.MaNguoiDung";
        $params = [];
        if ($maDot) {
            $sql .= " WHERE tb.MaDot = :madot OR tb.MaDot IS NULL";
            $params['madot'] = $maDot;
        }
        $sql .= " ORDER BY tb.NgayDang DESC";
        return $this->fetchAll($sql, $params);
    }

    public function addNotification(array $data): bool {
        $sql = "INSERT INTO ThongBaoBieuMau (MaDot, TieuDe, Loai, NoiDung, FileDinhKem, NguoiDang)
                VALUES (:madot, :tieude, :loai, :noidung, :filedinhkem, :nguoidang)";
        return $this->execute($sql, [
            'madot'       => !empty($data['MaDot']) ? $data['MaDot'] : null,
            'tieude'      => $data['TieuDe'],
            'loai'        => $data['Loai'],
            'noidung'     => $data['NoiDung'] ?? null,
            'filedinhkem' => $data['FileDinhKem'] ?? null,
            'nguoidang'   => $data['NguoiDang'] ?? null
        ]);
    }

    public function updateNotification(int $id, array $data): bool {
        $sql = "UPDATE ThongBaoBieuMau SET 
                MaDot = :madot, 
                TieuDe = :tieude, 
                Loai = :loai, 
                NoiDung = :noidung";
        $params = [
            'madot'   => !empty($data['MaDot']) ? $data['MaDot'] : null,
            'tieude'  => $data['TieuDe'],
            'loai'    => $data['Loai'],
            'noidung' => $data['NoiDung'] ?? null,
            'id'      => $id
        ];
        if (!empty($data['FileDinhKem'])) {
            $sql .= ", FileDinhKem = :file";
            $params['file'] = $data['FileDinhKem'];
        }
        $sql .= " WHERE MaThongBao = :id";
        return $this->execute($sql, $params);
    }

    public function deleteNotification(int $id): bool {
        return $this->execute("DELETE FROM ThongBaoBieuMau WHERE MaThongBao = :id", ['id' => $id]);
    }
}

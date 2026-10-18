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
        $this->db->beginTransaction();
        try {
            // 1. Xóa thông báo biểu mẫu gắn với đợt
            $this->execute("DELETE FROM ThongBaoBieuMau WHERE MaDot = :id", ['id' => $maDot]);

            // 2. Xóa các phân công hội đồng liên quan đến các đề tài thuộc đợt
            $topics = $this->fetchAll("SELECT MaDeTai FROM DeTai WHERE MaDot = :id", ['id' => $maDot]);
            foreach ($topics as $t) {
                $this->execute("DELETE FROM PhanCongHoiDong WHERE MaDeTai = :id", ['id' => $t['MaDeTai']]);
            }

            // 3. Xóa các đề tài thuộc đợt (sẽ cascade sang các bảng con)
            $this->execute("DELETE FROM DeTai WHERE MaDot = :id", ['id' => $maDot]);

            // 4. Xóa hội đồng nghiệm thu thuộc đợt (sẽ cascade sang thành viên & phân công)
            $this->execute("DELETE FROM HoiDong WHERE MaDot = :id", ['id' => $maDot]);

            // 5. Xóa chính đợt NCKH
            $res = $this->execute("DELETE FROM DotNCKH WHERE MaDot = :id", ['id' => $maDot]);

            $this->db->commit();
            return $res;
        } catch (\Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
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
        $this->db->beginTransaction();
        try {
            $count = $this->fetchOne("SELECT COUNT(*) AS total FROM DeTai WHERE MaHuongNC = :id", ['id' => $ma]);
            if (($count['total'] ?? 0) > 0) {
                $fallback = 'HNC_KHAC';
                if ($ma === $fallback) {
                    $other = $this->fetchOne("SELECT MaHuongNC FROM HuongNghienCuu WHERE MaHuongNC != :ma LIMIT 1", ['ma' => $ma]);
                    $fallback = $other ? $other['MaHuongNC'] : null;
                } else {
                    $exists = $this->fetchOne("SELECT MaHuongNC FROM HuongNghienCuu WHERE MaHuongNC = :f", ['f' => $fallback]);
                    if (!$exists) {
                        $this->createResearchDirection('HNC_KHAC', 'Hướng nghiên cứu khác & Liên ngành', 'Tự động tạo để lưu trữ các đề tài khi xóa hướng nghiên cứu');
                    }
                }

                if ($fallback && $fallback !== $ma) {
                    $this->execute("UPDATE DeTai SET MaHuongNC = :fallback WHERE MaHuongNC = :ma", [
                        'fallback' => $fallback,
                        'ma'       => $ma
                    ]);
                }
            }

            $res = $this->execute("DELETE FROM HuongNghienCuu WHERE MaHuongNC = :id", ['id' => $ma]);
            $this->db->commit();
            return $res;
        } catch (\Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
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

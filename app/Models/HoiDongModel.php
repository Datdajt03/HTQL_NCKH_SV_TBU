<?php
namespace App\Models;

use Exception;

class HoiDongModel extends BaseModel {
    public function getAllCouncils(?string $maDot = null): array {
        $sql = "SELECT hd.*, d.TenDot,
                       (SELECT COUNT(*) FROM ThanhVienHoiDong tv WHERE tv.MaHoiDong = hd.MaHoiDong) AS SoThanhVien,
                       (SELECT COUNT(*) FROM PhanCongHoiDong pc WHERE pc.MaHoiDong = hd.MaHoiDong) AS SoDeTai
                FROM HoiDong hd
                JOIN DotNCKH d ON hd.MaDot = d.MaDot";
        $params = [];
        if ($maDot) {
            $sql .= " WHERE hd.MaDot = :dot";
            $params['dot'] = $maDot;
        }
        $sql .= " ORDER BY hd.NgayThanhLap DESC";
        return $this->fetchAll($sql, $params);
    }

    public function findById(string $maHoiDong): ?array {
        return $this->fetchOne(
            "SELECT hd.*, d.TenDot FROM HoiDong hd JOIN DotNCKH d ON hd.MaDot = d.MaDot WHERE hd.MaHoiDong = :id",
            ['id' => $maHoiDong]
        );
    }

    public function getCouncilMembers(string $maHoiDong): array {
        return $this->fetchAll(
            "SELECT tv.*, nd.HoTen, nd.Email, nd.SoDienThoai, nd.VaiTro,
                    gv.HocHam, gv.HocVi, gv.DonViCongTac, cb.PhongBan, cb.ChucVu
             FROM ThanhVienHoiDong tv
             JOIN NguoiDung nd ON tv.MaNguoiDung = nd.MaNguoiDung
             LEFT JOIN GiangVien gv ON nd.MaNguoiDung = gv.MaGV
             LEFT JOIN CanBoQL cb ON nd.MaNguoiDung = cb.MaCB
             WHERE tv.MaHoiDong = :id
             ORDER BY FIELD(tv.VaiTroHoiDong, 'CHU_TICH', 'THU_KY', 'PHAN_BIEN_1', 'PHAN_BIEN_2', 'UY_VIEN')",
            ['id' => $maHoiDong]
        );
    }

    public function createCouncil(array $data, array $members): bool {
        $this->db->beginTransaction();
        try {
            $sql = "INSERT INTO HoiDong (MaHoiDong, TenHoiDong, MaDot, LoaiHoiDong, NgayThanhLap, SoQuyetDinh, TrangThai)
                    VALUES (:ma, :ten, :dot, :loai, :ngay, :soqd, :trangthai)";
            $this->execute($sql, [
                'ma'        => $data['MaHoiDong'],
                'ten'       => $data['TenHoiDong'],
                'dot'       => $data['MaDot'],
                'loai'      => $data['LoaiHoiDong'],
                'ngay'      => $data['NgayThanhLap'],
                'soqd'      => $data['SoQuyetDinh'] ?? null,
                'trangthai' => $data['TrangThai'] ?? 'HOAT_DONG'
            ]);

            $memberSql = "INSERT INTO ThanhVienHoiDong (MaHoiDong, MaNguoiDung, VaiTroHoiDong) VALUES (:mahd, :mand, :vaitro)";
            foreach ($members as $m) {
                if (!empty($m['MaNguoiDung'])) {
                    $this->execute($memberSql, [
                        'mahd'   => $data['MaHoiDong'],
                        'mand'   => $m['MaNguoiDung'],
                        'vaitro' => $m['VaiTroHoiDong']
                    ]);
                }
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    // Phân công đề tài vào hội đồng
    public function assignTopic(array $data): bool {
        // Kiểm tra xung đột lợi ích: Thành viên hội đồng không được là GVHD của đề tài
        $topic = $this->fetchOne("SELECT MaGVHD FROM DeTai WHERE MaDeTai = :id", ['id' => $data['MaDeTai']]);
        if ($topic) {
            $conflict = $this->fetchOne(
                "SELECT * FROM ThanhVienHoiDong WHERE MaHoiDong = :mahd AND MaNguoiDung = :magv",
                ['mahd' => $data['MaHoiDong'], 'magv' => $topic['MaGVHD']]
            );
            if ($conflict) {
                throw new Exception("Xung đột lợi ích! Giảng viên hướng dẫn đề tài này (" . $topic['MaGVHD'] . ") đang là thành viên của Hội đồng " . $data['MaHoiDong'] . ".");
            }
        }

        $sql = "INSERT INTO PhanCongHoiDong (MaHoiDong, MaDeTai, ThoiGianBaoCao, DiaDiem)
                VALUES (:mahd, :madetai, :thoigian, :diadiem)
                ON DUPLICATE KEY UPDATE ThoiGianBaoCao = VALUES(ThoiGianBaoCao), DiaDiem = VALUES(DiaDiem), MaHoiDong = VALUES(MaHoiDong)";
        return $this->execute($sql, [
            'mahd'     => $data['MaHoiDong'],
            'madetai'  => $data['MaDeTai'],
            'thoigian' => $data['ThoiGianBaoCao'],
            'diadiem'  => $data['DiaDiem']
        ]);
    }

    public function getAssignmentsByCouncil(string $maHoiDong): array {
        return $this->fetchAll(
            "SELECT pc.*, dt.TenDeTai, dt.TrangThai AS TrangThaiDeTai,
                    nd_gv.HoTen AS TenGVHD,
                    (SELECT COUNT(*) FROM PhieuDanhGia pd WHERE pd.MaPhanCong = pc.MaPhanCong) AS SoPhieuCham
             FROM PhanCongHoiDong pc
             JOIN DeTai dt ON pc.MaDeTai = dt.MaDeTai
             JOIN NguoiDung nd_gv ON dt.MaGVHD = nd_gv.MaNguoiDung
             WHERE pc.MaHoiDong = :id
             ORDER BY pc.ThoiGianBaoCao ASC",
            ['id' => $maHoiDong]
        );
    }

    public function getAssignmentByTopic(string $maDeTai): ?array {
        return $this->fetchOne(
            "SELECT pc.*, hd.TenHoiDong, hd.LoaiHoiDong, hd.SoQuyetDinh
             FROM PhanCongHoiDong pc
             JOIN HoiDong hd ON pc.MaHoiDong = hd.MaHoiDong
             WHERE pc.MaDeTai = :id",
            ['id' => $maDeTai]
        );
    }

    public function getAssignmentById(int $maPhanCong): ?array {
        return $this->fetchOne(
            "SELECT pc.*, dt.TenDeTai, dt.MaDot, hd.TenHoiDong, hd.MaHoiDong, dt.MaGVHD
             FROM PhanCongHoiDong pc
             JOIN DeTai dt ON pc.MaDeTai = dt.MaDeTai
             JOIN HoiDong hd ON pc.MaHoiDong = hd.MaHoiDong
             WHERE pc.MaPhanCong = :id",
            ['id' => $maPhanCong]
        );
    }

    // Nhập phiếu đánh giá của từng giám khảo
    public function saveScoreSheet(array $data): bool {
        $sql = "INSERT INTO PhieuDanhGia (
                    MaPhanCong, MaGiamKhao, DiemTongQuan, DiemPhuongPhap, DiemSanPham, DiemBaoCao, NhanXetChiTiet
                ) VALUES (
                    :mapc, :magk, :dtq, :dpp, :dsp, :dbc, :nhanxet
                )";
        return $this->execute($sql, [
            'mapc'    => (int)$data['MaPhanCong'],
            'magk'    => $data['MaGiamKhao'],
            'dtq'     => (float)$data['DiemTongQuan'],
            'dpp'     => (float)$data['DiemPhuongPhap'],
            'dsp'     => (float)$data['DiemSanPham'],
            'dbc'     => (float)$data['DiemBaoCao'],
            'nhanxet' => $data['NhanXetChiTiet'] ?? null
        ]);
    }

    public function getScoreSheets(int $maPhanCong): array {
        return $this->fetchAll(
            "SELECT p.*, nd.HoTen AS TenGiamKhao, tv.VaiTroHoiDong
             FROM PhieuDanhGia p
             JOIN NguoiDung nd ON p.MaGiamKhao = nd.MaNguoiDung
             JOIN PhanCongHoiDong pc ON p.MaPhanCong = pc.MaPhanCong
             LEFT JOIN ThanhVienHoiDong tv ON (pc.MaHoiDong = tv.MaHoiDong AND p.MaGiamKhao = tv.MaNguoiDung)
             WHERE p.MaPhanCong = :id
             ORDER BY p.NgayCham ASC",
            ['id' => $maPhanCong]
        );
    }

    // Cán bộ chốt kết quả và tính điểm trung bình hội đồng
    public function finalizeDefense(int $maPhanCong, float $diemTB, string $ketLuan, ?string $fileBienBan = null): bool {
        $this->db->beginTransaction();
        try {
            $assignment = $this->getAssignmentById($maPhanCong);
            if (!$assignment) {
                throw new Exception("Không tìm thấy thông tin phân công bảo vệ.");
            }

            $sql = "UPDATE PhanCongHoiDong SET
                        DiemTrungBinh = :diem,
                        KetLuan = :ketluan";
            $params = [
                'diem'    => $diemTB,
                'ketluan' => $ketLuan,
                'id'      => $maPhanCong
            ];
            if ($fileBienBan) {
                $sql .= ", FileBienBan = :file";
                $params['file'] = $fileBienBan;
            }
            $sql .= " WHERE MaPhanCong = :id";
            $this->execute($sql, $params);

            // Cập nhật trạng thái đề tài sang DA_NGHIEM_THU nếu đạt
            if ($ketLuan === 'DAT') {
                $this->execute(
                    "UPDATE DeTai SET TrangThai = 'DA_NGHIEM_THU' WHERE MaDeTai = :id",
                    ['id' => $assignment['MaDeTai']]
                );
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function updateCouncil(string $maHoiDong, array $data, ?array $members = null): bool {
        $this->db->beginTransaction();
        try {
            $sql = "UPDATE HoiDong SET
                        TenHoiDong = :ten,
                        LoaiHoiDong = :loai,
                        NgayThanhLap = :ngay,
                        SoQuyetDinh = :soqd,
                        TrangThai = :trangthai
                    WHERE MaHoiDong = :ma";
            $this->execute($sql, [
                'ten'       => $data['TenHoiDong'],
                'loai'      => $data['LoaiHoiDong'] ?? 'NGHIEM_THU',
                'ngay'      => $data['NgayThanhLap'] ?? date('Y-m-d'),
                'soqd'      => $data['SoQuyetDinh'] ?? null,
                'trangthai' => $data['TrangThai'] ?? 'HOAT_DONG',
                'ma'        => $maHoiDong
            ]);

            if ($members !== null && is_array($members) && count($members) > 0) {
                $this->execute("DELETE FROM ThanhVienHoiDong WHERE MaHoiDong = :id", ['id' => $maHoiDong]);
                $memberSql = "INSERT INTO ThanhVienHoiDong (MaHoiDong, MaNguoiDung, VaiTroHoiDong) VALUES (:mahd, :mand, :vaitro)";
                foreach ($members as $m) {
                    if (!empty($m['MaNguoiDung'])) {
                        $this->execute($memberSql, [
                            'mahd'   => $maHoiDong,
                            'mand'   => $m['MaNguoiDung'],
                            'vaitro' => $m['VaiTroHoiDong']
                        ]);
                    }
                }
            }

            $this->db->commit();
            return true;
        } catch (\Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function deleteCouncil(string $maHoiDong): bool {
        $this->db->beginTransaction();
        try {
            $this->execute("DELETE FROM PhieuDanhGia WHERE MaPhanCong IN (SELECT MaPhanCong FROM PhanCongHoiDong WHERE MaHoiDong = :hd1)", ['hd1' => $maHoiDong]);
            $this->execute("DELETE FROM PhanCongHoiDong WHERE MaHoiDong = :hd2", ['hd2' => $maHoiDong]);
            $this->execute("DELETE FROM ThanhVienHoiDong WHERE MaHoiDong = :hd3", ['hd3' => $maHoiDong]);
            $res = $this->execute("DELETE FROM HoiDong WHERE MaHoiDong = :id", ['id' => $maHoiDong]);
            $this->db->commit();
            return $res;
        } catch (\Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function unassignTopic(int $maPhanCong): bool {
        $this->db->beginTransaction();
        try {
            $this->execute("DELETE FROM PhieuDanhGia WHERE MaPhanCong = :id", ['id' => $maPhanCong]);
            $res = $this->execute("DELETE FROM PhanCongHoiDong WHERE MaPhanCong = :id", ['id' => $maPhanCong]);
            $this->db->commit();
            return $res;
        } catch (\Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function deleteScoreSheet(int $maPhieu): bool {
        return $this->execute("DELETE FROM PhieuDanhGia WHERE MaPhieu = :id", ['id' => $maPhieu]);
    }
}

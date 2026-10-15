<?php
namespace App\Models;

use PDO;
use Exception;

class DeTaiModel extends BaseModel {
    public function generateMaDeTai(): string {
        $year = date('Y');
        $row = $this->fetchOne(
            "SELECT MaDeTai FROM DeTai WHERE MaDeTai LIKE :prefix ORDER BY MaDeTai DESC LIMIT 1",
            ['prefix' => "DT_{$year}_%"]
        );
        if ($row && preg_match('/DT_\d{4}_(\d+)/', $row['MaDeTai'], $matches)) {
            $next = intval($matches[1]) + 1;
        } else {
            $next = 1;
        }
        return sprintf("DT_%s_%03d", $year, $next);
    }

    public function findById(string $maDeTai): ?array {
        return $this->fetchOne(
            "SELECT dt.*, d.TenDot, d.NamHoc, hnc.TenHuongNC,
                    nd_gv.HoTen AS TenGVHD, nd_gv.Email AS EmailGVHD, nd_gv.SoDienThoai AS SdtGVHD,
                    gv.HocHam, gv.HocVi, gv.DonViCongTac
             FROM DeTai dt
             JOIN DotNCKH d ON dt.MaDot = d.MaDot
             JOIN HuongNghienCuu hnc ON dt.MaHuongNC = hnc.MaHuongNC
             JOIN GiangVien gv ON dt.MaGVHD = gv.MaGV
             JOIN NguoiDung nd_gv ON gv.MaGV = nd_gv.MaNguoiDung
             WHERE dt.MaDeTai = :id",
            ['id' => $maDeTai]
        );
    }

    public function getMembers(string $maDeTai): array {
        return $this->fetchAll(
            "SELECT tv.*, nd.HoTen, nd.Email, nd.SoDienThoai, sv.Lop, sv.Khoa, sv.NganhHoc, sv.KhoaHoc
             FROM ThanhVienDeTai tv
             JOIN SinhVien sv ON tv.MaSV = sv.MaSV
             JOIN NguoiDung nd ON sv.MaSV = nd.MaNguoiDung
             WHERE tv.MaDeTai = :id
             ORDER BY tv.VaiTro = 'TRUONG_NHOM' DESC, nd.HoTen ASC",
            ['id' => $maDeTai]
        );
    }

    public function getTopicsByStudent(string $maSV): array {
        return $this->fetchAll(
            "SELECT dt.*, tv.VaiTro AS VaiTroCuaSV, tv.NhiemVuCuThe,
                    d.TenDot, hnc.TenHuongNC, nd_gv.HoTen AS TenGVHD
             FROM ThanhVienDeTai tv
             JOIN DeTai dt ON tv.MaDeTai = dt.MaDeTai
             JOIN DotNCKH d ON dt.MaDot = d.MaDot
             JOIN HuongNghienCuu hnc ON dt.MaHuongNC = hnc.MaHuongNC
             JOIN NguoiDung nd_gv ON dt.MaGVHD = nd_gv.MaNguoiDung
             WHERE tv.MaSV = :masv
             ORDER BY dt.NgayDangKy DESC",
            ['masv' => $maSV]
        );
    }

    public function getTopicsByTeacher(string $maGV, ?string $filterStatus = null): array {
        $sql = "SELECT dt.*, d.TenDot, hnc.TenHuongNC,
                       (SELECT nd.HoTen FROM ThanhVienDeTai tv JOIN NguoiDung nd ON tv.MaSV = nd.MaNguoiDung WHERE tv.MaDeTai = dt.MaDeTai AND tv.VaiTro = 'TRUONG_NHOM' LIMIT 1) AS TenTruongNhom,
                       (SELECT COUNT(*) FROM ThanhVienDeTai tv WHERE tv.MaDeTai = dt.MaDeTai) AS SoThanhVien
                FROM DeTai dt
                JOIN DotNCKH d ON dt.MaDot = d.MaDot
                JOIN HuongNghienCuu hnc ON dt.MaHuongNC = hnc.MaHuongNC
                WHERE dt.MaGVHD = :magv";
        $params = ['magv' => $maGV];
        if ($filterStatus) {
            $sql .= " AND dt.TrangThai = :status";
            $params['status'] = $filterStatus;
        }
        $sql .= " ORDER BY dt.NgayDangKy DESC";
        return $this->fetchAll($sql, $params);
    }

    public function getAllTopics(?string $filterStatus = null, ?string $filterDot = null, ?string $search = null): array {
        $sql = "SELECT dt.*, d.TenDot, d.NamHoc, hnc.TenHuongNC, nd_gv.HoTen AS TenGVHD,
                       (SELECT nd.HoTen FROM ThanhVienDeTai tv JOIN NguoiDung nd ON tv.MaSV = nd.MaNguoiDung WHERE tv.MaDeTai = dt.MaDeTai AND tv.VaiTro = 'TRUONG_NHOM' LIMIT 1) AS TenTruongNhom,
                       (SELECT COUNT(*) FROM ThanhVienDeTai tv WHERE tv.MaDeTai = dt.MaDeTai) AS SoThanhVien,
                       kq.DiemTongKet, kq.XepLoai, kq.DanhHieuKhenThuong
                FROM DeTai dt
                JOIN DotNCKH d ON dt.MaDot = d.MaDot
                JOIN HuongNghienCuu hnc ON dt.MaHuongNC = hnc.MaHuongNC
                JOIN NguoiDung nd_gv ON dt.MaGVHD = nd_gv.MaNguoiDung
                LEFT JOIN KetQuaKhenThuong kq ON dt.MaDeTai = kq.MaDeTai
                WHERE 1=1";
        $params = [];
        if ($filterStatus) {
            $sql .= " AND dt.TrangThai = :status";
            $params['status'] = $filterStatus;
        }
        if ($filterDot) {
            $sql .= " AND dt.MaDot = :dot";
            $params['dot'] = $filterDot;
        }
        if ($search) {
            $sql .= " AND (dt.TenDeTai LIKE :s OR dt.MaDeTai LIKE :s OR nd_gv.HoTen LIKE :s)";
            $params['s'] = "%{$search}%";
        }
        $sql .= " ORDER BY dt.NgayDangKy DESC";
        return $this->fetchAll($sql, $params);
    }

    // UC 2: Đăng ký đề tài (Transaction: tạo đề tài + các thành viên)
    public function registerTopic(array $topicData, array $members): string {
        $this->db->beginTransaction();
        try {
            $maDeTai = $this->generateMaDeTai();
            $sql = "INSERT INTO DeTai (
                        MaDeTai, TenDeTai, MaDot, MaHuongNC, MaGVHD,
                        MucTieu, NoiDungNC, SanPhamDuKien, KinhPhiDuKien,
                        FilePhieuThuyetMinh, FilePhieuDeXuat, TrangThai
                    ) VALUES (
                        :madetai, :tendetai, :madot, :mahuong, :magvhd,
                        :muctieu, :noidung, :sanpham, :kinhphi,
                        :filetm, :filedx, 'CHO_GVHD_DUYET'
                    )";
            $this->execute($sql, [
                'madetai'  => $maDeTai,
                'tendetai' => $topicData['TenDeTai'],
                'madot'    => $topicData['MaDot'],
                'mahuong'  => $topicData['MaHuongNC'],
                'magvhd'   => $topicData['MaGVHD'],
                'muctieu'  => $topicData['MucTieu'],
                'noidung'  => $topicData['NoiDungNC'],
                'sanpham'  => $topicData['SanPhamDuKien'],
                'kinhphi'  => $topicData['KinhPhiDuKien'] ?? 0,
                'filetm'   => $topicData['FilePhieuThuyetMinh'],
                'filedx'   => $topicData['FilePhieuDeXuat']
            ]);

            // Thêm các thành viên nhóm
            $memberSql = "INSERT INTO ThanhVienDeTai (MaDeTai, MaSV, VaiTro, NhiemVuCuThe, NgayThamGia)
                          VALUES (:madetai, :masv, :vaitro, :nhiemvu, CURRENT_DATE)";
            foreach ($members as $m) {
                $this->execute($memberSql, [
                    'madetai' => $maDeTai,
                    'masv'    => $m['MaSV'],
                    'vaitro'  => $m['VaiTro'],
                    'nhiemvu' => $m['NhiemVuCuThe'] ?? null
                ]);
            }

            $this->db->commit();
            return $maDeTai;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    // UC 3: Sửa hồ sơ đề tài theo góp ý
    public function updateTopicProposal(string $maDeTai, array $data, string $maSV, string $noiDungSua): bool {
        $this->db->beginTransaction();
        try {
            // Đếm số lần sửa hiện tại
            $countRow = $this->fetchOne(
                "SELECT COUNT(*) AS total FROM LichSuChinhSua WHERE MaDeTai = :id",
                ['id' => $maDeTai]
            );
            $lanSua = ($countRow['total'] ?? 0) + 1;

            // Cập nhật thông tin đề tài & chuyển trạng thái về CHO_THAM_DINH
            $sql = "UPDATE DeTai SET
                        TenDeTai = :tendetai,
                        MaHuongNC = :mahuong,
                        MucTieu = :muctieu,
                        NoiDungNC = :noidung,
                        SanPhamDuKien = :sanpham,
                        KinhPhiDuKien = :kinhphi,
                        TrangThai = 'CHO_THAM_DINH'";
            $params = [
                'tendetai' => $data['TenDeTai'],
                'mahuong'  => $data['MaHuongNC'],
                'muctieu'  => $data['MucTieu'],
                'noidung'  => $data['NoiDungNC'],
                'sanpham'  => $data['SanPhamDuKien'],
                'kinhphi'  => $data['KinhPhiDuKien'] ?? 0,
                'id'       => $maDeTai
            ];

            if (!empty($data['FilePhieuThuyetMinh'])) {
                $sql .= ", FilePhieuThuyetMinh = :filetm";
                $params['filetm'] = $data['FilePhieuThuyetMinh'];
            }
            if (!empty($data['FilePhieuDeXuat'])) {
                $sql .= ", FilePhieuDeXuat = :filedx";
                $params['filedx'] = $data['FilePhieuDeXuat'];
            }
            $sql .= " WHERE MaDeTai = :id";
            $this->execute($sql, $params);

            // Ghi vào bảng LichSuChinhSua
            $historySql = "INSERT INTO LichSuChinhSua (MaDeTai, LanChinhSua, NoiDungSua, FileThuyetMinhMoi, FileDeXuatMoi, NguoiSua)
                           VALUES (:madetai, :lan, :noidung, :filetm, :filedx, :nguoisua)";
            $this->execute($historySql, [
                'madetai'  => $maDeTai,
                'lan'      => $lanSua,
                'noidung'  => $noiDungSua,
                'filetm'   => $data['FilePhieuThuyetMinh'] ?? null,
                'filedx'   => $data['FilePhieuDeXuat'] ?? null,
                'nguoisua' => $maSV
            ]);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function getEditHistory(string $maDeTai): array {
        return $this->fetchAll(
            "SELECT ls.*, nd.HoTen AS TenNguoiSua
             FROM LichSuChinhSua ls
             LEFT JOIN SinhVien sv ON ls.NguoiSua = sv.MaSV
             LEFT JOIN NguoiDung nd ON sv.MaSV = nd.MaNguoiDung
             WHERE ls.MaDeTai = :id
             ORDER BY ls.LanChinhSua DESC",
            ['id' => $maDeTai]
        );
    }

    // UC 8: GVHD xét duyệt đề tài
    public function teacherApprove(string $maDeTai, bool $approved, ?string $comments): bool {
        $status = $approved ? 'CHO_THAM_DINH' : 'GVHD_TU_CHOI';
        return $this->execute(
            "UPDATE DeTai SET TrangThai = :status, NhanXetGVHD = :comments, NgayGVHDDuyet = CURRENT_TIMESTAMP WHERE MaDeTai = :id",
            ['status' => $status, 'comments' => $comments, 'id' => $maDeTai]
        );
    }

    // UC 12: Cán bộ QL thẩm định đề tài
    public function officerApprove(string $maDeTai, string $decision, ?string $comments): bool {
        // decision: PHE_DUYET -> DANG_THUC_HIEN; YEU_CAU_SUA -> YEU_CAU_CHINH_SUA; TU_CHOI -> HUY
        $status = match ($decision) {
            'PHE_DUYET'   => 'DANG_THUC_HIEN',
            'YEU_CAU_SUA' => 'YEU_CAU_CHINH_SUA',
            default       => 'HUY'
        };

        return $this->execute(
            "UPDATE DeTai SET TrangThai = :status, NhanXetCanBo = :comments, NgayCanBoDuyet = CURRENT_TIMESTAMP WHERE MaDeTai = :id",
            ['status' => $status, 'comments' => $comments, 'id' => $maDeTai]
        );
    }

    public function updateStatus(string $maDeTai, string $status): bool {
        return $this->execute(
            "UPDATE DeTai SET TrangThai = :status WHERE MaDeTai = :id",
            ['status' => $status, 'id' => $maDeTai]
        );
    }
}

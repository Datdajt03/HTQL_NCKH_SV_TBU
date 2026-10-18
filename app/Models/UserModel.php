<?php
namespace App\Models;

use App\Config\App;

class UserModel extends BaseModel {
    public function findById(string $id, bool $onlyActive = false): ?array {
        $sql = "SELECT * FROM NguoiDung WHERE MaNguoiDung = :id";
        if ($onlyActive) {
            $sql .= " AND TrangThai = 'HOAT_DONG'";
        }
        $user = $this->fetchOne($sql, ['id' => $id]);
        if ($user) {
            $user['extra'] = $this->getExtraInfo($user['MaNguoiDung'], $user['VaiTro']);
        }
        return $user;
    }

    public function findByLogin(string $login, bool $onlyActive = false): ?array {
        $sql = "SELECT * FROM NguoiDung WHERE (MaNguoiDung = :login1 OR Email = :login2)";
        if ($onlyActive) {
            $sql .= " AND TrangThai = 'HOAT_DONG'";
        }
        $user = $this->fetchOne($sql, ['login1' => $login, 'login2' => $login]);
        if ($user) {
            $user['extra'] = $this->getExtraInfo($user['MaNguoiDung'], $user['VaiTro']);
        }
        return $user;
    }

    public function getExtraInfo(string $userId, string $role): array {
        if ($role === App::ROLE_SINH_VIEN) {
            return $this->fetchOne("SELECT * FROM SinhVien WHERE MaSV = :id", ['id' => $userId]) ?: [];
        } elseif ($role === App::ROLE_GIANG_VIEN) {
            return $this->fetchOne("SELECT * FROM GiangVien WHERE MaGV = :id", ['id' => $userId]) ?: [];
        } elseif ($role === App::ROLE_CAN_BO_QL || $role === App::ROLE_ADMIN) {
            return $this->fetchOne("SELECT * FROM CanBoQL WHERE MaCB = :id", ['id' => $userId]) ?: [];
        }
        return [];
    }

    public function isUsernameTaken(string $username): bool {
        $row = $this->fetchOne("SELECT 1 FROM NguoiDung WHERE MaNguoiDung = :u", ['u' => $username]);
        return !empty($row);
    }

    public function isEmailTaken(string $email, ?string $excludeUserId = null): bool {
        $sql = "SELECT 1 FROM NguoiDung WHERE Email = :e";
        $params = ['e' => $email];
        if ($excludeUserId) {
            $sql .= " AND MaNguoiDung != :exclude";
            $params['exclude'] = $excludeUserId;
        }
        $row = $this->fetchOne($sql, $params);
        return !empty($row);
    }

    public function registerUser(array $userData, array $roleData): bool {
        $this->db->beginTransaction();
        try {
            // 1. Thêm vào bảng NguoiDung
            $sqlNd = "INSERT INTO NguoiDung (MaNguoiDung, MatKhauHash, HoTen, Email, SoDienThoai, VaiTro, TrangThai)
                      VALUES (:mand, :hash, :hoten, :email, :sdt, :vaitro, :trangthai)";
            $this->execute($sqlNd, [
                'mand'      => $userData['MaNguoiDung'],
                'hash'      => $userData['MatKhauHash'],
                'hoten'     => $userData['HoTen'],
                'email'     => $userData['Email'],
                'sdt'       => $userData['SoDienThoai'] ?? null,
                'vaitro'    => $userData['VaiTro'],
                'trangthai' => $userData['TrangThai'] ?? 'HOAT_DONG'
            ]);

            // 2. Thêm vào bảng chi tiết theo vai trò
            if ($userData['VaiTro'] === App::ROLE_SINH_VIEN) {
                $sqlSv = "INSERT INTO SinhVien (MaSV, Lop, Khoa, NganhHoc, KhoaHoc)
                          VALUES (:masv, :lop, :khoa, :nganh, :khoahoc)";
                $this->execute($sqlSv, [
                    'masv'    => $userData['MaNguoiDung'],
                    'lop'     => $roleData['Lop'] ?? 'Chưa cập nhật',
                    'khoa'    => $roleData['Khoa'] ?? 'Khoa Công nghệ Thông tin',
                    'nganh'   => $roleData['NganhHoc'] ?? 'Khoa học Máy tính',
                    'khoahoc' => $roleData['KhoaHoc'] ?? (date('Y') . ' - ' . (date('Y') + 4))
                ]);
            } elseif ($userData['VaiTro'] === App::ROLE_GIANG_VIEN) {
                $sqlGv = "INSERT INTO GiangVien (MaGV, HocHam, HocVi, DonViCongTac, LinhVucNghienCuu)
                          VALUES (:magv, :hocham, :hocvi, :donvi, :linhvuc)";
                $this->execute($sqlGv, [
                    'magv'    => $userData['MaNguoiDung'],
                    'hocham'  => !empty($roleData['HocHam']) ? $roleData['HocHam'] : null,
                    'hocvi'   => $roleData['HocVi'] ?? 'Thạc sĩ',
                    'donvi'   => $roleData['DonViCongTac'] ?? 'Khoa Công nghệ Thông tin',
                    'linhvuc' => $roleData['LinhVucNghienCuu'] ?? null
                ]);
            } elseif ($userData['VaiTro'] === App::ROLE_CAN_BO_QL || $userData['VaiTro'] === App::ROLE_ADMIN) {
                $sqlCb = "INSERT INTO CanBoQL (MaCB, PhongBan, ChucVu)
                          VALUES (:macb, :phong, :chucvu)";
                $this->execute($sqlCb, [
                    'macb'   => $userData['MaNguoiDung'],
                    'phong'  => $roleData['PhongBan'] ?? 'Phòng Quản lý Khoa học',
                    'chucvu' => $roleData['ChucVu'] ?? 'Chuyên viên'
                ]);
            }

            $this->db->commit();
            return true;
        } catch (\Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function updateProfileDetailed(string $userId, array $basic, array $roleData = []): bool {
        $this->db->beginTransaction();
        try {
            // 1. Cập nhật NguoiDung
            $this->execute(
                "UPDATE NguoiDung SET HoTen = :hoten, Email = :email, SoDienThoai = :sdt WHERE MaNguoiDung = :id",
                [
                    'hoten' => $basic['HoTen'],
                    'email' => $basic['Email'],
                    'sdt'   => $basic['SoDienThoai'] ?? null,
                    'id'    => $userId
                ]
            );

            // 2. Cập nhật bảng vai trò chi tiết
            $user = $this->fetchOne("SELECT VaiTro FROM NguoiDung WHERE MaNguoiDung = :id", ['id' => $userId]);
            if ($user) {
                $role = $user['VaiTro'];
                if ($role === App::ROLE_SINH_VIEN && !empty($roleData)) {
                    $this->execute(
                        "UPDATE SinhVien SET Lop = :lop, Khoa = :khoa, NganhHoc = :nganh, KhoaHoc = :khoahoc WHERE MaSV = :id",
                        [
                            'lop'     => $roleData['Lop'] ?? '',
                            'khoa'    => $roleData['Khoa'] ?? '',
                            'nganh'   => $roleData['NganhHoc'] ?? '',
                            'khoahoc' => $roleData['KhoaHoc'] ?? '',
                            'id'      => $userId
                        ]
                    );
                } elseif ($role === App::ROLE_GIANG_VIEN && !empty($roleData)) {
                    $this->execute(
                        "UPDATE GiangVien SET HocHam = :hocham, HocVi = :hocvi, DonViCongTac = :donvi, LinhVucNghienCuu = :linhvuc WHERE MaGV = :id",
                        [
                            'hocham'  => !empty($roleData['HocHam']) ? $roleData['HocHam'] : null,
                            'hocvi'   => $roleData['HocVi'] ?? 'Thạc sĩ',
                            'donvi'   => $roleData['DonViCongTac'] ?? '',
                            'linhvuc' => $roleData['LinhVucNghienCuu'] ?? '',
                            'id'      => $userId
                        ]
                    );
                } elseif (($role === App::ROLE_CAN_BO_QL || $role === App::ROLE_ADMIN) && !empty($roleData)) {
                    $this->execute(
                        "UPDATE CanBoQL SET PhongBan = :phong, ChucVu = :chucvu WHERE MaCB = :id",
                        [
                            'phong'  => $roleData['PhongBan'] ?? '',
                            'chucvu' => $roleData['ChucVu'] ?? '',
                            'id'     => $userId
                        ]
                    );
                }
            }

            $this->db->commit();
            return true;
        } catch (\Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function getAllUsers(?string $search = null, ?string $role = null, ?string $status = null): array {
        $sql = "SELECT nd.*,
                       sv.Lop, sv.Khoa AS KhoaSV, sv.NganhHoc, sv.KhoaHoc,
                       gv.HocHam, gv.HocVi, gv.DonViCongTac, gv.LinhVucNghienCuu,
                       cb.PhongBan, cb.ChucVu
                FROM NguoiDung nd
                LEFT JOIN SinhVien sv ON nd.MaNguoiDung = sv.MaSV
                LEFT JOIN GiangVien gv ON nd.MaNguoiDung = gv.MaGV
                LEFT JOIN CanBoQL cb ON nd.MaNguoiDung = cb.MaCB
                WHERE 1=1";
        $params = [];

        if ($search) {
            $sql .= " AND (nd.MaNguoiDung LIKE :s OR nd.HoTen LIKE :s OR nd.Email LIKE :s OR nd.SoDienThoai LIKE :s)";
            $params['s'] = "%{$search}%";
        }
        if ($role) {
            $sql .= " AND nd.VaiTro = :role";
            $params['role'] = $role;
        }
        if ($status) {
            $sql .= " AND nd.TrangThai = :status";
            $params['status'] = $status;
        }

        $sql .= " ORDER BY nd.NgayTao DESC, nd.MaNguoiDung ASC";
        return $this->fetchAll($sql, $params);
    }

    public function toggleUserStatus(string $userId): string {
        $user = $this->fetchOne("SELECT TrangThai FROM NguoiDung WHERE MaNguoiDung = :id", ['id' => $userId]);
        if (!$user) {
            throw new \Exception("Không tìm thấy người dùng {$userId}.");
        }
        $newStatus = ($user['TrangThai'] === 'HOAT_DONG') ? 'KHOA' : 'HOAT_DONG';
        $this->execute("UPDATE NguoiDung SET TrangThai = :st WHERE MaNguoiDung = :id", [
            'st' => $newStatus,
            'id' => $userId
        ]);
        return $newStatus;
    }

    public function deleteUser(string $userId, bool $cascadeTopics = true): bool {
        // Kiểm tra xem người dùng có phải là ADMIN hệ thống không
        if ($userId === 'ADMIN') {
            throw new \Exception("Không được phép xóa tài khoản Quản trị viên tối cao (ADMIN)!");
        }

        $this->db->beginTransaction();
        try {
            if ($cascadeTopics) {
                // Xóa các đề tài do người này làm GVHD
                $gvTopics = $this->fetchAll("SELECT MaDeTai FROM DeTai WHERE MaGVHD = :id", ['id' => $userId]);
                foreach ($gvTopics as $t) {
                    $this->execute("DELETE FROM PhanCongHoiDong WHERE MaDeTai = :id", ['id' => $t['MaDeTai']]);
                    $this->execute("DELETE FROM DeTai WHERE MaDeTai = :id", ['id' => $t['MaDeTai']]);
                }

                // Xóa các đề tài mà sinh viên này làm trưởng nhóm
                $svTopics = $this->fetchAll("SELECT MaDeTai FROM ThanhVienDeTai WHERE MaSV = :id AND VaiTro = 'TRUONG_NHOM'", ['id' => $userId]);
                foreach ($svTopics as $t) {
                    $this->execute("DELETE FROM PhanCongHoiDong WHERE MaDeTai = :id", ['id' => $t['MaDeTai']]);
                    $this->execute("DELETE FROM DeTai WHERE MaDeTai = :id", ['id' => $t['MaDeTai']]);
                }
            } else {
                $countDt = $this->fetchOne("SELECT COUNT(*) AS total FROM DeTai WHERE MaGVHD = :id", ['id' => $userId]);
                if (($countDt['total'] ?? 0) > 0) {
                    throw new \Exception("Không thể xóa giảng viên đang hướng dẫn {$countDt['total']} đề tài NCKH. Hãy chuyển đề tài hoặc khóa tài khoản.");
                }
                $countTv = $this->fetchOne("SELECT COUNT(*) AS total FROM ThanhVienDeTai WHERE MaSV = :id AND VaiTro = 'TRUONG_NHOM'", ['id' => $userId]);
                if (($countTv['total'] ?? 0) > 0) {
                    throw new \Exception("Không thể xóa sinh viên đang là trưởng nhóm của đề tài NCKH. Hãy chuyển quyền trưởng nhóm hoặc xóa đề tài trước.");
                }
            }

            // Xóa phiếu đánh giá do người này chấm
            $this->execute("DELETE FROM PhieuDanhGia WHERE MaGiamKhao = :id", ['id' => $userId]);
            // Xóa thành viên hội đồng
            $this->execute("DELETE FROM ThanhVienHoiDong WHERE MaNguoiDung = :id", ['id' => $userId]);
            // Xóa thành viên đề tài (vai trò thành viên thông thường)
            $this->execute("DELETE FROM ThanhVienDeTai WHERE MaSV = :id", ['id' => $userId]);
            // Giải phóng ràng buộc thông báo do cán bộ đăng
            $this->execute("UPDATE ThongBaoBieuMau SET NguoiDang = NULL WHERE NguoiDang = :id", ['id' => $userId]);
            // Giải phóng ràng buộc đợt do cán bộ tạo
            $this->execute("UPDATE DotNCKH SET MaCanBoTao = NULL WHERE MaCanBoTao = :id", ['id' => $userId]);

            // Xóa role tables
            $this->execute("DELETE FROM SinhVien WHERE MaSV = :id", ['id' => $userId]);
            $this->execute("DELETE FROM GiangVien WHERE MaGV = :id", ['id' => $userId]);
            $this->execute("DELETE FROM CanBoQL WHERE MaCB = :id", ['id' => $userId]);

            $res = $this->execute("DELETE FROM NguoiDung WHERE MaNguoiDung = :id", ['id' => $userId]);
            $this->db->commit();
            return $res;
        } catch (\Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function getAllTeachers(): array {
        return $this->fetchAll(
            "SELECT nd.MaNguoiDung, nd.HoTen, nd.Email, nd.SoDienThoai, 
                    gv.HocHam, gv.HocVi, gv.DonViCongTac, gv.LinhVucNghienCuu
             FROM NguoiDung nd
             JOIN GiangVien gv ON nd.MaNguoiDung = gv.MaGV
             WHERE nd.TrangThai = 'HOAT_DONG'
             ORDER BY nd.HoTen ASC"
        );
    }

    public function getAllStudents(): array {
        return $this->fetchAll(
            "SELECT nd.MaNguoiDung, nd.HoTen, nd.Email, sv.Lop, sv.Khoa, sv.NganhHoc
             FROM NguoiDung nd
             JOIN SinhVien sv ON nd.MaNguoiDung = sv.MaSV
             WHERE nd.TrangThai = 'HOAT_DONG'
             ORDER BY sv.Lop ASC, nd.HoTen ASC"
        );
    }

    public function updatePassword(string $userId, string $newHash): bool {
        return $this->execute(
            "UPDATE NguoiDung SET MatKhauHash = :hash WHERE MaNguoiDung = :id",
            ['hash' => $newHash, 'id' => $userId]
        );
    }

    public function updateProfile(string $userId, string $hoTen, string $email, ?string $soDienThoai): bool {
        return $this->execute(
            "UPDATE NguoiDung SET HoTen = :hoten, Email = :email, SoDienThoai = :sdt WHERE MaNguoiDung = :id",
            ['hoten' => $hoTen, 'email' => $email, 'sdt' => $soDienThoai, 'id' => $userId]
        );
    }

    public function getUserStats(): array {
        $total = $this->fetchOne("SELECT COUNT(*) as total FROM NguoiDung")['total'] ?? 0;
        $sinhVien = $this->fetchOne("SELECT COUNT(*) as total FROM NguoiDung WHERE VaiTro = 'SINH_VIEN'")['total'] ?? 0;
        $giangVien = $this->fetchOne("SELECT COUNT(*) as total FROM NguoiDung WHERE VaiTro = 'GIANG_VIEN'")['total'] ?? 0;
        $canBo = $this->fetchOne("SELECT COUNT(*) as total FROM NguoiDung WHERE VaiTro IN ('CAN_BO_QL', 'ADMIN')")['total'] ?? 0;
        $active = $this->fetchOne("SELECT COUNT(*) as total FROM NguoiDung WHERE TrangThai = 'HOAT_DONG'")['total'] ?? 0;
        return [
            'total'    => (int)$total,
            'sinhVien' => (int)$sinhVien,
            'giangVien'=> (int)$giangVien,
            'canBo'    => (int)$canBo,
            'active'   => (int)$active,
        ];
    }
}

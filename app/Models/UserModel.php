<?php
namespace App\Models;

use App\Config\App;

class UserModel extends BaseModel {
    public function findById(string $id): ?array {
        $user = $this->fetchOne(
            "SELECT * FROM NguoiDung WHERE MaNguoiDung = :id AND TrangThai = 'HOAT_DONG'",
            ['id' => $id]
        );
        if ($user) {
            $user['extra'] = $this->getExtraInfo($user['MaNguoiDung'], $user['VaiTro']);
        }
        return $user;
    }

    public function findByLogin(string $login): ?array {
        $user = $this->fetchOne(
            "SELECT * FROM NguoiDung WHERE (MaNguoiDung = :login OR Email = :login) AND TrangThai = 'HOAT_DONG'",
            ['login' => $login]
        );
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
}

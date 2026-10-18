-- ============================================================================
-- CƠ SỞ DỮ LIỆU: HỆ THỐNG QUẢN LÝ NGHIÊN CỨU KHOA HỌC SINH VIÊN
-- Tương thích: MySQL 5.7+, MySQL 8.0+, MariaDB 10.3+
-- Bảng mã: utf8mb4_unicode_ci
-- ============================================================================

CREATE DATABASE IF NOT EXISTS `ql_nckh_sv` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `ql_nckh_sv`;

SET FOREIGN_KEY_CHECKS = 0;

-- 1. BẢNG NGUOI DUNG & CHUYÊN BIỆT HÓA VAI TRÒ
DROP TABLE IF EXISTS `PhieuDanhGia`;
DROP TABLE IF EXISTS `PhanCongHoiDong`;
DROP TABLE IF EXISTS `ThanhVienHoiDong`;
DROP TABLE IF EXISTS `HoiDong`;
DROP TABLE IF EXISTS `KetQuaKhenThuong`;
DROP TABLE IF EXISTS `SanPhamNghienCuu`;
DROP TABLE IF EXISTS `BaoCaoTongKet`;
DROP TABLE IF EXISTS `BaoCaoTienDo`;
DROP TABLE IF EXISTS `LichSuChinhSua`;
DROP TABLE IF EXISTS `ThanhVienDeTai`;
DROP TABLE IF EXISTS `DeTai`;
DROP TABLE IF EXISTS `ThongBaoBieuMau`;
DROP TABLE IF EXISTS `HuongNghienCuu`;
DROP TABLE IF EXISTS `DotNCKH`;
DROP TABLE IF EXISTS `CanBoQL`;
DROP TABLE IF EXISTS `GiangVien`;
DROP TABLE IF EXISTS `SinhVien`;
DROP TABLE IF EXISTS `NguoiDung`;

CREATE TABLE `NguoiDung` (
    `MaNguoiDung` VARCHAR(20) NOT NULL,
    `MatKhauHash` VARCHAR(255) NOT NULL,
    `HoTen` VARCHAR(100) NOT NULL,
    `Email` VARCHAR(100) NOT NULL,
    `SoDienThoai` VARCHAR(15) NULL,
    `VaiTro` VARCHAR(20) NOT NULL,
    `TrangThai` VARCHAR(20) NOT NULL DEFAULT 'HOAT_DONG',
    `NgayTao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`MaNguoiDung`),
    UNIQUE KEY `uk_nguoidung_email` (`Email`),
    CONSTRAINT `chk_vaitro` CHECK (`VaiTro` IN ('SINH_VIEN', 'GIANG_VIEN', 'CAN_BO_QL', 'ADMIN')),
    CONSTRAINT `chk_trangthai_nd` CHECK (`TrangThai` IN ('HOAT_DONG', 'KHOA'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `SinhVien` (
    `MaSV` VARCHAR(20) NOT NULL,
    `Lop` VARCHAR(50) NOT NULL,
    `Khoa` VARCHAR(100) NOT NULL,
    `NganhHoc` VARCHAR(100) NOT NULL,
    `KhoaHoc` VARCHAR(20) NOT NULL,
    PRIMARY KEY (`MaSV`),
    CONSTRAINT `fk_sinhvien_nguoidung` FOREIGN KEY (`MaSV`) REFERENCES `NguoiDung` (`MaNguoiDung`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `GiangVien` (
    `MaGV` VARCHAR(20) NOT NULL,
    `HocHam` VARCHAR(30) NULL,
    `HocVi` VARCHAR(30) NOT NULL,
    `DonViCongTac` VARCHAR(100) NOT NULL,
    `LinhVucNghienCuu` TEXT NULL,
    PRIMARY KEY (`MaGV`),
    CONSTRAINT `fk_giangvien_nguoidung` FOREIGN KEY (`MaGV`) REFERENCES `NguoiDung` (`MaNguoiDung`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `CanBoQL` (
    `MaCB` VARCHAR(20) NOT NULL,
    `PhongBan` VARCHAR(100) NOT NULL,
    `ChucVu` VARCHAR(50) NOT NULL,
    PRIMARY KEY (`MaCB`),
    CONSTRAINT `fk_canboql_nguoidung` FOREIGN KEY (`MaCB`) REFERENCES `NguoiDung` (`MaNguoiDung`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. ĐỢT NCKH & HƯỚNG NGHIÊN CỨU & THÔNG BÁO BIỂU MẪU
CREATE TABLE `DotNCKH` (
    `MaDot` VARCHAR(30) NOT NULL,
    `TenDot` VARCHAR(255) NOT NULL,
    `NamHoc` VARCHAR(20) NOT NULL,
    `NgayBatDau` DATE NOT NULL,
    `HanDangKy` DATE NOT NULL,
    `HanNopBaoCaoTD` DATE NOT NULL,
    `HanNopNghiemThu` DATE NOT NULL,
    `NgayKetThuc` DATE NOT NULL,
    `TrangThai` VARCHAR(30) NOT NULL DEFAULT 'MO_DANG_KY',
    `MaCanBoTao` VARCHAR(20) NULL,
    PRIMARY KEY (`MaDot`),
    CONSTRAINT `chk_trangthai_dot` CHECK (`TrangThai` IN ('MO_DANG_KY', 'DANG_THUC_HIEN', 'NGHIEM_THU', 'KET_THUC')),
    CONSTRAINT `fk_dotnckh_canbo` FOREIGN KEY (`MaCanBoTao`) REFERENCES `CanBoQL` (`MaCB`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `HuongNghienCuu` (
    `MaHuongNC` VARCHAR(30) NOT NULL,
    `TenHuongNC` VARCHAR(255) NOT NULL,
    `MoTa` TEXT NULL,
    PRIMARY KEY (`MaHuongNC`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `ThongBaoBieuMau` (
    `MaThongBao` INT NOT NULL AUTO_INCREMENT,
    `MaDot` VARCHAR(30) NULL,
    `TieuDe` VARCHAR(255) NOT NULL,
    `Loai` VARCHAR(30) NOT NULL,
    `NoiDung` TEXT NULL,
    `FileDinhKem` VARCHAR(500) NULL,
    `NgayDang` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `NguoiDang` VARCHAR(20) NULL,
    PRIMARY KEY (`MaThongBao`),
    CONSTRAINT `chk_loai_thongbao` CHECK (`Loai` IN ('THONG_BAO', 'BIEU_MAU', 'QUY_DINH')),
    CONSTRAINT `fk_thongbao_dot` FOREIGN KEY (`MaDot`) REFERENCES `DotNCKH` (`MaDot`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_thongbao_nguoidang` FOREIGN KEY (`NguoiDang`) REFERENCES `CanBoQL` (`MaCB`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. HỒ SƠ ĐỀ TÀI & NHÓM THỰC HIỆN
CREATE TABLE `DeTai` (
    `MaDeTai` VARCHAR(30) NOT NULL,
    `TenDeTai` VARCHAR(500) NOT NULL,
    `MaDot` VARCHAR(30) NOT NULL,
    `MaHuongNC` VARCHAR(30) NOT NULL,
    `MaGVHD` VARCHAR(20) NOT NULL,
    `MucTieu` TEXT NOT NULL,
    `NoiDungNC` TEXT NOT NULL,
    `SanPhamDuKien` TEXT NOT NULL,
    `KinhPhiDuKien` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `FilePhieuThuyetMinh` VARCHAR(500) NULL,
    `FilePhieuDeXuat` VARCHAR(500) NULL,
    `DuLieuDeXuat` LONGTEXT NULL,
    `DuLieuThuyetMinh` LONGTEXT NULL,
    `TrangThai` VARCHAR(40) NOT NULL DEFAULT 'CHO_GVHD_DUYET',
    `NhanXetGVHD` TEXT NULL,
    `NgayGVHDDuyet` TIMESTAMP NULL DEFAULT NULL,
    `NhanXetCanBo` TEXT NULL,
    `NgayCanBoDuyet` TIMESTAMP NULL DEFAULT NULL,
    `NgayDangKy` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`MaDeTai`),
    KEY `idx_detai_madot` (`MaDot`),
    KEY `idx_detai_trangthai` (`TrangThai`),
    KEY `idx_detai_magvhd` (`MaGVHD`),
    CONSTRAINT `chk_trangthai_detai` CHECK (`TrangThai` IN (
        'CHO_GVHD_DUYET', 'GVHD_TU_CHOI', 'CHO_THAM_DINH', 'YEU_CAU_CHINH_SUA', 
        'DA_PHE_DUYET', 'DANG_THUC_HIEN', 'CHO_NGHIEM_THU', 'DA_NGHIEM_THU', 'HUY'
    )),
    CONSTRAINT `fk_detai_dot` FOREIGN KEY (`MaDot`) REFERENCES `DotNCKH` (`MaDot`) ON UPDATE CASCADE,
    CONSTRAINT `fk_detai_huongnc` FOREIGN KEY (`MaHuongNC`) REFERENCES `HuongNghienCuu` (`MaHuongNC`) ON UPDATE CASCADE,
    CONSTRAINT `fk_detai_gvhd` FOREIGN KEY (`MaGVHD`) REFERENCES `GiangVien` (`MaGV`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `ThanhVienDeTai` (
    `MaDeTai` VARCHAR(30) NOT NULL,
    `MaSV` VARCHAR(20) NOT NULL,
    `VaiTro` VARCHAR(30) NOT NULL,
    `NhiemVuCuThe` VARCHAR(255) NULL,
    `NgayThamGia` DATE NOT NULL,
    PRIMARY KEY (`MaDeTai`, `MaSV`),
    KEY `idx_thanhvien_masv` (`MaSV`),
    CONSTRAINT `chk_vaitro_thanhvien` CHECK (`VaiTro` IN ('TRUONG_NHOM', 'THANH_VIEN')),
    CONSTRAINT `fk_thanhvien_detai` FOREIGN KEY (`MaDeTai`) REFERENCES `DeTai` (`MaDeTai`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_thanhvien_sinhvien` FOREIGN KEY (`MaSV`) REFERENCES `SinhVien` (`MaSV`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `LichSuChinhSua` (
    `MaLichSu` INT NOT NULL AUTO_INCREMENT,
    `MaDeTai` VARCHAR(30) NOT NULL,
    `LanChinhSua` INT NOT NULL,
    `NoiDungSua` TEXT NOT NULL,
    `FileThuyetMinhMoi` VARCHAR(500) NULL,
    `FileDeXuatMoi` VARCHAR(500) NULL,
    `DuLieuDeXuatMoi` LONGTEXT NULL,
    `DuLieuThuyetMinhMoi` LONGTEXT NULL,
    `NgaySua` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `NguoiSua` VARCHAR(20) NULL,
    PRIMARY KEY (`MaLichSu`),
    KEY `idx_lichsu_detai` (`MaDeTai`),
    CONSTRAINT `fk_lichsu_detai` FOREIGN KEY (`MaDeTai`) REFERENCES `DeTai` (`MaDeTai`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_lichsu_nguoisua` FOREIGN KEY (`NguoiSua`) REFERENCES `SinhVien` (`MaSV`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. BÁO CÁO TIẾN ĐỘ, TỔNG KẾT & SẢN PHẨM
CREATE TABLE `BaoCaoTienDo` (
    `MaBaoCaoTD` INT NOT NULL AUTO_INCREMENT,
    `MaDeTai` VARCHAR(30) NOT NULL,
    `DotBaoCao` VARCHAR(50) NOT NULL,
    `PhanTramHoanThanh` INT NOT NULL,
    `CongViecDaLam` TEXT NOT NULL,
    `ChiTietTienDo` LONGTEXT NULL,
    `KhoKhanVuongMac` TEXT NULL,
    `FileBaoCao` VARCHAR(500) NULL,
    `NgayNop` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `NhanXetGVHD` TEXT NULL,
    `DanhGiaTienDo` VARCHAR(30) NULL,
    `NgayDanhGia` TIMESTAMP NULL DEFAULT NULL,
    `NhanXetCanBo` TEXT NULL,
    `DanhGiaCanBo` VARCHAR(30) NULL,
    `NgayCanBoDanhGia` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`MaBaoCaoTD`),
    KEY `idx_baocaotd_madetai` (`MaDeTai`),
    CONSTRAINT `chk_phantram` CHECK (`PhanTramHoanThanh` BETWEEN 0 AND 100),
    CONSTRAINT `chk_danhgia_tiendo` CHECK (`DanhGiaTienDo` IS NULL OR `DanhGiaTienDo` IN ('DAT_TIEN_DO', 'CHAM_TIEN_DO', 'CAN_NHAC_NHO')),
    CONSTRAINT `fk_baocaotd_detai` FOREIGN KEY (`MaDeTai`) REFERENCES `DeTai` (`MaDeTai`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `BaoCaoTongKet` (
    `MaBaoCaoTK` INT NOT NULL AUTO_INCREMENT,
    `MaDeTai` VARCHAR(30) NOT NULL,
    `TomTatKetQua` TEXT NOT NULL,
    `FileBaoCaoTongKet` VARCHAR(500) NOT NULL,
    `NgayNop` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `DanhGiaSoBoGVHD` VARCHAR(30) NOT NULL DEFAULT 'CHO_DANH_GIA',
    `NhanXetSoBoGVHD` TEXT NULL,
    `NgayGVHDDanhGia` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`MaBaoCaoTK`),
    UNIQUE KEY `uk_baocaotk_detai` (`MaDeTai`),
    CONSTRAINT `chk_danhgia_sobo` CHECK (`DanhGiaSoBoGVHD` IN ('CHO_DANH_GIA', 'DONG_Y_NGHIEM_THU', 'KHONG_DONG_Y')),
    CONSTRAINT `fk_baocaotk_detai` FOREIGN KEY (`MaDeTai`) REFERENCES `DeTai` (`MaDeTai`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `SanPhamNghienCuu` (
    `MaSanPham` INT NOT NULL AUTO_INCREMENT,
    `MaBaoCaoTK` INT NOT NULL,
    `TenSanPham` VARCHAR(255) NOT NULL,
    `LoaiSanPham` VARCHAR(50) NOT NULL,
    `MoTaSanPham` TEXT NULL,
    `LienKetMinhChung` VARCHAR(500) NULL,
    `FileMinhChung` VARCHAR(500) NULL,
    PRIMARY KEY (`MaSanPham`),
    KEY `idx_sanpham_baocao` (`MaBaoCaoTK`),
    CONSTRAINT `chk_loai_sanpham` CHECK (`LoaiSanPham` IN ('PHAN_MEM', 'BAI_BAO_KHOA_HOC', 'MO_HINH', 'QUY_TRINH')),
    CONSTRAINT `fk_sanpham_baocao` FOREIGN KEY (`MaBaoCaoTK`) REFERENCES `BaoCaoTongKet` (`MaBaoCaoTK`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. HỘI ĐỒNG NGHIỆM THU, CHẤM ĐIỂM & KHEN THƯỞNG
CREATE TABLE `HoiDong` (
    `MaHoiDong` VARCHAR(30) NOT NULL,
    `TenHoiDong` VARCHAR(255) NOT NULL,
    `MaDot` VARCHAR(30) NOT NULL,
    `LoaiHoiDong` VARCHAR(30) NOT NULL,
    `NgayThanhLap` DATE NOT NULL,
    `SoQuyetDinh` VARCHAR(100) NULL,
    `TrangThai` VARCHAR(30) NOT NULL DEFAULT 'HOAT_DONG',
    PRIMARY KEY (`MaHoiDong`),
    CONSTRAINT `chk_loai_hoidong` CHECK (`LoaiHoiDong` IN ('THAM_DINH', 'NGHIEM_THU')),
    CONSTRAINT `chk_trangthai_hd` CHECK (`TrangThai` IN ('HOAT_DONG', 'HOAN_THANH')),
    CONSTRAINT `fk_hoidong_dot` FOREIGN KEY (`MaDot`) REFERENCES `DotNCKH` (`MaDot`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `ThanhVienHoiDong` (
    `MaHoiDong` VARCHAR(30) NOT NULL,
    `MaNguoiDung` VARCHAR(20) NOT NULL,
    `VaiTroHoiDong` VARCHAR(30) NOT NULL,
    PRIMARY KEY (`MaHoiDong`, `MaNguoiDung`),
    CONSTRAINT `chk_vaitro_hoidong` CHECK (`VaiTroHoiDong` IN ('CHU_TICH', 'THU_KY', 'PHAN_BIEN_1', 'PHAN_BIEN_2', 'UY_VIEN')),
    CONSTRAINT `fk_thanhvienhd_hoidong` FOREIGN KEY (`MaHoiDong`) REFERENCES `HoiDong` (`MaHoiDong`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_thanhvienhd_nguoidung` FOREIGN KEY (`MaNguoiDung`) REFERENCES `NguoiDung` (`MaNguoiDung`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `PhanCongHoiDong` (
    `MaPhanCong` INT NOT NULL AUTO_INCREMENT,
    `MaHoiDong` VARCHAR(30) NOT NULL,
    `MaDeTai` VARCHAR(30) NOT NULL,
    `ThoiGianBaoCao` TIMESTAMP NOT NULL,
    `DiaDiem` VARCHAR(255) NOT NULL,
    `DiemTrungBinh` DECIMAL(5,2) NULL,
    `KetLuan` VARCHAR(30) NULL,
    `FileBienBan` VARCHAR(500) NULL,
    PRIMARY KEY (`MaPhanCong`),
    UNIQUE KEY `uk_phancong_detai` (`MaDeTai`),
    KEY `idx_phancong_mahd` (`MaHoiDong`),
    CONSTRAINT `chk_ketluan` CHECK (`KetLuan` IS NULL OR `KetLuan` IN ('DAT', 'KHONG_DAT', 'BAO_VE_LAI')),
    CONSTRAINT `fk_phancong_hoidong` FOREIGN KEY (`MaHoiDong`) REFERENCES `HoiDong` (`MaHoiDong`) ON UPDATE CASCADE,
    CONSTRAINT `fk_phancong_detai` FOREIGN KEY (`MaDeTai`) REFERENCES `DeTai` (`MaDeTai`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `PhieuDanhGia` (
    `MaPhieu` INT NOT NULL AUTO_INCREMENT,
    `MaPhanCong` INT NOT NULL,
    `MaGiamKhao` VARCHAR(20) NOT NULL,
    `DiemTongQuan` DECIMAL(5,2) NOT NULL DEFAULT 0.00,
    `DiemPhuongPhap` DECIMAL(5,2) NOT NULL DEFAULT 0.00,
    `DiemSanPham` DECIMAL(5,2) NOT NULL DEFAULT 0.00,
    `DiemBaoCao` DECIMAL(5,2) NOT NULL DEFAULT 0.00,
    `TongDiem` DECIMAL(5,2) GENERATED ALWAYS AS (`DiemTongQuan` + `DiemPhuongPhap` + `DiemSanPham` + `DiemBaoCao`) STORED,
    `NhanXetChiTiet` TEXT NULL,
    `NgayCham` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`MaPhieu`),
    KEY `idx_phieudg_phancong` (`MaPhanCong`),
    KEY `idx_phieudg_giamkhao` (`MaGiamKhao`),
    CONSTRAINT `chk_diem_tongquan` CHECK (`DiemTongQuan` BETWEEN 0 AND 20),
    CONSTRAINT `chk_diem_phuongphap` CHECK (`DiemPhuongPhap` BETWEEN 0 AND 30),
    CONSTRAINT `chk_diem_sanpham` CHECK (`DiemSanPham` BETWEEN 0 AND 30),
    CONSTRAINT `chk_diem_baocao` CHECK (`DiemBaoCao` BETWEEN 0 AND 20),
    CONSTRAINT `fk_phieudg_phancong` FOREIGN KEY (`MaPhanCong`) REFERENCES `PhanCongHoiDong` (`MaPhanCong`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_phieudg_giamkhao` FOREIGN KEY (`MaGiamKhao`) REFERENCES `NguoiDung` (`MaNguoiDung`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `KetQuaKhenThuong` (
    `MaKetQua` INT NOT NULL AUTO_INCREMENT,
    `MaDeTai` VARCHAR(30) NOT NULL,
    `DiemTongKet` DECIMAL(5,2) NOT NULL,
    `XepLoai` VARCHAR(30) NOT NULL,
    `DanhHieuKhenThuong` VARCHAR(50) NOT NULL DEFAULT 'KHONG',
    `SoQuyetDinh` VARCHAR(100) NULL,
    `TienThuong` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `GhiChu` TEXT NULL,
    `NgayQuyetDinh` DATE NULL,
    PRIMARY KEY (`MaKetQua`),
    UNIQUE KEY `uk_khenthuong_detai` (`MaDeTai`),
    CONSTRAINT `chk_xeploai` CHECK (`XepLoai` IN ('XUAT_SAC', 'TOT', 'KHA', 'DAT', 'KHONG_DAT')),
    CONSTRAINT `chk_danhhieu` CHECK (`DanhHieuKhenThuong` IN ('GIAI_NHAT', 'GIAI_NHI', 'GIAI_BA', 'GIAI_KHUYEN_KHICH', 'KHONG')),
    CONSTRAINT `fk_khenthuong_detai` FOREIGN KEY (`MaDeTai`) REFERENCES `DeTai` (`MaDeTai`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

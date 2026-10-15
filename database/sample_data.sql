-- ============================================================================
-- DỮ LIỆU MẪU (SEED DATA) CHO HỆ THỐNG QUẢN LÝ NCKH SINH VIÊN
-- Mật khẩu mặc định cho TẤT CẢ tài khoản là: 123456
-- ============================================================================

USE `ql_nckh_sv`;

SET FOREIGN_KEY_CHECKS = 0;

-- 1. NGUOI DUNG (Mật khẩu: 123456)
-- Hash bcrypt của '123456'
SET @pwd = '$2y$10$EYsPfga3AzOXt.bpWpBgKeUYdgtmIF3PHbaN7DpvBL56i92SrPBm6';

INSERT INTO `NguoiDung` (`MaNguoiDung`, `MatKhauHash`, `HoTen`, `Email`, `SoDienThoai`, `VaiTro`, `TrangThai`) VALUES
-- Cán bộ quản lý
('CB001', @pwd, 'Nguyễn Văn Cán', 'canbo.can@school.edu.vn', '0912345678', 'CAN_BO_QL', 'HOAT_DONG'),
('CB002', @pwd, 'Trần Thị Mai', 'canbo.mai@school.edu.vn', '0912345679', 'CAN_BO_QL', 'HOAT_DONG'),
('ADMIN', @pwd, 'Quản Trị Viên Hệ Thống', 'admin@school.edu.vn', '0909000999', 'ADMIN', 'HOAT_DONG'),

-- Giảng viên hướng dẫn
('GV001', @pwd, 'TS. Lê Hoàng Nam', 'nam.lh@school.edu.vn', '0988111222', 'GIANG_VIEN', 'HOAT_DONG'),
('GV002', @pwd, 'PGS.TS. Phạm Thị Hương', 'huong.pt@school.edu.vn', '0988333444', 'GIANG_VIEN', 'HOAT_DONG'),
('GV003', @pwd, 'TS. Vũ Minh Đức', 'duc.vm@school.edu.vn', '0988555666', 'GIANG_VIEN', 'HOAT_DONG'),

-- Sinh viên
('SV001', @pwd, 'Nguyễn Huy Hoàng', 'hoang.nh.sv@school.edu.vn', '0355111222', 'SINH_VIEN', 'HOAT_DONG'),
('SV002', @pwd, 'Trần Tuấn Anh', 'anh.tt.sv@school.edu.vn', '0355222333', 'SINH_VIEN', 'HOAT_DONG'),
('SV003', @pwd, 'Lê Ngọc Ánh', 'anh.ln.sv@school.edu.vn', '0355333444', 'SINH_VIEN', 'HOAT_DONG'),
('SV004', @pwd, 'Đỗ Đức Minh', 'minh.dd.sv@school.edu.vn', '0355444555', 'SINH_VIEN', 'HOAT_DONG'),
('SV005', @pwd, 'Hoàng Thu Thảo', 'thao.ht.sv@school.edu.vn', '0355555666', 'SINH_VIEN', 'HOAT_DONG'),
('SV006', @pwd, 'Bùi Hải Đăng', 'dang.bh.sv@school.edu.vn', '0355666777', 'SINH_VIEN', 'HOAT_DONG');

-- Bảng chi tiết Cán bộ QL
INSERT INTO `CanBoQL` (`MaCB`, `PhongBan`, `ChucVu`) VALUES
('CB001', 'Phòng Quản lý Khoa học & Hợp tác Quốc tế', 'Trưởng phòng'),
('CB002', 'Phòng Quản lý Khoa học & Hợp tác Quốc tế', 'Chuyên viên QL NCKH'),
('ADMIN', 'Trung tâm Công nghệ Thông tin', 'Quản trị viên');

-- Bảng chi tiết Giảng viên
INSERT INTO `GiangVien` (`MaGV`, `HocHam`, `HocVi`, `DonViCongTac`, `LinhVucNghienCuu`) VALUES
('GV001', NULL, 'Tiến sĩ', 'Bộ môn Khoa học Máy tính - Khoa CNTT', 'Trí tuệ nhân tạo, Xử lý ngôn ngữ tự nhiên, Thị giác máy tính'),
('GV002', 'Phó Giáo sư', 'Tiến sĩ', 'Bộ môn Kỹ thuật Phần mềm - Khoa CNTT', 'Kỹ thuật phần mềm, Hệ thống nhúng, Internet of Things (IoT)'),
('GV003', NULL, 'Tiến sĩ', 'Khoa Điện tử - Viễn thông', 'Mạng máy tính, An toàn thông tin, Chuỗi khối Blockchain');

-- Bảng chi tiết Sinh viên
INSERT INTO `SinhVien` (`MaSV`, `Lop`, `Khoa`, `NganhHoc`, `KhoaHoc`) VALUES
('SV001', 'K64 CNTT 1', 'Khoa Công nghệ Thông tin', 'Khoa học Máy tính', '2023 - 2027'),
('SV002', 'K64 CNTT 1', 'Khoa Công nghệ Thông tin', 'Khoa học Máy tính', '2023 - 2027'),
('SV003', 'K64 CNTT 2', 'Khoa Công nghệ Thông tin', 'Kỹ thuật Phần mềm', '2023 - 2027'),
('SV004', 'K65 KTPM 1', 'Khoa Công nghệ Thông tin', 'Kỹ thuật Phần mềm', '2024 - 2028'),
('SV005', 'K65 KTPM 2', 'Khoa Công nghệ Thông tin', 'Kỹ thuật Phần mềm', '2024 - 2028'),
('SV006', 'K64 DTVT', 'Khoa Điện tử - Viễn thông', 'Kỹ thuật Điện tử', '2023 - 2027');

-- 2. ĐỢT NCKH
INSERT INTO `DotNCKH` (`MaDot`, `TenDot`, `NamHoc`, `NgayBatDau`, `HanDangKy`, `HanNopBaoCaoTD`, `HanNopNghiemThu`, `NgayKetThuc`, `TrangThai`, `MaCanBoTao`) VALUES
('NCKH_2026_2027_D1', 'Đợt Nghiên Cứu Khoa Học Sinh Viên Năm Học 2026 - 2027 (Đợt 1)', '2026 - 2027', '2026-09-01', '2026-10-30', '2027-01-15', '2027-04-30', '2027-05-30', 'DANG_THUC_HIEN', 'CB001'),
('NCKH_2025_2026_D2', 'Đợt Nghiên Cứu Khoa Học Sinh Viên Năm Học 2025 - 2026 (Đợt 2)', '2025 - 2026', '2025-09-01', '2025-10-25', '2026-01-10', '2026-04-20', '2026-05-25', 'KET_THUC', 'CB001');

-- 3. HƯỚNG NGHIÊN CỨU
INSERT INTO `HuongNghienCuu` (`MaHuongNC`, `TenHuongNC`, `MoTa`) VALUES
('HNC_AI', 'Trí tuệ Nhân tạo & Xử lý Dữ liệu lớn (AI/ML/BigData)', 'Ứng dụng mô hình học sâu, thị giác máy tính, NLP và LLM trong giải quyết các bài toán thực tiễn.'),
('HNC_IOT', 'Internet Vạn vật & Hệ thống Thông minh (IoT/Smart Systems)', 'Xây dựng thiết bị đo lường, giám sát tự động, giải pháp nông nghiệp thông minh và thành phố thông minh.'),
('HNC_WEB_APP', 'Công nghệ Web tiên tiến & Ứng dụng Di động', 'Phát triển hệ thống microservices, Progressive Web Apps, tối ưu hóa trải nghiệm người dùng.'),
('HNC_BLOCKCHAIN', 'Công nghệ Chuỗi khối & An toàn Thông tin (Blockchain/Cybersecurity)', 'Bảo mật dữ liệu phi tập trung, hợp đồng thông minh, chữ ký số và xác thực điện tử.');

-- 4. THÔNG BÁO & BIỂU MẪU
INSERT INTO `ThongBaoBieuMau` (`MaDot`, `TieuDe`, `Loai`, `NoiDung`, `FileDinhKem`, `NguoiDang`) VALUES
('NCKH_2026_2027_D1', 'Kế hoạch phát động phong trào NCKH sinh viên năm học 2026-2027', 'THONG_BAO', 'Nhà trường thông báo phát động phong trào NCKH sinh viên đợt 1. Kính mời sinh viên các khoa lập nhóm nghiên cứu và đăng ký đúng hạn quy định.', 'uploads/Ke_hoach_NCKH_2026_2027.pdf', 'CB001'),
('NCKH_2026_2027_D1', 'Mẫu 01: Phiếu thuyết minh đề tài NCKH Sinh viên', 'BIEU_MAU', 'Sinh viên tải mẫu phiếu thuyết minh theo đúng quy chuẩn mẫu của Bộ GD&ĐT và quy chế nhà trường để điền và tải lên hệ thống.', 'uploads/Mau_01_Phieu_Thuyet_Minh.docx', 'CB002'),
('NCKH_2026_2027_D1', 'Mẫu 02: Phiếu đề xuất đề tài NCKH Sinh viên', 'BIEU_MAU', 'Biểu mẫu tóm tắt đề xuất đề tài NCKH, có chữ ký sơ bộ của nhóm sinh viên.', 'uploads/Mau_02_Phieu_De_Xuat.docx', 'CB002'),
('NCKH_2026_2027_D1', 'Quy chế đánh giá, tính điểm và xếp loại khen thưởng NCKH sinh viên', 'QUY_DINH', 'Quy định khung điểm hội đồng nghiệm thu (thang 100 quy đổi 10) và tiêu chuẩn khen thưởng các mức Giải Nhất, Nhì, Ba, Khuyến khích.', 'uploads/Quy_che_danh_gia_NCKH.pdf', 'CB001');

-- 5. ĐỀ TÀI NGHIÊN CỨU
INSERT INTO `DeTai` (
    `MaDeTai`, `TenDeTai`, `MaDot`, `MaHuongNC`, `MaGVHD`, `MucTieu`, `NoiDungNC`, `SanPhamDuKien`, `KinhPhiDuKien`,
    `FilePhieuThuyetMinh`, `FilePhieuDeXuat`, `TrangThai`, `NhanXetGVHD`, `NgayGVHDDuyet`, `NhanXetCanBo`, `NgayCanBoDuyet`, `NgayDangKy`
) VALUES
-- Đề tài 1: Đang thực hiện, có báo cáo tiến độ
('DT_2026_001', 'Nghiên cứu ứng dụng Mô hình Ngôn ngữ Lớn (LLM) hỗ trợ gia sư học tập thông minh cho sinh viên',
 'NCKH_2026_2027_D1', 'HNC_AI', 'GV001',
 'Xây dựng trợ lý ảo hỏi đáp chuyên ngành Công nghệ thông tin có khả năng giải thích code và tóm tắt tài liệu.',
 'Nghiên cứu kiến trúc RAG (Retrieval-Augmented Generation), tinh chỉnh Prompt và tích hợp Vector Database.',
 'Hệ thống Web App gia sư ảo, tài liệu đặc tả API và 01 bài báo hội nghị sinh viên.',
 8000000.00, 'uploads/PhieuThuyetMinh_DT001.pdf', 'uploads/PhieuDeXuat_DT001.pdf',
 'DANG_THUC_HIEN', 'Đề tài có tính cấp thiết cao, phương pháp rõ ràng, nhóm sinh viên có năng lực tốt.', '2026-09-15 10:30:00',
 'Hồ sơ hợp lệ, đề nghị khoa tạo điều kiện phòng thí nghiệm cho nhóm.', '2026-09-20 14:00:00', '2026-09-10 08:30:00'),

-- Đề tài 2: Mới đăng ký, chờ GVHD duyệt
('DT_2026_002', 'Hệ thống giám sát chất lượng không khí trong nhà trường ứng dụng IoT và cảnh báo sớm qua di động',
 'NCKH_2026_2027_D1', 'HNC_IOT', 'GV002',
 'Thiết kế trạm quan trắc đo nồng độ bụi mịn PM2.5, nhiệt độ, độ ẩm và gửi dữ liệu thời gian thực lên Dashboard.',
 'Chế tạo mạch cảm biến ESP32, giao thức MQTT kết hợp máy chủ Node.js và ứng dụng Flutter.',
 '02 bộ trạm đo thực tế tại sảnh giảng đường và ứng dụng theo dõi trên điện thoại.',
 6500000.00, 'uploads/PhieuThuyetMinh_DT002.pdf', 'uploads/PhieuDeXuat_DT002.pdf',
 'CHO_GVHD_DUYET', NULL, NULL, NULL, NULL, '2026-10-02 09:15:00'),

-- Đề tài 3: GVHD đã duyệt, đang chờ Cán bộ thẩm định
('DT_2026_003', 'Ứng dụng Chuỗi khối (Blockchain) trong việc quản lý và xác thực chứng chỉ học tập điện tử',
 'NCKH_2026_2027_D1', 'HNC_BLOCKCHAIN', 'GV001',
 'Loại bỏ tình trạng làm giả chứng chỉ, văn bằng tốt nghiệp với độ tin cậy tuyệt đối.',
 'Triển khai Smart Contract trên mạng thử nghiệm Ethereum/Polygon, tích hợp mã QR công khai.',
 'Hệ thống cấp phát chứng chỉ cho phòng đào tạo và cổng tra cứu văn bằng trực tuyến.',
 7000000.00, 'uploads/PhieuThuyetMinh_DT003.pdf', 'uploads/PhieuDeXuat_DT003.pdf',
 'CHO_THAM_DINH', 'Đề tài mang tính ứng dụng cao, đồng ý hướng dẫn nhóm nghiên cứu.', '2026-10-05 16:45:00', NULL, NULL, '2026-10-01 14:00:00'),

-- Đề tài 4: Bị Cán bộ QL yêu cầu chỉnh sửa hồ sơ
('DT_2026_004', 'Giải pháp tối ưu hóa hiệu năng ứng dụng Web Microservices dựa trên Docker và Kubernetes',
 'NCKH_2026_2027_D1', 'HNC_WEB_APP', 'GV002',
 'Xây dựng kiến trúc chịu tải cao và tự động co giãn tài nguyên cho cổng đăng ký học phần.',
 'Thực nghiệm kịch bản tải bằng JMeter, so sánh latency và throughput giữa Monolith và Microservices.',
 'Mô hình kiến trúc triển khai Helm Chart và báo cáo đo lường chi tiết.',
 5000000.00, 'uploads/PhieuThuyetMinh_DT004.pdf', 'uploads/PhieuDeXuat_DT004.pdf',
 'YEU_CAU_CHINH_SUA', 'Đồng ý hướng dẫn.', '2026-09-25 11:20:00',
 'Dự toán kinh phí chi tiết chưa hợp lý, đề nghị nhóm bổ sung giải trình các hạng mục mua linh kiện.', '2026-09-28 15:30:00', '2026-09-22 10:00:00'),

-- Đề tài 5: Đã hoàn thành nộp Báo cáo tổng kết, chờ nghiệm thu
('DT_2026_005', 'Hệ thống nhận diện biển số xe tự động và phân luồng đỗ xe thông minh trong bãi giữ xe trường',
 'NCKH_2026_2027_D1', 'HNC_AI', 'GV003',
 'Giảm ùn tắc giờ cao điểm tại bãi đỗ xe trường đại học với độ chính xác nhận diện trên 98%.',
 'Thu thập dữ liệu biển số xe máy Việt Nam, huấn luyện mô hình YOLOv8 và OCR EasyOCR.',
 'Phần mềm quản lý bãi đỗ xe hoàn chỉnh, camera IP thử nghiệm và video demo thực tế.',
 9500000.00, 'uploads/PhieuThuyetMinh_DT005.pdf', 'uploads/PhieuDeXuat_DT005.pdf',
 'CHO_NGHIEM_THU', 'Sản phẩm hoàn thiện vượt mức đăng ký ban đầu.', '2026-09-12 09:00:00',
 'Đã duyệt đề tài.', '2026-09-16 14:00:00', '2026-09-05 08:00:00'),

-- Đề tài 6 (Đợt trước): Đã nghiệm thu & khen thưởng Giải Nhất
('DT_2025_001', 'Thiết bị hỗ trợ dẫn đường cho người khiếm thị ứng dụng cảm biến siêu âm và phản hồi rung',
 'NCKH_2025_2026_D2', 'HNC_IOT', 'GV002',
 'Tạo ra thiết bị đeo thông minh giúp người khiếm thị tự tin di chuyển trong môi trường đô thị.',
 'Tích hợp mảng cảm biến đo khoảng cách, định vị GPS và module phát âm thanh tiếng Việt.',
 '01 mẫu gậy dẫn đường thông minh có tích hợp pin sạc và nút khẩn cấp SOS.',
 10000000.00, 'uploads/PhieuThuyetMinh_DT2025.pdf', 'uploads/PhieuDeXuat_DT2025.pdf',
 'DA_NGHIEM_THU', 'Sản phẩm có ý nghĩa nhân văn sâu sắc, đã thử nghiệm thực tế rất tốt.', '2025-09-20 10:00:00',
 'Duyệt đề tài.', '2025-09-25 15:00:00', '2025-09-15 08:00:00');

-- 6. THÀNH VIÊN ĐỀ TÀI
INSERT INTO `ThanhVienDeTai` (`MaDeTai`, `MaSV`, `VaiTro`, `NhiemVuCuThe`, `NgayThamGia`) VALUES
-- DT001
('DT_2026_001', 'SV001', 'TRUONG_NHOM', 'Quản lý dự án, tích hợp mô hình LLM và backend', '2026-09-10'),
('DT_2026_001', 'SV002', 'THANH_VIEN', 'Xây dựng giao diện Web và thu thập dữ liệu hỏi đáp', '2026-09-10'),
('DT_2026_001', 'SV003', 'THANH_VIEN', 'Tối ưu hóa Vector Database và kiểm thử tải', '2026-09-10'),

-- DT002
('DT_2026_002', 'SV004', 'TRUONG_NHOM', 'Thiết kế phần cứng mạch ESP32 và cảm biến', '2026-10-02'),
('DT_2026_002', 'SV005', 'THANH_VIEN', 'Lập trình ứng dụng di động hiển thị thông số', '2026-10-02'),

-- DT003
('DT_2026_003', 'SV006', 'TRUONG_NHOM', 'Viết Smart Contract Solidity và kiểm thử', '2026-10-01'),

-- DT004
('DT_2026_004', 'SV002', 'TRUONG_NHOM', 'Thiết lập cụm Docker Swarm/Kubernetes và viết kịch bản tải', '2026-09-22'),

-- DT005
('DT_2026_005', 'SV001', 'TRUONG_NHOM', 'Huấn luyện mạng nơ-ron nhận diện biển số xe', '2026-09-05'),
('DT_2026_005', 'SV003', 'THANH_VIEN', 'Lập trình phần mềm desktop quản lý bãi đỗ', '2026-09-05'),

-- DT2025
('DT_2025_001', 'SV001', 'TRUONG_NHOM', 'Thiết kế tổng thể hệ thống phần cứng và phần mềm', '2025-09-15'),
('DT_2025_001', 'SV006', 'THANH_VIEN', 'Chế tạo vỏ thiết bị và mạch nguồn thông minh', '2025-09-15');

-- 7. LỊCH SỬ CHỈNH SỬA
INSERT INTO `LichSuChinhSua` (`MaDeTai`, `LanChinhSua`, `NoiDungSua`, `FileThuyetMinhMoi`, `FileDeXuatMoi`, `NguoiSua`) VALUES
('DT_2026_004', 1, 'Đã điều chỉnh lại bảng dự trù kinh phí mua bản quyền và thiết bị đo lường theo đúng góp ý của Hội đồng.', 'uploads/PhieuThuyetMinh_DT004_v2.pdf', 'uploads/PhieuDeXuat_DT004_v2.pdf', 'SV002');

-- 8. BÁO CÁO TIẾN ĐỘ
INSERT INTO `BaoCaoTienDo` (
    `MaDeTai`, `DotBaoCao`, `PhanTramHoanThanh`, `CongViecDaLam`, `KhoKhanVuongMac`,
    `FileBaoCao`, `NhanXetGVHD`, `DanhGiaTienDo`, `NgayDanhGia`
) VALUES
('DT_2026_001', 'Báo cáo tiến độ Định kỳ Đợt 1', 65,
 'Đã hoàn thành module xử lý văn bản PDF thành vector embeddings, tích hợp thành công mô hình Llama-3 qua API cục bộ.',
 'Cần thêm tài nguyên GPU để kiểm thử thời gian phản hồi khi nhiều người dùng cùng truy vấn.',
 'uploads/BaoCaoTienDo_DT001_D1.pdf',
 'Tiến độ rất khả quan, nhóm đã chủ động làm việc tốt. Đề xuất xin thêm tài nguyên server của khoa.',
 'DAT_TIEN_DO', '2026-10-10 15:00:00');

-- 9. BÁO CÁO TỔNG KẾT & SẢN PHẨM
INSERT INTO `BaoCaoTongKet` (
    `MaDeTai`, `TomTatKetQua`, `FileBaoCaoTongKet`, `DanhGiaSoBoGVHD`, `NhanXetSoBoGVHD`, `NgayGVHDDanhGia`
) VALUES
('DT_2026_005',
 'Nhóm đã xây dựng hoàn chỉnh hệ thống nhận diện biển số xe máy và ô tô với tốc độ xử lý 28 FPS trên webcam thông thường, đạt độ chính xác ký tự 98.6%. Hệ thống đã thử nghiệm 2 tuần tại cổng số 2 nhà trường.',
 'uploads/BaoCaoTongKet_DT005.pdf',
 'DONG_Y_NGHIEM_THU',
 'Đề tài hoàn thành đầy đủ các chỉ tiêu, sản phẩm chạy ổn định trên thực tế, đủ điều kiện đưa ra hội đồng nghiệm thu.',
 '2026-10-12 11:00:00'),

('DT_2025_001',
 'Chế tạo thành công thiết bị gậy thông minh nhỏ gọn, pin dùng liên tục 18 giờ, phản hồi khoảng cách chướng ngại vật cực nhanh qua motor rung.',
 'uploads/BaoCaoTongKet_DT2025.pdf',
 'DONG_Y_NGHIEM_THU',
 'Sản phẩm xuất sắc, tính ứng dụng thực tiễn cao.',
 '2026-04-15 09:00:00');

-- Sản phẩm nghiên cứu đính kèm
INSERT INTO `SanPhamNghienCuu` (`MaBaoCaoTK`, `TenSanPham`, `LoaiSanPham`, `MoTaSanPham`, `LienKetMinhChung`, `FileMinhChung`) VALUES
(1, 'Phần mềm SmartParking AI', 'PHAN_MEM', 'Phần mềm nhận diện biển số và xuất hóa đơn gửi xe tự động', 'https://github.com/nckh/smart-parking', 'uploads/Demo_SmartParking.zip'),
(1, 'Bài báo khoa học: Biển số xe VN bằng YOLOv8', 'BAI_BAO_KHOA_HOC', 'Bài báo gửi đăng tại Hội nghị Khoa học Trẻ toàn quốc 2026', 'https://sciencedirect.example/paper/12345', 'uploads/Paper_YOLO_LicensePlate.pdf'),
(2, 'Thiết bị Gậy thông minh BlindNavigator', 'MO_HINH', 'Mẫu phần cứng hoàn thiện tích hợp cảm biến siêu âm', 'https://youtube.com/watch?v=blind-nav-demo', 'uploads/Mau_blind_nav.zip');

-- 10. HỘI ĐỒNG NGHIỆM THU
INSERT INTO `HoiDong` (`MaHoiDong`, `TenHoiDong`, `MaDot`, `LoaiHoiDong`, `NgayThanhLap`, `SoQuyetDinh`, `TrangThai`) VALUES
('HD_NT_2026_01', 'Hội đồng nghiệm thu đề tài NCKH Sinh viên chuyên ngành AI & CNTT 2026', 'NCKH_2026_2027_D1', 'NGHIEM_THU', '2026-10-10', 'QĐ-128/QĐ-ĐH-NCKH', 'HOAT_DONG'),
('HD_NT_2025_01', 'Hội đồng nghiệm thu đề tài NCKH Sinh viên năm học 2025-2026', 'NCKH_2025_2026_D2', 'NGHIEM_THU', '2026-04-10', 'QĐ-99/QĐ-ĐH-NCKH', 'HOAN_THANH');

-- Thành viên hội đồng
INSERT INTO `ThanhVienHoiDong` (`MaHoiDong`, `MaNguoiDung`, `VaiTroHoiDong`) VALUES
-- Hội đồng 2026 (Đang hoạt động)
('HD_NT_2026_01', 'GV002', 'CHU_TICH'),
('HD_NT_2026_01', 'GV001', 'PHAN_BIEN_1'),
('HD_NT_2026_01', 'CB001', 'THU_KY'),

-- Hội đồng 2025
('HD_NT_2025_01', 'GV001', 'CHU_TICH'),
('HD_NT_2025_01', 'GV003', 'PHAN_BIEN_1'),
('HD_NT_2025_01', 'CB002', 'THU_KY');

-- Phân công hội đồng
INSERT INTO `PhanCongHoiDong` (`MaHoiDong`, `MaDeTai`, `ThoiGianBaoCao`, `DiaDiem`, `DiemTrungBinh`, `KetLuan`, `FileBienBan`) VALUES
-- Phân công cho DT005 bảo vệ sắp tới
('HD_NT_2026_01', 'DT_2026_005', '2026-10-25 08:30:00', 'Phòng Hội thảo A2-302, Tòa nhà Thư viện', NULL, NULL, NULL),

-- DT2025 đã nghiệm thu xong
('HD_NT_2025_01', 'DT_2025_001', '2026-04-25 14:00:00', 'Phòng Hội đồng 1, Tòa nhà Điều hành', 95.50, 'DAT', 'uploads/BienBanNghiemThu_DT2025.pdf');

-- 11. PHIẾU ĐÁNH GIÁ (Chấm điểm chi tiết của giám khảo)
-- Phiếu chấm cho DT2025 (3 giám khảo: GV001, GV003, CB002)
INSERT INTO `PhieuDanhGia` (
    `MaPhanCong`, `MaGiamKhao`, `DiemTongQuan`, `DiemPhuongPhap`, `DiemSanPham`, `DiemBaoCao`, `NhanXetChiTiet`
) VALUES
(2, 'GV001', 19.00, 28.50, 29.50, 19.00, 'Đề tài xuất sắc, sản phẩm hoạt động rất tốt trong thực tế.'),
(2, 'GV003', 18.50, 28.00, 29.00, 19.50, 'Nhóm sinh viên tự tin, trả lời tốt các câu hỏi phản biện chuyên sâu.'),
(2, 'CB002', 19.50, 29.00, 29.00, 19.00, 'Hồ sơ đầy đủ, minh chứng rõ ràng, mang ý nghĩa xã hội cao.');

-- 12. KẾT QUẢ VÀ KHEN THƯỞNG
INSERT INTO `KetQuaKhenThuong` (
    `MaDeTai`, `DiemTongKet`, `XepLoai`, `DanhHieuKhenThuong`, `SoQuyetDinh`, `TienThuong`, `GhiChu`, `NgayQuyetDinh`
) VALUES
('DT_2025_001', 9.55, 'XUAT_SAC', 'GIAI_NHAT', 'QĐ-KHT-2026/01-KT', 5000000.00, 'Trao tặng Giấy khen của Hiệu trưởng và phần thưởng NCKH Xuất sắc nhất năm học', '2026-05-10');

SET FOREIGN_KEY_CHECKS = 1;

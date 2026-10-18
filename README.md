<p align="center">
  <a href="https://utb.edu.vn/" target="_blank">
    <img src="pic/Logo-Dai-Hoc-Tay-Bac.png" alt="Trường Đại học Tây Bắc" height="85">
  </a>
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  <a href="https://nst.utb.edu.vn/gioi-thieu.html" target="_blank">
    <img src="pic/logo-khoa.png" alt="Khoa Nông - Sinh - Công nghệ" height="85">
  </a>
</p>

<h1 align="center">Hệ Thống Quản Lý Nghiên Cứu Khoa Học Sinh Viên<br>Trường Đại Học Tây Bắc (UTB)</h1>

<p align="center">
  <b>Đơn vị đào tạo & nghiên cứu:</b> <a href="https://utb.edu.vn/" target="_blank">Trường Đại học Tây Bắc</a> | <a href="https://nst.utb.edu.vn/gioi-thieu.html" target="_blank">Khoa Nông - Sinh - Công nghệ</a>
</p>

---

## 📌 Bài toán đặt ra

Hệ thống tin học hóa và chuẩn hóa toàn diện quy trình quản lý hoạt động Nghiên cứu Khoa học (NCKH) của sinh viên tại **Trường Đại học Tây Bắc**, kết nối xuyên suốt 3 nhóm người dùng:

- **Sinh viên**: Nộp hồ sơ đăng ký đề tài trực tuyến (Phiếu đề xuất & Thuyết minh), cập nhật báo cáo tiến độ định kỳ, nộp sản phẩm nghiệm thu và tra cứu kết quả khen thưởng.
- **Giảng viên hướng dẫn (GVHD)**: Tiếp nhận duyệt hồ sơ hướng dẫn, theo dõi và nhận xét tiến độ thực hiện đề tài của các nhóm nghiên cứu.
- **Cán bộ Quản lý (Phòng QLKH / Ban chủ nhiệm Khoa)**: Ban hành các đợt NCKH, thẩm định phê duyệt đề tài, thành lập Hội đồng nghiệm thu, nhập điểm đánh giá và xét duyệt khen thưởng.

---

## 🚀 Hướng dẫn cài đặt & chạy thử

### 1. Yêu cầu môi trường
- Đã cài đặt [XAMPP](https://www.apachefriends.org/) (hỗ trợ PHP 8.x và MySQL / MariaDB).
- Đặt thư mục mã nguồn vào: `C:\xampp\htdocs\web1` (hoặc thư mục tương ứng trong `htdocs`).

### 2. Khởi tạo Cơ sở dữ liệu
Bật **Apache** và **MySQL** trong XAMPP Control Panel, sau đó chọn **1 trong 3 cách** sau:
- **Cách 1 (Nhanh nhất - Khuyên dùng)**: Nhấp đúp chạy file `setup_database.bat` trong thư mục dự án để tự động tạo CSDL `ql_nckh_sv` và nạp dữ liệu mẫu.
- **Cách 2 (Qua giao diện Web)**: Mở trình duyệt truy cập `http://localhost/web1/setup.php` và nhấn nút thực thi khởi tạo.
- **Cách 3 (Thủ công qua phpMyAdmin)**: Vào `http://localhost/phpmyadmin/`, tạo database `ql_nckh_sv`, rồi import lần lượt 2 file:
  1. `database/schema.sql` (Cấu trúc 18 bảng)
  2. `database/sample_data.sql` (Dữ liệu mẫu thử nghiệm)

### 3. Đăng nhập & Trải nghiệm
Mở trình duyệt truy cập: **`http://localhost/web1/`**

**Tài khoản thử nghiệm (Mật khẩu chung: `123456`)**:

| Vai trò | Tên đăng nhập | Họ và tên | Chức năng chính |
| :--- | :--- | :--- | :--- |
| **Cán bộ QL** | `ADMIN` / `CB001` | Quản trị viên / Trưởng phòng QLKH | Quản lý đợt NCKH, thẩm định đề tài, hội đồng & khen thưởng |
| **Giảng viên** | `GV001` | TS. Lê Hoàng Nam | Duyệt hướng dẫn đề tài, theo dõi & nhận xét tiến độ |
| **Giảng viên** | `GV002` | ThS. Trần Thị Mai | Giảng viên hướng dẫn, chấm điểm hội đồng |
| **Sinh viên** | `SV001` | Nguyễn Huy Hoàng | Trưởng nhóm đề tài đã nghiệm thu, xem kết quả khen thưởng |
| **Sinh viên** | `SV002` | Trần Mai Anh | Đăng ký đề tài mới, nộp báo cáo tiến độ định kỳ |

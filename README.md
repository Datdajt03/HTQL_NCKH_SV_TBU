# HTQL_NCKH_SV_TBU

Hệ Thống Quản Lý Hoạt Động Nghiên Cứu Khoa Học Sinh Viên - Trường Đại học Thái Bình (TBU)

---

## 📌 Giới thiệu dự án

Hệ thống hỗ trợ tin học hóa và chuẩn hóa toàn diện quy trình quản lý hoạt động Nghiên cứu Khoa học (NCKH) của sinh viên tại Trường Đại học Thái Bình. Hệ thống kết nối liền mạch 3 chủ thể: **Sinh viên**, **Giảng viên hướng dẫn (GVHD)** và **Cán bộ Quản lý NCKH**.

### 🌟 Tính năng chính theo phân hệ

1. **Phân hệ Sinh viên**:
   - Tra cứu thông tin các đợt phát động NCKH, quy chế và tải biểu mẫu hướng dẫn.
   - Đăng ký đề tài NCKH (nộp trực tuyến Phiếu đề xuất và Phiếu thuyết minh).
   - Chỉnh sửa hồ sơ đăng ký theo phản hồi/góp ý của GVHD và Cán bộ QL.
   - Nộp báo cáo tiến độ định kỳ theo yêu cầu đợt NCKH.
   - Nộp báo cáo tổng kết, tài liệu nghiệm thu và liên kết sản phẩm nghiên cứu.
   - Tra cứu kết quả đánh giá, điểm số hội đồng và danh hiệu khen thưởng.

2. **Phân hệ Giảng viên hướng dẫn**:
   - Tiếp nhận hồ sơ đăng ký đề tài của các nhóm sinh viên.
   - Xét duyệt / Xác nhận hướng dẫn hoặc từ chối kèm lý do.
   - Theo dõi tiến độ nghiên cứu và ghi nhận xét, đôn đốc sinh viên.
   - Đánh giá sơ bộ kết quả nghiên cứu và duyệt cho phép nghiệm thu cấp trường/khoa.

3. **Phân hệ Cán bộ Quản lý NCKH (Admin/Phòng QLKH)**:
   - Quản lý các đợt NCKH, công bố kế hoạch và quy chế.
   - Thẩm định hồ sơ đề tài, phê duyệt giao đề tài chính thức.
   - Quản lý, giám sát tiến độ thực hiện toàn trường.
   - Thành lập và phân công Hội đồng nghiệm thu (Chủ tịch, Thư ký, Ủy viên, Phản biện).
   - Nhập phiếu điểm, tự động tính điểm trung bình và xếp loại (Xuất sắc, Tốt, Khá, Đạt, Không đạt).
   - Xét duyệt danh sách khen thưởng, thống kê báo cáo và xuất danh sách in ấn.

---

## 🛠️ Công nghệ sử dụng

- **Ngôn ngữ**: PHP 8.x thuần, kiến trúc MVC rõ ràng, chuẩn PSR-4 Autoloading.
- **Cơ sở dữ liệu**: MySQL / MariaDB (Chuẩn hóa 3NF, 18 bảng quan hệ đầy đủ Foreign Keys và Cascade constraints).
- **Giao diện**: HTML5, Modern CSS (Glassmorphism, Dark/Light Mode Palette, Responsive), Vanilla JavaScript.
- **Web Server**: Hỗ trợ tối ưu trên Apache (XAMPP / WampServer / LAMP).

---

## 🚀 Hướng dẫn cài đặt & triển khai trên XAMPP

### Cách 1: Cài đặt tự động qua giao diện Web (Khuyến nghị)

1. Sao chép toàn bộ thư mục dự án vào thư mục `htdocs` của XAMPP:
   ```
   C:\xampp\htdocs\web1
   ```
2. Khởi động **Apache** và **MySQL** trong XAMPP Control Panel.
3. Mở trình duyệt web và truy cập vào đường dẫn:
   ```
   http://localhost/web1/setup.php
   ```
4. Điền thông tin kết nối MySQL (mặc định Host `127.0.0.1`, User `root`, Password để trống) và nhấn **"Thực Thi Khởi Tạo CSDL & Dữ Liệu Mẫu"**.
5. Sau khi hoàn tất, hệ thống tự động tạo cơ sở dữ liệu `ql_nckh_sv` với 18 bảng và nạp sẵn 12 tài khoản mẫu cùng 6 đề tài thử nghiệm.

### Cách 2: Cài đặt thủ công qua phpMyAdmin

1. Mở phpMyAdmin tại `http://localhost/phpmyadmin/`.
2. Tạo mới Database tên: `ql_nckh_sv` (Collation: `utf8mb4_unicode_ci`).
3. Import lần lượt 2 file SQL:
   - `database/schema.sql` (Cấu trúc bảng)
   - `database/sample_data.sql` (Dữ liệu mẫu thử nghiệm)
4. Truy cập hệ thống tại: `http://localhost/web1/`

---

## 👥 Tài khoản thử nghiệm (Mật khẩu chung: `123456`)

| Vai trò | Tên đăng nhập | Họ và tên | Chức năng chính |
| :--- | :--- | :--- | :--- |
| **Cán bộ QL** | `ADMIN` / `CB001` | Trưởng phòng QLKH | Quản trị toàn quyền hệ thống, duyệt đề tài, lập hội đồng, khen thưởng |
| **Giảng viên** | `GV001` | TS. Lê Hoàng Nam | Duyệt hướng dẫn đề tài, chấm sơ bộ, theo dõi tiến độ |
| **Giảng viên** | `GV002` | ThS. Trần Thị Mai | GVHD, Thành viên hội đồng nghiệm thu |
| **Sinh viên** | `SV001` | Nguyễn Huy Hoàng | Trưởng nhóm đề tài đã nghiệm thu, xem kết quả khen thưởng |
| **Sinh viên** | `SV002` | Trần Mai Anh | Đăng ký đề tài, nộp báo cáo tiến độ |

---

## 📁 Cấu trúc thư mục

```
web1/
├── app/
│   ├── Config/          # Cấu hình hệ thống & CSDL (Database.php, App.php)
│   ├── Controllers/     # Bộ điều khiển (AuthController, SinhVienController, CanBoQLController, ...)
│   ├── Helpers/         # Hàm bổ trợ (AuthHelper, UploadHelper, ViewHelper)
│   └── Models/          # Mô hình dữ liệu thao tác CSDL (DeTaiModel, UserModel, HoiDongModel, ...)
├── database/
│   ├── schema.sql       # Script tạo cấu trúc 18 bảng CSDL chuẩn 3NF
│   └── sample_data.sql  # Dữ liệu mẫu hoàn chỉnh
├── motahethong/         # Tài liệu phân tích yêu cầu & thiết kế CSDL
├── public/
│   ├── css/style.css    # Giao diện Modern Glassmorphism
│   ├── js/app.js        # Xử lý tương tác Client
│   └── uploads/         # Thư mục lưu trữ tài liệu, báo cáo tải lên
├── views/
│   ├── auth/            # Màn hình đăng nhập & hồ sơ cá nhân
│   ├── canboql/         # Giao diện Cán bộ Quản lý NCKH
│   ├── giangvien/       # Giao diện Giảng viên hướng dẫn
│   ├── sinhvien/        # Giao diện Sinh viên
│   └── layouts/         # Khung giao diện dùng chung (header, navbar, sidebar, footer)
├── index.php            # Entry point & Front Controller routing
└── setup.php            # Trình cài đặt CSDL tự động
```

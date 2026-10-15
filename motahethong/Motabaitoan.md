**1\. Mô tả quy trình hệ thống**

Mô hình hệ thống Quản lý Hoạt động Nghiên cứu Khoa học (NCKH) Sinh viên gồm các đối tượng chính là Sinh viên, Giảng viên hướng dẫn (GVHD) và Cán bộ Quản lý NCKH . Khi nhà trường mở đợt đăng ký, sinh viên có nhu cầu tham gia NCKH sẽ đăng nhập vào hệ thống để tìm hiểu thông tin về các đợt phát động, quy định, biểu mẫu và danh sách các hướng nghiên cứu. Sinh viên lập nhóm, điền thông tin đề tài gồm Phiếu thuyết minh và Phiếu đề xuất và chọn GVHD rồi gửi yêu cầu đăng ký lên hệ thống.

GVHD tiếp nhận yêu cầu, xem xét đề tài và tiến hành xét duyệt (đồng ý hoặc từ chối hướng dẫn). Nếu được GVHD chấp thuận, hồ sơ sẽ được chuyển tiếp đến Cán bộ Quản lý NCKH  để thẩm định và trình Hội đồng xét duyệt cấp trường/khoa. Cán bộ quản lý kiểm tra tính hợp lệ, cập nhật trạng thái đề tài (Được duyệt hoặc Yêu cầu sửa đổi). Sau khi đề tài được giao, định kỳ sinh viên nộp báo cáo tiến độ hoàn thành cho hội đồng được thành lập bởi Cán bộ Quản lý NCKH. Đến hạn nghiệm thu, sinh viên nộp báo cáo tổng kết và sản phẩm nghiên cứu. Cán bộ Quản lý NCKH tổ chức hội đồng đánh giá,  nghiệm thu, xếp loại kết quả và tiến hành lập danh sách khen thưởng cho các đề tài đạt thành tích tốt.

**2\. Phân loại vai trò và các Use Case**

| Vai trò | Actor: Sinh viên | Actor: Giảng viên hướng dẫn | Actor: Cán bộ Quản lý NCKH |
| :---- | :---- | :---- | :---- |
| **Các Use Case** | 1\. Đăng nhập hệ thống. 2\. Xem thông tin các đợt NCKH và biểu mẫu hướng dẫn. 3\. Đăng ký đề tài NCKH 4\. Chỉnh sửa hồ sơ đăng ký theo góp ý. 5\. Nộp báo cáo tiến độ định kỳ. 6\. Nộp báo cáo tổng kết và sản phẩm nghiên cứu 7\. Tra cứu kết quả đánh giá, xếp loại đề tài. | 1\. Đăng nhập hệ thống. 2\. Xem và tiếp nhận hồ sơ đề tài. 3\. Xét duyệt/Xác nhận hướng dẫn đề tài. 4\. Theo dõi và ghi nhận xét tiến độ thực hiện đề tài. 5\. Đánh giá sơ bộ sản     phẩm và xác nhận cho phép sinh viên nghiệm thu. | 1\. Đăng nhập hệ thống. 2\. Đăng tải thông báo, quy định đợt NCKH mới. 3\. Thẩm định và duyệt đề tài. 4\. Quản lý tiến độ thực hiện các đề tài. 5\. Tổ chức hội đồng nghiệm thu \+ Nhập kết quả đánh giá 6\. Tổng hợp điểm, xếp loại kết quả đề tài. 7\. Xuất danh sách khen thưởng và in báo cáo thống kê. |

**Trong đó chúng ta sẽ chỉ lấy:**

### **Sinh viên**

1. **Xem thông tin NCKH**  
2. **Đăng ký đề tài**  
3. **Sửa hồ sơ đề tài**  
4. **Nộp báo cáo tiến độ**  
5. **Báo cáo tổng kết**  
6. **Xem kết quả**

### **Giảng viên hướng dẫn**

1. **Tiếp nhận hồ sơ**  
2. **Duyệt hướng dẫn**  
3. **Theo dõi tiến độ**  
4. **Đánh giá sơ bộ**

### **Cán bộ quản lý NCKH**

1. **Quản lý đợt NCKH**  
2. **Thẩm định đề tài**  
3. **Quản lý tiến độ**  
4. **Tổ chức nghiệm thu**  
5. **Xếp loại và khen thưởng**

**có 15 UC** 
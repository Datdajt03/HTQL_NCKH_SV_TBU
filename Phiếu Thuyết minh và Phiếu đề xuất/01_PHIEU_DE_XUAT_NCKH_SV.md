# BỘ GIÁO DỤC VÀ ĐÀO TẠO  
# TRƯỜNG ĐẠI HỌC TÂY BẮC

**CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM**  
**Độc lập - Tự do - Hạnh phúc**  

*Sơn La, ngày 20 tháng 10 năm 2025*

---

# PHIẾU ĐỀ XUẤT  
## ĐỀ TÀI NGHIÊN CỨU KHOA HỌC CỦA SINH VIÊN

---

### 1. Tên đề tài:
Nghiên cứu các thuật toán gần đúng giải các bài toán s-Club

---

### 2. Giải trình về tính cấp thiết:
- Trong thời đại công nghiệp 4.0, khi trí tuệ nhân tạo (AI), dữ liệu lớn (Big Data) và các mô hình tính toán thông minh đang dần trở thành nền tảng cho sự phát triển của khoa học công nghệ, việc nghiên cứu các bài toán tối ưu trên đồ thị (graph optimization problems) ngày càng trở nên quan trọng và cấp thiết. Đồ thị không chỉ là mô hình toán học biểu diễn các mối quan hệ giữa các đối tượng mà còn là công cụ hữu hiệu để mô phỏng, phân tích và tối ưu hóa trong nhiều lĩnh vực như mạng xã hội, sinh học, truyền thông, logistics, và cả các hệ thống ra quyết định tự động.
- Khi xã hội ngày càng dựa nhiều hơn vào dữ liệu và kết nối, việc hiểu rõ cấu trúc liên kết giữa các phần tử trong mạng đóng vai trò then chốt trong việc phát hiện quy luật, dự đoán hành vi, hay phát triển các mô hình phân tích cộng đồng thông minh.
- Trong bối cảnh đó, các bài toán như s-Clique, s-Club nổi lên như những công cụ mạnh mẽ giúp xác định các nhóm đỉnh có liên kết chặt chẽ trong đồ thị. Nếu như bài toán clique chỉ xem xét các mối liên hệ trực tiếp giữa các đỉnh, thì s-Club cho phép mở rộng khái niệm này bằng cách xét các đỉnh có khoảng cách không vượt quá $s$. Chính điều này giúp phản ánh chính xác hơn các mối quan hệ thực tế, nơi mà sự gắn kết giữa các đối tượng có thể thông qua một hoặc vài trung gian tương tự như cách con người trong mạng xã hội có thể kết nối gián tiếp nhưng vẫn thuộc cùng một “cộng đồng”.
- Sự khác biệt đó khiến bài toán s-Club trở thành một công cụ phân tích hữu ích trong nhiều ứng dụng hiện đại như phát hiện nhóm người có ảnh hưởng trong mạng xã hội, tối ưu hóa luồng thông tin trong mạng truyền thông, hay nhận diện các cụm gene tương đồng trong mạng sinh học.
- Tuy nhiên, các bài toán thuộc lớp s-Club lại nằm trong nhóm NP-Khó (NP-Hard) nghĩa là không thể tìm được lời giải chính xác trong thời gian ngắn khi kích thước đồ thị tăng lớn. Với những đồ thị thực tế chứa hàng nghìn hoặc hàng triệu đỉnh, việc tìm lời giải tối ưu bằng các phương pháp truyền thống là bất khả thi. Chính vì vậy, việc nghiên cứu và xây dựng các thuật toán gần đúng (approximation algorithms) hoặc các chiến lược heuristic, meta-heuristic là hướng đi cần thiết để đạt được nghiệm tốt trong thời gian hợp lý.
- Trên thế giới, nhiều công trình đã tập trung nghiên cứu hướng này. Các thuật toán tiêu biểu như CONSTELLATION, DROP, hay k-CLIQUE&DROP được (Bourjolly, Laporte và Pesant 2000, 2002) đề xuất đã cho thấy khả năng tìm nghiệm gần đúng hiệu quả cho bài toán maximum k-club. Dẫu vậy, khi mở rộng sang các biến thể phức tạp hơn như s-Club, các thuật toán này vẫn bộc lộ hạn chế về tốc độ xử lý, khả năng hội tụ và chất lượng nghiệm khi áp dụng trên đồ thị lớn.
- Nhằm khắc phục vấn đề này, nhiều hướng tiếp cận mới đã được đề xuất, kết hợp giữa các kỹ thuật tối ưu hóa tiến hóa (Evolutionary Optimization) như thuật toán di truyền (Genetic Algorithm – GA), thuật toán tối ưu bầy đàn (Particle Swarm Optimization – PSO), hay thuật toán heuristic tham lam (Greedy). Sự kết hợp này mang lại hiệu quả tích cực: vừa đảm bảo tốc độ, vừa nâng cao chất lượng nghiệm, đồng thời dễ dàng thích ứng với các đặc thù khác nhau của đồ thị.
- Tại Việt Nam, lĩnh vực nghiên cứu này vẫn còn khá mới mẻ. Một số nhóm nghiên cứu, điển hình là nhóm nghiên cứu PGS.TS. Huỳnh Thị Thanh Bình (Trường Công nghệ Thông tin và Truyền thông, Đại học Bách khoa Hà Nội), đã có những công trình đáng chú ý về thuật toán tiến hóa quần thể (population-based algorithms) và ứng dụng vào các bài toán tối ưu đồ thị như: Clustered Minimum Routing Cost Problem hay Clustered Shortest Path Tree Problem. Tuy nhiên, cho đến nay, chưa có công trình nào đi sâu vào bài toán s-Club.
- Từ thực tế trên có thể khẳng định rằng, đề tài “Nghiên cứu các thuật toán gần đúng giải các bài toán s-Club” không chỉ mang tính cấp thiết về mặt học thuật mà còn có giá trị ứng dụng thực tiễn sâu sắc. Việc nghiên cứu và đề xuất các thuật toán gần đúng, đặc biệt là dựa trên mô hình tiến hóa nhóm, không chỉ góp phần mở rộng lý thuyết tối ưu hóa đồ thị mà còn giúp tạo nền tảng cho việc ứng dụng vào các lĩnh vực phân tích mạng xã hội, phát hiện cộng đồng, tối ưu hóa mạng cảm biến hoặc xử lý dữ liệu lớn.
- Kết quả nghiên cứu kỳ vọng sẽ giúp nâng cao hiệu quả của các ứng dụng phân tích mạng thông minh, đồng thời đóng góp vào việc phát triển thuật toán tối ưu trong lĩnh vực khoa học máy tính và trí tuệ nhân tạo tại Việt Nam.

---

### 3. Mục tiêu dự kiến của đề tài:
- Hiểu và trình bày được khái niệm bài toán s-Club trong lý thuyết đồ thị, mối quan hệ giữa s-Club, k-Club và Clique.
- Tìm hiểu và cài đặt thử nghiệm một số thuật toán gần đúng (heuristic) phổ biến giải bài toán s-Club như DROP, CONSTELLATION.
- Xây dựng và thử nghiệm phiên bản thuật toán gần đúng để giải bài toán s-Club.
- Đánh giá so sánh hiệu quả (độ chính xác và thời gian chạy) giữa thuật toán nhóm xây dựng với các thuật toán đã có.
- Viết báo cáo và bài viết khoa học, trình bày kết quả thử nghiệm và hướng phát triển sau.

---

### 4. Nội dung chủ yếu của đề tài:

#### 4.1. Nội dung 1: Tìm hiểu lý thuyết và các thuật toán cơ bản
- Tìm hiểu khái niệm đồ thị, các mô hình bài toán k-Club và s-Club.
- Nắm vững nguyên lý của các thuật toán gần đúng như CONSTELLATION, DROP và IDROP, …
- Tổng hợp tài liệu, mô tả quy trình hoạt động và độ phức tạp của các thuật toán trên.
- Cài đặt thử nghiệm các thuật toán này bằng Python hoặc Java trên các bộ dữ liệu nhỏ (đồ thị từ 10–50 đỉnh).
- Phân tích ưu nhược điểm và chọn hướng tiếp cận phù hợp cho nhóm (dựa vào tài nguyên, thời gian).

#### 4.2. Nội dung 2: Đề xuất và đánh giá thuật toán di truyền nhóm (GGA)
- Đề xuất ý tưởng áp dụng thuật toán gần đúng vào bài toán s-Club.
- Thực nghiệm trên cùng bộ dữ liệu, so sánh kết quả với các thuật toán.
- Ghi nhận kết quả, đánh giá và rút ra nhận xét về hiệu quả, hạn chế.

---

### 5. Dự kiến sản phẩm của đề tài:
- 01 Báo cáo phân tích và tổng hợp lý thuyết
- 01 Báo cáo tổng kết đề tài
- 01 Chương trình mô phỏng cài đặt thuật toán
- 01 Bộ dữ liệu thử nghiệm và tài liệu hướng dẫn có thể dùng cho giảng dạy và nghiên cứu trong lĩnh vực tối ưu hóa đồ thị

---

### 6. Khả năng và địa chỉ áp dụng:
- **Đối tượng áp dụng:** Giảng viên, sinh viên và nhà nghiên cứu trong lĩnh vực Công nghệ thông tin, Trí tuệ nhân tạo và Tối ưu hóa.
- **Địa chỉ áp dụng:** Khoa Khoa học Tự nhiên – Công nghệ, Trường Đại học Tây Bắc.
- **Khả năng mở rộng:** Kết quả nghiên cứu có thể áp dụng cho các bài toán phân tích mạng xã hội, tối ưu mạng cảm biến, phát hiện cộng đồng trong dữ liệu lớn hoặc bài toán phân vùng đồ thị.

---

| **Xác nhận của Giảng viên hướng dẫn** | **Cá nhân đề xuất** |
| :---: | :---: |
| *(Ký và ghi rõ họ tên)* | *(Ký và ghi rõ họ tên)* |
| **TS. Phạm Đình Thành** | **Lò Mạnh Đạt** |

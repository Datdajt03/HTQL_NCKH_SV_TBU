# BỘ GIÁO DỤC VÀ ĐÀO TẠO  
# TRƯỜNG ĐẠI HỌC TÂY BẮC

**CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM**  
**Độc lập - Tự do - Hạnh phúc**  

*Sơn La, ngày 20 tháng 10 năm 2025*

---

# THUYẾT MINH ĐỀ TÀI  
## NGHIÊN CỨU KHOA HỌC VÀ CÔNG NGHỆ CẤP TRƯỜNG CỦA SINH VIÊN  
### NĂM HỌC 2025 - 2026

---

### 1. TÊN ĐỀ TÀI:
Nghiên cứu các thuật toán gần đúng giải các bài toán s-Club

---

### 2. LOẠI HÌNH NGHIÊN CỨU:
- [x] Cơ Bản
- [ ] Ứng Dụng
- [ ] Triển Khai

---

### 3. NHÓM NGÀNH:
Công nghệ thông tin

---

### 4. THỜI GIAN THỰC HIỆN:
Từ tháng 10 năm 2025 đến tháng 11 năm 2026

---

### 5. SINH VIÊN CHỊU TRÁCH NHIỆM CHÍNH:
| Họ và tên | Lớp | Điện thoại |
| :--- | :--- | :--- |
| **Lò Mạnh Đạt** | K64 ĐH CNTT-B | 0982 943 664 |

---

### 6. NHỮNG NGƯỜI THAM GIA THỰC HIỆN ĐỀ TÀI:
| TT | Họ và tên | Lớp | Nội dung công việc được giao | Chữ ký |
| :---: | :--- | :--- | :--- | :---: |
| 1 | Lò Mạnh Đạt | K64 ĐH CNTT-B | Trưởng nhóm, tổng hợp lý thuyết, xây dựng mô hình mô phỏng thuật toán. | *(Đã ký)* |
| 2 | Nguyễn Huy Hoàng | K64 ĐH CNTT-B | Nghiên cứu và cài đặt các thuật toán gần đúng. | *(Đã ký)* |
| 3 | Tòng Lưu Anh Tú | K64 ĐH CNTT-B | Thực hiện mô phỏng, kiểm thử thuật toán và đánh giá kết quả. | *(Đã ký)* |
| 4 | Phạm Thị Thanh Hảo | K64 ĐH CNTT-B | Thu thập tài liệu, hỗ trợ nhập dữ liệu và trình bày báo cáo, biểu đồ kết quả. | *(Đã ký)* |

---

### 7. GIẢNG VIÊN HƯỚNG DẪN:
| Họ và tên | Chức danh KH, học vị | Điện thoại liên hệ |
| :--- | :--- | :--- |
| **Phạm Đình Thành** | Tiến sĩ | 0988 811 127 |

---

### 8. TÌNH HÌNH NGHIÊN CỨU:

#### 8.1. Tổng quan tình hình nghiên cứu thuộc lĩnh vực của đề tài:
- Trong hơn hai thập kỷ qua, các bài toán tối ưu trên đồ thị đã trở thành chủ đề nghiên cứu được quan tâm trong lĩnh vực Khoa học máy tính, đặc biệt là các bài toán phát hiện cộng đồng, phân cụm và xác định nhóm đỉnh có liên kết chặt chẽ trong mạng xã hội, mạng sinh học hay mạng truyền thông. Trong số đó, bài toán k-Club và s-Club đóng vai trò nền tảng trong mô hình hóa các nhóm đỉnh có khoảng cách tối đa giữa các đỉnh không vượt quá một giá trị cho trước.
- Trên thế giới, nhiều công trình đã tập trung giải quyết bài toán s-Club bằng các thuật toán gần đúng hoặc thuật toán heuristic nhằm rút ngắn thời gian tìm kiếm nghiệm gần tối ưu.
- Các hướng tiếp cận tiêu biểu gồm: CONSTELLATION và DROP [1], [2], áp dụng chiến lược tìm kiếm lặp và loại bỏ cạnh không cần thiết để tối ưu kích thước tập đỉnh, tuy nhiên hiệu suất giảm mạnh với đồ thị lớn. IDROP [2] cải tiến DROP bằng việc sử dụng cấu trúc dữ liệu động, giúp tăng tốc độ hội tụ. CCA (Cluster-Constrained Algorithm) [3] khai thác heuristic dựa trên khoảng cách để giới hạn không gian tìm kiếm, đạt kết quả khả quan nhưng vẫn bị giới hạn bởi bộ nhớ và độ phức tạp $O(n^3)$.
- Gần đây, xu hướng kết hợp các thuật toán heuristic truyền thống với các mô hình tiến hóa (Evolutionary Algorithms) như Genetic Algorithm (GA), Particle Swarm Optimization (PSO) và Greedy Adaptive Search Procedure (GRASP) đã chứng minh hiệu quả vượt trội. Các nghiên cứu như [4] cho thấy việc lai ghép giữa tìm kiếm tham lam và di truyền giúp cải thiện đáng kể chất lượng nghiệm và tốc độ hội tụ.
- Tại Việt Nam, hướng nghiên cứu này còn tương đối mới. Một số nhóm tại Trường Đại học Bách Khoa Hà Nội dưới sự hướng dẫn của PGS.TS. Huỳnh Thị Thanh Bình đã công bố nhiều công trình về tối ưu quần thể và ứng dụng vào các bài toán đồ thị phức tạp như Clustered Minimum Routing Cost Problem, Clustered Shortest Path Tree Problem, hay Multidomain Network Optimization [5]. Tuy nhiên, các nghiên cứu tập trung chủ yếu vào bài toán định tuyến hoặc phân cụm, chưa có đề tài nào chuyên sâu về bài toán s-Club và các thuật toán gần đúng ứng dụng trong lĩnh vực này.
- Do đó, việc nghiên cứu, mô phỏng và đánh giá các thuật toán gần đúng giải bài toán s-Club không chỉ góp phần làm rõ hiệu quả của các hướng heuristic hiện có, mà còn mở ra khả năng ứng dụng thực tế trong việc phát hiện cộng đồng và tối ưu hóa mạng lưới dữ liệu.

#### 8.2. Danh mục các công trình liên quan:
*(Họ và tên tác giả; Nhan đề bài báo, ấn phẩm, nguồn tài liệu)*
- **[1]** J.-M. Bourjolly, G. Laporte, and G. Pesant, “An exact algorithm for the maximum k-club problem in graphs,” *Computers & Operations Research*, vol. 29, no. 4, pp. 479–490, 2002.
- **[2]** R.-S. Chang, C.-Y. Lee, and C.-L. Hsieh, “Improved DROP heuristic for maximum k-club detection,” *Information Sciences*, vol. 241, pp. 97–108, 2013.
- **[3]** R. Dondi, F. Sikora, and G. Valentina, “Heuristic algorithms for s-club identification,” *Applied Soft Computing*, vol. 81, pp. 105–119, 2019.
- **[4]** A. Sánchez-Caballé, J. Gisbert, and M. Esteve, “Metaheuristic optimization in social network clustering,” *Expert Systems with Applications*, vol. 140, pp. 112–123, 2020.
- **[5]** H. T. T. Bình, T. T. Vân, and P. T. H. Linh, “Population-based algorithms for clustered graph problems,” *Proceedings of the Hanoi University of Science and Technology*, 2018–2022.

---

### 9. TÍNH CẤP THIẾT CỦA ĐỀ TÀI:
- Trong thời đại công nghiệp 4.0, khi trí tuệ nhân tạo (AI), dữ liệu lớn (Big Data) và các mô hình tính toán thông minh đang dần trở thành nền tảng cho sự phát triển của khoa học công nghệ, việc nghiên cứu các bài toán tối ưu trên đồ thị (graph optimization problems) ngày càng trở nên quan trọng và cấp thiết. Đồ thị không chỉ là mô hình toán học biểu diễn các mối quan hệ giữa các đối tượng mà còn là công cụ hữu hiệu để mô phỏng, phân tích và tối ưu hóa trong nhiều lĩnh vực như mạng xã hội, sinh học, truyền thông, logistics, và cả các hệ thống ra quyết định tự động.
- Khi xã hội ngày càng dựa nhiều hơn vào dữ liệu và kết nối, việc hiểu rõ cấu trúc liên kết giữa các phần tử trong mạng đóng vai trò then chốt trong việc phát hiện quy luật, dự đoán hành vi, hay phát triển các mô hình phân tích cộng đồng thông minh.
- Trong bối cảnh đó, các bài toán như s-Clique, s-Club nổi lên như những công cụ mạnh mẽ giúp xác định các nhóm đỉnh có liên kết chặt chẽ trong đồ thị. Nếu như bài toán clique chỉ xem xét các mối liên hệ trực tiếp giữa các đỉnh, thì s-Club cho phép mở rộng khái niệm này bằng cách xét các đỉnh có khoảng cách không vượt quá $s$. Chính điều này giúp phản ánh chính xác hơn các mối quan hệ thực tế, nơi mà sự gắn kết giữa các đối tượng có thể thông qua một hoặc vài trung gian tương tự như cách con người trong mạng xã hội có thể kết nối gián tiếp nhưng vẫn thuộc cùng một “cộng đồng”.
- Sự khác biệt đó khiến bài toán s-Club trở thành một công cụ phân tích hữu ích trong nhiều ứng dụng hiện đại như phát hiện nhóm người có ảnh hưởng trong mạng xã hội, tối ưu hóa luồng thông tin trong mạng truyền thông, hay nhận diện các cụm gene tương đồng trong mạng sinh học.
- Tuy nhiên, các bài toán thuộc lớp s-Club lại nằm trong nhóm NP-Khó (NP-Hard) nghĩa là không thể tìm được lời giải chính xác trong thời gian ngắn khi kích thước đồ thị tăng lớn. Với những đồ thị thực tế chứa hàng nghìn hoặc hàng triệu đỉnh, việc tìm lời giải tối ưu bằng các phương pháp truyền thống là bất khả thi. Chính vì vậy, việc nghiên cứu và xây dựng các thuật toán gần đúng (approximation algorithms) hoặc các chiến lược heuristic, meta-heuristic là hướng đi cần thiết để đạt được nghiệm tốt trong thời gian hợp lý.
- Trên thế giới, nhiều công trình đã tập trung nghiên cứu hướng này. Các thuật toán tiêu biểu như CONSTELLATION, DROP, hay k-CLIQUE&DROP được Bourjolly, (Laporte và Pesant 2000, 2002) đề xuất đã cho thấy khả năng tìm nghiệm gần đúng hiệu quả cho bài toán maximum k-club. Dẫu vậy, khi mở rộng sang các biến thể phức tạp hơn như s-Club, các thuật toán này vẫn bộc lộ hạn chế về tốc độ xử lý, khả năng hội tụ và chất lượng nghiệm khi áp dụng trên đồ thị lớn.
- Nhằm khắc phục vấn đề này, nhiều hướng tiếp cận mới đã được đề xuất, kết hợp giữa các kỹ thuật tối ưu hóa tiến hóa (Evolutionary Optimization) như thuật toán di truyền (Genetic Algorithm – GA), thuật toán tối ưu bầy đàn (Particle Swarm Optimization – PSO), hay thuật toán heuristic tham lam (Greedy). Sự kết hợp này mang lại hiệu quả tích cực: vừa đảm bảo tốc độ, vừa nâng cao chất lượng nghiệm, đồng thời dễ dàng thích ứng với các đặc thù khác nhau của đồ thị.
- Tại Việt Nam, lĩnh vực nghiên cứu này vẫn còn khá mới mẻ. Một số nhóm nghiên cứu, điển hình là nhóm nghiên cứu PGS.TS. Huỳnh Thị Thanh Bình (Trường Công nghệ Thông tin và Truyền thông, Đại học Bách khoa Hà Nội), đã có những công trình đáng chú ý về thuật toán tiến hóa quần thể (population-based algorithms) và ứng dụng vào các bài toán tối ưu đồ thị như: Clustered Minimum Routing Cost Problem hay Clustered Shortest Path Tree Problem. Tuy nhiên, cho đến nay, chưa có công trình nào đi sâu vào bài toán s-Club.
- Từ thực tế trên có thể khẳng định rằng, đề tài “Nghiên cứu các thuật toán gần đúng giải các bài toán s-Club” không chỉ mang tính cấp thiết về mặt học thuật mà còn có giá trị ứng dụng thực tiễn sâu sắc. Việc nghiên cứu và đề xuất các thuật toán gần đúng, đặc biệt là dựa trên mô hình tiến hóa nhóm, không chỉ góp phần mở rộng lý thuyết tối ưu hóa đồ thị mà còn giúp tạo nền tảng cho việc ứng dụng vào các lĩnh vực phân tích mạng xã hội, phát hiện cộng đồng, tối ưu hóa mạng cảm biến hoặc xử lý dữ liệu lớn.
- Kết quả nghiên cứu kỳ vọng sẽ giúp nâng cao hiệu quả của các ứng dụng phân tích mạng thông minh, đồng thời đóng góp vào việc phát triển thuật toán tối ưu trong lĩnh vực khoa học máy tính và trí tuệ nhân tạo tại Việt Nam.

---

### 10. MỤC TIÊU ĐỀ TÀI:
- Đề tài được thực hiện với mục tiêu chung là nghiên cứu, cài đặt và đánh giá hiệu quả của các thuật toán gần đúng trong việc giải quyết bài toán s-Club trên đồ thị, từ đó đề xuất một hướng tiếp cận tối ưu hơn dựa trên thuật toán tiến hóa.
- Các mục tiêu cụ thể gồm:
  - **(1)** Trình bày được cơ sở lý thuyết của bài toán s-Club trong lý thuyết đồ thị, làm rõ mối quan hệ giữa các khái niệm clique, k-club và s-club, cũng như các đặc điểm về độ phức tạp tính toán của bài toán này.
  - **(2)** Nghiên cứu, tổng hợp và hiểu rõ nguyên lý hoạt động của các thuật toán gần đúng (heuristic và meta-heuristic) phổ biến đã được công bố, bao gồm CONSTELLATION, DROP, IDROP và GRASP,... nhằm xác định ưu nhược điểm của từng phương pháp.
  - **(3)** Cài đặt và thử nghiệm thực tế các thuật toán nói trên trên các bộ dữ liệu đồ thị mẫu, đánh giá hiệu quả dựa trên các tiêu chí: độ chính xác, tốc độ hội tụ và khả năng mở rộng.
  - **(4)** Tìm hiểu, cải tiến thuật toán gần đúng, nhằm cải thiện hiệu quả giải quyết bài toán s-Club.
  - **(5)** Thực nghiệm, đối chiếu và rút ra kết luận về khả năng ứng dụng của từng thuật toán, đồng thời hoàn thiện báo cáo tổng kết.

---

### 11. ĐỐI TƯỢNG, PHẠM VI , PHƯƠNG PHÁP NGHIÊN CỨU:
1. **Đối tượng nghiên cứu:**
   - Các thuật toán gần đúng (heuristic, meta-heuristic) dùng để giải bài toán s-Club trên đồ thị vô hướng.
2. **Phạm vi nghiên cứu:**
   - Nghiên cứu và mô phỏng trên các đồ thị có từ 10–50 đỉnh.
   - Sử dụng ngôn ngữ C# hoặc Java, dữ liệu tạo ngẫu nhiên hoặc từ thư viện NetworkX/SNAP.
   - Tập trung đánh giá độ chính xác và thời gian chạy, không đi sâu chứng minh lý thuyết.
3. **Phương pháp nghiên cứu:**
   - **Tổng hợp lý thuyết:** Thu thập, phân tích tài liệu trong và ngoài nước.
   - **Mô phỏng – thực nghiệm:** Cài đặt, chạy thử các thuật toán DROP, IDROP, GRASP.
   - **Thống kê – so sánh:** Phân tích kết quả bằng bảng, biểu đồ; đánh giá theo tiêu chí tốc độ và chất lượng nghiệm.

---

### 12. NỘI DUNG NGHIÊN CỨU VÀ TIẾN ĐỘ THỰC HIỆN:
| TT | Các nội dung, công việc thực hiện chủ yếu | Dự kiến sản phẩm | Thời gian (Bắt đầu – Kết thúc) | Người thực hiện |
| :---: | :--- | :--- | :--- | :--- |
| 1 | Tổng quan tài liệu, tìm hiểu bài toán s-Club và các thuật toán gần đúng | Báo cáo tổng hợp | Tháng 10/2025 đến tháng 12/2025 | Nhóm |
| 2 | Nghiên cứu, cài đặt thử nghiệm các thuật toán CONSTELLATION, DROP, IDROP, GRASP | Mã nguồn và bản mô tả thuật toán | Đầu tháng 01/2026 đến tháng 04/2026 | Nhóm |
| 3 | Thiết kế và mô phỏng thuật toán | Mã nguồn và báo cáo kết quả mô phỏng | Đầu tháng 05/2026 đến tháng 09/2026 | Nhóm |
| 4 | Viết báo cáo tổng kết, chỉnh sửa và nộp đề tài | Báo cáo hoàn chỉnh | Đầu tháng 10/2026 | Nhóm |

---

### 13. SẢN PHẨM VÀ ĐỊA CHỈ ỨNG DỤNG:

#### 13.1. Loại sản phẩm:
- [x] Báo cáo phân tích
- [ ] Mẫu
- [ ] Thiết bị máy móc
- [ ] Luận chứng kinh tế
- [ ] Giống cây trồng
- [ ] Vật liệu
- [ ] Phương pháp
- [x] Phần mềm máy tính
- [ ] Giống gia súc
- [ ] Đề án
- [ ] Tiêu chuẩn
- [ ] Qui trình công nghệ
- [ ] Tài liệu dự báo
- [ ] Sơ đồ
- [ ] Bản kiến nghị
- [ ] Sản phẩm khác

#### 13.2. Tên sản phẩm, số lượng và yêu cầu khoa học đối với sản phẩm:
| TT | Tên sản phẩm | Số lượng | Yêu cầu khoa học |
| :---: | :--- | :---: | :--- |
| 1 | Báo cáo tổng kết đề tài “Nghiên cứu các thuật toán gần đúng giải các bài toán s-Club” | 01 | Có đầy đủ cơ sở lý thuyết, kết quả thực nghiệm, biểu đồ và đánh giá khoa học. |
| 2 | Chương trình mô phỏng cài đặt thuật toán giải bài toán s-Club | 01 | Chạy ổn định trên máy tính, có giao diện trực quan và hướng dẫn sử dụng. |
| 3 | Bộ dữ liệu đồ thị mẫu và kết quả thực nghiệm so sánh | 01 | Đầy đủ tập mẫu từ 10 đến 50 đỉnh kèm kết quả so sánh độ chính xác và thời gian. |

#### 13.3. Địa chỉ có thể ứng dụng (tên địa phương, đơn vị ứng dụng):
- Khoa Khoa học Tự nhiên – Công nghệ, Trường Đại học Tây Bắc, thành phố Sơn La, tỉnh Sơn La.
- Kết quả nghiên cứu có thể ứng dụng trong giảng dạy và nghiên cứu các học phần, chuyên đề liên quan đến thuật toán tối ưu, trí tuệ nhân tạo và khoa học máy tính tại Trường Đại học Tây Bắc.

---

| **Giảng viên hướng dẫn** | **Sinh viên chịu trách nhiệm chính thực hiện đề tài** |
| :---: | :---: |
| *Ngày 20 tháng 10 năm 2025* | *Ngày 20 tháng 10 năm 2025* |
| *(Ký và ghi rõ họ tên)* | *(Ký và ghi rõ họ tên)* |
| **TS. Phạm Đình Thành** | **Lò Mạnh Đạt** |

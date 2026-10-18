<?php
namespace App\Helpers;

use App\Config\App;

/**
 * Helper chuyên trách xử lý dữ liệu và hiển thị trực quan 2 biểu mẫu NCKH:
 * 1. Phiếu Đề Xuất (Mẫu 02)
 * 2. Thuyết Minh Đề Tài (Mẫu 01)
 * Chuẩn hóa 100% theo quy định Trường Đại học Tây Bắc (Sơn La).
 */
class NckhFormHelper {

    /**
     * Danh sách 16 loại hình sản phẩm chuẩn quy định trong Mẫu 01
     */
    public const DANH_MUC_LOAI_SAN_PHAM = [
        'Báo cáo phân tích',
        'Mẫu',
        'Thiết bị máy móc',
        'Luận chứng kinh tế',
        'Giống cây trồng',
        'Vật liệu',
        'Phương pháp',
        'Phần mềm máy tính',
        'Giống gia súc',
        'Đề án',
        'Tiêu chuẩn',
        'Qui trình công nghệ',
        'Tài liệu dự báo',
        'Sơ đồ',
        'Bản kiến nghị',
        'Sản phẩm khác'
    ];

    /**
     * Dữ liệu mẫu chuẩn từ Phiếu Đề Xuất (ĐH Tây Bắc - Sơn La)
     */
    public static function getSampleDeXuatData(): array {
        return [
            'co_quan_chu_quan' => 'BỘ GIÁO DỤC VÀ ĐÀO TẠO',
            'don_vi' => 'TRƯỜNG ĐẠI HỌC TÂY BẮC',
            'dia_diem_ngay' => 'Sơn La, ngày 20 tháng 10 năm 2025',
            'ten_de_tai' => 'Nghiên cứu các thuật toán gần đúng giải các bài toán s-Club',
            'tinh_cap_thiet' => "- Trong thời đại công nghiệp 4.0, khi trí tuệ nhân tạo (AI), dữ liệu lớn (Big Data) và các mô hình tính toán thông minh đang dần trở thành nền tảng cho sự phát triển của khoa học công nghệ, việc nghiên cứu các bài toán tối ưu trên đồ thị (graph optimization problems) ngày càng trở nên quan trọng và cấp thiết. Đồ thị không chỉ là mô hình toán học biểu diễn các mối quan hệ giữa các đối tượng mà còn là công cụ hữu hiệu để mô phỏng, phân tích và tối ưu hóa trong nhiều lĩnh vực như mạng xã hội, sinh học, truyền thông, logistics, và cả các hệ thống ra quyết định tự động.\n\n- Khi xã hội ngày càng dựa nhiều hơn vào dữ liệu và kết nối, việc hiểu rõ cấu trúc liên kết giữa các phần tử trong mạng đóng vai trò then chốt trong việc phát hiện quy luật, dự đoán hành vi, hay phát triển các mô hình phân tích cộng đồng thông minh.\n\n- Trong bối cảnh đó, các bài toán như s-Clique, s-Club nổi lên như những công cụ mạnh mẽ giúp xác định các nhóm đỉnh có liên kết chặt chẽ trong đồ thị. Nếu như bài toán clique chỉ xem xét các mối liên hệ trực tiếp giữa các đỉnh, thì s-Club cho phép mở rộng khái niệm này bằng cách xét các đỉnh có khoảng cách không vượt quá s. Chính điều này giúp phản ánh chính xác hơn các mối quan hệ thực tế, nơi mà sự gắn kết giữa các đối tượng có thể thông qua một hoặc vài trung gian tương tự như cách con người trong mạng xã hội có thể kết nối gián tiếp nhưng vẫn thuộc cùng một “cộng đồng”.\n\n- Sự khác biệt đó khiến bài toán s-Club trở thành một công cụ phân tích hữu ích trong nhiều ứng dụng hiện đại như phát hiện nhóm người có ảnh hưởng trong mạng xã hội, tối ưu hóa luồng thông tin trong mạng truyền thông, hay nhận diện các cụm gene tương đồng trong mạng sinh học.\n\n- Tuy nhiên, các bài toán thuộc lớp s-Club lại nằm trong nhóm NP-Khó (NP-Hard) nghĩa là không thể tìm được lời giải chính xác trong thời gian ngắn khi kích thước đồ thị tăng lớn. Với những đồ thị thực tế chứa hàng nghìn hoặc hàng triệu đỉnh, việc tìm lời giải tối ưu bằng các phương pháp truyền thống là bất khả thi. Chính vì vậy, việc nghiên cứu và xây dựng các thuật toán gần đúng (approximation algorithms) hoặc các chiến lược heuristic, meta-heuristic là hướng đi cần thiết để đạt được nghiệm tốt trong thời gian hợp lý.\n\n- Trên thế giới, nhiều công trình đã tập trung nghiên cứu hướng này. Các thuật toán tiêu biểu như CONSTELLATION, DROP, hay k-CLIQUE&DROP được (Bourjolly, Laporte và Pesant 2000, 2002) đề xuất đã cho thấy khả năng tìm nghiệm gần đúng hiệu quả cho bài toán maximum k-club. Dẫu vậy, khi mở rộng sang các biến thể phức tạp hơn như s-Club, các thuật toán này vẫn bộc lộ hạn chế về tốc độ xử lý, khả năng hội tụ và chất lượng nghiệm khi áp dụng trên đồ thị lớn.\n\n- Nhằm khắc phục vấn đề này, nhiều hướng tiếp cận mới đã được đề xuất, kết hợp giữa các kỹ thuật tối ưu hóa tiến hóa (Evolutionary Optimization) như thuật toán di truyền (Genetic Algorithm – GA), thuật toán tối ưu bầy đàn (Particle Swarm Optimization – PSO), hay thuật toán heuristic tham lam (Greedy). Sự kết hợp này mang lại hiệu quả tích cực: vừa đảm bảo tốc độ, vừa nâng cao chất lượng nghiệm, đồng thời dễ dàng thích ứng với các đặc thù khác nhau của đồ thị.\n\n- Tại Việt Nam, lĩnh vực nghiên cứu này vẫn còn khá mới mẻ. Một số nhóm nghiên cứu, điển hình là nhóm nghiên cứu PGS.TS. Huỳnh Thị Thanh Bình (Trường Công nghệ Thông tin và Truyền thông, Đại học Bách khoa Hà Nội), đã có những công trình đáng chú ý về thuật toán tiến hóa quần thể (population-based algorithms) và ứng dụng vào các bài toán tối ưu đồ thị như: Clustered Minimum Routing Cost Problem hay Clustered Shortest Path Tree Problem. Tuy nhiên, cho đến nay, chưa có công trình nào đi sâu vào bài toán s-Club.\n\n- Từ thực tế trên có thể khẳng định rằng, đề tài “Nghiên cứu các thuật toán gần đúng giải các bài toán s-Club” không chỉ mang tính cấp thiết về mặt học thuật mà còn có giá trị ứng dụng thực tiễn sâu sắc. Việc nghiên cứu và đề xuất các thuật toán gần đúng, đặc biệt là dựa trên mô hình tiến hóa nhóm, không chỉ góp phần mở rộng lý thuyết tối ưu hóa đồ thị mà còn giúp tạo nền tảng cho việc ứng dụng vào các lĩnh vực phân tích mạng xã hội, phát hiện cộng đồng, tối ưu hóa mạng cảm biến hoặc xử lý dữ liệu lớn.\n\n- Kết quả nghiên cứu kỳ vọng sẽ giúp nâng cao hiệu quả của các ứng dụng phân tích mạng thông minh, đồng thời đóng góp vào việc phát triển thuật toán tối ưu trong lĩnh vực khoa học máy tính và trí tuệ nhân tạo tại Việt Nam.",
            'muc_tieu' => "- Hiểu và trình bày được khái niệm bài toán s-Club trong lý thuyết đồ thị, mối quan hệ giữa s-Club, k-Club và Clique.\n- Tìm hiểu và cài đặt thử nghiệm một số thuật toán gần đúng (heuristic) phổ biến giải bài toán s-Club như DROP, CONSTELLATION.\n- Xây dựng và thử nghiệm phiên bản thuật toán gần đúng để giải bài toán s-Club.\n- Đánh giá so sánh hiệu quả (độ chính xác và thời gian chạy) giữa thuật toán nhóm xây dựng với các thuật toán đã có.\n- Viết báo cáo và bài viết khoa học, trình bày kết quả thử nghiệm và hướng phát triển sau.",
            'noi_dung_chu_yeu' => [
                [
                    'tieu_de' => '4.1. Nội dung 1: Tìm hiểu lý thuyết và các thuật toán cơ bản',
                    'chi_tiet' => "- Tìm hiểu khái niệm đồ thị, các mô hình bài toán k-Club và s-Club.\n- Nắm vững nguyên lý của các thuật toán gần đúng như CONSTELLATION, DROP và IDROP, …\n- Tổng hợp tài liệu, mô tả quy trình hoạt động và độ phức tạp của các thuật toán trên.\n- Cài đặt thử nghiệm các thuật toán này bằng Python hoặc Java trên các bộ dữ liệu nhỏ (đồ thị từ 10–50 đỉnh).\n- Phân tích ưu nhược điểm và chọn hướng tiếp cận phù hợp cho nhóm (dựa vào tài nguyên, thời gian)."
                ],
                [
                    'tieu_de' => '4.2. Nội dung 2: Đề xuất và đánh giá thuật toán di truyền nhóm (GGA)',
                    'chi_tiet' => "- Đề xuất ý tưởng áp dụng thuật toán gần đúng vào bài toán s-Club.\n- Thực nghiệm trên cùng bộ dữ liệu, so sánh kết quả với các thuật toán.\n- Ghi nhận kết quả, đánh giá và rút ra nhận xét về hiệu quả, hạn chế."
                ]
            ],
            'du_kien_san_pham' => "- 01 Báo cáo phân tích và tổng hợp lý thuyết\n- 01 Báo cáo tổng kết đề tài\n- 01 Chương trình mô phỏng cài đặt thuật toán\n- 01 Bộ dữ liệu thử nghiệm và tài liệu hướng dẫn có thể dùng cho giảng dạy và nghiên cứu trong lĩnh vực tối ưu hóa đồ thị",
            'kha_nang_dia_chi' => [
                'doi_tuong' => 'Giảng viên, sinh viên và nhà nghiên cứu trong lĩnh vực Công nghệ thông tin, Trí tuệ nhân tạo và Tối ưu hóa.',
                'dia_chi' => 'Khoa Công nghệ Thông tin, Trường Đại học Tây Bắc, Sơn La.',
                'kha_nang_mo_rong' => 'Kết quả nghiên cứu có thể áp dụng cho các bài toán phân tích mạng xã hội, tối ưu mạng cảm biến, phát hiện cộng đồng trong dữ liệu lớn hoặc bài toán phân vùng đồ thị.'
            ],
            'xac_nhan_gvhd' => 'TS. Phạm Đình Thành',
            'ca_nhan_de_xuat' => 'Lò Mạnh Đạt'
        ];
    }

    /**
     * Dữ liệu mẫu chuẩn từ Thuyết Minh Đề Tài (ĐH Tây Bắc - Sơn La)
     */
    public static function getSampleThuyetMinhData(): array {
        return [
            'co_quan_chu_quan' => 'BỘ GIÁO DỤC VÀ ĐÀO TẠO',
            'don_vi' => 'TRƯỜNG ĐẠI HỌC TÂY BẮC',
            'dia_diem_ngay' => 'Sơn La, ngày 20 tháng 10 năm 2025',
            'nam_hoc' => '2025 - 2026',
            'ten_de_tai' => 'Nghiên cứu các thuật toán gần đúng giải các bài toán s-Club',
            'loai_hinh_nc' => 'CO_BAN', // CO_BAN, UNG_DUNG, TRIEN_KHAI
            'nhom_nganh' => 'Công nghệ thông tin',
            'thoi_gian_thuc_hien' => 'Từ tháng 10 năm 2025 đến tháng 11 năm 2026',
            'chu_nhiem' => [
                'ho_ten' => 'Lò Mạnh Đạt',
                'lop' => 'K64 ĐH CNTT-B',
                'dien_thoai' => '0982 943 664'
            ],
            'thanh_vien_tham_gia' => [
                [
                    'tt' => 1,
                    'ho_ten' => 'Lò Mạnh Đạt',
                    'lop' => 'K64 ĐH CNTT-B',
                    'cong_viec' => 'Trưởng nhóm, tổng hợp lý thuyết, xây dựng mô hình mô phỏng thuật toán.',
                    'chu_ky' => 'Đã ký'
                ],
                [
                    'tt' => 2,
                    'ho_ten' => 'Nguyễn Huy Hoàng',
                    'lop' => 'K64 ĐH CNTT-B',
                    'cong_viec' => 'Nghiên cứu và cài đặt các thuật toán gần đúng.',
                    'chu_ky' => 'Đã ký'
                ],
                [
                    'tt' => 3,
                    'ho_ten' => 'Tòng Lưu Anh Tú',
                    'lop' => 'K64 ĐH CNTT-B',
                    'cong_viec' => 'Thực hiện mô phỏng, kiểm thử thuật toán và đánh giá kết quả.',
                    'chu_ky' => 'Đã ký'
                ],
                [
                    'tt' => 4,
                    'ho_ten' => 'Phạm Thị Thanh Hảo',
                    'lop' => 'K64 ĐH CNTT-B',
                    'cong_viec' => 'Thu thập tài liệu, hỗ trợ nhập dữ liệu và trình bày báo cáo, biểu đồ kết quả.',
                    'chu_ky' => 'Đã ký'
                ]
            ],
            'gvhd' => [
                'ho_ten' => 'Phạm Đình Thành',
                'chuc_danh_hoc_vi' => 'Tiến sĩ',
                'dien_thoai' => '0988 811 127'
            ],
            'tinh_hinh_nc' => [
                'tong_quan' => "- Trong hơn hai thập kỷ qua, các bài toán tối ưu trên đồ thị đã trở thành chủ đề nghiên cứu được quan tâm trong lĩnh vực Khoa học máy tính, đặc biệt là các bài toán phát hiện cộng đồng, phân cụm và xác định nhóm đỉnh có liên kết chặt chẽ trong mạng xã hội, mạng sinh học hay mạng truyền thông. Trong số đó, bài toán k-Club và s-Club đóng vai trò nền tảng trong mô hình hóa các nhóm đỉnh có khoảng cách tối đa giữa các đỉnh không vượt quá một giá trị cho trước.\n- Trên thế giới, nhiều công trình đã tập trung giải quyết bài toán s-Club bằng các thuật toán gần đúng hoặc thuật toán heuristic nhằm rút ngắn thời gian tìm kiếm nghiệm gần tối ưu.\n- Các hướng tiếp cận tiêu biểu gồm: CONSTELLATION và DROP [1] ,[2], áp dụng chiến lược tìm kiếm lặp và loại bỏ cạnh không cần thiết để tối ưu kích thước tập đỉnh, tuy nhiên hiệu suất giảm mạnh với đồ thị lớn. IDROP [2] cải tiến DROP bằng việc sử dụng cấu trúc dữ liệu động, giúp tăng tốc độ hội tụ. CCA (Cluster-Constrained Algorithm) [3] khai thác heuristic dựa trên khoảng cách để giới hạn không gian tìm kiếm, đạt kết quả khả quan nhưng vẫn bị giới hạn bởi bộ nhớ và độ phức tạp O(n³).\n- Gần đây, xu hướng kết hợp các thuật toán heuristic truyền thống với các mô hình tiến hóa (Evolutionary Algorithms) như Genetic Algorithm (GA), Particle Swarm Optimization (PSO) và Greedy Adaptive Search Procedure (GRASP) đã chứng minh hiệu quả vượt trội. Các nghiên cứu như [4] cho thấy việc lai ghép giữa tìm kiếm tham lam và di truyền giúp cải thiện đáng kể chất lượng nghiệm và tốc độ hội tụ.\n- Tại Việt Nam, hướng nghiên cứu này còn tương đối mới. Một số nhóm tại Trường Đại học Bách Khoa Hà Nội dưới sự hướng dẫn của PGS.TS. Huỳnh Thị Thanh Bình đã công bố nhiều công trình về tối ưu quần thể và ứng dụng vào các bài toán đồ thị phức tạp như Clustered Minimum Routing Cost Problem, Clustered Shortest Path Tree Problem, hay Multidomain Network Optimization [5]. Tuy nhiên, các nghiên cứu tập trung chủ yếu vào bài toán định tuyến hoặc phân cụm, chưa có đề tài nào chuyên sâu về bài toán s-Club và các thuật toán gần đúng ứng dụng trong lĩnh vực này.\n- Do đó, việc nghiên cứu, mô phỏng và đánh giá các thuật toán gần đúng giải bài toán s-Club không chỉ góp phần làm rõ hiệu quả của các hướng heuristic hiện có, mà còn mở ra khả năng ứng dụng thực tế trong việc phát hiện cộng đồng và tối ưu hóa mạng lưới dữ liệu.",
                'cong_trinh_lien_quan' => "[1] J.-M. Bourjolly, G. Laporte, and G. Pesant, “An exact algorithm for the maximum k-club problem in graphs,” Computers & Operations Research, vol. 29, no. 4, pp. 479–490, 2002.\n[2] R.-S. Chang, C.-Y. Lee, and C.-L. Hsieh, “Improved DROP heuristic for maximum k-club detection,” Information Sciences, vol. 241, pp. 97–108, 2013.\n[3] R. Dondi, F. Sikora, and G. Valentina, “Heuristic algorithms for s-club identification,” Applied Soft Computing, vol. 81, pp. 105–119, 2019.\n[4] A. Sánchez-Caballé, J. Gisbert, and M. Esteve, “Metaheuristic optimization in social network clustering,” Expert Systems with Applications, vol. 140, pp. 112–123, 2020.\n[5] H. T. T. Bình, T. T. Vân, and P. T. H. Linh, “Population-based algorithms for clustered graph problems,” Proceedings of the Hanoi University of Science and Technology, 2018–2022."
            ],
            'tinh_cap_thiet' => "- Trong thời đại công nghiệp 4.0, khi trí tuệ nhân tạo (AI), dữ liệu lớn (Big Data) và các mô hình tính toán thông minh đang dần trở thành nền tảng cho sự phát triển của khoa học công nghệ, việc nghiên cứu các bài toán tối ưu trên đồ thị (graph optimization problems) ngày càng trở nên quan trọng và cấp thiết. Đồ thị không chỉ là mô hình toán học biểu diễn các mối quan hệ giữa các đối tượng mà còn là công cụ hữu hiệu để mô phỏng, phân tích và tối ưu hóa trong nhiều lĩnh vực như mạng xã hội, sinh học, truyền thông, logistics, và cả các hệ thống ra quyết định tự động.\n- Khi xã hội ngày càng dựa nhiều hơn vào dữ liệu và kết nối, việc hiểu rõ cấu trúc liên kết giữa các phần tử trong mạng đóng vai trò then chốt trong việc phát hiện quy luật, dự đoán hành vi, hay phát triển các mô hình phân tích cộng đồng thông minh.\n- Trong bối cảnh đó, các bài toán như s-Clique, s-Club nổi lên như những công cụ mạnh mẽ giúp xác định các nhóm đỉnh có liên kết chặt chẽ trong đồ thị. Nếu như bài toán clique chỉ xem xét các mối liên hệ trực tiếp giữa các đỉnh, thì s-Club cho phép mở rộng khái niệm này bằng cách xét các đỉnh có khoảng cách không vượt quá s. Chính điều này giúp phản ánh chính xác hơn các mối quan hệ thực tế, nơi mà sự gắn kết giữa các đối tượng có thể thông qua một hoặc vài trung gian tương tự như cách con người trong mạng xã hội có thể kết nối gián tiếp nhưng vẫn thuộc cùng một “cộng đồng”.\n- Sự khác biệt đó khiến bài toán s-Club trở thành một công cụ phân tích hữu ích trong nhiều ứng dụng hiện đại như phát hiện nhóm người có ảnh hưởng trong mạng xã hội, tối ưu hóa luồng thông tin trong mạng truyền thông, hay nhận diện các cụm gene tương đồng trong mạng sinh học.\n- Tuy nhiên, các bài toán thuộc lớp s-Club lại nằm trong nhóm NP-Khó (NP-Hard) nghĩa là không thể tìm được lời giải chính xác trong thời gian ngắn khi kích thước đồ thị tăng lớn. Với những đồ thị thực tế chứa hàng nghìn hoặc hàng triệu đỉnh, việc tìm lời giải tối ưu bằng các phương pháp truyền thống là bất khả thi. Chính vì vậy, việc nghiên cứu và xây dựng các thuật toán gần đúng (approximation algorithms) hoặc các chiến lược heuristic, meta-heuristic là hướng đi cần thiết để đạt được nghiệm tốt trong thời gian hợp lý.\n- Trên thế giới, nhiều công trình đã tập trung nghiên cứu hướng này. Các thuật toán tiêu biểu như CONSTELLATION,DROP, hay k-CLIQUE&DROP được Bourjolly, (Laporte và Pesant 2000, 2002) đề xuất đã cho thấy khả năng tìm nghiệm gần đúng hiệu quả cho bài toán maximum k-club. Dẫu vậy, khi mở rộng sang các biến thể phức tạp hơn như s-Club, các thuật toán này vẫn bộc lộ hạn chế về tốc độ xử lý, khả năng hội tụ và chất lượng nghiệm khi áp dụng trên đồ thị lớn.\n- Nhằm khắc phục vấn đề này, nhiều hướng tiếp cận mới đã được đề xuất, kết hợp giữa các kỹ thuật tối ưu hóa tiến hóa (Evolutionary Optimization) như thuật toán di truyền (Genetic Algorithm – GA), thuật toán tối ưu bầy đàn (Particle Swarm Optimization – PSO), hay thuật toán heuristic tham lam (Greedy). Sự kết hợp này mang lại hiệu quả tích cực: vừa đảm bảo tốc độ, vừa nâng cao chất lượng nghiệm, đồng thời dễ dàng thích ứng với các đặc thù khác nhau của đồ thị.\n- Tại Việt Nam, lĩnh vực nghiên cứu này vẫn còn khá mới mẻ. Một số nhóm nghiên cứu, điển hình là nhóm nghiên cứu PGS.TS. Huỳnh Thị Thanh Bình (Trường Công nghệ Thông tin và Truyền thông, Đại học Bách khoa Hà Nội), đã có những công trình đáng chú ý về thuật toán tiến hóa quần thể (population-based algorithms) và ứng dụng vào các bài toán tối ưu đồ thị như: Clustered Minimum Routing Cost Problem hay Clustered Shortest Path Tree Problem.Tuy nhiên, cho đến nay, chưa có công trình nào đi sâu vào bài toán s-Club.\n- Từ thực tế trên có thể khẳng định rằng, đề tài “Nghiên cứu các thuật toán gần đúng giải các bài toán s-Club” không chỉ mang tính cấp thiết về mặt học thuật mà còn có giá trị ứng dụng thực tiễn sâu sắc. Việc nghiên cứu và đề xuất các thuật toán gần đúng, đặc biệt là dựa trên mô hình tiến hóa nhóm, không chỉ góp phần mở rộng lý thuyết tối ưu hóa đồ thị mà còn giúp tạo nền tảng cho việc ứng dụng vào các lĩnh vực phân tích mạng xã hội, phát hiện cộng đồng, tối ưu hóa mạng cảm biến hoặc xử lý dữ liệu lớn.\n- Kết quả nghiên cứu kỳ vọng sẽ giúp nâng cao hiệu quả của các ứng dụng phân tích mạng thông minh, đồng thời đóng góp vào việc phát triển thuật toán tối ưu trong lĩnh vực khoa học máy tính và trí tuệ nhân tạo tại Việt Nam.",
            'muc_tieu' => [
                'muc_tieu_chung' => 'Đề tài được thực hiện với mục tiêu chung là nghiên cứu, cài đặt và đánh giá hiệu quả của các thuật toán gần đúng trong việc giải quyết bài toán s-Club trên đồ thị, từ đó đề xuất một hướng tiếp cận tối ưu hơn dựa trên thuật toán tiến hóa.',
                'muc_tieu_cu_the' => "(1) Trình bày được cơ sở lý thuyết của bài toán s-Club trong lý thuyết đồ thị, làm rõ mối quan hệ giữa các khái niệm clique, k-club và s-club, cũng như các đặc điểm về độ phức tạp tính toán của bài toán này.\n(2) Nghiên cứu, tổng hợp và hiểu rõ nguyên lý hoạt động của các thuật toán gần đúng (heuristic và meta-heuristic) phổ biến đã được công bố, bao gồm CONSTELLATION, DROP, IDROP và GRASP,... nhằm xác định ưu nhược điểm của từng phương pháp.\n(3) Cài đặt và thử nghiệm thực tế các thuật toán nói trên trên các bộ dữ liệu đồ thị mẫu, đánh giá hiệu quả dựa trên các tiêu chí: độ chính xác, tốc độ hội tụ và khả năng mở rộng.\n(4) Tìm hiểu, cải tiến thuật toán gần đúng, nhằm cải thiện hiệu quả giải quyết bài toán s-Club.\n(5) Thực nghiệm, đối chiếu và rút ra kết luận về khả năng ứng dụng của từng thuật toán, đồng thời hoàn thiện báo cáo tổng kết"
            ],
            'doi_tuong_pham_vi_phuong_phap' => [
                'doi_tuong' => '- Các thuật toán gần đúng (heuristic, meta-heuristic) dùng để giải bài toán s-Club trên đồ thị vô hướng.',
                'pham_vi' => "- Nghiên cứu và mô phỏng trên các đồ thị có từ 10–50 đỉnh.\n- Sử dụng ngôn ngữ C# hoặc Java, dữ liệu tạo ngẫu nhiên hoặc từ thư viện NetworkX/SNAP.\n- Tập trung đánh giá độ chính xác và thời gian chạy, không đi sâu chứng minh lý thuyết.",
                'phuong_phap' => "- Tổng hợp lý thuyết: Thu thập, phân tích tài liệu trong và ngoài nước.\n- Mô phỏng – thực nghiệm: Cài đặt, chạy thử các thuật toán DROP, IDROP, GRASP.\n- Thống kê – so sánh: Phân tích kết quả bằng bảng, biểu đồ; đánh giá theo tiêu chí tốc độ và chất lượng nghiệm"
            ],
            'tien_do_thuc_hien' => [
                [
                    'tt' => 1,
                    'noi_dung' => 'Tổng quan tài liệu, tìm hiểu bài toán s-Club và các thuật toán gần đúng',
                    'san_pham' => 'Báo cáo tổng hợp',
                    'thoi_gian' => 'Tháng 10/2025 đến tháng 12/2025',
                    'nguoi_thuc_hien' => 'Nhóm'
                ],
                [
                    'tt' => 2,
                    'noi_dung' => 'Nghiên cứu, cài đặt thử nghiệm các thuật toán CONSTELLATION, DROP, IDROP, GRASP',
                    'san_pham' => 'Mã nguồn và bản mô tả thuật toán',
                    'thoi_gian' => 'Đầu tháng 01/2026 đến tháng 04/2026',
                    'nguoi_thuc_hien' => 'Nhóm'
                ],
                [
                    'tt' => 3,
                    'noi_dung' => 'Thiết kế và mô phỏng thuật toán',
                    'san_pham' => 'Mã nguồn và báo cáo kết quả mô phỏng',
                    'thoi_gian' => 'Đầu tháng 05/2026 đến tháng 09/2026',
                    'nguoi_thuc_hien' => 'Nhóm'
                ],
                [
                    'tt' => 4,
                    'noi_dung' => 'Viết báo cáo tổng kết, chỉnh sửa và nộp đề tài',
                    'san_pham' => 'Báo cáo hoàn chỉnh',
                    'thoi_gian' => 'Đầu tháng 10/2026',
                    'nguoi_thuc_hien' => 'Nhóm'
                ]
            ],
            'san_pham_ung_dung' => [
                'loai_san_pham' => ['Báo cáo phân tích', 'Phần mềm máy tính'],
                'danh_sach' => [
                    [
                        'tt' => 1,
                        'ten_san_pham' => 'Báo cáo tổng kết đề tài “Nghiên cứu các thuật toán gần đúng giải các bài toán s-Club”',
                        'so_luong' => '01',
                        'yeu_cau_khoa_hoc' => 'Có đầy đủ cơ sở lý thuyết, kết quả thực nghiệm, biểu đồ và đánh giá khoa học.'
                    ],
                    [
                        'tt' => 2,
                        'ten_san_pham' => 'Chương trình mô phỏng cài đặt thuật toán giải bài toán s-Club',
                        'so_luong' => '01',
                        'yeu_cau_khoa_hoc' => 'Chạy ổn định trên máy tính, có giao diện trực quan và hướng dẫn sử dụng.'
                    ],
                    [
                        'tt' => 3,
                        'ten_san_pham' => 'Bộ dữ liệu đồ thị mẫu và kết quả thực nghiệm so sánh',
                        'so_luong' => '01',
                        'yeu_cau_khoa_hoc' => 'Đầy đủ tập mẫu từ 10 đến 50 đỉnh kèm kết quả so sánh độ chính xác và thời gian.'
                    ]
                ],
                'dia_chi_ap_dung' => "+ Khoa Công nghệ Thông tin, Trường Đại học Tây Bắc, tỉnh Sơn La.\n+ Kết quả nghiên cứu có thể ứng dụng trong giảng dạy và nghiên cứu các học phần, chuyên đề liên quan đến thuật toán tối ưu, trí tuệ nhân tạo và khoa học máy tính tại Trường Đại học Tây Bắc."
            ],
            'ngay_ky_gvhd' => 'Ngày 20 tháng 10 năm 2025',
            'gvhd_ky' => 'TS. Phạm Đình Thành',
            'ngay_ky_sv' => 'Ngày 20 tháng 10 năm 2026',
            'sv_ky' => 'Lò Mạnh Đạt'
        ];
    }

    /**
     * Dữ liệu khởi tạo trống cho Phiếu Đề Xuất
     */
    public static function getEmptyDeXuatData(?array $topic = null): array {
        return [
            'co_quan_chu_quan' => 'BỘ GIÁO DỤC VÀ ĐÀO TẠO',
            'don_vi' => 'TRƯỜNG ĐẠI HỌC TÂY BẮC',
            'dia_diem_ngay' => 'Sơn La, ngày ' . date('d') . ' tháng ' . date('m') . ' năm ' . date('Y'),
            'ten_de_tai' => $topic['TenDeTai'] ?? '',
            'tinh_cap_thiet' => '',
            'muc_tieu' => $topic['MucTieu'] ?? '',
            'noi_dung_chu_yeu' => [
                [
                    'tieu_de' => '4.1. Nội dung 1: Tìm hiểu lý thuyết và khảo sát hiện trạng',
                    'chi_tiet' => $topic['NoiDungNC'] ?? ''
                ],
                [
                    'tieu_de' => '4.2. Nội dung 2: Xây dựng giải pháp và thực nghiệm',
                    'chi_tiet' => ''
                ]
            ],
            'du_kien_san_pham' => $topic['SanPhamDuKien'] ?? '',
            'kha_nang_dia_chi' => [
                'doi_tuong' => 'Giảng viên, sinh viên Trường Đại học Tây Bắc',
                'dia_chi' => 'Trường Đại học Tây Bắc (Sơn La)',
                'kha_nang_mo_rong' => 'Có khả năng ứng dụng thực tế và mở rộng quy mô'
            ],
            'xac_nhan_gvhd' => $topic['TenGVHD'] ?? '',
            'ca_nhan_de_xuat' => ''
        ];
    }

    /**
     * Dữ liệu khởi tạo trống cho Thuyết Minh Đề Tài
     */
    public static function getEmptyThuyetMinhData(?array $topic = null): array {
        return [
            'co_quan_chu_quan' => 'BỘ GIÁO DỤC VÀ ĐÀO TẠO',
            'don_vi' => 'TRƯỜNG ĐẠI HỌC TÂY BẮC',
            'dia_diem_ngay' => 'Sơn La, ngày ' . date('d') . ' tháng ' . date('m') . ' năm ' . date('Y'),
            'nam_hoc' => $topic['NamHoc'] ?? (date('Y') . ' - ' . (date('Y') + 1)),
            'ten_de_tai' => $topic['TenDeTai'] ?? '',
            'loai_hinh_nc' => 'UNG_DUNG',
            'nhom_nganh' => 'Công nghệ thông tin',
            'thoi_gian_thuc_hien' => 'Từ tháng ' . date('m') . ' năm ' . date('Y') . ' đến tháng ' . date('m') . ' năm ' . (date('Y') + 1),
            'chu_nhiem' => [
                'ho_ten' => '',
                'lop' => '',
                'dien_thoai' => ''
            ],
            'thanh_vien_tham_gia' => [],
            'gvhd' => [
                'ho_ten' => $topic['TenGVHD'] ?? '',
                'chuc_danh_hoc_vi' => ($topic['HocHam'] ?? '') . ' ' . ($topic['HocVi'] ?? ''),
                'dien_thoai' => $topic['SdtGVHD'] ?? ''
            ],
            'tinh_hinh_nc' => [
                'tong_quan' => '',
                'cong_trinh_lien_quan' => ''
            ],
            'tinh_cap_thiet' => '',
            'muc_tieu' => [
                'muc_tieu_chung' => $topic['MucTieu'] ?? '',
                'muc_tieu_cu_the' => ''
            ],
            'doi_tuong_pham_vi_phuong_phap' => [
                'doi_tuong' => '',
                'pham_vi' => '',
                'phuong_phap' => ''
            ],
            'tien_do_thuc_hien' => [
                [
                    'tt' => 1,
                    'noi_dung' => 'Tổng quan lý thuyết và xây dựng đề cương',
                    'san_pham' => 'Báo cáo tổng quan',
                    'thoi_gian' => 'Tháng 1 - Tháng 2',
                    'nguoi_thuc_hien' => 'Nhóm nghiên cứu'
                ],
                [
                    'tt' => 2,
                    'noi_dung' => 'Triển khai nghiên cứu thực nghiệm và xây dựng mô hình',
                    'san_pham' => 'Phần mềm / Sản phẩm mẫu',
                    'thoi_gian' => 'Tháng 3 - Tháng 6',
                    'nguoi_thuc_hien' => 'Nhóm nghiên cứu'
                ],
                [
                    'tt' => 3,
                    'noi_dung' => 'Đánh giá kết quả, viết báo cáo tổng kết và nghiệm thu',
                    'san_pham' => 'Báo cáo hoàn chỉnh',
                    'thoi_gian' => 'Tháng 7 - Tháng 8',
                    'nguoi_thuc_hien' => 'Nhóm nghiên cứu'
                ]
            ],
            'san_pham_ung_dung' => [
                'loai_san_pham' => ['Báo cáo phân tích', 'Phần mềm máy tính'],
                'danh_sach' => [
                    [
                        'tt' => 1,
                        'ten_san_pham' => 'Báo cáo tổng kết đề tài',
                        'so_luong' => '01',
                        'yeu_cau_khoa_hoc' => 'Đầy đủ nội dung, kết quả thực nghiệm và đúng quy cách'
                    ]
                ],
                'dia_chi_ap_dung' => 'Trường Đại học Tây Bắc (Sơn La)'
            ],
            'ngay_ky_gvhd' => 'Ngày ' . date('d') . ' tháng ' . date('m') . ' năm ' . date('Y'),
            'gvhd_ky' => $topic['TenGVHD'] ?? '',
            'ngay_ky_sv' => 'Ngày ' . date('d') . ' tháng ' . date('m') . ' năm ' . date('Y'),
            'sv_ky' => ''
        ];
    }

    /**
     * Phân giải dữ liệu JSON Phiếu Đề Xuất an toàn
     */
    public static function parseDeXuatData(?string $json, ?array $topic = null, array $members = []): array {
        $data = [];
        if (!empty($json)) {
            $decoded = json_decode($json, true);
            if (is_array($decoded) && !empty($decoded['ten_de_tai'])) {
                $data = $decoded;
            }
        }
        $result = array_replace_recursive(self::getEmptyDeXuatData($topic), $data);
        if (empty($result['ca_nhan_de_xuat']) && !empty($members)) {
            foreach ($members as $m) {
                if (($m['VaiTro'] ?? '') === 'TRUONG_NHOM') {
                    $result['ca_nhan_de_xuat'] = $m['HoTen'] ?? '';
                    break;
                }
            }
        }
        return $result;
    }

    /**
     * Phân giải dữ liệu JSON Thuyết Minh an toàn
     */
    public static function parseThuyetMinhData(?string $json, ?array $topic = null, array $members = []): array {
        $data = [];
        if (!empty($json)) {
            $decoded = json_decode($json, true);
            if (is_array($decoded) && !empty($decoded['ten_de_tai'])) {
                $data = $decoded;
            }
        }
        $result = array_replace_recursive(self::getEmptyThuyetMinhData($topic), $data);
        
        // Tự động điền danh sách thành viên nếu trong JSON chưa có nhưng DB có danh sách
        if (empty($result['thanh_vien_tham_gia']) && !empty($members)) {
            $result['thanh_vien_tham_gia'] = [];
            foreach ($members as $idx => $m) {
                $result['thanh_vien_tham_gia'][] = [
                    'tt' => $idx + 1,
                    'ho_ten' => $m['HoTen'] ?? '',
                    'lop' => $m['Lop'] ?? '',
                    'cong_viec' => $m['NhiemVuCuThe'] ?? (($m['VaiTro'] ?? '') === 'TRUONG_NHOM' ? 'Chủ nhiệm đề tài, điều phối chung' : 'Nghiên cứu và thực hiện'),
                    'chu_ky' => 'Đã ký'
                ];
                if (($m['VaiTro'] ?? '') === 'TRUONG_NHOM' && empty($result['chu_nhiem']['ho_ten'])) {
                    $result['chu_nhiem']['ho_ten'] = $m['HoTen'] ?? '';
                    $result['chu_nhiem']['lop'] = $m['Lop'] ?? '';
                    $result['chu_nhiem']['dien_thoai'] = $m['SoDienThoai'] ?? '';
                    if (empty($result['sv_ky'])) {
                        $result['sv_ky'] = $m['HoTen'] ?? '';
                    }
                }
            }
        }
        return $result;
    }

    /**
     * Render HTML trực quan chuẩn văn bản hành chính cho Phiếu Đề Xuất (Mẫu 02)
     */
    public static function renderPhieuDeXuatHtml(array $data, bool $isPrintMode = false): string {
        ob_start();
        ?>
        <div class="nckh-doc-wrapper <?= $isPrintMode ? 'is-print-mode' : '' ?>">
            <!-- HEADER QUỐC HIỆU VÀ ĐƠN VỊ -->
            <table class="nckh-doc-table-header">
                <tr>
                    <td style="width: 48%; text-align: center; vertical-align: top;">
                        <div style="margin-bottom: 6px;">
                            <img src="<?= \App\Config\App::url('pic/logo.jpg') ?>" alt="Logo ĐH Tây Bắc" onerror="this.onerror=null; this.src='<?= \App\Config\App::url('public/images/logo.jpg') ?>';" style="width: 52px; height: 52px; border-radius: 50%; object-fit: cover; display: inline-block;">
                        </div>
                        <div class="nckh-co-quan"><?= ViewHelper::e($data['co_quan_chu_quan'] ?? 'BỘ GIÁO DỤC VÀ ĐÀO TẠO') ?></div>
                        <div class="nckh-don-vi"><?= ViewHelper::e($data['don_vi'] ?? 'TRƯỜNG ĐẠI HỌC TÂY BẮC') ?></div>
                        <div class="nckh-divider"></div>
                    </td>
                    <td style="width: 4%;"></td>
                    <td style="width: 48%; text-align: center; vertical-align: top;">
                        <div class="nckh-quoc-hieu">CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM</div>
                        <div class="nckh-tieu-ngu">Độc lập - Tự do - Hạnh phúc</div>
                        <div class="nckh-divider"></div>
                        <div class="nckh-ngay-thang"><?= ViewHelper::e($data['dia_diem_ngay'] ?? 'Sơn La, ngày ... tháng ... năm ...') ?></div>
                    </td>
                </tr>
            </table>

            <!-- TIÊU ĐỀ BIỂU MẪU -->
            <div class="nckh-doc-title-block">
                <h1 class="nckh-doc-main-title">PHIẾU ĐỀ XUẤT</h1>
                <h2 class="nckh-doc-sub-title">ĐỀ TÀI NGHIÊN CỨU KHOA HỌC CỦA SINH VIÊN</h2>
            </div>

            <!-- NỘI DUNG 6 MỤC CHUẨN -->
            <div class="nckh-doc-content">
                <!-- MỤC 1 -->
                <div class="nckh-section">
                    <h3 class="nckh-section-title">1. Tên đề tài:</h3>
                    <div class="nckh-section-body nckh-highlight-box">
                        <strong><?= ViewHelper::e(!empty($data['ten_de_tai']) ? $data['ten_de_tai'] : '(Chưa nhập tên đề tài)') ?></strong>
                    </div>
                </div>

                <!-- MỤC 2 -->
                <div class="nckh-section">
                    <h3 class="nckh-section-title">2. Giải trình về tính cấp thiết:</h3>
                    <div class="nckh-section-body nckh-text-justified">
                        <?= !empty($data['tinh_cap_thiet']) ? nl2br(ViewHelper::e($data['tinh_cap_thiet'])) : '<em class="text-muted">(Chưa cập nhật nội dung)</em>' ?>
                    </div>
                </div>

                <!-- MỤC 3 -->
                <div class="nckh-section">
                    <h3 class="nckh-section-title">3. Mục tiêu dự kiến của đề tài:</h3>
                    <div class="nckh-section-body nckh-text-justified">
                        <?= !empty($data['muc_tieu']) ? nl2br(ViewHelper::e($data['muc_tieu'])) : '<em class="text-muted">(Chưa cập nhật nội dung)</em>' ?>
                    </div>
                </div>

                <!-- MỤC 4 -->
                <div class="nckh-section">
                    <h3 class="nckh-section-title">4. Nội dung chủ yếu của đề tài:</h3>
                    <div class="nckh-section-body">
                        <?php if (!empty($data['noi_dung_chu_yeu']) && is_array($data['noi_dung_chu_yeu'])): ?>
                            <?php foreach ($data['noi_dung_chu_yeu'] as $nd): ?>
                                <div class="nckh-sub-section">
                                    <h4 class="nckh-sub-section-title"><?= ViewHelper::e($nd['tieu_de'] ?? '') ?></h4>
                                    <div class="nckh-text-justified" style="padding-left: 16px;">
                                        <?= nl2br(ViewHelper::e($nd['chi_tiet'] ?? '')) ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <em class="text-muted">(Chưa cập nhật nội dung)</em>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- MỤC 5 -->
                <div class="nckh-section">
                    <h3 class="nckh-section-title">5. Dự kiến sản phẩm của đề tài:</h3>
                    <div class="nckh-section-body nckh-text-justified">
                        <?= !empty($data['du_kien_san_pham']) ? nl2br(ViewHelper::e($data['du_kien_san_pham'])) : '<em class="text-muted">(Chưa cập nhật nội dung)</em>' ?>
                    </div>
                </div>

                <!-- MỤC 6 -->
                <div class="nckh-section">
                    <h3 class="nckh-section-title">6. Khả năng và địa chỉ áp dụng:</h3>
                    <div class="nckh-section-body" style="padding-left: 8px;">
                        <p style="margin-bottom: 6px;">
                            <strong>- Đối tượng áp dụng:</strong>
                            <span><?= ViewHelper::e($data['kha_nang_dia_chi']['doi_tuong'] ?? '') ?></span>
                        </p>
                        <p style="margin-bottom: 6px;">
                            <strong>- Địa chỉ áp dụng:</strong>
                            <span><?= ViewHelper::e($data['kha_nang_dia_chi']['dia_chi'] ?? '') ?></span>
                        </p>
                        <p style="margin-bottom: 6px;">
                            <strong>- Khả năng mở rộng:</strong>
                            <span><?= ViewHelper::e($data['kha_nang_dia_chi']['kha_nang_mo_rong'] ?? '') ?></span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- CHỮ KÝ XÁC NHẬN -->
            <table class="nckh-signature-table">
                <tr>
                    <td style="width: 50%; text-align: center; vertical-align: top;">
                        <div class="nckh-sig-role">Xác nhận của Giảng viên hướng dẫn</div>
                        <div class="nckh-sig-note"><em>(Ký và ghi rõ họ tên)</em></div>
                        <div class="nckh-sig-space"></div>
                        <div class="nckh-sig-name"><?= ViewHelper::e(!empty($data['xac_nhan_gvhd']) ? $data['xac_nhan_gvhd'] : 'TS. Phạm Đình Thành') ?></div>
                    </td>
                    <td style="width: 50%; text-align: center; vertical-align: top;">
                        <div class="nckh-sig-role">Cá nhân đề xuất</div>
                        <div class="nckh-sig-note"><em>(Ký và ghi rõ họ tên)</em></div>
                        <div class="nckh-sig-space"></div>
                        <div class="nckh-sig-name"><?= ViewHelper::e(!empty($data['ca_nhan_de_xuat']) ? $data['ca_nhan_de_xuat'] : 'Lò Mạnh Đạt') ?></div>
                    </td>
                </tr>
            </table>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Render HTML trực quan chuẩn văn bản hành chính cho Thuyết Minh Đề Tài (Mẫu 01)
     */
    public static function renderThuyetMinhHtml(array $data, bool $isPrintMode = false): string {
        ob_start();
        $loaiHinh = $data['loai_hinh_nc'] ?? 'CO_BAN';
        ?>
        <div class="nckh-doc-wrapper <?= $isPrintMode ? 'is-print-mode' : '' ?>">
            <!-- HEADER QUỐC HIỆU VÀ ĐƠN VỊ -->
            <table class="nckh-doc-table-header">
                <tr>
                    <td style="width: 48%; text-align: center; vertical-align: top;">
                        <div style="margin-bottom: 6px;">
                            <img src="<?= \App\Config\App::url('pic/logo.jpg') ?>" alt="Logo ĐH Tây Bắc" onerror="this.onerror=null; this.src='<?= \App\Config\App::url('public/images/logo.jpg') ?>';" style="width: 52px; height: 52px; border-radius: 50%; object-fit: cover; display: inline-block;">
                        </div>
                        <div class="nckh-co-quan"><?= ViewHelper::e($data['co_quan_chu_quan'] ?? 'BỘ GIÁO DỤC VÀ ĐÀO TẠO') ?></div>
                        <div class="nckh-don-vi"><?= ViewHelper::e($data['don_vi'] ?? 'TRƯỜNG ĐẠI HỌC TÂY BẮC') ?></div>
                        <div class="nckh-divider"></div>
                    </td>
                    <td style="width: 4%;"></td>
                    <td style="width: 48%; text-align: center; vertical-align: top;">
                        <div class="nckh-quoc-hieu">CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM</div>
                        <div class="nckh-tieu-ngu">Độc lập - Tự do - Hạnh phúc</div>
                        <div class="nckh-divider"></div>
                        <div class="nckh-ngay-thang"><?= ViewHelper::e($data['dia_diem_ngay'] ?? 'Sơn La, ngày ... tháng ... năm ...') ?></div>
                    </td>
                </tr>
            </table>

            <!-- TIÊU ĐỀ BIỂU MẪU -->
            <div class="nckh-doc-title-block">
                <h1 class="nckh-doc-main-title">THUYẾT MINH ĐỀ TÀI</h1>
                <h2 class="nckh-doc-sub-title">NGHIÊN CỨU KHOA HỌC VÀ CÔNG NGHỆ CẤP TRƯỜNG CỦA SINH VIÊN</h2>
                <div class="nckh-doc-academic-year">NĂM HỌC <?= ViewHelper::e($data['nam_hoc'] ?? '2025 - 2026') ?></div>
            </div>

            <!-- NỘI DUNG 13 MỤC CHUẨN -->
            <div class="nckh-doc-content">
                <!-- MỤC 1 -->
                <div class="nckh-section">
                    <h3 class="nckh-section-title">1. TÊN ĐỀ TÀI:</h3>
                    <div class="nckh-section-body nckh-highlight-box">
                        <strong><?= ViewHelper::e(!empty($data['ten_de_tai']) ? $data['ten_de_tai'] : '(Chưa nhập tên đề tài)') ?></strong>
                    </div>
                </div>

                <!-- MỤC 2 -->
                <div class="nckh-section">
                    <h3 class="nckh-section-title">2. LOẠI HÌNH NGHIÊN CỨU:</h3>
                    <div class="nckh-section-body" style="display: flex; gap: 32px; padding: 6px 12px;">
                        <label class="nckh-checkbox-label">
                            <span class="nckh-check-box"><?= ($loaiHinh === 'CO_BAN') ? '☒' : '☐' ?></span>
                            <span>Cơ Bản</span>
                        </label>
                        <label class="nckh-checkbox-label">
                            <span class="nckh-check-box"><?= ($loaiHinh === 'UNG_DUNG') ? '☒' : '☐' ?></span>
                            <span>Ứng Dụng</span>
                        </label>
                        <label class="nckh-checkbox-label">
                            <span class="nckh-check-box"><?= ($loaiHinh === 'TRIEN_KHAI') ? '☒' : '☐' ?></span>
                            <span>Triển Khai</span>
                        </label>
                    </div>
                </div>

                <!-- MỤC 3 -->
                <div class="nckh-section">
                    <h3 class="nckh-section-title">3. NHÓM NGÀNH:</h3>
                    <div class="nckh-section-body" style="padding-left: 12px;">
                        <span><?= ViewHelper::e($data['nhom_nganh'] ?? 'Công nghệ thông tin') ?></span>
                    </div>
                </div>

                <!-- MỤC 4 -->
                <div class="nckh-section">
                    <h3 class="nckh-section-title">4. THỜI GIAN THỰC HIỆN:</h3>
                    <div class="nckh-section-body" style="padding-left: 12px;">
                        <span><?= ViewHelper::e($data['thoi_gian_thuc_hien'] ?? '') ?></span>
                    </div>
                </div>

                <!-- MỤC 5 -->
                <div class="nckh-section">
                    <h3 class="nckh-section-title">5. SINH VIÊN CHỊU TRÁCH NHIỆM CHÍNH:</h3>
                    <div class="nckh-section-body">
                        <table class="nckh-table-bordered">
                            <tr>
                                <td style="width: 40%;"><strong>Họ và tên:</strong> <?= ViewHelper::e($data['chu_nhiem']['ho_ten'] ?? '') ?></td>
                                <td style="width: 30%;"><strong>Lớp:</strong> <?= ViewHelper::e($data['chu_nhiem']['lop'] ?? '') ?></td>
                                <td style="width: 30%;"><strong>Điện thoại:</strong> <?= ViewHelper::e($data['chu_nhiem']['dien_thoai'] ?? '') ?></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- MỤC 6 -->
                <div class="nckh-section">
                    <h3 class="nckh-section-title">6. NHỮNG NGƯỜI THAM GIA THỰC HIỆN ĐỀ TÀI:</h3>
                    <div class="nckh-section-body">
                        <table class="nckh-table-bordered">
                            <thead>
                                <tr>
                                    <th style="width: 6%; text-align: center;">TT</th>
                                    <th style="width: 24%;">Họ và tên</th>
                                    <th style="width: 18%;">Lớp</th>
                                    <th style="width: 40%;">Nội dung công việc được giao</th>
                                    <th style="width: 12%; text-align: center;">Chữ ký</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($data['thanh_vien_tham_gia'])): ?>
                                    <?php foreach ($data['thanh_vien_tham_gia'] as $idx => $tv): ?>
                                        <tr>
                                            <td style="text-align: center;"><?= $idx + 1 ?></td>
                                            <td><strong><?= ViewHelper::e($tv['ho_ten'] ?? '') ?></strong></td>
                                            <td><?= ViewHelper::e($tv['lop'] ?? '') ?></td>
                                            <td class="nckh-text-justified"><?= nl2br(ViewHelper::e($tv['cong_viec'] ?? '')) ?></td>
                                            <td style="text-align: center; font-style: italic; color: #64748b;"><?= ViewHelper::e(!empty($tv['chu_ky']) ? $tv['chu_ky'] : 'Đã ký') ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" style="text-align: center; color: var(--text-muted);">(Chưa có thành viên nào)</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- MỤC 7 -->
                <div class="nckh-section">
                    <h3 class="nckh-section-title">7. GIẢNG VIÊN HƯỚNG DẪN:</h3>
                    <div class="nckh-section-body">
                        <table class="nckh-table-bordered">
                            <tr>
                                <td style="width: 45%;"><strong>Họ và tên:</strong> <?= ViewHelper::e($data['gvhd']['ho_ten'] ?? '') ?></td>
                                <td style="width: 25%;"><strong>Học hàm, học vị:</strong> <?= ViewHelper::e($data['gvhd']['chuc_danh_hoc_vi'] ?? '') ?></td>
                                <td style="width: 30%;"><strong>Điện thoại:</strong> <?= ViewHelper::e($data['gvhd']['dien_thoai'] ?? '') ?></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- MỤC 8 -->
                <div class="nckh-section">
                    <h3 class="nckh-section-title">8. TÌNH HÌNH NGHIÊN CỨU:</h3>
                    <div class="nckh-section-body" style="padding-left: 8px;">
                        <div class="nckh-sub-section">
                            <h4 class="nckh-sub-section-title">8.1. Tổng quan tình hình nghiên cứu thuộc lĩnh vực của đề tài:</h4>
                            <div class="nckh-text-justified" style="margin-top: 6px;">
                                <?= !empty($data['tinh_hinh_nc']['tong_quan']) ? nl2br(ViewHelper::e($data['tinh_hinh_nc']['tong_quan'])) : '<em class="text-muted">(Chưa cập nhật nội dung)</em>' ?>
                            </div>
                        </div>

                        <div class="nckh-sub-section" style="margin-top: 14px;">
                            <h4 class="nckh-sub-section-title">8.2. Danh mục các công trình liên quan (Họ và tên tác giả; Nhan đề bài báo, ấn phẩm, nguồn tài liệu):</h4>
                            <div class="nckh-text-justified" style="margin-top: 6px; font-size: 13.5px; line-height: 1.6;">
                                <?= !empty($data['tinh_hinh_nc']['cong_trinh_lien_quan']) ? nl2br(ViewHelper::e($data['tinh_hinh_nc']['cong_trinh_lien_quan'])) : '<em class="text-muted">(Chưa cập nhật danh mục công trình)</em>' ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- MỤC 9 -->
                <div class="nckh-section">
                    <h3 class="nckh-section-title">9. TÍNH CẤP THIẾT CỦA ĐỀ TÀI:</h3>
                    <div class="nckh-section-body nckh-text-justified">
                        <?= !empty($data['tinh_cap_thiet']) ? nl2br(ViewHelper::e($data['tinh_cap_thiet'])) : '<em class="text-muted">(Chưa cập nhật nội dung)</em>' ?>
                    </div>
                </div>

                <!-- MỤC 10 -->
                <div class="nckh-section">
                    <h3 class="nckh-section-title">10. MỤC TIÊU ĐỀ TÀI:</h3>
                    <div class="nckh-section-body nckh-text-justified">
                        <?php if (!empty($data['muc_tieu']['muc_tieu_chung'])): ?>
                            <p style="margin-bottom: 8px;">
                                <strong>- Mục tiêu chung:</strong> <?= ViewHelper::e($data['muc_tieu']['muc_tieu_chung']) ?>
                            </p>
                        <?php endif; ?>
                        <?php if (!empty($data['muc_tieu']['muc_tieu_cu_the'])): ?>
                            <div style="margin-bottom: 6px;">
                                <strong>- Các mục tiêu cụ thể:</strong>
                                <div style="padding-left: 8px; margin-top: 4px;">
                                    <?= nl2br(ViewHelper::e($data['muc_tieu']['muc_tieu_cu_the'])) ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- MỤC 11 -->
                <div class="nckh-section">
                    <h3 class="nckh-section-title">11. ĐỐI TƯỢNG, PHẠM VI , PHƯƠNG PHÁP NGHIÊN CỨU:</h3>
                    <div class="nckh-section-body" style="padding-left: 8px;">
                        <p style="margin-bottom: 6px;">
                            <strong>1) Đối tượng nghiên cứu:</strong><br>
                            <?= nl2br(ViewHelper::e($data['doi_tuong_pham_vi_phuong_phap']['doi_tuong'] ?? '')) ?>
                        </p>
                        <p style="margin-bottom: 6px;">
                            <strong>2) Phạm vi nghiên cứu:</strong><br>
                            <?= nl2br(ViewHelper::e($data['doi_tuong_pham_vi_phuong_phap']['pham_vi'] ?? '')) ?>
                        </p>
                        <p style="margin-bottom: 6px;">
                            <strong>3) Phương pháp nghiên cứu:</strong><br>
                            <?= nl2br(ViewHelper::e($data['doi_tuong_pham_vi_phuong_phap']['phuong_phap'] ?? '')) ?>
                        </p>
                    </div>
                </div>

                <!-- MỤC 12 -->
                <div class="nckh-section">
                    <h3 class="nckh-section-title">12. NỘI DUNG NGHIÊN CỨU VÀ TIẾN ĐỘ THỰC HIỆN:</h3>
                    <div class="nckh-section-body">
                        <table class="nckh-table-bordered">
                            <thead>
                                <tr>
                                    <th style="width: 5%; text-align: center;">TT</th>
                                    <th style="width: 35%;">Các nội dung, công việc thực hiện chủ yếu</th>
                                    <th style="width: 25%;">Dự kiến sản phẩm</th>
                                    <th style="width: 20%;">Thời gian (Bắt đầu – Kết thúc)</th>
                                    <th style="width: 15%;">Người thực hiện</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($data['tien_do_thuc_hien'])): ?>
                                    <?php foreach ($data['tien_do_thuc_hien'] as $idx => $td): ?>
                                        <tr>
                                            <td style="text-align: center;"><?= $idx + 1 ?></td>
                                            <td class="nckh-text-justified"><?= nl2br(ViewHelper::e($td['noi_dung'] ?? '')) ?></td>
                                            <td><?= ViewHelper::e($td['san_pham'] ?? '') ?></td>
                                            <td><?= ViewHelper::e($td['thoi_gian'] ?? '') ?></td>
                                            <td><?= ViewHelper::e($td['nguoi_thuc_hien'] ?? '') ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" style="text-align: center; color: var(--text-muted);">(Chưa có nội dung tiến độ)</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- MỤC 13 -->
                <div class="nckh-section">
                    <h3 class="nckh-section-title">13. SẢN PHẨM VÀ ĐỊA CHỈ ỨNG DỤNG:</h3>
                    <div class="nckh-section-body">
                        <!-- 13.1 Loại sản phẩm -->
                        <div style="margin-bottom: 14px;">
                            <strong>- Loại sản phẩm:</strong>
                            <div class="nckh-products-grid">
                                <?php 
                                $selectedProducts = (array)($data['san_pham_ung_dung']['loai_san_pham'] ?? []);
                                foreach (self::DANH_MUC_LOAI_SAN_PHAM as $spItem): 
                                    $checked = in_array($spItem, $selectedProducts);
                                ?>
                                    <div class="nckh-product-item">
                                        <span class="nckh-check-box"><?= $checked ? '☒' : '☐' ?></span>
                                        <span style="<?= $checked ? 'font-weight: 700; color: #1e3a8a;' : '' ?>"><?= ViewHelper::e($spItem) ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- 13.2 Bảng chi tiết sản phẩm -->
                        <div style="margin-bottom: 14px;">
                            <strong>- Tên sản phẩm, số lượng và yêu cầu khoa học đối với sản phẩm:</strong>
                            <table class="nckh-table-bordered" style="margin-top: 6px;">
                                <thead>
                                    <tr>
                                        <th style="width: 6%; text-align: center;">TT</th>
                                        <th style="width: 44%;">Tên sản phẩm</th>
                                        <th style="width: 12%; text-align: center;">Số lượng</th>
                                        <th style="width: 38%;">Yêu cầu khoa học</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($data['san_pham_ung_dung']['danh_sach'])): ?>
                                        <?php foreach ($data['san_pham_ung_dung']['danh_sach'] as $idx => $sp): ?>
                                            <tr>
                                                <td style="text-align: center;"><?= $idx + 1 ?></td>
                                                <td><strong><?= ViewHelper::e($sp['ten_san_pham'] ?? '') ?></strong></td>
                                                <td style="text-align: center;"><?= ViewHelper::e($sp['so_luong'] ?? '01') ?></td>
                                                <td class="nckh-text-justified"><?= nl2br(ViewHelper::e($sp['yeu_cau_khoa_hoc'] ?? '')) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="4" style="text-align: center; color: var(--text-muted);">(Chưa có danh sách sản phẩm)</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- 13.3 Địa chỉ có thể ứng dụng -->
                        <div>
                            <strong>- Địa chỉ có thể ứng dụng (tên địa phương, đơn vị ứng dụng):</strong>
                            <div class="nckh-text-justified" style="padding-left: 8px; margin-top: 4px;">
                                <?= !empty($data['san_pham_ung_dung']['dia_chi_ap_dung']) ? nl2br(ViewHelper::e($data['san_pham_ung_dung']['dia_chi_ap_dung'])) : '<em class="text-muted">(Chưa cập nhật)</em>' ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CHỮ KÝ XÁC NHẬN -->
            <table class="nckh-signature-table">
                <tr>
                    <td style="width: 50%; text-align: center; vertical-align: top;">
                        <div class="nckh-sig-date"><?= ViewHelper::e(!empty($data['ngay_ky_gvhd']) ? $data['ngay_ky_gvhd'] : 'Sơn La, ngày 20 tháng 10 năm 2025') ?></div>
                        <div class="nckh-sig-role">Giảng viên hướng dẫn</div>
                        <div class="nckh-sig-note"><em>(Ký và ghi rõ họ tên)</em></div>
                        <div class="nckh-sig-space"></div>
                        <div class="nckh-sig-name"><?= ViewHelper::e(!empty($data['gvhd_ky']) ? $data['gvhd_ky'] : ($data['gvhd']['ho_ten'] ?? 'TS. Phạm Đình Thành')) ?></div>
                    </td>
                    <td style="width: 50%; text-align: center; vertical-align: top;">
                        <div class="nckh-sig-date"><?= ViewHelper::e(!empty($data['ngay_ky_sv']) ? $data['ngay_ky_sv'] : 'Sơn La, ngày 20 tháng 10 năm 2025') ?></div>
                        <div class="nckh-sig-role">Sinh viên chịu trách nhiệm chính thực hiện đề tài</div>
                        <div class="nckh-sig-note"><em>(Ký và ghi rõ họ tên)</em></div>
                        <div class="nckh-sig-space"></div>
                        <div class="nckh-sig-name"><?= ViewHelper::e(!empty($data['sv_ky']) ? $data['sv_ky'] : ($data['chu_nhiem']['ho_ten'] ?? 'Lò Mạnh Đạt')) ?></div>
                    </td>
                </tr>
            </table>
        </div>
        <?php
        return ob_get_clean();
    }
}

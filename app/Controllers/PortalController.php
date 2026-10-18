<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Config\App;
use App\Helpers\AuthHelper;
use App\Models\DeTaiModel;
use App\Models\DotNCKHModel;
use App\Models\KhenThuongModel;
use App\Models\UserModel;
use Throwable;

class PortalController {
    /**
     * Cổng giới thiệu Trường Đại học Tây Bắc (UTB - Sơn La) & Web Viewer utb.edu.vn/gioi-thieu/
     * Dữ liệu chính thống trích xuất từ https://utb.edu.vn/gioi-thieu/ kết hợp số liệu NCKH thực tế
     */
    public function gioiThieu(): void {
        try {
            // 1. Thống kê số liệu NCKH thực tế từ CSDL với cơ chế Fallback an toàn
            $totalTopics = 0;
            $executingTopics = 0;
            $completedTopics = 0;
            $totalBudget = 0.0;
            $totalStudents = 0;
            $totalLecturers = 0;
            $activeDots = [];
            $directions = [];
            $notices = [];
            $awards = [];

            try {
                $deTaiModel = new DeTaiModel();
                $allTopics = $deTaiModel->getAllTopics();
                $totalTopics = count($allTopics);
                
                foreach ($allTopics as $t) {
                    $status = $t['TrangThai'] ?? '';
                    if (in_array($status, [App::STATUS_DANG_THUC_HIEN, App::STATUS_DA_PHE_DUYET, App::STATUS_CHO_NGHIEM_THU])) {
                        $executingTopics++;
                    }
                    if ($status === App::STATUS_DA_NGHIEM_THU) {
                        $completedTopics++;
                    }
                }

                $financialOverview = $deTaiModel->getFinancialOverview();
                $totalBudget = (float)($financialOverview['TongKinhPhi'] ?? 0);
            } catch (Throwable $e) {
                // Fallback nếu CSDL chưa có dữ liệu đề tài
                $totalTopics = 0;
            }

            try {
                $userModel = new UserModel();
                $userStats = $userModel->getUserStats();
                $totalStudents = (int)($userStats['sinhVien'] ?? 0);
                $totalLecturers = (int)($userStats['giangVien'] ?? 0);
            } catch (Throwable $e) {
                $totalStudents = 0;
                $totalLecturers = 0;
            }

            try {
                $dotModel = new DotNCKHModel();
                $activeDots = $dotModel->getActive();
                $directions = $dotModel->getAllResearchDirections();
                $notices = $dotModel->getNotifications();
            } catch (Throwable $e) {
                $activeDots = [];
                $directions = [];
                $notices = [];
            }

            try {
                $khenThuongModel = new KhenThuongModel();
                $awards = $khenThuongModel->getAllAwards();
            } catch (Throwable $e) {
                $awards = [];
            }

            // 2. Thông tin chính thống về Trường Đại học Tây Bắc (UTB - Sơn La)
            $universityInfo = [
                'name' => 'TRƯỜNG ĐẠI HỌC TÂY BẮC',
                'english_name' => 'TAY BAC UNIVERSITY (UTB)',
                'short_name' => 'UTB',
                'governing_body' => 'BỘ GIÁO DỤC VÀ ĐÀO TẠO',
                'established_year' => 1960,
                'upgrade_year' => 2001,
                'decision' => 'Quyết định số 39/2001/QĐ-TTg ngày 23/3/2001 của Thủ tướng Chính phủ',
                'address' => 'Đường Chu Văn An, Phường Quyết Tâm, Thành phố Sơn La, Tỉnh Sơn La',
                'location' => 'Sơn La',
                'phone' => '(+84) 212.3.751.700',
                'fax' => '0212.3.751.701',
                'hotline_nckh' => '(+84) 212.3.751.700',
                'email' => 'contact@utb.edu.vn',
                'email_academic' => 'ctdt@utb.edu.vn',
                'email_nckh' => 'qlkh@utb.edu.vn',
                'website' => 'https://utb.edu.vn',
                'intro_url' => 'https://utb.edu.vn/gioi-thieu/',
                'core_values' => 'Đoàn kết - Trách nhiệm - Sáng tạo - Hiệu quả',
                'philosophy' => 'Toàn diện - Thực tiễn - Khai phóng - Hội nhập',
                'mission' => 'Trường Đại học Tây Bắc là cơ sở giáo dục đại học công lập trực thuộc Bộ Giáo dục và Đào tạo, đào tạo nguồn nhân lực chất lượng cao trình độ đại học và sau đại học; là trung tâm nghiên cứu khoa học, chuyển giao công nghệ, hợp tác quốc tế, bảo tồn và phát huy bản sắc văn hóa các dân tộc phục vụ sự nghiệp phát triển kinh tế - xã hội vùng Tây Bắc và cả nước.',
                'vision_2030' => 'Đến năm 2030, Trường Đại học Tây Bắc trở thành trường đại học đa ngành, định hướng ứng dụng có uy tín trong nước và khu vực; là trung tâm đào tạo nhân lực, nghiên cứu khoa học chuyển giao tiến bộ công nghệ và đổi mới sáng tạo hàng đầu vùng Tây Bắc.'
            ];

            // 3. Cơ cấu các Khoa chuyên môn tại Trường Đại học Tây Bắc (Sơn La)
            $faculties = [
                [
                    'code' => 'K_CNTT',
                    'name' => 'Khoa Công nghệ Thông tin',
                    'short' => 'CNTT & Chuyển đổi số',
                    'icon' => 'monitor',
                    'color' => '#2563eb',
                    'description' => 'Đào tạo kỹ sư CNTT, Kỹ thuật phần mềm, Trí tuệ nhân tạo (AI), An toàn thông tin và giải pháp chuyển đổi số phục vụ chính quyền và doanh nghiệp vùng Tây Bắc.',
                    'highlights' => ['Trí tuệ nhân tạo (AI) & Ứng dụng thực tiễn', 'Phát triển phần mềm & Ứng dụng Web / Cloud', 'Internet vạn vật (IoT) trong nông nghiệp thông minh', 'An toàn thông tin và dữ liệu số']
                ],
                [
                    'code' => 'K_NL',
                    'name' => 'Khoa Nông - Lâm',
                    'short' => 'Nông - Lâm nghiệp Công nghệ cao',
                    'icon' => 'feather',
                    'color' => '#059669',
                    'description' => 'Mũi nhọn thế mạnh đặc thù của ĐH Tây Bắc tại Sơn La: nghiên cứu giống cây ăn quả, cà phê, cây dược liệu, nông nghiệp công nghệ cao và quản lý bền vững tài nguyên rừng Tây Bắc.',
                    'highlights' => ['Nông nghiệp hữu cơ & Công nghệ cao Sơn La', 'Kỹ thuật chế biến & Bảo quản nông sản (Xoài, Mận, Cà phê)', 'Bảo tồn đa dạng sinh học & Lâm nghiệp cộng đồng', 'Công nghệ sinh học trong bảo vệ thực vật']
                ],
                [
                    'code' => 'K_KT',
                    'name' => 'Khoa Kinh tế',
                    'short' => 'Kinh tế & Quản trị kinh doanh',
                    'icon' => 'trending-up',
                    'color' => '#0891b2',
                    'description' => 'Đào tạo cử nhân Quản trị kinh doanh, Kinh tế phát triển, Tài chính - Ngân hàng, Kế toán và Thương mại điện tử chuỗi nông sản đặc sản vùng cao.',
                    'highlights' => ['Kinh tế nông lâm nghiệp & Phát triển nông thôn', 'Thương mại điện tử & Chuỗi giá trị nông sản vùng Tây Bắc', 'Quản trị doanh nghiệp khởi nghiệp đổi mới sáng tạo', 'Tài chính công & Vi mô']
                ],
                [
                    'code' => 'K_KHXH',
                    'name' => 'Khoa Khoa học Xã hội',
                    'short' => 'KH Xã hội & Văn hóa Dân tộc',
                    'icon' => 'book-open',
                    'color' => '#7c3aed',
                    'description' => 'Nghiên cứu bảo tồn, phát huy các giá trị văn hóa, ngôn ngữ, lịch sử và địa lý của các dân tộc thiểu số vùng Tây Bắc kết hợp phát triển du lịch cộng đồng sinh thái.',
                    'highlights' => ['Bảo tồn di sản văn hóa các dân tộc Tây Bắc (Thái, Mông, Dao...)', 'Du lịch sinh thái văn hóa Sơn La - Điện Biên - Mộc Châu', 'Ngôn ngữ học & Văn học các dân tộc', 'Chính sách dân tộc và an sinh xã hội vùng cao']
                ],
                [
                    'code' => 'K_KHTN_CN',
                    'name' => 'Khoa Khoa học Tự nhiên - Công nghệ',
                    'short' => 'KHTN & Kỹ thuật Công nghệ',
                    'icon' => 'cpu',
                    'color' => '#d97706',
                    'description' => 'Đào tạo và nghiên cứu Toán học ứng dụng, Vật lý kỹ thuật, Hóa học vật liệu, Sinh học và Khoa học môi trường thích ứng biến đổi khí hậu.',
                    'highlights' => ['Khoa học môi trường & Năng lượng tái tạo vùng núi', 'Hóa học chiết xuất dược liệu bản địa Tây Bắc', 'Toán ứng dụng & Mô hình hóa số liệu', 'Khoa học vật liệu và ứng dụng']
                ],
                [
                    'code' => 'K_SP',
                    'name' => 'Khoa Sư phạm',
                    'short' => 'Sư phạm & Đào tạo Giáo viên',
                    'icon' => 'user-check',
                    'color' => '#e11d48',
                    'description' => 'Tiếp nối truyền thống vẻ vang từ 1960: Đào tạo đội ngũ giáo viên Tiểu học, Mầm non và THCS/THPT vững vàng chuyên môn cho các tỉnh Tây Bắc.',
                    'highlights' => ['Phương pháp giảng dạy song ngữ tiếng dân tộc', 'Chuyển đổi số trong giáo dục phổ thông vùng cao', 'Tâm lý học lứa tuổi & Giáo dục hòa nhập', 'Đổi mới phương pháp dạy học STEM']
                ],
                [
                    'code' => 'K_NN',
                    'name' => 'Khoa Ngoại ngữ',
                    'short' => 'Ngoại ngữ & Hợp tác Quốc tế',
                    'icon' => 'globe',
                    'color' => '#4f46e5',
                    'description' => 'Đào tạo cử nhân Ngôn ngữ Anh, Ngôn ngữ Trung Quốc; xúc tiến hợp tác quốc tế đặc biệt là chương trình đào tạo Lưu học sinh nước CHDCND Lào.',
                    'highlights' => ['Biên - phiên dịch chuyên ngành & Ngoại giao kinh tế', 'Hợp tác giáo dục đặc biệt Việt Nam - Lào', 'Giảng dạy tiếng Việt cho người nước ngoài', 'Tiếng Anh chuyên ngành khoa học công nghệ']
                ]
            ];

            // 4. Lịch sử hơn 65 năm xây dựng và phát triển của Trường Đại học Tây Bắc (Sơn La)
            $historyMilestones = [
                [
                    'year' => '1960',
                    'title' => 'Thành lập Trường Sư phạm cấp 2 Khu tự trị Thái Mèo',
                    'desc' => 'Tiền thân của Trường Đại học Tây Bắc, thành lập năm 1960 nhằm đào tạo đội ngũ giáo viên phục vụ sự nghiệp giáo dục và phát triển văn hóa - xã hội vùng Tây Bắc đất nước.'
                ],
                [
                    'year' => '1981',
                    'title' => 'Nâng cấp thành Trường Cao đẳng Sư phạm Tây Bắc',
                    'desc' => 'Trường CĐSP Tây Bắc được thành lập, mở rộng quy mô đào tạo giáo viên đa chuyên ngành chất lượng cao cho các tỉnh Sơn La, Điện Biên, Lai Châu, Hòa Bình, Lào Cai, Yên Bái.'
                ],
                [
                    'year' => '2001',
                    'title' => 'Thủ tướng Chính phủ ký QĐ thành lập Trường Đại học Tây Bắc',
                    'desc' => 'Ngày 23/3/2001, Thủ tướng ban hành Quyết định số 39/2001/QĐ-TTg thành lập Trường Đại học Tây Bắc, đánh dấu bước ngoặt lịch sử trở thành trường đại học công lập đầu tiên tại vùng Tây Bắc.'
                ],
                [
                    'year' => '2018 - 2024',
                    'title' => 'Đạt chuẩn Kiểm định Quốc gia & Đột phá Khoa học Công nghệ',
                    'desc' => 'Nhà trường được cấp Chứng nhận Kiểm định chất lượng giáo dục quốc gia; phong trào NCKH sinh viên phát triển vượt bậc với hàng loạt công trình đạt giải thưởng cấp Bộ, Tỉnh và giải khởi nghiệp đổi mới sáng tạo.'
                ],
                [
                    'year' => '2025 - 2030',
                    'title' => 'Đại học Đa ngành Ứng dụng & Trung tâm Đổi mới Sáng tạo Tây Bắc',
                    'desc' => 'Chuyển đổi số toàn diện, phát triển các trung tâm nghiên cứu AI, nông - lâm nghiệp công nghệ cao, giao lưu hợp tác quốc tế và đào tạo nguồn nhân lực chất lượng cao phụng sự Tổ quốc.'
                ]
            ];

            // 5. Quy trình 5 bước NCKH Sinh viên Trường Đại học Tây Bắc
            $roadmapSteps = [
                [
                    'step' => '1',
                    'title' => 'Đăng ký & Chọn hướng NC',
                    'desc' => 'Tiếp cận thông báo phát động đợt NCKH của Nhà trường, lựa chọn hướng nghiên cứu trọng điểm của Khoa và đăng ký tài khoản portal.',
                    'action_label' => 'Đăng ký tài khoản',
                    'action_url' => App::url('index.php?action=register')
                ],
                [
                    'step' => '2',
                    'title' => 'Lập Phiếu Đề xuất & Thuyết minh',
                    'desc' => 'Lập nhóm sinh viên (1-5 thành viên), chọn Giảng viên hướng dẫn và điền trực tuyến Mẫu 02 (Phiếu đề xuất) & Mẫu 01 (Thuyết minh chuẩn UTB).',
                    'action_label' => 'Đăng ký đề tài',
                    'action_url' => App::url('index.php?action=sv-dangky-detai')
                ],
                [
                    'step' => '3',
                    'title' => 'GVHD & Hội đồng thẩm định',
                    'desc' => 'GVHD góp ý và tiếp nhận hồ sơ; Hội đồng Khoa học Trường ĐH Tây Bắc họp thẩm định tính khả thi, phê duyệt và cấp kinh phí thực hiện.',
                    'action_label' => 'Xem hướng dẫn',
                    'action_url' => App::url('index.php?action=sv-thongtin-nckh')
                ],
                [
                    'step' => '4',
                    'title' => 'Triển khai & Báo cáo tiến độ',
                    'desc' => 'Thực nghiệm giải pháp, thu thập số liệu thực địa vùng Tây Bắc, hoàn thiện sản phẩm và thực hiện báo cáo tiến độ định kỳ 4 mốc trên hệ thống.',
                    'action_label' => 'Báo cáo tiến độ',
                    'action_url' => App::url('index.php?action=sv-baocao-tiendo')
                ],
                [
                    'step' => '5',
                    'title' => 'Nghiệm thu & Vinh danh trao giải',
                    'desc' => 'Bảo vệ kết quả trước Hội đồng nghiệm thu cấp Trường; xét khen thưởng Đề tài Xuất sắc, hỗ trợ xuất bản tạp chí khoa học và dự thi các cấp.',
                    'action_label' => 'Tra cứu kết quả',
                    'action_url' => App::url('index.php?action=sv-xem-ketqua')
                ]
            ];

            $pageTitle = "Cổng Thông Tin & Website Trường Đại Học Tây Bắc (Sơn La) - UTB";
            
            // Tìm và nạp file view an toàn tuyệt đối
            $viewPath = dirname(__DIR__, 2) . '/views/portal/gioi_thieu.php';
            if (!file_exists($viewPath)) {
                $viewPath = __DIR__ . '/../../views/portal/gioi_thieu.php';
            }
            
            if (file_exists($viewPath)) {
                require $viewPath;
            } else {
                throw new \RuntimeException("Không tìm thấy file giao diện: " . $viewPath);
            }
        } catch (Throwable $e) {
            http_response_code(500);
            $errorMessage = "Lỗi hiển thị Cổng Giới thiệu Trường Đại học Tây Bắc: " . $e->getMessage();
            $errorTrace = $e->getTraceAsString();
            $errorFile = $e->getFile() . ':' . $e->getLine();
            require dirname(__DIR__, 2) . '/views/errors/500.php';
        }
    }
}

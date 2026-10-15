<?php
namespace App\Controllers;

use App\Config\App;
use App\Helpers\AuthHelper;
use App\Models\DeTaiModel;
use App\Models\BaoCaoTienDoModel;
use App\Models\BaoCaoTongKetModel;
use App\Models\DotNCKHModel;
use Exception;

class GiangVienController {
    private DeTaiModel $deTaiModel;
    private BaoCaoTienDoModel $tienDoModel;
    private BaoCaoTongKetModel $tongKetModel;
    private DotNCKHModel $dotModel;

    public function __construct() {
        AuthHelper::requireRole(App::ROLE_GIANG_VIEN);
        $this->deTaiModel = new DeTaiModel();
        $this->tienDoModel = new BaoCaoTienDoModel();
        $this->tongKetModel = new BaoCaoTongKetModel();
        $this->dotModel = new DotNCKHModel();
    }

    public function dashboard(): void {
        $gvId = AuthHelper::id();
        $allTopics = $this->deTaiModel->getTopicsByTeacher($gvId);
        
        $pendingTopics = array_filter($allTopics, fn($t) => $t['TrangThai'] === 'CHO_GVHD_DUYET');
        $activeTopics = array_filter($allTopics, fn($t) => in_array($t['TrangThai'], ['CHO_THAM_DINH', 'DA_PHE_DUYET', 'DANG_THUC_HIEN']));
        $completedTopics = array_filter($allTopics, fn($t) => in_array($t['TrangThai'], ['CHO_NGHIEM_THU', 'DA_NGHIEM_THU']));

        require dirname(__DIR__, 2) . '/views/giangvien/dashboard.php';
    }

    // UC 7 & UC 8: Tiếp nhận hồ sơ & Duyệt hướng dẫn
    public function tiepNhanHoSo(): void {
        $gvId = AuthHelper::id();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $maDeTai = $_POST['madetai'] ?? '';
                $action = $_POST['decision'] ?? ''; // 'dong_y' hoặc 'tu_choi'
                $comments = trim($_POST['comments'] ?? '');

                $topic = $this->deTaiModel->findById($maDeTai);
                if (!$topic || $topic['MaGVHD'] !== $gvId) {
                    throw new Exception("Bạn không có quyền xét duyệt đề tài này.");
                }

                $isApproved = ($action === 'dong_y');
                $this->deTaiModel->teacherApprove($maDeTai, $isApproved, $comments);

                $msg = $isApproved 
                    ? "Đã chấp thuận hướng dẫn đề tài {$maDeTai}! Hồ sơ đã được chuyển tiếp đến Cán bộ Quản lý NCKH để thẩm định."
                    : "Đã từ chối hướng dẫn đề tài {$maDeTai}. Ý kiến phản hồi đã được lưu lại.";
                AuthHelper::setFlash('success', $msg);
                header("Location: " . App::url('index.php?action=gv-tiepnhan-hoso'));
                exit;

            } catch (Exception $e) {
                AuthHelper::setFlash('danger', $e->getMessage());
            }
        }

        $pendingTopics = $this->deTaiModel->getTopicsByTeacher($gvId, 'CHO_GVHD_DUYET');
        $processedTopics = array_filter(
            $this->deTaiModel->getTopicsByTeacher($gvId),
            fn($t) => $t['TrangThai'] !== 'CHO_GVHD_DUYET'
        );

        require dirname(__DIR__, 2) . '/views/giangvien/tiepnhan_hoso.php';
    }

    // UC 9: Theo dõi và ghi nhận xét tiến độ
    public function theodoiTienDo(): void {
        $gvId = AuthHelper::id();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $reportId = (int)($_POST['report_id'] ?? 0);
                $danhGia = $_POST['danh_gia'] ?? ''; // 'DAT_TIEN_DO', 'CHAM_TIEN_DO', 'CAN_NHAC_NHO'
                $nhanXet = trim($_POST['nhan_xet'] ?? '');

                if (empty($danhGia) || empty($nhanXet)) {
                    throw new Exception("Vui lòng chọn kết quả đánh giá và nhập nhận xét góp ý cho sinh viên.");
                }

                $this->tienDoModel->teacherReview($reportId, $danhGia, $nhanXet);
                AuthHelper::setFlash('success', "Đã ghi nhận xét và đánh giá tiến độ thành công!");
                header("Location: " . App::url('index.php?action=gv-theodoi-tiendo'));
                exit;

            } catch (Exception $e) {
                AuthHelper::setFlash('danger', $e->getMessage());
            }
        }

        $myTopics = $this->deTaiModel->getTopicsByTeacher($gvId);
        $topicsWithReports = [];
        foreach ($myTopics as $t) {
            $reports = $this->tienDoModel->getByTopic($t['MaDeTai']);
            $topicsWithReports[] = [
                'topic' => $t,
                'reports' => $reports
            ];
        }

        require dirname(__DIR__, 2) . '/views/giangvien/theodoi_tiendo.php';
    }

    // UC 10: Đánh giá sơ bộ sản phẩm và xác nhận cho phép nghiệm thu
    public function danhGiaSoBo(): void {
        $gvId = AuthHelper::id();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $reportId = (int)($_POST['report_id'] ?? 0);
                $danhGia = $_POST['danh_gia_so_bo'] ?? ''; // 'DONG_Y_NGHIEM_THU', 'KHONG_DONG_Y'
                $nhanXet = trim($_POST['nhan_xet_so_bo'] ?? '');

                if (empty($danhGia) || empty($nhanXet)) {
                    throw new Exception("Vui lòng chọn quyết định đánh giá sơ bộ và nhập nhận xét chi tiết.");
                }

                $this->tongKetModel->teacherPreliminaryReview($reportId, $danhGia, $nhanXet);

                $msg = ($danhGia === 'DONG_Y_NGHIEM_THU')
                    ? "Xác nhận cho phép nghiệm thu thành công! Đề tài đã được chuyển sang trạng thái CHỜ NGHIỆM THU và thông báo tới Cán bộ Quản lý."
                    : "Đã lưu nhận xét sơ bộ (chưa cho phép nghiệm thu). Nhóm sinh viên sẽ cần chỉnh sửa, hoàn thiện thêm.";
                AuthHelper::setFlash('success', $msg);
                header("Location: " . App::url('index.php?action=gv-danhgia-sobo'));
                exit;

            } catch (Exception $e) {
                AuthHelper::setFlash('danger', $e->getMessage());
            }
        }

        $myTopics = $this->deTaiModel->getTopicsByTeacher($gvId);
        $summaryReviewList = [];
        foreach ($myTopics as $t) {
            $report = $this->tongKetModel->findByTopic($t['MaDeTai']);
            if ($report) {
                $summaryReviewList[] = [
                    'topic' => $t,
                    'report' => $report
                ];
            }
        }

        require dirname(__DIR__, 2) . '/views/giangvien/danhgia_sobo.php';
    }

    public function chiTietDeTai(): void {
        $gvId = AuthHelper::id();
        $maDeTai = $_GET['id'] ?? '';
        $topic = $this->deTaiModel->findById($maDeTai);

        if (!$topic) {
            AuthHelper::setFlash('danger', 'Không tìm thấy đề tài.');
            header("Location: " . App::url('index.php?action=gv-dashboard'));
            exit;
        }

        $members = $this->deTaiModel->getMembers($maDeTai);
        $editHistory = $this->deTaiModel->getEditHistory($maDeTai);
        $progressReports = $this->tienDoModel->getByTopic($maDeTai);
        $finalReport = $this->tongKetModel->findByTopic($maDeTai);

        require dirname(__DIR__, 2) . '/views/giangvien/chitiet_detai.php';
    }
}

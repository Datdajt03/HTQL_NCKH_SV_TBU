<?php
namespace App\Controllers;

use App\Config\App;
use App\Helpers\AuthHelper;
use App\Helpers\UploadHelper;
use App\Models\DotNCKHModel;
use App\Models\DeTaiModel;
use App\Models\BaoCaoTienDoModel;
use App\Models\BaoCaoTongKetModel;
use App\Models\KhenThuongModel;
use App\Models\UserModel;
use Exception;

class SinhVienController {
    private DeTaiModel $deTaiModel;
    private DotNCKHModel $dotModel;
    private BaoCaoTienDoModel $tienDoModel;
    private BaoCaoTongKetModel $tongKetModel;
    private KhenThuongModel $khenThuongModel;
    private UserModel $userModel;

    public function __construct() {
        AuthHelper::requireRole(App::ROLE_SINH_VIEN);
        $this->deTaiModel = new DeTaiModel();
        $this->dotModel = new DotNCKHModel();
        $this->tienDoModel = new BaoCaoTienDoModel();
        $this->tongKetModel = new BaoCaoTongKetModel();
        $this->khenThuongModel = new KhenThuongModel();
        $this->userModel = new UserModel();
    }

    public function dashboard(): void {
        $studentId = AuthHelper::id();
        $myTopics = $this->deTaiModel->getTopicsByStudent($studentId);
        $activeDots = $this->dotModel->getActive();
        $recentNotices = $this->dotModel->getNotifications();

        require dirname(__DIR__, 2) . '/views/sinhvien/dashboard.php';
    }

    // UC 1: Xem thông tin NCKH, biểu mẫu hướng dẫn
    public function thongTinNCKH(): void {
        $dots = $this->dotModel->getAll();
        $directions = $this->dotModel->getAllResearchDirections();
        $selectedDot = $_GET['dot_id'] ?? null;
        $notices = $this->dotModel->getNotifications($selectedDot);

        require dirname(__DIR__, 2) . '/views/sinhvien/thongtin_nckh.php';
    }

    // UC 2: Đăng ký đề tài
    public function dangKyDeTai(): void {
        $studentId = AuthHelper::id();
        $activeDots = $this->dotModel->getActive();
        $directions = $this->dotModel->getAllResearchDirections();
        $teachers = $this->userModel->getAllTeachers();
        $allStudents = $this->userModel->getAllStudents();

        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $tenDeTai = trim($_POST['tendetai'] ?? '');
                $maDot = $_POST['madot'] ?? '';
                $maHuongNC = $_POST['mahuongnc'] ?? '';
                $maGVHD = $_POST['magvhd'] ?? '';
                $mucTieu = trim($_POST['muctieu'] ?? '');
                $noiDungNC = trim($_POST['noidungnc'] ?? '');
                $sanPhamDuKien = trim($_POST['sanphamdukien'] ?? '');
                $kinhPhi = (float)str_replace(['.', ','], '', $_POST['kinhphi'] ?? '0');

                if (empty($tenDeTai) || empty($maDot) || empty($maHuongNC) || empty($maGVHD) || empty($mucTieu) || empty($noiDungNC) || empty($sanPhamDuKien)) {
                    throw new Exception("Vui lòng điền đầy đủ các thông tin bắt buộc.");
                }

                // Upload file Phiếu thuyết minh & Phiếu đề xuất
                $fileTM = UploadHelper::upload('file_thuyet_minh');
                $fileDX = UploadHelper::upload('file_de_xuat');

                if (!$fileTM || !$fileDX) {
                    throw new Exception("Vui lòng đính kèm đầy đủ file Phiếu thuyết minh và Phiếu đề xuất.");
                }

                // Xử lý thành viên nhóm
                $members = [];
                // Trưởng nhóm chính là sinh viên đang đăng nhập
                $members[] = [
                    'MaSV' => $studentId,
                    'VaiTro' => 'TRUONG_NHOM',
                    'NhiemVuCuThe' => trim($_POST['nhiemvu_truongnhom'] ?? 'Chủ nhiệm đề tài, điều phối chung')
                ];

                // Các thành viên khác nếu có
                if (!empty($_POST['extra_members']) && is_array($_POST['extra_members'])) {
                    foreach ($_POST['extra_members'] as $m) {
                        $mSV = trim($m['masv'] ?? '');
                        if (!empty($mSV) && $mSV !== $studentId) {
                            $members[] = [
                                'MaSV' => $mSV,
                                'VaiTro' => 'THANH_VIEN',
                                'NhiemVuCuThe' => trim($m['nhiemvu'] ?? 'Nghiên cứu và thực hiện nội dung được phân công')
                            ];
                        }
                    }
                }

                $topicData = [
                    'TenDeTai' => $tenDeTai,
                    'MaDot' => $maDot,
                    'MaHuongNC' => $maHuongNC,
                    'MaGVHD' => $maGVHD,
                    'MucTieu' => $mucTieu,
                    'NoiDungNC' => $noiDungNC,
                    'SanPhamDuKien' => $sanPhamDuKien,
                    'KinhPhiDuKien' => $kinhPhi,
                    'FilePhieuThuyetMinh' => $fileTM,
                    'FilePhieuDeXuat' => $fileDX
                ];

                $maDeTai = $this->deTaiModel->registerTopic($topicData, $members);
                AuthHelper::setFlash('success', "Đăng ký đề tài thành công! Mã đề tài của bạn là: {$maDeTai}. Hồ sơ đã được gửi đến GVHD để xét duyệt.");
                header("Location: " . App::url('index.php?action=sv-dashboard'));
                exit;

            } catch (Exception $e) {
                $error = $e->getMessage();
            }
        }

        require dirname(__DIR__, 2) . '/views/sinhvien/dangky_detai.php';
    }

    // UC 3: Sửa hồ sơ đề tài theo góp ý
    public function suaHoSoDeTai(): void {
        $studentId = AuthHelper::id();
        $maDeTai = $_GET['id'] ?? '';
        $topic = $this->deTaiModel->findById($maDeTai);

        if (!$topic) {
            AuthHelper::setFlash('danger', 'Không tìm thấy đề tài.');
            header("Location: " . App::url('index.php?action=sv-dashboard'));
            exit;
        }

        // Kiểm tra quyền: chỉ thành viên trong đề tài mới được sửa
        $members = $this->deTaiModel->getMembers($maDeTai);
        $isMember = false;
        foreach ($members as $m) {
            if ($m['MaSV'] === $studentId) {
                $isMember = true;
                break;
            }
        }
        if (!$isMember) {
            AuthHelper::setFlash('danger', 'Bạn không có quyền chỉnh sửa đề tài này.');
            header("Location: " . App::url('index.php?action=sv-dashboard'));
            exit;
        }

        $directions = $this->dotModel->getAllResearchDirections();
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $tenDeTai = trim($_POST['tendetai'] ?? '');
                $maHuongNC = $_POST['mahuongnc'] ?? '';
                $mucTieu = trim($_POST['muctieu'] ?? '');
                $noiDungNC = trim($_POST['noidungnc'] ?? '');
                $sanPhamDuKien = trim($_POST['sanphamdukien'] ?? '');
                $kinhPhi = (float)str_replace(['.', ','], '', $_POST['kinhphi'] ?? '0');
                $noiDungSua = trim($_POST['noidungsua'] ?? '');

                if (empty($tenDeTai) || empty($mucTieu) || empty($noiDungNC) || empty($noiDungSua)) {
                    throw new Exception("Vui lòng điền nội dung chỉnh sửa và các thông tin bắt buộc.");
                }

                $fileTM = UploadHelper::upload('file_thuyet_minh_moi');
                $fileDX = UploadHelper::upload('file_de_xuat_moi');

                $updateData = [
                    'TenDeTai' => $tenDeTai,
                    'MaHuongNC' => $maHuongNC,
                    'MucTieu' => $mucTieu,
                    'NoiDungNC' => $noiDungNC,
                    'SanPhamDuKien' => $sanPhamDuKien,
                    'KinhPhiDuKien' => $kinhPhi,
                    'FilePhieuThuyetMinh' => $fileTM,
                    'FilePhieuDeXuat' => $fileDX
                ];

                $this->deTaiModel->updateTopicProposal($maDeTai, $updateData, $studentId, $noiDungSua);
                AuthHelper::setFlash('success', "Cập nhật hồ sơ đề tài thành công! Hồ sơ đã được chuyển lại cho Cán bộ Quản lý để thẩm định.");
                header("Location: " . App::url('index.php?action=sv-chitiet-detai&id=' . $maDeTai));
                exit;

            } catch (Exception $e) {
                $error = $e->getMessage();
            }
        }

        $editHistory = $this->deTaiModel->getEditHistory($maDeTai);
        require dirname(__DIR__, 2) . '/views/sinhvien/suahoso_detai.php';
    }

    // UC 4: Nộp báo cáo tiến độ định kỳ
    public function baoCaoTienDo(): void {
        $studentId = AuthHelper::id();
        $maDeTai = $_GET['id'] ?? '';
        $topic = $this->deTaiModel->findById($maDeTai);

        if (!$topic) {
            AuthHelper::setFlash('danger', 'Không tìm thấy đề tài.');
            header("Location: " . App::url('index.php?action=sv-dashboard'));
            exit;
        }

        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $dotBaoCao = trim($_POST['dotbaocao'] ?? '');
                $phanTram = (int)($_POST['phantram'] ?? 0);
                $congViec = trim($_POST['congviec'] ?? '');
                $khoKhan = trim($_POST['khokhan'] ?? '');

                if (empty($dotBaoCao) || empty($congViec)) {
                    throw new Exception("Vui lòng nhập tên đợt báo cáo và nội dung công việc đã hoàn thành.");
                }
                if ($phanTram < 0 || $phanTram > 100) {
                    throw new Exception("Tiến độ hoàn thành phải từ 0% đến 100%.");
                }

                $fileBC = UploadHelper::upload('file_baocao');
                if (!$fileBC) {
                    throw new Exception("Vui lòng đính kèm file báo cáo tiến độ (PDF/Word).");
                }

                $this->tienDoModel->createReport([
                    'MaDeTai' => $maDeTai,
                    'DotBaoCao' => $dotBaoCao,
                    'PhanTramHoanThanh' => $phanTram,
                    'CongViecDaLam' => $congViec,
                    'KhoKhanVuongMac' => $khoKhan,
                    'FileBaoCao' => $fileBC
                ]);

                AuthHelper::setFlash('success', "Nộp báo cáo tiến độ thành công! GVHD sẽ sớm xem xét và nhận xét đánh giá.");
                header("Location: " . App::url('index.php?action=sv-baocao-tiendo&id=' . $maDeTai));
                exit;

            } catch (Exception $e) {
                $error = $e->getMessage();
            }
        }

        $reports = $this->tienDoModel->getByTopic($maDeTai);
        require dirname(__DIR__, 2) . '/views/sinhvien/baocao_tiendo.php';
    }

    // UC 5: Nộp báo cáo tổng kết & sản phẩm nghiên cứu
    public function baoCaoTongKet(): void {
        $studentId = AuthHelper::id();
        $maDeTai = $_GET['id'] ?? '';
        $topic = $this->deTaiModel->findById($maDeTai);

        if (!$topic) {
            AuthHelper::setFlash('danger', 'Không tìm thấy đề tài.');
            header("Location: " . App::url('index.php?action=sv-dashboard'));
            exit;
        }

        $existingReport = $this->tongKetModel->findByTopic($maDeTai);
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $tomTat = trim($_POST['tomtat'] ?? '');
                if (empty($tomTat)) {
                    throw new Exception("Vui lòng nhập tóm tắt kết quả nghiên cứu.");
                }

                $fileBC = UploadHelper::upload('file_baocaotongket');
                if (!$fileBC && empty($existingReport['FileBaoCaoTongKet'])) {
                    throw new Exception("Vui lòng đính kèm file toàn văn Báo cáo tổng kết.");
                }
                $finalFile = $fileBC ?: $existingReport['FileBaoCaoTongKet'];

                // Xử lý danh sách sản phẩm nghiên cứu
                $products = [];
                if (!empty($_POST['products']) && is_array($_POST['products'])) {
                    foreach ($_POST['products'] as $idx => $p) {
                        $tenSP = trim($p['ten'] ?? '');
                        $loaiSP = $p['loai'] ?? '';
                        if (!empty($tenSP) && !empty($loaiSP)) {
                            // File minh chứng sản phẩm nếu có
                            $fileMCKey = "product_file_{$idx}";
                            $fileMC = UploadHelper::upload($fileMCKey);
                            $products[] = [
                                'TenSanPham' => $tenSP,
                                'LoaiSanPham' => $loaiSP,
                                'MoTaSanPham' => trim($p['mota'] ?? ''),
                                'LienKetMinhChung' => trim($p['link'] ?? ''),
                                'FileMinhChung' => $fileMC
                            ];
                        }
                    }
                }

                if (empty($products)) {
                    throw new Exception("Vui lòng thêm ít nhất 01 sản phẩm nghiên cứu (Phần mềm, Bài báo khoa học, Mô hình, Quy trình).");
                }

                $this->tongKetModel->submitFinalReport([
                    'MaDeTai' => $maDeTai,
                    'TomTatKetQua' => $tomTat,
                    'FileBaoCaoTongKet' => $finalFile
                ], $products);

                AuthHelper::setFlash('success', "Nộp Báo cáo tổng kết & Sản phẩm nghiên cứu thành công! GVHD sẽ tiến hành đánh giá sơ bộ để xác nhận cho phép nghiệm thu.");
                header("Location: " . App::url('index.php?action=sv-baocao-tongket&id=' . $maDeTai));
                exit;

            } catch (Exception $e) {
                $error = $e->getMessage();
            }
        }

        require dirname(__DIR__, 2) . '/views/sinhvien/baocao_tongket.php';
    }

    // UC 6: Tra cứu kết quả đánh giá, xếp loại và khen thưởng
    public function xemKetQua(): void {
        $studentId = AuthHelper::id();
        $myTopics = $this->deTaiModel->getTopicsByStudent($studentId);
        
        $results = [];
        foreach ($myTopics as $t) {
            $award = $this->khenThuongModel->getByTopic($t['MaDeTai']);
            $results[] = [
                'topic' => $t,
                'award' => $award
            ];
        }

        require dirname(__DIR__, 2) . '/views/sinhvien/xem_ketqua.php';
    }

    public function chiTietDeTai(): void {
        $maDeTai = $_GET['id'] ?? '';
        $topic = $this->deTaiModel->findById($maDeTai);
        if (!$topic) {
            AuthHelper::setFlash('danger', 'Không tìm thấy đề tài.');
            header("Location: " . App::url('index.php?action=sv-dashboard'));
            exit;
        }

        $members = $this->deTaiModel->getMembers($maDeTai);
        $editHistory = $this->deTaiModel->getEditHistory($maDeTai);
        $progressReports = $this->tienDoModel->getByTopic($maDeTai);
        $finalReport = $this->tongKetModel->findByTopic($maDeTai);
        $award = $this->khenThuongModel->getByTopic($maDeTai);

        require dirname(__DIR__, 2) . '/views/sinhvien/chitiet_detai.php';
    }
}

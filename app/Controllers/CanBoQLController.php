<?php
namespace App\Controllers;

use App\Config\App;
use App\Helpers\AuthHelper;
use App\Helpers\UploadHelper;
use App\Models\DotNCKHModel;
use App\Models\DeTaiModel;
use App\Models\BaoCaoTienDoModel;
use App\Models\BaoCaoTongKetModel;
use App\Models\HoiDongModel;
use App\Models\KhenThuongModel;
use App\Models\UserModel;
use Exception;

class CanBoQLController {
    private DotNCKHModel $dotModel;
    private DeTaiModel $deTaiModel;
    private BaoCaoTienDoModel $tienDoModel;
    private BaoCaoTongKetModel $tongKetModel;
    private HoiDongModel $hoiDongModel;
    private KhenThuongModel $khenThuongModel;
    private UserModel $userModel;

    public function __construct() {
        AuthHelper::requireRole([App::ROLE_CAN_BO_QL, App::ROLE_ADMIN]);
        $this->dotModel = new DotNCKHModel();
        $this->deTaiModel = new DeTaiModel();
        $this->tienDoModel = new BaoCaoTienDoModel();
        $this->tongKetModel = new BaoCaoTongKetModel();
        $this->hoiDongModel = new HoiDongModel();
        $this->khenThuongModel = new KhenThuongModel();
        $this->userModel = new UserModel();
    }

    public function dashboard(): void {
        $stats = $this->khenThuongModel->getSystemStatistics();
        $pendingApprove = $this->deTaiModel->getAllTopics('CHO_THAM_DINH');
        $readyForDefense = $this->deTaiModel->getAllTopics('CHO_NGHIEM_THU');
        $councils = $this->hoiDongModel->getAllCouncils();
        $userStats = $this->userModel->getUserStats();
        $financialStats = $this->deTaiModel->getFinancialOverview();

        require dirname(__DIR__, 2) . '/views/canboql/dashboard.php';
    }

    // UC 11: Quản lý đợt NCKH, hướng nghiên cứu, thông báo biểu mẫu
    public function quanLyDot(): void {
        $officerId = AuthHelper::id();
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $subAction = $_POST['sub_action'] ?? '';
            try {
                if ($subAction === 'create_dot') {
                    $maDot = trim($_POST['madot'] ?? '');
                    $tenDot = trim($_POST['tendot'] ?? '');
                    $namHoc = trim($_POST['namhoc'] ?? '');
                    if (empty($maDot) || empty($tenDot) || empty($namHoc)) {
                        throw new Exception("Vui lòng nhập đầy đủ mã đợt, tên đợt và năm học.");
                    }
                    $this->dotModel->create([
                        'MaDot'          => $maDot,
                        'TenDot'         => $tenDot,
                        'NamHoc'         => $namHoc,
                        'NgayBatDau'     => $_POST['ngaybatdau'],
                        'HanDangKy'      => $_POST['handangky'],
                        'HanNopBaoCaoTD' => $_POST['hannopbaocaotd'],
                        'HanNopNghiemThu'=> $_POST['hannopnghiemthu'],
                        'NgayKetThuc'    => $_POST['ngayketthuc'],
                        'TrangThai'      => $_POST['trangthai'] ?? 'MO_DANG_KY',
                        'MaCanBoTao'     => $officerId
                    ]);
                    AuthHelper::setFlash('success', "Tạo đợt NCKH mới ({$maDot}) thành công!");
                } elseif ($subAction === 'update_dot') {
                    $maDot = $_POST['madot'] ?? '';
                    $this->dotModel->update($maDot, [
                        'TenDot'         => $_POST['tendot'],
                        'NamHoc'         => $_POST['namhoc'],
                        'NgayBatDau'     => $_POST['ngaybatdau'],
                        'HanDangKy'      => $_POST['handangky'],
                        'HanNopBaoCaoTD' => $_POST['hannopbaocaotd'],
                        'HanNopNghiemThu'=> $_POST['hannopnghiemthu'],
                        'NgayKetThuc'    => $_POST['ngayketthuc'],
                        'TrangThai'      => $_POST['trangthai']
                    ]);
                    AuthHelper::setFlash('success', "Cập nhật đợt NCKH ({$maDot}) thành công!");
                } elseif ($subAction === 'delete_dot') {
                    $maDot = $_POST['madot'] ?? '';
                    $this->dotModel->delete($maDot);
                    AuthHelper::setFlash('success', "Đã xóa đợt NCKH ({$maDot}) thành công!");
                } elseif ($subAction === 'create_direction') {
                    $maHNC = trim($_POST['mahuongnc'] ?? '');
                    $tenHNC = trim($_POST['tenhuongnc'] ?? '');
                    $mota = trim($_POST['mota'] ?? '');
                    if (empty($maHNC) || empty($tenHNC)) {
                        throw new Exception("Vui lòng nhập mã và tên hướng nghiên cứu.");
                    }
                    $this->dotModel->createResearchDirection($maHNC, $tenHNC, $mota);
                    AuthHelper::setFlash('success', "Thêm hướng nghiên cứu mới thành công!");
                } elseif ($subAction === 'update_direction') {
                    $maHNC = trim($_POST['mahuongnc'] ?? '');
                    $tenHNC = trim($_POST['tenhuongnc'] ?? '');
                    $mota = trim($_POST['mota'] ?? '');
                    if (empty($maHNC) || empty($tenHNC)) {
                        throw new Exception("Vui lòng nhập mã và tên hướng nghiên cứu.");
                    }
                    $this->dotModel->updateResearchDirection($maHNC, $tenHNC, $mota);
                    AuthHelper::setFlash('success', "Cập nhật hướng nghiên cứu thành công!");
                } elseif ($subAction === 'delete_direction') {
                    $maHNC = trim($_POST['mahuongnc'] ?? '');
                    $this->dotModel->deleteResearchDirection($maHNC);
                    AuthHelper::setFlash('success', "Đã xóa hướng nghiên cứu thành công!");
                } elseif ($subAction === 'add_notice') {
                    $tieuDe = trim($_POST['tieude'] ?? '');
                    $loai = $_POST['loai'] ?? 'THONG_BAO';
                    $maDot = $_POST['madot'] ?? null;
                    $noiDung = trim($_POST['noidung'] ?? '');
                    if (empty($tieuDe)) {
                        throw new Exception("Vui lòng nhập tiêu đề thông báo / biểu mẫu.");
                    }
                    $fileDinhKem = UploadHelper::upload('file_dinhkem');

                    $this->dotModel->addNotification([
                        'MaDot' => $maDot,
                        'TieuDe' => $tieuDe,
                        'Loai' => $loai,
                        'NoiDung' => $noiDung,
                        'FileDinhKem' => $fileDinhKem,
                        'NguoiDang' => $officerId
                    ]);
                    AuthHelper::setFlash('success', "Đăng tải thông báo / biểu mẫu thành công!");
                } elseif ($subAction === 'update_notice') {
                    $noticeId = (int)($_POST['id'] ?? 0);
                    $tieuDe = trim($_POST['tieude'] ?? '');
                    $loai = $_POST['loai'] ?? 'THONG_BAO';
                    $maDot = $_POST['madot'] ?? null;
                    $noiDung = trim($_POST['noidung'] ?? '');
                    if (!$noticeId || empty($tieuDe)) {
                        throw new Exception("Vui lòng nhập tiêu đề hợp lệ.");
                    }
                    $fileDinhKem = UploadHelper::upload('file_dinhkem');

                    $this->dotModel->updateNotification($noticeId, [
                        'MaDot' => $maDot,
                        'TieuDe' => $tieuDe,
                        'Loai' => $loai,
                        'NoiDung' => $noiDung,
                        'FileDinhKem' => $fileDinhKem
                    ]);
                    AuthHelper::setFlash('success', "Cập nhật thông báo / biểu mẫu thành công!");
                } elseif ($subAction === 'delete_notice') {
                    $noticeId = (int)($_POST['id'] ?? 0);
                    $this->dotModel->deleteNotification($noticeId);
                    AuthHelper::setFlash('success', "Đã xóa thông báo / biểu mẫu thành công!");
                }
                header("Location: " . App::url('index.php?action=cb-quanly-dot'));
                exit;

            } catch (\Throwable $e) {
                AuthHelper::setFlash('danger', "Thao tác thất bại: " . $e->getMessage());
                header("Location: " . App::url('index.php?action=cb-quanly-dot'));
                exit;
            }
        }

        $dots = $this->dotModel->getAll();
        $directions = $this->dotModel->getAllResearchDirections();
        $notices = $this->dotModel->getNotifications();

        require dirname(__DIR__, 2) . '/views/canboql/quanly_dot.php';
    }

    // UC 12: Thẩm định & Quản lý đề tài NCKH
    public function thamDinhDeTai(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $subAction = $_POST['sub_action'] ?? '';
                if ($subAction === 'delete_topic') {
                    $maDeTai = $_POST['madetai'] ?? '';
                    if (empty($maDeTai)) {
                        throw new Exception("Thiếu mã đề tài cần xóa.");
                    }
                    $this->deTaiModel->deleteTopic($maDeTai);
                    AuthHelper::setFlash('success', "Đã xóa đề tài {$maDeTai} thành công khỏi hệ thống!");
                } elseif ($subAction === 'quick_edit_topic') {
                    $maDeTai = $_POST['madetai'] ?? '';
                    $tenDeTai = trim($_POST['tendetai'] ?? '');
                    $maHuongNC = $_POST['mahuongnc'] ?? '';
                    $kinhPhi = floatval($_POST['kinhphi'] ?? 0);
                    $mucTieu = trim($_POST['muctieu'] ?? '');
                    $sanPham = trim($_POST['sanpham'] ?? '');

                    if (empty($maDeTai) || empty($tenDeTai) || empty($maHuongNC)) {
                        throw new Exception("Vui lòng điền đầy đủ tên đề tài và hướng nghiên cứu.");
                    }
                    $this->deTaiModel->updateTopicBasic($maDeTai, [
                        'TenDeTai'      => $tenDeTai,
                        'MaHuongNC'     => $maHuongNC,
                        'KinhPhiDuKien' => $kinhPhi,
                        'MucTieu'       => $mucTieu,
                        'SanPhamDuKien' => $sanPham
                    ]);
                    AuthHelper::setFlash('success', "Đã cập nhật thông tin cơ bản cho đề tài {$maDeTai}!");
                } else {
                    $maDeTai = $_POST['madetai'] ?? '';
                    $decision = $_POST['decision'] ?? ''; // 'PHE_DUYET', 'YEU_CAU_SUA', 'TU_CHOI'
                    $comments = trim($_POST['comments'] ?? '');

                    if (empty($maDeTai) || empty($decision)) {
                        throw new Exception("Thiếu thông tin xét duyệt đề tài.");
                    }

                    $this->deTaiModel->officerApprove($maDeTai, $decision, $comments);

                    $msg = match ($decision) {
                        'PHE_DUYET'   => "Đã PHÊ DUYỆT đề tài {$maDeTai}! Đề tài chính thức chuyển sang giai đoạn ĐANG THỰC HIỆN.",
                        'YEU_CAU_SUA' => "Đã gửi YÊU CẦU SỬA ĐỔI tới nhóm sinh viên đề tài {$maDeTai}.",
                        default       => "Đã TỪ CHỐI / HỦY đề tài {$maDeTai}."
                    };
                    AuthHelper::setFlash('success', $msg);
                }

                header("Location: " . App::url('index.php?action=cb-thamdinh-detai'));
                exit;

            } catch (\Throwable $e) {
                AuthHelper::setFlash('danger', "Thao tác thất bại: " . $e->getMessage());
                header("Location: " . App::url('index.php?action=cb-thamdinh-detai'));
                exit;
            }
        }

        $filterDot = $_GET['dot'] ?? null;
        $pendingTopics = $this->deTaiModel->getAllTopics('CHO_THAM_DINH', $filterDot);
        $allTopics = $this->deTaiModel->getAllTopics(null, $filterDot);
        $dots = $this->dotModel->getAll();
        $directions = $this->dotModel->getAllResearchDirections();

        require dirname(__DIR__, 2) . '/views/canboql/thamdinh_detai.php';
    }

    // UC 13: Quản lý tiến độ toàn trường
    public function quanLyTienDo(): void {
        $filterDot = $_GET['dot'] ?? null;
        $progressList = $this->tienDoModel->getAllProgressOverview($filterDot);
        $dots = $this->dotModel->getAll();

        require dirname(__DIR__, 2) . '/views/canboql/quanly_tiendo.php';
    }

    // UC 13.1: Cán bộ QL thẩm định và phê duyệt tiến độ
    public function duyetTienDo(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $reportId = (int)($_POST['report_id'] ?? 0);
                $danhGia = trim($_POST['danh_gia_can_bo'] ?? ''); // 'DAT_TIEN_DO', 'CHAM_TIEN_DO', 'CAN_NHAC_NHO'
                $nhanXet = trim($_POST['nhan_xet_can_bo'] ?? '');

                if (!$reportId || empty($danhGia)) {
                    throw new Exception("Vui lòng chọn kết luận thẩm định tiến độ của Cán bộ Quản lý.");
                }

                $this->tienDoModel->canBoReview($reportId, $danhGia, $nhanXet);
                AuthHelper::setFlash('success', "Cán bộ quản lý đã lưu thẩm định và phê duyệt tiến độ thành công!");
            } catch (Exception $e) {
                AuthHelper::setFlash('danger', $e->getMessage());
            }
        }
        $redirectUrl = !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : App::url('index.php?action=cb-quanly-tiendo');
        header("Location: " . $redirectUrl);
        exit;
    }

    // UC 14: Tổ chức hội đồng nghiệm thu + Nhập điểm đánh giá
    public function toChucNghiemThu(): void {
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $subAction = $_POST['sub_action'] ?? '';
            try {
                if ($subAction === 'create_council') {
                    $maHD = trim($_POST['mahoidong'] ?? '');
                    $tenHD = trim($_POST['tenhoidong'] ?? '');
                    $maDot = $_POST['madot'] ?? '';
                    $loaiHD = $_POST['loaihoidong'] ?? 'NGHIEM_THU';
                    $ngayTL = $_POST['ngaythanhlap'] ?? date('Y-m-d');
                    $soQD = trim($_POST['soquyetdinh'] ?? '');

                    if (empty($maHD) || empty($tenHD) || empty($maDot)) {
                        throw new Exception("Vui lòng điền mã hội đồng, tên hội đồng và đợt NCKH.");
                    }

                    // Thành viên hội đồng
                    $members = [];
                    if (!empty($_POST['members']) && is_array($_POST['members'])) {
                        foreach ($_POST['members'] as $m) {
                            $mUser = trim($m['user_id'] ?? '');
                            $mRole = trim($m['role'] ?? '');
                            if (!empty($mUser) && !empty($mRole)) {
                                $members[] = [
                                    'MaNguoiDung'   => $mUser,
                                    'VaiTroHoiDong' => $mRole
                                ];
                            }
                        }
                    }

                    if (count($members) < 3) {
                        throw new Exception("Hội đồng nghiệm thu cần tối thiểu 3 thành viên (Chủ tịch, Thư ký, Phản biện).");
                    }

                    $this->hoiDongModel->createCouncil([
                        'MaHoiDong'    => $maHD,
                        'TenHoiDong'   => $tenHD,
                        'MaDot'        => $maDot,
                        'LoaiHoiDong'  => $loaiHD,
                        'NgayThanhLap' => $ngayTL,
                        'SoQuyetDinh'  => $soQD,
                        'TrangThai'    => 'HOAT_DONG'
                    ], $members);

                    AuthHelper::setFlash('success', "Thành lập Hội đồng nghiệm thu ({$maHD}) thành công!");

                } elseif ($subAction === 'assign_topic') {
                    $maHD = $_POST['mahoidong'] ?? '';
                    $maDeTai = $_POST['madetai'] ?? '';
                    $thoiGian = $_POST['thoigianbaocao'] ?? '';
                    $diaDiem = trim($_POST['diadiem'] ?? '');

                    if (empty($maHD) || empty($maDeTai) || empty($thoiGian) || empty($diaDiem)) {
                        throw new Exception("Vui lòng điền đầy đủ đề tài, thời gian và địa điểm bảo vệ.");
                    }

                    $this->hoiDongModel->assignTopic([
                        'MaHoiDong'      => $maHD,
                        'MaDeTai'        => $maDeTai,
                        'ThoiGianBaoCao' => $thoiGian,
                        'DiaDiem'        => $diaDiem
                    ]);

                    AuthHelper::setFlash('success', "Phân công đề tài {$maDeTai} vào Hội đồng {$maHD} thành công!");

                } elseif ($subAction === 'score_sheet') {
                    $maPC = (int)($_POST['maphancong'] ?? 0);
                    $maGK = $_POST['magiamkhao'] ?? '';
                    $diemTQ = (float)($_POST['diem_tongquan'] ?? 0);
                    $diemPP = (float)($_POST['diem_phuongphap'] ?? 0);
                    $diemSP = (float)($_POST['diem_sanpham'] ?? 0);
                    $diemBC = (float)($_POST['diem_baocao'] ?? 0);
                    $nhanXet = trim($_POST['nhanxet_chitiet'] ?? '');

                    if (empty($maPC) || empty($maGK)) {
                        throw new Exception("Thiếu thông tin phân công bảo vệ hoặc giám khảo.");
                    }

                    $this->hoiDongModel->saveScoreSheet([
                        'MaPhanCong'     => $maPC,
                        'MaGiamKhao'     => $maGK,
                        'DiemTongQuan'   => $diemTQ,
                        'DiemPhuongPhap' => $diemPP,
                        'DiemSanPham'    => $diemSP,
                        'DiemBaoCao'     => $diemBC,
                        'NhanXetChiTiet' => $nhanXet
                    ]);

                    AuthHelper::setFlash('success', "Đã ghi nhận phiếu chấm điểm của Giám khảo ({$maGK})!");

                } elseif ($subAction === 'finalize_defense') {
                    $maPC = (int)($_POST['maphancong'] ?? 0);
                    $diemTB = (float)($_POST['diem_trungbinh'] ?? 0);
                    $ketLuan = $_POST['ketluan'] ?? 'DAT';
                    $fileBienBan = UploadHelper::upload('file_bienban');

                    $this->hoiDongModel->finalizeDefense($maPC, $diemTB, $ketLuan, $fileBienBan);
                    AuthHelper::setFlash('success', "Tổng kết kết quả nghiệm thu thành công! Điểm trung bình: {$diemTB}, Kết luận: {$ketLuan}.");

                } elseif ($subAction === 'update_council') {
                    $maHD = trim($_POST['mahoidong'] ?? '');
                    $tenHD = trim($_POST['tenhoidong'] ?? '');
                    $loaiHD = $_POST['loaihoidong'] ?? 'NGHIEM_THU';
                    $ngayTL = $_POST['ngaythanhlap'] ?? date('Y-m-d');
                    $soQD = trim($_POST['soquyetdinh'] ?? '');
                    $trangThai = $_POST['trangthai'] ?? 'HOAT_DONG';

                    if (empty($maHD) || empty($tenHD)) {
                        throw new Exception("Mã hội đồng và tên hội đồng không được để trống.");
                    }

                    $this->hoiDongModel->updateCouncil($maHD, [
                        'TenHoiDong'   => $tenHD,
                        'LoaiHoiDong'  => $loaiHD,
                        'NgayThanhLap' => $ngayTL,
                        'SoQuyetDinh'  => $soQD,
                        'TrangThai'    => $trangThai
                    ]);

                    AuthHelper::setFlash('success', "Cập nhật thông tin hội đồng ({$maHD}) thành công!");
                    header("Location: " . App::url('index.php?action=cb-tochuc-nghiemthu&council_id=' . urlencode($maHD)));
                    exit;

                } elseif ($subAction === 'delete_council') {
                    $maHD = trim($_POST['mahoidong'] ?? '');
                    if (empty($maHD)) {
                        throw new Exception("Thiếu mã hội đồng cần xóa.");
                    }
                    $this->hoiDongModel->deleteCouncil($maHD);
                    AuthHelper::setFlash('success', "Đã xóa hội đồng ({$maHD}) thành công!");
                    header("Location: " . App::url('index.php?action=cb-tochuc-nghiemthu'));
                    exit;

                } elseif ($subAction === 'unassign_topic') {
                    $maPC = (int)($_POST['maphancong'] ?? 0);
                    $councilId = $_POST['mahoidong'] ?? '';
                    if ($maPC <= 0) {
                        throw new Exception("Thiếu thông tin phân công bảo vệ cần gỡ.");
                    }
                    $this->hoiDongModel->unassignTopic($maPC);
                    AuthHelper::setFlash('success', "Đã gỡ đề tài khỏi phân công hội đồng thành công!");
                    header("Location: " . App::url('index.php?action=cb-tochuc-nghiemthu' . (!empty($councilId) ? '&council_id=' . urlencode($councilId) : '')));
                    exit;

                } elseif ($subAction === 'delete_scoresheet') {
                    $maPhieu = (int)($_POST['maphieu'] ?? 0);
                    $councilId = $_POST['mahoidong'] ?? '';
                    if ($maPhieu <= 0) {
                        throw new Exception("Thiếu mã phiếu điểm cần xóa.");
                    }
                    $this->hoiDongModel->deleteScoreSheet($maPhieu);
                    AuthHelper::setFlash('success', "Đã xóa phiếu chấm điểm thành công!");
                    header("Location: " . App::url('index.php?action=cb-tochuc-nghiemthu' . (!empty($councilId) ? '&council_id=' . urlencode($councilId) : '')));
                    exit;
                }

                $redirectCouncil = !empty($_POST['mahoidong']) ? '&council_id=' . urlencode($_POST['mahoidong']) : '';
                header("Location: " . App::url('index.php?action=cb-tochuc-nghiemthu' . $redirectCouncil));
                exit;

            } catch (\Throwable $e) {
                $redirectCouncil = !empty($_POST['mahoidong']) ? '&council_id=' . urlencode($_POST['mahoidong']) : '';
                AuthHelper::setFlash('danger', "Thao tác thất bại: " . $e->getMessage());
                header("Location: " . App::url('index.php?action=cb-tochuc-nghiemthu' . $redirectCouncil));
                exit;
            }
        }

        $councils = $this->hoiDongModel->getAllCouncils();
        $dots = $this->dotModel->getAll();
        $teachers = $this->userModel->getAllTeachers();
        $readyTopics = $this->deTaiModel->getAllTopics('CHO_NGHIEM_THU');

        // Lấy chi tiết của hội đồng được chọn xem nếu có
        $selectedCouncilId = $_GET['council_id'] ?? ($councils[0]['MaHoiDong'] ?? null);
        $selectedCouncil = null;
        $councilMembers = [];
        $assignedTopics = [];
        if ($selectedCouncilId) {
            $selectedCouncil = $this->hoiDongModel->findById($selectedCouncilId);
            $councilMembers = $this->hoiDongModel->getCouncilMembers($selectedCouncilId);
            $assignedTopics = $this->hoiDongModel->getAssignmentsByCouncil($selectedCouncilId);
            foreach ($assignedTopics as &$assign) {
                $assign['scoreSheets'] = $this->hoiDongModel->getScoreSheets($assign['MaPhanCong']);
            }
            unset($assign);
        }

        require dirname(__DIR__, 2) . '/views/canboql/tochuc_nghiemthu.php';
    }

    // UC 15: Xếp loại và Khen thưởng
    public function xepLoaiKhenThuong(): void {
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $subAction = $_POST['sub_action'] ?? '';
            try {
                if ($subAction === 'delete_award') {
                    $maDeTai = $_POST['madetai'] ?? '';
                    if (empty($maDeTai)) {
                        throw new Exception("Thiếu thông tin mã đề tài để xóa kết quả khen thưởng.");
                    }
                    $this->khenThuongModel->deleteAward($maDeTai);
                    AuthHelper::setFlash('success', "Đã xóa kết quả xếp loại / khen thưởng của đề tài {$maDeTai} thành công!");
                    header("Location: " . App::url('index.php?action=cb-xeploai-khenthuong'));
                    exit;
                }

                $maDeTai = $_POST['madetai'] ?? '';
                $diem = (float)($_POST['diem_tongket'] ?? 0);
                $xepLoai = $_POST['xep_loai'] ?? '';
                $danhHieu = $_POST['danh_hieu'] ?? 'KHONG';
                $soQD = trim($_POST['so_quyetdinh'] ?? '');
                $tienThuong = (float)str_replace(['.', ','], '', $_POST['tien_thuong'] ?? '0');
                $ghiChu = trim($_POST['ghi_chu'] ?? '');
                $ngayQD = $_POST['ngay_quyetdinh'] ?? date('Y-m-d');

                if (empty($maDeTai)) {
                    throw new Exception("Vui lòng chọn đề tài để xếp loại khen thưởng.");
                }

                $this->khenThuongModel->saveAward([
                    'MaDeTai'            => $maDeTai,
                    'DiemTongKet'        => $diem,
                    'XepLoai'            => $xepLoai,
                    'DanhHieuKhenThuong' => $danhHieu,
                    'SoQuyetDinh'        => $soQD,
                    'TienThuong'         => $tienThuong,
                    'GhiChu'             => $ghiChu,
                    'NgayQuyetDinh'      => $ngayQD
                ]);

                AuthHelper::setFlash('success', "Cập nhật kết quả xếp loại và khen thưởng cho đề tài {$maDeTai} thành công!");
                header("Location: " . App::url('index.php?action=cb-xeploai-khenthuong'));
                exit;

            } catch (\Throwable $e) {
                AuthHelper::setFlash('danger', "Thao tác thất bại: " . $e->getMessage());
                header("Location: " . App::url('index.php?action=cb-xeploai-khenthuong'));
                exit;
            }
        }

        $filterDot = $_GET['dot'] ?? null;
        $awards = $this->khenThuongModel->getAllAwards($filterDot);
        $dots = $this->dotModel->getAll();
        // Các đề tài đã nghiệm thu để có thể chọn xếp loại
        $defendedTopics = $this->deTaiModel->getAllTopics('DA_NGHIEM_THU', $filterDot);

        require dirname(__DIR__, 2) . '/views/canboql/xeploai_khenthuong.php';
    }

    public function thongKeBaoCao(): void {
        $filterDot = $_GET['dot'] ?? null;
        $stats = $this->khenThuongModel->getSystemStatistics($filterDot);
        $awards = $this->khenThuongModel->getAllAwards($filterDot);
        $dots = $this->dotModel->getAll();

        require dirname(__DIR__, 2) . '/views/canboql/thongke_baocao.php';
    }

    public function inDanhSach(): void {
        $filterDot = $_GET['dot'] ?? null;
        $awards = $this->khenThuongModel->getAllAwards($filterDot);
        $selectedDot = $filterDot ? $this->dotModel->findById($filterDot) : null;

        require dirname(__DIR__, 2) . '/views/canboql/in_danhsach.php';
    }

    public function chiTietDeTai(): void {
        $maDeTai = $_GET['id'] ?? '';
        $topic = $this->deTaiModel->findById($maDeTai);

        if (!$topic) {
            AuthHelper::setFlash('danger', 'Không tìm thấy đề tài.');
            header("Location: " . App::url('index.php?action=cb-dashboard'));
            exit;
        }

        $members = $this->deTaiModel->getMembers($maDeTai);
        $editHistory = $this->deTaiModel->getEditHistory($maDeTai);
        $progressReports = $this->tienDoModel->getByTopic($maDeTai);
        $finalReport = $this->tongKetModel->findByTopic($maDeTai);
        $assignment = $this->hoiDongModel->getAssignmentByTopic($maDeTai);
        $scoreSheets = $assignment ? $this->hoiDongModel->getScoreSheets($assignment['MaPhanCong']) : [];
        $award = $this->khenThuongModel->getByTopic($maDeTai);

        require dirname(__DIR__, 2) . '/views/canboql/chitiet_detai.php';
    }

    public function xoaDeTai(): void {
        $maDeTai = $_GET['id'] ?? ($_POST['madetai'] ?? '');
        if (empty($maDeTai)) {
            AuthHelper::setFlash('danger', 'Thiếu mã đề tài cần xóa.');
            header("Location: " . App::url('index.php?action=cb-thamdinh-detai'));
            exit;
        }

        try {
            $this->deTaiModel->deleteTopic($maDeTai);
            AuthHelper::setFlash('success', "Đã xóa đề tài {$maDeTai} thành công!");
        } catch (Exception $e) {
            AuthHelper::setFlash('danger', "Lỗi khi xóa đề tài: " . $e->getMessage());
        }

        header("Location: " . App::url('index.php?action=cb-thamdinh-detai'));
        exit;
    }

    // UC Quản lý Người Dùng (Admin & Cán bộ QL)
    public function quanLyNguoiDung(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $subAction = $_POST['sub_action'] ?? '';
            try {
                if ($subAction === 'create_user') {
                    $username = strtoupper(trim($_POST['username'] ?? ''));
                    $hoTen = trim($_POST['hoten'] ?? '');
                    $email = trim(strtolower($_POST['email'] ?? ''));
                    $sdt = trim($_POST['sdt'] ?? '');
                    $role = $_POST['vaitro'] ?? App::ROLE_SINH_VIEN;
                    $password = $_POST['password'] ?? '123456';

                    if (empty($username) || empty($hoTen) || empty($email)) {
                        throw new Exception("Vui lòng nhập đầy đủ Mã người dùng, Họ tên và Email.");
                    }
                    if ($this->userModel->isUsernameTaken($username)) {
                        throw new Exception("Mã người dùng '{$username}' đã tồn tại.");
                    }
                    if ($this->userModel->isEmailTaken($email)) {
                        throw new Exception("Email '{$email}' đã được đăng ký bởi tài khoản khác.");
                    }

                    $roleData = [];
                    if ($role === App::ROLE_SINH_VIEN) {
                        $roleData = [
                            'Lop'      => trim($_POST['sv_lop'] ?? 'K64 CNTT'),
                            'Khoa'     => trim($_POST['sv_khoa'] ?? 'Khoa Công nghệ Thông tin'),
                            'NganhHoc' => trim($_POST['sv_nganh'] ?? 'Khoa học Máy tính'),
                            'KhoaHoc'  => trim($_POST['sv_khoahoc'] ?? '2023 - 2027')
                        ];
                    } elseif ($role === App::ROLE_GIANG_VIEN) {
                        $roleData = [
                            'HocHam'           => !empty($_POST['gv_hocham']) ? trim($_POST['gv_hocham']) : null,
                            'HocVi'            => trim($_POST['gv_hocvi'] ?? 'Thạc sĩ'),
                            'DonViCongTac'     => trim($_POST['gv_donvicongtac'] ?? 'Khoa Công nghệ Thông tin'),
                            'LinhVucNghienCuu' => trim($_POST['gv_linhvuc'] ?? '')
                        ];
                    } elseif ($role === App::ROLE_CAN_BO_QL || $role === App::ROLE_ADMIN) {
                        $roleData = [
                            'PhongBan' => trim($_POST['cb_phongban'] ?? 'Phòng Quản lý Khoa học'),
                            'ChucVu'   => trim($_POST['cb_chucvu'] ?? 'Chuyên viên')
                        ];
                    }

                    $hash = password_hash($password, PASSWORD_BCRYPT);
                    $this->userModel->registerUser([
                        'MaNguoiDung' => $username,
                        'MatKhauHash' => $hash,
                        'HoTen'       => $hoTen,
                        'Email'       => $email,
                        'SoDienThoai' => $sdt,
                        'VaiTro'      => $role,
                        'TrangThai'   => 'HOAT_DONG'
                    ], $roleData);

                    AuthHelper::setFlash('success', "Thêm người dùng mới [{$username} - {$hoTen}] thành công!");

                } elseif ($subAction === 'update_user') {
                    $userId = trim($_POST['user_id'] ?? '');
                    $hoTen = trim($_POST['hoten'] ?? '');
                    $email = trim(strtolower($_POST['email'] ?? ''));
                    $sdt = trim($_POST['sdt'] ?? '');
                    $status = $_POST['trangthai'] ?? 'HOAT_DONG';

                    if (empty($userId) || empty($hoTen) || empty($email)) {
                        throw new Exception("Họ tên và Email không được để trống.");
                    }
                    if ($this->userModel->isEmailTaken($email, $userId)) {
                        throw new Exception("Email '{$email}' đã tồn tại cho tài khoản khác.");
                    }

                    $user = $this->userModel->findById($userId);
                    if (!$user) {
                        throw new Exception("Người dùng {$userId} không tồn tại.");
                    }

                    $role = $user['VaiTro'];
                    $roleData = [];
                    if ($role === App::ROLE_SINH_VIEN) {
                        $roleData = [
                            'Lop'      => trim($_POST['sv_lop'] ?? ''),
                            'Khoa'     => trim($_POST['sv_khoa'] ?? ''),
                            'NganhHoc' => trim($_POST['sv_nganh'] ?? ''),
                            'KhoaHoc'  => trim($_POST['sv_khoahoc'] ?? '')
                        ];
                    } elseif ($role === App::ROLE_GIANG_VIEN) {
                        $roleData = [
                            'HocHam'           => !empty($_POST['gv_hocham']) ? trim($_POST['gv_hocham']) : null,
                            'HocVi'            => trim($_POST['gv_hocvi'] ?? 'Thạc sĩ'),
                            'DonViCongTac'     => trim($_POST['gv_donvicongtac'] ?? ''),
                            'LinhVucNghienCuu' => trim($_POST['gv_linhvuc'] ?? '')
                        ];
                    } elseif ($role === App::ROLE_CAN_BO_QL || $role === App::ROLE_ADMIN) {
                        $roleData = [
                            'PhongBan' => trim($_POST['cb_phongban'] ?? ''),
                            'ChucVu'   => trim($_POST['cb_chucvu'] ?? '')
                        ];
                    }

                    $this->userModel->updateProfileDetailed($userId, [
                        'HoTen'       => $hoTen,
                        'Email'       => $email,
                        'SoDienThoai' => $sdt
                    ], $roleData);

                    // Cập nhật trạng thái
                    $this->userModel->execute("UPDATE NguoiDung SET TrangThai = :st WHERE MaNguoiDung = :id", [
                        'st' => $status,
                        'id' => $userId
                    ]);

                    // Nếu có đổi mật khẩu
                    $newPass = trim($_POST['new_password'] ?? '');
                    if (!empty($newPass)) {
                        if (strlen($newPass) < 6) {
                            throw new Exception("Mật khẩu mới phải có tối thiểu 6 ký tự.");
                        }
                        $this->userModel->updatePassword($userId, password_hash($newPass, PASSWORD_BCRYPT));
                    }

                    AuthHelper::setFlash('success', "Cập nhật thông tin người dùng [{$userId}] thành công!");

                } elseif ($subAction === 'toggle_status') {
                    $userId = trim($_POST['user_id'] ?? '');
                    $newStatus = $this->userModel->toggleUserStatus($userId);
                    $statusText = ($newStatus === 'HOAT_DONG') ? 'Kích hoạt' : 'Khóa';
                    AuthHelper::setFlash('success', "Đã {$statusText} tài khoản [{$userId}] thành công!");

                } elseif ($subAction === 'delete_user') {
                    $userId = trim($_POST['user_id'] ?? '');
                    $this->userModel->deleteUser($userId);
                    AuthHelper::setFlash('success', "Đã xóa tài khoản [{$userId}] khỏi hệ thống thành công!");
                }

                header("Location: " . App::url('index.php?action=cb-quanly-nguoidung'));
                exit;

            } catch (\Throwable $e) {
                AuthHelper::setFlash('danger', "Thao tác thất bại: " . $e->getMessage());
                header("Location: " . App::url('index.php?action=cb-quanly-nguoidung'));
                exit;
            }
        }

        $search = trim($_GET['search'] ?? '');
        $filterRole = $_GET['role'] ?? null;
        $filterStatus = $_GET['status'] ?? null;

        $users = $this->userModel->getAllUsers($search ?: null, $filterRole, $filterStatus);

        require dirname(__DIR__, 2) . '/views/canboql/quanly_nguoidung.php';
    }
}

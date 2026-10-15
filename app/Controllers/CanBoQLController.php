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
                } elseif ($subAction === 'create_direction') {
                    $maHNC = trim($_POST['mahuongnc'] ?? '');
                    $tenHNC = trim($_POST['tenhuongnc'] ?? '');
                    $mota = trim($_POST['mota'] ?? '');
                    if (empty($maHNC) || empty($tenHNC)) {
                        throw new Exception("Vui lòng nhập mã và tên hướng nghiên cứu.");
                    }
                    $this->dotModel->createResearchDirection($maHNC, $tenHNC, $mota);
                    AuthHelper::setFlash('success', "Thêm hướng nghiên cứu mới thành công!");
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
                }
                header("Location: " . App::url('index.php?action=cb-quanly-dot'));
                exit;

            } catch (Exception $e) {
                $error = $e->getMessage();
            }
        }

        $dots = $this->dotModel->getAll();
        $directions = $this->dotModel->getAllResearchDirections();
        $notices = $this->dotModel->getNotifications();

        require dirname(__DIR__, 2) . '/views/canboql/quanly_dot.php';
    }

    // UC 12: Thẩm định đề tài NCKH
    public function thamDinhDeTai(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
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
                header("Location: " . App::url('index.php?action=cb-thamdinh-detai'));
                exit;

            } catch (Exception $e) {
                AuthHelper::setFlash('danger', $e->getMessage());
            }
        }

        $filterDot = $_GET['dot'] ?? null;
        $pendingTopics = $this->deTaiModel->getAllTopics('CHO_THAM_DINH', $filterDot);
        $allTopics = $this->deTaiModel->getAllTopics(null, $filterDot);
        $dots = $this->dotModel->getAll();

        require dirname(__DIR__, 2) . '/views/canboql/thamdinh_detai.php';
    }

    // UC 13: Quản lý tiến độ toàn trường
    public function quanLyTienDo(): void {
        $filterDot = $_GET['dot'] ?? null;
        $progressList = $this->tienDoModel->getAllProgressOverview($filterDot);
        $dots = $this->dotModel->getAll();

        require dirname(__DIR__, 2) . '/views/canboql/quanly_tiendo.php';
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
                }

                header("Location: " . App::url('index.php?action=cb-tochuc-nghiemthu'));
                exit;

            } catch (Exception $e) {
                $error = $e->getMessage();
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
        }

        require dirname(__DIR__, 2) . '/views/canboql/tochuc_nghiemthu.php';
    }

    // UC 15: Xếp loại và Khen thưởng
    public function xepLoaiKhenThuong(): void {
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
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

            } catch (Exception $e) {
                $error = $e->getMessage();
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
}

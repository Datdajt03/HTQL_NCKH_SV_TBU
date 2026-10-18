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
        $user = $this->userModel->findById($studentId);
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

                // Nếu người dùng nhập ở form trực tiếp (Phiếu Đề Xuất / Thuyết Minh)
                if (empty($tenDeTai) && !empty($_POST['dx_ten_de_tai'])) {
                    $tenDeTai = trim($_POST['dx_ten_de_tai']);
                }
                if (empty($mucTieu) && !empty($_POST['dx_muc_tieu'])) {
                    $mucTieu = trim($_POST['dx_muc_tieu']);
                }
                if (empty($sanPhamDuKien) && !empty($_POST['dx_san_pham'])) {
                    $sanPhamDuKien = trim($_POST['dx_san_pham']);
                }
                if (empty($noiDungNC) && !empty($_POST['dx_noi_dung_1'])) {
                    $noiDungNC = trim($_POST['dx_noi_dung_1']);
                }

                if (empty($tenDeTai) || empty($maDot) || empty($maHuongNC) || empty($maGVHD)) {
                    throw new Exception("Vui lòng điền các thông tin bắt buộc: Tên đề tài, Đợt NCKH, Hướng nghiên cứu và Giảng viên hướng dẫn.");
                }

                // File upload là TÙY CHỌN vì toàn bộ phiếu đã được điền và lưu trực tiếp trên web
                $fileTM = UploadHelper::upload('file_thuyet_minh');
                $fileDX = UploadHelper::upload('file_de_xuat');

                // Xử lý dữ liệu Phiếu Đề Xuất trực tuyến (Mẫu 02)
                $dxNoiDung = [];
                if (!empty($_POST['dx_nd_tieude']) && is_array($_POST['dx_nd_tieude'])) {
                    foreach ($_POST['dx_nd_tieude'] as $idx => $tt) {
                        $ct = $_POST['dx_nd_chitiet'][$idx] ?? '';
                        if (!empty($tt) || !empty($ct)) {
                            $dxNoiDung[] = [
                                'tieu_de' => trim($tt),
                                'chi_tiet' => trim($ct)
                            ];
                        }
                    }
                }
                if (empty($dxNoiDung)) {
                    $dxNoiDung = [
                        [
                            'tieu_de' => '4.1. Nội dung 1: Tìm hiểu lý thuyết và các thuật toán cơ bản',
                            'chi_tiet' => $noiDungNC ?: 'Nghiên cứu cơ sở lý thuyết và các mô hình thuật toán liên quan.'
                        ],
                        [
                            'tieu_de' => '4.2. Nội dung 2: Đề xuất giải pháp và thử nghiệm thực tế',
                            'chi_tiet' => 'Cài đặt thuật toán, thực nghiệm so sánh và đánh giá hiệu năng.'
                        ]
                    ];
                }

                $deXuatData = [
                    'co_quan_chu_quan' => trim($_POST['dx_co_quan'] ?? 'BỘ GIÁO DỤC VÀ ĐÀO TẠO'),
                    'don_vi'           => trim($_POST['dx_don_vi'] ?? 'TRƯỜNG ĐẠI HỌC TÂY BẮC'),
                    'dia_diem_ngay'    => trim($_POST['dx_dia_diem_ngay'] ?? ('Sơn La, ngày ' . date('d') . ' tháng ' . date('m') . ' năm ' . date('Y'))),
                    'ten_de_tai'       => $tenDeTai,
                    'tinh_cap_thiet'   => trim($_POST['dx_tinh_cap_thiet'] ?? ''),
                    'muc_tieu'         => $mucTieu ?: trim($_POST['dx_muc_tieu'] ?? ''),
                    'noi_dung_chu_yeu' => $dxNoiDung,
                    'du_kien_san_pham' => $sanPhamDuKien ?: trim($_POST['dx_san_pham'] ?? ''),
                    'kha_nang_dia_chi' => [
                        'doi_tuong'        => trim($_POST['dx_doi_tuong'] ?? 'Giảng viên, sinh viên và nhà nghiên cứu'),
                        'dia_chi'          => trim($_POST['dx_dia_chi'] ?? 'Khoa CNTT, Trường Đại học Tây Bắc, Sơn La'),
                        'kha_nang_mo_rong' => trim($_POST['dx_kha_nang_mo_rong'] ?? 'Có khả năng ứng dụng thực tiễn và mở rộng quy mô')
                    ],
                    'xac_nhan_gvhd'    => trim($_POST['dx_gvhd'] ?? ''),
                    'ca_nhan_de_xuat'  => trim($_POST['dx_sinh_vien'] ?? ($user['HoTen'] ?? ''))
                ];

                // Xử lý dữ liệu Thuyết Minh Đề Tài trực tuyến (Mẫu 01)
                $tmThanhVien = [];
                if (!empty($_POST['tm_tv_hoten']) && is_array($_POST['tm_tv_hoten'])) {
                    foreach ($_POST['tm_tv_hoten'] as $idx => $tvName) {
                        if (!empty(trim($tvName))) {
                            $tmThanhVien[] = [
                                'tt' => count($tmThanhVien) + 1,
                                'ho_ten' => trim($tvName),
                                'lop' => trim($_POST['tm_tv_lop'][$idx] ?? ''),
                                'cong_viec' => trim($_POST['tm_tv_congviec'][$idx] ?? ''),
                                'chu_ky' => 'Đã ký'
                            ];
                        }
                    }
                }

                $tmTienDo = [];
                if (!empty($_POST['tm_td_noidung']) && is_array($_POST['tm_td_noidung'])) {
                    foreach ($_POST['tm_td_noidung'] as $idx => $tdNd) {
                        if (!empty(trim($tdNd))) {
                            $tmTienDo[] = [
                                'tt' => count($tmTienDo) + 1,
                                'noi_dung' => trim($tdNd),
                                'san_pham' => trim($_POST['tm_td_sanpham'][$idx] ?? ''),
                                'thoi_gian' => trim($_POST['tm_td_thoigian'][$idx] ?? ''),
                                'nguoi_thuc_hien' => trim($_POST['tm_td_nguoith'][$idx] ?? 'Nhóm')
                            ];
                        }
                    }
                }

                $tmDanhSachSP = [];
                if (!empty($_POST['tm_sp_ten']) && is_array($_POST['tm_sp_ten'])) {
                    foreach ($_POST['tm_sp_ten'] as $idx => $spTen) {
                        if (!empty(trim($spTen))) {
                            $tmDanhSachSP[] = [
                                'tt' => count($tmDanhSachSP) + 1,
                                'ten_san_pham' => trim($spTen),
                                'so_luong' => trim($_POST['tm_sp_soluong'][$idx] ?? '01'),
                                'yeu_cau_khoa_hoc' => trim($_POST['tm_sp_yeucau'][$idx] ?? '')
                            ];
                        }
                    }
                }

                $thuyetMinhData = [
                    'co_quan_chu_quan' => trim($_POST['tm_co_quan'] ?? 'BỘ GIÁO DỤC VÀ ĐÀO TẠO'),
                    'don_vi'           => trim($_POST['tm_don_vi'] ?? 'TRƯỜNG ĐẠI HỌC TÂY BẮC'),
                    'dia_diem_ngay'    => trim($_POST['tm_dia_diem_ngay'] ?? ('Sơn La, ngày ' . date('d') . ' tháng ' . date('m') . ' năm ' . date('Y'))),
                    'nam_hoc'          => trim($_POST['tm_nam_hoc'] ?? (date('Y') . ' - ' . (date('Y') + 1))),
                    'ten_de_tai'       => $tenDeTai,
                    'loai_hinh_nc'     => $_POST['tm_loai_hinh_nc'] ?? 'CO_BAN',
                    'nhom_nganh'       => trim($_POST['tm_nhom_nganh'] ?? 'Công nghệ thông tin'),
                    'thoi_gian_thuc_hien' => trim($_POST['tm_thoi_gian'] ?? ('Từ tháng ' . date('m') . ' năm ' . date('Y') . ' đến tháng ' . date('m') . ' năm ' . (date('Y') + 1))),
                    'chu_nhiem' => [
                        'ho_ten' => trim($_POST['tm_cn_hoten'] ?? ($user['HoTen'] ?? '')),
                        'lop'    => trim($_POST['tm_cn_lop'] ?? ''),
                        'dien_thoai' => trim($_POST['tm_cn_sdt'] ?? ($user['SoDienThoai'] ?? ''))
                    ],
                    'thanh_vien_tham_gia' => $tmThanhVien,
                    'gvhd' => [
                        'ho_ten' => trim($_POST['tm_gv_hoten'] ?? ''),
                        'chuc_danh_hoc_vi' => trim($_POST['tm_gv_hocvi'] ?? ''),
                        'dien_thoai' => trim($_POST['tm_gv_sdt'] ?? '')
                    ],
                    'tinh_hinh_nc' => [
                        'tong_quan' => trim($_POST['tm_tong_quan'] ?? ''),
                        'cong_trinh_lien_quan' => trim($_POST['tm_cong_trinh'] ?? '')
                    ],
                    'tinh_cap_thiet' => trim($_POST['tm_tinh_cap_thiet'] ?? ($deXuatData['tinh_cap_thiet'] ?? '')),
                    'muc_tieu' => [
                        'muc_tieu_chung' => $mucTieu,
                        'muc_tieu_cu_the' => trim($_POST['tm_muc_tieu_cu_the'] ?? '')
                    ],
                    'doi_tuong_pham_vi_phuong_phap' => [
                        'doi_tuong' => trim($_POST['tm_doi_tuong'] ?? ''),
                        'pham_vi' => trim($_POST['tm_pham_vi'] ?? ''),
                        'phuong_phap' => trim($_POST['tm_phuong_phap'] ?? '')
                    ],
                    'tien_do_thuc_hien' => $tmTienDo,
                    'san_pham_ung_dung' => [
                        'loai_san_pham' => $_POST['tm_loai_san_pham'] ?? ['Báo cáo phân tích', 'Phần mềm máy tính'],
                        'danh_sach' => $tmDanhSachSP,
                        'dia_chi_ap_dung' => trim($_POST['tm_dia_chi_ap_dung'] ?? 'Khoa Công nghệ Thông tin, Trường Đại học Tây Bắc.')
                    ],
                    'ngay_ky_gvhd' => trim($_POST['tm_ngay_ky_gv'] ?? ('Ngày ' . date('d') . ' tháng ' . date('m') . ' năm ' . date('Y'))),
                    'gvhd_ky'      => trim($_POST['tm_gvhd_ky'] ?? ''),
                    'ngay_ky_sv'   => trim($_POST['tm_ngay_ky_sv'] ?? ('Ngày ' . date('d') . ' tháng ' . date('m') . ' năm ' . date('Y'))),
                    'sv_ky'        => trim($_POST['tm_sv_ky'] ?? ($user['HoTen'] ?? ''))
                ];

                // Xử lý thành viên nhóm đề tài trong hệ thống
                $members = [];
                $members[] = [
                    'MaSV' => $studentId,
                    'VaiTro' => 'TRUONG_NHOM',
                    'NhiemVuCuThe' => trim($_POST['nhiemvu_truongnhom'] ?? 'Chủ nhiệm đề tài, điều phối chung')
                ];

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
                    'MucTieu' => $mucTieu ?: ($thuyetMinhData['muc_tieu']['muc_tieu_chung'] ?? 'Nghiên cứu khoa học'),
                    'NoiDungNC' => $noiDungNC ?: 'Theo nội dung thuyết minh chi tiết',
                    'SanPhamDuKien' => $sanPhamDuKien ?: ($deXuatData['du_kien_san_pham'] ?? 'Sản phẩm theo thuyết minh'),
                    'KinhPhiDuKien' => $kinhPhi,
                    'FilePhieuThuyetMinh' => $fileTM,
                    'FilePhieuDeXuat' => $fileDX
                ];

                $maDeTai = $this->deTaiModel->registerTopic($topicData, $members, $deXuatData, $thuyetMinhData);
                AuthHelper::setFlash('success', "Đăng ký đề tài thành công! Toàn bộ Phiếu đề xuất và Thuyết minh đã được số hóa lưu trực tiếp trên hệ thống. Mã đề tài: {$maDeTai}.");
                header("Location: " . App::url('index.php?action=sv-chitiet-detai&id=' . $maDeTai));
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
        $user = $this->userModel->findById($studentId);
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

        // Load dữ liệu biểu mẫu trực tuyến hiện tại
        $deXuatData = \App\Helpers\NckhFormHelper::parseDeXuatData($topic['DuLieuDeXuat'] ?? null, $topic);
        $thuyetMinhData = \App\Helpers\NckhFormHelper::parseThuyetMinhData($topic['DuLieuThuyetMinh'] ?? null, $topic);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $tenDeTai = trim($_POST['tendetai'] ?? '');
                $maHuongNC = $_POST['mahuongnc'] ?? '';
                $mucTieu = trim($_POST['muctieu'] ?? '');
                $noiDungNC = trim($_POST['noidungnc'] ?? '');
                $sanPhamDuKien = trim($_POST['sanphamdukien'] ?? '');
                $kinhPhi = (float)str_replace(['.', ','], '', $_POST['kinhphi'] ?? '0');
                $noiDungSua = trim($_POST['noidungsua'] ?? '');

                if (empty($tenDeTai) && !empty($_POST['dx_ten_de_tai'])) {
                    $tenDeTai = trim($_POST['dx_ten_de_tai']);
                }
                if (empty($mucTieu) && !empty($_POST['dx_muc_tieu'])) {
                    $mucTieu = trim($_POST['dx_muc_tieu']);
                }
                if (empty($sanPhamDuKien) && !empty($_POST['dx_san_pham'])) {
                    $sanPhamDuKien = trim($_POST['dx_san_pham']);
                }

                if (empty($tenDeTai) || empty($noiDungSua)) {
                    throw new Exception("Vui lòng nhập tên đề tài và tóm tắt nội dung giải trình chỉnh sửa.");
                }

                $fileTM = UploadHelper::upload('file_thuyet_minh_moi');
                $fileDX = UploadHelper::upload('file_de_xuat_moi');

                // Cập nhật dữ liệu Phiếu Đề Xuất trực tuyến (Mẫu 02)
                $dxNoiDung = [];
                if (!empty($_POST['dx_nd_tieude']) && is_array($_POST['dx_nd_tieude'])) {
                    foreach ($_POST['dx_nd_tieude'] as $idx => $tt) {
                        $ct = $_POST['dx_nd_chitiet'][$idx] ?? '';
                        if (!empty($tt) || !empty($ct)) {
                            $dxNoiDung[] = [
                                'tieu_de' => trim($tt),
                                'chi_tiet' => trim($ct)
                            ];
                        }
                    }
                }
                if (empty($dxNoiDung) && !empty($deXuatData['noi_dung_chu_yeu'])) {
                    $dxNoiDung = $deXuatData['noi_dung_chu_yeu'];
                }

                $updatedDeXuat = [
                    'co_quan_chu_quan' => trim($_POST['dx_co_quan'] ?? ($deXuatData['co_quan_chu_quan'] ?? 'BỘ GIÁO DỤC VÀ ĐÀO TẠO')),
                    'don_vi'           => trim($_POST['dx_don_vi'] ?? ($deXuatData['don_vi'] ?? 'TRƯỜNG ĐẠI HỌC TÂY BẮC')),
                    'dia_diem_ngay'    => trim($_POST['dx_dia_diem_ngay'] ?? ($deXuatData['dia_diem_ngay'] ?? '')),
                    'ten_de_tai'       => $tenDeTai,
                    'tinh_cap_thiet'   => trim($_POST['dx_tinh_cap_thiet'] ?? ($deXuatData['tinh_cap_thiet'] ?? '')),
                    'muc_tieu'         => $mucTieu ?: trim($_POST['dx_muc_tieu'] ?? ($deXuatData['muc_tieu'] ?? '')),
                    'noi_dung_chu_yeu' => $dxNoiDung,
                    'du_kien_san_pham' => $sanPhamDuKien ?: trim($_POST['dx_san_pham'] ?? ($deXuatData['du_kien_san_pham'] ?? '')),
                    'kha_nang_dia_chi' => [
                        'doi_tuong'        => trim($_POST['dx_doi_tuong'] ?? ($deXuatData['kha_nang_dia_chi']['doi_tuong'] ?? '')),
                        'dia_chi'          => trim($_POST['dx_dia_chi'] ?? ($deXuatData['kha_nang_dia_chi']['dia_chi'] ?? '')),
                        'kha_nang_mo_rong' => trim($_POST['dx_kha_nang_mo_rong'] ?? ($deXuatData['kha_nang_dia_chi']['kha_nang_mo_rong'] ?? ''))
                    ],
                    'xac_nhan_gvhd'    => trim($_POST['dx_gvhd'] ?? ($deXuatData['xac_nhan_gvhd'] ?? '')),
                    'ca_nhan_de_xuat'  => trim($_POST['dx_sinh_vien'] ?? ($deXuatData['ca_nhan_de_xuat'] ?? ''))
                ];

                // Cập nhật dữ liệu Thuyết Minh Đề Tài trực tuyến (Mẫu 01)
                $tmThanhVien = [];
                if (!empty($_POST['tm_tv_hoten']) && is_array($_POST['tm_tv_hoten'])) {
                    foreach ($_POST['tm_tv_hoten'] as $idx => $tvName) {
                        if (!empty(trim($tvName))) {
                            $tmThanhVien[] = [
                                'tt' => count($tmThanhVien) + 1,
                                'ho_ten' => trim($tvName),
                                'lop' => trim($_POST['tm_tv_lop'][$idx] ?? ''),
                                'cong_viec' => trim($_POST['tm_tv_congviec'][$idx] ?? ''),
                                'chu_ky' => 'Đã ký'
                            ];
                        }
                    }
                }
                if (empty($tmThanhVien) && !empty($thuyetMinhData['thanh_vien_tham_gia'])) {
                    $tmThanhVien = $thuyetMinhData['thanh_vien_tham_gia'];
                }

                $tmTienDo = [];
                if (!empty($_POST['tm_td_noidung']) && is_array($_POST['tm_td_noidung'])) {
                    foreach ($_POST['tm_td_noidung'] as $idx => $tdNd) {
                        if (!empty(trim($tdNd))) {
                            $tmTienDo[] = [
                                'tt' => count($tmTienDo) + 1,
                                'noi_dung' => trim($tdNd),
                                'san_pham' => trim($_POST['tm_td_sanpham'][$idx] ?? ''),
                                'thoi_gian' => trim($_POST['tm_td_thoigian'][$idx] ?? ''),
                                'nguoi_thuc_hien' => trim($_POST['tm_td_nguoith'][$idx] ?? 'Nhóm')
                            ];
                        }
                    }
                }
                if (empty($tmTienDo) && !empty($thuyetMinhData['tien_do_thuc_hien'])) {
                    $tmTienDo = $thuyetMinhData['tien_do_thuc_hien'];
                }

                $tmDanhSachSP = [];
                if (!empty($_POST['tm_sp_ten']) && is_array($_POST['tm_sp_ten'])) {
                    foreach ($_POST['tm_sp_ten'] as $idx => $spTen) {
                        if (!empty(trim($spTen))) {
                            $tmDanhSachSP[] = [
                                'tt' => count($tmDanhSachSP) + 1,
                                'ten_san_pham' => trim($spTen),
                                'so_luong' => trim($_POST['tm_sp_soluong'][$idx] ?? '01'),
                                'yeu_cau_khoa_hoc' => trim($_POST['tm_sp_yeucau'][$idx] ?? '')
                            ];
                        }
                    }
                }
                if (empty($tmDanhSachSP) && !empty($thuyetMinhData['san_pham_ung_dung']['danh_sach'])) {
                    $tmDanhSachSP = $thuyetMinhData['san_pham_ung_dung']['danh_sach'];
                }

                $updatedThuyetMinh = [
                    'co_quan_chu_quan' => trim($_POST['tm_co_quan'] ?? ($thuyetMinhData['co_quan_chu_quan'] ?? 'BỘ GIÁO DỤC VÀ ĐÀO TẠO')),
                    'don_vi'           => trim($_POST['tm_don_vi'] ?? ($thuyetMinhData['don_vi'] ?? 'TRƯỜNG ĐẠI HỌC TÂY BẮC')),
                    'dia_diem_ngay'    => trim($_POST['tm_dia_diem_ngay'] ?? ($thuyetMinhData['dia_diem_ngay'] ?? '')),
                    'nam_hoc'          => trim($_POST['tm_nam_hoc'] ?? ($thuyetMinhData['nam_hoc'] ?? '')),
                    'ten_de_tai'       => $tenDeTai,
                    'loai_hinh_nc'     => $_POST['tm_loai_hinh_nc'] ?? ($thuyetMinhData['loai_hinh_nc'] ?? 'CO_BAN'),
                    'nhom_nganh'       => trim($_POST['tm_nhom_nganh'] ?? ($thuyetMinhData['nhom_nganh'] ?? 'Công nghệ thông tin')),
                    'thoi_gian_thuc_hien' => trim($_POST['tm_thoi_gian'] ?? ($thuyetMinhData['thoi_gian_thuc_hien'] ?? '')),
                    'chu_nhiem' => [
                        'ho_ten' => trim($_POST['tm_cn_hoten'] ?? ($thuyetMinhData['chu_nhiem']['ho_ten'] ?? '')),
                        'lop'    => trim($_POST['tm_cn_lop'] ?? ($thuyetMinhData['chu_nhiem']['lop'] ?? '')),
                        'dien_thoai' => trim($_POST['tm_cn_sdt'] ?? ($thuyetMinhData['chu_nhiem']['dien_thoai'] ?? ''))
                    ],
                    'thanh_vien_tham_gia' => $tmThanhVien,
                    'gvhd' => [
                        'ho_ten' => trim($_POST['tm_gv_hoten'] ?? ($thuyetMinhData['gvhd']['ho_ten'] ?? '')),
                        'chuc_danh_hoc_vi' => trim($_POST['tm_gv_hocvi'] ?? ($thuyetMinhData['gvhd']['chuc_danh_hoc_vi'] ?? '')),
                        'dien_thoai' => trim($_POST['tm_gv_sdt'] ?? ($thuyetMinhData['gvhd']['dien_thoai'] ?? ''))
                    ],
                    'tinh_hinh_nc' => [
                        'tong_quan' => trim($_POST['tm_tong_quan'] ?? ($thuyetMinhData['tinh_hinh_nc']['tong_quan'] ?? '')),
                        'cong_trinh_lien_quan' => trim($_POST['tm_cong_trinh'] ?? ($thuyetMinhData['tinh_hinh_nc']['cong_trinh_lien_quan'] ?? ''))
                    ],
                    'tinh_cap_thiet' => trim($_POST['tm_tinh_cap_thiet'] ?? ($updatedDeXuat['tinh_cap_thiet'] ?? '')),
                    'muc_tieu' => [
                        'muc_tieu_chung' => $mucTieu ?: ($thuyetMinhData['muc_tieu']['muc_tieu_chung'] ?? ''),
                        'muc_tieu_cu_the' => trim($_POST['tm_muc_tieu_cu_the'] ?? ($thuyetMinhData['muc_tieu']['muc_tieu_cu_the'] ?? ''))
                    ],
                    'doi_tuong_pham_vi_phuong_phap' => [
                        'doi_tuong' => trim($_POST['tm_doi_tuong'] ?? ($thuyetMinhData['doi_tuong_pham_vi_phuong_phap']['doi_tuong'] ?? '')),
                        'pham_vi' => trim($_POST['tm_pham_vi'] ?? ($thuyetMinhData['doi_tuong_pham_vi_phuong_phap']['pham_vi'] ?? '')),
                        'phuong_phap' => trim($_POST['tm_phuong_phap'] ?? ($thuyetMinhData['doi_tuong_pham_vi_phuong_phap']['phuong_phap'] ?? ''))
                    ],
                    'tien_do_thuc_hien' => $tmTienDo,
                    'san_pham_ung_dung' => [
                        'loai_san_pham' => $_POST['tm_loai_san_pham'] ?? ($thuyetMinhData['san_pham_ung_dung']['loai_san_pham'] ?? ['Báo cáo phân tích']),
                        'danh_sach' => $tmDanhSachSP,
                        'dia_chi_ap_dung' => trim($_POST['tm_dia_chi_ap_dung'] ?? ($thuyetMinhData['san_pham_ung_dung']['dia_chi_ap_dung'] ?? ''))
                    ],
                    'ngay_ky_gvhd' => trim($_POST['tm_ngay_ky_gv'] ?? ($thuyetMinhData['ngay_ky_gvhd'] ?? '')),
                    'gvhd_ky'      => trim($_POST['tm_gvhd_ky'] ?? ($thuyetMinhData['gvhd_ky'] ?? '')),
                    'ngay_ky_sv'   => trim($_POST['tm_ngay_ky_sv'] ?? ($thuyetMinhData['ngay_ky_sv'] ?? '')),
                    'sv_ky'        => trim($_POST['tm_sv_ky'] ?? ($thuyetMinhData['sv_ky'] ?? ''))
                ];

                $updateData = [
                    'TenDeTai' => $tenDeTai,
                    'MaHuongNC' => $maHuongNC,
                    'MucTieu' => $mucTieu ?: ($updatedThuyetMinh['muc_tieu']['muc_tieu_chung'] ?? ''),
                    'NoiDungNC' => $noiDungNC ?: 'Theo nội dung thuyết minh',
                    'SanPhamDuKien' => $sanPhamDuKien ?: ($updatedDeXuat['du_kien_san_pham'] ?? ''),
                    'KinhPhiDuKien' => $kinhPhi,
                    'FilePhieuThuyetMinh' => $fileTM,
                    'FilePhieuDeXuat' => $fileDX
                ];

                $this->deTaiModel->updateTopicProposal($maDeTai, $updateData, $studentId, $noiDungSua, $updatedDeXuat, $updatedThuyetMinh);
                AuthHelper::setFlash('success', "Cập nhật hồ sơ đề tài thành công! Toàn bộ nội dung Phiếu Đề Xuất và Thuyết Minh trực tuyến đã được đồng bộ và chuyển cho Cán bộ Quản lý thẩm định.");
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
        
        // Tự động tìm đề tài của sinh viên nếu chưa truyền id
        if (empty($maDeTai)) {
            $myTopics = $this->deTaiModel->getTopicsByStudent($studentId);
            foreach ($myTopics as $t) {
                if (in_array($t['TrangThai'], ['DANG_THUC_HIEN', 'CHO_NGHIEM_THU', 'PHE_DUYET'])) {
                    $maDeTai = $t['MaDeTai'];
                    break;
                }
            }
            if (empty($maDeTai) && !empty($myTopics)) {
                $maDeTai = $myTopics[0]['MaDeTai'];
            }
        }

        $topic = $maDeTai ? $this->deTaiModel->findById($maDeTai) : null;

        if (!$topic) {
            AuthHelper::setFlash('warning', 'Bạn chưa có đề tài NCKH nào trong giai đoạn thực hiện để nộp báo cáo tiến độ.');
            header("Location: " . App::url('index.php?action=sv-dashboard'));
            exit;
        }

        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $dotBaoCao = trim($_POST['dotbaocao'] ?? '');
                $khoKhan = trim($_POST['khokhan'] ?? '');

                if (empty($dotBaoCao)) {
                    throw new Exception("Vui lòng nhập tên đợt báo cáo tiến độ (VD: Báo cáo tiến độ Tháng 11 / Đợt 1 / Giữa kỳ).");
                }

                // Xử lý danh sách các mục công việc từ Mục 12 Thuyết minh
                $tasks = [];
                $totalPoints = 0;
                $count = 0;

                if (!empty($_POST['task_noidung']) && is_array($_POST['task_noidung'])) {
                    foreach ($_POST['task_noidung'] as $idx => $nd) {
                        $noiDung = trim($nd);
                        if (empty($noiDung)) continue;

                        $mucDo = $_POST['task_mucdo'][$idx] ?? 'CHUA_HOAN_THANH';
                        $percent = match ($mucDo) {
                            'HOAN_THANH'          => 100,
                            'SAP_HOAN_THANH'      => 75,
                            'HOAN_THANH_MOT_NUA'  => 50,
                            default               => 0
                        };

                        $sanPhamNop = trim($_POST['task_sanpham_nop'][$idx] ?? '');
                        $ghiChu     = trim($_POST['task_ghichu'][$idx] ?? '');
                        $sanPhamKH  = trim($_POST['task_sanpham_kehoach'][$idx] ?? '');
                        $thoiGian   = trim($_POST['task_thoigian'][$idx] ?? '');
                        $nguoiTH    = trim($_POST['task_nguoith'][$idx] ?? 'Nhóm');

                        $tasks[] = [
                            'tt'                 => count($tasks) + 1,
                            'noi_dung'           => $noiDung,
                            'san_pham_ke_hoach'  => $sanPhamKH,
                            'thoi_gian'          => $thoiGian,
                            'nguoi_thuc_hien'    => $nguoiTH,
                            'muc_do'             => $mucDo,
                            'phan_tram'          => $percent,
                            'san_pham_nop'       => $sanPhamNop,
                            'ghi_chu'            => $ghiChu
                        ];

                        $totalPoints += $percent;
                        $count++;
                    }
                }

                if ($count > 0) {
                    $phanTram = (int)round($totalPoints / $count);
                } else {
                    $phanTram = max(0, min(100, (int)($_POST['phantram'] ?? 0)));
                }

                // Tự động tổng hợp nội dung công việc nếu chưa nhập textarea tổng quát
                $congViec = trim($_POST['congviec'] ?? '');
                if (empty($congViec) && !empty($tasks)) {
                    $summaryLines = [];
                    foreach ($tasks as $t) {
                        $label = match ($t['muc_do']) {
                            'HOAN_THANH'         => 'Hoàn thành (100%)',
                            'SAP_HOAN_THANH'     => 'Sắp hoàn thành (75%)',
                            'HOAN_THANH_MOT_NUA' => 'Đã hoàn thành 1 nửa (50%)',
                            default              => 'Chưa hoàn thành (0%)'
                        };
                        $spText = !empty($t['san_pham_nop']) ? " | Sản phẩm: {$t['san_pham_nop']}" : "";
                        $summaryLines[] = "• [{$label}] Mục {$t['tt']}: {$t['noi_dung']}{$spText}";
                    }
                    $congViec = implode("\n", $summaryLines);
                }

                if (empty($congViec)) {
                    throw new Exception("Vui lòng đánh giá mức độ hoàn thành các mục công việc hoặc mô tả công việc đã làm.");
                }

                $fileBC = UploadHelper::upload('file_baocao');

                $chiTietJson = !empty($tasks) ? json_encode($tasks, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) : null;

                $this->tienDoModel->createReport([
                    'MaDeTai'           => $maDeTai,
                    'DotBaoCao'         => $dotBaoCao,
                    'PhanTramHoanThanh' => $phanTram,
                    'CongViecDaLam'     => $congViec,
                    'ChiTietTienDo'     => $chiTietJson,
                    'KhoKhanVuongMac'   => $khoKhan,
                    'FileBaoCao'        => $fileBC
                ]);

                AuthHelper::setFlash('success', "Nộp báo cáo tiến độ thành công! Tiến độ tổng thể đạt {$phanTram}%, hệ thống đã chuyển dữ liệu cho GVHD và Cán bộ Quản lý thẩm định.");
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

    public function xoaDeTai(): void {
        $studentId = AuthHelper::id();
        $maDeTai = $_GET['id'] ?? ($_POST['madetai'] ?? '');

        if (empty($maDeTai)) {
            AuthHelper::setFlash('danger', 'Không tìm thấy mã đề tài cần xóa.');
            header("Location: " . App::url('index.php?action=sv-dashboard'));
            exit;
        }

        $topic = $this->deTaiModel->findById($maDeTai);
        if (!$topic) {
            AuthHelper::setFlash('danger', 'Đề tài không tồn tại trong hệ thống.');
            header("Location: " . App::url('index.php?action=sv-dashboard'));
            exit;
        }

        // Kiểm tra xem sinh viên hiện tại có phải là Trưởng nhóm của đề tài không
        $members = $this->deTaiModel->getMembers($maDeTai);
        $isLeader = false;
        foreach ($members as $m) {
            if ($m['MaSV'] === $studentId && $m['VaiTro'] === 'TRUONG_NHOM') {
                $isLeader = true;
                break;
            }
        }

        if (!AuthHelper::isAdmin() && !$isLeader) {
            AuthHelper::setFlash('danger', 'Chỉ Trưởng nhóm sinh viên mới có quyền hủy/xóa hồ sơ đề tài này.');
            header("Location: " . App::url('index.php?action=sv-dashboard'));
            exit;
        }

        if (!in_array($topic['TrangThai'], ['CHO_GVHD_DUYET', 'GVHD_TU_CHOI', 'YEU_CAU_CHINH_SUA', 'CHO_THAM_DINH', 'CAN_BO_TU_CHOI', 'HUY'])) {
            AuthHelper::setFlash('danger', 'Không thể xóa đề tài đang ở trạng thái ' . $topic['TrangThai'] . '. Vui lòng liên hệ Cán bộ Quản lý.');
            header("Location: " . App::url('index.php?action=sv-dashboard'));
            exit;
        }

        try {
            $this->deTaiModel->deleteTopic($maDeTai);
            AuthHelper::setFlash('success', "Đã xóa thành công hồ sơ đề tài {$maDeTai}.");
        } catch (\Throwable $e) {
            AuthHelper::setFlash('danger', "Lỗi khi xóa đề tài: " . $e->getMessage());
        }

        header("Location: " . App::url('index.php?action=sv-dashboard'));
        exit;
    }

    public function xoaBaoCaoTienDo(): void {
        $studentId = AuthHelper::id();
        $id = (int)($_GET['id'] ?? ($_POST['id'] ?? 0));
        if ($id <= 0) {
            AuthHelper::setFlash('danger', 'Thiếu mã báo cáo cần xóa.');
            header("Location: " . App::url('index.php?action=sv-baocao-tiendo'));
            exit;
        }

        $report = $this->tienDoModel->findById($id);
        if (!$report) {
            AuthHelper::setFlash('danger', 'Không tìm thấy báo cáo tiến độ.');
            header("Location: " . App::url('index.php?action=sv-baocao-tiendo'));
            exit;
        }

        $members = $this->deTaiModel->getMembers($report['MaDeTai']);
        $isMember = false;
        foreach ($members as $m) {
            if ($m['MaSV'] === $studentId) {
                $isMember = true;
                break;
            }
        }

        if (!AuthHelper::isAdmin() && !$isMember) {
            AuthHelper::setFlash('danger', 'Bạn không có quyền xóa báo cáo tiến độ này.');
            header("Location: " . App::url('index.php?action=sv-baocao-tiendo'));
            exit;
        }

        if (!empty($report['DanhGiaTienDo']) || !empty($report['DanhGiaCanBo'])) {
            AuthHelper::setFlash('danger', 'Báo cáo đã được đánh giá, không thể xóa.');
            header("Location: " . App::url('index.php?action=sv-baocao-tiendo'));
            exit;
        }

        try {
            $this->tienDoModel->deleteReport($id);
            AuthHelper::setFlash('success', 'Đã xóa báo cáo tiến độ thành công!');
        } catch (\Throwable $e) {
            AuthHelper::setFlash('danger', 'Lỗi khi xóa báo cáo: ' . $e->getMessage());
        }

        header("Location: " . App::url('index.php?action=sv-baocao-tiendo'));
        exit;
    }
}

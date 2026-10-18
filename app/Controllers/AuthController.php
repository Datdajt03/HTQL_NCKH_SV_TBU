<?php
namespace App\Controllers;

use App\Config\App;
use App\Helpers\AuthHelper;
use App\Models\UserModel;

class AuthController {
    private UserModel $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    public function login(): void {
        if (AuthHelper::isLoggedIn()) {
            $this->redirectByRole(AuthHelper::role());
            return;
        }

        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';

            if (empty($username) || empty($password)) {
                $error = "Vui lòng nhập đầy đủ tên đăng nhập và mật khẩu.";
            } else {
                $user = $this->userModel->findByLogin($username);
                if ($user) {
                    if ($user['TrangThai'] === 'KHOA') {
                        $error = "Tài khoản của bạn đang bị tạm khóa. Vui lòng liên hệ Ban Quản lý NCKH.";
                    } elseif (password_verify($password, $user['MatKhauHash'])) {
                        AuthHelper::login($user);
                        AuthHelper::setFlash('success', "Chào mừng " . $user['HoTen'] . " đã đăng nhập thành công!");
                        $this->redirectByRole($user['VaiTro']);
                        return;
                    } else {
                        $error = "Tài khoản hoặc mật khẩu không chính xác.";
                    }
                } else {
                    $error = "Tài khoản hoặc mật khẩu không chính xác.";
                }
            }
        }

        require dirname(__DIR__, 2) . '/views/auth/login.php';
    }

    public function register(): void {
        if (AuthHelper::isLoggedIn()) {
            $this->redirectByRole(AuthHelper::role());
            return;
        }

        $error = null;
        $formData = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $formData = $_POST;
            $username = strtoupper(trim($_POST['username'] ?? ''));
            $hoTen = trim($_POST['hoten'] ?? '');
            $email = trim(strtolower($_POST['email'] ?? ''));
            $sdt = trim($_POST['sdt'] ?? '');
            $vaiTro = $_POST['vaitro'] ?? App::ROLE_SINH_VIEN;
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            if (empty($username) || empty($hoTen) || empty($email) || empty($password)) {
                $error = "Vui lòng điền đầy đủ các thông tin bắt buộc (*).";
            } elseif (!preg_match('/^[A-Z0-9_]{3,20}$/', $username)) {
                $error = "Mã định danh (tên đăng nhập) phải từ 3-20 ký tự, gồm chữ hoa, chữ số hoặc gạch dưới (VD: SV010, GV010).";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = "Địa chỉ email không đúng định dạng.";
            } elseif (strlen($password) < 6) {
                $error = "Mật khẩu phải có tối thiểu 6 ký tự.";
            } elseif ($password !== $confirmPassword) {
                $error = "Xác nhận mật khẩu mới không khớp.";
            } elseif ($this->userModel->isUsernameTaken($username)) {
                $error = "Mã người dùng '{$username}' đã tồn tại trong hệ thống. Vui lòng chọn mã khác.";
            } elseif ($this->userModel->isEmailTaken($email)) {
                $error = "Địa chỉ email '{$email}' đã được đăng ký cho tài khoản khác.";
            } else {
                $roleData = [];
                if ($vaiTro === App::ROLE_SINH_VIEN) {
                    $roleData = [
                        'Lop'      => trim($_POST['sv_lop'] ?? 'K64 CNTT'),
                        'Khoa'     => trim($_POST['sv_khoa'] ?? 'Khoa Công nghệ Thông tin'),
                        'NganhHoc' => trim($_POST['sv_nganh'] ?? 'Khoa học Máy tính'),
                        'KhoaHoc'  => trim($_POST['sv_khoahoc'] ?? (date('Y') . ' - ' . (date('Y') + 4)))
                    ];
                } elseif ($vaiTro === App::ROLE_GIANG_VIEN) {
                    $roleData = [
                        'HocHam'           => !empty($_POST['gv_hocham']) ? trim($_POST['gv_hocham']) : null,
                        'HocVi'            => trim($_POST['gv_hocvi'] ?? 'Thạc sĩ'),
                        'DonViCongTac'     => trim($_POST['gv_donvicongtac'] ?? 'Khoa Công nghệ Thông tin'),
                        'LinhVucNghienCuu' => trim($_POST['gv_linhvuc'] ?? '')
                    ];
                } else {
                    $vaiTro = App::ROLE_SINH_VIEN;
                }

                try {
                    $hash = password_hash($password, PASSWORD_BCRYPT);
                    $this->userModel->registerUser([
                        'MaNguoiDung' => $username,
                        'MatKhauHash' => $hash,
                        'HoTen'       => $hoTen,
                        'Email'       => $email,
                        'SoDienThoai' => $sdt,
                        'VaiTro'      => $vaiTro,
                        'TrangThai'   => 'HOAT_DONG'
                    ], $roleData);

                    AuthHelper::setFlash('success', "🎉 Chúc mừng <strong>{$hoTen}</strong>! Tài khoản [{$username}] đã được tạo thành công trên hệ thống NCKH. Bạn có thể đăng nhập ngay!");
                    header("Location: " . App::url('index.php?action=login'));
                    exit;
                } catch (\Throwable $e) {
                    $error = "Lỗi khi lưu tài khoản vào cơ sở dữ liệu: " . $e->getMessage();
                }
            }
        }

        require dirname(__DIR__, 2) . '/views/auth/register.php';
    }

    public function profile(): void {
        AuthHelper::requireLogin();
        $userId = AuthHelper::id();
        $user = $this->userModel->findById($userId);
        if (!$user) {
            AuthHelper::setFlash('danger', 'Không tìm thấy thông tin tài khoản người dùng.');
            header("Location: " . App::url('index.php?action=login'));
            exit;
        }

        $message = null;
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $subAction = $_POST['sub_action'] ?? '';
            if ($subAction === 'update_profile') {
                $hoTen = trim($_POST['hoten'] ?? '');
                $email = trim(strtolower($_POST['email'] ?? ''));
                $sdt = trim($_POST['sdt'] ?? '');

                if (empty($hoTen) || empty($email)) {
                    $error = "Họ tên và Email không được để trống.";
                } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $error = "Địa chỉ email không đúng định dạng.";
                } elseif ($this->userModel->isEmailTaken($email, $userId)) {
                    $error = "Địa chỉ email '{$email}' đã được sử dụng bởi một tài khoản khác.";
                } else {
                    $roleData = [];
                    if ($user['VaiTro'] === App::ROLE_SINH_VIEN) {
                        $roleData = [
                            'Lop'      => trim($_POST['sv_lop'] ?? ($user['extra']['Lop'] ?? '')),
                            'Khoa'     => trim($_POST['sv_khoa'] ?? ($user['extra']['Khoa'] ?? '')),
                            'NganhHoc' => trim($_POST['sv_nganh'] ?? ($user['extra']['NganhHoc'] ?? '')),
                            'KhoaHoc'  => trim($_POST['sv_khoahoc'] ?? ($user['extra']['KhoaHoc'] ?? ''))
                        ];
                    } elseif ($user['VaiTro'] === App::ROLE_GIANG_VIEN) {
                        $roleData = [
                            'HocHam'           => !empty($_POST['gv_hocham']) ? trim($_POST['gv_hocham']) : null,
                            'HocVi'            => trim($_POST['gv_hocvi'] ?? ($user['extra']['HocVi'] ?? 'Thạc sĩ')),
                            'DonViCongTac'     => trim($_POST['gv_donvicongtac'] ?? ($user['extra']['DonViCongTac'] ?? '')),
                            'LinhVucNghienCuu' => trim($_POST['gv_linhvuc'] ?? ($user['extra']['LinhVucNghienCuu'] ?? ''))
                        ];
                    } elseif ($user['VaiTro'] === App::ROLE_CAN_BO_QL || $user['VaiTro'] === App::ROLE_ADMIN) {
                        $roleData = [
                            'PhongBan' => trim($_POST['cb_phongban'] ?? ($user['extra']['PhongBan'] ?? '')),
                            'ChucVu'   => trim($_POST['cb_chucvu'] ?? ($user['extra']['ChucVu'] ?? ''))
                        ];
                    }

                    try {
                        $this->userModel->updateProfileDetailed($userId, [
                            'HoTen'       => $hoTen,
                            'Email'       => $email,
                            'SoDienThoai' => $sdt
                        ], $roleData);

                        $user = $this->userModel->findById($userId);
                        AuthHelper::updateSessionUser($user);
                        $message = "Cập nhật thông tin cá nhân và chuyên môn thành công!";
                    } catch (\Throwable $e) {
                        $error = "Lỗi khi cập nhật hồ sơ: " . $e->getMessage();
                    }
                }
            } elseif ($subAction === 'change_password') {
                $oldPass = $_POST['old_password'] ?? '';
                $newPass = $_POST['new_password'] ?? '';
                $confirmPass = $_POST['confirm_password'] ?? '';

                if (!password_verify($oldPass, $user['MatKhauHash'])) {
                    $error = "Mật khẩu hiện tại không chính xác.";
                } elseif (strlen($newPass) < 6) {
                    $error = "Mật khẩu mới phải có tối thiểu 6 ký tự.";
                } elseif ($newPass !== $confirmPass) {
                    $error = "Xác nhận mật khẩu mới không khớp.";
                } else {
                    $newHash = password_hash($newPass, PASSWORD_BCRYPT);
                    $this->userModel->updatePassword($userId, $newHash);
                    $user = $this->userModel->findById($userId);
                    $message = "Đổi mật khẩu tài khoản thành công!";
                }
            }
        }

        require dirname(__DIR__, 2) . '/views/auth/profile.php';
    }

    public function logout(): void {
        AuthHelper::logout();
        AuthHelper::setFlash('info', 'Bạn đã đăng xuất khỏi hệ thống thành công.');
        header("Location: " . App::url('index.php?action=login'));
        exit;
    }

    public function quickLogin(): void {
        $userId = trim($_GET['user_id'] ?? '');
        if (empty($userId)) {
            header("Location: " . App::url('index.php?action=login'));
            exit;
        }

        $user = $this->userModel->findById($userId);
        if ($user) {
            AuthHelper::login($user);
            AuthHelper::setFlash('success', "Đã đăng nhập nhanh với tài khoản " . $user['HoTen'] . " (" . $user['MaNguoiDung'] . ")");
            $this->redirectByRole($user['VaiTro']);
            return;
        }

        AuthHelper::setFlash('danger', "Không tìm thấy tài khoản mẫu {$userId}.");
        header("Location: " . App::url('index.php?action=login'));
        exit;
    }

    public function adminSwitchRole(): void {
        AuthHelper::requireLogin();
        if (!AuthHelper::isAdmin()) {
            AuthHelper::setFlash('danger', 'Chức năng chỉ dành riêng cho Quản trị viên hệ thống (Admin).');
            header("Location: " . App::url('index.php?action=403'));
            exit;
        }

        $targetRole = trim($_GET['role'] ?? '');
        $targetUserId = match ($targetRole) {
            App::ROLE_SINH_VIEN  => 'SV001',
            App::ROLE_GIANG_VIEN => 'GV001',
            App::ROLE_CAN_BO_QL  => 'CB001',
            App::ROLE_ADMIN      => 'ADMIN',
            default              => 'ADMIN'
        };

        $user = $this->userModel->findById($targetUserId);
        if (!$user) {
            $users = match ($targetRole) {
                App::ROLE_SINH_VIEN  => $this->userModel->getAllStudents(),
                App::ROLE_GIANG_VIEN => $this->userModel->getAllTeachers(),
                default              => []
            };
            if (!empty($users[0]['MaNguoiDung'])) {
                $user = $this->userModel->findById($users[0]['MaNguoiDung']);
            }
        }

        if (!$user) {
            AuthHelper::setFlash('warning', "Không tìm thấy tài khoản phù hợp với vai trò {$targetRole}.");
            header("Location: " . App::url('index.php?action=cb-dashboard'));
            exit;
        }

        // Đảm bảo cờ admin_root luôn được duy trì để Admin có thể chuyển đổi bất kỳ lúc nào
        $_SESSION['admin_root'] = true;
        $_SESSION['user'] = [
            'id'          => $user['MaNguoiDung'],
            'MaNguoiDung' => $user['MaNguoiDung'],
            'name'        => $user['HoTen'],
            'HoTen'       => $user['HoTen'],
            'email'       => $user['Email'],
            'Email'       => $user['Email'],
            'phone'       => $user['SoDienThoai'] ?? '',
            'SoDienThoai' => $user['SoDienThoai'] ?? '',
            'role'        => $user['VaiTro'],
            'VaiTro'      => $user['VaiTro'],
            'TrangThai'   => $user['TrangThai'] ?? 'HOAT_DONG',
            'NgayTao'     => $user['NgayTao'] ?? date('Y-m-d H:i:s'),
            'extra'       => $user['extra'] ?? []
        ];

        $roleTitle = match ($user['VaiTro']) {
            App::ROLE_SINH_VIEN  => 'Sinh Viên',
            App::ROLE_GIANG_VIEN => 'Giảng Viên',
            App::ROLE_CAN_BO_QL  => 'Cán Bộ Quản Lý',
            App::ROLE_ADMIN      => 'Quản Trị Viên (Admin)',
            default              => $user['VaiTro']
        };

        AuthHelper::setFlash('info', "👑 Đã chuyển sang giao diện: <strong>{$roleTitle}</strong> ({$user['MaNguoiDung']} - {$user['HoTen']}) với toàn quyền quản trị.");
        $this->redirectByRole($user['VaiTro']);
    }

    private function redirectByRole(string $role): void {
        switch ($role) {
            case App::ROLE_SINH_VIEN:
                header("Location: " . App::url('index.php?action=sv-dashboard'));
                break;
            case App::ROLE_GIANG_VIEN:
                header("Location: " . App::url('index.php?action=gv-dashboard'));
                break;
            case App::ROLE_CAN_BO_QL:
            case App::ROLE_ADMIN:
                header("Location: " . App::url('index.php?action=cb-dashboard'));
                break;
            default:
                header("Location: " . App::url('index.php?action=login'));
                break;
        }
        exit;
    }
}

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
                if ($user && password_verify($password, $user['MatKhauHash'])) {
                    AuthHelper::login($user);
                    AuthHelper::setFlash('success', "Chào mừng " . $user['HoTen'] . " đã đăng nhập thành công!");
                    $this->redirectByRole($user['VaiTro']);
                    return;
                } else {
                    $error = "Tài khoản hoặc mật khẩu không chính xác.";
                }
            }
        }

        require dirname(__DIR__, 2) . '/views/auth/login.php';
    }

    // Tiện ích đăng nhập nhanh 1 chạm cho Hội đồng / Người chấm bài / Khảo sát demo
    public function quickLogin(): void {
        $id = $_GET['user_id'] ?? '';
        $user = $this->userModel->findById($id);
        if ($user) {
            AuthHelper::login($user);
            AuthHelper::setFlash('success', "Đã đăng nhập nhanh với vai trò: " . $user['HoTen'] . " (" . $user['VaiTro'] . ")");
            $this->redirectByRole($user['VaiTro']);
            return;
        }
        AuthHelper::setFlash('danger', "Không tìm thấy tài khoản để đăng nhập nhanh.");
        header("Location: " . App::url('index.php?action=login'));
        exit;
    }

    public function logout(): void {
        AuthHelper::logout();
        AuthHelper::setFlash('info', 'Bạn đã đăng xuất khỏi hệ thống thành công.');
        header("Location: " . App::url('index.php?action=login'));
        exit;
    }

    public function profile(): void {
        AuthHelper::requireLogin();
        $userId = AuthHelper::id();
        $user = $this->userModel->findById($userId);
        $message = null;
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $subAction = $_POST['sub_action'] ?? '';
            if ($subAction === 'update_profile') {
                $hoTen = trim($_POST['hoten'] ?? '');
                $email = trim($_POST['email'] ?? '');
                $sdt = trim($_POST['sdt'] ?? '');
                if (empty($hoTen) || empty($email)) {
                    $error = "Họ tên và Email không được để trống.";
                } else {
                    $this->userModel->updateProfile($userId, $hoTen, $email, $sdt);
                    $_SESSION['user']['name'] = $hoTen;
                    $_SESSION['user']['email'] = $email;
                    $_SESSION['user']['phone'] = $sdt;
                    $user = $this->userModel->findById($userId);
                    $message = "Cập nhật thông tin cá nhân thành công!";
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
                    $message = "Đổi mật khẩu thành công!";
                }
            }
        }

        require dirname(__DIR__, 2) . '/views/auth/profile.php';
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

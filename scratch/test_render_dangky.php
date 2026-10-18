<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once __DIR__ . '/../app/Config/App.php';
require_once __DIR__ . '/../app/Config/Database.php';
require_once __DIR__ . '/../app/Helpers/AuthHelper.php';
require_once __DIR__ . '/../app/Helpers/ViewHelper.php';
require_once __DIR__ . '/../app/Helpers/NckhFormHelper.php';
require_once __DIR__ . '/../app/Models/BaseModel.php';
require_once __DIR__ . '/../app/Models/UserModel.php';
require_once __DIR__ . '/../app/Models/DotNCKHModel.php';

use App\Helpers\AuthHelper;
use App\Models\UserModel;
use App\Models\DotNCKHModel;

AuthHelper::login([
    'MaNguoiDung' => 'SV001',
    'HoTen' => 'Nguyễn Huy Hoàng',
    'Email' => 'sv1@tbu.edu.vn',
    'VaiTro' => 'SINH_VIEN',
    'extra' => ['Lop' => 'K64 ĐH CNTT-B']
]);

$studentId = 'SV001';
$dotModel = new DotNCKHModel();
$userModel = new UserModel();
$user = $userModel->findById($studentId);
$activeDots = $dotModel->getActive();
$directions = $dotModel->getAllResearchDirections();
$teachers = $userModel->getAllTeachers();
$allStudents = $userModel->getAllStudents();
$error = null;

ob_start();
require __DIR__ . '/../views/sinhvien/dangky_detai.php';
$content = ob_get_clean();

echo "RENDERED " . strlen($content) . " bytes successfully!" . PHP_EOL;

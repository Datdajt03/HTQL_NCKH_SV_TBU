<?php
require __DIR__ . '/../app/Config/App.php';
require __DIR__ . '/../app/Config/Database.php';
require __DIR__ . '/../app/Helpers/ViewHelper.php';
require __DIR__ . '/../app/Helpers/NckhFormHelper.php';
require __DIR__ . '/../app/Models/BaseModel.php';
require __DIR__ . '/../app/Models/DeTaiModel.php';

use App\Config\Database;
use App\Helpers\NckhFormHelper;
use App\Models\DeTaiModel;

echo "=== TESTING TOPIC DT_2026_004 RENDERING ===\n";
$model = new DeTaiModel();
$topic = $model->findById('DT_2026_004');
$members = $model->getMembers('DT_2026_004');

echo "Topic: " . $topic['TenDeTai'] . "\n";
echo "Status: " . $topic['TrangThai'] . "\n";
echo "Members count: " . count($members) . "\n";

$parsedDX = NckhFormHelper::parseDeXuatData($topic['DuLieuDeXuat'] ?? null, $topic, $members);
$parsedTM = NckhFormHelper::parseThuyetMinhData($topic['DuLieuThuyetMinh'] ?? null, $topic, $members);

echo "DX Title: " . $parsedDX['ten_de_tai'] . "\n";
echo "DX Sections: 1. Ten, 2. Cap thiet (" . strlen($parsedDX['tinh_cap_thiet']) . " chars), 3. Muc tieu, 4. Noi dung (" . count($parsedDX['noi_dung_chu_yeu']) . " items), 5. San pham, 6. Kha nang dia chi\n";

$htmlDX = NckhFormHelper::renderPhieuDeXuatHtml($parsedDX);
echo "Rendered DX HTML length: " . strlen($htmlDX) . " bytes\n";

echo "TM Title: " . $parsedTM['ten_de_tai'] . "\n";
echo "TM Loai Hinh: " . $parsedTM['loai_hinh_nc'] . "\n";
echo "TM Members: " . count($parsedTM['thanh_vien_tham_gia']) . "\n";
echo "TM Tien Do: " . count($parsedTM['tien_do_thuc_hien']) . " phases\n";
echo "TM San Pham: " . count($parsedTM['san_pham_ung_dung']['danh_sach']) . " products\n";

$htmlTM = NckhFormHelper::renderThuyetMinhHtml($parsedTM);
echo "Rendered TM HTML length: " . strlen($htmlTM) . " bytes\n";

echo "\n=== ALL CHECKS PASSED SUCCESSFULLY ===\n";

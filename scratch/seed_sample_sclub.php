<?php
require __DIR__ . '/../app/Config/Database.php';
require __DIR__ . '/../app/Helpers/ViewHelper.php';
require __DIR__ . '/../app/Helpers/NckhFormHelper.php';

use App\Helpers\NckhFormHelper;

try {
    $db = \App\Config\Database::getConnection();
    
    $dxData = NckhFormHelper::getSampleDeXuatData();
    $tmData = NckhFormHelper::getSampleThuyetMinhData();
    
    $jsonDX = json_encode($dxData, JSON_UNESCAPED_UNICODE);
    $jsonTM = json_encode($tmData, JSON_UNESCAPED_UNICODE);

    // Update DT_2026_004 with s-Club data
    $stmt = $db->prepare("UPDATE DeTai SET 
        TenDeTai = :ten,
        MucTieu = :muctieu,
        NoiDungNC = :noidung,
        SanPhamDuKien = :sanpham,
        DuLieuDeXuat = :dx,
        DuLieuThuyetMinh = :tm
        WHERE MaDeTai = 'DT_2026_004'");

    $stmt->execute([
        'ten' => $dxData['ten_de_tai'],
        'muctieu' => $dxData['muc_tieu'],
        'noidung' => $dxData['noi_dung_chu_yeu'][0]['chi_tiet'],
        'sanpham' => $dxData['du_kien_san_pham'],
        'dx' => $jsonDX,
        'tm' => $jsonTM
    ]);

    echo "Successfully updated DT_2026_004 with s-Club standardized online data!\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

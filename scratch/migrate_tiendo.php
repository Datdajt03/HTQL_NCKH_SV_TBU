<?php
require __DIR__ . '/../app/Config/Database.php';

try {
    $db = \App\Config\Database::getConnection();

    $cols = $db->query("SHOW COLUMNS FROM BaoCaoTienDo")->fetchAll(PDO::FETCH_COLUMN);
    
    if (!in_array('ChiTietTienDo', $cols)) {
        $db->exec("ALTER TABLE BaoCaoTienDo ADD COLUMN ChiTietTienDo LONGTEXT NULL AFTER CongViecDaLam");
        echo "Added ChiTietTienDo column to BaoCaoTienDo.\n";
    }

    if (!in_array('NhanXetCanBo', $cols)) {
        $db->exec("ALTER TABLE BaoCaoTienDo ADD COLUMN NhanXetCanBo TEXT NULL AFTER NhanXetGVHD");
        echo "Added NhanXetCanBo column to BaoCaoTienDo.\n";
    }

    if (!in_array('DanhGiaCanBo', $cols)) {
        $db->exec("ALTER TABLE BaoCaoTienDo ADD COLUMN DanhGiaCanBo VARCHAR(30) NULL AFTER DanhGiaTienDo");
        echo "Added DanhGiaCanBo column to BaoCaoTienDo.\n";
    }

    if (!in_array('NgayCanBoDanhGia', $cols)) {
        $db->exec("ALTER TABLE BaoCaoTienDo ADD COLUMN NgayCanBoDanhGia TIMESTAMP NULL DEFAULT NULL AFTER NgayDanhGia");
        echo "Added NgayCanBoDanhGia column to BaoCaoTienDo.\n";
    }

    $db->exec("ALTER TABLE BaoCaoTienDo MODIFY COLUMN FileBaoCao VARCHAR(500) NULL");
    echo "Made FileBaoCao NULLABLE in BaoCaoTienDo.\n";

    echo "Migration completed successfully!\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

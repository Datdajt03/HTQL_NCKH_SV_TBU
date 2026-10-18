<?php
require __DIR__ . '/../app/Config/Database.php';

try {
    $db = \App\Config\Database::getConnection();
    $stmt = $db->query('SELECT MaDeTai, TenDeTai, TrangThai, LENGTH(DuLieuDeXuat) AS lenDX, LENGTH(DuLieuThuyetMinh) AS lenTM FROM DeTai');
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "Found " . count($rows) . " topics:\n";
    foreach ($rows as $r) {
        echo " - {$r['MaDeTai']} | {$r['TenDeTai']} | Status: {$r['TrangThai']} | DX: " . ($r['lenDX'] ?? 'NULL') . " bytes | TM: " . ($r['lenTM'] ?? 'NULL') . " bytes\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

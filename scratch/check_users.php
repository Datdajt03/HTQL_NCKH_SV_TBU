<?php
require __DIR__ . '/../app/Config/Database.php';

try {
    $db = \App\Config\Database::getConnection();
    echo "--- STUDENTS ---\n";
    $st = $db->query("SELECT sv.MaSV, nd.HoTen, nd.Email, sv.Lop, sv.Khoa FROM SinhVien sv JOIN NguoiDung nd ON sv.MaSV = nd.MaNguoiDung");
    foreach ($st->fetchAll(PDO::FETCH_ASSOC) as $s) {
        echo " {$s['MaSV']} - {$s['HoTen']} - {$s['Lop']} - {$s['Khoa']}\n";
    }

    echo "\n--- LECTURERS ---\n";
    $st = $db->query("SELECT gv.MaGV, nd.HoTen, gv.HocHam, gv.HocVi, gv.DonViCongTac FROM GiangVien gv JOIN NguoiDung nd ON gv.MaGV = nd.MaNguoiDung");
    foreach ($st->fetchAll(PDO::FETCH_ASSOC) as $g) {
        echo " {$g['MaGV']} - {$g['HocVi']} {$g['HoTen']} - {$g['DonViCongTac']}\n";
    }

    echo "\n--- TOPIC MEMBERS ---\n";
    $st = $db->query("SELECT tv.MaDeTai, tv.MaSV, nd.HoTen, tv.VaiTro, dt.TrangThai FROM ThanhVienDeTai tv JOIN NguoiDung nd ON tv.MaSV = nd.MaNguoiDung JOIN DeTai dt ON tv.MaDeTai = dt.MaDeTai");
    foreach ($st->fetchAll(PDO::FETCH_ASSOC) as $r) {
        echo " {$r['MaDeTai']} - {$r['MaSV']} ({$r['HoTen']}) - {$r['VaiTro']} - Status: {$r['TrangThai']}\n";
    }
} catch (Exception $e) {
    echo $e->getMessage() . "\n";
}

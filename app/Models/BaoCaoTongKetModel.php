<?php
namespace App\Models;

use Exception;

class BaoCaoTongKetModel extends BaseModel {
    public function findByTopic(string $maDeTai): ?array {
        $report = $this->fetchOne(
            "SELECT bc.*, dt.TenDeTai, dt.MaGVHD, dt.TrangThai AS TrangThaiDeTai
             FROM BaoCaoTongKet bc
             JOIN DeTai dt ON bc.MaDeTai = dt.MaDeTai
             WHERE bc.MaDeTai = :id",
            ['id' => $maDeTai]
        );
        if ($report) {
            $report['san_pham'] = $this->getProducts($report['MaBaoCaoTK']);
        }
        return $report;
    }

    public function getProducts(int $maBaoCaoTK): array {
        return $this->fetchAll(
            "SELECT * FROM SanPhamNghienCuu WHERE MaBaoCaoTK = :id ORDER BY MaSanPham ASC",
            ['id' => $maBaoCaoTK]
        );
    }

    // UC 5: Nộp báo cáo tổng kết & sản phẩm nghiên cứu
    public function submitFinalReport(array $reportData, array $products): int {
        $this->db->beginTransaction();
        try {
            // Kiểm tra xem đã có báo cáo tổng kết chưa
            $existing = $this->fetchOne(
                "SELECT MaBaoCaoTK FROM BaoCaoTongKet WHERE MaDeTai = :id",
                ['id' => $reportData['MaDeTai']]
            );

            if ($existing) {
                // Cập nhật
                $maBaoCaoTK = (int)$existing['MaBaoCaoTK'];
                $sql = "UPDATE BaoCaoTongKet SET
                            TomTatKetQua = :tomtat,
                            FileBaoCaoTongKet = :filebc,
                            DanhGiaSoBoGVHD = 'CHO_DANH_GIA',
                            NhanXetSoBoGVHD = NULL,
                            NgayGVHDDanhGia = NULL,
                            NgayNop = CURRENT_TIMESTAMP
                        WHERE MaBaoCaoTK = :id";
                $this->execute($sql, [
                    'tomtat' => $reportData['TomTatKetQua'],
                    'filebc' => $reportData['FileBaoCaoTongKet'],
                    'id'     => $maBaoCaoTK
                ]);
                // Xóa sản phẩm cũ để cập nhật mới
                $this->execute("DELETE FROM SanPhamNghienCuu WHERE MaBaoCaoTK = :id", ['id' => $maBaoCaoTK]);
            } else {
                // Thêm mới
                $sql = "INSERT INTO BaoCaoTongKet (MaDeTai, TomTatKetQua, FileBaoCaoTongKet, DanhGiaSoBoGVHD)
                        VALUES (:madetai, :tomtat, :filebc, 'CHO_DANH_GIA')";
                $this->execute($sql, [
                    'madetai' => $reportData['MaDeTai'],
                    'tomtat'  => $reportData['TomTatKetQua'],
                    'filebc'  => $reportData['FileBaoCaoTongKet']
                ]);
                $maBaoCaoTK = (int)$this->lastInsertId();
            }

            // Thêm các sản phẩm nghiên cứu
            $productSql = "INSERT INTO SanPhamNghienCuu (MaBaoCaoTK, TenSanPham, LoaiSanPham, MoTaSanPham, LienKetMinhChung, FileMinhChung)
                           VALUES (:mabc, :ten, :loai, :mota, :link, :file)";
            foreach ($products as $p) {
                $this->execute($productSql, [
                    'mabc' => $maBaoCaoTK,
                    'ten'  => $p['TenSanPham'],
                    'loai' => $p['LoaiSanPham'],
                    'mota' => $p['MoTaSanPham'] ?? null,
                    'link' => $p['LienKetMinhChung'] ?? null,
                    'file' => $p['FileMinhChung'] ?? null
                ]);
            }

            $this->db->commit();
            return $maBaoCaoTK;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    // UC 10: GVHD đánh giá sơ bộ sản phẩm và xác nhận cho phép nghiệm thu
    public function teacherPreliminaryReview(int $reportId, string $danhGia, ?string $nhanXet): bool {
        $this->db->beginTransaction();
        try {
            // Cập nhật báo cáo tổng kết
            $this->execute(
                "UPDATE BaoCaoTongKet SET
                    DanhGiaSoBoGVHD = :danhgia,
                    NhanXetSoBoGVHD = :nhanxet,
                    NgayGVHDDanhGia = CURRENT_TIMESTAMP
                 WHERE MaBaoCaoTK = :id",
                [
                    'danhgia' => $danhGia,
                    'nhanxet' => $nhanXet,
                    'id'      => $reportId
                ]
            );

            // Nếu GVHD đồng ý cho nghiệm thu -> chuyển trạng thái đề tài sang CHO_NGHIEM_THU
            if ($danhGia === 'DONG_Y_NGHIEM_THU') {
                $report = $this->fetchOne("SELECT MaDeTai FROM BaoCaoTongKet WHERE MaBaoCaoTK = :id", ['id' => $reportId]);
                if ($report) {
                    $this->execute(
                        "UPDATE DeTai SET TrangThai = 'CHO_NGHIEM_THU' WHERE MaDeTai = :id",
                        ['id' => $report['MaDeTai']]
                    );
                }
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function deleteFinalReport(int $id): bool {
        $this->db->beginTransaction();
        try {
            $this->execute("DELETE FROM SanPhamNghienCuu WHERE MaBaoCaoTK = :id", ['id' => $id]);
            $res = $this->execute("DELETE FROM BaoCaoTongKet WHERE MaBaoCaoTK = :id", ['id' => $id]);
            $this->db->commit();
            return $res;
        } catch (\Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function deleteFinalReportByTopic(string $maDeTai): bool {
        $report = $this->fetchOne("SELECT MaBaoCaoTK FROM BaoCaoTongKet WHERE MaDeTai = :id", ['id' => $maDeTai]);
        if ($report) {
            return $this->deleteFinalReport((int)$report['MaBaoCaoTK']);
        }
        return false;
    }
}

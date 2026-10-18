<?php
use App\Config\App;
use App\Helpers\ViewHelper;

$pageTitle = "Xếp Loại & Khen Thưởng NCKH";
require dirname(__DIR__) . '/layouts/header.php';
?>
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h2 style="font-size: 20px; font-weight: 800; color: #fff; margin-bottom: 4px;">
            15. Xếp Loại Đề Tài & Khen Thưởng Thành Tích NCKH
        </h2>
        <p style="color: var(--text-secondary); font-size: 14px; margin: 0;">
            Tổng hợp điểm số nghiệm thu, xếp loại chính thức, phân bổ các mức giải thưởng và cấp quyết định khen thưởng của Hiệu trưởng.
        </p>
    </div>
    <div style="display: flex; gap: 10px;">
        <a href="<?= App::url('index.php?action=cb-in-danhsach' . ($filterDot ? '&dot=' . $filterDot : '')) ?>" target="_blank" class="btn btn-secondary">
            <?= ViewHelper::icon('printer') ?> In Danh Sách Khen Thưởng
        </a>
        <a href="<?= App::url('index.php?action=export-awards' . ($filterDot ? '&dot=' . $filterDot : '')) ?>" class="btn btn-secondary">
            <?= ViewHelper::icon('download') ?> Xuất File Excel / CSV
        </a>
        <button type="button" class="btn btn-primary" onclick="openModal('modal-add-award')">
            <?= ViewHelper::icon('award') ?> Xếp Loại & Khen Thưởng
        </button>
    </div>
</div>

<!-- DANH SÁCH KHEN THƯỞNG ĐÃ BAN HÀNH -->
<div class="card">
    <div class="card-header">
        <div class="card-title">
            <?= ViewHelper::icon('award') ?>
            <span>Bảng Vinh Danh & Danh Sách Đề Tài Đạt Giải Khen Thưởng (<?= count($awards) ?> đề tài)</span>
        </div>
        <!-- Filter đợt -->
        <form method="GET" action="<?= App::url('index.php') ?>" style="display: flex; gap: 8px;">
            <input type="hidden" name="action" value="cb-xeploai-khenthuong">
            <select name="dot" class="form-control" onchange="this.form.submit()" style="padding: 6px 12px; font-size: 13px;">
                <option value="">-- Tất cả các đợt --</option>
                <?php foreach ($dots as $d): ?>
                    <option value="<?= ViewHelper::e($d['MaDot']) ?>" <?= $filterDot === $d['MaDot'] ? 'selected' : '' ?>>
                        <?= ViewHelper::e($d['TenDot']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>
    <div class="card-body" style="padding: 0;">
        <?php if (empty($awards)): ?>
            <div style="text-align: center; padding: 48px; color: var(--text-muted);">
                Chưa có đề tài nào được ban hành quyết định khen thưởng trong đợt này.
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Mã Đề Tài</th>
                            <th>Tên Đề Tài</th>
                            <th>Trưởng Nhóm / Lớp</th>
                            <th>GVHD</th>
                            <th>Điểm Tổng Kết</th>
                            <th>Xếp Loại</th>
                            <th>Danh Hiệu Khen Thưởng</th>
                            <th>Tiền Thưởng (VNĐ)</th>
                            <th>Số Quyết Định</th>
                            <th>Tác Vụ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($awards as $a): ?>
                            <tr>
                                <td><strong style="color: #60a5fa;"><?= ViewHelper::e($a['MaDeTai']) ?></strong></td>
                                <td style="max-width: 240px; font-weight: 600; color: #fff;">
                                    <?= ViewHelper::e($a['TenDeTai']) ?>
                                    <div style="font-size: 11px; color: var(--text-muted);"><?= ViewHelper::e($a['TenDot']) ?></div>
                                </td>
                                <td>
                                    <strong style="color: #fff;"><?= ViewHelper::e($a['TenTruongNhom']) ?></strong>
                                    <div style="font-size: 12px; color: var(--text-muted);"><?= ViewHelper::e($a['LopTruongNhom']) ?></div>
                                </td>
                                <td><?= ViewHelper::e($a['TenGVHD']) ?></td>
                                <td>
                                    <strong style="color: #38bdf8; font-size: 15px;">
                                        <?= number_format((float)$a['DiemTongKet'], 2) ?>
                                    </strong>
                                </td>
                                <td><?= ViewHelper::statusBadge($a['XepLoai']) ?></td>
                                <td>
                                    <?= ViewHelper::statusBadge($a['DanhHieuKhenThuong']) ?>
                                </td>
                                <td>
                                    <strong style="color: #f59e0b;"><?= ViewHelper::money($a['TienThuong']) ?></strong>
                                </td>
                                <td style="font-size: 12px; color: var(--text-secondary);">
                                    <?= ViewHelper::e($a['SoQuyetDinh'] ?: 'Chưa số QĐ') ?>
                                    <div style="font-size: 11px; color: var(--text-muted);"><?= ViewHelper::date($a['NgayQuyetDinh']) ?></div>
                                </td>
                                <td>
                                    <div style="display: flex; gap: 6px;">
                                        <button type="button" class="btn btn-sm btn-secondary" onclick="editAward('<?= ViewHelper::e($a['MaDeTai']) ?>', <?= (float)$a['DiemTongKet'] ?>, '<?= $a['XepLoai'] ?>', '<?= $a['DanhHieuKhenThuong'] ?>', '<?= ViewHelper::e($a['SoQuyetDinh'] ?? '') ?>', <?= (float)$a['TienThuong'] ?>)">
                                            <?= ViewHelper::icon('edit') ?> Sửa
                                        </button>
                                        <form method="POST" action="<?= App::url('index.php?action=cb-xeploai-khenthuong') ?>" style="display:inline;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa kết quả xếp loại / khen thưởng của đề tài <?= ViewHelper::e($a['MaDeTai']) ?>?');">
                                            <input type="hidden" name="sub_action" value="delete_award">
                                            <input type="hidden" name="madetai" value="<?= ViewHelper::e($a['MaDeTai']) ?>">
                                            <button type="submit" class="btn btn-sm btn-danger" title="Xóa kết quả khen thưởng">
                                                <?= ViewHelper::icon('trash') ?>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- MODAL: XẾP LOẠI & KHEN THƯỞNG -->
<div id="modal-add-award" class="modal-backdrop">
    <div class="modal-container">
        <div class="modal-header">
            <div class="modal-title">Xếp Loại & Ban Hành Quyết Định Khen Thưởng</div>
            <button type="button" class="modal-close" onclick="closeModal('modal-add-award')">&times;</button>
        </div>
        <form method="POST" action="<?= App::url('index.php?action=cb-xeploai-khenthuong') ?>">
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Chọn Đề Tài Cần Xếp Loại / Khen Thưởng <span class="required">*</span></label>
                    <select name="madetai" id="award-madetai" class="form-control" required>
                        <option value="">-- Chọn đề tài --</option>
                        <?php foreach ($defendedTopics as $t): ?>
                            <option value="<?= ViewHelper::e($t['MaDeTai']) ?>">
                                <?= ViewHelper::e($t['MaDeTai']) ?> - <?= ViewHelper::e($t['TenDeTai']) ?> (GVHD: <?= ViewHelper::e($t['TenGVHD']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="grid-cols-2">
                    <div class="form-group">
                        <label class="form-label">Điểm Tổng Kết Chính Thức (Thang 10 hoặc 100) <span class="required">*</span></label>
                        <input type="number" step="0.05" min="0" max="100" name="diem_tongket" id="award-diem" class="form-control" placeholder="VD: 9.35 hoặc 93.5" required oninput="autoSelectRank(this.value)">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Xếp Loại Chất Lượng <span class="required">*</span></label>
                        <select name="xep_loai" id="award-xeploai" class="form-control" required>
                            <option value="XUAT_SAC">Xuất Sắc (>= 9.0)</option>
                            <option value="TOT">Tốt (8.0 - 8.9)</option>
                            <option value="KHA">Khá (7.0 - 7.9)</option>
                            <option value="DAT">Đạt (5.0 - 6.9)</option>
                            <option value="KHONG_DAT">Không Đạt (< 5.0)</option>
                        </select>
                    </div>
                </div>

                <div class="grid-cols-2">
                    <div class="form-group">
                        <label class="form-label">Danh Hiệu Khen Thưởng</label>
                        <select name="danh_hieu" id="award-danhhieu" class="form-control">
                            <option value="KHONG">Không xét giải</option>
                            <option value="GIAI_NHAT">Giải Nhất NCKH Sinh viên</option>
                            <option value="GIAI_NHI">Giải Nhì NCKH Sinh viên</option>
                            <option value="GIAI_BA">Giải Ba NCKH Sinh viên</option>
                            <option value="GIAI_KHUYEN_KHICH">Giải Khuyến Khích</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Mức Tiền Thưởng (VNĐ)</label>
                        <input type="number" name="tien_thuong" id="award-tienthuong" class="form-control" placeholder="VD: 5000000" min="0" step="500000" value="0">
                    </div>
                </div>

                <div class="grid-cols-2">
                    <div class="form-group">
                        <label class="form-label">Số Quyết Định Của Hiệu Trưởng</label>
                        <input type="text" name="so_quyetdinh" id="award-soqd" class="form-control" placeholder="VD: QĐ-KHT-2026/01-KT">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Ngày Ban Hành Quyết Định</label>
                        <input type="date" name="ngay_quyetdinh" id="award-ngayqd" class="form-control" value="<?= date('Y-m-d') ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Ghi Chú Khen Thưởng</label>
                    <textarea name="ghi_chu" id="award-ghichu" class="form-control" rows="2" placeholder="VD: Tặng Giấy khen của Hiệu trưởng và phần thưởng..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modal-add-award')">Hủy</button>
                <button type="submit" class="btn btn-primary">Lưu Quyết Định Khen Thưởng</button>
            </div>
        </form>
    </div>
</div>

<script>
function autoSelectRank(score) {
    const s = parseFloat(score) || 0;
    const normalized = (s > 10) ? (s / 10.0) : s;
    const rankSelect = document.getElementById('award-xeploai');
    if (normalized >= 9.0) rankSelect.value = 'XUAT_SAC';
    else if (normalized >= 8.0) rankSelect.value = 'TOT';
    else if (normalized >= 7.0) rankSelect.value = 'KHA';
    else if (normalized >= 5.0) rankSelect.value = 'DAT';
    else rankSelect.value = 'KHONG_DAT';
}

function editAward(maDeTai, diem, xepLoai, danhHieu, soQD, tienThuong) {
    document.getElementById('award-madetai').value = maDeTai;
    document.getElementById('award-diem').value = diem;
    document.getElementById('award-xeploai').value = xepLoai;
    document.getElementById('award-danhhieu').value = danhHieu;
    document.getElementById('award-soqd').value = soQD;
    document.getElementById('award-tienthuong').value = tienThuong;
    openModal('modal-add-award');
}
</script>
<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>

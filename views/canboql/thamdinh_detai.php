<?php
use App\Config\App;
use App\Helpers\ViewHelper;

$pageTitle = "Thẩm Định & Duyệt Đề Tài NCKH";
require dirname(__DIR__) . '/layouts/header.php';
?>
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h2 style="font-size: 20px; font-weight: 800; color: #fff; margin-bottom: 4px;">
            12. Thẩm Định & Phê Duyệt Đề Tài NCKH
        </h2>
        <p style="color: var(--text-secondary); font-size: 14px; margin: 0;">
            Kiểm tra tính hợp lệ, tính khoa học của hồ sơ đã qua GVHD đồng ý hướng dẫn, phê duyệt giao đề tài hoặc yêu cầu sửa đổi.
        </p>
    </div>

    <!-- FILTER THEO ĐỢT -->
    <form method="GET" action="<?= App::url('index.php') ?>" style="display: flex; gap: 8px;">
        <input type="hidden" name="action" value="cb-thamdinh-detai">
        <select name="dot" class="form-control" onchange="this.form.submit()" style="padding: 8px 12px; font-size: 13px;">
            <option value="">-- Tất cả các đợt --</option>
            <?php foreach ($dots as $d): ?>
                <option value="<?= ViewHelper::e($d['MaDot']) ?>" <?= $filterDot === $d['MaDot'] ? 'selected' : '' ?>>
                    <?= ViewHelper::e($d['TenDot']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>
</div>

<!-- DANH SÁCH ĐỀ TÀI CHỜ THẨM ĐỊNH -->
<div class="card" style="border-color: rgba(59, 130, 246, 0.4);">
    <div class="card-header" style="background: rgba(59, 130, 246, 0.08);">
        <div class="card-title">
            <?= ViewHelper::icon('clock') ?>
            <span>Hồ Sơ Đang Chờ Cán Bộ QL Thẩm Định (<?= count($pendingTopics) ?>)</span>
        </div>
    </div>
    <div class="card-body">
        <?php if (empty($pendingTopics)): ?>
            <div style="text-align: center; padding: 36px; color: var(--text-muted); display: flex; align-items: center; justify-content: center; gap: 8px;">
                <?= ViewHelper::icon('check-circle') ?> Hiện không có đề tài nào đang chờ thẩm định.
            </div>
        <?php else: ?>
            <div style="display: flex; flex-direction: column; gap: 24px;">
                <?php foreach ($pendingTopics as $t): ?>
                    <div class="card" style="margin-bottom: 0; background: rgba(15, 23, 42, 0.6); border: 1px solid var(--border-color);">
                        <div class="card-header">
                            <div>
                                <span style="color: #60a5fa; font-weight: 700; font-size: 13px;"><?= ViewHelper::e($t['MaDeTai']) ?></span>
                                <h3 style="font-size: 17px; font-weight: 800; color: #fff; margin-top: 2px;">
                                    <?= ViewHelper::e($t['TenDeTai']) ?>
                                </h3>
                            </div>
                            <?= ViewHelper::statusBadge($t['TrangThai']) ?>
                        </div>
                        <div class="card-body">
                            <div class="grid-cols-4" style="margin-bottom: 16px; font-size: 13px;">
                                <div>
                                    <span style="color: var(--text-muted);">Đợt NCKH:</span>
                                    <strong style="color: #fff; display: block;"><?= ViewHelper::e($t['TenDot']) ?></strong>
                                </div>
                                <div>
                                    <span style="color: var(--text-muted);">Hướng NC:</span>
                                    <strong style="color: #38bdf8; display: block;"><?= ViewHelper::e($t['TenHuongNC']) ?></strong>
                                </div>
                                <div>
                                    <span style="color: var(--text-muted);">GVHD:</span>
                                    <strong style="color: #fff; display: block;"><?= ViewHelper::e($t['TenGVHD']) ?></strong>
                                </div>
                                <div>
                                    <span style="color: var(--text-muted);">Trưởng nhóm:</span>
                                    <strong style="color: #fff; display: block;"><?= ViewHelper::e($t['TenTruongNhom']) ?></strong>
                                </div>
                            </div>

                            <div style="margin-bottom: 14px;">
                                <strong style="color: #60a5fa; font-size: 13px; display: block; margin-bottom: 4px;">Mục tiêu đề tài:</strong>
                                <div style="color: #cbd5e1; font-size: 14px;"><?= nl2br(ViewHelper::e($t['MucTieu'])) ?></div>
                            </div>

                            <div style="margin-bottom: 14px;">
                                <strong style="color: #60a5fa; font-size: 13px; display: block; margin-bottom: 4px;">Ý kiến đồng ý của GVHD:</strong>
                                <div style="color: #a78bfa; font-size: 13px; background: rgba(139, 92, 246, 0.08); padding: 10px 14px; border-radius: var(--radius-sm); border-left: 3px solid #8b5cf6;">
                                    "<?= ViewHelper::e($t['NhanXetGVHD'] ?: 'GVHD đã xác nhận đồng ý hướng dẫn') ?>"
                                    <div style="font-size: 11px; color: var(--text-muted); margin-top: 4px;">Duyệt ngày: <?= ViewHelper::datetime($t['NgayGVHDDuyet']) ?></div>
                                </div>
                            </div>

                            <div style="display: flex; gap: 12px; margin-bottom: 20px; flex-wrap: wrap;">
                                <a href="<?= App::url('public/' . $t['FilePhieuThuyetMinh']) ?>" target="_blank" class="btn btn-sm btn-secondary" style="color: #93c5fd; border-color: rgba(59, 130, 246, 0.4);">
                                    <?= ViewHelper::icon('download') ?> Tải Phiếu Thuyết Minh
                                </a>
                                <a href="<?= App::url('public/' . $t['FilePhieuDeXuat']) ?>" target="_blank" class="btn btn-sm btn-secondary" style="color: #93c5fd; border-color: rgba(59, 130, 246, 0.4);">
                                    <?= ViewHelper::icon('download') ?> Tải Phiếu Đề Xuất
                                </a>
                                <a href="<?= App::url('index.php?action=cb-chitiet-detai&id=' . $t['MaDeTai']) ?>" class="btn btn-sm btn-secondary">
                                    <?= ViewHelper::icon('search') ?> Xem toàn bộ hồ sơ & Nhóm SV
                                </a>
                            </div>

                            <!-- FORM THẨM ĐỊNH -->
                            <form method="POST" action="<?= App::url('index.php?action=cb-thamdinh-detai') ?>" style="background: rgba(15, 23, 42, 0.85); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 18px;">
                                <input type="hidden" name="madetai" value="<?= ViewHelper::e($t['MaDeTai']) ?>">

                                <div class="form-group" style="margin-bottom: 14px;">
                                    <label class="form-label">Ý Kiến Thẩm Định Của Cán Bộ Quản Lý / Hội Đồng Trường <span class="required">*</span></label>
                                    <textarea name="comments" class="form-control" rows="3" placeholder="Nhập kết luận thẩm định, các nội dung yêu cầu sửa đổi (nếu có) hoặc phê chuẩn phân bổ phòng thí nghiệm..." required></textarea>
                                </div>

                                <div style="display: flex; gap: 12px; justify-content: flex-end;">
                                    <button type="submit" name="decision" value="TU_CHOI" class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn TỪ CHỐI đề tài này?');">
                                        <?= ViewHelper::icon('x') ?> Từ Chối / Không Duyệt
                                    </button>
                                    <button type="submit" name="decision" value="YEU_CAU_SUA" class="btn btn-secondary" style="border-color: #f59e0b; color: #fde68a;">
                                        <?= ViewHelper::icon('edit') ?> Yêu Cầu Sửa Đổi Hồ Sơ
                                    </button>
                                    <button type="submit" name="decision" value="PHE_DUYET" class="btn btn-success">
                                        <?= ViewHelper::icon('check') ?> Phê Duyệt & Giao Đề Tài
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- TẤT CẢ ĐỀ TÀI TRONG ĐỢT -->
<div class="card">
    <div class="card-header">
        <div class="card-title">
            <?= ViewHelper::icon('file-text') ?>
            <span>Tổng Hợp Tình Trạng Hồ Sơ Đề Tài Toàn Đợt (<?= count($allTopics) ?> đề tài)</span>
        </div>
        <a href="<?= App::url('index.php?action=export-topics' . ($filterDot ? '&dot=' . $filterDot : '')) ?>" class="btn btn-sm btn-secondary">
            <?= ViewHelper::icon('download') ?> Xuất Excel/CSV
        </a>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Mã Đề Tài</th>
                        <th>Tên Đề Tài</th>
                        <th>Đợt</th>
                        <th>GVHD</th>
                        <th>Trưởng Nhóm</th>
                        <th>Trạng Thái</th>
                        <th>Ý Kiến Cán Bộ</th>
                        <th>Tác Vụ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($allTopics as $t): ?>
                        <tr>
                            <td><strong style="color: #60a5fa;"><?= ViewHelper::e($t['MaDeTai']) ?></strong></td>
                            <td style="max-width: 260px; font-weight: 600; color: #fff;">
                                <?= ViewHelper::e($t['TenDeTai']) ?>
                            </td>
                            <td><?= ViewHelper::e($t['TenDot']) ?></td>
                            <td><?= ViewHelper::e($t['TenGVHD']) ?></td>
                            <td><?= ViewHelper::e($t['TenTruongNhom']) ?></td>
                            <td><?= ViewHelper::statusBadge($t['TrangThai']) ?></td>
                            <td style="max-width: 220px; font-size: 13px;">
                                <?= ViewHelper::e($t['NhanXetCanBo'] ?: '-') ?>
                            </td>
                            <td>
                                <a href="<?= App::url('index.php?action=cb-chitiet-detai&id=' . $t['MaDeTai']) ?>" class="btn btn-sm btn-secondary">
                                    Chi tiết
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>

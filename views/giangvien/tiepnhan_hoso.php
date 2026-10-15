<?php
use App\Config\App;
use App\Helpers\ViewHelper;

$pageTitle = "Tiếp Nhận & Duyệt Hồ Sơ Đề Tài";
require dirname(__DIR__) . '/layouts/header.php';
?>
<div style="margin-bottom: 24px;">
    <h2 style="font-size: 20px; font-weight: 800; color: #fff; margin-bottom: 4px;">
        7 & 8. Tiếp Nhận & Xét Duyệt Hướng Dẫn Đề Tài
    </h2>
    <p style="color: var(--text-secondary); font-size: 14px; margin: 0;">
        Xem xét hồ sơ đề tài sinh viên đăng ký, kiểm tra phiếu thuyết minh, xác nhận đồng ý hoặc từ chối kèm góp ý.
    </p>
</div>

<!-- ĐỀ TÀI ĐANG CHỜ DUYỆT -->
<div class="card" style="border-color: rgba(245, 158, 11, 0.4);">
    <div class="card-header" style="background: rgba(245, 158, 11, 0.05);">
        <div class="card-title" style="color: #fef08a;">
            <span><?= ViewHelper::icon('clock', '', 18) ?></span>
            <span>Hồ Sơ Đề Tài Mới Đang Chờ Xét Duyệt (<?= count($pendingTopics) ?>)</span>
        </div>
    </div>
    <div class="card-body">
        <?php if (empty($pendingTopics)): ?>
            <div style="text-align: center; padding: 36px; color: var(--text-muted);">
                Hiện không có đề tài nào đang chờ duyệt hướng dẫn.
            </div>
        <?php else: ?>
            <div style="display: flex; flex-direction: column; gap: 20px;">
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
                            <div class="grid-cols-3" style="margin-bottom: 16px; font-size: 13px;">
                                <div>
                                    <span style="color: var(--text-muted);">Đợt NCKH:</span>
                                    <strong style="color: #fff; margin-left: 4px;"><?= ViewHelper::e($t['TenDot']) ?></strong>
                                </div>
                                <div>
                                    <span style="color: var(--text-muted);">Hướng NC:</span>
                                    <strong style="color: #38bdf8; margin-left: 4px;"><?= ViewHelper::e($t['TenHuongNC']) ?></strong>
                                </div>
                                <div>
                                    <span style="color: var(--text-muted);">Trưởng nhóm:</span>
                                    <strong style="color: #fff; margin-left: 4px;"><?= ViewHelper::e($t['TenTruongNhom']) ?></strong>
                                </div>
                            </div>

                            <div style="margin-bottom: 16px;">
                                <strong style="color: #60a5fa; font-size: 13px; display: block; margin-bottom: 4px;">Mục tiêu đề tài:</strong>
                                <div style="color: #cbd5e1; font-size: 14px;"><?= nl2br(ViewHelper::e($t['MucTieu'])) ?></div>
                            </div>

                            <div style="display: flex; gap: 12px; margin-bottom: 20px; flex-wrap: wrap;">
                                <a href="<?= App::url('public/' . $t['FilePhieuThuyetMinh']) ?>" target="_blank" class="btn btn-sm btn-secondary" style="border-color: rgba(59, 130, 246, 0.4); color: #93c5fd; display: inline-flex; align-items: center; gap: 6px;">
                                    <?= ViewHelper::icon('file-text', '', 14) ?> Xem Phiếu Thuyết Minh (Mẫu 01)
                                </a>
                                <a href="<?= App::url('public/' . $t['FilePhieuDeXuat']) ?>" target="_blank" class="btn btn-sm btn-secondary" style="border-color: rgba(59, 130, 246, 0.4); color: #93c5fd; display: inline-flex; align-items: center; gap: 6px;">
                                    <?= ViewHelper::icon('file-text', '', 14) ?> Xem Phiếu Đề Xuất (Mẫu 02)
                                </a>
                                <a href="<?= App::url('index.php?action=gv-chitiet-detai&id=' . $t['MaDeTai']) ?>" class="btn btn-sm btn-secondary" style="display: inline-flex; align-items: center; gap: 6px;">
                                    <?= ViewHelper::icon('eye', '', 14) ?> Xem toàn bộ hồ sơ
                                </a>
                            </div>

                            <!-- FORM DUYỆT -->
                            <form method="POST" action="<?= App::url('index.php?action=gv-tiepnhan-hoso') ?>" style="background: rgba(15, 23, 42, 0.8); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 18px;">
                                <input type="hidden" name="madetai" value="<?= ViewHelper::e($t['MaDeTai']) ?>">
                                
                                <div class="form-group" style="margin-bottom: 14px;">
                                    <label class="form-label">Ý Kiến Nhận Xét / Góp Ý Của Giảng Viên Hướng Dẫn</label>
                                    <textarea name="comments" class="form-control" rows="3" placeholder="Nhập nhận xét về tính khả thi, phương pháp nghiên cứu hoặc yêu cầu bổ sung nếu có..."></textarea>
                                </div>

                                <div style="display: flex; gap: 12px; justify-content: flex-end;">
                                    <button type="submit" name="decision" value="tu_choi" class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn TỪ CHỐI hướng dẫn đề tài này?');" style="display: inline-flex; align-items: center; gap: 6px;">
                                        <?= ViewHelper::icon('x', '', 14) ?> Từ Chối Hướng Dẫn
                                    </button>
                                    <button type="submit" name="decision" value="dong_y" class="btn btn-success" style="display: inline-flex; align-items: center; gap: 6px;">
                                        <?= ViewHelper::icon('check', '', 14) ?> Đồng Ý Hướng Dẫn & Trình Cán Bộ QL
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

<!-- LỊCH SỬ CÁC ĐỀ TÀI ĐÃ XÉT DUYỆT -->
<div class="card">
    <div class="card-header">
        <div class="card-title">
            <span><?= ViewHelper::icon('file-text', '', 18) ?></span>
            <span>Các Đề Tài Đã Xét Duyệt Trước Đây (<?= count($processedTopics) ?>)</span>
        </div>
    </div>
    <div class="card-body" style="padding: 0;">
        <?php if (empty($processedTopics)): ?>
            <div style="text-align: center; padding: 24px; color: var(--text-muted);">Chưa có dữ liệu.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Mã Đề Tài</th>
                            <th>Tên Đề Tài</th>
                            <th>Trưởng Nhóm</th>
                            <th>Trạng Thái</th>
                            <th>Nhận Xét Của GVHD</th>
                            <th>Ngày Duyệt</th>
                            <th>Tác Vụ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($processedTopics as $t): ?>
                            <tr>
                                <td><strong><?= ViewHelper::e($t['MaDeTai']) ?></strong></td>
                                <td style="max-width: 280px; font-weight: 600; color: #fff;">
                                    <?= ViewHelper::e($t['TenDeTai']) ?>
                                </td>
                                <td><?= ViewHelper::e($t['TenTruongNhom']) ?></td>
                                <td><?= ViewHelper::statusBadge($t['TrangThai']) ?></td>
                                <td style="max-width: 250px; font-size: 13px;"><?= nl2br(ViewHelper::e($t['NhanXetGVHD'] ?: '-')) ?></td>
                                <td><?= ViewHelper::datetime($t['NgayGVHDDuyet']) ?></td>
                                <td>
                                    <a href="<?= App::url('index.php?action=gv-chitiet-detai&id=' . $t['MaDeTai']) ?>" class="btn btn-sm btn-secondary">
                                        Chi tiết
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>

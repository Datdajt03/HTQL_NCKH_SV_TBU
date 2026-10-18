<?php
use App\Config\App;
use App\Helpers\ViewHelper;

$pageTitle = "Quản Lý & Phê Duyệt Tiến Độ NCKH Toàn Trường";
require dirname(__DIR__) . '/layouts/header.php';
?>
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
    <div>
        <h2 style="font-size: 20px; font-weight: 800; color: #fff; margin-bottom: 4px;">
            13. Giám Sát, Thẩm Định & Phê Duyệt Tiến Độ Đề Tài NCKH Toàn Trường
        </h2>
        <p style="color: var(--text-secondary); font-size: 14px; margin: 0;">
            Theo dõi tỷ lệ hoàn thành theo Mục 12 Thuyết minh, thẩm định sản phẩm nộp và phê duyệt tiến độ cho từng đề tài.
        </p>
    </div>

    <!-- FILTER THEO ĐỢT -->
    <form method="GET" action="<?= App::url('index.php') ?>" style="display: flex; gap: 8px;">
        <input type="hidden" name="action" value="cb-quanly-tiendo">
        <select name="dot" class="form-control" onchange="this.form.submit()" style="padding: 8px 12px; font-size: 13px;">
            <option value="">-- Tất cả các đợt NCKH --</option>
            <?php foreach ($dots as $d): ?>
                <option value="<?= ViewHelper::e($d['MaDot']) ?>" <?= $filterDot === $d['MaDot'] ? 'selected' : '' ?>>
                    <?= ViewHelper::e($d['TenDot']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">
            <?= ViewHelper::icon('bar-chart') ?>
            <span>Bảng Theo Dõi & Thẩm Định Tiến Độ Đề Tài (<?= count($progressList) ?> đề tài)</span>
        </div>
    </div>
    <div class="card-body" style="padding: 0;">
        <?php if (empty($progressList)): ?>
            <div style="text-align: center; padding: 48px; color: var(--text-muted);">
                Chưa có đề tài nào bước vào giai đoạn thực hiện trong đợt này.
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Mã Đề Tài</th>
                            <th>Tên Đề Tài & Trưởng Nhóm</th>
                            <th>GVHD</th>
                            <th>Tiến Độ (%)</th>
                            <th>Đợt Báo Cáo</th>
                            <th>Đánh Giá GVHD</th>
                            <th>Phê Duyệt Cán Bộ QL</th>
                            <th>Hành Động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($progressList as $idx => $p): 
                            $chiTietTasks = [];
                            if (!empty($p['ChiTietTienDo'])) {
                                $decoded = json_decode($p['ChiTietTienDo'], true);
                                if (is_array($decoded)) {
                                    $chiTietTasks = $decoded;
                                }
                            }
                        ?>
                            <tr>
                                <td>
                                    <strong style="color: #60a5fa;"><?= ViewHelper::e($p['MaDeTai']) ?></strong>
                                </td>
                                <td style="max-width: 250px;">
                                    <strong style="color: #fff; display: block; margin-bottom: 2px;">
                                        <?= ViewHelper::e($p['TenDeTai']) ?>
                                    </strong>
                                    <div style="font-size: 11.5px; color: var(--text-muted);">
                                        Nhóm trưởng: <span style="color: #cbd5e1;"><?= ViewHelper::e($p['TenTruongNhom']) ?></span>
                                    </div>
                                    <span style="font-size: 11px; color: #64748b;"><?= ViewHelper::e($p['TenDot']) ?></span>
                                </td>
                                <td>
                                    <span style="color: #cbd5e1; font-size: 13px;"><?= ViewHelper::e($p['TenGVHD']) ?></span>
                                </td>
                                <td style="min-width: 140px;">
                                    <?php if ($p['PhanTramHoanThanh'] !== null): ?>
                                        <div style="display: flex; justify-content: space-between; font-size: 12px; margin-bottom: 4px;">
                                            <strong style="color: #38bdf8;"><?= $p['PhanTramHoanThanh'] ?>%</strong>
                                            <?php if ($p['PhanTramHoanThanh'] >= 100): ?>
                                                <span style="color: #34d399; font-size: 11px; font-weight: 700;">Hoàn tất</span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="progress-track">
                                            <div class="progress-fill" style="width: <?= $p['PhanTramHoanThanh'] ?>%;"></div>
                                        </div>
                                    <?php else: ?>
                                        <span style="color: #f59e0b; font-size: 12px; display: inline-flex; align-items: center; gap: 4px;">
                                            <?= ViewHelper::icon('alert-circle', '', 14) ?> Chưa nộp
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($p['DotBaoCao'])): ?>
                                        <strong style="color: #fff; font-size: 13px;"><?= ViewHelper::e($p['DotBaoCao']) ?></strong>
                                        <div style="font-size: 11px; color: var(--text-muted); margin-top: 2px;">
                                            <?= ViewHelper::datetime($p['NgayNop']) ?>
                                        </div>
                                        <?php if (!empty($chiTietTasks)): ?>
                                            <button type="button" class="btn btn-xs btn-secondary" onclick="toggleTaskDetails(<?= $idx ?>)" style="margin-top: 4px; font-size: 11px; padding: 2px 6px;">
                                                <?= ViewHelper::icon('list', '', 12) ?> Xem <?= count($chiTietTasks) ?> mục
                                            </button>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span style="color: var(--text-muted); font-size: 12px;">-</span>
                                    <?php endif; ?>
                                </td>
                                <td style="max-width: 180px;">
                                    <?php if ($p['DanhGiaTienDo']): ?>
                                        <?= ViewHelper::statusBadge($p['DanhGiaTienDo']) ?>
                                        <?php if (!empty($p['NhanXetGVHD'])): ?>
                                            <div style="font-size: 11.5px; color: #94a3b8; margin-top: 4px;">
                                                <?= ViewHelper::e($p['NhanXetGVHD']) ?>
                                            </div>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span style="color: var(--text-muted); font-size: 12px;">Chưa đánh giá</span>
                                    <?php endif; ?>
                                </td>
                                <td style="max-width: 220px;">
                                    <?php if (!empty($p['DanhGiaCanBo'])): ?>
                                        <?= ViewHelper::statusBadge($p['DanhGiaCanBo']) ?>
                                        <?php if (!empty($p['NhanXetCanBo'])): ?>
                                            <div style="font-size: 11.5px; color: #94a3b8; margin-top: 4px;">
                                                <?= ViewHelper::e($p['NhanXetCanBo']) ?>
                                            </div>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="badge badge-secondary" style="font-size: 11px;">
                                            <span class="badge-dot dot-info"></span>Chưa duyệt
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div style="display: flex; gap: 6px; align-items: center;">
                                        <?php if (!empty($p['MaBaoCaoTD'])): ?>
                                            <button type="button" class="btn btn-sm btn-primary" onclick="openReviewModal(<?= htmlspecialchars(json_encode([
                                                'id' => $p['MaBaoCaoTD'],
                                                'topic_id' => $p['MaDeTai'],
                                                'topic_name' => $p['TenDeTai'],
                                                'round' => $p['DotBaoCao'],
                                                'percent' => $p['PhanTramHoanThanh'],
                                                'current_eval' => $p['DanhGiaCanBo'] ?? '',
                                                'current_comment' => $p['NhanXetCanBo'] ?? '',
                                                'tasks' => $chiTietTasks,
                                                'file' => $p['FileBaoCao'] ?? ''
                                            ])) ?>)" style="padding: 4px 10px; font-size: 12px; display: inline-flex; align-items: center; gap: 4px;">
                                                <?= ViewHelper::icon('check-circle', '', 13) ?> Duyệt
                                            </button>
                                        <?php endif; ?>

                                        <a href="<?= App::url('index.php?action=cb-chitiet-detai&id=' . $p['MaDeTai']) ?>" class="btn btn-sm btn-secondary" title="Xem hồ sơ chi tiết" style="padding: 4px 8px; font-size: 12px;">
                                            <?= ViewHelper::icon('eye', '', 13) ?>
                                        </a>

                                        <?php if (!empty($p['FileBaoCao'])): ?>
                                            <a href="<?= App::url('public/' . $p['FileBaoCao']) ?>" target="_blank" class="btn btn-sm btn-secondary" title="Xem file báo cáo" style="padding: 4px 8px; font-size: 12px;">
                                                <?= ViewHelper::icon('file-text', '', 13) ?>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>

                            <!-- ROW CHI TIẾT CÁC MỤC MỤC 12 BÁO CÁO (EXPANDABLE) -->
                            <?php if (!empty($chiTietTasks)): ?>
                                <tr id="task-detail-row-<?= $idx ?>" style="display: none; background: rgba(15, 23, 42, 0.4);">
                                    <td colspan="8" style="padding: 16px 24px;">
                                        <div style="background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(59, 130, 246, 0.3); border-radius: var(--radius-md); padding: 16px;">
                                            <h4 style="font-size: 13px; font-weight: 800; color: #38bdf8; margin: 0 0 10px; display: flex; align-items: center; gap: 6px;">
                                                <?= ViewHelper::icon('check-circle', '', 14) ?> Chi tiết tiến độ từng nội dung Mục 12 (<?= ViewHelper::e($p['DotBaoCao']) ?>):
                                            </h4>
                                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 12px;">
                                                <?php foreach ($chiTietTasks as $ct): 
                                                    $bColor = match($ct['muc_do'] ?? '') {
                                                        'HOAN_THANH' => '#10b981',
                                                        'SAP_HOAN_THANH' => '#38bdf8',
                                                        'HOAN_THANH_MOT_NUA' => '#f59e0b',
                                                        default => '#ef4444'
                                                    };
                                                    $bLabel = match($ct['muc_do'] ?? '') {
                                                        'HOAN_THANH' => 'Hoàn thành (100%)',
                                                        'SAP_HOAN_THANH' => 'Sắp hoàn thành (75%)',
                                                        'HOAN_THANH_MOT_NUA' => 'Hoàn thành 1/2 (50%)',
                                                        default => 'Chưa hoàn thành (0%)'
                                                    };
                                                ?>
                                                    <div style="background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(255, 255, 255, 0.08); border-left: 3px solid <?= $bColor ?>; border-radius: 6px; padding: 10px 12px;">
                                                        <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                                                            <strong style="color: #fff; font-size: 13px;">Mục <?= $ct['tt'] ?? '' ?>: <?= ViewHelper::e($ct['noi_dung'] ?? '') ?></strong>
                                                            <span style="font-size: 11px; font-weight: 700; color: <?= $bColor ?>; white-space: nowrap; margin-left: 6px;"><?= $bLabel ?></span>
                                                        </div>
                                                        <div style="font-size: 11.5px; color: var(--text-muted); margin-top: 4px;">
                                                            🎯 SP kế hoạch: <span style="color: #cbd5e1;"><?= ViewHelper::e($ct['san_pham_ke_hoach'] ?? '-') ?></span>
                                                        </div>
                                                        <?php if (!empty($ct['san_pham_nop'])): ?>
                                                            <div style="font-size: 11.5px; color: #38bdf8; margin-top: 4px; word-break: break-all;">
                                                                📦 <strong>Sản phẩm nộp:</strong> <?= ViewHelper::e($ct['san_pham_nop']) ?>
                                                            </div>
                                                        <?php endif; ?>
                                                        <?php if (!empty($ct['ghi_chu'])): ?>
                                                            <div style="font-size: 11px; color: #94a3b8; margin-top: 3px; font-style: italic;">
                                                                📝 <?= ViewHelper::e($ct['ghi_chu']) ?>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>

                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- MODAL DUYỆT TIẾN ĐỘ DÀNH CHO CÁN BỘ QUẢN LÝ -->
<div id="review-modal" style="display: none; position: fixed; inset: 0; background: rgba(0, 0, 0, 0.7); backdrop-filter: blur(4px); z-index: 9999; align-items: center; justify-content: center; padding: 16px;">
    <div style="background: #1e293b; border: 1px solid rgba(59, 130, 246, 0.4); border-radius: var(--radius-lg); width: 100%; max-width: 620px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5); overflow: hidden;">
        <div style="padding: 16px 20px; background: rgba(15, 23, 42, 0.8); border-bottom: 1px solid rgba(255, 255, 255, 0.08); display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-size: 16px; font-weight: 800; color: #fff; margin: 0; display: flex; align-items: center; gap: 8px;">
                <?= ViewHelper::icon('check-circle', '', 18) ?> Phê Duyệt Tiến Độ Đề Tài NCKH
            </h3>
            <button type="button" onclick="closeReviewModal()" style="background: transparent; border: none; color: #94a3b8; cursor: pointer; font-size: 18px;">&times;</button>
        </div>
        <form method="POST" action="<?= App::url('index.php?action=cb-duyet-tiendo') ?>" style="padding: 20px;">
            <input type="hidden" name="report_id" id="modal-report-id" value="">
            
            <div style="margin-bottom: 16px; background: rgba(15, 23, 42, 0.5); padding: 12px; border-radius: 6px; font-size: 13px;">
                <div style="color: #60a5fa; font-weight: 700; margin-bottom: 2px;" id="modal-topic-code"></div>
                <div style="color: #fff; font-weight: 700; margin-bottom: 4px;" id="modal-topic-name"></div>
                <div style="color: var(--text-muted); font-size: 12px;">
                    Đợt báo cáo: <strong style="color: #cbd5e1;" id="modal-round-name"></strong> | Tiến độ: <strong style="color: #38bdf8;" id="modal-percent-val"></strong>
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 16px;">
                <label class="form-label">Kết Luận Phê Duyệt Tiến Độ <span class="required">*</span></label>
                <select name="danh_gia_can_bo" id="modal-eval-select" class="form-control" required style="font-size: 13.5px; font-weight: 600;">
                    <option value="">-- Chọn kết luận thẩm định của Cán bộ QL --</option>
                    <option value="DAT_TIEN_DO">✅ Đạt tiến độ theo kế hoạch (Phê duyệt)</option>
                    <option value="CHAM_TIEN_DO">⏳ Chậm tiến độ, cần đẩy nhanh</option>
                    <option value="CAN_NHAC_NHO">⚠️ Chậm nghiêm trọng / Cần đôn đốc nhắc nhở</option>
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label class="form-label">Ý Kiến Chỉ Đạo & Nhận Xét Của Cán Bộ Quản Lý</label>
                <textarea name="nhan_xet_can_bo" id="modal-comment-text" class="form-control" rows="4" placeholder="Ghi nhận xét, yêu cầu bổ sung sản phẩm hoặc chỉ đạo thực hiện..."></textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" class="btn btn-secondary" onclick="closeReviewModal()">Đóng</button>
                <button type="submit" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 6px;">
                    <?= ViewHelper::icon('check', '', 16) ?> Lưu Phê Duyệt Tiến Độ
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleTaskDetails(idx) {
    const row = document.getElementById('task-detail-row-' + idx);
    if (row) {
        row.style.display = (row.style.display === 'none' || row.style.display === '') ? 'table-row' : 'none';
    }
}

function openReviewModal(data) {
    document.getElementById('modal-report-id').value = data.id;
    document.getElementById('modal-topic-code').textContent = data.topic_id;
    document.getElementById('modal-topic-name').textContent = data.topic_name;
    document.getElementById('modal-round-name').textContent = data.round || 'Báo cáo tiến độ';
    document.getElementById('modal-percent-val').textContent = (data.percent || 0) + '%';
    document.getElementById('modal-eval-select').value = data.current_eval || 'DAT_TIEN_DO';
    document.getElementById('modal-comment-text').value = data.current_comment || '';

    const modal = document.getElementById('review-modal');
    modal.style.display = 'flex';
}

function closeReviewModal() {
    document.getElementById('review-modal').style.display = 'none';
}
</script>

<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>

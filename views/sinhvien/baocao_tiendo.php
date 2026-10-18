<?php
use App\Config\App;
use App\Helpers\ViewHelper;
use App\Helpers\NckhFormHelper;

$pageTitle = "Nộp Báo Cáo Tiến Độ Định Kỳ";
require dirname(__DIR__) . '/layouts/header.php';

$thuyetMinhData = NckhFormHelper::parseThuyetMinhData($topic['DuLieuThuyetMinh'] ?? null, $topic);
$tienDoKeHoach = $thuyetMinhData['tien_do_thuc_hien'] ?? [];
if (empty($tienDoKeHoach)) {
    $tienDoKeHoach = NckhFormHelper::getEmptyThuyetMinhData($topic)['tien_do_thuc_hien'];
}
?>
<div style="max-width: 1040px; margin: 0 auto;">
    <div style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
        <div>
            <h2 style="font-size: 20px; font-weight: 800; color: #fff; margin-bottom: 4px;">
                4. Nộp Báo Cáo Tiến Độ Định Kỳ
            </h2>
            <p style="color: var(--text-secondary); font-size: 14px; margin: 0;">
                Đề tài: <strong style="color: #60a5fa;"><?= ViewHelper::e($topic['MaDeTai']) ?></strong> - <?= ViewHelper::e($topic['TenDeTai']) ?>
            </p>
        </div>
        <div style="display: flex; gap: 8px;">
            <a href="<?= App::url('index.php?action=sv-chitiet-detai&id=' . $topic['MaDeTai']) ?>" class="btn btn-secondary btn-sm">
                <?= ViewHelper::icon('eye', '', 14) ?> Xem Thuyết Minh Mẫu 01
            </a>
            <a href="<?= App::url('index.php?action=sv-dashboard') ?>" class="btn btn-secondary btn-sm">
                &larr; Về Dashboard
            </a>
        </div>
    </div>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger" style="margin-bottom: 20px;">
            <span><?= ViewHelper::icon('alert-circle', '', 18) ?></span>
            <div><?= ViewHelper::e($error) ?></div>
        </div>
    <?php endif; ?>

    <!-- FORM NỘP BÁO CÁO TIẾN ĐỘ THEO MỤC 12 THUYẾT MINH -->
    <div class="card" style="border-color: rgba(59, 130, 246, 0.4); margin-bottom: 30px;">
        <div class="card-header" style="background: rgba(30, 41, 59, 0.6);">
            <div class="card-title">
                <span><?= ViewHelper::icon('clock', '', 18) ?></span>
                <span>Nộp Báo Cáo Tiến Độ (Đánh Giá Theo Mục 12 Thuyết Minh)</span>
            </div>
            <?= ViewHelper::statusBadge($topic['TrangThai']) ?>
        </div>
        <div class="card-body">
            <form method="POST" action="<?= App::url('index.php?action=sv-baocao-tiendo&id=' . $topic['MaDeTai']) ?>" enctype="multipart/form-data">
                
                <div class="form-group" style="margin-bottom: 20px;">
                    <label class="form-label">Tên Đợt Báo Cáo <span class="required">*</span></label>
                    <input type="text" name="dotbaocao" class="form-control" placeholder="VD: Báo cáo tiến độ Giữa kỳ / Đợt 1 / Tháng 11" required>
                    <div class="form-help">Xác định tên đợt báo cáo để GVHD và Cán bộ Quản lý theo dõi mốc kiểm tra.</div>
                </div>

                <!-- KHU VỰC ĐÁNH GIÁ CHI TIẾT THEO MỤC 12 NỘI DUNG NGHIÊN CỨU VÀ TIẾN ĐỘ THỰC HIỆN -->
                <div style="background: rgba(15, 23, 42, 0.7); border: 1px solid rgba(59, 130, 246, 0.3); border-radius: var(--radius-md); padding: 20px; margin-bottom: 24px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; border-bottom: 1px solid rgba(255, 255, 255, 0.08); padding-bottom: 12px; flex-wrap: wrap; gap: 8px;">
                        <div>
                            <h3 style="font-size: 16px; font-weight: 800; color: #38bdf8; margin: 0; display: flex; align-items: center; gap: 8px;">
                                <?= ViewHelper::icon('check-circle', '', 18) ?> 12. NỘI DUNG NGHIÊN CỨU VÀ TIẾN ĐỘ THỰC HIỆN
                            </h3>
                            <p style="font-size: 12.5px; color: var(--text-secondary); margin: 4px 0 0;">
                                Đánh giá mức độ hoàn thành và nộp sản phẩm/minh chứng cho từng nội dung nghiên cứu theo Thuyết minh đề tài (Mẫu 01).
                            </p>
                        </div>
                        <span class="badge badge-info" style="font-size: 11px; padding: 4px 10px;">
                            <?= count($tienDoKeHoach) ?> nội dung nghiên cứu
                        </span>
                    </div>

                    <!-- DANH SÁCH TỪNG MỤC CÔNG VIỆC -->
                    <div style="display: flex; flex-direction: column; gap: 16px;">
                        <?php foreach ($tienDoKeHoach as $idx => $task): ?>
                            <div class="task-progress-card" style="background: rgba(30, 41, 59, 0.5); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: var(--radius-md); padding: 16px;">
                                <input type="hidden" name="task_tt[]" value="<?= $idx + 1 ?>">
                                <input type="hidden" name="task_noidung[]" value="<?= ViewHelper::e($task['noi_dung'] ?? '') ?>">
                                <input type="hidden" name="task_sanpham_kehoach[]" value="<?= ViewHelper::e($task['san_pham'] ?? '') ?>">
                                <input type="hidden" name="task_thoigian[]" value="<?= ViewHelper::e($task['thoi_gian'] ?? '') ?>">
                                <input type="hidden" name="task_nguoith[]" value="<?= ViewHelper::e($task['nguoi_thuc_hien'] ?? 'Nhóm') ?>">

                                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px; gap: 12px;">
                                    <div>
                                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                                            <span style="display: inline-block; background: #2563eb; color: #fff; font-size: 11px; font-weight: 800; padding: 2px 8px; border-radius: 4px;">
                                                Mục <?= $task['tt'] ?? ($idx + 1) ?>
                                            </span>
                                            <strong style="color: #fff; font-size: 14.5px;">
                                                <?= ViewHelper::e($task['noi_dung'] ?? '') ?>
                                            </strong>
                                        </div>
                                        <div style="display: flex; flex-wrap: wrap; gap: 14px; font-size: 12px; color: var(--text-muted); margin-top: 6px;">
                                            <span><strong>Sản phẩm kế hoạch:</strong> <span style="color: #cbd5e1;"><?= ViewHelper::e($task['san_pham'] ?? '-') ?></span></span>
                                            <span><strong>Thời gian:</strong> <span style="color: #cbd5e1;"><?= ViewHelper::e($task['thoi_gian'] ?? '-') ?></span></span>
                                            <span><strong>Thực hiện:</strong> <span style="color: #cbd5e1;"><?= ViewHelper::e($task['nguoi_thuc_hien'] ?? 'Nhóm') ?></span></span>
                                        </div>
                                    </div>
                                    <span class="task-badge" id="task-badge-<?= $idx ?>" style="font-size: 12px; font-weight: 700; padding: 4px 10px; border-radius: 4px; background: rgba(148, 163, 184, 0.15); color: #94a3b8; white-space: nowrap;">
                                        Chưa hoàn thành (0%)
                                    </span>
                                </div>

                                <div class="grid-cols-2" style="gap: 12px; margin-top: 12px; border-top: 1px dashed rgba(255, 255, 255, 0.08); padding-top: 12px;">
                                    <div>
                                        <label class="form-label" style="font-size: 12px; margin-bottom: 4px;">
                                            Mức độ hoàn thành <span class="required">*</span>
                                        </label>
                                        <select name="task_mucdo[]" class="form-control task-status-select" data-idx="<?= $idx ?>" onchange="calculateOverallProgress()" required style="font-size: 13px; font-weight: 600;">
                                            <option value="CHUA_HOAN_THANH" data-percent="0">❌ Chưa hoàn thành (0%)</option>
                                            <option value="HOAN_THANH_MOT_NUA" data-percent="50">⚡ Đã hoàn thành 1 nửa (50%)</option>
                                            <option value="SAP_HOAN_THANH" data-percent="75">⏳ Sắp hoàn thành (75%)</option>
                                            <option value="HOAN_THANH" data-percent="100">✅ Hoàn thành (100%)</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="form-label" style="font-size: 12px; margin-bottom: 4px;">
                                            Nộp sản phẩm / Minh chứng kết quả
                                        </label>
                                        <input type="text" name="task_sanpham_nop[]" class="form-control" placeholder="Link GitHub, Google Drive, link tài liệu hoặc mô tả sản phẩm nộp..." style="font-size: 13px;">
                                    </div>
                                </div>

                                <div style="margin-top: 8px;">
                                    <input type="text" name="task_ghichu[]" class="form-control" placeholder="Ghi chú thêm về tiến độ thực hiện mục này (nếu có)..." style="font-size: 12px; padding: 6px 10px;">
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- WIDGET HIỂN THỊ TIẾN ĐỘ TỔNG THỂ TỰ ĐỘNG TÍNH TOÁN -->
                    <div style="background: rgba(15, 23, 42, 0.9); border: 1px solid rgba(56, 189, 248, 0.4); border-radius: var(--radius-md); padding: 18px; margin-top: 20px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                            <div>
                                <span style="font-size: 13.5px; font-weight: 800; color: #fff; text-transform: uppercase; letter-spacing: 0.5px;">
                                    Tiến độ tổng thể (Tự động tính từ Mục 12):
                                </span>
                                <span id="progress-calc-detail" style="font-size: 12px; color: var(--text-muted); margin-left: 8px;">
                                    (Trung bình cộng từ <?= count($tienDoKeHoach) ?> nội dung nghiên cứu)
                                </span>
                            </div>
                            <div style="font-size: 26px; font-weight: 900; color: #38bdf8;" id="overall-percent-text">
                                0%
                            </div>
                        </div>
                        <div class="progress-track" style="height: 14px; background: rgba(255, 255, 255, 0.08); border-radius: 8px; overflow: hidden;">
                            <div id="overall-progress-bar" class="progress-fill" style="width: 0%; height: 100%; transition: width 0.4s ease; background: linear-gradient(90deg, #38bdf8, #10b981);"></div>
                        </div>
                        <input type="hidden" name="phantram" id="overall-percent-input" value="0">
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 16px;">
                    <label class="form-label">Tóm Tắt Công Việc Chung Đã Thực Hiện</label>
                    <textarea name="congviec" class="form-control" rows="3" placeholder="Hệ thống sẽ tự động tổng hợp từ các mục trên, hoặc bạn có thể bổ sung thêm mô tả tổng quan tại đây..."></textarea>
                </div>

                <div class="form-group" style="margin-bottom: 16px;">
                    <label class="form-label">Khó Khăn & Vướng Mắc Cần Hỗ Trợ (Nếu có)</label>
                    <textarea name="khokhan" class="form-control" rows="2" placeholder="Các vướng mắc về kỹ thuật, thiết bị, phòng thí nghiệm cần GVHD hoặc Cán bộ Quản lý hỗ trợ..."></textarea>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label class="form-label">Đính Kèm File Báo Cáo Tiến Độ (PDF / Word) <span style="color: var(--text-muted); font-size: 12px;">(Không bắt buộc nếu đã nộp link sản phẩm)</span></label>
                    <input type="file" name="file_baocao" class="form-control" accept=".pdf,.doc,.docx">
                    <div class="form-help">Đính kèm báo cáo tiến độ chi tiết có chữ ký nhóm sinh viên nếu có.</div>
                </div>

                <div style="display: flex; justify-content: flex-end;">
                    <button type="submit" class="btn btn-primary btn-lg" style="display: inline-flex; align-items: center; gap: 8px; padding: 12px 28px; font-weight: 700;">
                        <?= ViewHelper::icon('send', '', 18) ?> Gửi Báo Cáo Tiến Độ Cho GVHD & Cán Bộ QL
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- LỊCH SỬ CÁC BÁO CÁO TIẾN ĐỘ ĐÃ NỘP -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <span><?= ViewHelper::icon('file-text', '', 18) ?></span>
                <span>Lịch Sử Các Báo Cáo Tiến Độ Đã Nộp</span>
            </div>
            <span class="badge badge-secondary"><?= count($reports) ?> đợt</span>
        </div>
        <div class="card-body" style="padding: 0;">
            <?php if (empty($reports)): ?>
                <div style="text-align: center; padding: 36px; color: var(--text-muted);">
                    Nhóm chưa nộp báo cáo tiến độ nào cho đề tài này.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Đợt Báo Cáo</th>
                                <th>Tiến Độ (%)</th>
                                <th>Chi Tiết Mục 12 & Sản Phẩm</th>
                                <th>Đánh Giá GVHD</th>
                                <th>Thẩm Định Cán Bộ QL</th>
                                <th>Ngày Nộp</th>
                                <th>File</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reports as $r): 
                                $chiTietTasks = [];
                                if (!empty($r['ChiTietTienDo'])) {
                                    $decoded = json_decode($r['ChiTietTienDo'], true);
                                    if (is_array($decoded)) {
                                        $chiTietTasks = $decoded;
                                    }
                                }
                            ?>
                                <tr>
                                    <td>
                                        <strong style="color: #fff;"><?= ViewHelper::e($r['DotBaoCao']) ?></strong>
                                    </td>
                                    <td style="min-width: 130px;">
                                        <div style="display: flex; justify-content: space-between; font-size: 12px; margin-bottom: 4px;">
                                            <strong style="color: #38bdf8;"><?= $r['PhanTramHoanThanh'] ?>%</strong>
                                        </div>
                                        <div class="progress-track">
                                            <div class="progress-fill" style="width: <?= $r['PhanTramHoanThanh'] ?>%;"></div>
                                        </div>
                                    </td>
                                    <td style="max-width: 280px;">
                                        <?php if (!empty($chiTietTasks)): ?>
                                            <div style="font-size: 12px; display: flex; flex-direction: column; gap: 4px;">
                                                <?php foreach ($chiTietTasks as $ct): 
                                                    $badgeColor = match($ct['muc_do'] ?? '') {
                                                        'HOAN_THANH' => '#10b981',
                                                        'SAP_HOAN_THANH' => '#38bdf8',
                                                        'HOAN_THANH_MOT_NUA' => '#f59e0b',
                                                        default => '#ef4444'
                                                    };
                                                    $label = match($ct['muc_do'] ?? '') {
                                                        'HOAN_THANH' => 'Hoàn thành (100%)',
                                                        'SAP_HOAN_THANH' => 'Sắp hoàn thành (75%)',
                                                        'HOAN_THANH_MOT_NUA' => 'Hoàn thành 1/2 (50%)',
                                                        default => 'Chưa hoàn thành (0%)'
                                                    };
                                                ?>
                                                    <div style="background: rgba(15, 23, 42, 0.4); padding: 4px 8px; border-radius: 4px; border-left: 2px solid <?= $badgeColor ?>;">
                                                        <span style="font-weight: 700; color: #fff;">Mục <?= $ct['tt'] ?? '' ?>:</span> 
                                                        <span style="color: <?= $badgeColor ?>; font-weight: 600;"><?= $label ?></span>
                                                        <?php if (!empty($ct['san_pham_nop'])): ?>
                                                            <div style="font-size: 11px; color: #94a3b8; word-break: break-all; margin-top: 2px;">
                                                                📦 SP: <?= ViewHelper::e($ct['san_pham_nop']) ?>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php else: ?>
                                            <div style="font-size: 13px; color: var(--text-secondary);"><?= nl2br(ViewHelper::e($r['CongViecDaLam'])) ?></div>
                                        <?php endif; ?>

                                        <?php if (!empty($r['KhoKhanVuongMac'])): ?>
                                            <div style="font-size: 11.5px; color: #f59e0b; margin-top: 6px; display: flex; align-items: center; gap: 4px;">
                                                <?= ViewHelper::icon('alert-triangle', '', 12) ?> Vướng mắc: <?= ViewHelper::e($r['KhoKhanVuongMac']) ?>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td style="max-width: 170px;">
                                        <?php if ($r['DanhGiaTienDo']): ?>
                                            <?= ViewHelper::statusBadge($r['DanhGiaTienDo']) ?>
                                            <?php if (!empty($r['NhanXetGVHD'])): ?>
                                                <div style="font-size: 11.5px; color: #94a3b8; margin-top: 4px;">
                                                    <?= ViewHelper::e($r['NhanXetGVHD']) ?>
                                                </div>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="badge badge-secondary"><span class="badge-dot dot-warning"></span>Chờ GVHD xem</span>
                                        <?php endif; ?>
                                    </td>
                                    <td style="max-width: 170px;">
                                        <?php if (!empty($r['DanhGiaCanBo'])): ?>
                                            <?= ViewHelper::statusBadge($r['DanhGiaCanBo']) ?>
                                            <?php if (!empty($r['NhanXetCanBo'])): ?>
                                                <div style="font-size: 11.5px; color: #94a3b8; margin-top: 4px;">
                                                    <?= ViewHelper::e($r['NhanXetCanBo']) ?>
                                                </div>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="badge badge-secondary"><span class="badge-dot dot-info"></span>Chờ Cán bộ QL</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= ViewHelper::datetime($r['NgayNop']) ?></td>
                                    <td>
                                        <?php if (!empty($r['FileBaoCao'])): ?>
                                            <a href="<?= App::url('public/' . $r['FileBaoCao']) ?>" target="_blank" class="btn btn-sm btn-secondary" title="Xem file" style="display: inline-flex; align-items: center; gap: 4px;">
                                                <?= ViewHelper::icon('file-text', '', 13) ?> File
                                            </a>
                                        <?php else: ?>
                                            <span style="color: var(--text-muted); font-size: 12px;">Link trực tiếp</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function calculateOverallProgress() {
    const selects = document.querySelectorAll('.task-status-select');
    if (!selects.length) return;
    
    let totalPoints = 0;
    selects.forEach(sel => {
        const idx = sel.getAttribute('data-idx');
        const opt = sel.options[sel.selectedIndex];
        const val = parseInt(opt.getAttribute('data-percent') || '0', 10);
        totalPoints += val;

        const badge = document.getElementById('task-badge-' + idx);
        if (badge) {
            badge.textContent = opt.textContent.replace(/^[^\w\s\u00C0-\u1EF9]+/, '').trim();
            if (val === 100) {
                badge.style.background = 'rgba(16, 185, 129, 0.15)';
                badge.style.color = '#34d399';
            } else if (val === 75) {
                badge.style.background = 'rgba(56, 189, 248, 0.15)';
                badge.style.color = '#38bdf8';
            } else if (val === 50) {
                badge.style.background = 'rgba(245, 158, 11, 0.15)';
                badge.style.color = '#fbbf24';
            } else {
                badge.style.background = 'rgba(239, 68, 68, 0.15)';
                badge.style.color = '#f87171';
            }
        }
    });

    const avg = Math.round(totalPoints / selects.length);
    const overallText = document.getElementById('overall-percent-text');
    const overallBar = document.getElementById('overall-progress-bar');
    const overallInput = document.getElementById('overall-percent-input');
    
    if (overallText) overallText.textContent = avg + '%';
    if (overallBar) overallBar.style.width = avg + '%';
    if (overallInput) overallInput.value = avg;
}

document.addEventListener('DOMContentLoaded', calculateOverallProgress);
</script>

<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>

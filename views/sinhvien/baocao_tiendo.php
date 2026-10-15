<?php
use App\Config\App;
use App\Helpers\ViewHelper;

$pageTitle = "Nộp Báo Cáo Tiến Độ Định Kỳ";
require dirname(__DIR__) . '/layouts/header.php';
?>
<div style="max-width: 960px; margin: 0 auto;">
    <div style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h2 style="font-size: 20px; font-weight: 800; color: #fff; margin-bottom: 4px;">
                4. Nộp Báo Cáo Tiến Độ Định Kỳ
            </h2>
            <p style="color: var(--text-secondary); font-size: 14px; margin: 0;">
                Đề tài: <strong style="color: #60a5fa;"><?= ViewHelper::e($topic['MaDeTai']) ?></strong> - <?= ViewHelper::e($topic['TenDeTai']) ?>
            </p>
        </div>
        <a href="<?= App::url('index.php?action=sv-dashboard') ?>" class="btn btn-secondary btn-sm">
            &larr; Về Dashboard
        </a>
    </div>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger">
            <span><?= ViewHelper::icon('alert-circle', '', 18) ?></span>
            <div><?= ViewHelper::e($error) ?></div>
        </div>
    <?php endif; ?>

    <!-- FORM NỘP BÁO CÁO TIẾN ĐỘ MỚI -->
    <div class="card" style="border-color: rgba(59, 130, 246, 0.4);">
        <div class="card-header">
            <div class="card-title">
                <span><?= ViewHelper::icon('clock', '', 18) ?></span>
                <span>Nộp Báo Cáo Tiến Độ Mới</span>
            </div>
            <?= ViewHelper::statusBadge($topic['TrangThai']) ?>
        </div>
        <div class="card-body">
            <form method="POST" action="<?= App::url('index.php?action=sv-baocao-tiendo&id=' . $topic['MaDeTai']) ?>" enctype="multipart/form-data">
                <div class="grid-cols-2">
                    <div class="form-group">
                        <label class="form-label">Tên Đợt Báo Cáo <span class="required">*</span></label>
                        <input type="text" name="dotbaocao" class="form-control" placeholder="VD: Báo cáo tiến độ Giữa kỳ / Đợt 1 / Tháng 11" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Khối Lượng Hoàn Thành Ước Lượng (%) <span class="required">*</span></label>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <input type="range" name="phantram" id="progress-slider" min="0" max="100" value="50" style="flex: 1;" oninput="document.getElementById('slider-val').textContent = this.value + '%'">
                            <span id="slider-val" style="font-weight: 800; font-size: 18px; color: #38bdf8; min-width: 50px;">50%</span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Chi Tiết Công Việc Đã Hoàn Thành <span class="required">*</span></label>
                    <textarea name="congviec" class="form-control" rows="4" placeholder="Liệt kê cụ thể các đầu việc, chức năng, tài liệu hoặc thực nghiệm đã hoàn tất trong giai đoạn vừa qua..." required></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Khó Khăn & Vướng Mắc Cần Hỗ Trợ (Nếu có)</label>
                    <textarea name="khokhan" class="form-control" rows="3" placeholder="Các vướng mắc về kỹ thuật, trang thiết bị, phòng lab hoặc cần trao đổi thêm với GVHD..."></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">File Báo Cáo Tiến Độ (PDF / Word) <span class="required">*</span></label>
                    <input type="file" name="file_baocao" class="form-control" accept=".pdf,.doc,.docx" required>
                    <div class="form-help">Đính kèm báo cáo tiến độ chi tiết có chữ ký của nhóm sinh viên.</div>
                </div>

                <div style="display: flex; justify-content: flex-end; margin-top: 16px;">
                    <button type="submit" class="btn btn-primary btn-lg" style="display: inline-flex; align-items: center; gap: 8px;">
                        <?= ViewHelper::icon('send', '', 18) ?> Gửi Báo Cáo Tiến Độ Cho GVHD
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- DANH SÁCH BÁO CÁO ĐÃ NỘP -->
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
                                <th>Nội Dung Hoàn Thành</th>
                                <th>Đánh Giá Của GVHD</th>
                                <th>Ngày Nộp</th>
                                <th>File</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reports as $r): ?>
                                <tr>
                                    <td>
                                        <strong style="color: #fff;"><?= ViewHelper::e($r['DotBaoCao']) ?></strong>
                                    </td>
                                    <td style="min-width: 140px;">
                                        <div style="display: flex; justify-content: space-between; font-size: 12px; margin-bottom: 4px;">
                                            <span><?= $r['PhanTramHoanThanh'] ?>%</span>
                                        </div>
                                        <div class="progress-track">
                                            <div class="progress-fill" style="width: <?= $r['PhanTramHoanThanh'] ?>%;"></div>
                                        </div>
                                    </td>
                                    <td style="max-width: 250px;">
                                        <div style="font-size: 13px; color: var(--text-secondary);"><?= nl2br(ViewHelper::e($r['CongViecDaLam'])) ?></div>
                                        <?php if (!empty($r['KhoKhanVuongMac'])): ?>
                                            <div style="font-size: 12px; color: #f59e0b; margin-top: 4px; display: flex; align-items: center; gap: 4px;">
                                                <?= ViewHelper::icon('alert-triangle', '', 12) ?> Vướng mắc: <?= ViewHelper::e($r['KhoKhanVuongMac']) ?>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td style="max-width: 220px;">
                                        <?php if ($r['DanhGiaTienDo']): ?>
                                            <?= ViewHelper::statusBadge($r['DanhGiaTienDo']) ?>
                                            <?php if (!empty($r['NhanXetGVHD'])): ?>
                                                <div style="font-size: 12px; color: #94a3b8; margin-top: 4px;">
                                                    <?= ViewHelper::e($r['NhanXetGVHD']) ?>
                                                </div>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="badge badge-secondary"><span class="badge-dot dot-warning"></span>Chờ GVHD xem</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= ViewHelper::datetime($r['NgayNop']) ?></td>
                                    <td>
                                        <a href="<?= App::url('public/' . $r['FileBaoCao']) ?>" target="_blank" class="btn btn-sm btn-secondary" title="Xem file" style="display: inline-flex; align-items: center; gap: 4px;">
                                            <?= ViewHelper::icon('file-text', '', 13) ?> File
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
</div>
<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>

<?php
use App\Config\App;
use App\Helpers\ViewHelper;

$pageTitle = "Chi Tiết Đề Tài - " . $topic['MaDeTai'];
require dirname(__DIR__) . '/layouts/header.php';
?>
<div style="max-width: 1040px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <div>
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 6px;">
                <h2 style="font-size: 22px; font-weight: 800; color: #fff; margin: 0;">
                    <?= ViewHelper::e($topic['TenDeTai']) ?>
                </h2>
                <?= ViewHelper::statusBadge($topic['TrangThai']) ?>
            </div>
            <div style="font-size: 13px; color: var(--text-secondary);">
                Mã đề tài: <strong style="color: #60a5fa;"><?= ViewHelper::e($topic['MaDeTai']) ?></strong> | 
                Đợt: <strong><?= ViewHelper::e($topic['TenDot']) ?></strong> | 
                Hướng NC: <strong><?= ViewHelper::e($topic['TenHuongNC']) ?></strong> | 
                Kinh phí: <strong style="color: #38bdf8;"><?= ViewHelper::money($topic['KinhPhiDuKien']) ?></strong>
            </div>
        </div>
        <a href="<?= App::url('index.php?action=gv-dashboard') ?>" class="btn btn-secondary">
            &larr; Về Dashboard
        </a>
    </div>

    <div class="tabs-wrapper">
        <div class="tabs-nav">
            <div class="tab-btn active" data-tab="tab-overview" style="display: inline-flex; align-items: center; gap: 6px;"><?= ViewHelper::icon('file-text', '', 14) ?> Thông Tin Hồ Sơ</div>
            <div class="tab-btn" data-tab="tab-members" style="display: inline-flex; align-items: center; gap: 6px;"><?= ViewHelper::icon('users', '', 14) ?> Nhóm Sinh Viên (<?= count($members) ?>)</div>
            <div class="tab-btn" data-tab="tab-progress" style="display: inline-flex; align-items: center; gap: 6px;"><?= ViewHelper::icon('clock', '', 14) ?> Tiến Độ (<?= count($progressReports) ?>)</div>
            <div class="tab-btn" data-tab="tab-final" style="display: inline-flex; align-items: center; gap: 6px;"><?= ViewHelper::icon('folder', '', 14) ?> Báo Cáo Tổng Kết & SP</div>
            <div class="tab-btn" data-tab="tab-history" style="display: inline-flex; align-items: center; gap: 6px;"><?= ViewHelper::icon('refresh', '', 14) ?> Lịch Sử Sửa Đổi (<?= count($editHistory) ?>)</div>
        </div>

        <div id="tab-overview" class="tab-content">
            <div class="grid-cols-2">
                <div class="card">
                    <div class="card-header"><div class="card-title">Mục Tiêu & Nội Dung Nghiên Cứu</div></div>
                    <div class="card-body">
                        <div style="margin-bottom: 14px;">
                            <strong style="color: #60a5fa; font-size: 13px; display: block; margin-bottom: 4px;">Mục tiêu đề tài:</strong>
                            <div style="color: #e2e8f0; font-size: 14px;"><?= nl2br(ViewHelper::e($topic['MucTieu'])) ?></div>
                        </div>
                        <div style="margin-bottom: 14px;">
                            <strong style="color: #60a5fa; font-size: 13px; display: block; margin-bottom: 4px;">Nội dung nghiên cứu:</strong>
                            <div style="color: #e2e8f0; font-size: 14px;"><?= nl2br(ViewHelper::e($topic['NoiDungNC'])) ?></div>
                        </div>
                        <div>
                            <strong style="color: #60a5fa; font-size: 13px; display: block; margin-bottom: 4px;">Sản phẩm dự kiến:</strong>
                            <div style="color: #e2e8f0; font-size: 14px;"><?= nl2br(ViewHelper::e($topic['SanPhamDuKien'])) ?></div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header"><div class="card-title">Hồ Sơ Đăng Ký Đính Kèm</div></div>
                    <div class="card-body">
                        <div style="display: flex; flex-direction: column; gap: 10px; margin-bottom: 20px;">
                            <a href="<?= App::url('public/' . $topic['FilePhieuThuyetMinh']) ?>" target="_blank" class="btn btn-secondary" style="justify-content: flex-start;">
                                <?= ViewHelper::icon('file-text', '', 15) ?> Phiếu Thuyết Minh Đề Tài (Mẫu 01)
                            </a>
                            <a href="<?= App::url('public/' . $topic['FilePhieuDeXuat']) ?>" target="_blank" class="btn btn-secondary" style="justify-content: flex-start;">
                                <?= ViewHelper::icon('file-text', '', 15) ?> Phiếu Đề Xuất Đề Tài (Mẫu 02)
                            </a>
                        </div>

                        <?php if (!empty($topic['NhanXetGVHD'])): ?>
                            <div style="background: rgba(15, 23, 42, 0.6); padding: 12px; border-radius: var(--radius-sm); margin-bottom: 10px;">
                                <span style="color: #60a5fa; font-size: 12px; font-weight: 700;">Nhận xét của bạn khi duyệt:</span>
                                <div style="color: #cbd5e1; font-size: 13px; margin-top: 4px;"><?= nl2br(ViewHelper::e($topic['NhanXetGVHD'])) ?></div>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($topic['NhanXetCanBo'])): ?>
                            <div style="background: rgba(15, 23, 42, 0.6); padding: 12px; border-radius: var(--radius-sm);">
                                <span style="color: #f59e0b; font-size: 12px; font-weight: 700;">Ý kiến của Cán bộ thẩm định:</span>
                                <div style="color: #cbd5e1; font-size: 13px; margin-top: 4px;"><?= nl2br(ViewHelper::e($topic['NhanXetCanBo'])) ?></div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div id="tab-members" class="tab-content" style="display: none;">
            <div class="card">
                <div class="card-body" style="padding: 0;">
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Mã SV</th><th>Họ và Tên</th><th>Vai Trò</th><th>Lớp - Khoa</th><th>Nhiệm Vụ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($members as $m): ?>
                                    <tr>
                                        <td><strong><?= ViewHelper::e($m['MaSV']) ?></strong></td>
                                        <td>
                                            <strong style="color: #fff;"><?= ViewHelper::e($m['HoTen']) ?></strong>
                                            <div style="font-size: 12px; color: var(--text-muted);"><?= ViewHelper::e($m['Email']) ?> | <?= ViewHelper::e($m['SoDienThoai'] ?: 'Chưa có SĐT') ?></div>
                                        </td>
                                        <td>
                                            <span class="badge <?= $m['VaiTro'] === 'TRUONG_NHOM' ? 'badge-amber' : 'badge-secondary' ?>">
                                                <?= $m['VaiTro'] === 'TRUONG_NHOM' ? 'Trưởng Nhóm' : 'Thành Viên' ?>
                                            </span>
                                        </td>
                                        <td><?= ViewHelper::e($m['Lop']) ?> (<?= ViewHelper::e($m['Khoa']) ?>)</td>
                                        <td><?= ViewHelper::e($m['NhiemVuCuThe']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div id="tab-progress" class="tab-content" style="display: none;">
            <div class="card">
                <div class="card-body" style="padding: 0;">
                    <?php if (empty($progressReports)): ?>
                        <div style="padding: 32px; text-align: center; color: var(--text-muted);">Chưa có báo cáo tiến độ nào.</div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Đợt Báo Cáo</th><th>Tiến Độ (%)</th><th>Nội Dung Hoàn Thành</th><th>Đánh Giá Của Bạn</th><th>Ngày Nộp</th><th>File</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($progressReports as $r): ?>
                                        <tr>
                                            <td><strong><?= ViewHelper::e($r['DotBaoCao']) ?></strong></td>
                                            <td><strong><?= $r['PhanTramHoanThanh'] ?>%</strong></td>
                                            <td style="max-width: 250px;"><?= nl2br(ViewHelper::e($r['CongViecDaLam'])) ?></td>
                                            <td>
                                                <?= $r['DanhGiaTienDo'] ? ViewHelper::statusBadge($r['DanhGiaTienDo']) : 'Chưa đánh giá' ?>
                                                <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;"><?= ViewHelper::e($r['NhanXetGVHD']) ?></div>
                                            </td>
                                            <td><?= ViewHelper::datetime($r['NgayNop']) ?></td>
                                            <td><a href="<?= App::url('public/' . $r['FileBaoCao']) ?>" target="_blank" class="btn btn-sm btn-secondary">Xem file</a></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div id="tab-final" class="tab-content" style="display: none;">
            <?php if (!$finalReport): ?>
                <div class="card" style="text-align: center; padding: 36px; color: var(--text-muted);">Nhóm chưa nộp Báo cáo tổng kết.</div>
            <?php else: ?>
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Báo Cáo Tổng Kết & Đánh Giá Sơ Bộ</div>
                        <?= ViewHelper::statusBadge($finalReport['DanhGiaSoBoGVHD']) ?>
                    </div>
                    <div class="card-body">
                        <div style="margin-bottom: 14px;">
                            <strong style="color: #60a5fa; font-size: 13px; display: block; margin-bottom: 4px;">Tóm tắt kết quả:</strong>
                            <div style="color: #e2e8f0; font-size: 14px;"><?= nl2br(ViewHelper::e($finalReport['TomTatKetQua'])) ?></div>
                        </div>
                        <a href="<?= App::url('public/' . $finalReport['FileBaoCaoTongKet']) ?>" target="_blank" class="btn btn-primary btn-sm">
                            <?= ViewHelper::icon('download', '', 14) ?> Tải Toàn Văn Báo Cáo Tổng Kết
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div id="tab-history" class="tab-content" style="display: none;">
            <div class="card">
                <div class="card-body">
                    <?php if (empty($editHistory)): ?>
                        <div style="text-align: center; padding: 24px; color: var(--text-muted);">Chưa có lần chỉnh sửa nào.</div>
                    <?php else: ?>
                        <div class="timeline">
                            <?php foreach ($editHistory as $ls): ?>
                                <div class="timeline-step">
                                    <div class="timeline-dot done"></div>
                                    <div class="timeline-content">
                                        <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
                                            <strong style="color: #60a5fa;">Lần sửa #<?= $ls['LanChinhSua'] ?></strong>
                                            <span style="font-size: 12px; color: var(--text-muted);"><?= ViewHelper::datetime($ls['NgaySua']) ?></span>
                                        </div>
                                        <p style="font-size: 13px; color: #cbd5e1;"><?= nl2br(ViewHelper::e($ls['NoiDungSua'])) ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>

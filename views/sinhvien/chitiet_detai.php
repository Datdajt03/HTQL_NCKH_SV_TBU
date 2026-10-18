<?php
use App\Config\App;
use App\Helpers\ViewHelper;
use App\Helpers\NckhFormHelper;

$parsedDeXuat = NckhFormHelper::parseDeXuatData($topic['DuLieuDeXuat'] ?? null, $topic, $members);
$parsedThuyetMinh = NckhFormHelper::parseThuyetMinhData($topic['DuLieuThuyetMinh'] ?? null, $topic, $members);

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
                Mã số: <strong style="color: #60a5fa;"><?= ViewHelper::e($topic['MaDeTai']) ?></strong> | 
                Đợt: <strong><?= ViewHelper::e($topic['TenDot']) ?></strong> | 
                Hướng NC: <strong><?= ViewHelper::e($topic['TenHuongNC']) ?></strong> | 
                Ngày nộp: <strong><?= ViewHelper::datetime($topic['NgayDangKy']) ?></strong>
            </div>
        </div>
        <div style="display: flex; gap: 8px;" class="no-print">
            <button type="button" onclick="window.print()" class="btn btn-secondary" style="display: inline-flex; align-items: center; gap: 6px;">
                <?= ViewHelper::icon('printer', '', 14) ?> In / Xuất A4
            </button>
            <?php if ($topic['TrangThai'] === 'YEU_CAU_CHINH_SUA'): ?>
                <a href="<?= App::url('index.php?action=sv-suahoso-detai&id=' . $topic['MaDeTai']) ?>" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 6px;">
                    <?= ViewHelper::icon('edit', '', 14) ?> Sửa Hồ Sơ
                </a>
            <?php endif; ?>
            <a href="<?= App::url('index.php?action=sv-dashboard') ?>" class="btn btn-secondary">
                &larr; Về Dashboard
            </a>
        </div>
    </div>

    <!-- TABS NAVIGATION -->
    <div class="tabs-wrapper">
        <div class="tabs-nav no-print">
            <div class="tab-btn active" data-tab="tab-overview" style="display: inline-flex; align-items: center; gap: 6px;">
                <?= ViewHelper::icon('file-text', '', 14) ?> Thông Tin Chung
            </div>
            <div class="tab-btn" data-tab="tab-doc-dexuat" style="display: inline-flex; align-items: center; gap: 6px;">
                <?= ViewHelper::icon('file-text', '', 14) ?> Phiếu Đề Xuất (Mẫu 02)
            </div>
            <div class="tab-btn" data-tab="tab-doc-thuyetminh" style="display: inline-flex; align-items: center; gap: 6px;">
                <?= ViewHelper::icon('file-text', '', 14) ?> Thuyết Minh Đề Tài (Mẫu 01)
            </div>
            <div class="tab-btn" data-tab="tab-members" style="display: inline-flex; align-items: center; gap: 6px;">
                <?= ViewHelper::icon('users', '', 14) ?> Nhóm Nghiên Cứu (<?= count($members) ?>)
            </div>
            <div class="tab-btn" data-tab="tab-progress" style="display: inline-flex; align-items: center; gap: 6px;">
                <?= ViewHelper::icon('clock', '', 14) ?> Tiến Độ (<?= count($progressReports) ?>)
            </div>
            <div class="tab-btn" data-tab="tab-final" style="display: inline-flex; align-items: center; gap: 6px;">
                <?= ViewHelper::icon('folder', '', 14) ?> Báo Cáo Tổng Kết & SP
            </div>
            <div class="tab-btn" data-tab="tab-history" style="display: inline-flex; align-items: center; gap: 6px;">
                <?= ViewHelper::icon('refresh', '', 14) ?> Lịch Sử Sửa Đổi (<?= count($editHistory) ?>)
            </div>
        </div>

        <!-- TAB 1: THÔNG TIN CHUNG -->
        <div id="tab-overview" class="tab-content">
            <div class="grid-cols-2">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Mục Tiêu & Nội Dung Nghiên Cứu</div>
                    </div>
                    <div class="card-body">
                        <div style="margin-bottom: 16px;">
                            <strong style="color: #60a5fa; font-size: 13px; display: block; margin-bottom: 4px;">Mục tiêu đề tài:</strong>
                            <div style="color: #e2e8f0; font-size: 14px; line-height: 1.6;"><?= nl2br(ViewHelper::e($topic['MucTieu'])) ?></div>
                        </div>
                        <div style="margin-bottom: 16px;">
                            <strong style="color: #60a5fa; font-size: 13px; display: block; margin-bottom: 4px;">Nội dung nghiên cứu chính:</strong>
                            <div style="color: #e2e8f0; font-size: 14px; line-height: 1.6;"><?= nl2br(ViewHelper::e($topic['NoiDungNC'])) ?></div>
                        </div>
                        <div>
                            <strong style="color: #60a5fa; font-size: 13px; display: block; margin-bottom: 4px;">Sản phẩm dự kiến:</strong>
                            <div style="color: #e2e8f0; font-size: 14px; line-height: 1.6;"><?= nl2br(ViewHelper::e($topic['SanPhamDuKien'])) ?></div>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Giảng Viên Hướng Dẫn</div>
                        </div>
                        <div class="card-body">
                            <div style="font-size: 16px; font-weight: 700; color: #fff; margin-bottom: 4px;">
                                <?= ViewHelper::e(($topic['HocHam'] ? $topic['HocHam'] . ' ' : '') . $topic['HocVi'] . ' ' . $topic['TenGVHD']) ?>
                            </div>
                            <div style="font-size: 13px; color: var(--text-secondary); margin-bottom: 12px;">
                                Đơn vị: <?= ViewHelper::e($topic['DonViCongTac']) ?>
                            </div>
                            <div style="font-size: 13px; color: var(--text-muted); margin-bottom: 4px;">
                                Email: <strong style="color: #cbd5e1;"><?= ViewHelper::e($topic['EmailGVHD']) ?></strong>
                            </div>
                            <div style="font-size: 13px; color: var(--text-muted);">
                                SĐT: <strong style="color: #cbd5e1;"><?= ViewHelper::e($topic['SdtGVHD'] ?: 'Chưa cập nhật') ?></strong>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Hồ Sơ Đề Tài Trực Tuyến & Đính Kèm</div>
                        </div>
                        <div class="card-body">
                            <div style="display: flex; flex-direction: column; gap: 10px;">
                                <button type="button" class="btn btn-primary" onclick="document.querySelector('.tab-btn[data-tab=\'tab-doc-dexuat\']').click()" style="justify-content: flex-start; display: inline-flex; align-items: center; gap: 8px;">
                                    <?= ViewHelper::icon('file-text', '', 15) ?> Xem Phiếu Đề Xuất (Mẫu 02 Trực Tuyến)
                                </button>
                                <button type="button" class="btn btn-primary" onclick="document.querySelector('.tab-btn[data-tab=\'tab-doc-thuyetminh\']').click()" style="justify-content: flex-start; display: inline-flex; align-items: center; gap: 8px;">
                                    <?= ViewHelper::icon('file-text', '', 15) ?> Xem Thuyết Minh Đề Tài (Mẫu 01 Trực Tuyến)
                                </button>
                                <?php if (!empty($topic['FilePhieuThuyetMinh'])): ?>
                                    <a href="<?= App::url('public/' . $topic['FilePhieuThuyetMinh']) ?>" target="_blank" class="btn btn-secondary" style="justify-content: flex-start; display: inline-flex; align-items: center; gap: 8px;">
                                        <?= ViewHelper::icon('download', '', 15) ?> Tải Tệp Thuyết Minh Đã Đính Kèm
                                    </a>
                                <?php endif; ?>
                                <?php if (!empty($topic['FilePhieuDeXuat'])): ?>
                                    <a href="<?= App::url('public/' . $topic['FilePhieuDeXuat']) ?>" target="_blank" class="btn btn-secondary" style="justify-content: flex-start; display: inline-flex; align-items: center; gap: 8px;">
                                        <?= ViewHelper::icon('download', '', 15) ?> Tải Tệp Đề Xuất Đã Đính Kèm
                                    </a>
                                <?php endif; ?>
                                <div style="font-size: 13px; color: var(--text-muted); margin-top: 6px;">
                                    Kinh phí dự trù: <strong style="color: #38bdf8;"><?= ViewHelper::money($topic['KinhPhiDuKien']) ?></strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB 2: NHÓM NGHIÊN CỨU -->
        <div id="tab-members" class="tab-content" style="display: none;">
            <div class="card">
                <div class="card-body" style="padding: 0;">
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Mã SV</th>
                                    <th>Họ và Tên</th>
                                    <th>Vai Trò</th>
                                    <th>Lớp - Khoa</th>
                                    <th>Nhiệm Vụ Trong Đề Tài</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($members as $m): ?>
                                    <tr>
                                        <td><strong><?= ViewHelper::e($m['MaSV']) ?></strong></td>
                                        <td>
                                            <strong style="color: #fff;"><?= ViewHelper::e($m['HoTen']) ?></strong>
                                            <div style="font-size: 12px; color: var(--text-muted);"><?= ViewHelper::e($m['Email']) ?></div>
                                        </td>
                                        <td>
                                            <span class="badge <?= $m['VaiTro'] === 'TRUONG_NHOM' ? 'badge-amber' : 'badge-secondary' ?>">
                                                <?= $m['VaiTro'] === 'TRUONG_NHOM' ? 'Trưởng Nhóm' : 'Thành Viên' ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?= ViewHelper::e($m['Lop']) ?> (<?= ViewHelper::e($m['Khoa']) ?>)
                                        </td>
                                        <td><?= ViewHelper::e($m['NhiemVuCuThe']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB 3: TIẾN ĐỘ -->
        <div id="tab-progress" class="tab-content" style="display: none;">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Báo Cáo Tiến Độ Đã Nộp</div>
                    <?php if (in_array($topic['TrangThai'], ['DA_PHE_DUYET', 'DANG_THUC_HIEN'])): ?>
                        <a href="<?= App::url('index.php?action=sv-baocao-tiendo&id=' . $topic['MaDeTai']) ?>" class="btn btn-sm btn-primary">
                            + Nộp Báo Cáo Tiến Độ
                        </a>
                    <?php endif; ?>
                </div>
                <div class="card-body" style="padding: 0;">
                    <?php if (empty($progressReports)): ?>
                        <div style="padding: 32px; text-align: center; color: var(--text-muted);">Chưa có báo cáo tiến độ nào.</div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Đợt Báo Cáo</th>
                                        <th>Tiến Độ (%)</th>
                                        <th>Công Việc Đã Làm</th>
                                        <th>Nhận Xét Của GVHD</th>
                                        <th>Ngày Nộp</th>
                                        <th>File</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($progressReports as $r): ?>
                                        <tr>
                                            <td><strong><?= ViewHelper::e($r['DotBaoCao']) ?></strong></td>
                                            <td><strong><?= $r['PhanTramHoanThanh'] ?>%</strong></td>
                                            <td style="max-width: 250px;"><?= nl2br(ViewHelper::e($r['CongViecDaLam'])) ?></td>
                                            <td>
                                                <?= $r['DanhGiaTienDo'] ? ViewHelper::statusBadge($r['DanhGiaTienDo']) : 'Chờ GVHD đánh giá' ?>
                                                <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;"><?= ViewHelper::e($r['NhanXetGVHD']) ?></div>
                                            </td>
                                            <td><?= ViewHelper::datetime($r['NgayNop']) ?></td>
                                            <td>
                                                <a href="<?= App::url('public/' . $r['FileBaoCao']) ?>" target="_blank" class="btn btn-sm btn-secondary">Xem file</a>
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

        <!-- TAB 4: BÁO CÁO TỔNG KẾT & SẢN PHẨM -->
        <div id="tab-final" class="tab-content" style="display: none;">
            <?php if (!$finalReport): ?>
                <div class="card" style="text-align: center; padding: 48px; color: var(--text-muted);">
                    <div style="margin-bottom: 12px; color: var(--text-muted);"><?= ViewHelper::icon('folder', '', 44) ?></div>
                    <h3 style="color: #fff; font-size: 16px;">Chưa nộp Báo cáo tổng kết</h3>
                    <p style="font-size: 14px; margin-top: 6px;">Khi đề tài hoàn thành, hãy nộp toàn văn báo cáo và đính kèm các sản phẩm nghiên cứu.</p>
                    <?php if (in_array($topic['TrangThai'], ['DA_PHE_DUYET', 'DANG_THUC_HIEN'])): ?>
                        <a href="<?= App::url('index.php?action=sv-baocao-tongket&id=' . $topic['MaDeTai']) ?>" class="btn btn-success" style="margin-top: 16px;">
                            Nộp Báo Cáo Tổng Kết Ngay
                        </a>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Toàn Văn Báo Cáo Tổng Kết</div>
                        <?= ViewHelper::statusBadge($finalReport['DanhGiaSoBoGVHD']) ?>
                    </div>
                    <div class="card-body">
                        <div style="margin-bottom: 16px;">
                            <strong style="color: #60a5fa; font-size: 13px; display: block; margin-bottom: 4px;">Tóm tắt kết quả nghiên cứu:</strong>
                            <div style="color: #e2e8f0; font-size: 14px; line-height: 1.6;"><?= nl2br(ViewHelper::e($finalReport['TomTatKetQua'])) ?></div>
                        </div>
                        <div>
                            <a href="<?= App::url('public/' . $finalReport['FileBaoCaoTongKet']) ?>" target="_blank" class="btn btn-primary btn-sm" style="display: inline-flex; align-items: center; gap: 6px;">
                                <?= ViewHelper::icon('download', '', 14) ?> Tải Toàn Văn Báo Cáo Tổng Kết
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Danh Sách Sản Phẩm Nghiên Cứu</div>
                    </div>
                    <div class="card-body" style="padding: 0;">
                        <div class="table-responsive">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Tên Sản Phẩm</th>
                                        <th>Loại</th>
                                        <th>Mô Tả</th>
                                        <th>Liên Kết / Minh Chứng</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($finalReport['san_pham'] as $sp): ?>
                                        <tr>
                                            <td><strong style="color: #fff;"><?= ViewHelper::e($sp['TenSanPham']) ?></strong></td>
                                            <td><span class="badge badge-purple"><?= ViewHelper::e($sp['LoaiSanPham']) ?></span></td>
                                            <td style="max-width: 280px;"><?= ViewHelper::e($sp['MoTaSanPham']) ?></td>
                                            <td>
                                                <?php if (!empty($sp['LienKetMinhChung'])): ?>
                                                    <a href="<?= ViewHelper::e($sp['LienKetMinhChung']) ?>" target="_blank" style="color: #38bdf8; display: inline-flex; align-items: center; gap: 4px; margin-bottom: 4px;">
                                                        <?= ViewHelper::icon('external-link', '', 13) ?> Link demo/bài báo
                                                    </a>
                                                <?php endif; ?>
                                                <?php if (!empty($sp['FileMinhChung'])): ?>
                                                    <a href="<?= App::url('public/' . $sp['FileMinhChung']) ?>" target="_blank" class="btn btn-sm btn-secondary" style="display: inline-flex; align-items: center; gap: 4px;">
                                                        <?= ViewHelper::icon('file-text', '', 13) ?> File minh chứng
                                                    </a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- TAB 5: LỊCH SỬ CHỈNH SỬA -->
        <div id="tab-history" class="tab-content" style="display: none;">
            <div class="card">
                <div class="card-body">
                    <?php if (empty($editHistory)): ?>
                        <div style="text-align: center; padding: 24px; color: var(--text-muted);">Chưa có lần chỉnh sửa nào được ghi nhận.</div>
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

        <!-- TAB: PHIẾU ĐỀ XUẤT (MẪU 02) -->
        <div id="tab-doc-dexuat" class="tab-content" style="display: none;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; background: var(--bg-card); padding: 12px 16px; border-radius: var(--radius-md); border: 1px solid var(--border-color);" class="no-print">
                <div>
                    <span style="font-size: 15px; font-weight: 700; color: var(--text-primary);">Phiếu Đề Xuất Đề Tài NCKH Sinh Viên (Mẫu 02)</span>
                    <div style="font-size: 12px; color: var(--text-secondary); margin-top: 2px;">Chuẩn hóa theo quy định Trường Đại học Tây Bắc - 6 mục nội dung</div>
                </div>
                <div style="display: flex; gap: 8px;">
                    <button type="button" onclick="window.print()" class="btn btn-secondary" style="display: inline-flex; align-items: center; gap: 6px;">
                        <?= ViewHelper::icon('printer', '', 14) ?> In / Xuất A4
                    </button>
                    <?php if ($topic['TrangThai'] === 'YEU_CAU_CHINH_SUA'): ?>
                        <a href="<?= App::url('index.php?action=sv-suahoso-detai&id=' . $topic['MaDeTai'] . '#step-2') ?>" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 6px;">
                            <?= ViewHelper::icon('edit', '', 14) ?> Chỉnh Sửa Phiếu
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <?= NckhFormHelper::renderPhieuDeXuatHtml($parsedDeXuat) ?>
        </div>

        <!-- TAB: THUYẾT MINH ĐỀ TÀI (MẪU 01) -->
        <div id="tab-doc-thuyetminh" class="tab-content" style="display: none;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; background: var(--bg-card); padding: 12px 16px; border-radius: var(--radius-md); border: 1px solid var(--border-color);" class="no-print">
                <div>
                    <span style="font-size: 15px; font-weight: 700; color: var(--text-primary);">Thuyết Minh Đề Tài NCKH Cấp Trường Của Sinh Viên (Mẫu 01)</span>
                    <div style="font-size: 12px; color: var(--text-secondary); margin-top: 2px;">Chuẩn hóa theo quy định Trường Đại học Tây Bắc - 13 mục nội dung</div>
                </div>
                <div style="display: flex; gap: 8px;">
                    <button type="button" onclick="window.print()" class="btn btn-secondary" style="display: inline-flex; align-items: center; gap: 6px;">
                        <?= ViewHelper::icon('printer', '', 14) ?> In / Xuất A4
                    </button>
                    <?php if ($topic['TrangThai'] === 'YEU_CAU_CHINH_SUA'): ?>
                        <a href="<?= App::url('index.php?action=sv-suahoso-detai&id=' . $topic['MaDeTai'] . '#step-3') ?>" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 6px;">
                            <?= ViewHelper::icon('edit', '', 14) ?> Chỉnh Sửa Thuyết Minh
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <?= NckhFormHelper::renderThuyetMinhHtml($parsedThuyetMinh) ?>
        </div>
    </div>
</div>
<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>

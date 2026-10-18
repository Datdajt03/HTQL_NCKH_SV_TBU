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
                Mã: <strong style="color: #60a5fa;"><?= ViewHelper::e($topic['MaDeTai']) ?></strong> | 
                Đợt: <strong><?= ViewHelper::e($topic['TenDot']) ?></strong> | 
                Hướng NC: <strong><?= ViewHelper::e($topic['TenHuongNC']) ?></strong> | 
                Kinh phí: <strong style="color: #38bdf8;"><?= ViewHelper::money($topic['KinhPhiDuKien']) ?></strong>
            </div>
        </div>
        <div style="display: flex; gap: 8px;" class="no-print">
            <button type="button" onclick="window.print()" class="btn btn-secondary" style="display: inline-flex; align-items: center; gap: 6px;">
                <?= ViewHelper::icon('printer', '', 14) ?> In / Xuất A4
            </button>
            <?php if ($topic['TrangThai'] === 'CHO_THAM_DINH'): ?>
                <a href="<?= App::url('index.php?action=cb-thamdinh-detai') ?>" class="btn btn-primary">
                    Thẩm Định Ngay
                </a>
            <?php endif; ?>
            <a href="<?= App::url('index.php?action=cb-dashboard') ?>" class="btn btn-secondary">
                &larr; Về Dashboard
            </a>
        </div>
    </div>

    <div class="tabs-wrapper">
        <div class="tabs-nav no-print">
            <div class="tab-btn active" data-tab="tab-overview" style="display: inline-flex; align-items: center; gap: 6px;"><?= ViewHelper::icon('file-text', '', 14) ?> Thông Tin Chung</div>
            <div class="tab-btn" data-tab="tab-doc-dexuat" style="display: inline-flex; align-items: center; gap: 6px;"><?= ViewHelper::icon('file-text', '', 14) ?> Phiếu Đề Xuất (Mẫu 02)</div>
            <div class="tab-btn" data-tab="tab-doc-thuyetminh" style="display: inline-flex; align-items: center; gap: 6px;"><?= ViewHelper::icon('file-text', '', 14) ?> Thuyết Minh Đề Tài (Mẫu 01)</div>
            <div class="tab-btn" data-tab="tab-members" style="display: inline-flex; align-items: center; gap: 6px;"><?= ViewHelper::icon('users', '', 14) ?> Nhóm Nghiên Cứu (<?= count($members) ?>)</div>
            <div class="tab-btn" data-tab="tab-progress" style="display: inline-flex; align-items: center; gap: 6px;"><?= ViewHelper::icon('clock', '', 14) ?> Tiến Độ (<?= count($progressReports) ?>)</div>
            <div class="tab-btn" data-tab="tab-defense" style="display: inline-flex; align-items: center; gap: 6px;"><?= ViewHelper::icon('building', '', 14) ?> Hội Đồng & Điểm Chấm</div>
            <div class="tab-btn" data-tab="tab-award" style="display: inline-flex; align-items: center; gap: 6px;"><?= ViewHelper::icon('award', '', 14) ?> Xếp Loại & Khen Thưởng</div>
            <div class="tab-btn" data-tab="tab-history" style="display: inline-flex; align-items: center; gap: 6px;"><?= ViewHelper::icon('refresh', '', 14) ?> Lịch Sử Sửa Đổi (<?= count($editHistory) ?>)</div>
        </div>

        <div id="tab-overview" class="tab-content">
            <div class="grid-cols-2">
                <div class="card">
                    <div class="card-header"><div class="card-title">Nội Dung Thuyết Minh Đề Tài</div></div>
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

                <div>
                    <div class="card">
                        <div class="card-header"><div class="card-title">Giảng Viên Hướng Dẫn</div></div>
                        <div class="card-body">
                            <strong style="color: #fff; font-size: 16px;"><?= ViewHelper::e(($topic['HocHam'] ? $topic['HocHam'] . ' ' : '') . $topic['HocVi'] . ' ' . $topic['TenGVHD']) ?></strong>
                            <div style="font-size: 13px; color: var(--text-secondary); margin-top: 4px;">Đơn vị: <?= ViewHelper::e($topic['DonViCongTac']) ?></div>
                            <div style="font-size: 13px; color: var(--text-muted); margin-top: 4px;">Email: <?= ViewHelper::e($topic['EmailGVHD']) ?> | SĐT: <?= ViewHelper::e($topic['SdtGVHD'] ?: '-') ?></div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header"><div class="card-title">Hồ Sơ Đề Tài Trực Tuyến & Đính Kèm</div></div>
                        <div class="card-body">
                            <div style="display: flex; flex-direction: column; gap: 10px; margin-bottom: 16px;">
                                <button type="button" class="btn btn-primary" onclick="document.querySelector('.tab-btn[data-tab=\'tab-doc-dexuat\']').click()" style="justify-content: flex-start; display: inline-flex; align-items: center; gap: 8px;">
                                    <?= ViewHelper::icon('file-text', '', 15) ?> Xem Phiếu Đề Xuất (Mẫu 02 Trực Tuyến)
                                </button>
                                <button type="button" class="btn btn-primary" onclick="document.querySelector('.tab-btn[data-tab=\'tab-doc-thuyetminh\']').click()" style="justify-content: flex-start; display: inline-flex; align-items: center; gap: 8px;">
                                    <?= ViewHelper::icon('file-text', '', 15) ?> Xem Thuyết Minh Đề Tài (Mẫu 01 Trực Tuyến)
                                </button>
                                <?php if (!empty($topic['FilePhieuThuyetMinh'])): ?>
                                    <a href="<?= App::url('public/' . $topic['FilePhieuThuyetMinh']) ?>" target="_blank" class="btn btn-secondary" style="justify-content: flex-start; display: inline-flex; align-items: center; gap: 8px;">
                                        <?= ViewHelper::icon('download', '', 15) ?> Tải Tệp Thuyết Minh Đính Kèm
                                    </a>
                                <?php endif; ?>
                                <?php if (!empty($topic['FilePhieuDeXuat'])): ?>
                                    <a href="<?= App::url('public/' . $topic['FilePhieuDeXuat']) ?>" target="_blank" class="btn btn-secondary" style="justify-content: flex-start; display: inline-flex; align-items: center; gap: 8px;">
                                        <?= ViewHelper::icon('download', '', 15) ?> Tải Tệp Đề Xuất Đính Kèm
                                    </a>
                                <?php endif; ?>
                            </div>

                            <?php if (!empty($topic['NhanXetCanBo'])): ?>
                                <div style="background: rgba(15, 23, 42, 0.6); padding: 12px; border-radius: var(--radius-sm);">
                                    <span style="color: #38bdf8; font-size: 12px; font-weight: 700;">Ý kiến thẩm định của Cán bộ:</span>
                                    <div style="color: #cbd5e1; font-size: 13px; margin-top: 4px;"><?= nl2br(ViewHelper::e($topic['NhanXetCanBo'])) ?></div>
                                </div>
                            <?php endif; ?>
                        </div>
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
                                    <th>Mã SV</th><th>Họ và Tên</th><th>Vai Trò</th><th>Lớp - Khoa</th><th>Nhiệm Vụ Cụ Thể</th>
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
                                        <th>Đợt Báo Cáo</th><th>Tiến Độ (%)</th><th>Nội Dung Hoàn Thành</th><th>Đánh Giá Của GVHD</th><th>Ngày Nộp</th><th>File</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($progressReports as $r): ?>
                                        <tr>
                                            <td><strong><?= ViewHelper::e($r['DotBaoCao']) ?></strong></td>
                                            <td><strong><?= $r['PhanTramHoanThanh'] ?>%</strong></td>
                                            <td style="max-width: 250px;"><?= nl2br(ViewHelper::e($r['CongViecDaLam'])) ?></td>
                                            <td>
                                                <?= $r['DanhGiaTienDo'] ? ViewHelper::statusBadge($r['DanhGiaTienDo']) : 'Chờ GVHD nhận xét' ?>
                                                <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;"><?= ViewHelper::e($r['NhanXetGVHD']) ?></div>
                                            </td>
                                            <td><?= ViewHelper::datetime($r['NgayNop']) ?></td>
                                            <td><a href="<?= App::url('public/' . $r['FileBaoCao']) ?>" target="_blank" class="btn btn-sm btn-secondary">File</a></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div id="tab-defense" class="tab-content" style="display: none;">
            <?php if (!$assignment): ?>
                <div class="card" style="text-align: center; padding: 36px; color: var(--text-muted);">
                    Đề tài chưa được phân công vào Hội đồng nghiệm thu nào.
                </div>
            <?php else: ?>
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Thông Tin Buổi Bảo Vệ Nghiệm Thu</div>
                        <?= $assignment['KetLuan'] ? ViewHelper::statusBadge($assignment['KetLuan']) : '<span class="badge badge-warning">Chờ Chấm Điểm</span>' ?>
                    </div>
                    <div class="card-body">
                        <div class="grid-cols-3" style="margin-bottom: 16px;">
                            <div>
                                <span style="color: var(--text-muted); font-size: 13px;">Hội đồng phụ trách:</span>
                                <strong style="color: #fff; display: block;"><?= ViewHelper::e($assignment['TenHoiDong']) ?></strong>
                            </div>
                            <div>
                                <span style="color: var(--text-muted); font-size: 13px;">Thời gian bảo vệ:</span>
                                <strong style="color: #fff; display: block;"><?= ViewHelper::datetime($assignment['ThoiGianBaoCao']) ?></strong>
                            </div>
                            <div>
                                <span style="color: var(--text-muted); font-size: 13px;">Địa điểm:</span>
                                <strong style="color: #38bdf8; display: block;"><?= ViewHelper::e($assignment['DiaDiem']) ?></strong>
                            </div>
                        </div>

                        <?php if ($assignment['DiemTrungBinh']): ?>
                            <div style="background: rgba(15, 23, 42, 0.6); padding: 16px; border-radius: var(--radius-md); display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <span style="color: var(--text-muted); font-size: 13px;">Điểm trung bình chính thức của Hội đồng:</span>
                                    <div style="font-size: 28px; font-weight: 800; color: #f59e0b;"><?= number_format((float)$assignment['DiemTrungBinh'], 2) ?> / 100</div>
                                </div>
                                <?php if (!empty($assignment['FileBienBan'])): ?>
                                    <a href="<?= App::url('public/' . $assignment['FileBienBan']) ?>" target="_blank" class="btn btn-secondary">
                                        <?= ViewHelper::icon('download') ?> Xem Biên Bản Họp Hội Đồng
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- CÁC PHIẾU CHẤM CỦA GIÁM KHẢO -->
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Chi Tiết Phiếu Chấm Điểm Giám Khảo (<?= count($scoreSheets) ?> phiếu)</div>
                    </div>
                    <div class="card-body" style="padding: 0;">
                        <div class="table-responsive">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Giám Khảo</th>
                                        <th>Vai Trò HĐ</th>
                                        <th>Tổng Quan (20đ)</th>
                                        <th>Phương Pháp (30đ)</th>
                                        <th>Sản Phẩm (30đ)</th>
                                        <th>Báo Cáo (20đ)</th>
                                        <th>Tổng Điểm (100)</th>
                                        <th>Nhận Xét</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($scoreSheets as $s): ?>
                                        <tr>
                                            <td><strong style="color: #fff;"><?= ViewHelper::e($s['TenGiamKhao']) ?></strong></td>
                                            <td><span class="badge badge-secondary"><?= $s['VaiTroHoiDong'] ?></span></td>
                                            <td><?= $s['DiemTongQuan'] ?></td>
                                            <td><?= $s['DiemPhuongPhap'] ?></td>
                                            <td><?= $s['DiemSanPham'] ?></td>
                                            <td><?= $s['DiemBaoCao'] ?></td>
                                            <td><strong style="color: #38bdf8; font-size: 16px;"><?= $s['TongDiem'] ?></strong></td>
                                            <td style="max-width: 200px; font-size: 12px;"><?= ViewHelper::e($s['NhanXetChiTiet']) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div id="tab-award" class="tab-content" style="display: none;">
            <?php if (!$award): ?>
                <div class="card" style="text-align: center; padding: 36px; color: var(--text-muted);">
                    Đề tài chưa được xếp loại khen thưởng.
                </div>
            <?php else: ?>
                <div class="card">
                    <div class="card-body">
                        <div class="grid-cols-4">
                            <div>
                                <span style="font-size: 12px; color: var(--text-muted); text-transform: uppercase;">Điểm Tổng Kết</span>
                                <div style="font-size: 28px; font-weight: 800; color: #38bdf8;"><?= $award['DiemTongKet'] ?></div>
                            </div>
                            <div>
                                <span style="font-size: 12px; color: var(--text-muted); text-transform: uppercase;">Xếp Loại</span>
                                <div style="margin-top: 6px;"><?= ViewHelper::statusBadge($award['XepLoai']) ?></div>
                            </div>
                            <div>
                                <span style="font-size: 12px; color: var(--text-muted); text-transform: uppercase;">Danh Hiệu Khen Thưởng</span>
                                <div style="margin-top: 6px;"><?= ViewHelper::statusBadge($award['DanhHieuKhenThuong']) ?></div>
                            </div>
                            <div>
                                <span style="font-size: 12px; color: var(--text-muted); text-transform: uppercase;">Tiền Thưởng</span>
                                <div style="font-size: 18px; font-weight: 800; color: #f59e0b; margin-top: 4px;"><?= ViewHelper::money($award['TienThuong']) ?></div>
                            </div>
                        </div>
                        <div style="margin-top: 16px; border-top: 1px solid var(--border-color); padding-top: 12px; font-size: 13px; color: var(--text-secondary);">
                            Số Quyết định: <strong><?= ViewHelper::e($award['SoQuyetDinh']) ?></strong> (Ngày ký: <?= ViewHelper::date($award['NgayQuyetDinh']) ?>)
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div id="tab-history" class="tab-content" style="display: none;">
            <div class="card">
                <div class="card-body">
                    <?php if (empty($editHistory)): ?>
                        <div style="text-align: center; padding: 24px; color: var(--text-muted);">Không có lần chỉnh sửa nào.</div>
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
        <!-- TAB: PHIẾU ĐỀ XUẤT (MẪU 02) -->
        <div id="tab-doc-dexuat" class="tab-content" style="display: none;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; background: var(--bg-card); padding: 12px 16px; border-radius: var(--radius-md); border: 1px solid var(--border-color);" class="no-print">
                <div>
                    <span style="font-size: 15px; font-weight: 700; color: var(--text-primary);">Phiếu Đề Xuất Đề Tài NCKH Sinh Viên (Mẫu 02)</span>
                    <div style="font-size: 12px; color: var(--text-secondary); margin-top: 2px;">Chuẩn hóa theo quy định Trường Đại học Tây Bắc - 6 mục nội dung</div>
                </div>
                <button type="button" onclick="window.print()" class="btn btn-secondary" style="display: inline-flex; align-items: center; gap: 6px;">
                    <?= ViewHelper::icon('printer', '', 14) ?> In / Xuất A4
                </button>
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
                <button type="button" onclick="window.print()" class="btn btn-secondary" style="display: inline-flex; align-items: center; gap: 6px;">
                    <?= ViewHelper::icon('printer', '', 14) ?> In / Xuất A4
                </button>
            </div>
            <?= NckhFormHelper::renderThuyetMinhHtml($parsedThuyetMinh) ?>
        </div>
    </div>
</div>
<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>

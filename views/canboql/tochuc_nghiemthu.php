<?php
use App\Config\App;
use App\Helpers\ViewHelper;

$pageTitle = "Tổ Chức Hội Đồng Nghiệm Thu";
require dirname(__DIR__) . '/layouts/header.php';
?>
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h2 style="font-size: 20px; font-weight: 800; color: #fff; margin-bottom: 4px;">
            14. Tổ Chức Hội Đồng Nghiệm Thu & Nhập Kết Quả Đánh Giá
        </h2>
        <p style="color: var(--text-secondary); font-size: 14px; margin: 0;">
            Thành lập hội đồng chuyên môn, phân công lịch bảo vệ, chấm điểm 4 tiêu chuẩn và lập biên bản kết luận nghiệm thu.
        </p>
    </div>
    <div style="display: flex; gap: 10px;">
        <button type="button" class="btn btn-secondary" onclick="openModal('modal-assign-topic')">
            <?= ViewHelper::icon('plus') ?> Phân Công Đề Tài Bảo Vệ
        </button>
        <button type="button" class="btn btn-primary" onclick="openModal('modal-create-council')">
            <?= ViewHelper::icon('building') ?> Thành Lập Hội Đồng Mới
        </button>
    </div>
</div>

<!-- CHỌN HỘI ĐỒNG XEM CHI TIẾT -->
<div class="card">
    <div class="card-header">
        <div class="card-title">
            <?= ViewHelper::icon('building') ?>
            <span>Danh Sách Các Hội Đồng Nghiệm Thu</span>
        </div>
        <div style="display: flex; gap: 8px;">
            <?php foreach ($councils as $c): ?>
                <a href="<?= App::url('index.php?action=cb-tochuc-nghiemthu&council_id=' . $c['MaHoiDong']) ?>" class="switch-pill <?= ($selectedCouncil && $selectedCouncil['MaHoiDong'] === $c['MaHoiDong']) ? 'active' : '' ?>">
                    <?= ViewHelper::e($c['MaHoiDong']) ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="card-body">
        <?php if (!$selectedCouncil): ?>
            <div style="text-align: center; padding: 36px; color: var(--text-muted);">
                Chưa có hội đồng nghiệm thu nào được thành lập. Bấm nút "+ Thành Lập Hội Đồng Mới" ở góc trên để tạo.
            </div>
        <?php else: ?>
            <div style="background: rgba(15, 23, 42, 0.6); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 20px; margin-bottom: 24px;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                    <div>
                        <span class="badge badge-purple" style="margin-bottom: 6px;"><?= $selectedCouncil['LoaiHoiDong'] ?></span>
                        <h3 style="font-size: 18px; font-weight: 800; color: #fff; margin: 0;">
                            <?= ViewHelper::e($selectedCouncil['TenHoiDong']) ?>
                        </h3>
                        <div style="font-size: 13px; color: var(--text-muted); margin-top: 4px;">
                            Mã HĐ: <strong style="color: #60a5fa;"><?= ViewHelper::e($selectedCouncil['MaHoiDong']) ?></strong> | 
                            Đợt: <?= ViewHelper::e($selectedCouncil['TenDot']) ?> | 
                            Quyết định số: <?= ViewHelper::e($selectedCouncil['SoQuyetDinh'] ?: 'Chưa cập nhật') ?> | 
                            Ngày lập: <?= ViewHelper::date($selectedCouncil['NgayThanhLap']) ?>
                        </div>
                    </div>
                    <div style="display: flex; gap: 8px; align-items: center;">
                        <?= ViewHelper::statusBadge($selectedCouncil['TrangThai']) ?>
                        <button type="button" class="btn btn-sm btn-secondary" onclick="openModal('modal-edit-council')">
                            <?= ViewHelper::icon('edit') ?> Sửa Hội Đồng
                        </button>
                        <form method="POST" action="<?= App::url('index.php?action=cb-tochuc-nghiemthu') ?>" style="display:inline;" onsubmit="return confirm('CẢNH BÁO: Bạn có chắc chắn muốn xóa hội đồng <?= ViewHelper::e($selectedCouncil['MaHoiDong']) ?>? Các phân công bảo vệ và phiếu điểm liên quan sẽ bị xóa.');">
                            <input type="hidden" name="sub_action" value="delete_council">
                            <input type="hidden" name="mahoidong" value="<?= ViewHelper::e($selectedCouncil['MaHoiDong']) ?>">
                            <button type="submit" class="btn btn-sm btn-danger" title="Xóa hội đồng">
                                <?= ViewHelper::icon('trash') ?> Xóa
                            </button>
                        </form>
                    </div>
                </div>

                <!-- DANH SÁCH THÀNH VIÊN HỘI ĐỒNG -->
                <div style="margin-top: 16px; border-top: 1px solid var(--border-color); padding-top: 14px;">
                    <div style="font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 10px;">Thành Viên Trong Hội Đồng:</div>
                    <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                        <?php foreach ($councilMembers as $mem): ?>
                            <div style="background: rgba(255, 255, 255, 0.04); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 8px 14px; font-size: 13px;">
                                <span class="badge <?= $mem['VaiTroHoiDong'] === 'CHU_TICH' ? 'badge-gold' : ($mem['VaiTroHoiDong'] === 'THU_KY' ? 'badge-cyan' : 'badge-primary') ?>" style="margin-right: 6px; font-size: 11px;">
                                    <?= $mem['VaiTroHoiDong'] ?>
                                </span>
                                <strong style="color: #fff;"><?= ViewHelper::e(($mem['HocVi'] ? $mem['HocVi'] . ' ' : '') . $mem['HoTen']) ?></strong>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- DANH SÁCH ĐỀ TÀI PHÂN CÔNG VÀO HỘI ĐỒNG NÀY -->
            <div>
                <h4 style="color: #fff; font-size: 16px; margin-bottom: 12px; display: flex; align-items: center; justify-content: space-between;">
                    <span style="display: flex; align-items: center; gap: 8px;"><?= ViewHelper::icon('file-text') ?> Các Đề Tài Phân Công Bảo Vệ Tại Hội Đồng (<?= count($assignedTopics) ?> đề tài)</span>
                    <button type="button" class="btn btn-sm btn-primary" onclick="openModal('modal-assign-topic')">
                        <?= ViewHelper::icon('plus') ?> Phân Công Thêm Đề Tài
                    </button>
                </h4>

                <?php if (empty($assignedTopics)): ?>
                    <div style="text-align: center; padding: 32px; background: rgba(15, 23, 42, 0.3); border-radius: var(--radius-md); color: var(--text-muted);">
                        Hội đồng này chưa được phân công chấm đề tài nào.
                    </div>
                <?php else: ?>
                    <div style="display: flex; flex-direction: column; gap: 18px;">
                        <?php foreach ($assignedTopics as $assign): ?>
                            <div class="card" style="margin-bottom: 0; background: rgba(15, 23, 42, 0.5); border: 1px solid var(--border-color);">
                                <div class="card-header">
                                    <div>
                                        <span style="color: #60a5fa; font-weight: 700; font-size: 13px;"><?= ViewHelper::e($assign['MaDeTai']) ?></span>
                                        <h4 style="font-size: 16px; font-weight: 800; color: #fff; margin-top: 2px;">
                                            <?= ViewHelper::e($assign['TenDeTai']) ?>
                                        </h4>
                                    </div>
                                    <div>
                                        <?php if ($assign['KetLuan']): ?>
                                            <span class="badge <?= $assign['KetLuan'] === 'DAT' ? 'badge-success' : 'badge-danger' ?>" style="font-size: 13px; display: inline-flex; align-items: center; gap: 4px;">
                                                <?= $assign['KetLuan'] === 'DAT' ? ViewHelper::icon('check', '', 14) . ' ĐẠT NGHIỆM THU' : ViewHelper::icon('x', '', 14) . ' KHÔNG ĐẠT' ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="badge badge-warning" style="display: inline-flex; align-items: center; gap: 4px;">
                                                <?= ViewHelper::icon('clock', '', 12) ?> Chờ Chấm Điểm
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="grid-cols-3" style="font-size: 13px; margin-bottom: 16px;">
                                        <div>
                                            <span style="color: var(--text-muted);">Thời gian bảo vệ:</span>
                                            <strong style="color: #fff; display: block;"><?= ViewHelper::datetime($assign['ThoiGianBaoCao']) ?></strong>
                                        </div>
                                        <div>
                                            <span style="color: var(--text-muted);">Địa điểm:</span>
                                            <strong style="color: #38bdf8; display: block;"><?= ViewHelper::e($assign['DiaDiem']) ?></strong>
                                        </div>
                                        <div>
                                            <span style="color: var(--text-muted);">Điểm trung bình HĐ:</span>
                                            <strong style="color: #f59e0b; font-size: 16px; display: block;">
                                                <?= $assign['DiemTrungBinh'] ? number_format((float)$assign['DiemTrungBinh'], 2) . ' / 100' : 'Chưa tổng kết' ?>
                                            </strong>
                                        </div>
                                    </div>

                                    <!-- DANH SÁCH PHIẾU ĐIỂM ĐÃ NHẬP -->
                                    <?php if (!empty($assign['scoreSheets'])): ?>
                                        <div style="background: rgba(15, 23, 42, 0.4); border-radius: var(--radius-sm); padding: 10px 14px; margin-bottom: 14px; border: 1px solid rgba(255, 255, 255, 0.05);">
                                            <div style="font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 8px;">
                                                Chi Tiết Phiếu Chấm Của Giám Khảo (<?= count($assign['scoreSheets']) ?> phiếu):
                                            </div>
                                            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                                                <?php foreach ($assign['scoreSheets'] as $ps): ?>
                                                    <div style="display: flex; align-items: center; gap: 8px; background: rgba(255, 255, 255, 0.05); padding: 6px 10px; border-radius: var(--radius-sm); font-size: 12px;">
                                                        <span style="color: #60a5fa; font-weight: 600;"><?= ViewHelper::e($ps['TenGiamKhao']) ?> (<?= ViewHelper::e($ps['VaiTroHoiDong'] ?? 'GK') ?>):</span>
                                                        <strong style="color: #38bdf8;"><?= number_format((float)$ps['TongDiem'], 1) ?>đ</strong>
                                                        <form method="POST" action="<?= App::url('index.php?action=cb-tochuc-nghiemthu') ?>" style="display:inline; margin:0;" onsubmit="return confirm('Bạn có chắc muốn xóa phiếu chấm của giám khảo <?= ViewHelper::e($ps['TenGiamKhao']) ?>?');">
                                                            <input type="hidden" name="sub_action" value="delete_scoresheet">
                                                            <input type="hidden" name="maphieu" value="<?= (int)$ps['MaPhieu'] ?>">
                                                            <input type="hidden" name="mahoidong" value="<?= ViewHelper::e($selectedCouncil['MaHoiDong']) ?>">
                                                            <button type="submit" class="btn btn-sm btn-danger" style="padding: 2px 6px; font-size: 11px;" title="Xóa phiếu chấm này">
                                                                <?= ViewHelper::icon('trash', '', 12) ?>
                                                            </button>
                                                        </form>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <!-- NÚT THAO TÁC CHẤM ĐIỂM & TỔNG KẾT -->
                                    <div style="display: flex; gap: 10px; justify-content: flex-end; border-top: 1px solid var(--border-color); padding-top: 14px; flex-wrap: wrap;">
                                        <a href="<?= App::url('index.php?action=cb-chitiet-detai&id=' . $assign['MaDeTai']) ?>" class="btn btn-sm btn-secondary">
                                            <?= ViewHelper::icon('search') ?> Xem Toàn Bộ Hồ Sơ & Sản Phẩm
                                        </a>

                                        <button type="button" class="btn btn-sm btn-secondary" onclick="openScoreSheetModal(<?= $assign['MaPhanCong'] ?>, '<?= ViewHelper::e($assign['MaDeTai']) ?>')">
                                            <?= ViewHelper::icon('edit') ?> Nhập Phiếu Chấm Giám Khảo (<?= $assign['SoPhieuCham'] ?> phiếu)
                                        </button>

                                        <button type="button" class="btn btn-sm btn-success" onclick="openFinalizeModal(<?= $assign['MaPhanCong'] ?>, '<?= ViewHelper::e($assign['MaDeTai']) ?>')">
                                            <?= ViewHelper::icon('check-circle') ?> Tổng Kết Nghiệm Thu
                                        </button>

                                        <form method="POST" action="<?= App::url('index.php?action=cb-tochuc-nghiemthu') ?>" style="display:inline;" onsubmit="return confirm('Bạn có chắc chắn muốn gỡ đề tài <?= ViewHelper::e($assign['MaDeTai']) ?> khỏi phân công của hội đồng này?');">
                                            <input type="hidden" name="sub_action" value="unassign_topic">
                                            <input type="hidden" name="maphancong" value="<?= (int)$assign['MaPhanCong'] ?>">
                                            <input type="hidden" name="mahoidong" value="<?= ViewHelper::e($selectedCouncil['MaHoiDong']) ?>">
                                            <button type="submit" class="btn btn-sm btn-danger" title="Gỡ đề tài khỏi hội đồng">
                                                <?= ViewHelper::icon('trash') ?> Gỡ Phân Công
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- MODAL: THÀNH LẬP HỘI ĐỒNG MỚI -->
<div id="modal-create-council" class="modal-backdrop">
    <div class="modal-container" style="max-width: 720px;">
        <div class="modal-header">
            <div class="modal-title">Thành Lập Hội Đồng Nghiệm Thu Mới</div>
            <button type="button" class="modal-close" onclick="closeModal('modal-create-council')">&times;</button>
        </div>
        <form method="POST" action="<?= App::url('index.php?action=cb-tochuc-nghiemthu') ?>">
            <input type="hidden" name="sub_action" value="create_council">
            <div class="modal-body">
                <div class="grid-cols-2">
                    <div class="form-group">
                        <label class="form-label">Mã Hội Đồng <span class="required">*</span></label>
                        <input type="text" name="mahoidong" class="form-control" placeholder="VD: HD_NT_2026_02" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Đợt NCKH Áp Dụng <span class="required">*</span></label>
                        <select name="madot" class="form-control" required>
                            <?php foreach ($dots as $d): ?>
                                <option value="<?= ViewHelper::e($d['MaDot']) ?>"><?= ViewHelper::e($d['TenDot']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Tên Hội Đồng Nghiệm Thu <span class="required">*</span></label>
                    <input type="text" name="tenhoidong" class="form-control" placeholder="VD: Hội đồng nghiệm thu đề tài NCKH Sinh viên chuyên ngành AI số 02" required>
                </div>

                <div class="grid-cols-2">
                    <div class="form-group">
                        <label class="form-label">Loại Hội Đồng <span class="required">*</span></label>
                        <select name="loaihoidong" class="form-control" required>
                            <option value="NGHIEM_THU">Hội đồng Nghiệm thu chính thức</option>
                            <option value="THAM_DINH">Hội đồng Thẩm định sơ bộ</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Số Quyết Định Thành Lập</label>
                        <input type="text" name="soquyetdinh" class="form-control" placeholder="VD: QĐ-135/QĐ-ĐH-NCKH">
                    </div>
                </div>

                <!-- THÀNH VIÊN HỘI ĐỒNG -->
                <div style="background: rgba(15, 23, 42, 0.4); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 16px; margin-top: 14px;">
                    <div style="font-size: 13px; font-weight: 700; color: #fff; margin-bottom: 12px;">Chỉ Định Thành Viên Hội Đồng (Tối thiểu 3 thành viên)</div>
                    
                    <div class="form-group">
                        <label class="form-label">1. Chủ Tịch Hội Đồng <span class="required">*</span></label>
                        <input type="hidden" name="members[0][role]" value="CHU_TICH">
                        <select name="members[0][user_id]" class="form-control" required>
                            <option value="">-- Chọn Chủ tịch hội đồng --</option>
                            <?php foreach ($teachers as $t): ?>
                                <option value="<?= ViewHelper::e($t['MaNguoiDung']) ?>"><?= ViewHelper::e($t['HocVi'] . ' ' . $t['HoTen']) ?> (<?= ViewHelper::e($t['DonViCongTac']) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">2. Thư Ký Hội Đồng <span class="required">*</span></label>
                        <input type="hidden" name="members[1][role]" value="THU_KY">
                        <select name="members[1][user_id]" class="form-control" required>
                            <option value="">-- Chọn Thư ký hội đồng --</option>
                            <option value="CB001">Nguyễn Văn Cán (Phòng Quản lý Khoa học)</option>
                            <option value="CB002">Trần Thị Mai (Phòng Quản lý Khoa học)</option>
                            <?php foreach ($teachers as $t): ?>
                                <option value="<?= ViewHelper::e($t['MaNguoiDung']) ?>"><?= ViewHelper::e($t['HocVi'] . ' ' . $t['HoTen']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">3. Giám Khảo Phản Biện 1 <span class="required">*</span></label>
                        <input type="hidden" name="members[2][role]" value="PHAN_BIEN_1">
                        <select name="members[2][user_id]" class="form-control" required>
                            <option value="">-- Chọn Giám khảo phản biện 1 --</option>
                            <?php foreach ($teachers as $t): ?>
                                <option value="<?= ViewHelper::e($t['MaNguoiDung']) ?>"><?= ViewHelper::e($t['HocVi'] . ' ' . $t['HoTen']) ?> (<?= ViewHelper::e($t['DonViCongTac']) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modal-create-council')">Hủy</button>
                <button type="submit" class="btn btn-primary">Thành Lập Hội Đồng</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL: PHÂN CÔNG ĐỀ TÀI VÀO HỘI ĐỒNG -->
<div id="modal-assign-topic" class="modal-backdrop">
    <div class="modal-container">
        <div class="modal-header">
            <div class="modal-title">Phân Công Đề Tài Vào Hội Đồng Nghiệm Thu</div>
            <button type="button" class="modal-close" onclick="closeModal('modal-assign-topic')">&times;</button>
        </div>
        <form method="POST" action="<?= App::url('index.php?action=cb-tochuc-nghiemthu') ?>">
            <input type="hidden" name="sub_action" value="assign_topic">
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Hội Đồng Phụ Trách <span class="required">*</span></label>
                    <select name="mahoidong" class="form-control" required>
                        <?php foreach ($councils as $c): ?>
                            <option value="<?= ViewHelper::e($c['MaHoiDong']) ?>" <?= ($selectedCouncil && $selectedCouncil['MaHoiDong'] === $c['MaHoiDong']) ? 'selected' : '' ?>>
                                <?= ViewHelper::e($c['MaHoiDong']) ?> - <?= ViewHelper::e($c['TenHoiDong']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Chọn Đề Tài Cần Nghiệm Thu (Trạng thái CHO_NGHIEM_THU) <span class="required">*</span></label>
                    <select name="madetai" class="form-control" required>
                        <option value="">-- Chọn đề tài đã sẵn sàng nghiệm thu --</option>
                        <?php foreach ($readyTopics as $t): ?>
                            <option value="<?= ViewHelper::e($t['MaDeTai']) ?>">
                                <?= ViewHelper::e($t['MaDeTai']) ?> - <?= ViewHelper::e($t['TenDeTai']) ?> (GVHD: <?= ViewHelper::e($t['TenGVHD']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="form-help">Hệ thống sẽ tự động kiểm tra để tránh xung đột lợi ích (GVHD không được là thành viên của HĐ chấm đề tài).</div>
                </div>

                <div class="grid-cols-2">
                    <div class="form-group">
                        <label class="form-label">Thời Gian Tổ Chức Bảo Vệ <span class="required">*</span></label>
                        <input type="datetime-local" name="thoigianbaocao" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Phòng Họp / Địa Điểm <span class="required">*</span></label>
                        <input type="text" name="diadiem" class="form-control" placeholder="VD: Phòng Hội thảo A2-302" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modal-assign-topic')">Hủy</button>
                <button type="submit" class="btn btn-primary">Xác Nhận Phân Công</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL: NHẬP PHIẾU CHẤM ĐIỂM GIÁM KHẢO -->
<div id="modal-scoresheet" class="modal-backdrop">
    <div class="modal-container">
        <div class="modal-header">
            <div class="modal-title">Nhập Phiếu Chấm Điểm Của Giám Khảo</div>
            <button type="button" class="modal-close" onclick="closeModal('modal-scoresheet')">&times;</button>
        </div>
        <form method="POST" action="<?= App::url('index.php?action=cb-tochuc-nghiemthu') ?>">
            <input type="hidden" name="sub_action" value="score_sheet">
            <input type="hidden" name="maphancong" id="scoresheet-maphancong">
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Giám Khảo Chấm Điểm <span class="required">*</span></label>
                    <select name="magiamkhao" class="form-control" required>
                        <option value="">-- Chọn giám khảo trong hội đồng --</option>
                        <?php if ($selectedCouncil): ?>
                            <?php foreach ($councilMembers as $mem): ?>
                                <option value="<?= ViewHelper::e($mem['MaNguoiDung']) ?>">
                                    <?= ViewHelper::e($mem['HoTen']) ?> (<?= ViewHelper::e($mem['VaiTroHoiDong']) ?>)
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <!-- 4 TIÊU CHÍ ĐIỂM CHUẨN TRONG THIETKEDATABASE.MD -->
                <div style="background: rgba(15, 23, 42, 0.4); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 16px; margin-bottom: 16px;">
                    <div style="font-size: 13px; font-weight: 700; color: #fff; margin-bottom: 12px;">Khung Điểm Chấm 4 Tiêu Chí (Thang 100)</div>
                    
                    <div class="grid-cols-2">
                        <div class="form-group">
                            <label class="form-label" style="font-size: 12px;">1. Điểm Tổng Quan Tình Hình NC (Tối đa 20đ) <span class="required">*</span></label>
                            <input type="number" step="0.5" min="0" max="20" name="diem_tongquan" class="form-control calc-score-input" value="18" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label" style="font-size: 12px;">2. Điểm Phương Pháp & Nội Dung (Tối đa 30đ) <span class="required">*</span></label>
                            <input type="number" step="0.5" min="0" max="30" name="diem_phuongphap" class="form-control calc-score-input" value="27" required>
                        </div>
                    </div>

                    <div class="grid-cols-2">
                        <div class="form-group">
                            <label class="form-label" style="font-size: 12px;">3. Điểm Giá Trị Sản Phẩm (Tối đa 30đ) <span class="required">*</span></label>
                            <input type="number" step="0.5" min="0" max="30" name="diem_sanpham" class="form-control calc-score-input" value="28" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label" style="font-size: 12px;">4. Điểm Trình Bày & Trả Lời (Tối đa 20đ) <span class="required">*</span></label>
                            <input type="number" step="0.5" min="0" max="20" name="diem_baocao" class="form-control calc-score-input" value="19" required>
                        </div>
                    </div>

                    <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--border-color); padding-top: 10px; margin-top: 10px;">
                        <span style="font-weight: 700; color: #fff;">Tổng Điểm Tự Động Tính (Thang 100):</span>
                        <span id="calc-total-score" style="font-size: 24px; font-weight: 800; color: #38bdf8;">92.00</span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Nhận Xét Chi Tiết Của Giám Khảo</label>
                    <textarea name="nhanxet_chitiet" class="form-control" rows="3" placeholder="Đánh giá ưu nhược điểm của đề tài và hướng phát triển..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modal-scoresheet')">Hủy</button>
                <button type="submit" class="btn btn-primary">Lưu Phiếu Điểm</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL: TỔNG KẾT NGHIỆM THU -->
<div id="modal-finalize" class="modal-backdrop">
    <div class="modal-container">
        <div class="modal-header">
            <div class="modal-title">Tổng Kết Kết Quả Nghiệm Thu Đề Tài</div>
            <button type="button" class="modal-close" onclick="closeModal('modal-finalize')">&times;</button>
        </div>
        <form method="POST" action="<?= App::url('index.php?action=cb-tochuc-nghiemthu') ?>" enctype="multipart/form-data">
            <input type="hidden" name="sub_action" value="finalize_defense">
            <input type="hidden" name="maphancong" id="finalize-maphancong">
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Điểm Trung Bình Hội Đồng (Thang 100) <span class="required">*</span></label>
                    <input type="number" step="0.1" min="0" max="100" name="diem_trungbinh" class="form-control" placeholder="VD: 92.5" required>
                    <div class="form-help">Điểm số trung bình cộng từ tất cả các phiếu chấm của giám khảo trong hội đồng.</div>
                </div>

                <div class="form-group">
                    <label class="form-label">Kết Luận Của Hội Đồng <span class="required">*</span></label>
                    <select name="ketluan" class="form-control" required>
                        <option value="DAT">ĐẠT - Công nhận hoàn thành đề tài NCKH</option>
                        <option value="BAO_VE_LAI">BẢO VỆ LẠI - Cần chỉnh sửa bổ sung</option>
                        <option value="KHONG_DAT">KHÔNG ĐẠT - Không công nhận kết quả</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">File Scan Biên Bản Họp Hội Đồng (PDF / Word)</label>
                    <input type="file" name="file_bienban" class="form-control" accept=".pdf,.doc,.docx">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modal-finalize')">Hủy</button>
                <button type="submit" class="btn btn-success">Chốt Kết Quả Nghiệm Thu</button>
            </div>
        </form>
    </div>
</div>

<?php if ($selectedCouncil): ?>
<!-- MODAL: CHỈNH SỬA HỘI ĐỒNG -->
<div id="modal-edit-council" class="modal-backdrop">
    <div class="modal-container" style="max-width: 650px;">
        <div class="modal-header">
            <div class="modal-title">Chỉnh Sửa Thông Tin Hội Đồng: <?= ViewHelper::e($selectedCouncil['MaHoiDong']) ?></div>
            <button type="button" class="modal-close" onclick="closeModal('modal-edit-council')">&times;</button>
        </div>
        <form method="POST" action="<?= App::url('index.php?action=cb-tochuc-nghiemthu') ?>">
            <input type="hidden" name="sub_action" value="update_council">
            <input type="hidden" name="mahoidong" value="<?= ViewHelper::e($selectedCouncil['MaHoiDong']) ?>">
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Tên Hội Đồng Nghiệm Thu <span class="required">*</span></label>
                    <input type="text" name="tenhoidong" class="form-control" value="<?= ViewHelper::e($selectedCouncil['TenHoiDong']) ?>" required>
                </div>

                <div class="grid-cols-2">
                    <div class="form-group">
                        <label class="form-label">Loại Hội Đồng <span class="required">*</span></label>
                        <select name="loaihoidong" class="form-control" required>
                            <option value="NGHIEM_THU" <?= $selectedCouncil['LoaiHoiDong'] === 'NGHIEM_THU' ? 'selected' : '' ?>>Hội đồng Nghiệm thu chính thức</option>
                            <option value="THAM_DINH" <?= $selectedCouncil['LoaiHoiDong'] === 'THAM_DINH' ? 'selected' : '' ?>>Hội đồng Thẩm định sơ bộ</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Trạng Thái <span class="required">*</span></label>
                        <select name="trangthai" class="form-control" required>
                            <option value="HOAT_DONG" <?= $selectedCouncil['TrangThai'] === 'HOAT_DONG' ? 'selected' : '' ?>>Đang hoạt động</option>
                            <option value="HOAN_THANH" <?= $selectedCouncil['TrangThai'] === 'HOAN_THANH' ? 'selected' : '' ?>>Hoàn thành nghiệm thu</option>
                        </select>
                    </div>
                </div>

                <div class="grid-cols-2">
                    <div class="form-group">
                        <label class="form-label">Ngày Thành Lập <span class="required">*</span></label>
                        <input type="date" name="ngaythanhlap" class="form-control" value="<?= ViewHelper::e($selectedCouncil['NgayThanhLap']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Số Quyết Định</label>
                        <input type="text" name="soquyetdinh" class="form-control" value="<?= ViewHelper::e($selectedCouncil['SoQuyetDinh'] ?? '') ?>" placeholder="VD: QĐ-135/QĐ-ĐH-NCKH">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modal-edit-council')">Hủy</button>
                <button type="submit" class="btn btn-primary">Lưu Thay Đổi</button>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>

<script>
function openScoreSheetModal(maPhanCong, maDeTai) {
    document.getElementById('scoresheet-maphancong').value = maPhanCong;
    openModal('modal-scoresheet');
}
function openFinalizeModal(maPhanCong, maDeTai) {
    document.getElementById('finalize-maphancong').value = maPhanCong;
    openModal('modal-finalize');
}
</script>
<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>

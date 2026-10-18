<?php
use App\Config\App;
use App\Helpers\ViewHelper;

$pageTitle = "Quản Lý Đợt NCKH & Biểu Mẫu";
require dirname(__DIR__) . '/layouts/header.php';
?>
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h2 style="font-size: 20px; font-weight: 800; color: #fff; margin-bottom: 4px;">
            11. Quản Lý Đợt NCKH, Hướng Nghiên Cứu & Biểu Mẫu
        </h2>
        <p style="color: var(--text-secondary); font-size: 14px; margin: 0;">
            Quản trị chu kỳ các đợt phát động NCKH theo năm học, danh mục lĩnh vực trọng điểm và đăng tải quy định.
        </p>
    </div>
    <div style="display: flex; gap: 10px;">
        <button type="button" class="btn btn-secondary" onclick="openModal('modal-add-notice')">
            <?= ViewHelper::icon('file-plus') ?> Đăng Thông Báo / Biểu Mẫu
        </button>
        <button type="button" class="btn btn-secondary" onclick="openModal('modal-add-direction')">
            <?= ViewHelper::icon('plus') ?> Thêm Hướng NC
        </button>
        <button type="button" class="btn btn-primary" onclick="openModal('modal-add-dot')">
            <?= ViewHelper::icon('calendar') ?> Mở Đợt NCKH Mới
        </button>
    </div>
</div>

<!-- DANH SÁCH ĐỢT PHÁT ĐỘNG -->
<div class="card">
    <div class="card-header">
        <div class="card-title">
            <?= ViewHelper::icon('calendar') ?>
            <span>Các Đợt Phát Động NCKH Sinh Viên</span>
        </div>
        <span class="badge badge-primary"><?= count($dots) ?> đợt</span>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Mã Đợt</th>
                        <th>Tên Đợt NCKH</th>
                        <th>Năm Học</th>
                        <th>Hạn Đăng Ký</th>
                        <th>Hạn Nộp Tiến Độ</th>
                        <th>Hạn Nghiệm Thu</th>
                        <th>Số Đề Tài</th>
                        <th>Trạng Thái</th>
                        <th>Thao Tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dots as $d): ?>
                        <tr>
                            <td><strong style="color: #60a5fa;"><?= ViewHelper::e($d['MaDot']) ?></strong></td>
                            <td>
                                <strong style="color: #fff;"><?= ViewHelper::e($d['TenDot']) ?></strong>
                            </td>
                            <td><?= ViewHelper::e($d['NamHoc']) ?></td>
                            <td><span style="color: #f87171; font-weight: 700;"><?= ViewHelper::date($d['HanDangKy']) ?></span></td>
                            <td><?= ViewHelper::date($d['HanNopBaoCaoTD']) ?></td>
                            <td><?= ViewHelper::date($d['HanNopNghiemThu']) ?></td>
                            <td><span class="badge badge-secondary"><?= $d['TongSoDeTai'] ?> đề tài</span></td>
                            <td><?= ViewHelper::statusBadge($d['TrangThai']) ?></td>
                            <td>
                                <div style="display: flex; gap: 6px;">
                                    <button type="button" class="btn btn-sm btn-secondary" onclick='editDot(<?= json_encode($d, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>)' title="Chỉnh sửa đợt NCKH">
                                        <?= ViewHelper::icon('edit') ?> Sửa
                                    </button>
                                    <form method="POST" action="<?= App::url('index.php?action=cb-quanly-dot') ?>" style="display:inline;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa đợt NCKH <?= ViewHelper::e($d['MaDot']) ?>?');">
                                        <input type="hidden" name="sub_action" value="delete_dot">
                                        <input type="hidden" name="madot" value="<?= ViewHelper::e($d['MaDot']) ?>">
                                        <button type="submit" class="btn btn-sm btn-danger" <?= ((int)($d['TongSoDeTai'] ?? 0) > 0) ? 'title="Đợt đã có đề tài không thể xóa trực tiếp"' : 'title="Xóa đợt"' ?>>
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
    </div>
</div>

<div class="grid-cols-2">
    <!-- HƯỚNG NGHIÊN CỨU -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <?= ViewHelper::icon('search') ?>
                <span>Danh Mục Hướng Nghiên Cứu (<?= count($directions) ?>)</span>
            </div>
            <button type="button" class="btn btn-sm btn-secondary" onclick="openModal('modal-add-direction')">
                <?= ViewHelper::icon('plus') ?> Thêm
            </button>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Mã HNC</th>
                            <th>Tên Hướng Nghiên Cứu</th>
                            <th>Số Đề Tài</th>
                            <th>Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($directions as $h): ?>
                            <tr>
                                <td><code style="color: #38bdf8;"><?= ViewHelper::e($h['MaHuongNC']) ?></code></td>
                                <td>
                                    <strong style="color: #fff; display: block;"><?= ViewHelper::e($h['TenHuongNC']) ?></strong>
                                    <div style="font-size: 12px; color: var(--text-muted);"><?= ViewHelper::e($h['MoTa']) ?></div>
                                </td>
                                <td><span class="badge badge-secondary"><?= $h['SoDeTai'] ?></span></td>
                                <td>
                                    <div style="display: flex; gap: 6px;">
                                        <button type="button" class="btn btn-sm btn-secondary" onclick='editDirection(<?= json_encode($h, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>)' title="Chỉnh sửa hướng NC">
                                            <?= ViewHelper::icon('edit') ?>
                                        </button>
                                        <form method="POST" action="<?= App::url('index.php?action=cb-quanly-dot') ?>" style="display:inline;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa hướng nghiên cứu <?= ViewHelper::e($h['MaHuongNC']) ?>?');">
                                            <input type="hidden" name="sub_action" value="delete_direction">
                                            <input type="hidden" name="mahuongnc" value="<?= ViewHelper::e($h['MaHuongNC']) ?>">
                                            <button type="submit" class="btn btn-sm btn-danger" title="Xóa hướng NC">
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
        </div>
    </div>

    <!-- THÔNG BÁO & BIỂU MẪU ĐÃ ĐĂNG -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <?= ViewHelper::icon('bell') ?>
                <span>Thông Báo & Biểu Mẫu (<?= count($notices) ?>)</span>
            </div>
            <button type="button" class="btn btn-sm btn-secondary" onclick="openModal('modal-add-notice')">
                <?= ViewHelper::icon('file-plus') ?> Đăng bài
            </button>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Tiêu Đề</th>
                            <th>Loại</th>
                            <th>File</th>
                            <th>Ngày Đăng</th>
                            <th>Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($notices as $tb): ?>
                            <tr>
                                <td style="max-width: 220px;">
                                    <strong style="color: #fff;"><?= ViewHelper::e($tb['TieuDe']) ?></strong>
                                    <div style="font-size: 11px; color: var(--text-muted);">Đăng bởi: <?= ViewHelper::e($tb['TenNguoiDang']) ?></div>
                                </td>
                                <td>
                                    <span class="badge <?= $tb['Loai'] === 'BIEU_MAU' ? 'badge-primary' : 'badge-amber' ?>">
                                        <?= $tb['Loai'] ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if (!empty($tb['FileDinhKem'])): ?>
                                        <a href="<?= App::url('public/' . $tb['FileDinhKem']) ?>" target="_blank" class="btn btn-sm btn-secondary" style="font-size: 11px; padding: 3px 8px;">
                                            Tải file
                                        </a>
                                    <?php else: ?>
                                        <span style="color: var(--text-muted); font-size: 12px;">Không có</span>
                                    <?php endif; ?>
                                </td>
                                <td style="font-size: 12px;"><?= ViewHelper::date($tb['NgayDang']) ?></td>
                                <td>
                                    <div style="display: flex; gap: 6px;">
                                        <button type="button" class="btn btn-sm btn-secondary" onclick='editNotice(<?= json_encode($tb, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>)' title="Chỉnh sửa thông báo">
                                            <?= ViewHelper::icon('edit') ?>
                                        </button>
                                        <form method="POST" action="<?= App::url('index.php?action=cb-quanly-dot') ?>" style="display:inline;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa bài viết này?');">
                                            <input type="hidden" name="sub_action" value="delete_notice">
                                            <input type="hidden" name="id" value="<?= (int)($tb['MaThongBao'] ?? $tb['ID'] ?? 0) ?>">
                                            <button type="submit" class="btn btn-sm btn-danger" title="Xóa bài viết">
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
        </div>
    </div>
</div>

<!-- MODAL: TẠO ĐỢT NCKH MỚI -->
<div id="modal-add-dot" class="modal-backdrop">
    <div class="modal-container">
        <div class="modal-header">
            <div class="modal-title">Mở Đợt Phát Động NCKH Mới</div>
            <button type="button" class="modal-close" onclick="closeModal('modal-add-dot')">&times;</button>
        </div>
        <form method="POST" action="<?= App::url('index.php?action=cb-quanly-dot') ?>">
            <input type="hidden" name="sub_action" value="create_dot">
            <div class="modal-body">
                <div class="grid-cols-2">
                    <div class="form-group">
                        <label class="form-label">Mã Đợt NCKH <span class="required">*</span></label>
                        <input type="text" name="madot" class="form-control" placeholder="VD: NCKH_2026_2027_D1" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Năm Học <span class="required">*</span></label>
                        <input type="text" name="namhoc" class="form-control" placeholder="VD: 2026 - 2027" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Tên Đợt Phát Động NCKH <span class="required">*</span></label>
                    <input type="text" name="tendot" class="form-control" placeholder="VD: Đợt Nghiên Cứu Khoa Học Sinh Viên Năm Học 2026 - 2027" required>
                </div>

                <div class="grid-cols-2">
                    <div class="form-group">
                        <label class="form-label">Ngày Bắt Đầu Mở Đợt <span class="required">*</span></label>
                        <input type="date" name="ngaybatdau" class="form-control" value="<?= date('Y-m-d') ?>" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Hạn Chót Đăng Ký Đề Tài <span class="required">*</span></label>
                        <input type="date" name="handangky" class="form-control" required>
                    </div>
                </div>

                <div class="grid-cols-2">
                    <div class="form-group">
                        <label class="form-label">Hạn Nộp Báo Cáo Tiến Độ <span class="required">*</span></label>
                        <input type="date" name="hannopbaocaotd" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Hạn Nộp Báo Cáo Nghiệm Thu <span class="required">*</span></label>
                        <input type="date" name="hannopnghiemthu" class="form-control" required>
                    </div>
                </div>

                <div class="grid-cols-2">
                    <div class="form-group">
                        <label class="form-label">Ngày Kết Thúc Toàn Đợt <span class="required">*</span></label>
                        <input type="date" name="ngayketthuc" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Trạng Thái Đợt Ban Đầu</label>
                        <select name="trangthai" class="form-control">
                            <option value="MO_DANG_KY">Đang mở đăng ký</option>
                            <option value="DANG_THUC_HIEN">Đang thực hiện</option>
                            <option value="NGHIEM_THU">Nghiệm thu</option>
                            <option value="KET_THUC">Đã kết thúc</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modal-add-dot')">Hủy</button>
                <button type="submit" class="btn btn-primary">Khởi Tạo Đợt NCKH</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL: THÊM HƯỚNG NGHIÊN CỨU -->
<div id="modal-add-direction" class="modal-backdrop">
    <div class="modal-container">
        <div class="modal-header">
            <div class="modal-title">Thêm Hướng Nghiên Cứu Mới</div>
            <button type="button" class="modal-close" onclick="closeModal('modal-add-direction')">&times;</button>
        </div>
        <form method="POST" action="<?= App::url('index.php?action=cb-quanly-dot') ?>">
            <input type="hidden" name="sub_action" value="create_direction">
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Mã Hướng Nghiên Cứu <span class="required">*</span></label>
                    <input type="text" name="mahuongnc" class="form-control" placeholder="VD: HNC_FINTECH, HNC_ROBOTICS..." required>
                </div>
                <div class="form-group">
                    <label class="form-label">Tên Hướng Nghiên Cứu <span class="required">*</span></label>
                    <input type="text" name="tenhuongnc" class="form-control" placeholder="VD: Công nghệ Tài chính & Ngân hàng số" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Mô Tả Lĩnh Vực / Định Hướng Nghiên Cứu</label>
                    <textarea name="mota" class="form-control" rows="3" placeholder="Gợi ý các bài toán ứng dụng, công nghệ liên quan..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modal-add-direction')">Hủy</button>
                <button type="submit" class="btn btn-primary">Lưu Hướng NC</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL: ĐĂNG THÔNG BÁO / BIỂU MẪU -->
<div id="modal-add-notice" class="modal-backdrop">
    <div class="modal-container">
        <div class="modal-header">
            <div class="modal-title">Đăng Thông Báo / Biểu Mẫu / Quy Định</div>
            <button type="button" class="modal-close" onclick="closeModal('modal-add-notice')">&times;</button>
        </div>
        <form method="POST" action="<?= App::url('index.php?action=cb-quanly-dot') ?>" enctype="multipart/form-data">
            <input type="hidden" name="sub_action" value="add_notice">
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Tiêu Đề Bài Đăng <span class="required">*</span></label>
                    <input type="text" name="tieude" class="form-control" placeholder="VD: Mẫu 03: Phiếu đánh giá của hội đồng..." required>
                </div>

                <div class="grid-cols-2">
                    <div class="form-group">
                        <label class="form-label">Phân Loại <span class="required">*</span></label>
                        <select name="loai" class="form-control" required>
                            <option value="THONG_BAO">Thông báo chung</option>
                            <option value="BIEU_MAU">Biểu mẫu hướng dẫn</option>
                            <option value="QUY_DINH">Quy chế / Quy định</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Thuộc Đợt NCKH</label>
                        <select name="madot" class="form-control">
                            <option value="">-- Áp dụng cho tất cả các đợt --</option>
                            <?php foreach ($dots as $d): ?>
                                <option value="<?= ViewHelper::e($d['MaDot']) ?>"><?= ViewHelper::e($d['TenDot']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Nội Dung Chi Tiết</label>
                    <textarea name="noidung" class="form-control" rows="3" placeholder="Tóm tắt nội dung thông báo..."></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">File Đính Kèm (PDF / Word / Excel / ZIP)</label>
                    <input type="file" name="file_dinhkem" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modal-add-notice')">Hủy</button>
                <button type="submit" class="btn btn-primary">Đăng Tải Ngay</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL: CHỈNH SỬA ĐỢT NCKH -->
<div id="modal-edit-dot" class="modal-backdrop">
    <div class="modal-container">
        <div class="modal-header">
            <div class="modal-title">Cập Nhật Đợt NCKH</div>
            <button type="button" class="modal-close" onclick="closeModal('modal-edit-dot')">&times;</button>
        </div>
        <form method="POST" action="<?= App::url('index.php?action=cb-quanly-dot') ?>">
            <input type="hidden" name="sub_action" value="update_dot">
            <input type="hidden" name="madot" id="edit-dot-madot">
            <div class="modal-body">
                <div class="grid-cols-2">
                    <div class="form-group">
                        <label class="form-label">Mã Đợt NCKH</label>
                        <input type="text" id="edit-dot-display-madot" class="form-control" disabled style="opacity: 0.7;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Năm Học <span class="required">*</span></label>
                        <input type="text" name="namhoc" id="edit-dot-namhoc" class="form-control" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Tên Đợt Phát Động NCKH <span class="required">*</span></label>
                    <input type="text" name="tendot" id="edit-dot-tendot" class="form-control" required>
                </div>

                <div class="grid-cols-2">
                    <div class="form-group">
                        <label class="form-label">Ngày Bắt Đầu Mở Đợt <span class="required">*</span></label>
                        <input type="date" name="ngaybatdau" id="edit-dot-ngaybatdau" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Hạn Chót Đăng Ký Đề Tài <span class="required">*</span></label>
                        <input type="date" name="handangky" id="edit-dot-handangky" class="form-control" required>
                    </div>
                </div>

                <div class="grid-cols-2">
                    <div class="form-group">
                        <label class="form-label">Hạn Nộp Báo Cáo Tiến Độ <span class="required">*</span></label>
                        <input type="date" name="hannopbaocaotd" id="edit-dot-hannopbaocaotd" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Hạn Nộp Báo Cáo Nghiệm Thu <span class="required">*</span></label>
                        <input type="date" name="hannopnghiemthu" id="edit-dot-hannopnghiemthu" class="form-control" required>
                    </div>
                </div>

                <div class="grid-cols-2">
                    <div class="form-group">
                        <label class="form-label">Ngày Kết Thúc Toàn Đợt <span class="required">*</span></label>
                        <input type="date" name="ngayketthuc" id="edit-dot-ngayketthuc" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Trạng Thái Đợt</label>
                        <select name="trangthai" id="edit-dot-trangthai" class="form-control">
                            <option value="MO_DANG_KY">Đang mở đăng ký</option>
                            <option value="DANG_THUC_HIEN">Đang thực hiện</option>
                            <option value="NGHIEM_THU">Nghiệm thu</option>
                            <option value="KET_THUC">Đã kết thúc</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modal-edit-dot')">Hủy</button>
                <button type="submit" class="btn btn-primary">Lưu Thay Đổi</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL: CHỈNH SỬA HƯỚNG NGHIÊN CỨU -->
<div id="modal-edit-direction" class="modal-backdrop">
    <div class="modal-container">
        <div class="modal-header">
            <div class="modal-title">Cập Nhật Hướng Nghiên Cứu</div>
            <button type="button" class="modal-close" onclick="closeModal('modal-edit-direction')">&times;</button>
        </div>
        <form method="POST" action="<?= App::url('index.php?action=cb-quanly-dot') ?>">
            <input type="hidden" name="sub_action" value="update_direction">
            <input type="hidden" name="mahuongnc" id="edit-dir-mahuongnc">
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Mã Hướng Nghiên Cứu</label>
                    <input type="text" id="edit-dir-display-mahuongnc" class="form-control" disabled style="opacity: 0.7;">
                </div>
                <div class="form-group">
                    <label class="form-label">Tên Hướng Nghiên Cứu <span class="required">*</span></label>
                    <input type="text" name="tenhuongnc" id="edit-dir-tenhuongnc" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Mô Tả Lĩnh Vực / Định Hướng Nghiên Cứu</label>
                    <textarea name="mota" id="edit-dir-mota" class="form-control" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modal-edit-direction')">Hủy</button>
                <button type="submit" class="btn btn-primary">Lưu Hướng NC</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL: CHỈNH SỬA THÔNG BÁO / BIỂU MẪU -->
<div id="modal-edit-notice" class="modal-backdrop">
    <div class="modal-container">
        <div class="modal-header">
            <div class="modal-title">Cập Nhật Thông Báo / Biểu Mẫu</div>
            <button type="button" class="modal-close" onclick="closeModal('modal-edit-notice')">&times;</button>
        </div>
        <form method="POST" action="<?= App::url('index.php?action=cb-quanly-dot') ?>" enctype="multipart/form-data">
            <input type="hidden" name="sub_action" value="update_notice">
            <input type="hidden" name="id" id="edit-notice-id">
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Tiêu Đề Bài Đăng <span class="required">*</span></label>
                    <input type="text" name="tieude" id="edit-notice-tieude" class="form-control" required>
                </div>

                <div class="grid-cols-2">
                    <div class="form-group">
                        <label class="form-label">Phân Loại <span class="required">*</span></label>
                        <select name="loai" id="edit-notice-loai" class="form-control" required>
                            <option value="THONG_BAO">Thông báo chung</option>
                            <option value="BIEU_MAU">Biểu mẫu hướng dẫn</option>
                            <option value="QUY_DINH">Quy chế / Quy định</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Thuộc Đợt NCKH</label>
                        <select name="madot" id="edit-notice-madot" class="form-control">
                            <option value="">-- Áp dụng cho tất cả các đợt --</option>
                            <?php foreach ($dots as $d): ?>
                                <option value="<?= ViewHelper::e($d['MaDot']) ?>"><?= ViewHelper::e($d['TenDot']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Nội Dung Chi Tiết</label>
                    <textarea name="noidung" id="edit-notice-noidung" class="form-control" rows="3"></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Thay Đổi File Đính Kèm (nếu có)</label>
                    <input type="file" name="file_dinhkem" class="form-control">
                    <small style="font-size: 11px; color: var(--text-muted);">Bỏ trống nếu muốn giữ nguyên tệp tin đính kèm hiện tại.</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modal-edit-notice')">Hủy</button>
                <button type="submit" class="btn btn-primary">Lưu Bài Đăng</button>
            </div>
        </form>
    </div>
</div>

<script>
function editDot(dot) {
    document.getElementById('edit-dot-madot').value = dot.MaDot || '';
    document.getElementById('edit-dot-display-madot').value = dot.MaDot || '';
    document.getElementById('edit-dot-namhoc').value = dot.NamHoc || '';
    document.getElementById('edit-dot-tendot').value = dot.TenDot || '';
    document.getElementById('edit-dot-ngaybatdau').value = (dot.NgayBatDau || '').substring(0, 10);
    document.getElementById('edit-dot-handangky').value = (dot.HanDangKy || '').substring(0, 10);
    document.getElementById('edit-dot-hannopbaocaotd').value = (dot.HanNopBaoCaoTD || '').substring(0, 10);
    document.getElementById('edit-dot-hannopnghiemthu').value = (dot.HanNopNghiemThu || '').substring(0, 10);
    document.getElementById('edit-dot-ngayketthuc').value = (dot.NgayKetThuc || '').substring(0, 10);
    document.getElementById('edit-dot-trangthai').value = dot.TrangThai || 'MO_DANG_KY';
    openModal('modal-edit-dot');
}

function editDirection(dir) {
    document.getElementById('edit-dir-mahuongnc').value = dir.MaHuongNC || '';
    document.getElementById('edit-dir-display-mahuongnc').value = dir.MaHuongNC || '';
    document.getElementById('edit-dir-tenhuongnc').value = dir.TenHuongNC || '';
    document.getElementById('edit-dir-mota').value = dir.MoTa || '';
    openModal('modal-edit-direction');
}

function editNotice(notice) {
    document.getElementById('edit-notice-id').value = notice.MaThongBao || notice.ID || '';
    document.getElementById('edit-notice-tieude').value = notice.TieuDe || '';
    document.getElementById('edit-notice-loai').value = notice.Loai || 'THONG_BAO';
    document.getElementById('edit-notice-madot').value = notice.MaDot || '';
    document.getElementById('edit-notice-noidung').value = notice.NoiDung || '';
    openModal('modal-edit-notice');
}
</script>

<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>

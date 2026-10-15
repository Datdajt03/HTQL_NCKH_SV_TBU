<?php
use App\Config\App;
use App\Helpers\ViewHelper;

$pageTitle = "Chỉnh Sửa Hồ Sơ Đề Tài";
require dirname(__DIR__) . '/layouts/header.php';
?>
<div style="max-width: 960px; margin: 0 auto;">
    <div style="margin-bottom: 24px;">
        <h2 style="font-size: 20px; font-weight: 800; color: #fff; margin-bottom: 4px;">
            3. Chỉnh Sửa Hồ Sơ Đề Tài Theo Góp Ý
        </h2>
        <p style="color: var(--text-secondary); font-size: 14px; margin: 0;">
            Đề tài: <strong style="color: #60a5fa;"><?= ViewHelper::e($topic['MaDeTai']) ?></strong> - <?= ViewHelper::e($topic['TenDeTai']) ?>
        </p>
    </div>

    <!-- Ý KIẾN GÓP Ý TỪ CÁN BỘ / GVHD -->
    <?php if (!empty($topic['NhanXetCanBo']) || !empty($topic['NhanXetGVHD'])): ?>
        <div class="card" style="background: rgba(245, 158, 11, 0.08); border-color: rgba(245, 158, 11, 0.3);">
            <div class="card-header">
                <div class="card-title" style="color: #fde68a;">
                    <span><?= ViewHelper::icon('alert-triangle', '', 18) ?></span>
                    <span>Ý Kiến Góp Ý / Yêu Cầu Chỉnh Sửa</span>
                </div>
            </div>
            <div class="card-body">
                <?php if (!empty($topic['NhanXetCanBo'])): ?>
                    <div style="margin-bottom: 12px;">
                        <strong style="color: #f59e0b; font-size: 13px;">Ý kiến thẩm định từ Cán bộ Quản lý NCKH:</strong>
                        <div style="background: rgba(15, 23, 42, 0.6); padding: 12px 16px; border-radius: var(--radius-md); margin-top: 6px; color: #fff;">
                            <?= nl2br(ViewHelper::e($topic['NhanXetCanBo'])) ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (!empty($topic['NhanXetGVHD'])): ?>
                    <div>
                        <strong style="color: #60a5fa; font-size: 13px;">Nhận xét từ GVHD (<?= ViewHelper::e($topic['TenGVHD']) ?>):</strong>
                        <div style="background: rgba(15, 23, 42, 0.6); padding: 12px 16px; border-radius: var(--radius-md); margin-top: 6px; color: #fff;">
                            <?= nl2br(ViewHelper::e($topic['NhanXetGVHD'])) ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger">
            <span><?= ViewHelper::icon('alert-circle', '', 18) ?></span>
            <div><?= ViewHelper::e($error) ?></div>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?= App::url('index.php?action=sv-suahoso-detai&id=' . $topic['MaDeTai']) ?>" enctype="multipart/form-data">
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <span><?= ViewHelper::icon('edit', '', 18) ?></span>
                    <span>Nội Dung Hồ Sơ Cập Nhật</span>
                </div>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Tên Đề Tài NCKH <span class="required">*</span></label>
                    <input type="text" name="tendetai" class="form-control" value="<?= ViewHelper::e($topic['TenDeTai']) ?>" required>
                </div>

                <div class="grid-cols-2">
                    <div class="form-group">
                        <label class="form-label">Hướng Nghiên Cứu <span class="required">*</span></label>
                        <select name="mahuongnc" class="form-control" required>
                            <?php foreach ($directions as $h): ?>
                                <option value="<?= ViewHelper::e($h['MaHuongNC']) ?>" <?= $topic['MaHuongNC'] === $h['MaHuongNC'] ? 'selected' : '' ?>>
                                    <?= ViewHelper::e($h['TenHuongNC']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Kinh Phí Dự Trù (VNĐ)</label>
                        <input type="number" name="kinhphi" class="form-control" value="<?= (int)$topic['KinhPhiDuKien'] ?>" min="0" step="500000">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Mục Tiêu Nghiên Cứu <span class="required">*</span></label>
                    <textarea name="muctieu" class="form-control" rows="3" required><?= ViewHelper::e($topic['MucTieu']) ?></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Nội Dung Nghiên Cứu Chính <span class="required">*</span></label>
                    <textarea name="noidungnc" class="form-control" rows="4" required><?= ViewHelper::e($topic['NoiDungNC']) ?></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Sản Phẩm Dự Kiến <span class="required">*</span></label>
                    <textarea name="sanphamdukien" class="form-control" rows="3" required><?= ViewHelper::e($topic['SanPhamDuKien']) ?></textarea>
                </div>
            </div>
        </div>

        <!-- FILE CẬP NHẬT -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <span><?= ViewHelper::icon('paperclip', '', 18) ?></span>
                    <span>Tải Lên Phiếu Thuyết Minh / Đề Xuất Đã Chỉnh Sửa</span>
                </div>
            </div>
            <div class="card-body">
                <div class="grid-cols-2">
                    <div class="form-group">
                        <label class="form-label">File Phiếu Thuyết Minh Mới</label>
                        <input type="file" name="file_thuyet_minh_moi" class="form-control" accept=".pdf,.doc,.docx">
                        <div class="form-help">
                            File hiện tại: <a href="<?= App::url('public/' . $topic['FilePhieuThuyetMinh']) ?>" target="_blank" style="color: #60a5fa;">Tải file cũ</a> (Để trống nếu không thay đổi file).
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">File Phiếu Đề Xuất Mới</label>
                        <input type="file" name="file_de_xuat_moi" class="form-control" accept=".pdf,.doc,.docx">
                        <div class="form-help">
                            File hiện tại: <a href="<?= App::url('public/' . $topic['FilePhieuDeXuat']) ?>" target="_blank" style="color: #60a5fa;">Tải file cũ</a> (Để trống nếu không thay đổi file).
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- GIẢI TRÌNH SỬA ĐỔI -->
        <div class="card" style="border-color: rgba(59, 130, 246, 0.4);">
            <div class="card-header">
                <div class="card-title">
                    <span><?= ViewHelper::icon('file-text', '', 18) ?></span>
                    <span>Tóm Tắt Nội Dung Đã Sửa Đổi Theo Góp Ý <span class="required">*</span></span>
                </div>
            </div>
            <div class="card-body">
                <div class="form-group" style="margin-bottom: 0;">
                    <textarea name="noidungsua" class="form-control" rows="4" placeholder="Mô tả cụ thể những nội dung, số liệu, file mà nhóm đã tiếp thu và sửa đổi theo góp ý..." required></textarea>
                    <div class="form-help">Nội dung giải trình sẽ được lưu vào lịch sử chỉnh sửa để Cán bộ QL đối chiếu.</div>
                </div>
            </div>
        </div>

        <div style="display: flex; gap: 14px; justify-content: flex-end; margin-top: 24px; margin-bottom: 40px;">
            <a href="<?= App::url('index.php?action=sv-dashboard') ?>" class="btn btn-secondary btn-lg">
                Hủy Bỏ
            </a>
            <button type="submit" class="btn btn-primary btn-lg" style="display: inline-flex; align-items: center; gap: 8px;">
                <?= ViewHelper::icon('send', '', 18) ?> Nộp Bản Sửa Đổi & Chuyển Lại Thẩm Định
            </button>
        </div>
    </form>

    <!-- LỊCH SỬ CHỈNH SỬA TRƯỚC ĐÓ -->
    <?php if (!empty($editHistory)): ?>
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <span><?= ViewHelper::icon('clock', '', 18) ?></span>
                    <span>Lịch Sử Các Lần Chỉnh Sửa Hồ Sơ</span>
                </div>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <?php foreach ($editHistory as $ls): ?>
                        <div class="timeline-step">
                            <div class="timeline-dot done"></div>
                            <div class="timeline-content">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                                    <strong style="color: #60a5fa;">Lần chỉnh sửa thứ <?= $ls['LanChinhSua'] ?></strong>
                                    <span style="font-size: 12px; color: var(--text-muted);"><?= ViewHelper::datetime($ls['NgaySua']) ?></span>
                                </div>
                                <p style="font-size: 13px; color: var(--text-secondary); margin-bottom: 8px;">
                                    <?= nl2br(ViewHelper::e($ls['NoiDungSua'])) ?>
                                </p>
                                <div style="font-size: 12px; color: var(--text-muted);">
                                    Người gửi bản sửa: <strong><?= ViewHelper::e($ls['TenNguoiSua'] ?? $ls['NguoiSua']) ?></strong>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>

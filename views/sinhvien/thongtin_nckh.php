<?php
use App\Config\App;
use App\Helpers\ViewHelper;

$pageTitle = "Thông Tin NCKH & Biểu Mẫu Hướng Dẫn";
require dirname(__DIR__) . '/layouts/header.php';
?>
<div style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
    <div>
        <h2 style="font-size: 20px; font-weight: 800; color: var(--text-primary); margin-bottom: 4px;">1. Kế Hoạch & Biểu Mẫu Nghiên Cứu Khoa Học</h2>
        <p style="color: var(--text-secondary); font-size: 14px; margin: 0;">Thông tin các đợt phát động, quy định, biểu mẫu và danh sách các hướng nghiên cứu trọng điểm của Trường ĐH Tây Bắc (Sơn La).</p>
    </div>
    <div style="display: flex; gap: 8px;">
        <a href="<?= App::url('index.php?action=gioi-thieu#cua-so-web') ?>" class="btn btn-secondary" style="gap: 6px;" title="Cửa Sổ Trực Tuyến utb.edu.vn">
            <img src="<?= App::url('pic/Logo-Dai-Hoc-Tay-Bac.png') ?>" alt="Logo UTB" onerror="this.onerror=null; this.src='<?= App::url('public/images/Logo-Dai-Hoc-Tay-Bac.png') ?>';" style="width: 16px; height: 16px; border-radius: 50%; object-fit: cover;"> Website UTB (Sơn La) ↗
        </a>
        <a href="<?= App::url('index.php?action=sv-dangky-detai') ?>" class="btn btn-primary">
            <?= ViewHelper::icon('file-plus', '', 16) ?> Đăng Ký Đề Tài Mới
        </a>
    </div>
</div>

<!-- CÁC ĐỢT PHÁT ĐỘNG NCKH -->
<div class="card">
    <div class="card-header">
        <div class="card-title">
            <span><?= ViewHelper::icon('calendar', '', 18) ?></span>
            <span>Các Đợt Phát Động NCKH Sinh Viên</span>
        </div>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Mã Đợt</th>
                        <th>Tên Đợt Phát Động</th>
                        <th>Năm Học</th>
                        <th>Hạn Đăng Ký</th>
                        <th>Hạn Nộp Tiến Độ</th>
                        <th>Hạn Nghiệm Thu</th>
                        <th>Trạng Thái</th>
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
                            <td><?= ViewHelper::statusBadge($d['TrangThai']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="grid-cols-2">
    <!-- HƯỚNG NGHIÊN CỨU TRỌNG ĐIỂM -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <span><?= ViewHelper::icon('target', '', 18) ?></span>
                <span>Danh Mục Hướng Nghiên Cứu Trọng Điểm</span>
            </div>
        </div>
        <div class="card-body">
            <div style="display: flex; flex-direction: column; gap: 14px;">
                <?php foreach ($directions as $hnc): ?>
                    <div style="background: rgba(15, 23, 42, 0.4); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 16px;">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 6px;">
                            <strong style="color: #38bdf8; font-size: 15px;"><?= ViewHelper::e($hnc['TenHuongNC']) ?></strong>
                            <span class="badge badge-secondary" style="font-size: 11px;"><?= $hnc['SoDeTai'] ?> đề tài</span>
                        </div>
                        <p style="font-size: 13px; color: var(--text-secondary); margin: 0; line-height: 1.5;">
                            <?= ViewHelper::e($hnc['MoTa']) ?>
                        </p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- THÔNG BÁO, BIỂU MẪU & QUY ĐỊNH -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <span><?= ViewHelper::icon('file-text', '', 18) ?></span>
                <span>Thông Báo & Biểu Mẫu Hướng Dẫn</span>
            </div>
        </div>
        <div class="card-body">
            <div style="display: flex; flex-direction: column; gap: 14px;">
                <?php foreach ($notices as $tb): ?>
                    <div style="background: rgba(15, 23, 42, 0.4); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 16px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                            <span class="badge <?= $tb['Loai'] === 'BIEU_MAU' ? 'badge-primary' : ($tb['Loai'] === 'QUY_DINH' ? 'badge-amber' : 'badge-cyan') ?>">
                                <?= $tb['Loai'] === 'BIEU_MAU' ? 'Biểu Mẫu' : ($tb['Loai'] === 'QUY_DINH' ? 'Quy Định' : 'Thông Báo') ?>
                            </span>
                            <span style="font-size: 12px; color: var(--text-muted);"><?= ViewHelper::date($tb['NgayDang']) ?></span>
                        </div>
                        <h4 style="font-size: 14px; font-weight: 700; color: #fff; margin-bottom: 6px;">
                            <?= ViewHelper::e($tb['TieuDe']) ?>
                        </h4>
                        <?php if (!empty($tb['NoiDung'])): ?>
                            <p style="font-size: 13px; color: var(--text-secondary); margin-bottom: 12px;">
                                <?= ViewHelper::e($tb['NoiDung']) ?>
                            </p>
                        <?php endif; ?>
                        <?php if (!empty($tb['FileDinhKem'])): ?>
                            <a href="<?= App::url('public/' . $tb['FileDinhKem']) ?>" target="_blank" class="btn btn-sm btn-secondary" style="border-color: rgba(59, 130, 246, 0.4); color: #93c5fd; display: inline-flex; align-items: center; gap: 6px;">
                                <?= ViewHelper::icon('download', '', 14) ?> Tải xuống file đính kèm
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>

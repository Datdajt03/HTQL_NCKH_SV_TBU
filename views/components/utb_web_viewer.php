<?php
/**
 * UTB Web Viewer Component
 * Cửa sổ trình duyệt nhúng trực tiếp Cổng thông tin & Trang giới thiệu Trường Đại học Tây Bắc (Sơn La)
 * Domain: https://utb.edu.vn
 */
use App\Config\App;
use App\Helpers\ViewHelper;

$viewerId = $viewerId ?? 'utb';
$initialUrl = $initialUrl ?? App::UNIVERSITY_INTRO_URL;
$height = $height ?? '660px';
$title = $title ?? 'Cửa Sổ Website Trường Đại Học Tây Bắc (utb.edu.vn)';
$badgeText = $badgeText ?? 'SƠN LA • TRỰC TUYẾN';
$showQuickNav = $showQuickNav ?? true;
$isMainHero = $isMainHero ?? true;

if ($viewerId === 'portal') {
    $containerId = 'utb-browser-container';
    $iframeId = 'utb_web_iframe';
    $loadingId = 'utb-loading-overlay';
    $urlInputId = 'utb-url-input';
    $btnReloadId = 'btn-utb-reload';
    $btnHomeId = 'btn-utb-home';
    $btnCopyId = 'btn-copy-url';
    $copyStatusId = 'copy-status';
    $btnFsId = 'btn-toggle-fullscreen';
    $btnExtId = 'btn-open-external';
    $wrapperId = 'utb-iframe-wrapper';
} else {
    $containerId = $viewerId . '-browser-container';
    $iframeId = match ($viewerId) {
        'sv'     => 'sv_utb_iframe',
        'gv'     => 'gv_utb_iframe',
        'cb'     => 'cb_utb_iframe',
        default  => $viewerId . '_utb_iframe'
    };
    $loadingId = $viewerId . '-loading-overlay';
    $urlInputId = $viewerId . '-url-input';
    $btnReloadId = $viewerId . '-btn-reload';
    $btnHomeId = $viewerId . '-btn-home';
    $btnCopyId = $viewerId . '-btn-copy';
    $copyStatusId = $viewerId . '-copy-status';
    $btnFsId = $viewerId . '-btn-fullscreen';
    $btnExtId = $viewerId . '-btn-external';
    $wrapperId = $viewerId . '-iframe-wrapper';
}
?>
<div class="card utb-browser-window" id="<?= htmlspecialchars($containerId) ?>" style="padding: 0; overflow: hidden; border-radius: var(--radius-lg); border: 1px solid rgba(37,99,235,0.35); box-shadow: var(--shadow-lg); background: var(--bg-card); transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1); margin-bottom: 24px;">
    
    <!-- 1. BROWSER TOP CHROME / HEADER BAR -->
    <div style="background: var(--bg-secondary); border-bottom: 1px solid var(--border-color); padding: 10px 16px; display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap;">
        
        <!-- Cụm nút 3 chấm macOS & Tiêu đề nhận diện Sơn La -->
        <div style="display: flex; align-items: center; gap: 10px;">
            <div style="display: flex; gap: 6px; align-items: center; margin-right: 4px;" title="Cửa sổ Web Trực Tuyến UTB Sơn La">
                <span style="width: 12px; height: 12px; border-radius: 50%; background: #ef4444; display: inline-block; box-shadow: 0 0 6px rgba(239,68,68,0.4);"></span>
                <span style="width: 12px; height: 12px; border-radius: 50%; background: #f59e0b; display: inline-block; box-shadow: 0 0 6px rgba(245,158,11,0.4);"></span>
                <span style="width: 12px; height: 12px; border-radius: 50%; background: #10b981; display: inline-block; box-shadow: 0 0 6px rgba(16,185,129,0.4);"></span>
            </div>

            <!-- Nút Reload & Home -->
            <button type="button" class="btn btn-secondary btn-sm" id="<?= htmlspecialchars($btnReloadId) ?>" title="Tải lại trang web" style="padding: 4px 8px; font-size: 12px;">
                <?= ViewHelper::icon('refresh-cw', '', 14) ?>
            </button>
            <button type="button" class="btn btn-secondary btn-sm" id="<?= htmlspecialchars($btnHomeId) ?>" title="Về trang Giới thiệu mặc định (utb.edu.vn/gioi-thieu/)" style="padding: 4px 8px; font-size: 12px;">
                <?= ViewHelper::icon('home', '', 14) ?>
            </button>

            <div style="display: flex; align-items: center; gap: 8px; margin-left: 4px;">
                <div style="width: 22px; height: 22px; border-radius: 50%; overflow: hidden; background: #fff; border: 1px solid rgba(37,99,235,0.4); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <img src="<?= App::url('pic/Logo-Dai-Hoc-Tay-Bac.png') ?>" alt="Logo UTB" onerror="this.onerror=null; this.src='<?= App::url('public/images/Logo-Dai-Hoc-Tay-Bac.png') ?>';" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                <span style="font-weight: 800; font-size: 13.5px; color: var(--text-primary); white-space: nowrap;"><?= htmlspecialchars($title) ?></span>
                <span class="badge" style="background: rgba(16,185,129,0.15); color: #059669; font-weight: 700; font-size: 11px;"><?= htmlspecialchars($badgeText) ?></span>
            </div>
        </div>

        <!-- THANH ĐỊA CHỈ URL (ADDRESS BAR) -->
        <div style="flex: 1; min-width: 240px; max-width: 560px; display: flex; align-items: center; background: var(--bg-main); border: 1px solid var(--border-color); border-radius: 20px; padding: 4px 12px; gap: 8px;">
            <span style="color: #10b981; display: flex; align-items: center;" title="Kết nối HTTPS An toàn chính thức của Đại học Tây Bắc (utb.edu.vn)">
                <?= ViewHelper::icon('lock', '', 13) ?>
            </span>
            <input type="text" id="<?= htmlspecialchars($urlInputId) ?>" value="<?= htmlspecialchars($initialUrl) ?>" readonly style="flex: 1; background: transparent; border: none; outline: none; font-size: 12px; font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace; color: var(--text-primary); cursor: pointer;" title="Địa chỉ URL đang hiển thị. Click để copy.">
            <button type="button" id="<?= htmlspecialchars($btnCopyId) ?>" title="Sao chép liên kết" style="background: none; border: none; color: var(--text-muted); cursor: pointer; padding: 2px 4px; display: flex; align-items: center;">
                <?= ViewHelper::icon('copy', '', 13) ?>
            </button>
            <span id="<?= htmlspecialchars($copyStatusId) ?>" style="font-size: 11px; color: #10b981; font-weight: 700; display: none;">Đã copy!</span>
        </div>

        <!-- CÁC THAO TÁC CỬA SỔ PHỤ TRỢ (FULLSCREEN & EXTERNAL TAB) -->
        <div style="display: flex; align-items: center; gap: 8px;">
            <button type="button" class="btn btn-secondary btn-sm btn-utb-fs" id="<?= htmlspecialchars($btnFsId) ?>" title="Phóng to toàn màn hình cửa sổ UTB (Bấm ESC để thoát)" style="padding: 5px 10px; font-size: 12px; gap: 5px;">
                <span class="fs-icon"><?= ViewHelper::icon('maximize', '', 13) ?></span>
                <span class="fs-text">Toàn màn hình</span>
            </button>
            <a href="<?= htmlspecialchars($initialUrl) ?>" target="_blank" rel="noopener" id="<?= htmlspecialchars($btnExtId) ?>" class="btn btn-primary btn-sm" title="Mở trang này trên tab trình duyệt riêng" style="padding: 5px 12px; font-size: 12px; gap: 6px;">
                <?= ViewHelper::icon('external-link', '', 13) ?> Mở Tab Mới ↗
            </a>
        </div>
    </div>

    <?php if ($showQuickNav): ?>
        <!-- 2. QUICK NAVIGATION CHIPS (CÁC CHUYÊN MỤC TRỌNG ĐIỂM UTB SƠN LA) -->
        <div style="background: rgba(15, 23, 42, 0.35); border-bottom: 1px solid var(--border-color); padding: 8px 16px; display: flex; gap: 6px; overflow-x: auto; align-items: center;" class="utb-quick-links-bar" data-viewer="<?= htmlspecialchars($viewerId) ?>">
            <span style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; white-space: nowrap; margin-right: 4px;">
                Mục nhanh:
            </span>
            <button type="button" class="btn btn-sm btn-secondary utb-quick-nav active" data-url="https://utb.edu.vn/gioi-thieu/" style="font-size: 11.5px; padding: 4px 9px;">
                🏛️ Giới thiệu chung
            </button>
            <button type="button" class="btn btn-sm btn-secondary utb-quick-nav" data-url="https://utb.edu.vn/gioi-thieu/su-mang-tam-nhin/" style="font-size: 11.5px; padding: 4px 9px;">
                🎯 Sứ mạng - Tầm nhìn
            </button>
            <button type="button" class="btn btn-sm btn-secondary utb-quick-nav" data-url="https://utb.edu.vn/gioi-thieu/lich-su/" style="font-size: 11.5px; padding: 4px 9px;">
                📜 Lịch sử truyền thống
            </button>
            <button type="button" class="btn btn-sm btn-secondary utb-quick-nav" data-url="https://utb.edu.vn/gioi-thieu/cac-khoa/" style="font-size: 11.5px; padding: 4px 9px;">
                📚 7 Khoa Sơn La
            </button>
            <button type="button" class="btn btn-sm btn-secondary utb-quick-nav" data-url="https://utb.edu.vn/khcn/nckh-sinh-vien/" style="font-size: 11.5px; padding: 4px 9px;">
                🔬 NCKH Sinh viên UTB
            </button>
            <button type="button" class="btn btn-sm btn-secondary utb-quick-nav" data-url="https://utb.edu.vn/" style="font-size: 11.5px; padding: 4px 9px;">
                🌐 Trang chủ utb.edu.vn
            </button>
        </div>
    <?php endif; ?>

    <!-- 3. IFRAME DISPLAY WRAPPER -->
    <div style="position: relative; width: 100%; height: <?= htmlspecialchars($height) ?>; background: #ffffff;" id="<?= htmlspecialchars($wrapperId) ?>" class="utb-iframe-wrapper">
        
        <!-- LOADING SPINNER HIỆN ĐẠI CÓ TÊN TRƯỜNG -->
        <div id="<?= htmlspecialchars($loadingId) ?>" style="position: absolute; inset: 0; background: rgba(15, 23, 42, 0.88); backdrop-filter: blur(4px); display: flex; flex-direction: column; align-items: center; justify-content: center; z-index: 10; color: #fff; transition: opacity 0.3s ease;">
            <div style="width: 44px; height: 44px; border: 4px solid rgba(59, 130, 246, 0.2); border-top-color: #3b82f6; border-radius: 50%; animation: spin 0.8s linear infinite; margin-bottom: 12px;"></div>
            <div style="font-weight: 700; font-size: 14.5px; margin-bottom: 3px;">Đang tải Cổng Thông Tin utb.edu.vn...</div>
            <div style="font-size: 12px; color: #94a3b8;">Trường Đại học Tây Bắc &bull; Phường Quyết Tâm, TP. Sơn La</div>
        </div>

        <!-- IFRAME NHÚNG CHÍNH THỨC -->
        <iframe 
            id="<?= htmlspecialchars($iframeId) ?>"
            name="<?= htmlspecialchars($viewerId) ?>_frame"
            src="<?= htmlspecialchars($initialUrl) ?>" 
            style="width: 100%; height: 100%; border: none; display: block;"
            title="Website Trường Đại học Tây Bắc (utb.edu.vn)"
            loading="lazy"
            allowfullscreen
            onload="var ld = document.getElementById(<?= json_encode($loadingId) ?>); if(ld) ld.style.display='none';"
        ></iframe>

        <!-- THANH HỖ TRỢ DƯỚI ĐÁY CỬA SỔ -->
        <div style="position: absolute; bottom: 0; left: 0; right: 0; padding: 6px 16px; background: rgba(15, 23, 42, 0.92); backdrop-filter: blur(8px); border-top: 1px solid rgba(255,255,255,0.1); display: flex; justify-content: space-between; align-items: center; font-size: 12px; color: #94a3b8; z-index: 5;">
            <div>
                🏛️ <strong>Trường Đại học Tây Bắc (Sơn La)</strong> &bull; Nguồn: <a href="https://utb.edu.vn" target="_blank" rel="noopener" style="color: #60a5fa; text-decoration: none;">https://utb.edu.vn</a>
            </div>
            <div style="display: flex; gap: 12px; align-items: center;">
                <span>Nếu mạng chậm, bạn có thể</span>
                <a href="<?= htmlspecialchars($initialUrl) ?>" target="_blank" rel="noopener" style="color: #38bdf8; font-weight: 700; text-decoration: none;">Mở trực tiếp tab mới ↗</a>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    const viewerId = <?= json_encode($viewerId) ?>;
    const defaultUrl = <?= json_encode($initialUrl) ?>;
    const container = document.getElementById(<?= json_encode($containerId) ?>);
    const iframe = document.getElementById(<?= json_encode($iframeId) ?>);
    const loadingOverlay = document.getElementById(<?= json_encode($loadingId) ?>);
    const urlInput = document.getElementById(<?= json_encode($urlInputId) ?>);
    const btnReload = document.getElementById(<?= json_encode($btnReloadId) ?>);
    const btnHome = document.getElementById(<?= json_encode($btnHomeId) ?>);
    const btnCopy = document.getElementById(<?= json_encode($btnCopyId) ?>);
    const copyStatus = document.getElementById(<?= json_encode($copyStatusId) ?>);
    const btnFs = document.getElementById(<?= json_encode($btnFsId) ?>);
    const btnExt = document.getElementById(<?= json_encode($btnExtId) ?>);
    const wrapper = document.getElementById(<?= json_encode($wrapperId) ?>);
    const defaultHeight = <?= json_encode($height) ?>;

    if (!container || !iframe) return;

    function navigate(url) {
        if (loadingOverlay) loadingOverlay.style.display = 'flex';
        iframe.src = url;
        if (urlInput) urlInput.value = url;
        if (btnExt) btnExt.href = url;

        // Cập nhật active tab
        const navBar = container.querySelector('.utb-quick-links-bar');
        if (navBar) {
            navBar.querySelectorAll('.utb-quick-nav').forEach(btn => {
                if (btn.getAttribute('data-url') === url) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            });
        }
    }

    if (btnReload) {
        btnReload.addEventListener('click', function() {
            if (loadingOverlay) loadingOverlay.style.display = 'flex';
            try {
                iframe.contentWindow.location.reload();
            } catch (e) {
                iframe.src = iframe.src;
            }
        });
    }

    if (btnHome) {
        btnHome.addEventListener('click', function() {
            navigate(defaultUrl);
        });
    }

    if (btnCopy && urlInput) {
        btnCopy.addEventListener('click', function() {
            navigator.clipboard.writeText(urlInput.value).then(() => {
                if (copyStatus) {
                    copyStatus.style.display = 'inline';
                    setTimeout(() => { copyStatus.style.display = 'none'; }, 2200);
                }
            }).catch(() => {
                urlInput.select();
                document.execCommand('copy');
                if (copyStatus) {
                    copyStatus.style.display = 'inline';
                    setTimeout(() => { copyStatus.style.display = 'none'; }, 2200);
                }
            });
        });
    }

    // Các quick nav chips
    const quickBtns = container.querySelectorAll('.utb-quick-nav');
    quickBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const url = this.getAttribute('data-url');
            if (url) navigate(url);
        });
    });

    // In-place Fullscreen Toggle
    let isFs = false;
    if (btnFs) {
        btnFs.addEventListener('click', function() {
            isFs = !isFs;
            if (isFs) {
                container.style.position = 'fixed';
                container.style.top = '0';
                container.style.left = '0';
                container.style.right = '0';
                container.style.bottom = '0';
                container.style.width = '100vw';
                container.style.height = '100vh';
                container.style.zIndex = '99999';
                container.style.margin = '0';
                container.style.borderRadius = '0';
                if (wrapper) wrapper.style.height = 'calc(100vh - 98px)';
                document.body.style.overflow = 'hidden';
                const fsText = btnFs.querySelector('.fs-text');
                if (fsText) fsText.textContent = 'Thu nhỏ (ESC)';
            } else {
                container.style.position = '';
                container.style.top = '';
                container.style.left = '';
                container.style.right = '';
                container.style.bottom = '';
                container.style.width = '';
                container.style.height = '';
                container.style.zIndex = '';
                container.style.margin = '0 0 24px 0';
                container.style.borderRadius = 'var(--radius-lg)';
                if (wrapper) wrapper.style.height = defaultHeight;
                document.body.style.overflow = '';
                const fsText = btnFs.querySelector('.fs-text');
                if (fsText) fsText.textContent = 'Toàn màn hình';
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && isFs) {
                btnFs.click();
            }
        });
    }

    // Timeout bảo đảm ẩn overlay sau tối đa 4 giây
    setTimeout(() => {
        if (loadingOverlay) loadingOverlay.style.display = 'none';
    }, 4000);
})();
</script>

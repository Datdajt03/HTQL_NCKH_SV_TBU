<?php
use App\Config\App;
use App\Helpers\AuthHelper;
?>
    <?php if (AuthHelper::isLoggedIn()): ?>
            </div><!-- .content-body -->
            <footer style="padding: 20px 32px; border-top: 1px solid var(--border-color); font-size: 13px; color: var(--text-muted); display: flex; justify-content: space-between; align-items: center; background: rgba(15, 23, 42, 0.4);">
                <div>
                    <strong><?= App::APP_NAME ?></strong> &copy; <?= date('Y') ?> &bull; <strong>Trường Đại học Tây Bắc (Sơn La)</strong>.
                </div>
                <div>
                    Phiên bản <?= App::VERSION ?> | <a href="https://utb.edu.vn" target="_blank" rel="noopener" style="color: var(--accent-blue); text-decoration: none;">utb.edu.vn</a> &bull; Phòng QL Khoa học & Hợp tác Quốc tế
                </div>
            </footer>
        </div><!-- .app-main -->
    <?php else: ?>
        </div><!-- non-logged in container -->
    <?php endif; ?>
</div><!-- .app-container -->

<script src="<?= App::url('public/js/app.js') ?>"></script>
</body>
</html>

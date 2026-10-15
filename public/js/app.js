/**
 * HỆ THỐNG QUẢN LÝ NGHIÊN CỨU KHOA HỌC SINH VIÊN
 * Client-side JavaScript interactions & Dynamic UI
 */

document.addEventListener('DOMContentLoaded', function () {
    // 0. THEME CONTROLLER (DARK / LIGHT MODE)
    const themeToggleBtn = document.getElementById('theme-toggle');
    const themeLabel = document.getElementById('theme-label');

    function updateThemeUI(theme) {
        document.documentElement.setAttribute('data-theme', theme);
        if (themeLabel) {
            themeLabel.textContent = (theme === 'dark') ? 'Chế độ Sáng' : 'Chế độ Tối';
        }
        if (themeToggleBtn) {
            themeToggleBtn.setAttribute('title', (theme === 'dark') ? 'Chuyển sang giao diện Sáng' : 'Chuyển sang giao diện Tối');
        }
    }

    const currentTheme = document.documentElement.getAttribute('data-theme') || localStorage.getItem('theme') || 'dark';
    updateThemeUI(currentTheme);

    if (themeToggleBtn) {
        themeToggleBtn.addEventListener('click', function () {
            const activeTheme = document.documentElement.getAttribute('data-theme') || 'dark';
            const nextTheme = (activeTheme === 'dark') ? 'light' : 'dark';
            
            localStorage.setItem('theme', nextTheme);
            document.cookie = "theme=" + nextTheme + "; path=/; max-age=31536000; SameSite=Lax";
            
            updateThemeUI(nextTheme);
        });
    }

    // 1. TỰ ĐỘNG ĐÓNG / ẨN FLASH ALERT SAU 5 GIÂY
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function (alert) {
        setTimeout(function () {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 6000);
    });

    // 2. MODAL DIALOG CONTROLLER
    window.openModal = function (modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('show');
            document.body.style.overflow = 'hidden';
        }
    };

    window.closeModal = function (modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('show');
            document.body.style.overflow = '';
        }
    };

    // Đóng khi click ra ngoài backdrop
    document.querySelectorAll('.modal-backdrop').forEach(function (backdrop) {
        backdrop.addEventListener('click', function (e) {
            if (e.target === backdrop) {
                backdrop.classList.remove('show');
                document.body.style.overflow = '';
            }
        });
    });

    // 3. TABS SWITCHER
    const tabBtns = document.querySelectorAll('.tab-btn');
    tabBtns.forEach(function (btn) {
        btn.addEventListener('click', function () {
            const targetId = btn.getAttribute('data-tab');
            const parent = btn.closest('.tabs-wrapper') || document;

            parent.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            parent.querySelectorAll('.tab-content').forEach(c => c.style.display = 'none');

            btn.classList.add('active');
            const targetContent = parent.querySelector('#' + targetId);
            if (targetContent) {
                targetContent.style.display = 'block';
            }
        });
    });

    // 4. TÍNH ĐIỂM TỔNG CỘNG TỰ ĐỘNG TRONG PHIẾU CHẤM ĐIỂM
    const scoreInputs = document.querySelectorAll('.calc-score-input');
    const totalScoreDisplay = document.getElementById('calc-total-score');
    
    if (scoreInputs.length > 0 && totalScoreDisplay) {
        function calculateTotal() {
            let sum = 0;
            scoreInputs.forEach(input => {
                const val = parseFloat(input.value) || 0;
                sum += val;
            });
            totalScoreDisplay.textContent = sum.toFixed(2);

            // Gợi ý xếp loại
            const rankHint = document.getElementById('calc-rank-hint');
            if (rankHint) {
                if (sum >= 90) rankHint.textContent = "Xuất Sắc (>= 90)";
                else if (sum >= 80) rankHint.textContent = "Tốt (80 - 89)";
                else if (sum >= 70) rankHint.textContent = "Khá (70 - 79)";
                else if (sum >= 50) rankHint.textContent = "Đạt (50 - 69)";
                else rankHint.textContent = "Không Đạt (< 50)";
            }
        }

        scoreInputs.forEach(input => {
            input.addEventListener('input', calculateTotal);
        });
        calculateTotal();
    }

    // 5. THÊM THÀNH VIÊN ĐỀ TÀI ĐỘNG (DANG KY DE TAI)
    const addMemberBtn = document.getElementById('btn-add-member');
    const memberContainer = document.getElementById('member-list-container');
    if (addMemberBtn && memberContainer) {
        let memberIdx = memberContainer.querySelectorAll('.member-row').length;
        
        addMemberBtn.addEventListener('click', function () {
            if (memberContainer.querySelectorAll('.member-row').length >= 4) {
                alert("Một đề tài NCKH chỉ được có tối đa 5 sinh viên (1 trưởng nhóm + 4 thành viên).");
                return;
            }

            const row = document.createElement('div');
            row.className = 'member-row card';
            row.style.padding = '14px';
            row.style.marginBottom = '12px';
            row.style.background = 'rgba(15, 23, 42, 0.4)';
            row.innerHTML = `
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                    <strong style="color: #60a5fa; font-size: 13px;">Thành viên tham gia #${memberIdx + 1}</strong>
                    <button type="button" class="btn btn-sm btn-danger btn-remove-row" style="padding: 2px 8px; font-size: 11px; display: inline-flex; align-items: center;">
                        <svg style="width:12px;height:12px;margin-right:3px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg> Xóa
                    </button>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <div>
                        <label class="form-label" style="font-size: 12px;">Mã Sinh Viên / Họ Tên</label>
                        <input type="text" name="extra_members[${memberIdx}][masv]" class="form-control" placeholder="VD: SV002 hoặc Mã SV" required>
                    </div>
                    <div>
                        <label class="form-label" style="font-size: 12px;">Nhiệm Vụ Cụ Thể</label>
                        <input type="text" name="extra_members[${memberIdx}][nhiemvu]" class="form-control" placeholder="VD: Lập trình frontend / Thu thập dữ liệu" required>
                    </div>
                </div>
            `;
            memberContainer.appendChild(row);
            memberIdx++;

            row.querySelector('.btn-remove-row').addEventListener('click', function () {
                row.remove();
            });
        });
    }

    // 6. THÊM SẢN PHẨM NGHIÊN CỨU ĐỘNG (BAO CAO TONG KET)
    const addProductBtn = document.getElementById('btn-add-product');
    const productContainer = document.getElementById('product-list-container');
    if (addProductBtn && productContainer) {
        let productIdx = productContainer.querySelectorAll('.product-row').length;

        addProductBtn.addEventListener('click', function () {
            const row = document.createElement('div');
            row.className = 'product-row card';
            row.style.padding = '16px';
            row.style.marginBottom = '16px';
            row.style.background = 'rgba(15, 23, 42, 0.4)';
            row.innerHTML = `
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                    <strong style="color: #a78bfa; font-size: 13px;">Sản phẩm nghiên cứu #${productIdx + 1}</strong>
                    <button type="button" class="btn btn-sm btn-danger btn-remove-row" style="padding: 2px 8px; font-size: 11px; display: inline-flex; align-items: center;">
                        <svg style="width:12px;height:12px;margin-right:3px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg> Xóa
                    </button>
                </div>
                <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 12px; margin-bottom: 10px;">
                    <div>
                        <label class="form-label" style="font-size: 12px;">Tên sản phẩm <span class="required">*</span></label>
                        <input type="text" name="products[${productIdx}][ten]" class="form-control" placeholder="VD: Phần mềm AI, Bài báo khoa học..." required>
                    </div>
                    <div>
                        <label class="form-label" style="font-size: 12px;">Loại sản phẩm <span class="required">*</span></label>
                        <select name="products[${productIdx}][loai]" class="form-control" required>
                            <option value="PHAN_MEM">Phần mềm ứng dụng</option>
                            <option value="BAI_BAO_KHOA_HOC">Bài báo khoa học</option>
                            <option value="MO_HINH">Mô hình / Thiết bị</option>
                            <option value="QUY_TRINH">Quy trình công nghệ</option>
                        </select>
                    </div>
                </div>
                <div style="margin-bottom: 10px;">
                    <label class="form-label" style="font-size: 12px;">Mô tả sản phẩm</label>
                    <textarea name="products[${productIdx}][mota]" class="form-control" rows="2" placeholder="Tính năng, thông số kỹ thuật hoặc tóm tắt bài báo"></textarea>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <div>
                        <label class="form-label" style="font-size: 12px;">Liên kết minh chứng (GitHub / URL Demo / Bài báo)</label>
                        <input type="url" name="products[${productIdx}][link]" class="form-control" placeholder="https://github.com/...">
                    </div>
                    <div>
                        <label class="form-label" style="font-size: 12px;">File minh chứng (PDF/ZIP)</label>
                        <input type="file" name="product_file_${productIdx}" class="form-control">
                    </div>
                </div>
            `;
            productContainer.appendChild(row);
            productIdx++;

            row.querySelector('.btn-remove-row').addEventListener('click', function () {
                row.remove();
            });
        });
    }
});

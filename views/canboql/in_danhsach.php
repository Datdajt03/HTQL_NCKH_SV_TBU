<?php
use App\Config\App;
use App\Helpers\ViewHelper;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Sách Khen Thưởng NCKH Sinh Viên - Bản In</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 20mm 15mm 20mm 20mm;
        }
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 13pt;
            line-height: 1.4;
            color: #000;
            background: #fff;
            margin: 0;
            padding: 24px;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }
        .header-table td {
            vertical-align: top;
            text-align: center;
        }
        .title {
            text-align: center;
            font-size: 16pt;
            font-weight: bold;
            margin: 20px 0 10px;
            text-transform: uppercase;
        }
        .subtitle {
            text-align: center;
            font-size: 12pt;
            font-style: italic;
            margin-bottom: 24px;
        }
        table.content-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
        }
        table.content-table th, table.content-table td {
            border: 1px solid #000;
            padding: 8px 6px;
            font-size: 11pt;
        }
        table.content-table th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }
        .signatures {
            width: 100%;
            margin-top: 40px;
            border-collapse: collapse;
        }
        .signatures td {
            vertical-align: top;
            text-align: center;
            width: 50%;
        }
        .no-print-bar {
            background: #0f172a;
            color: white;
            padding: 12px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: -24px -24px 24px -24px;
            font-family: sans-serif;
        }
        @media print {
            .no-print-bar {
                display: none;
            }
            body {
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="no-print-bar">
        <div>
            <strong>Xem trước bản in / Xuất PDF:</strong> Danh sách khen thưởng Nghiên cứu Khoa học Sinh viên
        </div>
        <div>
            <button onclick="window.print()" style="display: inline-flex; align-items: center; padding: 8px 16px; background: #2563eb; color: #fff; border: none; border-radius: 6px; font-weight: bold; cursor: pointer;">
                <svg style="width:16px;height:16px;margin-right:6px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg> In Văn Bản / Lưu PDF
            </button>
            <button onclick="window.close()" style="padding: 8px 16px; background: #475569; color: #fff; border: none; border-radius: 6px; cursor: pointer; margin-left: 8px;">
                Đóng
            </button>
        </div>
    </div>

    <!-- TIÊU NGỮ QUỐC GIA -->
    <table class="header-table">
        <tr>
            <td style="width: 45%;">
                BỘ GIÁO DỤC VÀ ĐÀO TẠO<br>
                <strong>TRƯỜNG ĐẠI HỌC CÔNG NGHỆ</strong><br>
                <strong>PHÒNG QLKH & HTQT</strong><br>
                <div style="width: 120px; border-bottom: 1px solid #000; margin: 4px auto 0;"></div>
            </td>
            <td style="width: 55%;">
                <strong>CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM</strong><br>
                <strong>Độc lập - Tự do - Hạnh phúc</strong><br>
                <div style="width: 140px; border-bottom: 1px solid #000; margin: 4px auto 0;"></div>
                <div style="font-style: italic; margin-top: 6px; font-size: 11pt;">Hà Nội, ngày <?= date('d') ?> tháng <?= date('m') ?> năm <?= date('Y') ?></div>
            </td>
        </tr>
    </table>

    <div class="title">
        DANH SÁCH ĐỀ TÀI NGHIÊN CỨU KHOA HỌC SINH VIÊN<br>
        ĐẠT GIẢI THƯỞNG VÀ KHEN THƯỞNG CẤP TRƯỜNG
    </div>
    <div class="subtitle">
        <?= $selectedDot ? 'Áp dụng cho: ' . ViewHelper::e($selectedDot['TenDot']) . ' (' . ViewHelper::e($selectedDot['NamHoc']) . ')' : 'Toàn trường' ?>
    </div>

    <table class="content-table">
        <thead>
            <tr>
                <th style="width: 5%;">STT</th>
                <th style="width: 12%;">Mã Đề Tài</th>
                <th style="width: 33%;">Tên Đề Tài NCKH</th>
                <th style="width: 18%;">Sinh Viên Chủ Nhiệm (Lớp)</th>
                <th style="width: 14%;">Cán Bộ Hướng Dẫn</th>
                <th style="width: 8%;">Điểm</th>
                <th style="width: 10%;">Danh Hiệu</th>
            </tr>
        </thead>
        <tbody>
            <?php $stt = 1; foreach ($awards as $a): ?>
                <tr>
                    <td style="text-align: center;"><?= $stt++ ?></td>
                    <td style="text-align: center; font-weight: bold;"><?= ViewHelper::e($a['MaDeTai']) ?></td>
                    <td><?= ViewHelper::e($a['TenDeTai']) ?></td>
                    <td>
                        <strong><?= ViewHelper::e($a['TenTruongNhom']) ?></strong><br>
                        <small style="color: #444;">Lớp: <?= ViewHelper::e($a['LopTruongNhom']) ?></small>
                    </td>
                    <td><?= ViewHelper::e($a['TenGVHD']) ?></td>
                    <td style="text-align: center; font-weight: bold;"><?= number_format((float)$a['DiemTongKet'], 2) ?></td>
                    <td style="text-align: center; font-weight: bold;">
                        <?= match($a['DanhHieuKhenThuong']) {
                            'GIAI_NHAT' => 'Giải Nhất',
                            'GIAI_NHI' => 'Giải Nhì',
                            'GIAI_BA' => 'Giải Ba',
                            'GIAI_KHUYEN_KHICH' => 'Khuyến Khích',
                            default => '-'
                        } ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <table class="signatures">
        <tr>
            <td>
                <strong>TRƯỞNG PHÒNG QLKH & HTQT</strong><br>
                <i>(Ký và ghi rõ họ tên)</i>
                <br><br><br><br><br>
                <strong>Nguyễn Văn Cán</strong>
            </td>
            <td>
                <strong>HIỆU TRƯỞNG</strong><br>
                <i>(Ký, đóng dấu và ghi rõ họ tên)</i>
                <br><br><br><br><br>
                <strong>PGS. TS. Trần Đức Dũng</strong>
            </td>
        </tr>
    </table>
</body>
</html>

<?php
namespace App\Helpers;

class UploadHelper {
    const ALLOWED_EXTENSIONS = ['pdf', 'doc', 'docx', 'zip', 'rar', 'png', 'jpg', 'jpeg', 'xlsx', 'xls', 'pptx', 'ppt'];
    const MAX_FILE_SIZE = 50 * 1024 * 1024; // 50MB

    public static function upload(string $inputName, string $folder = ''): ?string {
        if (!isset($_FILES[$inputName]) || $_FILES[$inputName]['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        $file = $_FILES[$inputName];
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new \Exception("Lỗi khi tải file lên (Mã lỗi: " . $file['error'] . ")");
        }

        if ($file['size'] > self::MAX_FILE_SIZE) {
            throw new \Exception("Dung lượng file vượt quá giới hạn 50MB.");
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, self::ALLOWED_EXTENSIONS)) {
            throw new \Exception("Định dạng file không được hỗ trợ (.{$ext}). Chỉ chấp nhận PDF, Word, Excel, PowerPoint, ZIP, RAR, Ảnh.");
        }

        $targetDir = dirname(__DIR__, 2) . '/public/uploads';
        if ($folder !== '') {
            $targetDir .= '/' . trim($folder, '/');
        }

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        // Tạo tên file ngẫu nhiên an toàn, tránh trùng lặp
        $originalName = pathinfo($file['name'], PATHINFO_FILENAME);
        $cleanName = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $originalName);
        $fileName = $cleanName . '_' . date('Ymd_His') . '_' . substr(bin2hex(random_bytes(4)), 0, 6) . '.' . $ext;
        $targetPath = $targetDir . '/' . $fileName;

        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            throw new \Exception("Không thể lưu file tải lên vào máy chủ.");
        }

        $relativePath = 'uploads/' . ($folder !== '' ? trim($folder, '/') . '/' : '') . $fileName;
        return $relativePath;
    }
}

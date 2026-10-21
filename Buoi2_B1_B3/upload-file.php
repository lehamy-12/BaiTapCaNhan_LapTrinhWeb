<?php
$message = '';
$uploadedFilePath = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['avatar'])) {
    $file = $_FILES['avatar'];

    // 1. Cấu hình quy định upload
    $maxFileSize = 2 * 1024 * 1024; // 2MB
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $uploadDir = 'uploads/'; // Thư mục lưu trữ

    // Tạo thư mục nếu chưa tồn tại
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // Kiểm tra lỗi hệ thống khi upload file
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $message = 'Lỗi trong quá trình tải file lên server.';
    } 
    // KIỂM TRA BẮT BUỘC 1: Kích thước file
    elseif ($file['size'] > $maxFileSize) {
        $message = 'Kích thước file vượt quá giới hạn cho phép (Tối đa 2MB).';
    } 
    else {
        // Lấy đuôi file và kiểm tra MIME type
        $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        // KIỂM TRA BẮT BUỘC 2: Định dạng đuôi file và MIME Type
        if (!in_array($fileExtension, $allowedExtensions) || !in_array($mimeType, $allowedMimeTypes)) {
            $message = 'Chỉ chấp nhận các định dạng ảnh: JPG, JPEG, PNG, GIF.';
        } 
        else {
            // KIỂM TRA BẮT BUỘC 3: Đổi tên file mới an toàn
            $newFileName = bin2hex(random_bytes(10)) . '_' . time() . '.' . $fileExtension;
            $destination = $uploadDir . $newFileName;

            // Tiến hành chuyển file từ thư mục tạm sang thư mục uploads
            if (move_uploaded_file($file['tmp_name'], $destination)) {
                $message = 'Upload ảnh đại diện thành công!';
                $uploadedFilePath = $destination;
            } else {
                $message = 'Không thể lưu file. Vui lòng thử lại!';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Upload Ảnh Đại Diện An Toàn</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f6f9; padding: 40px; }
        .card { max-width: 450px; margin: 0 auto; background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        h2 { text-align: center; color: #333; margin-top: 0; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: bold; }
        input[type="file"] { display: block; width: 100%; }
        button { width: 100%; background: #28a745; color: white; border: none; padding: 10px; font-size: 16px; border-radius: 4px; cursor: pointer; }
        button:hover { background: #218838; }
        .alert { padding: 10px; margin-top: 15px; border-radius: 4px; background-color: #e9ecef; }
        .avatar-preview { text-align: center; margin-top: 20px; }
        .avatar-preview img { max-width: 150px; border-radius: 50%; border: 3px solid #28a745; }
    </style>
</head>
<body>

<div class="card">
    <h2>UPLOAD CẬP NHẬT ẢNH ĐẠI DIỆN</h2>

    <!-- Lưu ý: Form upload bắt buộc phải có enctype="multipart/form-data" -->
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="avatar">Chọn ảnh đại diện (Tối đa 2MB):</label>
            <input type="file" name="avatar" id="avatar" accept="image/*" required>
        </div>
        <button type="submit">Tải lên</button>
    </form>

    <?php if ($message): ?>
        <div class="alert"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <?php if ($uploadedFilePath): ?>
        <div class="avatar-preview">
            <p><strong>Xem trước ảnh đại diện:</strong></p>
            <img src="<?= htmlspecialchars($uploadedFilePath, ENT_QUOTES, 'UTF-8') ?>" alt="Avatar">
        </div>
    <?php endif; ?>
</div>

</body>
</html>
<?php
/**
 * Upload Ảnh Đại Diện An Toàn
 * Bao gồm 3 kiểm tra bắt buộc: kích thước, định dạng + MIME type, đổi tên file an toàn
 */

// =====================================================
// 1. KHỞI TẠO BIẾN
// - $message: lưu thông báo (thành công hoặc lỗi)
// - $uploadedFilePath: đường dẫn file sau khi upload thành công
// =====================================================
$message = '';
$uploadedFilePath = '';

// =====================================================
// 2. XỬ LÝ KHI FORM ĐƯỢC GỬI (POST) VÀ CÓ FILE
// =====================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['avatar'])) {
    $file = $_FILES['avatar'];   // Lấy thông tin file được upload

    // =====================================================
    // 3. CẤU HÌNH QUY ĐỊNH UPLOAD
    // =====================================================
    $maxFileSize      = 2 * 1024 * 1024;                     // 2MB
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];      // Đuôi file cho phép
    $allowedMimeTypes  = ['image/jpeg', 'image/png', 'image/gif']; // MIME type thực tế
    $uploadDir         = 'uploads/';                         // Thư mục lưu trữ

    // Tạo thư mục uploads nếu chưa tồn tại
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);   // 0755: quyền đọc/ghi/thực thi
    }

    // =====================================================
    // 4. KIỂM TRA LỖI HỆ THỐNG KHI UPLOAD
    // UPLOAD_ERR_OK = 0 → upload thành công về phía PHP
    // =====================================================
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $message = 'Lỗi trong quá trình tải file lên server.';
    }
    // =====================================================
    // 5. KIỂM TRA BẮT BUỘC 1: KÍCH THƯỚC FILE
    // =====================================================
    elseif ($file['size'] > $maxFileSize) {
        $message = 'Kích thước file vượt quá giới hạn cho phép (Tối đa 2MB).';
    }
    else {
        // =====================================================
        // 6. LẤY ĐUÔI FILE VÀ KIỂM TRA MIME TYPE THỰC TẾ
        // - pathinfo(): lấy phần mở rộng của tên file
        // - finfo_*: kiểm tra loại file thực tế (an toàn hơn chỉ dựa vào đuôi)
        // =====================================================
        $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        $finfo    = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        // =====================================================
        // 7. KIỂM TRA BẮT BUỘC 2: ĐỊNH DẠNG + MIME TYPE
        // =====================================================
        if (!in_array($fileExtension, $allowedExtensions) || !in_array($mimeType, $allowedMimeTypes)) {
            $message = 'Chỉ chấp nhận các định dạng ảnh: JPG, JPEG, PNG, GIF.';
        }
        else {
            // =====================================================
            // 8. KIỂM TRA BẮT BUỘC 3: ĐỔI TÊN FILE MỚI AN TOÀN
            // - bin2hex(random_bytes(10)): tạo chuỗi ngẫu nhiên
            // - time(): thêm timestamp tránh trùng tên
            // → Tránh ghi đè file và tránh thực thi mã độc
            // =====================================================
            $newFileName = bin2hex(random_bytes(10)) . '_' . time() . '.' . $fileExtension;
            $destination = $uploadDir . $newFileName;

            // =====================================================
            // 9. DI CHUYỂN FILE TỪ THƯ MỤC TẠM SANG THƯ MỤC UPLOADS
            // move_uploaded_file() chỉ hoạt động với file được upload qua HTTP
            // =====================================================
            if (move_uploaded_file($file['tmp_name'], $destination)) {
                $message = 'Upload ảnh đại diện thành công!';
                $uploadedFilePath = $destination;   // Lưu đường dẫn để hiển thị
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
        /* ===== CSS GIAO DIỆN ===== */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            padding: 40px;
        }
        .card {
            max-width: 450px;
            margin: 0 auto;
            background: #fff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #333;
            margin-top: 0;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        input[type="file"] {
            display: block;
            width: 100%;
        }
        button {
            width: 100%;
            background: #28a745;
            color: white;
            border: none;
            padding: 10px;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background: #218838;
        }
        .alert {
            padding: 10px;
            margin-top: 15px;
            border-radius: 4px;
            background-color: #e9ecef;
        }
        .avatar-preview {
            text-align: center;
            margin-top: 20px;
        }
        .avatar-preview img {
            max-width: 150px;
            border-radius: 50%;
            border: 3px solid #28a745;
        }
    </style>
</head>
<body>

<div class="card">
    <h2>UPLOAD CẬP NHẬT ẢNH ĐẠI DIỆN</h2>

    <!-- =====================================================
         10. FORM UPLOAD
         - method="POST"
         - enctype="multipart/form-data" → BẮT BUỘC khi upload file
         - accept="image/*": chỉ cho phép chọn file ảnh
         ===================================================== -->
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="avatar">Chọn ảnh đại diện (Tối đa 2MB):</label>
            <input type="file" name="avatar" id="avatar" accept="image/*" required>
        </div>
        <button type="submit">Tải lên</button>
    </form>

    <!-- Hiển thị thông báo (thành công hoặc lỗi) -->
    <?php if ($message): ?>
        <div class="alert">
            <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <!-- Hiển thị ảnh xem trước nếu upload thành công -->
    <?php if ($uploadedFilePath): ?>
        <div class="avatar-preview">
            <p><strong>Xem trước ảnh đại diện:</strong></p>
            <img src="<?= htmlspecialchars($uploadedFilePath, ENT_QUOTES, 'UTF-8') ?>"
                 alt="Avatar">
        </div>
    <?php endif; ?>
</div>

</body>
</html>
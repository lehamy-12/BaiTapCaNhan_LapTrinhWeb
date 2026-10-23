<?php
/**
 * Form Liên Hệ An Toàn
 * Bao gồm: Sanitization, Validation, Upload file ảnh an toàn và chống XSS
 */

// =====================================================
// 1. KHỞI TẠO CÁC BIẾN
// - $errors: lưu thông báo lỗi theo từng trường
// - $successMessage: thông báo khi gửi thành công
// - $data: lưu dữ liệu người dùng đã nhập (để giữ lại khi có lỗi)
// =====================================================
$errors = [];
$successMessage = '';

$data = [
    'fullname' => '',
    'email'    => '',
    'subject'  => 'Hỗ trợ kỹ thuật',   // Giá trị mặc định
    'content'  => ''
];

// =====================================================
// 2. HÀM RÚT GỌN MÃ HÓA OUTPUT (chống XSS)
// Hàm e() dùng htmlspecialchars để tránh tấn công XSS
// =====================================================
function e($v) {
    return htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
}

// =====================================================
// 3. XỬ LÝ KHI FORM ĐƯỢC GỬI BẰNG PHƯƠNG THỨC POST
// =====================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // ----- 3.1. Sanitization (Làm sạch dữ liệu đầu vào) -----
    $data['fullname'] = trim($_POST['fullname'] ?? '');
    $data['email']    = trim($_POST['email'] ?? '');
    $data['subject']  = trim($_POST['subject'] ?? '');
    $data['content']  = trim($_POST['content'] ?? '');

    // ----- 3.2. Validate Họ tên -----
    if ($data['fullname'] === '') {
        $errors['fullname'] = 'Vui lòng nhập họ tên.';
    }

    // ----- 3.3. Validate Email -----
    if ($data['email'] === '') {
        $errors['email'] = 'Vui lòng nhập email.';
    } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Email không hợp lệ.';
    }

    // ----- 3.4. Validate Nội dung (10 - 500 ký tự) -----
    $contentLength = mb_strlen($data['content']); // mb_strlen hỗ trợ tiếng Việt
    if ($data['content'] === '') {
        $errors['content'] = 'Vui lòng nhập nội dung liên hệ.';
    } elseif ($contentLength < 10 || $contentLength > 500) {
        $errors['content'] = 'Nội dung phải từ 10 đến 500 ký tự.';
    }

    // =====================================================
    // 4. XỬ LÝ UPLOAD ẢNH ĐẠI DIỆN (3 kiểm tra bắt buộc)
    // =====================================================
    $avatarPath = ''; // Đường dẫn ảnh sau khi upload thành công

    // Kiểm tra xem người dùng có chọn file không
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] !== UPLOAD_ERR_NO_FILE) {
        $file = $_FILES['avatar'];
        $maxSize = 2 * 1024 * 1024; // 2MB
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $uploadDir = 'uploads/';

        // Tạo thư mục uploads nếu chưa tồn tại
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Kiểm tra lỗi upload từ PHP
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errors['avatar'] = 'Lỗi trong quá trình tải file lên.';
        }
        // Kiểm tra bắt buộc 1: Kích thước file
        elseif ($file['size'] > $maxSize) {
            $errors['avatar'] = 'Kích thước ảnh vượt quá giới hạn cho phép (Tối đa 2MB).';
        }
        else {
            // Lấy phần mở rộng file
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

            // Kiểm tra MIME type thực tế (an toàn hơn chỉ dựa vào đuôi file)
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime  = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);

            // Kiểm tra bắt buộc 2: Định dạng file + MIME type hợp lệ
            if (!in_array($ext, $allowedExtensions) || !in_array($mime, $allowedMimeTypes)) {
                $errors['avatar'] = 'Chỉ chấp nhận file ảnh định dạng JPG, JPEG, PNG, GIF.';
            }
            else {
                // Kiểm tra bắt buộc 3: Đổi tên file mới an toàn
                // - random_bytes tạo tên ngẫu nhiên tránh ghi đè và tránh thực thi mã độc
                $newFileName = bin2hex(random_bytes(8)) . '_' . time() . '.' . $ext;
                $destination = $uploadDir . $newFileName;

                // Di chuyển file từ thư mục tạm sang thư mục uploads
                if (move_uploaded_file($file['tmp_name'], $destination)) {
                    $avatarPath = $destination;
                } else {
                    $errors['avatar'] = 'Không thể lưu file ảnh. Vui lòng thử lại.';
                }
            }
        }
    }

    // ----- 5. Nếu không có lỗi → thông báo thành công -----
    if (empty($errors)) {
        $successMessage = 'Gửi liên hệ thành công! Cảm ơn bạn đã phản hồi.';
        // (Thực tế: Có thể lưu vào CSDL hoặc gửi email tại đây)
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Form Liên Hệ An Toàn</title>
    <style>
        /* ===== CSS GIAO DIỆN ===== */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 30px 0;
        }
        .container {
            max-width: 550px;
            margin: 0 auto;
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 5px;
        }
        .subtitle {
            text-align: center;
            color: #666;
            font-size: 14px;
            margin-bottom: 25px;
        }
        .form-group {
            margin-bottom: 18px;
        }
        label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
            color: #333;
            font-size: 14px;
        }
        input[type="text"],
        input[type="email"],
        select,
        textarea,
        input[type="file"] {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }
        textarea {
            resize: vertical;
            height: 100px;
        }
        .error {
            color: #d9534f;
            font-size: 0.85em;
            margin-top: 5px;
            display: block;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
            text-align: center;
            font-weight: bold;
        }
        button {
            width: 100%;
            background-color: #1b6ec2;
            color: white;
            border: none;
            padding: 12px;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
        }
        button:hover {
            background-color: #12569b;
        }
        .preview-img {
            margin-top: 10px;
            max-width: 100px;
            border-radius: 50%;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Liên hệ</h2>
    <div class="subtitle">Vui lòng nhập đầy đủ thông tin bên dưới.</div>

    <!-- Hiển thị thông báo thành công -->
    <?php if ($successMessage): ?>
        <div class="alert-success"><?= $successMessage ?></div>
    <?php endif; ?>

    <!-- Form bắt buộc có enctype="multipart/form-data" để upload file -->
    <form method="POST" action="" enctype="multipart/form-data">

        <!-- ===== HỌ TÊN ===== -->
        <div class="form-group">
            <label for="fullname">Họ tên</label>
            <input type="text" id="fullname" name="fullname"
                   value="<?= e($data['fullname']) ?>"
                   placeholder="Nhập họ tên...">
            <?php if (isset($errors['fullname'])): ?>
                <span class="error"><?= $errors['fullname'] ?></span>
            <?php endif; ?>
        </div>

        <!-- ===== EMAIL ===== -->
        <div class="form-group">
            <label for="email">Email</label>
            <input type="text" id="email" name="email"
                   value="<?= e($data['email']) ?>"
                   placeholder="name@example.com">
            <?php if (isset($errors['email'])): ?>
                <span class="error"><?= $errors['email'] ?></span>
            <?php endif; ?>
        </div>

        <!-- ===== CHỦ ĐỀ ===== -->
        <div class="form-group">
            <label for="subject">Chủ đề</label>
            <select id="subject" name="subject">
                <option value="Hỗ trợ kỹ thuật" <?= ($data['subject'] === 'Hỗ trợ kỹ thuật') ? 'selected' : '' ?>>
                    Hỗ trợ kỹ thuật
                </option>
                <option value="Góp ý dịch vụ" <?= ($data['subject'] === 'Góp ý dịch vụ') ? 'selected' : '' ?>>
                    Góp ý dịch vụ
                </option>
                <option value="Hợp tác kinh doanh" <?= ($data['subject'] === 'Hợp tác kinh doanh') ? 'selected' : '' ?>>
                    Hợp tác kinh doanh
                </option>
            </select>
        </div>

        <!-- ===== NỘI DUNG ===== -->
        <div class="form-group">
            <label for="content">Nội dung</label>
            <textarea id="content" name="content"
                      placeholder="Nhập nội dung liên hệ..."><?= e($data['content']) ?></textarea>
            <?php if (isset($errors['content'])): ?>
                <span class="error"><?= $errors['content'] ?></span>
            <?php endif; ?>
            <small style="color: #666;">Nội dung phải từ 10 đến 500 ký tự.</small>
        </div>

        <!-- ===== UPLOAD ẢNH ĐẠI DIỆN ===== -->
        <div class="form-group">
            <label for="avatar">Ảnh đại diện (Tùy chọn, tối đa 2MB)</label>
            <input type="file" id="avatar" name="avatar" accept="image/*">
            
            <?php if (isset($errors['avatar'])): ?>
                <span class="error"><?= $errors['avatar'] ?></span>
            <?php endif; ?>

            <!-- Hiển thị ảnh preview nếu upload thành công -->
            <?php if (!empty($avatarPath)): ?>
                <p style="color: green; margin-top: 5px;">Tải ảnh lên thành công!</p>
                <img src="<?= e($avatarPath) ?>" alt="Avatar Preview" class="preview-img">
            <?php endif; ?>
        </div>

        <button type="submit">Gửi liên hệ</button>
    </form>
</div>

</body>
</html>
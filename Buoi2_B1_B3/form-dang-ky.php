<?php
// Khởi tạo mảng lưu lỗi và mảng lưu dữ liệu người dùng nhập
$errors = [];
$data = [
    'name' => '',
    'email' => '',
    'birthday' => ''
];

// Hàm hỗ trợ mã hóa output chống XSS ngắn gọn
function e($v) {
    return htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
}

// Kiểm tra nếu request gửi lên bằng phương thức POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Lọc và chuẩn hóa dữ liệu đầu vào (Sanitization)
    $data['name'] = trim($_POST['name'] ?? '');
    $data['email'] = trim($_POST['email'] ?? '');
    $data['birthday'] = trim($_POST['birthday'] ?? '');

    // 2. Validate phía server (Server-side Validation)
    // Kiểm tra trường họ tên
    if ($data['name'] === '') {
        $errors['name'] = 'Vui lòng nhập họ tên.';
    }

    // Kiểm tra định dạng email
    if ($data['email'] === '') {
        $errors['email'] = 'Vui lòng nhập email.';
    } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Email không hợp lệ.';
    }

    // Kiểm tra trường ngày sinh
    if ($data['birthday'] === '') {
        $errors['birthday'] = 'Vui lòng nhập ngày sinh.';
    }

    // 3. Nếu không có lỗi nào, tiến hành xử lý thành công và chuyển hướng (PRG pattern)
    if (empty($errors)) {
        // (Thực tế: Lưu dữ liệu vào CSDL ở bước này)
        
        // Chuyển hướng sang trang thành công
        header('Location: success.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Form Đăng Ký Sinh Viên</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 40px 0;
        }
        .container {
            max-width: 480px;
            margin: 0 auto;
            background: #fff;
            padding: 25px 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 16px;
        }
        label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
            color: #333;
        }
        input[type="text"],
        input[type="email"],
        input[type="date"] {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }
        .error {
            color: #d9534f;
            font-size: 0.85em;
            margin-top: 4px;
            display: block;
        }
        button {
            width: 100%;
            background-color: #007bff;
            color: white;
            border: none;
            padding: 12px;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>ĐĂNG KÝ SINH VIÊN</h2>

    <!-- Form gửi dữ liệu bằng phương thức POST -->
    <form method="POST" action="">
        <!-- Họ và tên -->
        <div class="form-group">
            <label for="name">Họ và tên:</label>
            <input type="text" id="name" name="name" value="<?= e($data['name']) ?>">
            <?php if (isset($errors['name'])): ?>
                <span class="error"><?= $errors['name'] ?></span>
            <?php endif; ?>
        </div>

        <!-- Email -->
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="text" id="email" name="email" value="<?= e($data['email']) ?>">
            <?php if (isset($errors['email'])): ?>
                <span class="error"><?= $errors['email'] ?></span>
            <?php endif; ?>
        </div>

        <!-- Ngày sinh -->
        <div class="form-group">
            <label for="birthday">Ngày sinh:</label>
            <input type="date" id="birthday" name="birthday" value="<?= e($data['birthday']) ?>">
            <?php if (isset($errors['birthday'])): ?>
                <span class="error"><?= $errors['birthday'] ?></span>
            <?php endif; ?>
        </div>

        <button type="submit">Đăng ký</button>
    </form>
</div>

</body>
</html>
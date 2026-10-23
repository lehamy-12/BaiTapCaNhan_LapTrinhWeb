<?php
/**
 * Form Đăng Ký Sinh Viên
 * Bao gồm: Sanitization, Validation phía server, chống XSS và PRG pattern
 */

// =====================================================
// 1. KHỞI TẠO MẢNG LỖI VÀ MẢNG DỮ LIỆU
// - $errors: lưu các thông báo lỗi theo từng trường
// - $data: lưu giá trị người dùng đã nhập (để giữ lại khi có lỗi)
// =====================================================
$errors = [];
$data = [
    'name'     => '',
    'email'    => '',
    'birthday' => ''
];

// =====================================================
// 2. HÀM HỖ TRỢ MÃ HÓA OUTPUT (chống XSS)
// Hàm e() viết tắt của escape, dùng htmlspecialchars
// để tránh tấn công XSS khi in dữ liệu ra HTML
// =====================================================
function e($v) {
    return htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
}

// =====================================================
// 3. XỬ LÝ KHI FORM ĐƯỢC GỬI BẰNG PHƯƠNG THỨC POST
// =====================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // ----- 3.1. Sanitization (Lọc và chuẩn hóa dữ liệu) -----
    // trim() loại bỏ khoảng trắng thừa ở đầu/cuối
    // Toán tử ?? để tránh lỗi nếu key không tồn tại
    $data['name']     = trim($_POST['name'] ?? '');
    $data['email']    = trim($_POST['email'] ?? '');
    $data['birthday'] = trim($_POST['birthday'] ?? '');

    // ----- 3.2. Validation (Kiểm tra dữ liệu phía server) -----

    // Kiểm tra họ tên
    if ($data['name'] === '') {
        $errors['name'] = 'Vui lòng nhập họ tên.';
    }

    // Kiểm tra email
    if ($data['email'] === '') {
        $errors['email'] = 'Vui lòng nhập email.';
    } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        // filter_var + FILTER_VALIDATE_EMAIL kiểm tra định dạng email hợp lệ
        $errors['email'] = 'Email không hợp lệ.';
    }

    // Kiểm tra ngày sinh
    if ($data['birthday'] === '') {
        $errors['birthday'] = 'Vui lòng nhập ngày sinh.';
    }

    // ----- 3.3. Xử lý thành công (PRG Pattern) -----
    // Nếu không có lỗi nào → chuyển hướng sang trang success.php
    // PRG = Post-Redirect-Get (tránh việc người dùng F5 bị gửi lại form)
    if (empty($errors)) {
        // (Thực tế: Lưu dữ liệu vào cơ sở dữ liệu tại đây)

        header('Location: success.php');
        exit; // Dừng thực thi sau khi chuyển hướng
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Form Đăng Ký Sinh Viên</title>
    <style>
        /* ===== CSS GIAO DIỆN ===== */
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
            color: #d9534f;          /* Màu đỏ báo lỗi */
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

    <!-- Form gửi dữ liệu bằng phương thức POST về chính trang này -->
    <form method="POST" action="">

        <!-- ===== TRƯỜNG HỌ VÀ TÊN ===== -->
        <div class="form-group">
            <label for="name">Họ và tên:</label>
            <!-- value="<?= e($data['name']) ?>" giữ lại dữ liệu đã nhập khi có lỗi -->
            <input type="text" id="name" name="name" value="<?= e($data['name']) ?>">
            
            <!-- Hiển thị lỗi nếu có -->
            <?php if (isset($errors['name'])): ?>
                <span class="error"><?= $errors['name'] ?></span>
            <?php endif; ?>
        </div>

        <!-- ===== TRƯỜNG EMAIL ===== -->
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="text" id="email" name="email" value="<?= e($data['email']) ?>">
            
            <?php if (isset($errors['email'])): ?>
                <span class="error"><?= $errors['email'] ?></span>
            <?php endif; ?>
        </div>

        <!-- ===== TRƯỜNG NGÀY SINH ===== -->
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
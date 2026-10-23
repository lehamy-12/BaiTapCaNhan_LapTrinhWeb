<?php
/**
 * Buổi 2 - 2.7 Demo: Hóa đơn học liệu
 * Form nhập tên tài liệu, số lượng, đơn giá → tính thành tiền
 */

// =====================================================
// 1. HÀM ĐỊNH DẠNG TIỀN TỆ
// - Nhận giá trị số (float)
// - Trả về chuỗi đã format: 1.250.000 đ
// - number_format(value, số chữ số thập phân, dấu thập phân, dấu ngăn cách hàng nghìn)
// =====================================================
function money(float $value): string {
    return number_format($value, 0, ',', '.') . ' đ';
}

// =====================================================
// 2. LẤY DỮ LIỆU TỪ FORM (khi người dùng bấm nút Tính)
// - Sử dụng toán tử ?? để gán giá trị mặc định nếu không tồn tại
// - Ép kiểu (int) và (float) để đảm bảo đúng kiểu dữ liệu
// =====================================================
$name  = $_POST['name']  ?? '';                    // Tên tài liệu (chuỗi)
$qty   = (int)  ($_POST['qty']   ?? 0);            // Số lượng (số nguyên)
$price = (float)($_POST['price'] ?? 0);            // Đơn giá (số thực)

// =====================================================
// 3. TÍNH THÀNH TIỀN
// Thành tiền = Số lượng × Đơn giá
// =====================================================
$total = $qty * $price;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Hóa đơn học liệu</title>
    <style>
        /* ===== CSS GIAO DIỆN ===== */
        body {
            font-family: 'Segoe UI', sans-serif;
            max-width: 480px;
            margin: 40px auto;
            padding: 20px;
        }
        form {
            display: flex;
            gap: 8px;
            margin-bottom: 20px;
            flex-wrap: wrap;          /* Xuống hàng khi màn hình nhỏ */
        }
        input {
            padding: 8px 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
        }
        input[name="name"] {
            flex: 2;                 /* Chiếm nhiều không gian hơn */
            min-width: 140px;
        }
        input[type="number"] {
            width: 100px;
        }
        button {
            padding: 8px 18px;
            background: #e8e8e8;
            border: 1px solid #bbb;
            border-radius: 6px;
            cursor: pointer;
        }
        button:hover {
            background: #d5d5d5;
        }
        .ket-qua {
            font-size: 16px;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<!-- =====================================================
     4. FORM NHẬP LIỆU
     - method="post": gửi dữ liệu bằng POST
     - value="": giữ lại giá trị đã nhập sau khi submit
     - htmlspecialchars(): chống XSS khi in dữ liệu ra HTML
     ===================================================== -->
<form method="post">
    <input name="name"  
           placeholder="Tên tài liệu" 
           value="<?= htmlspecialchars($name) ?>">

    <input name="qty"   
           type="number" 
           min="1" 
           placeholder="Số lượng" 
           value="<?= $qty ?: '' ?>">   <!-- Nếu = 0 thì hiện rỗng -->

    <input name="price" 
           type="number" 
           min="0" 
           placeholder="Đơn giá" 
           value="<?= $price ?: '' ?>">

    <button>Tính</button>
</form>

<!-- =====================================================
     5. HIỂN THỊ KẾT QUẢ
     - Chỉ hiện khi $total > 0 (đã nhập số lượng và đơn giá)
     - Dùng htmlspecialchars() để an toàn
     - Gọi hàm money() để định dạng tiền tệ
     ===================================================== -->
<?php if ($total > 0): ?>
    <p class="ket-qua">
        <?= htmlspecialchars($name) ?>: <?= money($total) ?>
    </p>
<?php endif; ?>

</body>
</html>
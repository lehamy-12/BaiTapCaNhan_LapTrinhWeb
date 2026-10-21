<?php
/**
 * Buổi 2 - 2.7 Demo: Hóa đơn học liệu
 * Form nhập tên tài liệu, số lượng, đơn giá → tính thành tiền
 */

function money(float $value): string {
    return number_format($value, 0, ',', '.') . ' đ';
}

$name  = $_POST['name']  ?? '';
$qty   = (int)  ($_POST['qty']   ?? 0);
$price = (float)($_POST['price'] ?? 0);
$total = $qty * $price;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Hóa đơn học liệu</title>
    <style>
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
            flex-wrap: wrap;
        }
        input {
            padding: 8px 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
        }
        input[name="name"] {
            flex: 2;
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

<form method="post">
    <input name="name"  placeholder="Tên tài liệu" value="<?= htmlspecialchars($name) ?>">
    <input name="qty"   type="number" min="1" placeholder="Số lượng" value="<?= $qty ?: '' ?>">
    <input name="price" type="number" min="0" placeholder="Đơn giá" value="<?= $price ?: '' ?>">
    <button>Tính</button>
</form>

<?php if ($total > 0): ?>
    <p class="ket-qua">
        <?= htmlspecialchars($name) ?>: <?= money($total) ?>
    </p>
<?php endif; ?>

</body>
</html>
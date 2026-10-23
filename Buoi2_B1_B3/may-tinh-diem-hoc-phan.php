<?php
/**
 * Máy tính điểm học phần
 * Form nhập họ tên + 3 cột điểm → tính điểm tổng kết và xếp loại
 */

// =====================================================
// 1. KHỞI TẠO CÁC BIẾN
// - $fullname, $cc, $mid, $final: lưu dữ liệu người dùng nhập
// - $errors: lưu thông báo lỗi theo từng trường
// - $result: lưu kết quả tính toán (null nếu chưa tính hoặc có lỗi)
// =====================================================
$fullname = '';
$cc     = '';
$mid    = '';
$final  = '';

$errors = [];
$result = null;

// =====================================================
// 2. HÀM KIỂM TRA ĐIỂM HỢP LỆ
// Điểm phải là số và nằm trong khoảng từ 0 đến 10
// =====================================================
function isValidScore($score): bool {
    return is_numeric($score) && $score >= 0 && $score <= 10;
}

// =====================================================
// 3. HÀM TÍNH ĐIỂM TỔNG KẾT
// Công thức: 10% chuyên cần + 30% giữa kỳ + 60% cuối kỳ
// =====================================================
function finalScore(float $cc, float $mid, float $final): float {
    return $cc * 0.10 + $mid * 0.30 + $final * 0.60;
}

// =====================================================
// 4. HÀM XẾP LOẠI HỌC LỰC
// - >= 8.5 → Giỏi
// - >= 7.0 → Khá
// - >= 5.0 → Trung bình
// - < 5.0  → Chưa đạt
// =====================================================
function getRank(float $score): string {
    if ($score >= 8.5) {
        return 'Giỏi';
    } elseif ($score >= 7.0) {
        return 'Khá';
    } elseif ($score >= 5.0) {
        return 'Trung bình';
    } else {
        return 'Chưa đạt';
    }
}

// =====================================================
// 5. XỬ LÝ KHI NGƯỜI DÙNG GỬI FORM (POST)
// =====================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // ----- 5.1. Lấy dữ liệu và làm sạch -----
    $fullname = trim($_POST['fullname'] ?? '');
    $cc     = trim($_POST['cc'] ?? '');
    $mid    = trim($_POST['mid'] ?? '');
    $final  = trim($_POST['final'] ?? '');

    // ----- 5.2. Validate Họ tên -----
    if (empty($fullname)) {
        $errors['fullname'] = 'Vui lòng nhập họ và tên.';
    }

    // ----- 5.3. Validate Điểm chuyên cần -----
    if ($cc === '') {
        $errors['cc'] = 'Vui lòng nhập điểm chuyên cần.';
    } elseif (!isValidScore($cc)) {
        $errors['cc'] = 'Điểm chuyên cần không hợp lệ (phải nằm trong khoảng 0-10).';
    }

    // ----- 5.4. Validate Điểm giữa kỳ -----
    if ($mid === '') {
        $errors['mid'] = 'Vui lòng nhập điểm giữa kỳ.';
    } elseif (!isValidScore($mid)) {
        $errors['mid'] = 'Điểm giữa kỳ không hợp lệ (phải nằm trong khoảng 0-10).';
    }

    // ----- 5.5. Validate Điểm cuối kỳ -----
    if ($final === '') {
        $errors['final'] = 'Vui lòng nhập điểm cuối kỳ.';
    } elseif (!isValidScore($final)) {
        $errors['final'] = 'Điểm cuối kỳ không hợp lệ (phải nằm trong khoảng 0-10).';
    }

    // ----- 5.6. Nếu không có lỗi → tính toán -----
    if (empty($errors)) {
        $scoreCC    = (float)$cc;
        $scoreMid   = (float)$mid;
        $scoreFinal = (float)$final;

        $totalScore = finalScore($scoreCC, $scoreMid, $scoreFinal);
        $rank       = getRank($totalScore);

        // Lưu kết quả vào mảng $result để hiển thị
        $result = [
            'fullname'   => $fullname,
            'totalScore' => round($totalScore, 2),   // Làm tròn 2 chữ số thập phân
            'rank'       => $rank
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Máy tính điểm học phần</title>
    <style>
        /* ===== CSS GIAO DIỆN ===== */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 40px 0;
        }
        .container {
            max-width: 500px;
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
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }
        input[type="text"],
        input[type="number"] {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .error {
            color: #d9534f;
            font-size: 0.875em;
            margin-top: 5px;
            display: block;
        }
        button {
            width: 100%;
            background-color: #28a745;
            color: white;
            border: none;
            padding: 12px;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
        }
        button:hover {
            background-color: #218838;
        }
        .result-box {
            margin-top: 25px;
            padding: 15px;
            background-color: #e9ecef;
            border-left: 5px solid #28a745;
            border-radius: 4px;
        }
        .result-box p {
            margin: 8px 0;
            font-size: 16px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>MÁY TÍNH ĐIỂM HỌC PHẦN</h2>

    <!-- =====================================================
         6. FORM NHẬP LIỆU
         - method="POST": gửi dữ liệu bằng POST
         - value="": giữ lại dữ liệu đã nhập + chống XSS
         ===================================================== -->
    <form method="POST" action="">

        <!-- Họ và tên -->
        <div class="form-group">
            <label for="fullname">Họ và tên sinh viên:</label>
            <input type="text" id="fullname" name="fullname"
                   value="<?= htmlspecialchars($fullname) ?>">
            <?php if (isset($errors['fullname'])): ?>
                <span class="error"><?= $errors['fullname'] ?></span>
            <?php endif; ?>
        </div>

        <!-- Điểm chuyên cần (10%) -->
        <div class="form-group">
            <label for="cc">Điểm chuyên cần (10%):</label>
            <input type="number" step="0.1" id="cc" name="cc"
                   value="<?= htmlspecialchars($cc) ?>">
            <?php if (isset($errors['cc'])): ?>
                <span class="error"><?= $errors['cc'] ?></span>
            <?php endif; ?>
        </div>

        <!-- Điểm giữa kỳ (30%) -->
        <div class="form-group">
            <label for="mid">Điểm giữa kỳ (30%):</label>
            <input type="number" step="0.1" id="mid" name="mid"
                   value="<?= htmlspecialchars($mid) ?>">
            <?php if (isset($errors['mid'])): ?>
                <span class="error"><?= $errors['mid'] ?></span>
            <?php endif; ?>
        </div>

        <!-- Điểm cuối kỳ (60%) -->
        <div class="form-group">
            <label for="final">Điểm cuối kỳ (60%):</label>
            <input type="number" step="0.1" id="final" name="final"
                   value="<?= htmlspecialchars($final) ?>">
            <?php if (isset($errors['final'])): ?>
                <span class="error"><?= $errors['final'] ?></span>
            <?php endif; ?>
        </div>

        <button type="submit">Tính điểm</button>
    </form>

    <!-- =====================================================
         7. HIỂN THỊ KẾT QUẢ
         Chỉ hiện khi $result không rỗng (tính toán thành công)
         ===================================================== -->
    <?php if ($result): ?>
        <div class="result-box">
            <h3>KẾT QUẢ:</h3>
            <p><strong>Họ tên:</strong> <?= htmlspecialchars($result['fullname']) ?></p>
            <p><strong>Điểm tổng kết:</strong> <?= $result['totalScore'] ?></p>
            <p><strong>Xếp loại:</strong> <?= $result['rank'] ?></p>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
<?php
// =====================================================
// 1. TẠO MẢNG DỮ LIỆU SINH VIÊN
// Mảng $students chứa 3 sinh viên, mỗi sinh viên là 1 mảng kết hợp
// với các key: name (họ tên), midterm (điểm giữa kỳ), final (điểm cuối kỳ)
// =====================================================
$students = [
    [
        'name' => 'Nguyễn Văn A',
        'midterm' => 8.5,
        'final' => 7.0
    ],
    [
        'name' => 'Trần Thị B',
        'midterm' => 4.0,
        'final' => 5.0
    ],
    [
        'name' => 'Lê Hoàng C',
        'midterm' => 3.5,
        'final' => 4.0
    ]
];

// =====================================================
// 2. HÀM TÍNH ĐIỂM TRUNG BÌNH
// - Nhận 2 tham số kiểu float: điểm giữa kỳ và điểm cuối kỳ
// - Trả về kiểu float
// - Công thức: (điểm giữa kỳ + điểm cuối kỳ) / 2
// =====================================================
function calculateAverage(float $midterm, float $final): float {
    return ($midterm + $final) / 2;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Bảng điểm sinh viên</title>
    <style>
        /* ===== CSS CHO BẢNG ===== */
        table {
            border-collapse: collapse;   /* Gộp đường viền các ô */
            width: 80%;                  /* Chiều rộng bảng */
            margin: 20px auto;           /* Căn giữa bảng */
            font-family: Arial, sans-serif;
        }
        th, td {
            border: 1px solid #dddddd;   /* Đường viền ô */
            text-align: center;          /* Căn giữa nội dung */
            padding: 8px;                /* Khoảng cách bên trong ô */
        }
        th {
            background-color: #4CAF50;   /* Màu nền tiêu đề */
            color: white;                /* Màu chữ tiêu đề */
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;   /* Màu xen kẽ hàng chẵn */
        }
        /* Class tô màu kết quả */
        .pass {
            color: green;
            font-weight: bold;
        }
        .fail {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <h2 style="text-align: center;">BẢNG ĐIỂM SINH VIÊN</h2>

    <table>
        <thead>
            <tr>
                <th>STT</th>
                <th>Họ và Tên</th>
                <th>Điểm Giữa Kỳ</th>
                <th>Điểm Cuối Kỳ</th>
                <th>Điểm Trung Bình</th>
                <th>Kết Quả</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            // =====================================================
            // 3. DUYỆT MẢNG VÀ IN BẢNG HTML
            // Sử dụng vòng lặp foreach để lấy từng sinh viên
            // =====================================================
            $stt = 1; // Biến đếm số thứ tự, bắt đầu từ 1

            foreach ($students as $student): 
                // Gọi hàm tính điểm trung bình
                $avg = calculateAverage($student['midterm'], $student['final']);
                
                // =====================================================
                // 4. XÁC ĐỊNH KẾT QUẢ
                // Nếu điểm trung bình >= 5 → "Đạt" (màu xanh)
                // Ngược lại → "Không đạt" (màu đỏ)
                // Sử dụng toán tử 3 ngôi (ternary operator)
                // =====================================================
                $result = ($avg >= 5) ? 'Đạt' : 'Không đạt';
                $statusClass = ($avg >= 5) ? 'pass' : 'fail';
            ?>
                <tr>
                    <!-- In số thứ tự rồi tăng biến $stt lên 1 -->
                    <td><?= $stt++ ?></td>
                    
                    <!-- =====================================================
                         5. BẢO MẬT: Mã hóa tên sinh viên bằng htmlspecialchars()
                         - ENT_QUOTES: mã hóa cả dấu nháy đơn và kép
                         - UTF-8: hỗ trợ tiếng Việt
                         Mục đích: chống tấn công XSS
                         ===================================================== -->
                    <td style="text-align: left;">
                        <?= htmlspecialchars($student['name'], ENT_QUOTES, 'UTF-8') ?>
                    </td>
                    
                    <!-- number_format(..., 1) → hiển thị 1 chữ số thập phân -->
                    <td><?= number_format($student['midterm'], 1) ?></td>
                    <td><?= number_format($student['final'], 1) ?></td>
                    <td><?= number_format($avg, 1) ?></td>
                    
                    <!-- Gán class động (pass hoặc fail) để tô màu -->
                    <td class="<?= $statusClass ?>"><?= $result ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>
</html>
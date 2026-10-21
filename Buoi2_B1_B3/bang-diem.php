<?php
// 1. Tạo mảng $students gồm 3 sinh viên với name, midterm, final
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

// 2. Viết hàm calculateAverage() tính điểm trung bình
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
        table {
            border-collapse: collapse;
            width: 80%;
            margin: 20px auto;
            font-family: Arial, sans-serif;
        }
        th, td {
            border: 1px solid #dddddd;
            text-align: center;
            padding: 8px;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
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
            // 3. Dùng foreach để in bảng HTML
            $stt = 1;
            foreach ($students as $student): 
                // Tính điểm trung bình bằng hàm đã định nghĩa
                $avg = calculateAverage($student['midterm'], $student['final']);
                
                // 4. Thêm cột kết quả: Đạt nếu điểm trung bình >= 5
                $result = ($avg >= 5) ? 'Đạt' : 'Không đạt';
                $statusClass = ($avg >= 5) ? 'pass' : 'fail';
            ?>
                <tr>
                    <td><?= $stt++ ?></td>
                    
                    <!-- 5. Mã hóa tên sinh viên bằng htmlspecialchars() trước khi in[cite: 25] -->
                    <td style="text-align: left;"><?= htmlspecialchars($student['name'], ENT_QUOTES, 'UTF-8') ?></td>
                    
                    <td><?= number_format($student['midterm'], 1) ?></td>
                    <td><?= number_format($student['final'], 1) ?></td>
                    <td><?= number_format($avg, 1) ?></td>
                    <td class="<?= $statusClass ?>"><?= $result ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>
</html>
<?php
/**
 * Buổi 2 - Bài tập 2.4: Mảng chỉ số và mảng kết hợp
 * Tạo mảng 3 sinh viên, mỗi sinh viên có name và score
 * Sau đó duyệt mảng và in thông tin ra màn hình
 */

// =====================================================
// 1. TẠO MẢNG KẾT HỢP CHỨA 3 SINH VIÊN
// Mỗi phần tử là một mảng kết hợp (associative array)
// có 2 key: 'name' (tên) và 'score' (điểm)
// =====================================================
$students = [
    ['name' => 'An',   'score' => 8.0],
    ['name' => 'Bình', 'score' => 6.5],
    ['name' => 'Chi',  'score' => 4.5],
];

// =====================================================
// 2. DUYỆT MẢNG BẰNG FOREACH VÀ IN RA MÀN HÌNH
// - $student sẽ lần lượt nhận từng phần tử trong mảng $students
// - Dùng dấu chấm (.) để nối chuỗi
// - echo "<br>" để xuống dòng trong trình duyệt
// =====================================================
foreach ($students as $student) {
    // In tên sinh viên
    echo "Tên: " . $student['name'];
    
    // In điểm của sinh viên
    echo " - Điểm: " . $student['score'];
    
    // Xuống dòng
    echo "<br>";
}
?>
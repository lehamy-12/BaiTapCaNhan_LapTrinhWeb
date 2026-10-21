<?php
/**
 * Buổi 2 - Bài tập 2.4: Mảng chỉ số và mảng kết hợp
 * Tạo mảng 3 sinh viên, mỗi sinh viên có name và score
 */

// Mảng kết hợp chứa 3 sinh viên
$students = [
    ['name' => 'An',   'score' => 8.0],
    ['name' => 'Bình', 'score' => 6.5],
    ['name' => 'Chi',  'score' => 4.5],
];

// Duyệt mảng và in ra
foreach ($students as $student) {
    echo "Tên: " . $student['name'];
    echo " - Điểm: " . $student['score'];
    echo "<br>";
}
?>
<?php
/**
 * Buổi 2 - Bài tập 2.3: Điều kiện và vòng lặp
 * Xếp loại điểm
 */

$diem = 7.5; // Đổi số này để test

if ($diem >= 8) {
    $xep_loai = "Giỏi";
} elseif ($diem >= 6.5) {
    $xep_loai = "Khá";
} elseif ($diem >= 5) {
    $xep_loai = "Trung bình";
} else {
    $xep_loai = "Chưa đạt";
}

echo "Điểm: " . $diem . "<br>";
echo "Xếp loại: " . $xep_loai;
?>
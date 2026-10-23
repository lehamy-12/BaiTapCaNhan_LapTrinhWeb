<?php
/**
 * Buổi 2 - Bài tập 2.3: Điều kiện và vòng lặp
 * Xếp loại điểm
 */

// =====================================================
// 1. KHAI BÁO ĐIỂM CẦN XẾP LOẠI
// Đổi giá trị biến $diem để kiểm tra các trường hợp khác nhau
// =====================================================
$diem = 7.5; // Đổi số này để test

// =====================================================
// 2. CẤU TRÚC ĐIỀU KIỆN IF - ELSEIF - ELSE
// Dùng để phân loại điểm theo các mức:
// - >= 8.0  → Giỏi
// - >= 6.5  → Khá
// - >= 5.0  → Trung bình
// - < 5.0   → Chưa đạt
// =====================================================
if ($diem >= 8) {
    $xep_loai = "Giỏi";
} elseif ($diem >= 6.5) {
    $xep_loai = "Khá";
} elseif ($diem >= 5) {
    $xep_loai = "Trung bình";
} else {
    $xep_loai = "Chưa đạt";
}

// =====================================================
// 3. IN KẾT QUẢ RA MÀN HÌNH
// - Dùng dấu chấm (.) để nối chuỗi
// - <br> để xuống dòng trên trình duyệt
// =====================================================
echo "Điểm: " . $diem . "<br>";
echo "Xếp loại: " . $xep_loai;
?>
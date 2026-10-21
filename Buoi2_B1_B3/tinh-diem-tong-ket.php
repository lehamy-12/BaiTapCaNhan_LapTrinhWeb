<?php
/**
 * Buổi 2 - Bài tập 2.5: Hàm tính điểm tổng kết
 * Điểm tổng kết = Chuyên cần * 10% + Giữa kỳ * 30% + Cuối kỳ * 60%
 */

// Hàm tính điểm tổng kết
function finalScore(float $cc, float $mid, float $final): float
{
    return $cc * 0.10 + $mid * 0.30 + $final * 0.60;
}

// ===== Phần chạy thử =====
$chuyen_can = 8.5;
$giua_ky    = 7.0;
$cuoi_ky    = 9.0;

$diem_tong_ket = finalScore($chuyen_can, $giua_ky, $cuoi_ky);

echo "Điểm chuyên cần: $chuyen_can<br>";
echo "Điểm giữa kỳ: $giua_ky<br>";
echo "Điểm cuối kỳ: $cuoi_ky<br>";
echo "<strong>Điểm tổng kết: $diem_tong_ket</strong>";
?>
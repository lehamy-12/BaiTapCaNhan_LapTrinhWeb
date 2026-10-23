<?php
/**
 * Buổi 2 - Bài tập 2.5: Hàm tính điểm tổng kết
 * Điểm tổng kết = Chuyên cần * 10% + Giữa kỳ * 30% + Cuối kỳ * 60%
 */

// =====================================================
// 1. HÀM TÍNH ĐIỂM TỔNG KẾT
// - Nhận 3 tham số kiểu float:
//   + $cc    : điểm chuyên cần
//   + $mid   : điểm giữa kỳ
//   + $final : điểm cuối kỳ
// - Trả về kiểu float
// - Công thức:
//   Điểm tổng kết = (Chuyên cần × 10%) + (Giữa kỳ × 30%) + (Cuối kỳ × 60%)
// =====================================================
function finalScore(float $cc, float $mid, float $final): float
{
    return $cc * 0.10 + $mid * 0.30 + $final * 0.60;
}

// =====================================================
// 2. PHẦN CHẠY THỬ (Demo)
// Khai báo 3 biến điểm mẫu để kiểm tra hàm
// =====================================================
$chuyen_can = 8.5;   // Điểm chuyên cần
$giua_ky    = 7.0;   // Điểm giữa kỳ
$cuoi_ky    = 9.0;   // Điểm cuối kỳ

// =====================================================
// 3. GỌI HÀM TÍNH ĐIỂM TỔNG KẾT
// Truyền 3 điểm vào hàm finalScore() và nhận kết quả
// =====================================================
$diem_tong_ket = finalScore($chuyen_can, $giua_ky, $cuoi_ky);

// =====================================================
// 4. IN KẾT QUẢ RA MÀN HÌNH
// - Dùng echo để hiển thị
// - <br> để xuống dòng trên trình duyệt
// - <strong> để in đậm điểm tổng kết
// =====================================================
echo "Điểm chuyên cần: $chuyen_can<br>";
echo "Điểm giữa kỳ: $giua_ky<br>";
echo "Điểm cuối kỳ: $cuoi_ky<br>";
echo "<strong>Điểm tổng kết: $diem_tong_ket</strong>";
?>
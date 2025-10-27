<?php
include_once '../includes/connect.php';
session_start();

if (!isset($_SESSION['benh_nhan_id'])) {
    die("⚠️ Bạn chưa đăng nhập!");
}

$benh_nhan_id = $_SESSION['benh_nhan_id'];
$bac_si_id = $_POST['bac_si'] ?? '';
$lich_ranh_id = $_POST['khung_gio'] ?? '';
$ly_do = $_POST['ly_do'] ?? '';

if (!$bac_si_id || !$lich_ranh_id || !$ly_do) {
    die("⚠️ Thiếu thông tin cần thiết để đặt lịch!");
}

// ✅ Lấy thông tin khung giờ từ bảng lich_ranh
$stmt = $conn->prepare("SELECT ngay, gio_bat_dau, gio_ket_thuc FROM lich_ranh WHERE id=? AND trang_thai='Trống'");
$stmt->bind_param("i", $lich_ranh_id);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    die("⚠️ Khung giờ này đã có người đặt!");
}

$slot = $res->fetch_assoc();

// ✅ Lưu lịch hẹn
$stmt2 = $conn->prepare("
    INSERT INTO lich_hen (benh_nhan_id, bac_si_id, ngay, gio_bat_dau, gio_ket_thuc, ly_do, trang_thai)
    VALUES (?, ?, ?, ?, ?, ?, 'Chờ xác nhận')
");
$stmt2->bind_param("iissss", $benh_nhan_id, $bac_si_id, $slot['ngay'], $slot['gio_bat_dau'], $slot['gio_ket_thuc'], $ly_do);
$stmt2->execute();

// ✅ Cập nhật trạng thái lịch rảnh
$conn->query("UPDATE lich_ranh SET trang_thai='Đã đặt' WHERE id=$lich_ranh_id");

echo "<script>
alert('✅ Đặt lịch thành công! Bác sĩ sẽ xác nhận sớm.');
window.location.href='tra_cuu.php';
</script>";
?>

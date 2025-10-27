<?php
include("../includes/connect.php");
session_start();
$bac_si_id = $_SESSION['bac_si_id'] ?? 0;
$id = $_GET['id'] ?? 0;

if ($bac_si_id == 0 || $id == 0) {
    header("Location: bacsi_lich_ranh.php");
    exit;
}

$stmt = $conn->prepare("DELETE FROM lich_ranh WHERE id=? AND bac_si_id=? AND trang_thai='Trống'");
$stmt->bind_param("ii", $id, $bac_si_id);
$stmt->execute();

echo "<script>alert('✅ Đã xóa lịch thành công!'); window.location='bacsi_lich_ranh.php';</script>";
?>

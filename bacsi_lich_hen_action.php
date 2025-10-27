<?php
include("../includes/connect.php");
session_start();

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$action = $_GET['action'] ?? '';

if ($id <= 0) {
    die("❌ ID không hợp lệ!");
}

switch ($action) {
    case 'duyet':
        // Bác sĩ xác nhận lịch
        $sql = "UPDATE lich_hen SET trang_thai = 'Đã xác nhận' WHERE id = ?";
        break;
    case 'huy':
        // Bác sĩ hủy lịch
        $sql = "UPDATE lich_hen SET trang_thai = 'Đã hủy' WHERE id = ?";
        break;
    case 'kham_xong':
        // Sau khi hoàn tất khám
        $sql = "UPDATE lich_hen SET trang_thai = 'Đã khám' WHERE id = ?";
        break;
    default:
        die("❌ Hành động không hợp lệ!");
}

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

// Quay lại danh sách
header("Location: bacsi_lich_hen.php");
exit;
?>

<?php
include_once '../includes/connect.php';
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['benh_nhan_id'])) {
    echo json_encode(['trung' => false]);
    exit;
}

$benh_nhan_id = $_SESSION['benh_nhan_id'];
$slot_id = intval($_GET['slot_id'] ?? 0);

// Lấy giờ bắt đầu / kết thúc của slot
$stmt = $conn->prepare("SELECT ngay, gio_bat_dau, gio_ket_thuc FROM lich_ranh WHERE id=?");
$stmt->bind_param("i", $slot_id);
$stmt->execute();
$slot = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$slot) {
    echo json_encode(['trung' => false]);
    exit;
}

// Kiểm tra trùng giờ trong cùng ngày
$check = $conn->prepare("
    SELECT COUNT(*) AS cnt
    FROM lich_hen
    WHERE benh_nhan_id=? AND ngay=? AND (
        (? BETWEEN gio_bat_dau AND gio_ket_thuc)
        OR (? BETWEEN gio_bat_dau AND gio_ket_thuc)
        OR (gio_bat_dau BETWEEN ? AND ?)
        OR (gio_ket_thuc BETWEEN ? AND ?)
    )
");
$check->bind_param(
    "isssssss",
    $benh_nhan_id,
    $slot['ngay'],
    $slot['gio_bat_dau'],
    $slot['gio_ket_thuc'],
    $slot['gio_bat_dau'],
    $slot['gio_ket_thuc'],
    $slot['gio_bat_dau'],
    $slot['gio_ket_thuc']
);
$check->execute();
$count = $check->get_result()->fetch_assoc()['cnt'] ?? 0;
$check->close();

echo json_encode(['trung' => $count > 0]);

<?php
session_name("BACSI_SESSION");
session_start();

include("../includes/connect.php");
$conn->set_charset('utf8mb4');

if (!isset($_SESSION['bac_si_id'])) {
    header("Location: ../login/login.php");
    exit;
}

$bac_si_id = $_SESSION['bac_si_id'];
$matkhaucu = $_POST['matkhau_cu'];
$matkhaumoi = $_POST['matkhau_moi'];

$stmt = $conn->prepare("SELECT mat_khau FROM tai_khoan_bac_si WHERE bac_si_id = ?");
$stmt->bind_param("i", $bac_si_id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();

if ($row && password_verify($matkhaucu, $row['mat_khau'])) {
    $hash = password_hash($matkhaumoi, PASSWORD_DEFAULT);
    $update = $conn->prepare("UPDATE tai_khoan_bac_si SET mat_khau=? WHERE bac_si_id=?");
    $update->bind_param("si", $hash, $bac_si_id);
    $update->execute();
    echo "<p>✅ Cập nhật mật khẩu thành công!</p>";
} else {
    echo "<p style='color:red;'>❌ Mật khẩu cũ không chính xác!</p>";
}
?>

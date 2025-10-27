<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("../includes/connect.php");
if (session_status() !== PHP_SESSION_ACTIVE) session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // === Khi bác sĩ bấm Lưu hồ sơ ===
    $lich_hen_id = intval($_POST['lich_hen_id']);
    $chan_doan = trim($_POST['chan_doan']);
    $dieu_tri = trim($_POST['dieu_tri']);
    $ghi_chu = trim($_POST['ghi_chu'] ?? '');

    // Lấy thông tin lịch hẹn
    $stmt = $conn->prepare("SELECT * FROM lich_hen WHERE id = ?");
    $stmt->bind_param("i", $lich_hen_id);
    $stmt->execute();
    $lich = $stmt->get_result()->fetch_assoc();

    if ($lich) {
        $benh_nhan_id = $lich['benh_nhan_id'];
        $bac_si_id = $lich['bac_si_id'];
        $ngay = $lich['ngay'];
        $gio_bat_dau = $lich['gio_bat_dau'];
        $gio_ket_thuc = $lich['gio_ket_thuc'];
        $ly_do = $lich['ly_do'];

        // 1️⃣ Lưu hồ sơ khám
        $insert = $conn->prepare("
            INSERT INTO ho_so_kham (benh_nhan_id, bac_si_id, chan_doan, dieu_tri, ngay_lap)
            VALUES (?, ?, ?, ?, NOW())
        ");
        $insert->bind_param("iiss", $benh_nhan_id, $bac_si_id, $chan_doan, $dieu_tri);
        $insert->execute();

        // 2️⃣ Cập nhật lịch hẹn thành 'Đã khám'
        $update = $conn->prepare("UPDATE lich_hen SET trang_thai = 'Đã khám' WHERE id = ?");
        $update->bind_param("i", $lich_hen_id);
        $update->execute();

        // 3️⃣ Sao lưu sang dat_lich
        $save = $conn->prepare("
            INSERT INTO dat_lich (benh_nhan_id, bac_si_id, ngay_dat, ngay_kham, gio_bat_dau, gio_ket_thuc, trieu_chung, trang_thai, ghi_chu)
            VALUES (?, ?, NOW(), ?, ?, ?, ?, 'Đã khám', ?)
        ");
        $save->bind_param("iisssss", $benh_nhan_id, $bac_si_id, $ngay, $gio_bat_dau, $gio_ket_thuc, $ly_do, $ghi_chu);
        $save->execute();

        echo "<script>alert('✅ Hồ sơ khám đã được lưu thành công!');window.location='bacsi_lich_hen.php';</script>";
        exit;
    } else {
        echo "<p style='color:red;text-align:center;'>❌ Không tìm thấy lịch hẹn!</p>";
    }
} else {
    // === Khi mở form nhập hồ sơ khám ===
    $id = intval($_GET['id'] ?? 0);
    $stmt = $conn->prepare("SELECT lh.*, bn.ho_ten AS ten_bn FROM lich_hen lh 
                            JOIN benh_nhan bn ON lh.benh_nhan_id = bn.id
                            WHERE lh.id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $lich = $stmt->get_result()->fetch_assoc();

    if (!$lich) {
        echo "<p style='color:red;text-align:center;'>❌ Không tìm thấy lịch hẹn!</p>";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Hồ sơ khám bệnh</title>
<link rel="stylesheet" href="../css/bacsi_style.css">
<style>
body {
  font-family: Arial, sans-serif;
  background: #f2f8ff;
  margin-top: 80px; /* ⚠️ thêm dòng này */
}
.container {
  width: 90%; max-width: 700px; background: #fff;
  margin: 40px auto; padding: 25px; border-radius: 15px;
  box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}
h2 { text-align: center; color: #004aad; }
label { font-weight: bold; color: #003366; margin-top: 10px; display:block; }
input, textarea {
  width: 100%; padding: 10px; margin-top: 5px;
  border-radius: 8px; border: 1px solid #ccc;
}
button {
  display:block; margin: 20px auto; background: #007bff;
  color:#fff; border:none; padding:12px 25px; border-radius:20px;
  cursor:pointer; font-weight:bold;
}
button:hover { background:#005ad3; }
</style>
</head>
<body>

<div class="container">
  <h2>Hồ sơ khám bệnh</h2>
  <p><b>Bệnh nhân:</b> <?= htmlspecialchars($lich['ten_bn']) ?></p>
  <p><b>Ngày khám:</b> <?= htmlspecialchars($lich['ngay']) ?></p>
  <p><b>Giờ khám:</b> <?= htmlspecialchars($lich['gio_bat_dau']) ?> - <?= htmlspecialchars($lich['gio_ket_thuc']) ?></p>

  <form method="POST">
    <input type="hidden" name="lich_hen_id" value="<?= $lich['id'] ?>">
    
    <label>Chẩn đoán</label>
    <textarea name="chan_doan" required></textarea>

    <label>Phác đồ điều trị</label>
    <textarea name="dieu_tri" required></textarea>

    <label>Ghi chú thêm</label>
    <textarea name="ghi_chu"></textarea>

    <button type="submit">Lưu hồ sơ khám</button>
  </form>
</div>

</body>
</html>

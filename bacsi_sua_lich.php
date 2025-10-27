<?php
include("bacsi_header.php");
include("../includes/connect.php");

$id = $_GET['id'] ?? 0;
$bac_si_id = $_SESSION['bac_si_id'];

// Lấy thông tin lịch cần sửa
$stmt = $conn->prepare("SELECT * FROM lich_ranh WHERE id=? AND bac_si_id=?");
$stmt->bind_param("ii", $id, $bac_si_id);
$stmt->execute();
$lich = $stmt->get_result()->fetch_assoc();

if (!$lich) {
    echo "<script>alert('❌ Không tìm thấy lịch!'); window.location='bacsi_lich_ranh.php';</script>";
    exit;
}

function tinhThu($ngay) {
    $thu = date('w', strtotime($ngay));
    $days = ['Chủ nhật','Thứ hai','Thứ ba','Thứ tư','Thứ năm','Thứ sáu','Thứ bảy'];
    return $days[$thu];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ngay = $_POST['ngay'];
    $gio_bat_dau = $_POST['gio_bat_dau'];
    $gio_ket_thuc = $_POST['gio_ket_thuc'];
    $thu = tinhThu($ngay);

    // Kiểm tra hợp lệ
    if (strtotime($ngay) < strtotime(date('Y-m-d'))) {
        echo "<script>alert('⚠️ Ngày đã qua, không thể sửa!'); window.history.back();</script>";
        exit;
    }
    if ($gio_bat_dau >= $gio_ket_thuc) {
        echo "<script>alert('⚠️ Giờ bắt đầu phải nhỏ hơn giờ kết thúc!'); window.history.back();</script>";
        exit;
    }

    // Kiểm tra trùng lịch
    $check = $conn->prepare("
        SELECT * FROM lich_ranh 
        WHERE bac_si_id=? AND ngay=? AND id<>? 
          AND ((gio_bat_dau <= ? AND gio_ket_thuc > ?) 
            OR (gio_bat_dau < ? AND gio_ket_thuc >= ?))
    ");
    $check->bind_param("isissss", $bac_si_id, $ngay, $id, $gio_bat_dau, $gio_bat_dau, $gio_ket_thuc, $gio_ket_thuc);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('⚠️ Trùng với lịch khác!'); window.history.back();</script>";
        exit;
    }

    // Cập nhật lịch
    $update = $conn->prepare("UPDATE lich_ranh SET ngay=?, thu=?, gio_bat_dau=?, gio_ket_thuc=? WHERE id=?");
    $update->bind_param("ssssi", $ngay, $thu, $gio_bat_dau, $gio_ket_thuc, $id);
    $update->execute();

    echo "<script>alert('✅ Cập nhật thành công!'); window.location='bacsi_lich_ranh.php';</script>";
    exit;
}
?>

<div class="container">
  <h2 style="color:#0d47a1;">✏️ Sửa lịch rảnh</h2>
  <form method="POST">
    <label>Ngày:</label><br>
    <input type="date" name="ngay" value="<?= $lich['ngay'] ?>" required onchange="capNhatThu()" id="ngay" style="width:50%; padding:8px;"><br><br>

    <label>Thứ:</label><br>
    <input type="text" name="thu" id="thu" value="<?= $lich['thu'] ?>" readonly style="width:50%; padding:8px; background:#f5f5f5;"><br><br>

    <label>Giờ bắt đầu:</label><br>
    <input type="time" name="gio_bat_dau" value="<?= $lich['gio_bat_dau'] ?>" required style="width:50%; padding:8px;"><br><br>

    <label>Giờ kết thúc:</label><br>
    <input type="time" name="gio_ket_thuc" value="<?= $lich['gio_ket_thuc'] ?>" required style="width:50%; padding:8px;"><br><br>

    <button style="background:#64b5f6; color:white; border:none; padding:8px 15px; border-radius:5px;">Cập nhật</button>
  </form>
</div>

<script>
function capNhatThu() {
    const dateInput = document.getElementById('ngay').value;
    if (dateInput) {
        const d = new Date(dateInput);
        const days = ['Chủ nhật','Thứ hai','Thứ ba','Thứ tư','Thứ năm','Thứ sáu','Thứ bảy'];
        document.getElementById('thu').value = days[d.getDay()];
    } else {
        document.getElementById('thu').value = '';
    }
}
</script>

<?php include("bacsi_footer.php"); ?>

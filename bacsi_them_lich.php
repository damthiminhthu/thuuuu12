<?php
include("bacsi_header.php");
include("../includes/connect.php");

function tinhThu($ngay) {
    $thu = date('w', strtotime($ngay)); // 0 = Chủ nhật
    $days = ['Chủ nhật', 'Thứ hai', 'Thứ ba', 'Thứ tư', 'Thứ năm', 'Thứ sáu', 'Thứ bảy'];
    return $days[$thu];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $bac_si_id = $_SESSION['bac_si_id'];
    $ngay = $_POST['ngay'];
    $gio_bat_dau = $_POST['gio_bat_dau'];
    $gio_ket_thuc = $_POST['gio_ket_thuc'];
    $thu = tinhThu($ngay);
    $trang_thai = "Trống";

    // Kiểm tra ngày không nằm trong quá khứ
    if (strtotime($ngay) < strtotime(date('Y-m-d'))) {
        echo "<script>alert('⚠️ Ngày đã qua, không thể thêm lịch!'); window.history.back();</script>";
        exit;
    }

    // Kiểm tra giờ bắt đầu < giờ kết thúc
    if ($gio_bat_dau >= $gio_ket_thuc) {
        echo "<script>alert('⚠️ Giờ bắt đầu phải nhỏ hơn giờ kết thúc!'); window.history.back();</script>";
        exit;
    }

    // Kiểm tra trùng lịch rảnh
    $check = $conn->prepare("
        SELECT * FROM lich_ranh 
        WHERE bac_si_id=? AND ngay=? 
          AND ((gio_bat_dau <= ? AND gio_ket_thuc > ?) 
            OR (gio_bat_dau < ? AND gio_ket_thuc >= ?))
    ");
    $check->bind_param("isssss", $bac_si_id, $ngay, $gio_bat_dau, $gio_bat_dau, $gio_ket_thuc, $gio_ket_thuc);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('⚠️ Bác sĩ đã có lịch trong khung giờ này!'); window.history.back();</script>";
        exit;
    }

    // Thêm lịch rảnh mới
    $sql = "INSERT INTO lich_ranh (bac_si_id, ngay, thu, gio_bat_dau, gio_ket_thuc, trang_thai)
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssss", $bac_si_id, $ngay, $thu, $gio_bat_dau, $gio_ket_thuc, $trang_thai);

    if ($stmt->execute()) {
        echo "<script>alert('✅ Thêm lịch rảnh thành công!'); window.location='bacsi_lich_ranh.php';</script>";
    } else {
        echo "<script>alert('❌ Lỗi khi thêm lịch!'); window.history.back();</script>";
    }
}
?>
<head>
<meta charset="UTF-8">
<title>Thêm lịch rảnh</title>
<link rel="stylesheet" href="../css/bacsi_style.css">
</head>

<style>
.container {
  max-width: 700px;
  margin: 30px auto;
  background: #fff;
  border-radius: 15px;
  padding: 30px 40px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
  font-family: "Segoe UI", sans-serif;
}

h2 {
  color: #0d47a1;
  text-align: center;
  margin-bottom: 25px;
}

form {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 4px;
}

.form-group {
  width: 100%;
  max-width: 380px;
  margin-bottom: 10px;
}

label {
  font-weight: 600;
  color: #333;
  display: block;
  margin-bottom: 4px;
}

input[type="date"],
input[type="time"],
input[type="text"] {
  width: 100%;
  padding: 8px;
  border: 1px solid #cfd8dc;
  border-radius: 6px;
  font-size: 15px;
  background-color: #fafafa;
  transition: 0.2s;
}

input[type="date"]:focus,
input[type="time"]:focus,
input[type="text"]:focus {
  background-color: #fff;
  border-color: #64b5f6;
  outline: none;
  box-shadow: 0 0 4px rgba(100,181,246,0.6);
}

/* ✅ Nút Lưu xanh lá đồng bộ */
button {
  background-color: #4caf50;
  color: white;
  border: none;
  padding: 9px 20px;
  border-radius: 6px;
  cursor: pointer;
  font-size: 15px;
  font-weight: 600;
  transition: 0.2s;
  margin-top: 12px;
  box-shadow: 0 2px 5px rgba(76,175,80,0.3);
}

button:hover {
  background-color: #388e3c;
}
</style>

<div class="container">
  <h2>➕ Thêm lịch rảnh</h2>
  <form method="POST">
    <div class="form-group">
      <label>Ngày:</label>
      <input type="date" name="ngay" id="ngay" required onchange="capNhatThu()">
    </div>

    <div class="form-group">
      <label>Thứ:</label>
      <input type="text" name="thu" id="thu" readonly style="background:#f5f5f5;">
    </div>

    <div class="form-group">
      <label>Giờ bắt đầu:</label>
      <input type="time" name="gio_bat_dau" required>
    </div>

    <div class="form-group">
      <label>Giờ kết thúc:</label>
      <input type="time" name="gio_ket_thuc" required>
    </div>

    <button type="submit">Lưu lịch rảnh</button>
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

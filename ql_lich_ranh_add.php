<?php
include("../includes/connect.php");
include("../includes/header.php");

// Xử lý thêm lịch rảnh
if (isset($_POST['add'])) {
    $bac_si_id = $_POST['bac_si_id'];
    $ngay = $_POST['ngay'];
    $thu = $_POST['thu'];
    $gio_bat_dau = $_POST['gio_bat_dau'];
    $gio_ket_thuc = $_POST['gio_ket_thuc'];
    $trang_thai = $_POST['trang_thai'];

    $check = $conn->prepare("SELECT id FROM lich_ranh WHERE bac_si_id=? AND ngay=? AND gio_bat_dau=?");
    $check->bind_param("iss", $bac_si_id, $ngay, $gio_bat_dau);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo "<script>alert('⚠️ Lịch rảnh này đã tồn tại!');</script>";
    } else {
        $sql = "INSERT INTO lich_ranh (bac_si_id, ngay, thu, gio_bat_dau, gio_ket_thuc, trang_thai)
                VALUES ('$bac_si_id', '$ngay', '$thu', '$gio_bat_dau', '$gio_ket_thuc', '$trang_thai')";
        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('✅ Thêm lịch rảnh thành công!'); window.location='ql_lich_ranh.php';</script>";
        } else {
            echo "<script>alert('❌ Lỗi khi thêm lịch!');</script>";
        }
    }
}
?>

<style>
body {
  font-family: "Segoe UI", Arial, sans-serif;
  background: #f4f8ff;
  margin: 0;
  padding: 0;
  margin-left: 5cm;
}
.container {
  max-width: 700px;
  margin: 40px auto;
  background: #fff;
  border-radius: 16px;
  box-shadow: 0 4px 16px rgba(0,0,0,0.08);
  padding: 30px 40px;
  text-align: center;
}
h2 {
  color: #1565c0;
  font-weight: 700;
  margin-bottom: 25px;
}
form {
  display: flex;
  flex-direction: column;
  gap: 18px;
}
label {
  font-weight: 600;
  color: #333;
  text-align: left;
}
input[type="text"],
input[type="date"],
input[type="time"],
select {
  width: 100%;
  padding: 10px 14px;
  border: 1.5px solid #cfd8dc;
  border-radius: 8px;
  font-size: 15px;
  background: #fafbfc;
  transition: 0.2s;
}
input:focus, select:focus {
  border-color: #1976d2;
  background: #fff;
  outline: none;
}
.button-group {
  display: flex;
  justify-content: center;
  gap: 12px;
  margin-top: 15px;
}
.btn {
  border: none;
  border-radius: 8px;
  color: #fff;
  padding: 9px 20px;
  font-size: 15px;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.25s ease;
  box-shadow: 0 3px 6px rgba(0,0,0,0.1);
}
.btn-save { background: #43a047; }
.btn-save:hover { background: #2e7d32; }
.btn-cancel { background: #757575; }
.btn-cancel:hover { background: #616161; }
</style>

<div class="container">
  <h2>➕ Thêm Lịch Rảnh Mới</h2>

  <form method="POST" id="lichRanhForm">
    <label>Chuyên khoa:</label>
    <select id="chuyen_khoa" required>
      <option value="">-- Chọn chuyên khoa --</option>
      <?php
      $sql = "SELECT id, ten_chuyen_khoa FROM chuyen_khoa ORDER BY ten_chuyen_khoa";
      $rs = mysqli_query($conn, $sql);
      while ($row = mysqli_fetch_assoc($rs)) {
        echo "<option value='{$row['id']}'>{$row['ten_chuyen_khoa']}</option>";
      }
      ?>
    </select>

    <label>Bác sĩ:</label>
    <select name="bac_si_id" id="bac_si" required>
      <option value="">-- Chọn bác sĩ --</option>
    </select>

    <label>Ngày:</label>
    <input type="date" name="ngay" required>

    <label>Thứ:</label>
    <select name="thu" required>
      <option value="">-- Chọn thứ --</option>
      <option value="Thứ 2">Thứ 2</option>
      <option value="Thứ 3">Thứ 3</option>
      <option value="Thứ 4">Thứ 4</option>
      <option value="Thứ 5">Thứ 5</option>
      <option value="Thứ 6">Thứ 6</option>
      <option value="Thứ 7">Thứ 7</option>
      <option value="Chủ nhật">Chủ nhật</option>
    </select>

    <label>Giờ bắt đầu:</label>
    <input type="time" name="gio_bat_dau" required>

    <label>Giờ kết thúc:</label>
    <input type="time" name="gio_ket_thuc" required>

    <label>Trạng thái:</label>
    <select name="trang_thai" required>
      <option value="Trống">Trống</option>
      <option value="Đã đặt">Đã đặt</option>
    </select>

    <div class="button-group">
      <button type="submit" name="add" class="btn btn-save">💾 Lưu</button>
      <a href="ql_lich_ranh.php" class="btn btn-cancel">⬅ Quay lại</a>
    </div>
  </form>
</div>

<script>
// AJAX load bác sĩ theo chuyên khoa
document.getElementById('chuyen_khoa').addEventListener('change', function() {
  var id = this.value;
  var xhr = new XMLHttpRequest();
  xhr.open('GET', 'get_bacsi.php?chuyen_khoa_id=' + id, true);
  xhr.onload = function() {
    if (xhr.status === 200) {
      document.getElementById('bac_si').innerHTML = xhr.responseText;
    }
  };
  xhr.send();
});
</script>

<?php include("../includes/footer.php"); ?>

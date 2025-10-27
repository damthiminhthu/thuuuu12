<?php
include("../includes/connect.php");
include("../includes/header.php");

// Xử lý thêm lịch hẹn
if (isset($_POST['add'])) {
    $benh_nhan_id = $_POST['benh_nhan_id'];
    $bac_si_id = $_POST['bac_si_id'];
    $ngay = $_POST['ngay'];
    $gio_bat_dau = $_POST['gio_bat_dau'];
    $gio_ket_thuc = $_POST['gio_ket_thuc'];
    $ly_do = $_POST['ly_do'];
    $trang_thai = "Chờ xác nhận";

    $sql = "INSERT INTO lich_hen (benh_nhan_id, bac_si_id, ngay, gio_bat_dau, gio_ket_thuc, ly_do, trang_thai)
            VALUES ('$benh_nhan_id', '$bac_si_id', '$ngay', '$gio_bat_dau', '$gio_ket_thuc', '$ly_do', '$trang_thai')";
    if (mysqli_query($conn, $sql)) {
        mysqli_query($conn, "UPDATE lich_ranh 
                             SET trang_thai='Đã đặt' 
                             WHERE bac_si_id='$bac_si_id' 
                             AND ngay='$ngay' 
                             AND gio_bat_dau='$gio_bat_dau'");
        echo "<script>alert('✅ Thêm lịch hẹn thành công!'); window.location='ql_lichhen.php';</script>";
    } else {
        echo "<script>alert('❌ Lỗi khi thêm lịch hẹn!');</script>";
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
  max-width: 850px;
  margin: 40px auto;
  background: #fff;
  border-radius: 16px;
  box-shadow: 0 4px 16px rgba(0,0,0,0.08);
  padding: 30px 40px;
  text-align: center;
  margin-left: 10cm;
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
select,
textarea {
  width: 100%;
  padding: 10px 14px;
  border: 1.5px solid #cfd8dc;
  border-radius: 8px;
  font-size: 15px;
  background: #fafbfc;
  transition: 0.2s;
}
input:focus, select:focus, textarea:focus {
  border-color: #1976d2;
  background: #fff;
  outline: none;
}
textarea { min-height: 80px; resize: none; }
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
  <h2>Đặt Lịch Hẹn Mới</h2>

  <form method="POST">
    <label>Chuyên khoa:</label>
    <select id="chuyen_khoa" required>
      <option value="">-- Chọn chuyên khoa --</option>
      <?php
      $rs = mysqli_query($conn, "SELECT id, ten_chuyen_khoa FROM chuyen_khoa ORDER BY ten_chuyen_khoa ASC");
      while ($r = mysqli_fetch_assoc($rs)) {
        echo "<option value='{$r['id']}'>{$r['ten_chuyen_khoa']}</option>";
      }
      ?>
    </select>

    <label>Ngày hẹn:</label>
    <input type="date" id="ngay" required>

    <label>Bác sĩ rảnh trong ngày:</label>
    <select id="bac_si" name="bac_si_id" required>
      <option value="">-- Chọn bác sĩ --</option>
    </select>

    <label>Khung giờ:</label>
    <select id="gio_bat_dau" name="gio_bat_dau" required>
      <option value="">-- Chọn giờ --</option>
    </select>
    <label>Bệnh nhân:</label>
    <select name="benh_nhan_id" required>
      <option value="">-- Chọn bệnh nhân --</option>
      <?php
      $bn = mysqli_query($conn, "SELECT id, ho_ten FROM benh_nhan ORDER BY ho_ten ASC");
      while ($r = mysqli_fetch_assoc($bn)) {
        echo "<option value='{$r['id']}'>{$r['ho_ten']}</option>";
      }
      ?>
    </select>

    <label>Lý do khám:</label>
    <textarea name="ly_do" placeholder="Nhập lý do khám..."></textarea>

    <div class="button-group">
      <button type="submit" name="add" class="btn btn-save">Lưu</button>
      <a href="ql_lichhen.php" class="btn btn-cancel">Quay lại</a>
    </div>
  </form>
</div>

<script>
// Lấy danh sách bác sĩ rảnh theo chuyên khoa + ngày
document.getElementById('chuyen_khoa').addEventListener('change', loadDoctors);
document.getElementById('ngay').addEventListener('change', loadDoctors);

function loadDoctors(){
  const khoa = document.getElementById('chuyen_khoa').value;
  const ngay = document.getElementById('ngay').value;
  const selectBs = document.getElementById('bac_si');
  if(!khoa || !ngay){ selectBs.innerHTML='<option value="">-- Chọn bác sĩ --</option>'; return; }

  fetch('get_bacsi_ranh.php?khoa_id='+khoa+'&ngay='+ngay)
  .then(r=>r.text())
  .then(html => selectBs.innerHTML = html);
}

// Lấy khung giờ rảnh khi chọn bác sĩ
document.getElementById('bac_si').addEventListener('change', function(){
  const bs_id = this.value;
  const ngay = document.getElementById('ngay').value;
  const selGio = document.getElementById('gio_bat_dau');
  if(!bs_id || !ngay){ selGio.innerHTML='<option value="">-- Chọn giờ bắt đầu --</option>'; return; }

  fetch('get_khung_gio.php?bac_si_id='+bs_id+'&ngay='+ngay)
  .then(r=>r.json())
  .then(data=>{
    selGio.innerHTML = '<option value="">-- Chọn giờ bắt đầu --</option>';
    data.forEach(d=>{
      const opt=document.createElement('option');
      opt.value=d.gio_bat_dau;
      opt.textContent=`${d.gio_bat_dau} - ${d.gio_ket_thuc}`;
      opt.setAttribute('data-end', d.gio_ket_thuc);
      selGio.appendChild(opt);
    });
  });
});

// Tự điền giờ kết thúc
document.getElementById('gio_bat_dau').addEventListener('change', function(){
  const end = this.options[this.selectedIndex].getAttribute('data-end');
  document.getElementById('gio_ket_thuc').value = end || '';
});
</script>

<?php include("../includes/footer.php"); ?>

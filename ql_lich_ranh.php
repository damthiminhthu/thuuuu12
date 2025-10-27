<?php
include("../includes/connect.php");
include("../includes/header.php");

$selected_khoa = isset($_GET['chuyen_khoa_id']) ? intval($_GET['chuyen_khoa_id']) : 0;
?>

<style>
/* giống file thêm bệnh nhân */
body { font-family: "Segoe UI", Arial, sans-serif; background: #f4f8ff; margin: 0; padding: 0; margin-left: 5cm; }
.container { max-width: 1000px; margin: 40px auto; background: #fff; border-radius: 16px; box-shadow: 0 4px 16px rgba(0,0,0,0.08); padding: 30px 40px; margin-left: 3cm; }
h2 { color: #1565c0; font-weight: 700; margin-bottom: 25px; text-align: center; }
.table { width: 100%; border-collapse: collapse; margin-top: 20px; }
.table th, .table td { border: 1px solid #ddd; padding: 10px 12px; text-align: center; }
.table th { background-color: #1976d2; color: white; }
.table tr:nth-child(even) { background-color: #f9f9f9; }
select { padding: 8px; border-radius: 8px; border: 1px solid #cfd8dc; font-size: 15px; }
.btn { border: none; border-radius: 6px; color: #fff; padding: 6px 14px; font-size: 14px; font-weight: 500; cursor: pointer; text-decoration: none; }
.btn-add { background: #43a047; } .btn-add:hover { background: #2e7d32; }
</style>

<div class="container">
  <h2>Quản lý Lịch Rảnh Bác Sĩ</h2>

  <form method="GET" style="text-align:center; margin-bottom:20px; margin-right:12cm;">
    <label><b>Chọn chuyên khoa:</b></label>
    <select name="chuyen_khoa_id" onchange="this.form.submit()">
      <option value="">-- Tất cả chuyên khoa --</option>
      <?php
      $rs = mysqli_query($conn, "SELECT * FROM chuyen_khoa");
      while ($row = mysqli_fetch_assoc($rs)) {
        $sel = ($selected_khoa == $row['id']) ? "selected" : "";
        echo "<option value='{$row['id']}' $sel>{$row['ten_chuyen_khoa']}</option>";
      }
      ?>
    </select>
  </form>
  <table class="table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Bác sĩ</th>
        <th>Chuyên khoa</th>
        <th>Ngày</th>
        <th>Thứ</th>
        <th>Giờ bắt đầu</th>
        <th>Giờ kết thúc</th>
        <th>Trạng thái</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $sql = "
        SELECT lr.*, bs.ho_ten AS ten_bac_si, ck.ten_chuyen_khoa 
        FROM lich_ranh lr
        JOIN bac_si bs ON lr.bac_si_id = bs.id
        JOIN chuyen_khoa ck ON bs.chuyen_khoa_id = ck.id
      ";
      if ($selected_khoa > 0) $sql .= " WHERE ck.id = $selected_khoa";
      $sql .= " ORDER BY lr.ngay DESC";
      $rs = mysqli_query($conn, $sql);
      while ($r = mysqli_fetch_assoc($rs)) {
        echo "<tr>
          <td>{$r['id']}</td>
          <td>{$r['ten_bac_si']}</td>
          <td>{$r['ten_chuyen_khoa']}</td>
          <td>{$r['ngay']}</td>
          <td>{$r['thu']}</td>
          <td>{$r['gio_bat_dau']}</td>
          <td>{$r['gio_ket_thuc']}</td>
          <td>{$r['trang_thai']}</td>
        </tr>";
      }
      ?>
    </tbody>
  </table>
</div>

<?php include("../includes/footer.php"); ?>

<?php
include("../includes/connect.php");
include("../includes/header.php");
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
  max-width: 1000px;
  margin: 40px auto;
  background: #fff;
  border-radius: 12px;
  box-shadow: 0 4px 16px rgba(0,0,0,0.08);
  padding: 30px 40px;
  margin-left: 2.5cm;
}
h2 {
  color: #1565c0;
  font-weight: 700;
  margin-bottom: 25px;
  text-align: center;
}
.table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
}
.table th, .table td {
  border: 1px solid #ddd;
  padding: 10px 12px;
  text-align: center;
}
.table th {
  background-color: #1976d2;
  color: white;
}
.table tr:nth-child(even) {
  background-color: #f9f9f9;
}
.btn {
  border: none;
  border-radius: 6px;
  color: #fff;
  padding: 8px 14px;
  font-size: 14px;
  font-weight: 500;
  cursor: pointer;
  text-decoration: none;
  margin-right: 790px;
}
.btn-add {
  background: #43a047;
}
.btn-add:hover {
  background: #2e7d32;
}
</style>

<div class="container">
  <h2>📅 Quản lý Lịch Hẹn</h2>

  <div style="text-align:center; margin-bottom:20px;">
    <a href="ql_lichhen_add.php" class="btn btn-add">Thêm Lịch Hẹn</a>
  </div>

  <table class="table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Bệnh nhân</th>
        <th>Bác sĩ</th>
        <th>Chuyên khoa</th>
        <th>Ngày</th>
        <th>Giờ bắt đầu</th>
        <th>Giờ kết thúc</th>
        <th>Lý do</th>
        <th>Trạng thái</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $sql = "
        SELECT lh.*, bn.ho_ten AS ten_bn, bs.ho_ten AS ten_bs, ck.ten_chuyen_khoa
        FROM lich_hen lh
        JOIN benh_nhan bn ON lh.benh_nhan_id = bn.id
        JOIN bac_si bs ON lh.bac_si_id = bs.id
        JOIN chuyen_khoa ck ON bs.chuyen_khoa_id = ck.id
        ORDER BY lh.ngay DESC, lh.gio_bat_dau ASC
      ";
      $rs = mysqli_query($conn, $sql);
      while ($r = mysqli_fetch_assoc($rs)) {
        echo "
          <tr>
            <td>{$r['id']}</td>
            <td>{$r['ten_bn']}</td>
            <td>{$r['ten_bs']}</td>
            <td>{$r['ten_chuyen_khoa']}</td>
            <td>{$r['ngay']}</td>
            <td>{$r['gio_bat_dau']}</td>
            <td>{$r['gio_ket_thuc']}</td>
            <td>{$r['ly_do']}</td>
            <td>{$r['trang_thai']}</td>
          </tr>
        ";
      }
      ?>
    </tbody>
  </table>
</div>

<?php include("../includes/footer.php"); ?>

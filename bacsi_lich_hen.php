<?php
session_start();
include("../includes/connect.php");
include("bacsi_header.php");

if (!isset($_SESSION['bac_si_id'])) {
    header("Location: ../bacsi/login.php");
    exit;
}

$bac_si_id = $_SESSION['bac_si_id'];

// ✅ Chỉ hiển thị các lịch chưa hủy và chưa khám xong
$sql = "
  SELECT lh.*, bn.ho_ten AS ten_benh_nhan, bn.sdt, bn.gmail_bn AS email
  FROM lich_hen lh
  JOIN benh_nhan bn ON lh.benh_nhan_id = bn.id
  WHERE lh.bac_si_id = $bac_si_id
    AND lh.trang_thai NOT IN ('Đã hủy', 'Đã khám')
  ORDER BY lh.ngay DESC
";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Lịch hẹn của tôi</title>
<link rel="stylesheet" href="../css/bacsi_style.css">
<style>
body {
  font-family: Arial, sans-serif;
  background: #f4f8ff;
  margin: 0;
  padding: 0;
}
.container {
  width: 85%;
  margin: 60px auto;
  background: #fff;
  border-radius: 16px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
  padding: 30px 40px;
  text-align: center;
}
h2 {
  color: #0d6efd;
  font-weight: 700;
  margin-bottom: 25px;
}
table {
  width: 100%;
  border-collapse: collapse;
  border-radius: 12px;
  overflow: hidden;
  font-size: 15px;
}
th, td {
  padding: 12px;
  text-align: center;
  border-bottom: 1px solid #e0e0e0;
}
th {
  background-color: #0d6efd;
  color: white;
  font-weight: 600;
}
tr:hover { background-color: #f1f8ff; }
.btn {
  border: none;
  border-radius: 8px;
  padding: 7px 14px;
  color: #fff;
  font-weight: 600;
  cursor: pointer;
  transition: 0.25s;
  text-decoration: none;
}
.btn-approve { background: #43a047; }
.btn-approve:hover { background: #2e7d32; }
.btn-cancel { background: #e53935; }
.btn-cancel:hover { background: #c62828; }
/* ===================== FOOTER ===================== */
.footer {
  background: #0d6efd;
  color: white;
  text-align: center;
  padding: 15px 10px;
  font-size: 14px;
  line-height: 1.5;
}
</style>
</head>

<body>
<div class="container">
  <h2>📅 Lịch hẹn của tôi</h2>
  <table>
    <thead>
      <tr>
        <th>Bệnh nhân</th>
        <th>SĐT</th>
        <th>Email</th>
        <th>Ngày</th>
        <th>Giờ</th>
        <th>Trạng thái</th>
        <th>Hành động</th>
      </tr>
    </thead>
    <tbody>
      <?php 
      if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          $ngay = date('d/m/Y', strtotime($row['ngay']));
          $gio = date('H:i', strtotime($row['gio_bat_dau']));
          echo "<tr>
            <td>{$row['ten_benh_nhan']}</td>
            <td>{$row['sdt']}</td>
            <td>{$row['email']}</td>
            <td>$ngay</td>
            <td>$gio</td>
            <td>{$row['trang_thai']}</td>
            <td>";

          // ⚙️ Hiển thị nút theo trạng thái
          if ($row['trang_thai'] == 'Chờ xác nhận') {
              echo "<a href='bacsi_lich_hen_action.php?action=duyet&id={$row['id']}' class='btn btn-approve'>Duyệt</a> ";
              echo "<a href='bacsi_lich_hen_action.php?action=huy&id={$row['id']}' class='btn btn-cancel'>Hủy</a>";
          } elseif ($row['trang_thai'] == 'Đã xác nhận') {
              echo "<a href='bacsi_ho_so_kham.php?id={$row['id']}' class='btn btn-approve'>Khám</a>";
          } else {
              echo "<span>—</span>";
          }

          echo "</td></tr>";
        }
      } else {
        echo "<tr><td colspan='7'>Không có lịch hẹn nào.</td></tr>";
      }
      ?>
    </tbody>
  </table>
</div>
<?php include("bacsi_footer.php"); ?>
</body>
</html>
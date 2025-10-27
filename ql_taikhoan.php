<?php
include("../includes/connect.php");
include("../includes/header.php");

$result = mysqli_query($conn, "SELECT * FROM tai_khoan_admin ORDER BY id_admin ASC");
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
  max-width: 900px;
  margin: 40px auto;
  background: #fff;
  border-radius: 16px;
  box-shadow: 0 4px 16px rgba(0,0,0,0.08);
  padding: 30px 40px;
  text-align: center;
  margin-left: 3.5cm;
}
h2 {
  color: #1565c0;
  font-weight: 700;
  margin-bottom: 25px;
}
table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 10px;
}
th, td {
  padding: 12px;
  text-align: center;
  border-bottom: 1px solid #e0e0e0;
}
th {
  background-color: #1565c0;
  color: white;
  font-weight: 600;
}
tr:hover { background-color: #f1f8ff; }

</style>

<div class="container">
  <h2>Quản Lý Tài Khoản Admin</h2>
  
  <table>
    <tr>
      <th>ID Admin</th>
      <th>Tên đăng nhập</th>
      <th>Mật khẩu</th>
    </tr>

    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
      <tr>
        <td><?= $row['id_admin'] ?></td>
        <td><?= htmlspecialchars($row['tendangnhap']) ?></td>
        <td><?= htmlspecialchars($row['matkhau']) ?></td>
      </tr>
    <?php } ?>
  </table>
</div>

<?php include("../includes/footer.php"); ?>

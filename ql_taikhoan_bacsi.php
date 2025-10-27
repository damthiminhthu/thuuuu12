<?php
include("../includes/header.php");
include("../includes/connect.php");

// Cập nhật trạng thái (Khóa / Mở khóa)
if (isset($_GET['toggle']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $new_status = intval($_GET['toggle']);
    $conn->query("UPDATE tai_khoan_bac_si SET is_active = $new_status WHERE id = $id");
    header("Location: ql_taikhoan_bacsi.php");
    exit;
}

// Lấy danh sách tài khoản
$sql = "SELECT id, ten_dang_nhap, email, tao_luc, is_active FROM tai_khoan_bac_si ORDER BY id DESC";
$res = $conn->query($sql);
?>

<style>
body {
  font-family: "Segoe UI", Arial, sans-serif;
  background: #e3f2fd;
  margin: 0;
  padding: 0;
  margin-left: 2cm;
}

.container {
  max-width: 1000px;
  margin: 30px auto;
  background: #ffffff;
  border-radius: 16px;
  box-shadow: 0 4px 14px rgba(0, 0, 0, 0.08);
  padding: 25px 35px;
  margin-left: 6cm;
}

/* ======= TIÊU ĐỀ ======= */
h2 {
  color: #1565c0;
  font-weight: 700;
  margin-bottom: 25px;
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 10px;
}
h2 i {
  color: #1565c0;
}

/* ======= BẢNG ======= */
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
  background-color: #1565c0;
  color: white;
  font-weight: 600;
}
tr:hover {
  background-color: #f1f8ff;
}

/* ======= CỘT TRẠNG THÁI ======= */
.status-active {
  color: #2e7d32; /* Xanh lá cây */
  font-weight: 600;
}
.status-locked {
  color: #d32f2f; /* Đỏ đậm */
  font-weight: 600;
}

/* ======= NÚT HÀNH ĐỘNG ======= */
.btn-action {
  border: none;
  color: #fff;
  padding: 8px 16px;
  border-radius: 8px;
  font-weight: 600;
  cursor: pointer;
  transition: 0.25s;
  box-shadow: 0 3px 6px rgba(0,0,0,0.1);
  text-decoration: none;
  display: inline-block;
}

/* Nút khóa */
.btn-lock {
  background: #e53935; /* Đỏ */
}
.btn-lock:hover {
  background: #c62828;
  transform: scale(1.05);
}

/* Nút mở khóa */
.btn-unlock {
  background: #43a047; /* Xanh lá */
}
.btn-unlock:hover {
  background: #2e7d32;
  transform: scale(1.05);
}

/* Responsive */
@media (max-width: 1200px) {
  .container {
    padding: 20px;
  }
  table {
    font-size: 14px;
  }
  th, td {
    padding: 10px;
  }
}
</style>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Quản lý Tài khoản Bác sĩ</title>
</head>

<body>
<div class="main-content">
  <div class="container">
    <h2>🔒 Quản lý Tài khoản Bác sĩ</h2>

    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Tên đăng nhập</th>
          <th>Email</th>
          <th>Ngày tạo</th>
          <th>Trạng thái</th>
          <th>Hành động</th>
        </tr>
      </thead>
      <tbody>
      <?php
      if ($res && $res->num_rows > 0) {
          while ($r = $res->fetch_assoc()) {
              $isActive = intval($r['is_active']);
              $statusText = $isActive ? "Hoạt động" : "Bị khóa";
              $statusClass = $isActive ? "status-active" : "status-locked";
              $btnClass = $isActive ? "btn-lock" : "btn-unlock";
              $btnText = $isActive ? "Khóa tài khoản" : "Mở khóa";
              $toggleVal = $isActive ? 0 : 1;

              echo "
              <tr>
                <td>{$r['id']}</td>
                <td>{$r['ten_dang_nhap']}</td>
                <td>{$r['email']}</td>
                <td>{$r['tao_luc']}</td>
                <td><span class='{$statusClass}'>{$statusText}</span></td>
                <td><a href='?id={$r['id']}&toggle={$toggleVal}' class='btn-action {$btnClass}'>{$btnText}</a></td>
              </tr>";
          }
      } else {
          echo "<tr><td colspan='6'>Không có tài khoản bác sĩ nào.</td></tr>";
      }
      ?>
      </tbody>
    </table>
  </div>
</div>
</body>
</html>

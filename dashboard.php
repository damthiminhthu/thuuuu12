<?php
session_start();
if (empty($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}


// Kết nối CSDL
include '../includes/connect.php';

// --- Đếm tổng bác sĩ ---
$bacsi_count = $conn->query("SELECT COUNT(*) AS total FROM bac_si")->fetch_assoc()['total'];

// --- Đếm tổng bệnh nhân ---
$benhnhan_count = $conn->query("SELECT COUNT(*) AS total FROM benh_nhan")->fetch_assoc()['total'];

// --- Đếm tổng lịch hẹn ---
$lichhen_count = $conn->query("SELECT COUNT(*) AS total FROM lich_hen")->fetch_assoc()['total'];

// --- Đếm lịch hẹn hôm nay ---
$lichhen_homnay_count = $conn->query("SELECT COUNT(*) AS total FROM lich_hen WHERE DATE(ngay) = CURDATE()")->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Trang quản trị - Phòng khám</title>
  <style>
:root {
  --blue-dark: #1565c0;
  --blue-main: #1976d2;
  --blue-light: #e3f2fd;
  --text-light: #ffffff;
}

/* ======= BODY & HEADER ======= */
body {
  font-family: "Segoe UI", Arial, sans-serif;
  background: #f4f8ff;
  margin: 0;
  display: flex;
  flex-direction: column;
  min-height: 100vh;
}

header {
  background: var(--blue-main);
  color: white;
  text-align: center;
  padding: 22px;
  font-size: 22px;
  font-weight: 700;
  letter-spacing: 0.3px;
  line-height: 1.5;
}

/* ======= LINK (bỏ gạch chân) ======= */
a {
  text-decoration: none;
  color: inherit;
}
a:hover {
  text-decoration: none;
  opacity: 0.9;
}

/* ======= DASHBOARD BOX ======= */
.dashboard-container {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
  gap: 25px;
  width: 90%;
  max-width: 1100px;
  margin: 35px auto;
}

.dashboard-box {
  padding: 25px;
  border-radius: 16px;
  color: #fff;
  text-align: center;
  font-weight: 600;
  transition: 0.25s;
  position: relative;
  box-shadow: 0 4px 10px rgba(0,0,0,0.15);
}

.dashboard-box:hover {
  transform: translateY(-6px);
}

.dashboard-box h2 {
  font-size: 42px;
  font-weight: 800;
  color: #ffffff;
  margin-bottom: 8px;
  text-shadow: 0 2px 6px rgba(0, 0, 0, 0.25);
}

.dashboard-box p {
  margin: 0;
  font-size: 17px;
  font-weight: 500;
  color: rgba(255, 255, 255, 0.95);
}

/* ======= MÀU BOX ======= */
.blue { background: linear-gradient(135deg, #42a5f5, #1976d2); }
.green { background: linear-gradient(135deg, #66bb6a, #2e7d32); }
.orange { background: linear-gradient(135deg, #ffb74d, #f57c00); }
.purple { background: linear-gradient(135deg, #ab47bc, #6a1b9a); }

/* ======= BẢNG ======= */
h3 {
  text-align: center;
  color: #1565c0;
  margin-top: 10px;
  font-size: 20px;
  font-weight: 700;
}

table {
  width: 90%;
  max-width: 1100px;
  margin: 20px auto 60px;
  border-collapse: collapse;
  background: white;
  border-radius: 14px;
  overflow: hidden;
  box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

thead {
  background: #1565c0;
  color: white;
}

th, td {
  padding: 14px 16px;
  text-align: center;
  border-bottom: 1px solid #f1f1f1;
}

tbody tr:hover {
  background-color: #f5f9ff;
}

.no-data {
  color: #757575;
  font-style: italic;
  padding: 20px;
}

/* ======= FOOTER ======= */
footer {
  background: #1565c0;
  color: #fff;
  text-align: center;
  padding: 15px;
  margin-top: auto;
  font-weight: 500;
  letter-spacing: 0.2px;
}

/* ======= RESPONSIVE ======= */
@media (max-width: 768px) {
  .dashboard-container {
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 20px;
  }
  header {
    font-size: 18px;
    padding: 15px;
  }
  table {
    font-size: 14px;
  }
}
</style>

</head>
<body>

<header>
  🏥 Xin chào, <?= htmlspecialchars($_SESSION['username']) ?> — Trang quản trị Phòng Khám
  <br>
  <a href="../admin/logout.php" style="color:#fff;font-size:15px;text-decoration:underline;">Đăng xuất</a>
</header>

<div class="dashboard-container">
  <a href="ql_bacsi.php" class="dashboard-box-link">
    <div class="dashboard-box blue">
      <h2><?= $bacsi_count ?></h2>
      <p>👨‍⚕️ Bác sĩ</p>
    </div>
  </a>

  <a href="ql_benhnhan.php" class="dashboard-box-link">
    <div class="dashboard-box green">
      <h2><?= $benhnhan_count ?></h2>
      <p>🧍‍♂️ Bệnh nhân</p>
    </div>
  </a>

  <a href="ql_lichhen.php" class="dashboard-box-link">
    <div class="dashboard-box purple">
      <h2><?= $lichhen_count ?></h2>
      <p>📅 Tổng lịch hẹn</p>
    </div>
  </a>

  <a href="ql_lichhen.php?today=1" class="dashboard-box-link">
    <div class="dashboard-box orange">
      <h2><?= $lichhen_homnay_count ?></h2>
      <p>⏰ Lịch hẹn hôm nay</p>
    </div>
  </a>
</div>

<h3 style="text-align:center;">🗓️ Lịch hẹn gần đây</h3>
<table>
  <thead>
    <tr>
      <th>Bệnh nhân</th>
      <th>Bác sĩ</th>
      <th>Ngày</th>
      <th>Giờ</th>
      <th>Trạng thái</th>
    </tr>
  </thead>
  <tbody>
    <?php 
    $sql = "
      SELECT lh.*, bs.ho_ten AS ten_bac_si, bn.ho_ten AS ten_benh_nhan
      FROM lich_hen lh
      JOIN bac_si bs ON lh.bac_si_id = bs.id
      JOIN benh_nhan bn ON lh.benh_nhan_id = bn.id
      ORDER BY lh.ngay DESC 
      LIMIT 5
    ";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $ngay = date('d/m/Y', strtotime($row['ngay']));
            $gio = date('H:i', strtotime($row['gio_bat_dau']));
            echo "
            <tr>
              <td>".htmlspecialchars($row['ten_benh_nhan'])."</td>
              <td>".htmlspecialchars($row['ten_bac_si'])."</td>
              <td>$ngay</td>
              <td>$gio</td>
              <td>".htmlspecialchars($row['trang_thai'])."</td>
            </tr>";
        }
    } else {
        echo "<tr><td colspan='5' class='no-data'>Không có lịch hẹn gần đây</td></tr>";
    }
    ?>
  </tbody>
</table>

<footer>© <?= date('Y') ?> Phòng Khám Đa Khoa - Hệ thống quản trị</footer>

</body>
</html>

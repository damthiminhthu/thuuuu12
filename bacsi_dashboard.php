<?php
session_start();
include("../includes/connect.php");

// Kiểm tra đăng nhập
if (empty($_SESSION['bac_si_id'])) {
    header("Location: ../admin/login.php");
    exit;
}

$bac_si_id = $_SESSION['bac_si_id'];

// --- Thống kê ---
$total_lichhen = $conn->query("SELECT COUNT(*) AS total FROM lich_hen WHERE bac_si_id = $bac_si_id")->fetch_assoc()['total'];
$today_lichhen = $conn->query("SELECT COUNT(*) AS total FROM lich_hen WHERE bac_si_id = $bac_si_id AND DATE(ngay) = CURDATE()")->fetch_assoc()['total'];
$done_lichhen = $conn->query("SELECT COUNT(*) AS total FROM lich_hen WHERE bac_si_id = $bac_si_id AND trang_thai = 'Hoàn thành'")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Trang tổng quan Bác sĩ</title>
<style>
body {
  font-family: Arial, sans-serif;
  background: #f2f6ff;
  margin: 0;
  padding: 0;
}

/* ===== HEADER ===== */
.header {
  background-color: #1976d2;
  color: white;
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 14px 40px;
}

.header .title {
  font-size: 20px;
  font-weight: bold;
}

.navbar {
  display: flex;
  align-items: center;
  gap: 18px;
}

.navbar a {
  color: white;
  text-decoration: none;
  font-weight: 500;
  font-size: 15px;
  transition: 0.3s;
}

.navbar a:hover {
  opacity: 0.8;
}

.btn-logout {
  background: #e53935;
  padding: 8px 14px;
  border-radius: 6px;
  color: white;
  font-weight: bold;
}

.btn-logout:hover {
  background: #c62828;
}

/* ===== DASHBOARD ===== */
.dashboard {
  width: 90%;
  max-width: 1000px;
  margin: 40px auto;
  background: white;
  padding: 30px 35px 40px 35px;
  border-radius: 20px;
  box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
}

.dashboard h2 {
  text-align: center;
  color: #1565c0;
  margin-bottom: 30px;
  font-size: 20px;
}

/* Ô thống kê */
.stats {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  gap: 25px;
  text-align: center;
  margin-bottom: 35px;
}

.stat-box {
  border-radius: 16px;
  color: white;
  padding: 25px 10px;
  font-weight: 600;
  transition: 0.25s;
  box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

.stat-box h3 {
  font-size: 42px;
  margin: 0;
  font-weight: 800;
}

.stat-box p {
  font-size: 15px;
  margin-top: 6px;
}

.stat-blue { background: linear-gradient(135deg, #42a5f5, #1976d2); }
.stat-orange { background: linear-gradient(135deg, #ffb74d, #f57c00); }
.stat-green { background: linear-gradient(135deg, #66bb6a, #2e7d32); }

.stat-box:hover {
  transform: translateY(-6px);
}

/* ===== TABLE ===== */
table {
  width: 100%;
  border-collapse: collapse;
  background: #fff;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 4px 10px rgba(0,0,0,0.05);
}

thead {
  background: #1976d2;
  color: white;
}

th, td {
  padding: 12px 16px;
  text-align: center;
  border-bottom: 1px solid #f1f1f1;
}

tbody tr:hover {
  background-color: #f5f9ff;
}

.no-data {
  color: #888;
  padding: 15px;
}

/* ===== FOOTER ===== */
footer {
  background: #1976d2;
  color: white;
  text-align: center;
  padding: 12px;
  margin-top: 50px;
}

</style>
</head>
<body>

<!-- HEADER -->
<div class="header">
  <div class="title">👨‍⚕️ Bác sĩ: <?= htmlspecialchars($_SESSION['ten_bac_si']) ?></div>
  <div class="navbar">
    <a href="bacsi_dashboard.php">🏠 Trang chủ</a>
    <a href="bacsi_lich_hen.php">📅 Lịch hẹn</a>
    <a href="bacsi_lich_ranh.php">🕒 Lịch rảnh</a>
    <a href="bacsi_them_lich.php">➕ Thêm lịch</a>
    <a href="bacsi_lich_su.php">📘 Lịch sử</a>
    <a href="../admin/logout.php" class="btn-logout">🚪 Đăng xuất</a>
  </div>
</div>

<div class="dashboard">
  <h2>📊 Tổng quan của tôi</h2>
  <div class="stats">

    <!-- Tổng lịch hẹn -->
    <a href="bacsi_lich_hen.php" style="text-decoration:none;">
      <div class="stat-box stat-blue">
        <h3><?= $total_lichhen ?></h3>
        <p>📅 Tổng số lịch hẹn</p>
      </div>
    </a>

    <!-- Lịch hẹn hôm nay -->
    <a href="bacsi_lich_hen.php?today=1" style="text-decoration:none;">
      <div class="stat-box stat-orange">
        <h3><?= $today_lichhen ?></h3>
        <p>⏰ Lịch hẹn hôm nay</p>
      </div>
    </a>

    <!-- Bệnh nhân đã khám -->
    <a href="bacsi_lich_su.php" style="text-decoration:none;">
      <div class="stat-box stat-green">
        <h3><?= $done_lichhen ?></h3>
        <p>✅ Bệnh nhân đã khám</p>
      </div>
    </a>

  </div>
  <h2>📅 Lịch hẹn sắp tới</h2>
  <table>
    <thead>
      <tr>
        <th>Bệnh nhân</th>
        <th>Ngày</th>
        <th>Giờ bắt đầu</th>
        <th>Trạng thái</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $sql = "
        SELECT lh.*, bn.ho_ten AS ten_benh_nhan
        FROM lich_hen lh
        JOIN benh_nhan bn ON lh.benh_nhan_id = bn.id
        WHERE lh.bac_si_id = $bac_si_id
        ORDER BY lh.ngay DESC, lh.gio_bat_dau ASC
        LIMIT 5
      ";
      $result = $conn->query($sql);
      if ($result && $result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
              $ngay = date('d/m/Y', strtotime($row['ngay']));
              echo "
              <tr>
                <td>".htmlspecialchars($row['ten_benh_nhan'])."</td>
                <td>$ngay</td>
                <td>".htmlspecialchars($row['gio_bat_dau'])."</td>
                <td>".htmlspecialchars($row['trang_thai'])."</td>
              </tr>";
          }
      } else {
          echo "<tr><td colspan='4' class='no-data'>Không có lịch hẹn nào gần đây</td></tr>";
      }
      ?>
    </tbody>
  </table>
</div>
<?php include("bacsi_footer.php"); ?>
</body>
</html>

<?php
// ==== KHỞI TẠO PHIÊN LÀM VIỆC ====
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once("../includes/connect.php");

// ==== LẤY TÊN BÁC SĨ ====
if (!isset($_SESSION['ten_bac_si']) && isset($_SESSION['bac_si_id'])) {
    $id = $_SESSION['bac_si_id'];
    $result = $conn->query("SELECT ho_ten FROM bac_si WHERE id = $id");
    if ($result && $result->num_rows > 0) {
        $_SESSION['ten_bac_si'] = $result->fetch_assoc()['ho_ten'];
    }
}

// Nếu vẫn không có, gán tạm tên chung
$ten_bac_si = htmlspecialchars($_SESSION['ten_bac_si'] ?? "Bác sĩ");
?>

<!-- ===== HEADER BÁC SĨ ===== -->
<header class="doctor-header">
  <div class="header-container">
    <div class="doctor-info">
      👨‍⚕️ Bác sĩ: <?= htmlspecialchars($_SESSION['ten_bac_si']) ?>
    </div>
    <nav class="navbar">
      <a href="bacsi_dashboard.php">🏠 Trang chủ</a>
      <a href="bacsi_lich_hen.php">📅 Lịch hẹn</a>
      <a href="bacsi_lich_ranh.php">🕒 Lịch rảnh</a>
      <a href="bacsi_them_lich.php">➕ Thêm lịch</a>
      <a href="bacsi_lich_su.php">📘 Lịch sử</a>
      <a href="../admin/logout.php" class="btn-logout">🚪 Đăng xuất</a>
    </nav>
  </div>
</header>

<style>
.doctor-header {
  background-color: #1976d2;
  color: white;
  padding: 14px 40px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
  box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

.header-container {
  display: flex;
  flex-wrap: wrap;
  justify-content: space-between;
  align-items: center;
  width: 100%;
}

.doctor-info {
  font-size: 18px;
  font-weight: 700;
}

.navbar {
  display: flex;
  flex-wrap: wrap;
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
  opacity: 0.85;
  text-decoration: none;
}

.btn-logout {
  background: #e53935;
  padding: 8px 14px;
  border-radius: 6px;
  color: white;
  font-weight: 600;
}

.btn-logout:hover {
  background: #c62828;
}

/* Responsive */
@media (max-width: 768px) {
  .doctor-header {
    flex-direction: column;
    text-align: center;
    padding: 12px 20px;
  }
  .navbar {
    justify-content: center;
    margin-top: 8px;
    flex-wrap: wrap;
  }
}
</style>
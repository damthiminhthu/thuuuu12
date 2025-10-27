<?php
session_set_cookie_params(['path' => '/']);
session_start();
include_once '../includes/connect.php';

// Kiểm tra đăng nhập
$isLoggedIn = isset($_SESSION['role']) && in_array($_SESSION['role'], ['admin', 'bac_si', 'benh_nhan']);

// Lấy danh sách chuyên khoa
$sql = "SELECT id, ten_chuyen_khoa, mo_ta, anh_dai_dien FROM chuyen_khoa ORDER BY ten_chuyen_khoa ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Chuyên Khoa - Phòng Khám Đa Khoa</title>
<link rel="stylesheet" href="../css/style.css">
<style>
body { font-family: Arial, sans-serif; background: #eef5ff; margin: 0; }
.breadcrumb {
  font-size: 15px; padding: 14px 30px; background: #f5f5f5; border-bottom: 1px solid #ddd;
}
.breadcrumb a { color: #007bff; text-decoration: none; font-weight: 600; }
.breadcrumb span { color: #222; font-weight: 700; }

.section-title { text-align: center; color: #004aad; font-size: 28px; margin: 40px 0 20px 0; font-weight: bold; }

.service-list {
  display: flex; flex-wrap: wrap; justify-content: center; gap: 20px; padding: 0 5%;
}
.service-card {
  background: #fff; border-radius: 12px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);
  overflow: hidden; text-align: center; width: 250px; text-decoration: none;
  transition: transform 0.25s, box-shadow 0.25s;
}
.service-card:hover { transform: translateY(-5px); box-shadow: 0 8px 20px rgba(0,0,0,0.15); }
.service-card img { width: 100%; height: 160px; object-fit: cover; }
.service-card p {
  margin: 15px; color: #004aad; font-weight: 600; font-size: 17px;
}
.footer {
  background: #004aad; color: white; text-align: center; padding: 20px; margin-top: 40px;
}
.footer-content p { margin: 5px 0; }
        /* Menu active */
        .menu li a.active {
            color: #007bff;
            font-weight: bold;
        }
</style>
</head>

<body>
<header class="header">
  <div class="header-text">
    <h2>PHÒNG KHÁM ĐA KHOA</h2>
    <p class="slogan">Tận Tâm - Sáng Tạo - Nâng Tầm Tri Thức</p>
  </div>
  <nav class="main-nav">
    <ul class="menu">
      <li><a href="gioi-thieu.php">Giới thiệu</a></li>
      <li><a href="chuyen-khoa.php" class="active">Chuyên khoa</a></li>
      <li><a href="dich-vu.php">Dịch vụ</a></li>
      <li><a href="doctor.php">Tìm bác sĩ</a></li>
      <li><a href="booking.php">Đặt lịch khám</a></li>
      <li><a href="tra_cuu.php">Tra cứu</a></li>
      <li><a href="tin-tuc.php">Tin tức</a></li>
      <!-- ✅ Tự động hiển thị Đăng nhập / Đăng xuất -->
            <?php if ($isLoggedIn): ?>
                <li><a href="../admin/logout.php" class="logout-btn">Đăng xuất</a></li>
            <?php else: ?>
                <li><a href="../admin/login.php" class="login-btn">Đăng nhập</a></li>
            <?php endif; ?>
    </ul>
  </nav>
</header>

<div class="breadcrumb">
  <a href="index.php">Trang Chủ</a> &gt; <span>Chuyên khoa</span>
</div>

<h2 class="section-title">DANH SÁCH CHUYÊN KHOA</h2>
<section class="service-list">
  <?php while ($row = $result->fetch_assoc()): ?>
    <a href="chuyen-khoa-chi-tiet.php?id=<?php echo $row['id']; ?>" class="service-card">
      <img src="<?php echo htmlspecialchars($row['anh_dai_dien'] ?: '../images/chuyenkhoa.png'); ?>" alt="<?php echo htmlspecialchars($row['ten_chuyen_khoa']); ?>">
      <p><?php echo htmlspecialchars($row['ten_chuyen_khoa']); ?></p>
    </a>
  <?php endwhile; ?>
</section>

<footer class="footer">
  <div class="footer-content">
    <p>&copy; 2025 Phòng Khám Đa Khoa. Bảo lưu mọi quyền.</p>
    <p>Địa chỉ: 98 Dương Quảng Hàm, Cầu Giấy, Hà Nội</p>
    <p>Điện thoại: 0985 467 888</p>
  </div>
</footer>
</body>
</html>

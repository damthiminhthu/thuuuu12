<?php
session_set_cookie_params(['path' => '/']);
session_start();
include_once '../includes/connect.php';

// Kiểm tra đăng nhập
$isLoggedIn = isset($_SESSION['role']) && in_array($_SESSION['role'], ['admin', 'bac_si', 'benh_nhan']);
// Lấy ID chuyên khoa
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Lấy thông tin chuyên khoa
$stmt = $conn->prepare("SELECT * FROM chuyen_khoa WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$chuyen_khoa = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Lấy danh sách bác sĩ
$stmt = $conn->prepare("SELECT ho_ten, email, sdt, avatar FROM bac_si WHERE chuyen_khoa_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$bacsi = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

if (!$chuyen_khoa) {
    die("<h2 style='text-align:center;color:red;'>Không tìm thấy chuyên khoa!</h2>");
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo htmlspecialchars($chuyen_khoa['ten_chuyen_khoa']); ?> - Phòng Khám Đa Khoa</title>
<link rel="stylesheet" href="../css/style.css">
<style>
body {
    font-family: Arial, sans-serif;
    background: #eef5ff;
    margin: 0;
}
html { scroll-behavior: smooth; }

/* ======= BREADCRUMB ======= */
.breadcrumb {
    font-size: 15px;
    padding: 14px 30px;
    background: #f5f5f5;
    border-bottom: 1px solid #ddd;
    display: flex;
    align-items: center;
    gap: 8px;
}
.breadcrumb a {
    text-decoration: none;
    color: #007bff;
    font-weight: 600;
}
.breadcrumb a:hover { text-decoration: underline; }
.breadcrumb span { color: #222; font-weight: 700; }

/* ======= MAIN CONTAINER ======= */
.main-container {
    display: flex;
    justify-content: flex-start;
    align-items: stretch; /* hai cột cao bằng nhau */
    width: 85%;
    margin: 40px auto;
    background: #ffffff;
    border-radius: 20px;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
    padding: 30px 40px;
    box-sizing: border-box;
    gap: 40px;
    position: relative;
}

/* ======= SIDEBAR ======= */
.sidebar {
    flex: 0 0 230px;
    background: #f7faff;
    border-radius: 16px;
    padding: 15px;
    position: sticky;
    top: 140px; /* dừng ngay dưới menu */
    align-self: flex-start;
    box-shadow: 0 4px 10px rgba(0, 123, 255, 0.12);
    border: 1px solid #e3ecff;
    height: fit-content;
}

/* ======= NÚT TRONG MENU ======= */
.sidebar ul {
    list-style: none;
    padding: 0;
    margin: 0;
}
.sidebar li {
    margin-bottom: 12px;
}
.sidebar a {
    display: block;
    color: #004080;
    background: #eaf2ff;
    border-radius: 10px;
    padding: 10px 14px;
    font-weight: 600;
    font-size: 14.5px;
    text-decoration: none;
    text-align: center;
    border: 1px solid #dce6f7;
    transition: all 0.25s ease;
}
.sidebar a:hover,
.sidebar a.active {
    background: #007bff;
    color: white;
}
/* ======= CONTENT ======= */
.content {
    flex: 1;
    min-width: 0;
    padding-top: 10px;
}
.section-title {
    font-size: 30px;
    color: #004aad;
    text-align: center;
    margin-bottom: 40px;
    font-weight: bold;
}
.content section { margin-bottom: 60px; }
.content h3 {
    color: #004aad;
    margin-bottom: 10px;
    border-left: 5px solid #004aad;
    padding-left: 10px;
}
.content p, .content li {
    color: #333;
    line-height: 1.6;
    font-size: 16px;
}
.content img {
    width: 100%;
    max-height: 300px;
    border-radius: 10px;
    object-fit: cover;
    margin: 15px 0;
}

/* ======= DANH SÁCH BÁC SĨ ======= */
.doctor-list {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 18px;
}
.doctor-card {
    background: #f8fbff;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    padding: 12px;
    text-align: center;
    transition: transform 0.25s, box-shadow 0.25s;
}
.doctor-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 6px 14px rgba(0,0,0,0.12);
}
.doctor-card img {
    width: 100%;
    height: 160px;
    border-radius: 10px;
    object-fit: cover;
}
.doctor-card h4 {
    color: #004aad;
    margin: 8px 0 4px;
    font-size: 15.5px;
}
.doctor-card p {
    font-size: 13px;
    color: #333;
    margin: 3px 0;
}

/* ======= FOOTER ======= */
.footer {
    background: #004aad;
    color: #fff;
    text-align: center;
    padding: 20px;
    margin-top: 40px;
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
            <?php if ($isLoggedIn): ?>
                <li><a href="../admin/logout.php" class="logout-btn">Đăng xuất</a></li>
            <?php else: ?>
                <li><a href="../admin/login.php" class="login-btn">Đăng nhập</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

<div class="breadcrumb">
    <a href="index.php">Trang Chủ</a> &gt;
    <a href="chuyen-khoa.php">Chuyên khoa</a> &gt;
    <span><?php echo htmlspecialchars($chuyen_khoa['ten_chuyen_khoa']); ?></span>
</div>

<div class="main-container">
    <aside class="sidebar">
        <ul>
            <li><a href="#gioithieu" class="active">Giới thiệu chung</a></li>
            <li><a href="#chucnang">Chức năng - Nhiệm vụ</a></li>
            <li><a href="#dichvu">Các dịch vụ</a></li>
            <li><a href="#doingu">Đội ngũ chuyên gia</a></li>
            <li><a href="#cosovatchat">Cơ sở vật chất</a></li>
        </ul>
    </aside>

    <div class="content">
        <h2 class="section-title"><?php echo htmlspecialchars($chuyen_khoa['ten_chuyen_khoa']); ?></h2>

        <section id="gioithieu">
            <h3>GIỚI THIỆU CHUNG</h3>
            <p><?php echo nl2br(htmlspecialchars($chuyen_khoa['mo_ta'])); ?></p>
        </section>

        <section id="chucnang">
            <h3>CHỨC NĂNG - NHIỆM VỤ</h3>
            <p>Chuyên khoa <?php echo htmlspecialchars($chuyen_khoa['ten_chuyen_khoa']); ?> đảm nhận nhiệm vụ khám, tư vấn và điều trị chuyên sâu, đảm bảo mang lại dịch vụ y tế toàn diện và chất lượng cho bệnh nhân.</p>
        </section>

        <section id="dichvu">
            <h3>CÁC DỊCH VỤ</h3>
            <ul>
                <li>Khám, chẩn đoán và điều trị chuyên sâu.</li>
                <li>Tư vấn sức khỏe và phòng ngừa bệnh lý.</li>
                <li>Xét nghiệm, chẩn đoán hình ảnh, điều trị kỹ thuật cao.</li>
            </ul>
        </section>

        <section id="doingu">
            <h3>ĐỘI NGŨ CHUYÊN GIA</h3>
            <?php if (count($bacsi) > 0): ?>
                <div class="doctor-list">
                    <?php foreach ($bacsi as $b): ?>
                        <div class="doctor-card">
                            <img src="<?php echo htmlspecialchars($b['avatar']); ?>" alt="<?php echo htmlspecialchars($b['ho_ten']); ?>">
                            <h4><?php echo htmlspecialchars($b['ho_ten']); ?></h4>
                            <p><strong>Email:</strong> <?php echo htmlspecialchars($b['email']); ?></p>
                            <p><strong>Liên hệ:</strong> <?php echo htmlspecialchars($b['sdt']); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>Chưa có thông tin bác sĩ trong chuyên khoa này.</p>
            <?php endif; ?>
        </section>

        <section id="cosovatchat">
            <h3>CƠ SỞ VẬT CHẤT</h3>
            <p>Trang thiết bị hiện đại, không gian thân thiện, giúp bệnh nhân có trải nghiệm thoải mái và an toàn nhất.</p>
        </section>
    </div>
</div>

<footer class="footer">
    <div class="footer-content">
        <p>&copy; 2025 Phòng Khám Đa Khoa. Bảo lưu mọi quyền.</p>
        <p>Địa chỉ: 98 Dương Quảng Hàm, Cầu Giấy, Hà Nội</p>
        <p>Điện thoại: 0985 467 888</p>
    </div>
</footer>

<script>
document.addEventListener("DOMContentLoaded", function () {
  const sections = document.querySelectorAll(".content section");
  const links = document.querySelectorAll(".sidebar a");

  // Scroll spy - đổi màu menu khi cuộn
  window.addEventListener("scroll", () => {
    sections.forEach((sec, i) => {
      const rect = sec.getBoundingClientRect();
      if (rect.top <= 150 && rect.bottom >= 150) {
        links.forEach(a => a.classList.remove("active"));
        links[i].classList.add("active");
      }
    });
  });

  // Smooth scroll khi click menu
  links.forEach(link => {
    link.addEventListener("click", e => {
      e.preventDefault();
      const targetId = link.getAttribute("href").substring(1);
      const target = document.getElementById(targetId);
      const offsetTop = target.offsetTop - 100;
      window.scrollTo({ top: offsetTop, behavior: "smooth" });
    });
  });
});
</script>

</body>
</html>

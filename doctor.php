<?php
session_set_cookie_params(['path' => '/']);
session_start();
include_once '../includes/connect.php';

// Kiểm tra đăng nhập
$isLoggedIn = isset($_SESSION['role']) && in_array($_SESSION['role'], ['admin', 'bac_si', 'benh_nhan']);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tìm Bác Sĩ - Phòng Khám Đa Khoa</title>
<link rel="stylesheet" href="../css/style.css">
<style>
body {
    font-family: Arial, sans-serif;
    background: #eef5ff;
    margin: 0;
}
html { scroll-behavior: smooth; }

/* ======= MAIN CONTAINER ======= */
.main-container {
    display: flex;
    width: 85%;
    margin: 40px auto;
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
    padding: 30px 40px;
    gap: 30px;
}

/* ======= SIDEBAR ======= */
.sidebar {
    flex: 0 0 200px;
    background: #f7faff;
    border-radius: 16px;
    padding: 15px;
    border: 1px solid #e3ecff;
    box-shadow: 0 4px 10px rgba(0, 123, 255, 0.12);
    position: sticky;
    top: 120px;
    max-height: 500px;
    overflow-y: auto;
}
.sidebar h3 {
    text-align: center;
    color: #004aad;
    margin-bottom: 15px;
    font-size: 17px;
}
.sidebar ul {
    list-style: none;
    padding: 0;
    margin: 0;
}
.sidebar li { margin-bottom: 10px; }
.sidebar a {
    display: block;
    color: #004080;
    background: #eaf2ff;
    border-radius: 10px;
    padding: 8px 12px;
    font-weight: 600;
    font-size: 14px;
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
        /* Menu active */
        .menu li a.active {
            color: #007bff;
            font-weight: bold;
        }

/* ======= CONTENT ======= */
.content {
    flex: 1;
    padding-top: 10px;
}
.section-title {
    font-size: 26px;
    color: #004aad;
    text-align: center;
    font-weight: bold;
    margin-bottom: 25px;
}

/* ======= DANH SÁCH BÁC SĨ ======= */
.doctor-list {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    justify-items: center;
    align-items: start;
    gap: 40px 35px;
    padding: 30px 10px;
    margin-left: 20px;
}

/* Card bác sĩ to hơn, rõ ràng hơn */
.doctor-card {
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 6px 16px rgba(0,0,0,0.12);
    padding: 25px 20px;
    text-align: center;
    transition: transform 0.25s ease, box-shadow 0.25s ease;
    width: 95%;
    max-width: 330px; /* tăng kích thước card */
    min-height: 360px;
}
.doctor-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.18);
}

/* Ảnh bác sĩ to hơn */
.doctor-img {
    width: 140px;
    height: 140px;
    border-radius: 50%;
    overflow: hidden;
    margin: 0 auto 15px;
}
.doctor-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* Tên và chuyên khoa */
.doctor-card h4 {
    color: #004aad;
    font-size: 18px;
    margin: 8px 0 4px;
    font-weight: bold;
}
.chuyenkhoa {
    font-size: 15px;
    color: #007bff;
    margin-bottom: 6px;
    font-weight: 600;
}
.doctor-card p {
    font-size: 14px;
    margin: 3px 0;
    color: #333;
}

/* Nút đặt lịch */
.btn-book {
    display: inline-block;
    background: #007bff;
    color: white;
    border-radius: 25px;
    padding: 9px 20px;
    font-size: 14px;
    font-weight: bold;
    margin-top: 12px;
    text-decoration: none;
    transition: background 0.25s ease, transform 0.25s ease;
}
.btn-book:hover {
    background: #005ad3;
    transform: scale(1.05);
}

/* Responsive cho màn nhỏ */
@media (max-width: 992px) {
    .doctor-list {
        grid-template-columns: repeat(2, 1fr);
    }
}
@media (max-width: 600px) {
    .doctor-list {
        grid-template-columns: 1fr;
    }
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
/* Breadcrumb */
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
        .breadcrumb a:hover {
            text-decoration: underline;
        }
        .breadcrumb span {
            color: #222;
            font-weight: 700;
        }
        .breadcrumb .separator {
            color: #666;
            font-size: 16px;
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
            <li><a href="chuyen-khoa.php">Chuyên khoa</a></li>
            <li><a href="dich-vu.php">Dịch vụ</a></li>
            <li><a href="doctor.php" class="active">Tìm bác sĩ</a></li>
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
<!-- ====================== BREADCRUMB ====================== -->
<div class="breadcrumb">
    <a href="index.php">Trang Chủ</a> &gt; 
    <span>Dịch vụ</span>
</div>
<div class="main-container">
    <!-- ======= MENU TRÁI ======= -->
    <aside class="sidebar">
        <h3>Chuyên Khoa</h3>
        <ul>
            <li><a href="#" onclick="loadDoctors('all', this)">Tất cả bác sĩ</a></li>
            <?php
            $result = $conn->query("SELECT id, ten_chuyen_khoa FROM chuyen_khoa ORDER BY ten_chuyen_khoa ASC");
            while ($row = $result->fetch_assoc()) {
                echo '<li><a href="#" onclick="loadDoctors('.$row['id'].', this)">'.htmlspecialchars($row['ten_chuyen_khoa']).'</a></li>';
            }
            ?>
        </ul>
    </aside>

    <!-- ======= DANH SÁCH BÁC SĨ ======= -->
    <div class="content">
        <h2 class="section-title">DANH SÁCH BÁC SĨ THEO CHUYÊN KHOA</h2>
        <div id="doctorResult" class="doctor-list">
            <p style="text-align:center; color:#777;">Vui lòng chọn chuyên khoa ở bên trái để xem danh sách bác sĩ.</p>
        </div>
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
function loadDoctors(khoaId, el) {
    // Bỏ active cũ
    document.querySelectorAll('.sidebar a').forEach(a => a.classList.remove('active'));
    el.classList.add('active');

    fetch(`get_doctor.php?khoa=${khoaId}`)
    .then(res => res.text())
    .then(html => {
        document.getElementById('doctorResult').innerHTML = html;
    });
}
</script>
<script>
function loadDoctors(khoaId, el = null) {
    // Bỏ active cũ
    document.querySelectorAll('.sidebar a').forEach(a => a.classList.remove('active'));
    if (el) el.classList.add('active');

    fetch(`get_doctor.php?khoa=${khoaId}`)
    .then(res => res.text())
    .then(html => {
        document.getElementById('doctorResult').innerHTML = html;
    });
}

// 👉 Khi trang load xong, tự động gọi tất cả bác sĩ
document.addEventListener("DOMContentLoaded", function() {
    const allLink = document.querySelector('.sidebar a');
    if (allLink) {
        allLink.classList.add('active');
        loadDoctors('all');
    }
});
</script>

</body>
</html>

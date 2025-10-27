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
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Giới thiệu - Phòng Khám Đa Khoa</title>
    <link rel="stylesheet" href="../css/style.css" />
    <style>
        /* Banner giống trang chủ */
        .about-banner {
            background: url("https://cdn.phenikaamec.com/phenikaa-mec/image/5-14-2025/d15b3f5b-1df1-45fd-923e-b7919821abd3-image.webp") no-repeat center center;
            background-size: cover;
            height: 300px;  /* giữ nguyên như slider trang chủ */
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 40px;
            font-weight: bold;
        }

        /* Phần giới thiệu giữ width giống trang chủ */
        .about-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 40px;
            padding: 40px 10%;   /* giống card-container trang chủ */
            background-color: #eaf6ff;
        }
        .about-text {
            flex: 1;
        }
        .about-text h2 {
            color: #004aad;
            margin-bottom: 20px;
        }
        .about-text p {
            text-align: justify;
            line-height: 1.6;
            color: #333;
        }
        .about-img {
            flex: 1.25;
            margin-top: 2.7cm;
        }
        .about-img img {
            width: 100%;
            border-radius: 10px;
            display: block;
        }

        /* Menu active */
        .menu li a.active {
            color: #007bff;
            font-weight: bold;
        }

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
        .about-text {
            max-width: 800px;       /* Giữ khung chữ đẹp */
            margin: 30px auto;      /* Căn giữa */
            text-align: justify;    /* Căn đều 2 bên giống Word */
            font-family: "Arial", sans-serif; 
            font-size: 18px;        /* Cỡ chữ đồng nhất */
            line-height: 1.8;       /* Giãn dòng */
            color: #004080;         /* Màu chữ */
        }
        .about-text h2 {
            text-align: center;
            margin-bottom: 15px;
            color: #004aad;
            font-size: 26px;
        }
.about-text p {
    margin-bottom: 20px;    /* Khoảng cách giữa các đoạn */
}

    </style>
</head>
<body>

<!-- Header -->
<header class="header">
    <div class="logo">
        <img src="https://png.pngtree.com/template/20190926/ourmid/pngtree-medical-logo-design-health-care-logo-pharmacy-healthcare-vecto-image_309764.jpg" alt="Logo Phòng Khám" class="logo-img" />
    </div>   
    <div class="header-text">
        <h2>PHÒNG KHÁM ĐA KHOA</h2>
        <p class="slogan">Tận Tâm - Sáng Tạo - Nâng Tầm Tri Thức</p>
    </div>
    <nav class="main-nav">
        <ul class="menu">
            <li><a href="gioi-thieu.php" class="active">Giới thiệu</a></li>
            <li><a href="chuyen-khoa.php">Chuyên khoa</a></li>
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
    </nav>
</header>

<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="index.php">Trang Chủ</a> &gt; 
    <span>Giới Thiệu Chung</span>
</div>

<!-- Banner -->
<section class="about-banner">
</section>

<!-- Nội dung giới thiệu -->
<section class="about-section">
    <div class="about-text">
    <h2>VỀ CHÚNG TÔI</h2>
    <p>
        HNMU-MED là hệ thống đặt lịch phòng khám tư hiện đại, chuẩn mực quốc tế, trực thuộc Hệ sinh thái 
        Chăm sóc Sức khỏe HNMU. Với nền tảng công nghệ tiên tiến, đội ngũ y bác sĩ uy tín và dịch vụ thân thiện, 
        chúng tôi mang đến giải pháp đặt lịch nhanh chóng, an toàn và hiệu quả, giúp bệnh nhân tiếp cận dịch vụ 
        y tế chất lượng một cách thuận tiện nhất.
    </p>
    <p>
        HNMU-MED là hệ thống đặt lịch khám trực tuyến cho 5 khoa chính của phòng khám. Với nền tảng công nghệ 
        thuận tiện và đội ngũ y bác sĩ tận tâm, chúng tôi mang đến dịch vụ đặt lịch nhanh chóng, minh bạch 
        và dễ dàng tiếp cận, giúp bệnh nhân chủ động lựa chọn thời gian khám phù hợp.
    </p>
</div>

    <div class="about-img">
        <img src="https://png.pngtree.com/background/20240403/original/pngtree-doctor-standing-in-clinic-standing-man-confidence-photo-picture-image_8355943.jpg" alt="Phòng khám">
    </div>
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

<?php
session_set_cookie_params(['path' => '/']);
session_start();
include_once '../includes/connect.php';

// Kiểm tra login để hiển thị nút đăng nhập/đăng xuất
$isLoggedIn = isset($_SESSION['benh_nhan_id']) || isset($_SESSION['bac_si_id']) || isset($_SESSION['user']);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Dịch Vụ - Phòng Khám Đa Khoa</title>
    <link rel="stylesheet" href="../css/style.css" />
    <style>
        .about-img img {
            width: 100%;
            border-radius: 10px;
            display: block;
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

        /* Menu active */
        .menu li a.active {
            color: #007bff;
            font-weight: bold;
        }

        /* Tiêu đề section */
        .section-title-wrapper {
            width: 100%;
            text-align: center;
            margin: 40px 0 20px 0;
        }
        .section-title-wrapper .section-title {
            font-size: 28px;
            color: #004aad;
            font-weight: bold;
        }

        /* Cards dịch vụ */
        .service-overview {
            background-color: #eef5ff;
        }
        .service-list {
            background-color: #eef5ff;
            margin-top: 2cm;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            padding: 0 40px 60px;
        }
        .service-card {
            flex: 0 0 calc(25% - 20px);
            max-width: 250px;
            background: #fff;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }
        .service-card:hover {
            transform: translateY(-5px);
        }
        .service-card img {
            width: 100%;
            height: 160px;
            border-radius: 8px;
            object-fit: cover;
            margin-bottom: 10px;
        }
        .service-card p {
            font-weight: 600;
            color: #004aad;
            margin-bottom: 8px;
        }
        .service-card small {
            color: #555;
            font-size: 14px;
            line-height: 1.4;
            display: block;
        }
    </style>
</head>
<body>

<!-- ====================== HEADER ====================== -->
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
            <li><a href="gioi-thieu.php">Giới thiệu</a></li>
            <li><a href="chuyen-khoa.php">Chuyên khoa</a></li>
            <li><a href="dich-vu.php" class="active">Dịch vụ</a></li>
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

<!-- ====================== BREADCRUMB ====================== -->
<div class="breadcrumb">
    <a href="index.php">Trang Chủ</a> &gt; 
    <span>Dịch vụ</span>
</div>

<!-- ====================== GIỚI THIỆU DỊCH VỤ ====================== -->
<section class="service-overview">
    <div class="about-img">
        <img src="https://cdn.phenikaamec.com/phenikaa-mec/image/5-14-2025/b13d478b-c1b1-4454-b3be-43c434565431-image.webp" alt="Phòng khám">
    </div>
    <h2 class="section-title">DỊCH VỤ CỦA CHÚNG TÔI</h2>
    <p style="text-align:center; max-width: 750px; margin: 40px auto; 
          color: #004080; font-family: Arial, sans-serif; 
          font-size: 18px; line-height: 1.8;">
        Chúng tôi cung cấp các dịch vụ y tế chuyên nghiệp, đa dạng, đáp ứng nhu cầu chăm sóc sức khỏe toàn diện cho mọi lứa tuổi. 
        Với đội ngũ bác sĩ giàu kinh nghiệm và trang thiết bị hiện đại, phòng khám mang đến các gói khám từ tổng quát đến chuyên sâu, 
        bao gồm chẩn đoán, điều trị và theo dõi sức khỏe định kỳ. 
        Chúng tôi luôn đặt chất lượng dịch vụ, sự an toàn và hài lòng của người bệnh lên hàng đầu, 
        đồng thời tư vấn tận tình, cá nhân hóa từng kế hoạch chăm sóc phù hợp với từng bệnh nhân. 
    </p>
</section>

<!-- ====================== NÚT GỌI / ĐẶT LỊCH / TÌM BÁC SĨ ====================== -->
<section class="buttons">
    <div class="btn-box">
        <a href="tel:0985467888" class="btn call">Gọi tổng đài</a>
    </div>
    <div class="btn-box">
        <a href="booking.php" class="btn appointment">Đặt lịch khám</a>
    </div>
    <div class="btn-box">
        <a href="doctor.php" class="btn doctor">Tìm bác sĩ</a>
    </div>
</section>

<!-- ====================== DANH SÁCH DỊCH VỤ ====================== -->
<section class="service-list">
    <?php
    $query = "SELECT * FROM dich_vu WHERE trang_thai='Hiển thị' ORDER BY id ASC";
    $result = $conn->query($query);

    if ($result->num_rows > 0):
        while ($row = $result->fetch_assoc()):
    ?>
        <div class="service-card">
            <img src="../uploads/<?= htmlspecialchars($row['hinh_anh']) ?>" alt="<?= htmlspecialchars($row['ten_dich_vu']) ?>">
            <p><?= htmlspecialchars($row['ten_dich_vu']) ?></p>
            <?php if (!empty($row['mo_ta'])): ?>
                <small><?= htmlspecialchars($row['mo_ta']) ?></small>
            <?php endif; ?>
        </div>
    <?php
        endwhile;
    else:
        echo "<p style='text-align:center;color:#666;'>Hiện chưa có dịch vụ nào được đăng.</p>";
    endif;
    ?>
</section>

<!-- ====================== FOOTER ====================== -->
<footer class="footer">
    <div class="footer-content">
        <p>&copy; 2025 Phòng Khám Đa Khoa. Bảo lưu mọi quyền.</p>
        <p>Địa chỉ: 98 Dương Quảng Hàm, Cầu Giấy, Hà Nội</p>
        <p>Điện thoại: 0985 467 888</p>
    </div>
</footer>

</body>
</html>

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
            <li><a href="gioi-thieu.php">Giới thiệu</a></li>
            <li><a href="chuyen-khoa.php">Chuyên khoa</a></li>
            <li><a href="dich-vu.php">Dịch vụ</a></li>
            <li><a href="doctor.php">Tìm bác sĩ</a></li>
            <li><a href="booking.php">Đặt lịch khám</a></li>
            <li><a href="tra_cuu.php">Tra cứu</a></li>
            <li><a href="tin-tuc.php" class="active">Tin tức</a></li>
<!-- ✅ Tự động hiển thị Đăng nhập / Đăng xuất -->
            <?php if ($isLoggedIn): ?>
                <li><a href="../admin/logout.php" class="logout-btn">Đăng xuất</a></li>
            <?php else: ?>
                <li><a href="../admin/login.php" class="login-btn">Đăng nhập</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="index.php">Trang Chủ</a> &gt; 
    <span>Tin tức</span>
</div>
<!-- Danh sách tin tức -->
<div class="news-container" style="max-width: 1100px; margin: 30px auto; padding: 0 20px;">
    <div class="news-list" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
        <?php
        $sql = "SELECT id, tieu_de, noi_dung, ngay_dang, anh FROM tin_tuc ORDER BY ngay_dang DESC";
        $result = $conn->query($sql);
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $id = $row['id'];
                $title = htmlspecialchars($row['tieu_de']);
                $date = date("d/m/Y", strtotime($row['ngay_dang']));
                $img = htmlspecialchars($row['anh']);
                echo '
                <a href="tin-tuc.php?id='.$id.'" class="news-card" 
                   style="display: flex; background:#fff; border-radius:12px; overflow:hidden; 
                          box-shadow:0 4px 12px rgba(0,0,0,0.1); transition:transform 0.2s; text-decoration:none; color:#000;">
                    <img src="'.$img.'" alt="Tin tức" 
                         style="width:180px; height:130px; object-fit:cover;">
                    <div style="padding:12px; display:flex; flex-direction:column; justify-content:space-between;">
                        <h3 style="margin:0 0 10px; font-size:16px; font-weight:bold; color:#003366;
                                   line-height:1.4em; overflow:hidden; display:-webkit-box; 
                                   -webkit-line-clamp:2; -webkit-box-orient:vertical;">
                            '.$title.'
                        </h3>
                        <div style="font-size:13px; color:#666; display:flex; align-items:center; gap:6px;">
                            <span>📅</span> '.$date.'
                        </div>
                    </div>
                </a>
                ';
            }
        } else {
            echo "<p>Chưa có tin tức nào.</p>";
        }
        ?>
    </div>
</div>
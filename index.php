<?php
session_set_cookie_params(['path' => '/']); // dùng chung session toàn site
session_start();
include_once '../includes/connect.php';

// Kiểm tra xem bệnh nhân đã đăng nhập chưa
$isLoggedIn = isset($_SESSION['role']) && $_SESSION['role'] === 'benh_nhan';

// (Tuỳ chọn) Nếu có kết nối PDO riêng thì giữ nguyên phần menu động
function getMenuItems($pdo, $parent_id = NULL) {
    $query = "SELECT * FROM menu_items WHERE parent_id " . 
            (is_null($parent_id) ? "IS NULL" : "= :parent_id") . 
            " AND is_active = 1 ORDER BY id ASC";
    $stmt = $pdo->prepare($query);
    if (!is_null($parent_id)) {
        $stmt->bindParam(':parent_id', $parent_id, PDO::PARAM_INT);
    }
    $stmt->execute();
    return $stmt->fetchAll();
}

function renderMenu($pdo, $parent_id = NULL) {
    $items = getMenuItems($pdo, $parent_id);
    if ($items) {
        echo '<ul class="'.($parent_id === NULL ? 'main-menu' : 'dropdown').'">';
        foreach ($items as $item) {
            echo '<li>';
            echo '<a href="'.htmlspecialchars($item["url"]).'">'.htmlspecialchars($item["title"]).'</a>';
            if ($children = getMenuItems($pdo, $item["id"])) {
                renderMenu($pdo, $item["id"]);
            }
            echo '</li>';
        }
        echo '</ul>';
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Phòng Khám Đa Khoa</title>
    <link rel="stylesheet" href="../css/style.css" />
</head>
<body>

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
<section class="slider-section hero">
    <div class="slider">
        <div class="slides" id="slides">
            <img src="https://cdn.phenikaamec.com/phenikaa-mec/image/5-14-2025/d15b3f5b-1df1-45fd-923e-b7919821abd3-image.webp" alt="Ảnh 1" />
            <img src="https://cdn.phenikaamec.com/phenikaa-mec/image/5-14-2025/b13d478b-c1b1-4454-b3be-43c434565431-image.webp" alt="Ảnh 2" />
            <img src="https://cdn.phenikaamec.com/phenikaa-mec/slide/7-1-2025/c67b95ab-b06d-47d0-a90e-2b667101ac82-Banner-doi-ngu-lanh-dao-phenikaamec.webp" alt="Ảnh 3" />
            <img src="https://cdn.phenikaamec.com/phenikaa-mec/image/5-14-2025/b13d478b-c1b1-4454-b3be-43c434565431-image.webp" alt="Ảnh 4" />
        </div>
        <button class="prev" onclick="prevSlide()">&#10094;</button>
        <button class="next" onclick="nextSlide()">&#10095;</button>
    </div>
</section>

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
<section class="service-list">
  <!-- Chuyên khoa Tim mạch -->
  <div class="service-card">
    <img src="https://cdn.phenikaamec.com/phenikaa-mec/image/5-14-2025/b38cb088-a4c6-4f19-b900-becba07e212e-image.webp" alt="Khám Sàng Lọc Bệnh Lý Tim Bẩm Sinh" />
    <p>Khám Sàng Lọc Bệnh Lý Tim Bẩm Sinh</p>
  </div>
  <div class="service-card">
    <img src="https://cdn.phenikaamec.com/phenikaa-mec/image/5-14-2025/fdfd998e-0904-490a-81b7-9d8ccc36ef77-image.webp" alt="Tầm Soát Bệnh Tim Người Lớn" />
    <p>Tầm Soát Bệnh Tim Người Lớn</p>
  </div>

  <!-- Chuyên khoa Sản - Phụ khoa -->
  <div class="service-card">
    <img src="https://cdn.phenikaamec.com/phenikaa-mec/image/5-14-2025/9a83f2c5-d379-48ec-949d-faca373199c0-image.webp" alt="Chăm Sóc Thai Sản Trọn Gói" />
    <p>Chăm Sóc Thai Sản Trọn Gói</p>
  </div>
  <div class="service-card">
    <img src="https://cdn.phenikaamec.com/phenikaa-mec/dich-vu/7-30-2025/e4a6590f-3c49-4539-9707-3156f06d16d8-Goi-thai-san-12-tuan-PhenikaaMec---Thumb.webp" alt="Gói Thai Sản 12 Tuần" />
    <p>Gói Thai Sản 12 Tuần</p>
  </div>

  <!-- Chuyên khoa Nhi -->
  <div class="service-card">
    <img src="https://cdn.phenikaamec.com/phenikaa-mec/image/5-14-2025/f03d332e-4b90-4256-ae26-4b7a9a23f856-image.webp" alt="Gói Khám Nhi" />
    <p>Gói Khám Nhi</p>
  </div>
  <div class="service-card">
    <img src="https://cdn.phenikaamec.com/phenikaa-mec/dich-vu/7-24-2025/dfb4e1bd-1248-4170-996c-aacab4ca6d40-a1309b4a-95b7-43d4-be8c-39c3e28f4a78-image.webp" alt="Gói Tiêm Chủng Trẻ Em" />
    <p>Gói Tiêm Chủng Trẻ Em</p>
  </div>

  <!-- Chuyên khoa Tiêu hóa -->
  <div class="service-card">
    <img src="https://cdn.phenikaamec.com/phenikaa-mec/image/5-14-2025/154171ae-0dbe-4ac0-8a43-83d8809ea7ac-image.webp" alt="Nội Soi Tiêu Hóa" />
    <p>Nội Soi Tiêu Hóa</p>
  </div>
  <div class="service-card">
    <img src="https://cdn.phenikaamec.com/phenikaa-mec/image/5-14-2025/20e5ff7d-4b09-4c4c-af92-c2a97c57f215-image.webp" alt="Gói Khám Gan Mật" />
    <p>Gói Khám Gan Mật</p>
  </div>

  <!-- Chuyên khoa Ung bướu -->
  <div class="service-card">
    <img src="https://online.benhvienphuongdong.vn/wp-content/uploads/2022/02/kham-suc-khoe-dinh-ky-4.png.webp" alt="Tầm Soát Ung Thư Toàn Diện" />
    <p>Tầm Soát Ung Thư Toàn Diện</p>
  </div>
  <div class="service-card">
    <img src="https://cdn.phenikaamec.com/phenikaa-mec/dich-vu/8-3-2025/b47617fd-9e1a-497a-9189-97dec33b8455-Goi-cham-soc-sau-sinh-PhenikaaMec---Thumb.webp" alt="Gói Điều Trị Ung Thư Sớm" />
    <p>Gói Điều Trị Ung Thư Sớm</p>
  </div>
</section>


<section class="vision-section">
    <h2 class="section-title">TẦM NHÌN - SỨ MỆNH - GIÁ TRỊ CỐT LÕI</h2>
    <div class="wrapper">
         <div class="card-container vision-cards">
            <div class="card active" onclick="showDetail('tamnhin', this)">
                <img src="https://cdn.phenikaamec.com/phenikaa-mec/image/5-14-2025/f475952a-3068-42c7-bf15-a465c74ba701-image.webp" alt="Tầm nhìn" />
                <div class="card-title">Tầm nhìn</div>
            </div>
            <div class="card" onclick="showDetail('sumenh', this)">
                <img src="https://cdn.phenikaamec.com/phenikaa-mec/image/5-14-2025/befc71be-df7f-4c21-acc3-bf118c53e7fa-image.webp" alt="Sứ mệnh" />
                <div class="card-title">Sứ mệnh</div>
            </div>
            <div class="card" onclick="showDetail('giatri', this)">
                <img src="https://cdn.phenikaamec.com/phenikaa-mec/image/5-14-2025/13119a02-a7ff-49fd-9334-93ce2c60bee8-image.webp" alt="Giá trị cốt lõi" />
                <div class="card-title">Giá trị cốt lõi</div>
            </div>
        </div>
        <div class="detail-content" id="detail-box">
            <img src="https://cdn.phenikaamec.com/phenikaa-mec/image/5-14-2025/f475952a-3068-42c7-bf15-a465c74ba701-image.webp" alt="Tầm nhìn" class="detail-image" id="detail-image" />
            <div id="detail-text">
                <h3>TẦM NHÌN</h3>
                <p>Trở thành hệ thống y tế chuẩn mực quốc tế, hướng tới giá trị Chân – Thiện – Mỹ bằng nghiên cứu đột phá, chất lượng điều trị xuất sắc, dịch vụ hoàn hảo và giáo dục nâng tầm tri thức.</p>
            </div>
        </div>
    </div>
</section>

<script>
    const details = {
        tamnhin: {
            image: "https://cdn.phenikaamec.com/phenikaa-mec/image/5-14-2025/f475952a-3068-42c7-bf15-a465c74ba701-image.webp",
            title: "TẦM NHÌN",
            content: `<p>Trở thành hệ thống y tế chuẩn mực quốc tế, hướng tới giá trị Chân – Thiện – Mỹ bằng nghiên cứu đột phá, chất lượng điều trị xuất sắc, dịch vụ hoàn hảo và giáo dục nâng tầm tri thức.</p>`
        },
        sumenh: {
            image: "https://cdn.phenikaamec.com/phenikaa-mec/image/5-14-2025/befc71be-df7f-4c21-acc3-bf118c53e7fa-image.webp",
            title: "SỨ MỆNH",
            content: `<p>Vì một cộng đồng khỏe mạnh, nhân văn và thông thái hơn bằng tài năng, y đức, lòng trắc ẩn và tinh thần sẵn sàng cống hiến, phụng sự.</p>`
        },
        giatri: {
            image: "https://cdn.phenikaamec.com/phenikaa-mec/image/5-14-2025/13119a02-a7ff-49fd-9334-93ce2c60bee8-image.webp",
            title: "GIÁ TRỊ CỐT LÕI",
            content: `
            <h4>1. Chính trực</h4>
            <ul>
                <li>Luôn trung thực, minh bạch với người bệnh, khách hàng, đối tác và các bên liên quan.</li>
                <li>Luôn bản lĩnh, kiên định và nỗ lực đưa ra phương án tối ưu nhằm mang lại kết quả tốt nhất và sẵn sàng chịu trách nhiệm.</li>
                <li>Bảo mật mọi thông tin của người bệnh và khách hàng.</li>
            </ul>
            <h4>2. Xuất sắc</h4>
            <ul>
                <li>Theo đuổi các tiêu chuẩn quốc tế trong mọi hoạt động và dịch vụ cung cấp. Tuân thủ những nguyên tắc, tiêu chuẩn y đức của người thầy thuốc.</li>
                <li>Phấn đấu trở nên xuất sắc trong: Chất lượng chuyên môn và dịch vụ, Đào tạo nghiên cứu, Con người, Hoạt động hiệu quả và bền vững, Triết lý nhân văn, vì cuộc sống chất lượng và mạnh khỏe cho khách hàng, vì một quốc gia khỏe mạnh và hạnh phúc.</li>
                <li>Không ngừng học hỏi để trở nên xuất sắc và toàn diện hơn mỗi ngày.</li>
            </ul>
            <h4>3. Tận tâm cống hiến</h4>
            <ul>
                <li>Đặt khách hàng là trung tâm của mọi hành động. Nỗ lực hết mình để cung cấp dịch vụ khám bệnh – chữa bệnh và chăm sóc tốt nhất và duy trì chất lượng dịch vụ xuất sắc bằng lòng trắc ẩn, sự tận tâm và tình nhân ái.</li>
                <li>Đam mê và sẵn sàng cống hiến cho sự phát triển của nền y học thông qua hoạt động giáo dục và nghiên cứu.</li>
                <li>Duy trì và phát triển Văn hóa kinh doanh có ý thức góp phần vào sự phát triển bền vững của cộng đồng, xã hội.</li>
            </ul>
            <h4>4. Sáng tạo</h4>
            <ul>
                <li>Tiếp thu - ứng dụng và chủ động nghiên cứu chuyên sâu các phương pháp – công nghệ khám bệnh, chữa bệnh tiên tiến nhằm nâng cao dịch vụ chăm sóc cho người bệnh và tạo nên những đổi mới mang tính đột phá vì sự tiến bộ của y học.</li>
                <li>Nghiên cứu và ứng dụng khoa học - công nghệ tiến bộ trong mọi hoạt động nhằm tối ưu nguồn lực, quy trình vận hành và cải thiện chất lượng dịch vụ, không gian khám chữa bệnh văn minh.</li>
            </ul>
            `
        }
    };

    function showDetail(id, element) {
    const detailImage = document.getElementById('detail-image');
    const detailText = document.getElementById('detail-text');

    if (!details[id]) return;

    detailImage.src = details[id].image;
    detailImage.alt = details[id].title;
    detailText.innerHTML = `<h3>${details[id].title}</h3>` + details[id].content;

    // Xóa active cũ
    document.querySelectorAll('.card').forEach(card => card.classList.remove('active'));

    // Thêm active cho card đang click
    if (element) element.classList.add('active');
}

</script>

<!-- ====================== FOOTER ====================== -->
<footer class="footer">
    <div class="footer-content">
        <p>&copy; 2025 Phòng Khám Đa Khoa. Bảo lưu mọi quyền.</p>
        <p>Địa chỉ: 98 Dương Quảng Hàm, Cầu Giấy, Hà Nội</p>
        <p>Điện thoại: 0985 467 888</p>
    </div>
</footer>

<!-- ====================== STYLE NÚT LOGIN/LOGOUT ====================== -->
<style>
.login-btn, .logout-btn {
    color: #004aad;
    font-weight: bold;
    transition: 0.3s ease;
}
.login-btn:hover, .logout-btn:hover {
    color: #0d6efd;
    text-decoration: underline;
}
</style>

</body>
</html>

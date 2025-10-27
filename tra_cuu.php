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
    <title>Tra cứu - Phòng Khám Đa Khoa</title>
    <link rel="stylesheet" href="../css/style.css" />
    <style>
        .about-banner {
            background: url("https://cdn.phenikaamec.com/phenikaa-mec/image/5-14-2025/d15b3f5b-1df1-45fd-923e-b7919821abd3-image.webp") no-repeat center center;
            background-size: cover;
            height: 300px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 40px;
            font-weight: bold;
        }
        .menu li a.active { color: #007bff; font-weight: bold; }
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
        .breadcrumb span { color: #222; font-weight: 700; }
        .tra-cuu-container {
            max-width: 900px;
            margin: 40px auto;
            padding: 25px;
            background: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .tra-cuu-container h2 {
            text-align: center;
            color: #004aad;
            margin-bottom: 20px;
        }
        .tra-cuu-form {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-bottom: 20px;
        }
        .tra-cuu-form label { font-weight: bold; }
        .tra-cuu-form input, .tra-cuu-form select {
            padding: 10px; border: 1px solid #ccc; border-radius: 6px; font-size: 15px;
        }
        .tra-cuu-form button {
            padding: 12px; background: #007bff; color: #fff;
            border: none; border-radius: 6px; font-size: 16px; font-weight: bold;
            cursor: pointer; transition: 0.3s;
        }
        .tra-cuu-form button:hover { background: #0056b3; }
        table.result-table {
            width: 100%; border-collapse: collapse; background:#fff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        table.result-table th, table.result-table td {
            padding: 10px; text-align: center; border: 1px solid #ddd;
        }
        table.result-table th { background:#007bff; color:#fff; }
        .khong-tim-thay { color: red; text-align:center; margin-top:15px; font-weight:bold; }
        .status-cho-duyet { color: orange; font-weight: bold; }
        .status-xac-nhan { color: green; font-weight: bold; }
        .status-da-kham { color: #007bff; font-weight: bold; }
        .status-da-huy { color: red; font-weight: bold; }
    </style>
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
            <li><a href="tra_cuu.php" class="active">Tra cứu</a></li>
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
    <a href="index.php">Trang Chủ</a> &gt;
    <span>Tra cứu</span>
</div>

<div class="tra-cuu-container">
    <h2>Tra cứu thông tin</h2>

    <form method="post" class="tra-cuu-form">
        <label>Chọn loại tra cứu:</label>
        <select name="loai_tra_cuu" onchange="this.form.submit()">
            <option value="">-- Chọn --</option>
            <option value="ho_so" <?= (($_POST['loai_tra_cuu'] ?? '')==='ho_so')?'selected':'' ?>>Hồ sơ khám bệnh</option>
            <option value="lich_ranh" <?= (($_POST['loai_tra_cuu'] ?? '')==='lich_ranh')?'selected':'' ?>>Lịch rảnh bác sĩ</option>
            <option value="dat_lich" <?= (($_POST['loai_tra_cuu'] ?? '')==='dat_lich')?'selected':'' ?>>Lịch sử đặt lịch khám</option>
        </select>
    </form>

<?php
$loai = $_POST['loai_tra_cuu'] ?? '';

/* ========== TRA CỨU LỊCH SỬ ĐẶT LỊCH KHÁM (TỰ ĐỘNG THEO TÀI KHOẢN) ========== */
if ($loai === 'dat_lich') {
    // Kiểm tra nếu có đăng nhập bệnh nhân
    if (!isset($_SESSION['benh_nhan_id'])) {
        echo "<p style='text-align:center;color:red;font-weight:bold;'>⚠️ Vui lòng đăng nhập để tra cứu lịch khám của bạn.</p>";
    } else {
        $benhnhan_id = $_SESSION['benh_nhan_id'];
        $benhnhan = $conn->query("SELECT * FROM benh_nhan WHERE id = $benhnhan_id")->fetch_assoc();

        echo "<div class='tra-cuu-form'>
                <h3>Thông tin bệnh nhân</h3>
                <p><b>Họ tên:</b> {$benhnhan['ho_ten']}</p>
                <p><b>Ngày sinh:</b> {$benhnhan['ngay_sinh']}</p>
                <p><b>Số điện thoại:</b> {$benhnhan['sdt']}</p>
                <p><b>Email:</b> {$benhnhan['gmail_bn']}</p>
              </div>";

        // ✅ Xử lý hủy lịch và mở lại khung giờ rảnh cho bác sĩ
if (isset($_POST['huy_lich_id'])) {
    $id = intval($_POST['huy_lich_id']);

    // Lấy thông tin lịch hẹn để biết bác sĩ, ngày, giờ
    $sql_info = $conn->query("SELECT bac_si_id, ngay, gio_bat_dau, gio_ket_thuc 
                              FROM lich_hen 
                              WHERE id = $id AND benh_nhan_id = $benhnhan_id");

    if ($sql_info && $sql_info->num_rows > 0) {
        $info = $sql_info->fetch_assoc();

        // 1️⃣ Đổi trạng thái lịch hẹn sang "Đã hủy"
        $conn->query("UPDATE lich_hen SET trang_thai='Đã hủy' WHERE id=$id");

        // 2️⃣ Tìm đúng khung giờ rảnh và mở lại thành "trống"
        $bac_si_id = $info['bac_si_id'];
        $ngay = $info['ngay'];
        $gio_bat_dau = $info['gio_bat_dau'];
        $gio_ket_thuc = $info['gio_ket_thuc'];

        $update_ranh = $conn->prepare("
            UPDATE lich_ranh 
            SET trang_thai='Trống' 
            WHERE bac_si_id=? AND ngay=? AND gio_bat_dau=? AND gio_ket_thuc=?
        ");
        $update_ranh->bind_param("isss", $bac_si_id, $ngay, $gio_bat_dau, $gio_ket_thuc);
        $update_ranh->execute();

        echo "<p style='color:green;font-weight:bold;text-align:center;'>✅ Đã hủy lịch hẹn và mở lại khung giờ trống cho bác sĩ!</p>";
    } else {
        echo "<p style='color:red;font-weight:bold;text-align:center;'>❌ Không tìm thấy lịch hẹn cần hủy.</p>";
    }
}
        // Lấy lịch hẹn của bệnh nhân này
        $sql = "
            SELECT lh.*, bs.ho_ten AS bac_si
            FROM lich_hen lh
            JOIN bac_si bs ON lh.bac_si_id = bs.id
            WHERE lh.benh_nhan_id = $benhnhan_id
            ORDER BY lh.ngay DESC
        ";
        $res = $conn->query($sql);

        if ($res && $res->num_rows > 0) {
            echo "<h3>Lịch sử đặt lịch khám:</h3>
                  <table class='result-table'>
                  <tr>
                    <th>Ngày khám</th>
                    <th>Giờ bắt đầu</th>
                    <th>Giờ kết thúc</th>
                    <th>Lý do</th>
                    <th>Bác sĩ</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                  </tr>";
            while ($r = $res->fetch_assoc()) {
                $class = match (strtolower($r['trang_thai'])) {
                    'chờ duyệt', 'chờ xác nhận' => 'status-cho-duyet',
                    'đã xác nhận' => 'status-xac-nhan',
                    'đã khám' => 'status-da-kham',
                    'đã hủy' => 'status-da-huy',
                    default => ''
                };
                $btn = (in_array(strtolower($r['trang_thai']), ['chờ duyệt','chờ xác nhận']))
                    ? "<form method='post'><input type='hidden' name='loai_tra_cuu' value='dat_lich'>
                       <input type='hidden' name='huy_lich_id' value='{$r['id']}'>
                       <button type='submit' style='background:#dc3545;color:#fff;border:none;padding:6px 10px;border-radius:5px;cursor:pointer;'>Hủy</button></form>"
                    : '';
                echo "<tr>
                        <td>{$r['ngay']}</td>
                        <td>{$r['gio_bat_dau']}</td>
                        <td>{$r['gio_ket_thuc']}</td>
                        <td>".htmlspecialchars($r['ly_do'])."</td>
                        <td>{$r['bac_si']}</td>
                        <td class='$class'>{$r['trang_thai']}</td>
                        <td>$btn</td>
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "<p class='khong-tim-thay'>❌ Bạn chưa có lịch khám nào.</p>";
        }
    }
}

/* ========== TRA CỨU LỊCH RẢNH ========== */
if ($loai === 'lich_ranh') {
    echo '<form method="post" class="tra-cuu-form">';
    echo '<input type="hidden" name="loai_tra_cuu" value="lich_ranh">';
    echo '<label>Chọn chuyên khoa:</label>';
    echo '<select name="chuyen_khoa_id" onchange="this.form.submit()" required>';
    echo '<option value="">-- Chọn chuyên khoa --</option>';
    $ck = $conn->query("SELECT id, ten_chuyen_khoa FROM chuyen_khoa ORDER BY ten_chuyen_khoa");
    while ($r = $ck->fetch_assoc()) {
        $sel = (($_POST['chuyen_khoa_id'] ?? '') == $r['id']) ? 'selected' : '';
        echo "<option value='{$r['id']}' $sel>{$r['ten_chuyen_khoa']}</option>";
    }
    echo '</select></form>';

    if (!empty($_POST['chuyen_khoa_id'])) {
        $id = $_POST['chuyen_khoa_id'];
        echo '<form method="post" class="tra-cuu-form">';
        echo '<input type="hidden" name="loai_tra_cuu" value="lich_ranh">';
        echo '<input type="hidden" name="chuyen_khoa_id" value="'.$id.'">';
        echo '<label>Chọn bác sĩ:</label>';
        echo '<select name="bac_si_id" required>';
        echo '<option value="">-- Chọn bác sĩ --</option>';
        $bs = $conn->prepare("SELECT id, ho_ten FROM bac_si WHERE chuyen_khoa_id=? ORDER BY ho_ten");
        $bs->bind_param("i", $id);
        $bs->execute();
        $res = $bs->get_result();
        while ($r = $res->fetch_assoc()) {
            $sel = (($_POST['bac_si_id'] ?? '') == $r['id']) ? 'selected' : '';
            echo "<option value='{$r['id']}' $sel>{$r['ho_ten']}</option>";
        }
        echo '</select>';
        echo '<label>Chọn ngày:</label>';
        echo '<input type="date" name="ngay" value="'.($_POST['ngay'] ?? '').'" required>';
        echo '<button type="submit" name="xem_lich">Xem lịch rảnh</button></form>';
    }

    if (isset($_POST['xem_lich']) && !empty($_POST['bac_si_id']) && !empty($_POST['ngay'])) {
        $bs = intval($_POST['bac_si_id']);
        $ngay = $_POST['ngay'];
        $stmt = $conn->prepare("SELECT gio_bat_dau, gio_ket_thuc, trang_thai FROM lich_ranh WHERE bac_si_id=? AND ngay=? ORDER BY gio_bat_dau");
        $stmt->bind_param("is", $bs, $ngay);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows>0) {
            echo "<h3>Lịch rảnh bác sĩ:</h3>
                  <table class='result-table'><tr><th>Giờ bắt đầu</th><th>Giờ kết thúc</th><th>Trạng thái</th></tr>";
            while ($r = $res->fetch_assoc()) {
                $status = ($r['trang_thai']==='trống') ? "<span style='color:green;font-weight:bold;'>Trống</span>" : "<span style='color:red;font-weight:bold;'>Đã đặt</span>";
                echo "<tr><td>{$r['gio_bat_dau']}</td><td>{$r['gio_ket_thuc']}</td><td>$status</td></tr>";
            }
            echo "</table>";
        } else echo "<p class='khong-tim-thay'>Không có lịch rảnh cho ngày này.</p>";
    }
}
/* ========== TRA CỨU HỒ SƠ KHÁM BỆNH (TỰ ĐỘNG THEO TÀI KHOẢN) ========== */
if ($loai === 'ho_so') {
    if (!isset($_SESSION['benh_nhan_id'])) {
        echo "<p style='text-align:center;color:red;font-weight:bold;'>⚠️ Vui lòng đăng nhập để xem hồ sơ khám bệnh của bạn.</p>";
    } else {
        $benhnhan_id = $_SESSION['benh_nhan_id'];
        $benhnhan = $conn->query("SELECT * FROM benh_nhan WHERE id = $benhnhan_id")->fetch_assoc();

        echo "<div class='tra-cuu-form'>
                <h3>Thông tin bệnh nhân</h3>
                <p><b>Họ tên:</b> {$benhnhan['ho_ten']}</p>
                <p><b>Ngày sinh:</b> {$benhnhan['ngay_sinh']}</p>
                <p><b>Số điện thoại:</b> {$benhnhan['sdt']}</p>
              </div>";

        $sql = "
            SELECT hs.*, bs.ho_ten AS bac_si
            FROM ho_so_kham hs
            JOIN bac_si bs ON hs.bac_si_id = bs.id
            WHERE hs.benh_nhan_id = $benhnhan_id
            ORDER BY hs.ngay_lap DESC
        ";
        $res = $conn->query($sql);

        if ($res && $res->num_rows > 0) {
            echo "<h3>Hồ sơ khám bệnh:</h3>
                  <table class='result-table'>
                  <tr>
                    <th>Mã hồ sơ</th>
                    <th>Bác sĩ</th>
                    <th>Chẩn đoán</th>
                    <th>Điều trị</th>
                    <th>Ngày lập</th>
                  </tr>";
            while ($r = $res->fetch_assoc()) {
                echo "<tr>
                        <td>{$r['id']}</td>
                        <td>{$r['bac_si']}</td>
                        <td>".htmlspecialchars($r['chan_doan'])."</td>
                        <td>".htmlspecialchars($r['dieu_tri'])."</td>
                        <td>".date('d/m/Y H:i', strtotime($r['ngay_lap']))."</td>
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "<p class='khong-tim-thay'>❌ Không có hồ sơ khám bệnh nào.</p>";
        }
    }
}

?>
</div>
</body>
</html>

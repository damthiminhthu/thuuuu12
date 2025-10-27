<?php
include("../includes/connect.php");
$conn->set_charset("utf8mb4");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $ten = trim($_POST['ho_ten'] ?? '');
    $gioitinh = trim($_POST['gioi_tinh'] ?? '');
    $ngaysinh = $_POST['ngay_sinh'] ?? null;
    $sdt = trim($_POST['sdt'] ?? '');
    $gmail = trim($_POST['gmail_bn'] ?? '');
    $diachi = trim($_POST['dia_chi'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    if ($ten === '' || $username === '' || $sdt === '' || $gmail === '') {
        die("⚠️ Vui lòng nhập đầy đủ thông tin!");
    }

    // 1️⃣ Tạo tài khoản trước
    $stmt = $conn->prepare("INSERT INTO tai_khoan_benh_nhan (ten_dang_nhap, mat_khau, email, sdt) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $password, $gmail, $sdt);
    if (!$stmt->execute()) die("❌ Lỗi tạo tài khoản: " . $conn->error);
    $tai_khoan_id = $conn->insert_id;
    $stmt->close();

    // 2️⃣ Thêm bệnh nhân, liên kết khóa ngoại
    $stmt = $conn->prepare("INSERT INTO benh_nhan (tai_khoan_id, ho_ten, gioi_tinh, ngay_sinh, sdt, gmail_bn, dia_chi)
                            VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssss", $tai_khoan_id, $ten, $gioitinh, $ngaysinh, $sdt, $gmail, $diachi);
    if ($stmt->execute()) {
        echo "<script>alert('✅ Đăng ký thành công!'); window.location='login.php';</script>";
    } else {
        echo "❌ Lỗi thêm bệnh nhân: " . $conn->error;
    }
}
?>

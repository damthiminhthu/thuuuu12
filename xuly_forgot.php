<?php
include("../includes/connect.php");
$conn->set_charset('utf8mb4');

$loai = $_POST['loai_taikhoan'];
$email = trim($_POST['email']);
$newpass = substr(md5(time()), 0, 8);
$hash = password_hash($newpass, PASSWORD_DEFAULT);

switch ($loai) {
    case 'benh_nhan':
        $sql = "UPDATE tai_khoan_benh_nhan SET mat_khau=? WHERE email=?";
        break;
    case 'bac_si':
        $sql = "UPDATE tai_khoan_bac_si SET mat_khau=? WHERE email=?";
        break;
    case 'admin':
        $sql = "UPDATE tai_khoan_admin SET matkhau=? WHERE email=?";
        break;
    default:
        die("Loại tài khoản không hợp lệ");
}

$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $hash, $email);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo "<h3>✅ Mật khẩu mới của bạn là: <span style='color:red;'>$newpass</span></h3>";
    echo "<p>Hãy đăng nhập và đổi lại mật khẩu!</p>";
} else {
    echo "<h3 style='color:red;'>Không tìm thấy tài khoản với email này!</h3>";
}
?>

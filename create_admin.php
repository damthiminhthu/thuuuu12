<?php
// Kết nối CSDL
include_once '../includes/connect.php';

// Cấu hình tài khoản cần tạo
$username = 'admin';
$plainPassword = '123456'; // đổi nếu muốn

// Kiểm tra nếu tồn tại rồi thì dừng
$stmt = $conn->prepare("SELECT id_admin FROM tai_khoan_admin WHERE tendangnhap = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows > 0) {
    echo "❌ Tài khoản '$username' đã tồn tại.";
    exit;
}

// Hash mật khẩu
$hash = password_hash($plainPassword, PASSWORD_DEFAULT);

// Thực hiện thêm mới
$stmt = $conn->prepare("INSERT INTO tai_khoan_admin (tendangnhap, matkhau) VALUES (?, ?)");
$stmt->bind_param("ss", $username, $hash);

if ($stmt->execute()) {
    echo "✅ Tạo tài khoản admin thành công!<br>";
    echo "Tên đăng nhập: <b>$username</b><br>";
    echo "Mật khẩu: <b>$plainPassword</b><br>";
    echo "→ Hãy xóa file create_admin.php sau khi tạo để bảo mật.";
} else {
    echo "❌ Lỗi khi tạo tài khoản: " . $conn->error;
}

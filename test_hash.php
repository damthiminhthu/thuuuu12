<?php
$nhap = "123456";  // mật khẩu người dùng nhập
$hash = '$2y$10$ahA9OyUa77zmNUaZZ2flsuXKxR/1beXJvgyRaQtYScohuWbcMbet.'; // copy từ DB

if (password_verify($nhap, $hash)) {
    echo "✅ Mật khẩu khớp";
} else {
    echo "❌ Sai mật khẩu";
}
?>

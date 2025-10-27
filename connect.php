<?php
$servername = "localhost";
$username   = "root";
$password   = "";   // mật khẩu MySQL của bạn
$dbname     = "phongkham_vn";
$port       = 3306;       // MySQL mặc định chạy cổng 3306

$conn = new mysqli($servername, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
?>

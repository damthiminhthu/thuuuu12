<?php
include("../includes/connect.php");
include("../includes/header.php");
session_start();

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $ten_dich_vu = trim($_POST['ten_dich_vu']);
    $mo_ta = trim($_POST['mo_ta']);
    $trang_thai = $_POST['trang_thai'];

    // Xử lý upload ảnh
    $hinh_anh = "";
    if (!empty($_FILES["hinh_anh"]["name"])) {
        $target_dir = "../uploads/dichvu/";
        if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);
        $file_name = time() . "_" . basename($_FILES["hinh_anh"]["name"]);
        $target_file = $target_dir . $file_name;
        if (move_uploaded_file($_FILES["hinh_anh"]["tmp_name"], $target_file)) {
            $hinh_anh = $file_name;
        }
    }

    // Lưu vào CSDL
    $stmt = $conn->prepare("INSERT INTO dich_vu (ten_dich_vu, mo_ta, hinh_anh, trang_thai) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $ten_dich_vu, $mo_ta, $hinh_anh, $trang_thai);
    if ($stmt->execute()) {
        $message = "<p class='success'>✅ Thêm dịch vụ thành công!</p>";
    } else {
        $message = "<p class='error'>❌ Lỗi khi thêm: " . htmlspecialchars($stmt->error) . "</p>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Thêm Dịch Vụ Mới</title>
<style>
body {
    font-family: "Segoe UI", Arial, sans-serif;
    background: #eef4ff;
    margin: 0;
    padding: 0;
}
.form-wrapper {
    display: flex;
    justify-content: center;
    align-items: flex-start;
    min-height: 100vh;
    padding-top: 40px;
}
.form-container {
    width: 95%;
    max-width: 460px;
    background: #fff;
    border-radius: 18px;
    box-shadow: 0 6px 16px rgba(0,0,0,0.1);
    padding: 35px 40px 45px 40px;
    margin-left: 14cm;
}
h2 {
    text-align: center;
    color: #1b2cc1;
    margin-bottom: 25px;
    font-size: 22px;
    font-weight: 700;
}
h2::before {
    content: "+ ";
    color: #7b61ff;
    font-size: 28px;
}
.form-group {
    margin-bottom: 16px;
}
label {
    font-weight: 600;
    color: #2d2d2d;
    display: block;
    margin-bottom: 6px;
}
input[type="text"],
input[type="file"],
textarea,
select {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 15px;
    transition: 0.2s;
}
input:focus, textarea:focus, select:focus {
    outline: none;
    border-color: #007bff;
    box-shadow: 0 0 4px rgba(0,123,255,0.4);
}
textarea { resize: none; min-height: 80px; }

.btn-container {
    display: flex;
    justify-content: space-between;
    margin-top: 25px;
}
button {
    border: none;
    padding: 10px 22px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 15px;
    cursor: pointer;
    transition: 0.25s;
    display: flex;
    align-items: center;
    gap: 6px;
}
.btn-save {
    background: #43a047;
    color: #fff;
}
.btn-save:hover {
    background: #2e7d32;
}
.btn-back {
    background: #757575;
    color: #fff;
}
.btn-back:hover {
    background: #5c5c5c;
}
.success {
    color: green;
    font-weight: bold;
    text-align: center;
}
.error {
    color: red;
    font-weight: bold;
    text-align: center;
}
</style>
</head>
<body>
<div class="form-wrapper">
  <div class="form-container">
    <h2>Thêm Dịch Vụ Mới</h2>
    <?= $message ?>

    <form method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="ten_dich_vu">Tên dịch vụ:</label>
            <input type="text" id="ten_dich_vu" name="ten_dich_vu" required>
        </div>

        <div class="form-group">
            <label for="mo_ta">Mô tả:</label>
            <textarea id="mo_ta" name="mo_ta" placeholder="Nhập mô tả dịch vụ..."></textarea>
        </div>

        <label>Ảnh đại diện (URL):</label>
    <input type="text" name="avatar" placeholder="https://...">

        <div class="form-group">
            <label for="trang_thai">Trạng thái:</label>
            <select id="trang_thai" name="trang_thai" required>
                <option value="Hiển thị">Hiển thị</option>
                <option value="Ẩn">Ẩn</option>
            </select>
        </div>

        <div class="btn-container">
            <button type="submit" class="btn-save">Lưu</button>
            <button type="button" class="btn-back" onclick="window.location.href='ql_dichvu.php'">Quay lại</button>
        </div>
    </form>
  </div>
</div>
</body>
</html>

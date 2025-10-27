<?php
include("../includes/connect.php");
include("../includes/header.php");

// Xử lý thêm chuyên khoa (dùng link ảnh online)
if(isset($_POST['add'])){
    $ten_chuyen_khoa = trim($_POST['ten_chuyen_khoa']);
    $mo_ta = trim($_POST['mo_ta']);
    $anh_dai_dien = trim($_POST['anh_dai_dien']); // Nhập URL ảnh

    $sql = "INSERT INTO chuyen_khoa (ten_chuyen_khoa, mo_ta, anh_dai_dien)
            VALUES ('$ten_chuyen_khoa', '$mo_ta', '$anh_dai_dien')";
    if(mysqli_query($conn, $sql)){
        echo "<script>alert('✅ Thêm chuyên khoa thành công!'); window.location='ql_chuyenkhoa.php';</script>";
    } else {
        echo "<script>alert('❌ Lỗi khi thêm chuyên khoa!');</script>";
    }
}
?>

<style>
body {
  font-family: "Segoe UI", Arial, sans-serif;
  background: #f4f8ff;
  margin: 0;
  padding: 0;
  margin-left: 5cm;
}

.main-content {
  margin-left: 260px;
  padding: 30px 40px;
}

.container {
  max-width: 700px;
  margin: 40px auto;
  background: #fff;
  border-radius: 16px;
  box-shadow: 0 4px 16px rgba(0,0,0,0.08);
  padding: 30px 40px;
  text-align: center;
}

h2 {
  color: #1565c0;
  font-weight: 700;
  margin-bottom: 25px;
}

/* ===== Form ===== */
form {
  display: flex;
  flex-direction: column;
  gap: 18px;
}

label {
  font-weight: 600;
  color: #333;
  text-align: left;
}

input[type="text"],
textarea {
  width: 100%;
  padding: 10px 14px;
  border: 1.5px solid #cfd8dc;
  border-radius: 8px;
  font-size: 15px;
  background: #fafbfc;
  transition: 0.2s;
}

textarea {
  resize: vertical;
  min-height: 90px;
}

input:focus,
textarea:focus {
  border-color: #1976d2;
  background: #fff;
  outline: none;
}

/* ===== Buttons ===== */
.button-group {
  display: flex;
  justify-content: center;
  gap: 12px;
  margin-top: 15px;
}

.btn {
  border: none;
  border-radius: 8px;
  color: #fff;
  padding: 9px 20px;
  font-size: 15px;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.25s ease;
  box-shadow: 0 3px 6px rgba(0,0,0,0.1);
}

.btn-save {
  background: #43a047;
}
.btn-save:hover {
  background: #2e7d32;
}

.btn-cancel {
  background: #757575;
}
.btn-cancel:hover {
  background: #616161;
}

/* ===== Preview ảnh ===== */
.preview {
  margin-top: 10px;
  border-radius: 10px;
  overflow: hidden;
  border: 1px solid #e0e0e0;
}
.preview img {
  width: 100%;
  max-height: 240px;
  object-fit: cover;
  border-radius: 10px;
}

@media (max-width: 768px) {
  .main-content { margin-left: 0; padding: 20px; }
  .container { padding: 25px; }
}
</style>

<div class="container">
  <h2>Thêm Chuyên khoa Mới</h2>

  <form method="POST">
    <label>Tên chuyên khoa:</label>
    <input type="text" name="ten_chuyen_khoa" required placeholder="Nhập tên chuyên khoa...">

    <label>Mô tả:</label>
    <textarea name="mo_ta" placeholder="Nhập mô tả ngắn gọn về chuyên khoa..."></textarea>

    <label>Đường dẫn ảnh đại diện (URL):</label>
    <input type="text" name="anh_dai_dien" placeholder="Dán link ảnh (ví dụ: https://i.imgur.com/abc123.jpg)" oninput="previewURL(this.value)">
    
    <div class="preview" id="preview">
      <p style="color:#888;">(Ảnh xem trước sẽ hiện ở đây)</p>
    </div>

    <div class="button-group">
      <button type="submit" name="add" class="btn btn-save">Lưu</button>
      <a href="ql_chuyenkhoa.php" class="btn btn-cancel">Quay lại</a>
    </div>
  </form>
</div>

<script>
function previewURL(url) {
  const preview = document.getElementById('preview');
  if (url.trim() !== "") {
    preview.innerHTML = `<img src="${url}" alt="Ảnh xem trước" onerror="this.src='https://via.placeholder.com/400x240?text=Ảnh+không+hợp+lệ';">`;
  } else {
    preview.innerHTML = `<p style='color:#888;'>(Ảnh xem trước sẽ hiện ở đây)</p>`;
  }
}
</script>

<?php include("../includes/footer.php"); ?>

<?php
include("../includes/connect.php");
include("../includes/header.php");

if (isset($_POST['add'])) {
    $tieu_de = trim($_POST['tieu_de']);
    $noi_dung = trim($_POST['noi_dung']);
    $anh = trim($_POST['anh']);
    $anh = filter_var($anh, FILTER_SANITIZE_URL); // ✅ Chuẩn hóa URL
    $ngay_dang = date('Y-m-d H:i:s');
    $id_admin = "NULL"; // ✅ Tạm cho NULL để tránh lỗi khóa ngoại

    $sql = "INSERT INTO tin_tuc (tieu_de, noi_dung, anh, id_admin, ngay_dang)
            VALUES ('$tieu_de', '$noi_dung', '$anh', $id_admin, '$ngay_dang')";
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('✅ Thêm tin tức thành công!'); window.location='ql_tintuc.php';</script>";
    } else {
        echo "<script>alert('❌ Lỗi khi thêm tin tức!');</script>";
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
.container {
  max-width: 600px;
  margin: 40px auto;
  background: #fff;
  border-radius: 16px;
  box-shadow: 0 4px 16px rgba(0,0,0,0.08);
  padding: 30px 40px;
  text-align: center;
  margin-left: 10cm;
}
h2 {
  color: #1565c0;
  font-weight: 700;
  margin-bottom: 25px;
}
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
textarea { min-height: 80px; resize: none; }
input:focus, textarea:focus {
  border-color: #1976d2;
  background: #fff;
  outline: none;
}
img.preview {
  margin-top: 10px;
  width: 100%;
  max-height: 250px;
  object-fit: cover;
  border-radius: 10px;
  box-shadow: 0 3px 6px rgba(0,0,0,0.15);
}
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
.btn-save { background: #43a047; }
.btn-save:hover { background: #2e7d32; }
.btn-cancel { background: #757575; }
.btn-cancel:hover { background: #616161; }
</style>

<div class="container">
  <h2>Thêm Tin Tức Mới</h2>

  <form method="POST">
    <label>Tiêu đề:</label>
    <input type="text" name="tieu_de" required>

    <label>Nội dung:</label>
    <textarea name="noi_dung" placeholder="Nhập nội dung tin tức..." required></textarea>

    <label>Đường dẫn ảnh minh họa (URL):</label>
    <input type="text" id="anh" name="anh" placeholder="Dán link ảnh (ví dụ: https://i.imgur.com/abc123.jpg)" required>
    <img id="preview" class="preview" src="" alt="Xem trước ảnh" style="display:none;">

    <div class="button-group">
      <button type="submit" name="add" class="btn btn-save">Lưu</button>
      <a href="ql_tintuc.php" class="btn btn-cancel">Quay lại</a>
    </div>
  </form>
</div>

<script>
document.getElementById('anh').addEventListener('input', function() {
  const url = this.value.trim();
  const img = document.getElementById('preview');
  if (url.startsWith('http')) {
    img.src = url;
    img.style.display = 'block';
  } else {
    img.style.display = 'none';
  }
});
</script>

<?php include("../includes/footer.php"); ?>

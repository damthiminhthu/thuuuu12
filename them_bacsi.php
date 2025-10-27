<?php
include("../includes/connect.php");
include("../includes/header.php");

// Xử lý thêm bác sĩ
if (isset($_POST['add'])) {
    $ho_ten = trim($_POST['ho_ten']);
    $sdt = trim($_POST['sdt']);
    $email = trim($_POST['email']);
    $chuyen_khoa_id = intval($_POST['chuyen_khoa_id']);
    $avatar = trim($_POST['avatar']);

    // ✅ Kiểm tra email trùng
    $check = mysqli_query($conn, "SELECT id FROM tai_khoan_bac_si WHERE email = '$email'");
    if (mysqli_num_rows($check) > 0) {
        echo "<script>alert('❌ Email này đã tồn tại trong hệ thống! Vui lòng dùng email khác.');</script>";
    } else {
        // ✅ 1. Tạo tài khoản bác sĩ
        $username = $email;
        $default_password = password_hash("123456", PASSWORD_DEFAULT);
        $now = date('Y-m-d H:i:s');

        $sql_acc = "INSERT INTO tai_khoan_bac_si (ten_dang_nhap, mat_khau, email, tao_luc)
                    VALUES ('$username', '$default_password', '$email', '$now')";
        if (mysqli_query($conn, $sql_acc)) {
            $taikhoan_id = mysqli_insert_id($conn);

            // ✅ 2. Thêm bác sĩ mới
            $sql_add = "INSERT INTO bac_si (ho_ten, sdt, email, avatar, chuyen_khoa_id, tai_khoan_id)
                        VALUES ('$ho_ten', '$sdt', '$email', '$avatar', '$chuyen_khoa_id', '$taikhoan_id')";
            if (mysqli_query($conn, $sql_add)) {
                echo "<script>alert('✅ Thêm bác sĩ & tạo tài khoản thành công!'); window.location='ql_bacsi.php';</script>";
            } else {
                echo "<script>alert('❌ Lỗi khi thêm bác sĩ!');</script>";
            }
        } else {
            echo "<script>alert('❌ Lỗi khi tạo tài khoản!');</script>";
        }
    }
}
?>

<style>
body {
  font-family: "Segoe UI", Arial, sans-serif;
  background: #f4f8ff;
  margin: 0;
  padding: 0;
}
.main-content { margin-left: 260px; padding: 30px 40px; }
.container {
  max-width: 700px; margin: 40px auto; background: #fff;
  border-radius: 16px; box-shadow: 0 4px 16px rgba(0,0,0,0.08);
  padding: 30px 40px; text-align: center;
  margin-left: 6cm;
}
h2 { color: #1565c0; font-weight: 700; margin-bottom: 25px; }
form { display: flex; flex-direction: column; gap: 18px; }
label { font-weight: 600; color: #333; text-align: left; }
input[type="text"], select {
  width: 100%; padding: 10px 14px; border: 1.5px solid #cfd8dc;
  border-radius: 8px; font-size: 15px; background: #fafbfc; transition: 0.2s;
}
input:focus, select:focus { border-color: #1976d2; background: #fff; outline: none; }

/* Ảnh xem trước */
.preview-img {
  margin-top: 10px;
  width: 150px;
  height: 150px;
  object-fit: cover;
  border-radius: 8px;
  display: none;
  box-shadow: 0 3px 6px rgba(0,0,0,0.15);
}

/* Buttons */
.button-group {
  display: flex; justify-content: center; gap: 12px; margin-top: 15px;
}
.btn {
  border: none; border-radius: 8px; color: #fff; padding: 9px 20px;
  font-size: 15px; font-weight: 500; cursor: pointer;
  transition: all 0.25s ease; box-shadow: 0 3px 6px rgba(0,0,0,0.1);
}
.btn-save { background: #43a047; }
.btn-save:hover { background: #2e7d32; }
.btn-cancel { background: #757575; }
.btn-cancel:hover { background: #616161; }
</style>

<div class="container">
  <h2>Thêm Bác sĩ Mới</h2>

  <form method="POST">
    <label>Họ tên:</label>
    <input type="text" name="ho_ten" required>

    <label>Số điện thoại:</label>
    <input type="text" name="sdt" required>

    <label>Email (tên đăng nhập):</label>
    <input type="text" name="email" required placeholder="ví dụ: bacsiabc@gmail.com">

    <label>Ảnh đại diện (URL):</label>
    <input type="text" name="avatar" id="avatar" placeholder="https://..." required>
    <img id="preview" class="preview-img" alt="Ảnh bác sĩ">

    <label>Chuyên khoa:</label>
    <select name="chuyen_khoa_id" required>
      <option value="">-- Chọn chuyên khoa --</option>
      <?php
      $chuyen_khoa = mysqli_query($conn, "SELECT id, ten_chuyen_khoa FROM chuyen_khoa");
      while ($row = mysqli_fetch_assoc($chuyen_khoa)) {
          echo "<option value='{$row['id']}'>{$row['ten_chuyen_khoa']}</option>";
      }
      ?>
    </select>

    <div class="button-group">
      <button type="submit" name="add" class="btn btn-save">Lưu</button>
      <a href="ql_bacsi.php" class="btn btn-cancel">Quay lại</a>
    </div>
  </form>
</div>

<script>
// ✅ Hiển thị ảnh xem trước ngay khi nhập URL
document.getElementById('avatar').addEventListener('input', function() {
  const preview = document.getElementById('preview');
  const link = this.value.trim();
  if (link) {
    preview.src = link;
    preview.style.display = 'block';
  } else {
    preview.style.display = 'none';
  }
});
</script>

<?php include("../includes/footer.php"); ?>

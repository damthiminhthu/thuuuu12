<?php
include("../includes/connect.php");
include("../includes/header.php");

// Xử lý thêm bệnh nhân
if(isset($_POST['add'])){
    $ho_ten = trim($_POST['ho_ten']);
    $gioi_tinh = trim($_POST['gioi_tinh']);
    $ngay_sinh = trim($_POST['ngay_sinh']);
    $sdt = trim($_POST['sdt']);
    $gmail_bn = trim($_POST['gmail_bn']);
    $dia_chi = trim($_POST['dia_chi']);

    // ✅ Kiểm tra trùng email trong bảng tai_khoan_benh_nhan
    $check_email = $conn->prepare("SELECT id FROM tai_khoan_benh_nhan WHERE email = ?");
    $check_email->bind_param("s", $gmail_bn);
    $check_email->execute();
    $check_email->store_result();

    if ($check_email->num_rows > 0) {
        echo "<script>alert('⚠️ Email này đã tồn tại! Vui lòng nhập email khác.');</script>";
    } else {
        // ✅ 1. Thêm vào bảng benh_nhan
        $sql_add = "INSERT INTO benh_nhan (ho_ten, gioi_tinh, ngay_sinh, sdt, gmail_bn, dia_chi)
                    VALUES ('$ho_ten', '$gioi_tinh', '$ngay_sinh', '$sdt', '$gmail_bn', '$dia_chi')";
        if (mysqli_query($conn, $sql_add)) {

            // ✅ Lấy id bệnh nhân vừa thêm
            $benhnhan_id = mysqli_insert_id($conn);

            // ✅ 2. Tự tạo tài khoản bệnh nhân
            $username = $gmail_bn;
            $default_password = password_hash("123456", PASSWORD_DEFAULT);
            $now = date('Y-m-d H:i:s');

            $sql_acc = "INSERT INTO tai_khoan_benh_nhan (ten_dang_nhap, mat_khau, email, sdt, ngay_tao)
                        VALUES ('$username', '$default_password', '$gmail_bn', '$sdt', '$now')";
            if (mysqli_query($conn, $sql_acc)) {
                $taikhoan_id = mysqli_insert_id($conn);

                // ✅ Cập nhật id tài khoản vào bảng benh_nhan
                $sql_update_bn = "UPDATE benh_nhan SET tai_khoan_id = '$taikhoan_id' WHERE id = '$benhnhan_id'";
                mysqli_query($conn, $sql_update_bn);
            }

            echo "<script>alert('✅ Thêm bệnh nhân & tạo tài khoản thành công!'); window.location='ql_benhnhan.php';</script>";
        } else {
            echo "<script>alert('❌ Lỗi khi thêm bệnh nhân!');</script>";
        }
    }
    $check_email->close();
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
input[type="date"],
select {
  width: 100%;
  padding: 10px 14px;
  border: 1.5px solid #cfd8dc;
  border-radius: 8px;
  font-size: 15px;
  background: #fafbfc;
  transition: 0.2s;
}

input:focus,
select:focus {
  border-color: #1976d2;
  background: #fff;
  outline: none;
}

/* Buttons */
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
</style>

<div class="container">
  <h2>Thêm Bệnh nhân Mới</h2>

  <form method="POST">
    <label>Họ tên:</label>
    <input type="text" name="ho_ten" required>

    <label>Giới tính:</label>
    <select name="gioi_tinh" required>
      <option value="">-- Chọn giới tính --</option>
      <option value="Nam">Nam</option>
      <option value="Nữ">Nữ</option>
    </select>

    <label>Ngày sinh:</label>
    <input type="date" name="ngay_sinh" required>

    <label>Số điện thoại:</label>
    <input type="text" name="sdt" required>

    <label>Email (tên đăng nhập):</label>
    <input type="text" name="gmail_bn" required placeholder="ví dụ: abc@gmail.com">

    <label>Địa chỉ:</label>
    <input type="text" name="dia_chi" required>

    <div class="button-group">
      <button type="submit" name="add" class="btn btn-save">Lưu</button>
      <a href="ql_benhnhan.php" class="btn btn-cancel">Quay lại</a>
    </div>
  </form>
</div>

<?php include("../includes/footer.php"); ?>

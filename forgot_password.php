<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Khôi phục mật khẩu</title>
<style>
body {
  font-family: Arial, sans-serif;
  background: linear-gradient(120deg, #e3f2fd, #bbdefb);
  display: flex; justify-content: center; align-items: center;
  height: 100vh;
}
.form-box {
  background: white; padding: 30px;
  border-radius: 12px; width: 380px;
  box-shadow: 0 0 10px rgba(0,0,0,0.1);
}
h2 { text-align:center; color:#1565c0; }
input, select, button {
  width: 100%; padding: 10px;
  margin: 10px 0;
  border-radius: 6px; border: 1px solid #ccc;
}
button {
  background: #1976d2; color: white; border: none;
  font-weight: bold;
}
button:hover { background: #0d47a1; }
</style>
</head>
<body>
<div class="form-box">
  <h2>Khôi phục mật khẩu</h2>
  <form method="POST" action="xuly_forgot.php">
    <select name="loai_taikhoan" required>
      <option value="benh_nhan">Bệnh nhân</option>
      <option value="bac_si">Bác sĩ</option>
      <option value="admin">Quản trị viên</option>
    </select>
    <input type="email" name="email" placeholder="Nhập email tài khoản..." required>
    <button type="submit">Khôi phục</button>
  </form>
  <div style="text-align:center">
    <a href="login.php">⬅ Quay lại đăng nhập</a>
  </div>
</div>
</body>
</html>

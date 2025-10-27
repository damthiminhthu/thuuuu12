<?php
session_start();
$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Đăng nhập hệ thống phòng khám</title>
<style>
/* ===== Reset & Layout ===== */
body {
  font-family: Arial, sans-serif;
  background: linear-gradient(135deg, #bbdefb, #e3f2fd);
  margin: 0;
  padding: 0;
  height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
}

/* ===== Hộp chính ===== */
.login-container {
  background: #ffffff;
  width: 420px;
  padding: 50px 45px;
  border-radius: 25px;
  box-shadow: 0 10px 30px rgba(0,0,0,0.1);
  text-align: center;
  border: 6px solid #f8f9fa;
}

/* ===== Tiêu đề ===== */
h2 {
  color: #1565c0;
  font-size: 24px;
  font-weight: bold;
  margin-bottom: 28px;
}
label {
  display: block;
  font-weight: bold;
  color: #333;
  text-align: left;
  margin: 10px 0 5px;
}

input[type="text"],
input[type="password"],
select {
  display: block;
  width: 100%;
  height: 44px;
  padding: 0 14px;
  border: 1.5px solid #cfd8dc;
  border-radius: 10px;
  font-size: 15px;
  background: #fafbfc;
  transition: all 0.25s ease;
  margin-bottom: 15px;
  box-sizing: border-box;
  appearance: none; /* Bỏ kiểu mặc định để đều với input */
}

select {
  background-image: url("data:image/svg+xml;utf8,<svg fill='gray' height='18' viewBox='0 0 24 24' width='18' xmlns='http://www.w3.org/2000/svg'><path d='M7 10l5 5 5-5z'/></svg>");
  background-repeat: no-repeat;
  background-position: right 10px center;
  background-size: 16px;
}

input:focus,
select:focus {
  border-color: #1976d2;
  box-shadow: 0 0 4px rgba(25,118,210,0.3);
  outline: none;
  background: #fff;
}

/* ===== Nút ===== */
button {
  width: 100%;
  background: #1976d2;
  color: white;
  border: none;
  padding: 12px;
  border-radius: 10px;
  font-size: 16px;
  font-weight: bold;
  cursor: pointer;
  transition: 0.25s;
}

button:hover {
  background: #0d47a1;
  transform: translateY(-1px);
}

/* ===== Thông báo lỗi ===== */
.error {
  color: #d32f2f;
  background: #ffebee;
  padding: 10px;
  border-radius: 8px;
  font-size: 14px;
  margin-bottom: 15px;
  border: 1px solid #ffcdd2;
}

/* ===== Footer ===== */
.footer {
  margin-top: 20px;
  font-size: 14px;
  color: #555;
}

.footer a {
  color: #1565c0;
  font-weight: bold;
  text-decoration: none;
}

.footer a:hover {
  text-decoration: underline;
}
</style>
</head>

<body>
<div class="login-container">
  <h2>Đăng nhập hệ thống</h2>

  <?php if ($error): ?>
    <p class="error"><?= htmlspecialchars($error) ?></p>
  <?php endif; ?>

  <form method="POST" action="xuly_login.php">
    <label>Loại tài khoản:</label>
    <select name="loai_taikhoan" required>
      <option value="benh_nhan">👤 Bệnh nhân</option>
      <option value="bac_si">🧑‍⚕️ Bác sĩ</option>
      <option value="admin">🧑‍💼 Quản trị viên</option>
    </select>

    <label>Tên đăng nhập:</label>
    <input type="text" name="username" placeholder="Nhập tên đăng nhập..." required>

    <label>Mật khẩu:</label>
    <input type="password" name="password" placeholder="Nhập mật khẩu..." required>

    <button type="submit">Đăng nhập</button>
  </form>

  <div class="footer">
    <a href="register.php">Đăng ký tài khoản</a> | 
    <a href="#">Quên mật khẩu?</a>
  </div>
</div>
</body>
</html>

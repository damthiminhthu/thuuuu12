<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Đăng ký tài khoản bệnh nhân</title>
<style>
/* ==== Reset & Layout ==== */
body {
  font-family: Arial, sans-serif;
  background: linear-gradient(135deg, #bbdefb, #e3f2fd);
  margin: 0;
  padding: 40px 0;
  min-height: 100vh;
  display: flex;
  justify-content: center;
  align-items: flex-start;
  overflow-y: auto;
}

/* ==== Container chính ==== */
.register-container {
  background: #ffffff;
  width: 440px;
  padding: 50px 45px;
  border-radius: 25px;
  box-shadow: 0 10px 30px rgba(0,0,0,0.1);
  text-align: center;
  border: 6px solid #f8f9fa;
  margin: 30px 0;
}

/* ==== Tiêu đề ==== */
h2 {
  color: #1565c0;
  font-size: 24px;
  font-weight: bold;
  margin-bottom: 28px;
}

/* ==== Label ==== */
label {
  display: block;
  font-weight: bold;
  color: #333;
  text-align: left;
  margin: 10px 0 5px;
}

/* ==== Input & Select ==== */
input[type="text"],
input[type="email"],
input[type="tel"],
input[type="password"],
input[type="date"],
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
  appearance: none;
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

/* ==== Nút Đăng ký ==== */
button {
  width: 100%;
  background: #43a047;
  color: #fff;
  border: none;
  border-radius: 10px;
  padding: 12px;
  margin-top: 5px;
  font-size: 16px;
  font-weight: bold;
  cursor: pointer;
  transition: 0.25s;
}

button:hover {
  background: #2e7d32;
  transform: translateY(-1px);
}

/* ==== Footer ==== */
.footer {
  margin-top: 18px;
  font-size: 14px;
  color: #555;
}

.footer a {
  color: #1565c0;
  font-weight: 600;
  text-decoration: none;
}
.footer a:hover { text-decoration: underline; }
</style>
</head>

<body>
  <div class="register-container">
    <h2>Đăng ký tài khoản bệnh nhân</h2>

    <form method="POST" action="register_submit.php">
      <label>Họ và tên:</label>
      <input type="text" name="ho_ten" required>

      <label>Giới tính:</label>
      <select name="gioi_tinh" required>
        <option value="">-- Chọn giới tính --</option>
        <option value="Nam">Nam</option>
        <option value="Nữ">Nữ</option>
        <option value="Khác">Khác</option>
      </select>

      <label>Ngày sinh:</label>
      <input type="date" name="ngay_sinh">

      <label>Số điện thoại:</label>
      <input type="tel" name="sdt" required>

      <label>Gmail:</label>
      <input type="email" name="gmail_bn" required>

      <label>Địa chỉ:</label>
      <input type="text" name="dia_chi">

      <hr style="margin:15px 0; border:none; border-top:1.5px solid #eee;">

      <label>Tên đăng nhập:</label>
      <input type="text" name="username" required>

      <label>Mật khẩu:</label>
      <input type="password" name="password" required>

      <button type="submit">Đăng ký</button>
    </form>
  </div>
</body>
</html>

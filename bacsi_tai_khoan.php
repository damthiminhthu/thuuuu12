<?php
include("bacsi_header.php");
include("../includes/connect.php");

$bac_si_id = $_SESSION['bac_si_id'] ?? 0;
if ($bac_si_id == 0) {
    header("Location: ../admin/login.php");
    exit;
}

// Lấy thông tin bác sĩ & tài khoản
$sql = "
SELECT bs.id, bs.ho_ten, bs.sdt, bs.email, bs.avatar, ck.ten_chuyen_khoa, bs.tai_khoan_id, tk.ten_dang_nhap, tk.email AS email_tk
FROM bac_si bs
JOIN tai_khoan_bac_si tk ON bs.tai_khoan_id = tk.id
LEFT JOIN chuyen_khoa ck ON bs.chuyen_khoa_id = ck.id
WHERE bs.id = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $bac_si_id);
$stmt->execute();
$info = $stmt->get_result()->fetch_assoc();

// Cập nhật thông tin cá nhân
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_info'])) {
    $ho_ten = $_POST['ho_ten'];
    $email = $_POST['email'];
    $sdt = $_POST['sdt'];
    $update = $conn->prepare("UPDATE bac_si SET ho_ten=?, email=?, sdt=? WHERE id=?");
    $update->bind_param("sssi", $ho_ten, $email, $sdt, $bac_si_id);
    if ($update->execute()) {
        echo "<script>alert('✅ Cập nhật thông tin thành công!'); window.location='bacsi_tai_khoan.php';</script>";
    } else echo "<script>alert('❌ Lỗi khi cập nhật!');</script>";
}

// Đổi mật khẩu
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['change_pass'])) {
    $old = $_POST['old_pass'];
    $new = $_POST['new_pass'];
    $confirm = $_POST['confirm_pass'];
    $q = $conn->prepare("SELECT mat_khau FROM tai_khoan_bac_si WHERE id=?");
    $q->bind_param("i", $info['tai_khoan_id']);
    $q->execute();
    $pw = $q->get_result()->fetch_assoc();
    if (!password_verify($old, $pw['mat_khau'])) {
        echo "<script>alert('❌ Mật khẩu cũ không đúng!');</script>";
    } elseif ($new !== $confirm) {
        echo "<script>alert('⚠️ Mật khẩu xác nhận không khớp!');</script>";
    } else {
        $hashed = password_hash($new, PASSWORD_DEFAULT);
        $update = $conn->prepare("UPDATE tai_khoan_bac_si SET mat_khau=? WHERE id=?");
        $update->bind_param("si", $hashed, $info['tai_khoan_id']);
        $update->execute();
        echo "<script>alert('✅ Đổi mật khẩu thành công!');</script>";
    }
}
?>

<head>
<meta charset="UTF-8">
<title>Hồ sơ bác sĩ</title>
<link rel="stylesheet" href="../css/bacsi_style.css">
<style>
.avatar-circle {
  width: 120px;
  height: 120px;
  border-radius: 50%;
  object-fit: cover;
  border: 0px solid;
  box-shadow: 0 2px 8px rgba(0,0,0,0.15);
  margin-bottom: 15px;
}

.info-table {
  width: 90%;
  max-width: 700px;
  margin: 0 auto 15px;
  border-collapse: collapse;
  background: #fff;
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 2px 6px rgba(0,0,0,0.05);
}
.info-table th {
  background: #1976d2;
  color: white;
  padding: 10px;
  text-align: center;
}
.info-table td {
  padding: 10px 15px;
  border-bottom: 1px solid #e3f2fd;
}
.info-table tr:last-child td {
  border-bottom: none;
}
.info-table input {
  width: 100%;
  padding: 6px 10px;
  border-radius: 6px;
  border: 1px solid #cfd8dc;
  font-size: 14px;
  background: #fafafa;
}
.info-table input:focus {
  background: #fff;
  border-color: #64b5f6;
  box-shadow: 0 0 4px rgba(100,181,246,0.5);
}

.btn-luu, .btn-show {
  background: #4caf50;
  color: #fff;
  border: none;
  padding: 8px 18px;
  border-radius: 6px;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  margin: 10px 0;
  transition: 0.2s;
}
.btn-luu:hover { background: #388e3c; }
.btn-show {
  background: #64b5f6;
  margin-top: 15px;
}
.btn-show:hover { background: #1e88e5; }

.hidden {
  display: none !important;
  opacity: 0;
  transition: all 0.3s ease;
}

.show {
  display: flex !important;
  opacity: 1;
  flex-direction: column;
  align-items: center;
  gap: 10px;
  margin-top: 10px;
}

.change-pass input {
  width: 60%;
  padding: 8px;
  border: 1px solid #cfd8dc;
  border-radius: 6px;
  font-size: 14px;
  background: #fafafa;
}
.change-pass input:focus {
  border-color: #64b5f6;
  box-shadow: 0 0 4px rgba(100,181,246,0.6);
}
</style>
</head>

<div class="container">
  <h2>👨‍⚕️ Hồ sơ cá nhân</h2>

  <div style="text-align:center;">
    <img src="<?= $info['avatar'] ?: '../assets/default_doctor.png' ?>" alt="Avatar bác sĩ" class="avatar-circle">
  </div>

  <form method="POST">
    <input type="hidden" name="update_info" value="1">
    <table class="info-table">
      <tr><th>Trường</th><th>Thông tin</th></tr>
      <tr><td>Họ tên</td><td><input type="text" name="ho_ten" value="<?= htmlspecialchars($info['ho_ten']) ?>"></td></tr>
      <tr><td>Email</td><td><input type="email" name="email" value="<?= htmlspecialchars($info['email']) ?>"></td></tr>
      <tr><td>Số điện thoại</td><td><input type="text" name="sdt" value="<?= htmlspecialchars($info['sdt']) ?>"></td></tr>
      <tr><td>Chuyên khoa</td><td><input type="text" value="<?= htmlspecialchars($info['ten_chuyen_khoa']) ?>" disabled></td></tr>
    </table>
    <div style="text-align:center;">
      <button type="submit" class="btn-luu">Lưu thay đổi</button>
    </div>
  </form>

  <div class="account-box" style="text-align:center;">
    <h3>🔐 Thông tin tài khoản</h3>
    <table class="info-table">
      <tr><th>Tên đăng nhập</th><th>Email đăng ký</th></tr>
      <tr><td><?= htmlspecialchars($info['ten_dang_nhap']) ?></td><td><?= htmlspecialchars($info['email_tk']) ?></td></tr>
    </table>

    <button class="btn-show" id="toggleBtn">Đổi mật khẩu</button>

    <form method="POST" id="passwordForm" class="change-pass hidden">
      <input type="hidden" name="change_pass" value="1">
      <input type="password" name="old_pass" placeholder="Nhập mật khẩu cũ" required>
      <input type="password" name="new_pass" placeholder="Nhập mật khẩu mới" required>
      <input type="password" name="confirm_pass" placeholder="Nhập lại mật khẩu mới" required>
      <button type="submit" class="btn-luu">Lưu mật khẩu mới</button>
    </form>
  </div>
</div>

<script>
const form = document.getElementById("passwordForm");
const btn = document.getElementById("toggleBtn");
btn.addEventListener("click", () => {
  form.classList.toggle("hidden");
  form.classList.toggle("show");
});
</script>

<?php include("bacsi_footer.php"); ?>

<?php
session_set_cookie_params(['path' => '/']);
session_start();
include_once '../includes/connect.php';

$isLoggedIn = isset($_SESSION['benh_nhan_id']) || isset($_SESSION['user']) || isset($_SESSION['bac_si_id']);
$benhnhan = null;
$benh_nhan_id = $_SESSION['benh_nhan_id'] ?? 0;

// ✅ Nếu là bệnh nhân thì lấy thông tin để điền form
if ($benh_nhan_id) {
    $stmt = $conn->prepare("SELECT * FROM benh_nhan WHERE id = ?");
    $stmt->bind_param("i", $benh_nhan_id);
    $stmt->execute();
    $benhnhan = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Đặt Lịch Khám - Phòng Khám Đa Khoa</title>
<link rel="stylesheet" href="../css/style.css">
<style>
body { font-family: Arial, sans-serif; background: #eef5ff; margin: 0; }
.form-container {
    width: 90%; max-width: 900px; margin: 40px auto;
    background: #fff; padding: 35px 45px;
    border-radius: 20px; box-shadow: 0 6px 18px rgba(0,0,0,0.1);
}
.form-container h2 { text-align: center; color: #004aad; margin-bottom: 25px; font-size: 26px; font-weight: 700; }
.form-group { margin-bottom: 18px; }
label { display: block; font-weight: bold; color: #003366; margin-bottom: 6px; }
input, select, textarea {
    width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 8px;
    font-size: 15px; box-sizing: border-box;
}
textarea { resize: none; min-height: 80px; }
.btn-submit {
    background: #007bff; color: white; border: none;
    border-radius: 25px; padding: 12px 22px;
    font-size: 15px; font-weight: bold; cursor: pointer;
    transition: background 0.25s ease;
}
.btn-submit:hover { background: #005ad3; }
.notice {
    background: #ffeaea; padding: 10px; border: 1px solid #ffb3b3;
    color: #a00; border-radius: 6px; margin-bottom: 15px;
}
        /* Breadcrumb */
        .breadcrumb {
            font-size: 15px;
            padding: 14px 30px;
            background: #f5f5f5;
            border-bottom: 1px solid #ddd;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .breadcrumb a {
            text-decoration: none;
            color: #007bff;
            font-weight: 600;
        }
        .breadcrumb a:hover {
            text-decoration: underline;
        }
        .breadcrumb span {
            color: #222;
            font-weight: 700;
        }
        .breadcrumb .separator {
            color: #666;
            font-size: 16px;
        }
.footer { background: #004aad; color: #fff; text-align: center; padding: 20px; margin-top: 40px; }
.footer-content p { margin: 5px 0; }
        /* Menu active */
        .menu li a.active {
            color: #007bff;
            font-weight: bold;
        }
</style>
</head>
<body>

<header class="header">
    <div class="header-text">
        <h2>PHÒNG KHÁM ĐA KHOA</h2>
        <p class="slogan">Tận Tâm - Sáng Tạo - Nâng Tầm Tri Thức</p>
    </div>
    <nav class="main-nav">
        <ul class="menu">
            <li><a href="gioi-thieu.php">Giới thiệu</a></li>
            <li><a href="chuyen-khoa.php">Chuyên khoa</a></li>
            <li><a href="dich-vu.php">Dịch vụ</a></li>
            <li><a href="doctor.php">Tìm bác sĩ</a></li>
            <li><a href="booking.php" class="active">Đặt lịch khám</a></li>
            <li><a href="tra_cuu.php">Tra cứu</a></li>
            <li><a href="tin-tuc.php">Tin tức</a></li>
            <!-- ✅ Tự động hiển thị Đăng nhập / Đăng xuất -->
            <?php if ($isLoggedIn): ?>
                <li><a href="../admin/logout.php" class="logout-btn">Đăng xuất</a></li>
            <?php else: ?>
                <li><a href="../admin/login.php" class="login-btn">Đăng nhập</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

<div class="breadcrumb">
  <a href="index.php">Trang Chủ</a> &gt;
  <span>Đặt lịch khám</span>
</div>

<div class="form-container">
  <h2>Đặt lịch khám bệnh</h2>

  <?php if(!$isLoggedIn): ?>
    <div class="notice">
      Bạn chưa đăng nhập. <a href="../login/login.php?redirect=booking.php">Đăng nhập</a> để đặt lịch.
    </div>
  <?php endif; ?>

  <form id="bookingForm" method="post" action="savebooking.php">
    <div class="form-group">
      <label>Chuyên khoa</label>
      <select id="chuyen_khoa" name="chuyen_khoa" required>
        <option value="">-- Chọn chuyên khoa --</option>
        <?php
        $rs = $conn->query("SELECT id, ten_chuyen_khoa FROM chuyen_khoa ORDER BY ten_chuyen_khoa ASC");
        while ($r = $rs->fetch_assoc()) {
          echo "<option value='{$r['id']}'>{$r['ten_chuyen_khoa']}</option>";
        }
        ?>
      </select>
    </div>

    <div class="form-group">
      <label>Ngày khám</label>
      <input type="date" id="ngay" name="ngay" required>
    </div>
    <div class="form-group">
  <label>Giờ bạn rảnh</label>
  <select id="gio_ranh" name="gio_ranh" required>
    <option value="">-- Chọn giờ bạn rảnh --</option>
    <option value="07:00:00">07:00</option>
    <option value="08:00:00">08:00</option>
    <option value="09:00:00">09:00</option>
    <option value="10:00:00">10:00</option>
    <option value="13:00:00">13:00</option>
    <option value="14:00:00">14:00</option>
    <option value="15:00:00">15:00</option>
  </select>
</div>


    <div class="form-group">
      <label>Bác sĩ</label>
      <select id="bac_si" name="bac_si" required>
        <option value="">-- Chọn bác sĩ --</option>
      </select>
    </div>

    <div class="form-group">
      <label>Khung giờ</label>
      <select id="khung_gio" name="khung_gio" required>
        <option value="">-- Chọn khung giờ --</option>
      </select>
      <p id="warning" style="color:red;font-weight:bold;display:none;">⚠️ Bạn đã có lịch trùng khung giờ này!</p>
    </div>

    <h3>Thông tin bệnh nhân</h3>
    <div class="form-group"><label>Họ và tên</label><input type="text" name="ho_ten" value="<?= htmlspecialchars($benhnhan['ho_ten'] ?? '') ?>" required></div>
    <div class="form-group"><label>Email</label><input type="email" name="gmail_bn" value="<?= htmlspecialchars($benhnhan['gmail_bn'] ?? '') ?>" required></div>
    <div class="form-group">
      <label>Giới tính</label>
      <select name="gioi_tinh" required>
        <option value="">Chọn</option>
        <option value="Nam" <?= (isset($benhnhan['gioi_tinh']) && $benhnhan['gioi_tinh']=='Nam')?'selected':'' ?>>Nam</option>
        <option value="Nữ" <?= (isset($benhnhan['gioi_tinh']) && $benhnhan['gioi_tinh']=='Nữ')?'selected':'' ?>>Nữ</option>
      </select>
    </div>
    <div class="form-group"><label>Ngày sinh</label><input type="date" name="ngay_sinh" value="<?= htmlspecialchars($benhnhan['ngay_sinh'] ?? '') ?>" required></div>
    <div class="form-group"><label>SĐT</label><input type="tel" name="so_dien_thoai" value="<?= htmlspecialchars($benhnhan['sdt'] ?? '') ?>" required></div>
    <div class="form-group"><label>Địa chỉ</label><input type="text" name="dia_chi" value="<?= htmlspecialchars($benhnhan['dia_chi'] ?? '') ?>" required></div>
    <div class="form-group"><label>Lý do khám</label><textarea name="ly_do" placeholder="Nhập lý do khám..."></textarea></div>

    <div style="text-align:center;">
      <button id="submitBtn" class="btn-submit" type="submit">Đặt lịch</button>
    </div>
  </form>
</div>

<script>
// ✅ Load bác sĩ theo chuyên khoa, ngày và giờ rảnh — gọi từ get_slots.php
function loadDoctors() {
  const khoa = document.getElementById('chuyen_khoa').value;
  const ngay = document.getElementById('ngay').value;
  const gio_raw = document.getElementById('gio_ranh')?.value || '';
  const gio = gio_raw.length === 5 ? gio_raw + ':00' : gio_raw;
  const selectBs = document.getElementById('bac_si');

  selectBs.innerHTML = '<option>Đang tải...</option>';

  if (!khoa || !ngay || !gio) {
    selectBs.innerHTML = '<option>-- Chọn bác sĩ --</option>';
    return;
  }

  fetch('get_slots.php?khoa_id=' + encodeURIComponent(khoa) + '&ngay=' + encodeURIComponent(ngay) + '&gio=' + encodeURIComponent(gio))
    .then(r => r.json())
    .then(data => {
      selectBs.innerHTML = '<option value="">-- Chọn bác sĩ --</option>';
      if (data.length === 0) {
        const opt = document.createElement('option');
        opt.textContent = '❌ Không có bác sĩ nào rảnh giờ này';
        selectBs.appendChild(opt);
      } else {
        data.forEach(d => {
          const opt = document.createElement('option');
          opt.value = d.id;
          opt.textContent = d.ho_ten + ' (' + d.ten_chuyen_khoa + ')';
          selectBs.appendChild(opt);
        });
      }
    })
    .catch(() => {
      selectBs.innerHTML = '<option>⚠️ Lỗi tải danh sách bác sĩ</option>';
    });
}

// ✅ Lắng nghe sự kiện thay đổi
document.getElementById('chuyen_khoa').addEventListener('change', loadDoctors);
document.getElementById('ngay').addEventListener('change', loadDoctors);
document.getElementById('gio_ranh').addEventListener('change', loadDoctors);


// ✅ Khi thay đổi các trường thì gọi lại loadDoctors()
document.getElementById('chuyen_khoa').addEventListener('change', loadDoctors);
document.getElementById('ngay').addEventListener('change', loadDoctors);
document.getElementById('gio_ranh').addEventListener('change', loadDoctors);

// ✅ Load khung giờ khi chọn bác sĩ
document.getElementById('bac_si').addEventListener('change',function(){
  const ngay=document.getElementById('ngay').value;
  const bs_id=this.value;
  const selectKg=document.getElementById('khung_gio');
  selectKg.innerHTML='<option>Đang tải...</option>';
  if(!bs_id||!ngay){ selectKg.innerHTML='<option>-- Chọn khung giờ --</option>'; return; }
  fetch('get_slots.php?ngay='+encodeURIComponent(ngay)+'&bac_si_id='+bs_id)
  .then(r=>r.json())
  .then(data=>{
    selectKg.innerHTML='<option value="">-- Chọn khung giờ --</option>';
    data.forEach(d=>{
      const opt=document.createElement('option');
      opt.value=d.id;
      opt.textContent=d.gio_bat_dau+" - "+d.gio_ket_thuc;
      selectKg.appendChild(opt);
    });
  });
});

// ✅ Kiểm tra trùng giờ bằng AJAX khi chọn khung giờ
document.getElementById('khung_gio').addEventListener('change',function(){
  const slot_id=this.value;
  if(!slot_id) return;
  fetch('kiem_tra_trung_gio.php?slot_id='+slot_id)
  .then(r=>r.json())
  .then(res=>{
    const warning=document.getElementById('warning');
    const btn=document.getElementById('submitBtn');
    if(res.trung){
      warning.style.display='block';
      btn.disabled=true;
    }else{
      warning.style.display='none';
      btn.disabled=false;
    }
  });
});
</script>

<footer class="footer">
  <div class="footer-content">
    <p>&copy; 2025 Phòng Khám Đa Khoa. Bảo lưu mọi quyền.</p>
    <p>Địa chỉ: 98 Dương Quảng Hàm, Cầu Giấy, Hà Nội</p>
    <p>Điện thoại: 0985 467 888</p>
  </div>
</footer>
</body>
</html>

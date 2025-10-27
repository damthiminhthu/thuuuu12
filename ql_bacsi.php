<?php
include("../includes/connect.php");
include("../includes/header.php");

// ✅ Xử lý xóa bác sĩ + tài khoản liên quan
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);

    // Lấy ID tài khoản của bác sĩ này
    $tk_query = mysqli_query($conn, "SELECT tai_khoan_id FROM bac_si WHERE id = $id");
    if ($tk_query && mysqli_num_rows($tk_query) > 0) {
        $tk = mysqli_fetch_assoc($tk_query)['tai_khoan_id'];

        // Xóa bác sĩ
        mysqli_query($conn, "DELETE FROM bac_si WHERE id = $id");

        // Xóa tài khoản bác sĩ tương ứng (nếu có)
        if (!empty($tk)) {
            mysqli_query($conn, "DELETE FROM tai_khoan_bac_si WHERE id = $tk");
        }

        echo "<script>alert('🗑️ Xóa bác sĩ và tài khoản liên quan thành công!'); window.location='ql_bacsi.php';</script>";
        exit;
    }
}

// ✅ Lọc chuyên khoa
$filter = isset($_GET['chuyen_khoa_id']) ? intval($_GET['chuyen_khoa_id']) : 0;
$where = $filter > 0 ? "WHERE b.chuyen_khoa_id = $filter" : "";

// ✅ Lấy danh sách bác sĩ theo chuyên khoa
$result = mysqli_query($conn, "
    SELECT b.id, b.ho_ten, b.sdt, b.email, b.avatar, c.ten_chuyen_khoa 
    FROM bac_si b 
    LEFT JOIN chuyen_khoa c ON b.chuyen_khoa_id = c.id
    $where
    ORDER BY b.id DESC
");
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
  max-width: 1000px;
  background: #fff;
  border-radius: 16px;
  box-shadow: 0 4px 16px rgba(0,0,0,0.08);
  padding: 30px 40px;
  text-align: center;
  margin-left: 3cm;
}
h2 {
  color: #1565c0;
  font-weight: 700;
  margin-bottom: 25px;
}

/* Nút thêm */
.add-btn {
  background: #43a047;
  padding: 6px 15px;
  border-radius: 8px;
  color: #fff;
  text-decoration: none;
  font-weight: 600;
  margin-bottom: 16px;
  display: inline-block;
  box-shadow: 0 3px 6px rgba(0,0,0,0.1);
  transition: 0.25s;
  font-size: 15px;
}
.add-btn:hover { background: #2e7d32; text-decoration: none; }

/* Bộ lọc */
.filter-box {
  margin-bottom: 25px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  padding: 8px 0;
  margin-top: -10px; /* giảm khoảng trống thừa bên trên */
}
.filter-left, .filter-right {
  display: flex;
  align-items: center;
  gap: 12px;
}

.filter-box form {
  display: flex;
  gap: 10px;
  align-items: center;
}
select {
  padding: 8px 14px;
  border-radius: 8px;
  border: 1.5px solid #cfd8dc;
  font-size: 15px;
  background: #fafbfc;
  transition: 0.2s;
}
select:focus { border-color: #1976d2; outline: none; }

/* Bảng */
table {
  width: 100%;
  border-collapse: collapse;
  border-radius: 12px;
  overflow: hidden;
  font-size: 15px;
}
th, td {
  padding: 12px;
  text-align: center;
  border-bottom: 1px solid #e0e0e0;
}
th {
  background-color: #1565c0;
  color: white;
  font-weight: 600;
}
tr:hover { background-color: #f1f8ff; }

/* Ảnh bác sĩ */
img.avatar {
  width: 80px;
  height: 80px;
  border-radius: 50%;
  object-fit: cover;
  box-shadow: 0 2px 5px rgba(0,0,0,0.15);
  transition: transform 0.2s ease;
}
img.avatar:hover { transform: scale(1.15); }

/* Nút hành động */
.action-btn {
  border: none;
  color: #fff;
  padding: 8px 14px;
  border-radius: 8px;
  font-weight: 600;
  cursor: pointer;
  transition: 0.25s;
  box-shadow: 0 3px 6px rgba(0,0,0,0.1);
}
.btn-delete { background: #e53935; }
.btn-delete:hover { background: #c62828; }
</style>

<div class="container">
  <h2>Quản Lý Bác Sĩ</h2>

  <div class="filter-box">
    <a href="them_bacsi.php" class="add-btn">Thêm bác sĩ mới</a>
    <form method="GET">
      <label for="chuyen_khoa_id"><b>Lọc theo chuyên khoa:</b></label>
      <select name="chuyen_khoa_id" id="chuyen_khoa_id" onchange="this.form.submit()">
        <option value="0">-- Tất cả chuyên khoa --</option>
        <?php
        $ck = mysqli_query($conn, "SELECT id, ten_chuyen_khoa FROM chuyen_khoa ORDER BY ten_chuyen_khoa ASC");
        while ($row = mysqli_fetch_assoc($ck)) {
            $selected = ($row['id'] == $filter) ? "selected" : "";
            echo "<option value='{$row['id']}' $selected>{$row['ten_chuyen_khoa']}</option>";
        }
        ?>
      </select>
    </form>
  </div>

  <table>
    <tr>
      <th>ID</th>
      <th>Ảnh</th>
      <th>Họ tên</th>
      <th>Chuyên khoa</th>
      <th>SĐT</th>
      <th>Email</th>
      <th>Hành động</th>
    </tr>

    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
      <tr>
        <td><?= $row['id'] ?></td>
        <td>
          <?php if (!empty($row['avatar'])): ?>
            <img src="<?= htmlspecialchars($row['avatar']) ?>" class="avatar" alt="Ảnh bác sĩ">
          <?php else: ?>
            <span>Không có ảnh</span>
          <?php endif; ?>
        </td>
        <td><?= htmlspecialchars($row['ho_ten']) ?></td>
        <td><?= htmlspecialchars($row['ten_chuyen_khoa'] ?? '—') ?></td>
        <td><?= htmlspecialchars($row['sdt']) ?></td>
        <td><?= htmlspecialchars($row['email']) ?></td>
        <td>
          <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa bác sĩ này không?');">
            <button class="action-btn btn-delete">Xóa</button>
          </a>
        </td>
      </tr>
    <?php } ?>
  </table>
</div>

<?php include("../includes/footer.php"); ?>

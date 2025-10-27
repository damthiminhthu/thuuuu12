<?php
include("../includes/connect.php");
include("../includes/header.php");

// ✅ Cập nhật tin tức
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $tieu_de = trim($_POST['tieu_de']);
    $noi_dung = trim($_POST['noi_dung']);
    $anh = filter_var(trim($_POST['anh']), FILTER_SANITIZE_URL);
    mysqli_query($conn, "UPDATE tin_tuc SET tieu_de='$tieu_de', noi_dung='$noi_dung', anh='$anh' WHERE id='$id'");
    echo "<script>alert('✅ Cập nhật thành công!'); window.location='ql_tintuc.php';</script>";
    exit;
}

// ✅ Xóa tin tức
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM tin_tuc WHERE id=$id");
    echo "<script>alert('🗑️ Đã xóa tin tức!'); window.location='ql_tintuc.php';</script>";
    exit;
}

$result = mysqli_query($conn, "SELECT * FROM tin_tuc ORDER BY id DESC");
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
  max-width: 900px;
  margin: 40px auto;
  background: #fff;
  border-radius: 16px;
  box-shadow: 0 4px 16px rgba(0,0,0,0.08);
  padding: 30px 40px;
  text-align: center;
  margin-left: 3.5cm;
}
h2 {
  color: #1565c0;
  font-weight: 700;
  margin-bottom: 25px;
}
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
  margin-right: 17.75cm;
  font-size: 15px;
}
.add-btn:hover { background: #2e7d32; }

table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 10px;
}
th, td {
  padding: 12px;
  text-align: center;
  border-bottom: 1px solid #e0e0e0;
  vertical-align: middle;
}
th {
  background-color: #1565c0;
  color: white;
}
tr:hover { background-color: #f1f8ff; }

img.preview {
  width: 100px;
  height: 70px;
  object-fit: cover;
  border-radius: 6px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.15);
}

.btn {
  border: none;
  border-radius: 8px;
  padding: 8px 18px;
  color: #fff;
  cursor: pointer;
  font-size: 14px;
  font-weight: 500;
  transition: 0.25s;
  box-shadow: 0 3px 6px rgba(0,0,0,0.1);
  text-decoration: none;
  display: inline-block;
}
.btn-edit { background: #1e88e5; }
.btn-edit:hover { background: #1565c0; }
.btn-delete { background: #e53935; }
.btn-delete:hover { background: #b71c1c; }
.btn-save { background: #43a047; }
.btn-save:hover { background: #2e7d32; }
.btn-cancel { background: #757575; }
.btn-cancel:hover { background: #616161; }

.action-group {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 10px;
}

input[type="text"], textarea {
  width: 95%;
  padding: 6px 8px;
  border: 1.5px solid #cfd8dc;
  border-radius: 6px;
  font-size: 14px;
  background: #fafbfc;
  transition: 0.2s;
}
textarea { resize: none; height: 60px; }
input:focus, textarea:focus {
  border-color: #1976d2;
  background: #fff;
  outline: none;
}
</style>

<div class="container">
  <h2>Quản Lý Tin Tức</h2>
  <a href="add_tintuc.php" class="add-btn">Thêm tin tức mới</a>

  <table>
    <tr>
      <th>ID</th>
      <th>Tiêu đề</th>
      <th>Nội dung</th>
      <th>Ngày đăng</th>
      <th>Hình ảnh</th>
      <th>Hành động</th>
    </tr>

    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
      <tr id="row_<?= $row['id'] ?>">
        <form method="POST">
          <input type="hidden" name="id" value="<?= $row['id'] ?>">

          <td><?= $row['id'] ?></td>

          <td>
            <div class="view-mode"><?= htmlspecialchars($row['tieu_de']) ?></div>
            <input type="text" name="tieu_de" class="edit-mode" value="<?= htmlspecialchars($row['tieu_de']) ?>" style="display:none;">
          </td>

          <td>
            <div class="view-mode"><?= nl2br(htmlspecialchars($row['noi_dung'])) ?></div>
            <textarea name="noi_dung" class="edit-mode" style="display:none;"><?= htmlspecialchars($row['noi_dung']) ?></textarea>
          </td>

          <td><?= $row['ngay_dang'] ?></td>

          <td>
            <div class="view-mode">
              <?php if (!empty($row['anh'])): ?>
                <img src="<?= htmlspecialchars(trim($row['anh'])) ?>" class="preview">
              <?php else: ?>
                <span>Không có ảnh</span>
              <?php endif; ?>
            </div>
            <input type="text" name="anh" class="edit-mode" value="<?= htmlspecialchars($row['anh']) ?>" style="display:none;">
          </td>

          <td>
            <div class="action-group view-mode">
              <button type="button" class="btn btn-edit" onclick="editRow(<?= $row['id'] ?>)">Sửa</button>
              <a href="?delete=<?= $row['id'] ?>" class="btn btn-delete" onclick="return confirm('Xóa tin tức này?');">Xóa</a>
            </div>

            <div class="action-group edit-mode" style="display:none;">
              <button type="submit" name="update" class="btn btn-save">💾 Lưu</button>
              <button type="button" class="btn btn-cancel" onclick="cancelEdit(<?= $row['id'] ?>)">❌ Hủy</button>
            </div>
          </td>
        </form>
      </tr>
    <?php } ?>
  </table>
</div>

<script>
function editRow(id) {
  const row = document.getElementById('row_' + id);
  row.querySelectorAll('.view-mode').forEach(e => e.style.display = 'none');
  row.querySelectorAll('.edit-mode').forEach(e => e.style.display = 'flex');
}

function cancelEdit(id) {
  const row = document.getElementById('row_' + id);
  row.querySelectorAll('.view-mode').forEach(e => e.style.display = 'flex');
  row.querySelectorAll('.edit-mode').forEach(e => e.style.display = 'none');
}
</script>

<?php include("../includes/footer.php"); ?>

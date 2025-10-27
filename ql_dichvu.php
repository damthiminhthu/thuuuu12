<?php
include("../includes/header.php");
include("../includes/connect.php");

// ===== CẬP NHẬT ẢNH & TRẠNG THÁI =====
if (isset($_POST['save'])) {
    $id = intval($_POST['id']);
    $trang_thai = $_POST['trang_thai'];
    $hinh_anh = trim($_POST['hinh_anh'] ?? '');
    $conn->query("UPDATE dich_vu SET trang_thai='$trang_thai', hinh_anh='$hinh_anh' WHERE id=$id");
    header("Location: ql_dichvu.php");
    exit;
}

// ===== XÓA DỊCH VỤ =====
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM dich_vu WHERE id=$id");
    header("Location: ql_dichvu.php");
    exit;
}

// ===== LẤY DỮ LIỆU =====
$sql = "SELECT * FROM dich_vu ORDER BY id DESC";
$res = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Quản lý Dịch vụ</title>
<style>
body {
  font-family: "Segoe UI", Arial, sans-serif;
  background: #e3f2fd;
  margin: 0;
  padding: 0;
}
.main-content {
  margin-left: 100px;
  padding: 30px 40px;
}
.content-box {
  max-width: 1100px;
  margin: 30px auto;
  background: #ffffff;
  border-radius: 20px;
  box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
  padding: 25px 35px;
}
h2 {
  text-align: center;
  color: #1565c0;
  font-weight: 700;
  margin-bottom: 25px;
}
table {
  width: 100%;
  border-collapse: collapse;
  border-radius: 12px;
  overflow: hidden;
  font-size: 15px;
  table-layout: fixed;
  word-wrap: break-word;
}
thead {
  background: #1976d2;
  color: white;
}
th, td {
  padding: 11px 12px;
  text-align: center;
  border-bottom: 1px solid #e0e0e0;
  vertical-align: middle;
}
tbody tr:nth-child(even) {
  background: #f9fbff;
}
tbody tr:hover {
  background: #e3f2fd;
  transition: 0.2s;
}
.service-img {
  width: 70px;
  height: 70px;
  object-fit: cover;
  border-radius: 8px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.1);
  display: block;
  margin: 5px auto 0;
}
.image-link {
  display: block;
  word-break: break-all;
  max-width: 140px;
  margin: 0 auto;
  font-size: 13px;
  color: #1565c0;
  text-decoration: none;
}
.image-link:hover {
  text-decoration: underline;
}
.action-buttons {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 6px;
}
.btn {
  border: none;
  border-radius: 8px;
  color: #fff;
  padding: 6px 14px;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  transition: 0.25s;
  text-decoration: none;
  box-shadow: 0 2px 4px rgba(0,0,0,0.15);
}
.btn-add {
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
.btn-add:hover { background: #2e7d32; }
.btn-edit { background: #1976d2; }
.btn-edit:hover { background: #0d47a1; }
.btn-delete { background: #f44336; }
.btn-delete:hover { background: #c62828; }
.btn-save { background: #4caf50; }
.btn-save:hover { background: #388e3c; }
.btn-cancel { background: #9e9e9e; }
.btn-cancel:hover { background: #616161; }
select {
  padding: 6px 10px;
  border-radius: 6px;
  border: 1px solid #ccc;
  font-weight: 600;
  color: #1565c0;
  background: #fff;
}
.edit-input {
  width: 90%;
  padding: 6px;
  border: 1px solid #ccc;
  border-radius: 6px;
  text-align: center;
  font-size: 13px;
}
.preview-img {
  width: 65px;
  height: 65px;
  margin-top: 5px;
  border-radius: 8px;
  display: block;
  margin-left: auto;
  margin-right: auto;
}
</style>
</head>

<body>
<div class="main-content">
  <div class="content-box">
    <h2>Quản lý Dịch vụ</h2>
    <a href="them_dichvu.php" class="btn-add">Thêm dịch vụ</a>

    <table>
      <thead>
        <tr>
          <th style="width:5%;">ID</th>
          <th style="width:15%;">Tên dịch vụ</th>
          <th style="width:25%;">Mô tả</th>
          <th style="width:15%;">Hình ảnh</th>
          <th style="width:10%;">Trạng thái</th>
          <th style="width:10%;">Ngày tạo</th>
          <th style="width:10%;">Ngày cập nhật</th>
          <th style="width:15%;">Hành động</th>
        </tr>
      </thead>
      <tbody>
      <?php
      if ($res && $res->num_rows > 0) {
          while ($r = $res->fetch_assoc()) {
              $id = $r['id'];
              $editing = (isset($_GET['edit']) && $_GET['edit'] == $id);

              // ✅ Hiển thị link ảnh gọn + preview
              if (!empty($r['hinh_anh'])) {
                  $link = htmlspecialchars($r['hinh_anh']);
                  if (str_starts_with($r['hinh_anh'], 'data:image')) {
                      $displayLink = '(Ảnh Base64)';
                  } else {
                      $displayLink = strlen($r['hinh_anh']) > 40 
                          ? htmlspecialchars(substr($r['hinh_anh'], 0, 37)) . '...'
                          : htmlspecialchars($r['hinh_anh']);
                  }

                  $img_html = "
                      <a href='{$link}' target='_blank' class='image-link'>{$displayLink}</a>
                      <img src='{$link}' alt='Ảnh dịch vụ' class='service-img'>
                  ";
              } else {
                  $img_html = "<span style='color:#999;'>Chưa có ảnh</span>";
              }

              echo "<tr>
                      <td>{$r['id']}</td>
                      <td>{$r['ten_dich_vu']}</td>
                      <td>{$r['mo_ta']}</td>";

              // Nếu đang sửa
              if ($editing) {
                  $selHien = $r['trang_thai'] == 'Hiển thị' ? 'selected' : '';
                  $selAn = $r['trang_thai'] == 'Ẩn' ? 'selected' : '';

                  echo "<td>
                          <form method='POST' style='margin:0;'>
                            <input type='hidden' name='id' value='{$id}'>
                            <input type='text' name='hinh_anh' value='" . htmlspecialchars($r['hinh_anh']) . "' class='edit-input' oninput='previewEditImage(this, {$id})'>
                            <img id='preview{$id}' src='" . htmlspecialchars($r['hinh_anh']) . "' alt='Xem trước ảnh' class='preview-img'>
                        </td>
                        <td>
                            <select name='trang_thai'>
                              <option value='Hiển thị' $selHien>Hiển thị</option>
                              <option value='Ẩn' $selAn>Ẩn</option>
                            </select>
                        </td>
                        <td>{$r['ngay_tao']}</td>
                        <td>{$r['ngay_cap_nhat']}</td>
                        <td class='action-buttons'>
                            <button type='submit' name='save' class='btn btn-save'>Lưu</button>
                            <a href='ql_dichvu.php' class='btn btn-cancel'>Hủy</a>
                          </form>
                        </td>";
              } else {
                  echo "
                        <td>$img_html</td>
                        <td><span style='font-weight:600; color:" . ($r['trang_thai']=='Hiển thị' ? '#2e7d32' : '#c62828') . ";'>{$r['trang_thai']}</span></td>
                        <td>{$r['ngay_tao']}</td>
                        <td>{$r['ngay_cap_nhat']}</td>
                        <td class='action-buttons'>
                          <a href='?edit={$id}' class='btn btn-edit'>Sửa</a>
                          <a href='?delete={$id}' onclick=\"return confirm('Xóa dịch vụ này?');\" class='btn btn-delete'>Xóa</a>
                        </td>";
              }

              echo "</tr>";
          }
      } else {
          echo "<tr><td colspan='8'>Không có dịch vụ nào.</td></tr>";
      }
      ?>
      </tbody>
    </table>
  </div>
</div>

<script>
function previewEditImage(input, id) {
  const link = input.value.trim();
  const img = document.getElementById('preview' + id);
  if (link) {
    img.src = link;
    img.style.display = 'block';
  } else {
    img.style.display = 'none';
  }
}
</script>

</body>
</html>

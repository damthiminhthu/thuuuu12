<?php
include("../includes/connect.php");
include("../includes/header.php");

// Cập nhật chuyên khoa
if(isset($_POST['update'])){
    $id = $_POST['id'];
    $ten_chuyen_khoa = $_POST['ten_chuyen_khoa'];
    $mo_ta = $_POST['mo_ta'];
    $anh_dai_dien = $_POST['anh_dai_dien'];

    $sql_update = "UPDATE chuyen_khoa 
                   SET ten_chuyen_khoa='$ten_chuyen_khoa', mo_ta='$mo_ta', anh_dai_dien='$anh_dai_dien'
                   WHERE id=$id";
    mysqli_query($conn, $sql_update);
    echo "<script>window.location='ql_chuyenkhoa.php';</script>";
}

// Xóa chuyên khoa
if(isset($_POST['delete'])){
    $id = $_POST['id'];
    $sql_delete = "DELETE FROM chuyen_khoa WHERE id=$id";
    mysqli_query($conn, $sql_delete);
    echo "<script>window.location='ql_chuyenkhoa.php';</script>";
}
?>

<style>
body {
  font-family: "Segoe UI", Arial, sans-serif;
  background: #f4f8ff;
  margin: 0;
  padding: 0;
}
.main-content {
  margin-left: 270px;
  padding: 40px 50px;
}
.container {
  max-width: 1100px;
  margin: 30px auto;
  background: #ffffff;
  border-radius: 16px;
  box-shadow: 0 4px 14px rgba(0, 0, 0, 0.08);
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
}
thead {
  background: #1976d2;
  color: white;
}
th, td {
  padding: 11px 12px;
  text-align: center;
  border-bottom: 1px solid #e0e0e0;
}
tbody tr:nth-child(even) {
  background: #f9fbff;
}
tbody tr:hover {
  background: #e3f2fd;
  transition: 0.2s;
}

/* ====== Nút hành động ====== */
.action-buttons {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 6px;
}
.btn {
  border: none;
  border-radius: 6px;
  color: #fff;
  padding: 5px 12px;
  font-size: 13px;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s ease-in-out;
  min-width: 58px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
.btn-edit { background-color: #1976d2; }
.btn-edit:hover { background-color: #0d47a1; }
.btn-delete { background-color: #e53935; }
.btn-delete:hover { background-color: #c62828; }
.btn-save { background-color: #43a047; }
.btn-save:hover { background-color: #2e7d32; }
.btn-cancel { background-color: #757575; }
.btn-cancel:hover { background-color: #616161; }
.btn-add {
  display: inline-block;
  text-decoration: none;
  background: #43a047;
  padding: 7px 15px;
  color: #fff;
  border-radius: 6px;
  font-size: 14px;
  font-weight: 600;
  transition: 0.25s;
}
.btn-add:hover { background: #2e7d32; }
.btn:active { transform: translateY(1px); opacity: 0.9; }

@media (max-width: 768px) {
  .main-content { margin-left: 0; padding: 20px; }
  table { font-size: 13px; }
  .btn { padding: 4px 8px; }
}
</style>

<div class="container">
  <h2>Quản lý Chuyên khoa</h2>
  <a href="ql_chuyenkhoa_add.php" class="btn-add">Thêm chuyên khoa</a>
  <br><br>

  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Tên chuyên khoa</th>
        <th>Mô tả</th>
        <th>Ảnh đại diện</th>
        <th>Hành động</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $sql = "SELECT * FROM chuyen_khoa ORDER BY id DESC";
      $result = mysqli_query($conn, $sql);
      if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_assoc($result)){
          echo "<tr data-id='".$row['id']."'>
            <td>".$row['id']."</td>
            <td class='field' data-field='ten_chuyen_khoa'>".$row['ten_chuyen_khoa']."</td>
            <td class='field' data-field='mo_ta'>".$row['mo_ta']."</td>
            <td class='field' data-field='anh_dai_dien'>".$row['anh_dai_dien']."</td>
            <td>
              <div class='action-buttons'>
                <button class='btn btn-edit' onclick='editRow(this)'>Sửa</button>
                <form method='post' style='display:inline-block;' onsubmit=\"return confirm('Bạn có chắc muốn xóa chuyên khoa này?');\">
                  <input type='hidden' name='id' value='".$row['id']."'>
                  <button type='submit' name='delete' class='btn btn-delete'>Xóa</button>
                </form>
              </div>
            </td>
          </tr>";
        }
      } else {
        echo "<tr><td colspan='5'>Không có dữ liệu</td></tr>";
      }
      ?>
    </tbody>
  </table>
</div>

<script>
function editRow(btn){
  const tr = btn.closest('tr');
  const fields = tr.querySelectorAll('.field');
  fields.forEach(cell => {
    cell.contentEditable = "true";
    cell.style.background = "#f2f2f2";
  });
  btn.parentElement.innerHTML = `
    <div class="action-buttons">
      <button class="btn btn-save" onclick="saveRow(this)">Lưu</button>
      <button class="btn btn-cancel" onclick="cancelEdit()">Hủy</button>
    </div>
  `;
}
function cancelEdit(){ window.location.reload(); }
function saveRow(btn){
  const tr = btn.closest('tr');
  const form = document.createElement('form');
  form.method = 'post';
  form.innerHTML = `<input type='hidden' name='id' value='${tr.dataset.id}'><input type='hidden' name='update' value='1'>`;
  tr.querySelectorAll('.field').forEach(cell => {
    form.innerHTML += `<input type="hidden" name="${cell.dataset.field}" value="${cell.innerText}">`;
  });
  document.body.appendChild(form);
  form.submit();
}
</script>

<?php include("../includes/footer.php"); ?>

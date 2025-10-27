<?php
include("../includes/connect.php");
include("../includes/header.php");

// Cập nhật
if(isset($_POST['update'])){
    $id = $_POST['id'];
    $ho_ten = $_POST['ho_ten'];
    $gioi_tinh = $_POST['gioi_tinh'];
    $ngay_sinh = $_POST['ngay_sinh'];
    $sdt = $_POST['sdt'];
    $gmail_bn = $_POST['gmail_bn'];
    $dia_chi = $_POST['dia_chi'];

    $sql_update = "UPDATE benh_nhan 
                   SET ho_ten='$ho_ten', gioi_tinh='$gioi_tinh', ngay_sinh='$ngay_sinh',
                       sdt='$sdt', gmail_bn='$gmail_bn', dia_chi='$dia_chi'
                   WHERE id=$id";
    mysqli_query($conn, $sql_update);
    echo "<script>window.location='ql_benhnhan.php';</script>";
}

// Xóa
if(isset($_POST['delete'])){
    $id = $_POST['id'];
    $sql_delete = "DELETE FROM benh_nhan WHERE id=$id";
    mysqli_query($conn, $sql_delete);
    echo "<script>window.location='ql_benhnhan.php';</script>";
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
  margin-left: 260px;
  padding: 30px 40px;
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

/* Màu nút */
.btn-edit {
  background-color: #1976d2;
}
.btn-edit:hover {
  background-color: #0d47a1;
}
.btn-delete {
  background-color: #e53935;
}
.btn-delete:hover {
  background-color: #c62828;
}
.btn-save {
  background-color: #43a047;
}
.btn-save:hover {
  background-color: #2e7d32;
}
.btn-cancel {
  background-color: #757575;
}
.btn-cancel:hover {
  background-color: #616161;
}

/* Active */
.btn:active {
  transform: translateY(1px);
  opacity: 0.9;
}

/* Responsive */
@media (max-width: 768px) {
  .main-content { margin-left: 0; padding: 20px; }
  table { font-size: 13px; }
  .btn { padding: 4px 8px; }
}
</style>

<div class="container">
  <h2>Quản lý Bệnh nhân</h2>
  <a href="ql_benhnhan_add.php" class="btn btn-save" style="text-decoration:none;">+ Thêm bệnh nhân</a>
  <br><br>

  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Họ tên</th>
        <th>Giới tính</th>
        <th>Ngày sinh</th>
        <th>SĐT</th>
        <th>Email</th>
        <th>Địa chỉ</th>
        <th>Hành động</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $sql = "SELECT * FROM benh_nhan ORDER BY id DESC";
      $result = mysqli_query($conn, $sql);
      if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_assoc($result)){
          echo "<tr data-id='".$row['id']."'>
            <td>".$row['id']."</td>
            <td class='field' data-field='ho_ten'>".$row['ho_ten']."</td>
            <td class='field' data-field='gioi_tinh'>".$row['gioi_tinh']."</td>
            <td class='field' data-field='ngay_sinh'>".$row['ngay_sinh']."</td>
            <td class='field' data-field='sdt'>".$row['sdt']."</td>
            <td class='field' data-field='gmail_bn'>".$row['gmail_bn']."</td>
            <td class='field' data-field='dia_chi'>".$row['dia_chi']."</td>
            <td>
              <div class='action-buttons'>
                <button class='btn btn-edit' onclick='editRow(this)'>Sửa</button>
                <form method='post' style='display:inline-block;' onsubmit=\"return confirm('Bạn có chắc muốn xóa?');\">
                  <input type='hidden' name='id' value='".$row['id']."'>
                  <button type='submit' name='delete' class='btn btn-delete'>Xóa</button>
                </form>
              </div>
            </td>
          </tr>";
        }
      } else {
        echo "<tr><td colspan='8'>Không có dữ liệu</td></tr>";
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

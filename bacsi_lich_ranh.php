<?php
include("bacsi_header.php");
include("../includes/connect.php");

if (!isset($_SESSION['bac_si_id'])) {
    header("Location: ../admin/login.php");
    exit;
}

$bac_si_id = $_SESSION['bac_si_id'];

// 🧹 Xóa lịch rảnh đã quá hạn
$conn->query("DELETE FROM lich_ranh WHERE ngay < CURDATE()");

$sql = "
SELECT * FROM lich_ranh
WHERE bac_si_id = ?
  AND ngay >= CURDATE()
  AND trang_thai = 'Trống'
ORDER BY ngay ASC, gio_bat_dau ASC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $bac_si_id);
$stmt->execute();
$res = $stmt->get_result();
?>


<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>🕒 Lịch rảnh của tôi</title>
<link rel="stylesheet" href="../css/bacsi_style.css">
</head>

<body>
<div class="container">
  <h2>🕒 Lịch rảnh của tôi</h2>

  <table class="table-common">
    <thead>
      <tr>
        <th>Ngày</th>
        <th>Giờ bắt đầu</th>
        <th>Giờ kết thúc</th>
        <th>Trạng thái</th>
        <th>Thao tác</th>
      </tr>
    </thead>
    <tbody>
    <?php
    if ($res->num_rows === 0) {
        echo "<tr><td colspan='5' style='text-align:center; padding:15px;'>Chưa có lịch rảnh nào.</td></tr>";
    } else {
        while ($r = $res->fetch_assoc()) {
            echo "<tr>
                    <td>{$r['ngay']}</td>
                    <td>{$r['gio_bat_dau']}</td>
                    <td>{$r['gio_ket_thuc']}</td>
                    <td>{$r['trang_thai']}</td>
                    <td>
                      <a href='bacsi_sua_lich.php?id={$r['id']}' class='btn-sua'>Sửa</a>
                      <a href='bacsi_xoa_lich.php?id={$r['id']}' class='btn-huy'>Xóa</a>
                    </td>
                  </tr>";
        }
    }
    ?>
    </tbody>
  </table>
</div>
<?php include("bacsi_footer.php"); ?>
</body>
</html>

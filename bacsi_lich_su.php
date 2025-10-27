<?php
include("bacsi_header.php");
include("../includes/connect.php");

$bac_si_id = $_SESSION['bac_si_id'];

$sql = "
SELECT ls.*, bn.ho_ten, bn.gmail_bn
FROM dat_lich ls
JOIN benh_nhan bn ON ls.benh_nhan_id = bn.id
WHERE ls.bac_si_id = ? AND ls.trang_thai = 'Đã khám'
ORDER BY ls.ngay_kham DESC
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $bac_si_id);
$stmt->execute();
$res = $stmt->get_result();
?>

<head>
<meta charset="UTF-8">
<title>Lịch sử khám bệnh</title>
<link rel="stylesheet" href="../css/bacsi_style.css">
</head>

<div class="container">
  <h2>📜 Lịch sử khám bệnh</h2>
  <table class="table-common">
    <tr>
      <th>Bệnh nhân</th>
      <th>Email</th>
      <th>Ngày đặt</th>
      <th>Ngày khám</th>
      <th>Bắt đầu</th>
      <th>Kết thúc</th>
      <th>Triệu chứng</th>
      <th>Ghi chú</th>
    </tr>
    <?php
      if ($res->num_rows === 0) {
          echo "<tr><td colspan='8' style='text-align:center;'>Chưa có lịch sử khám nào.</td></tr>";
      } else {
          while ($r = $res->fetch_assoc()) {
              echo "<tr>
                      <td>{$r['ho_ten']}</td>
                      <td>{$r['gmail_bn']}</td>
                      <td>{$r['ngay_dat']}</td>
                      <td>{$r['ngay_kham']}</td>
                      <td>{$r['gio_bat_dau']}</td>
                      <td>{$r['gio_ket_thuc']}</td>
                      <td>{$r['trieu_chung']}</td>
                      <td>{$r['ghi_chu']}</td>
                    </tr>";
          }
      }
    ?>
  </table>
</div>

<?php include("bacsi_footer.php"); ?>

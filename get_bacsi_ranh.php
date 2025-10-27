<?php
include("../includes/connect.php");

$khoa_id = $_GET['khoa_id'];
$ngay = $_GET['ngay'];

$sql = "
SELECT DISTINCT bs.id, bs.ho_ten 
FROM bac_si bs
JOIN lich_ranh lr ON lr.bac_si_id = bs.id
WHERE bs.chuyen_khoa_id = '$khoa_id'
AND lr.ngay = '$ngay'
AND lr.trang_thai = 'Trống'
ORDER BY bs.ho_ten ASC";

$res = mysqli_query($conn, $sql);
echo '<option value="">-- Chọn bác sĩ --</option>';
while($r = mysqli_fetch_assoc($res)){
  echo "<option value='{$r['id']}'>{$r['ho_ten']}</option>";
}
?>

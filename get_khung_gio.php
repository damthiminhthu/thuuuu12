<?php
include("../includes/connect.php");

$bac_si_id = $_GET['bac_si_id'];
$ngay = $_GET['ngay'];

$sql = "SELECT gio_bat_dau, gio_ket_thuc FROM lich_ranh 
        WHERE bac_si_id='$bac_si_id' 
        AND ngay='$ngay' 
        AND trang_thai='Trống'
        ORDER BY gio_bat_dau ASC";

$res = mysqli_query($conn, $sql);
$data = [];
while($r = mysqli_fetch_assoc($res)){
  $data[] = $r;
}
echo json_encode($data);
?>

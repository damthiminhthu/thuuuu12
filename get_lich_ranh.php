<?php
include("../includes/connect.php");
header('Content-Type: application/json');

if (isset($_GET['bac_si_id']) && isset($_GET['ngay'])) {
    $bs = intval($_GET['bac_si_id']);
    $ngay = $_GET['ngay'];
    $rs = mysqli_query($conn, "SELECT gio_bat_dau, gio_ket_thuc 
                               FROM lich_ranh 
                               WHERE bac_si_id=$bs AND ngay='$ngay' AND trang_thai='Trống'");
    $arr = [];
    while ($r = mysqli_fetch_assoc($rs)) {
        $arr[] = $r;
    }
    echo json_encode($arr);
}
?>

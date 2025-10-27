<?php
include('../includes/connect.php'); 
include("sidebar.php");

// Lấy ngày hôm nay (định dạng YYYY-MM-DD)
$today = date("Y-m-d");

// Truy vấn chỉ lấy lịch hẹn của ngày hôm nay
$sql = "SELECT lh.id, bn.ho_ten AS ten_benh_nhan, bs.ho_ten AS ten_bac_si, lh.thoi_gian, lh.ly_do, lh.trang_thai
        FROM lich_hen lh
        JOIN benh_nhan bn ON lh.benh_nhan_id = bn.id
        JOIN bac_si bs ON lh.bac_si_id = bs.id
        WHERE DATE(lh.thoi_gian) = '$today'
        ORDER BY lh.thoi_gian ASC";

$result = mysqli_query($conn, $sql);
?>

<div class="main-content">
    <h1 style="text-align:center; margin-bottom:20px;">
        📅 Lịch hẹn hôm nay (<?php echo date("d/m/Y"); ?>)
    </h1>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Bệnh nhân</th>
                <th>Bác sĩ</th>
                <th>Thời gian</th>
                <th>Lý do</th>
                <th>Trạng thái</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['ten_benh_nhan']; ?></td>
                    <td><?php echo $row['ten_bac_si']; ?></td>
                    <td><?php echo date("H:i", strtotime($row['thoi_gian'])); ?></td>
                    <td><?php echo $row['ly_do']; ?></td>
                    <td><?php echo $row['trang_thai']; ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php
include("../includes/connect.php");
header('Content-Type: application/json; charset=UTF-8');

// Nếu truyền bac_si_id → load khung giờ rảnh của bác sĩ
if (isset($_GET['bac_si_id']) && isset($_GET['ngay'])) {
    $bac_si_id = intval($_GET['bac_si_id']);
    $ngay = $_GET['ngay'];

    $sql = "
        SELECT id, gio_bat_dau, gio_ket_thuc
        FROM lich_ranh
        WHERE bac_si_id = $bac_si_id 
          AND ngay = '$ngay'
          AND LOWER(trang_thai) = 'trống'
        ORDER BY gio_bat_dau ASC
    ";

    $result = $conn->query($sql);
    $slots = [];

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $slots[] = [
                'id' => $row['id'],
                'gio_bat_dau' => substr($row['gio_bat_dau'], 0, 5),
                'gio_ket_thuc' => substr($row['gio_ket_thuc'], 0, 5)
            ];
        }
    }

    echo json_encode($slots);
    exit;
}

// Nếu truyền khoa_id + ngay + gio → lọc bác sĩ có giờ rảnh trùng
if (isset($_GET['khoa_id']) && isset($_GET['ngay']) && isset($_GET['gio'])) {
    $khoa_id = intval($_GET['khoa_id']);
    $ngay = $_GET['ngay'];
    $gio = $_GET['gio'];

    if (strlen($gio) === 5) $gio .= ':00';

    $sql = "
        SELECT DISTINCT b.id, b.ho_ten, c.ten_chuyen_khoa
        FROM bac_si b
        JOIN lich_ranh l ON b.id = l.bac_si_id
        JOIN chuyen_khoa c ON c.id = b.chuyen_khoa_id
        WHERE b.chuyen_khoa_id = $khoa_id
          AND l.ngay = '$ngay'
          AND l.gio_bat_dau = '$gio'
          AND LOWER(l.trang_thai) = 'Trống'
        ORDER BY b.ho_ten ASC
    ";

    $res = $conn->query($sql);
    $data = [];
    if ($res && $res->num_rows > 0) {
        while ($r = $res->fetch_assoc()) {
            $data[] = $r;
        }
    }

    echo json_encode($data);
    exit;
}

echo json_encode([]);
?>

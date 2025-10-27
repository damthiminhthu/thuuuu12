<?php
include_once '../includes/connect.php';

// Nhận ID chuyên khoa (có thể là khoa_id hoặc khoa)
$khoa_id = $_GET['khoa_id'] ?? ($_GET['khoa'] ?? 'all');

// Nếu chọn "Tất cả bác sĩ"
if ($khoa_id === 'all' || $khoa_id == 0) {
    $sql = "
        SELECT 
            b.id, 
            b.ho_ten, 
            b.email, 
            b.sdt, 
            b.avatar, 
            c.ten_chuyen_khoa
        FROM bac_si b
        LEFT JOIN chuyen_khoa c ON b.chuyen_khoa_id = c.id
        ORDER BY c.ten_chuyen_khoa ASC, b.ho_ten ASC
    ";
    $result = $conn->query($sql);
} 
// Nếu chọn chuyên khoa cụ thể
else {
    $stmt = $conn->prepare("
        SELECT 
            b.id, 
            b.ho_ten, 
            b.email, 
            b.sdt, 
            b.avatar, 
            c.ten_chuyen_khoa
        FROM bac_si b
        INNER JOIN chuyen_khoa c ON b.chuyen_khoa_id = c.id
        WHERE c.id = ?
        ORDER BY b.ho_ten ASC
    ");
    $stmt->bind_param("i", $khoa_id);
    $stmt->execute();
    $result = $stmt->get_result();
}

// Trả kết quả HTML
if ($result->num_rows === 0) {
    echo '<p style="text-align:center;color:#777;">Không có bác sĩ nào trong chuyên khoa này.</p>';
} else {
    echo '<div class="doctor-list">';
    while ($row = $result->fetch_assoc()) {
        $img = htmlspecialchars($row['avatar'] ?: 'https://cdn-icons-png.flaticon.com/512/3135/3135715.png');
        echo '
        <div class="doctor-card">
            <div class="doctor-img">
                <img src="'.$img.'" alt="'.htmlspecialchars($row['ho_ten']).'">
            </div>
            <h4>'.htmlspecialchars($row['ho_ten']).'</h4>
            <p class="chuyenkhoa">'.htmlspecialchars($row['ten_chuyen_khoa']).'</p>
            <p><strong>SDT:</strong> '.htmlspecialchars($row['sdt']).'</p>
            <p><strong>Email:</strong> '.htmlspecialchars($row['email']).'</p>
            <a href="booking.php?bacsi='.urlencode($row['id']).'" class="btn-book">Đặt lịch ngay</a>
        </div>';
    }
    echo '</div>';
}

// Dọn dẹp
if (isset($stmt)) $stmt->close();
?>

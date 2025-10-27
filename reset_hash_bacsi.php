<?php
require_once '../includes/connect.php';
$conn->set_charset('utf8mb4');

$default_pw = '123456';
$count = 0;

$sql = "SELECT id FROM tai_khoan_bac_si";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $id = $row['id'];
        $hash = password_hash($default_pw, PASSWORD_BCRYPT);

        $stmt = $conn->prepare("UPDATE tai_khoan_bac_si SET mat_khau = ? WHERE id = ?");
        $stmt->bind_param("si", $hash, $id);
        $stmt->execute();
        $stmt->close();

        $count++;
    }
    echo "<p style='color:green'>✅ Đã reset mật khẩu hash cho {$count} tài khoản bác sĩ (mật khẩu: 123456).</p>";
} else {
    echo "<p style='color:red'>Không có tài khoản bác sĩ nào trong DB!</p>";
}
$conn->close();
?>

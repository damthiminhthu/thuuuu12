<?php
include("../includes/connect.php");
include("../includes/header.php");

if (!isset($_GET['id'])) {
    die("Thiếu ID lịch hẹn");
}

$id_lich_hen = (int)$_GET['id'];

// Lấy thông tin bệnh nhân & bác sĩ từ lịch hẹn
$sql = "SELECT benh_nhan_id, bac_si_id FROM lich_hen WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id_lich_hen);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$lich_hen = mysqli_fetch_assoc($result);

if (!$lich_hen) {
    die("Không tìm thấy lịch hẹn");
}

$benh_nhan_id = $lich_hen['benh_nhan_id'];
$bac_si_id    = $lich_hen['bac_si_id'];

// Xử lý lưu hồ sơ
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $chan_doan = $_POST['chan_doan'];
    $dieu_tri  = $_POST['dieu_tri'];
    $ngay_lap  = date('Y-m-d');

    $stmt = mysqli_prepare($conn, "INSERT INTO ho_so_kham (benh_nhan_id, bac_si_id, chan_doan, dieu_tri, ngay_lap) VALUES (?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "iisss", $benh_nhan_id, $bac_si_id, $chan_doan, $dieu_tri, $ngay_lap);
    mysqli_stmt_execute($stmt);

    header("Location: ql_lichhen.php");
    exit;
}
?>

<style>
.container {
    max-width: 600px;
    margin: 40px auto;
    background: #ffffff;
    padding: 25px 30px;
    border-radius: 12px;
    box-shadow: 0 6px 15px rgba(0,0,0,0.1);
    font-family: "Segoe UI", sans-serif;
}

.container h2 {
    text-align: center;
    margin-bottom: 25px;
    color: #31557cff;
    font-size: 24px;
    border-bottom: 2px solid #f0f0f0;
    padding-bottom: 10px;
}

.container label {
    font-weight: bold;
    margin-bottom: 6px;
    display: block;
    color: #333;
}

.container input[type="text"],
.container textarea {
    width: 100%;
    padding: 10px 12px;
    margin-bottom: 20px;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 15px;
    transition: 0.2s;
}

.container input[type="text"]:focus,
.container textarea:focus {
    border-color: #007bff;
    outline: none;
    box-shadow: 0 0 5px rgba(0,123,255,0.4);
}

.container textarea {
    min-height: 100px;
    resize: vertical;
}

.container button {
    width: 100%;
    padding: 12px;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    background: #5d8bbdff;
    color: white;
    cursor: pointer;
    transition: background 0.3s;
}

.container button:hover {
    background: #0056b3;
}
</style>

<div class="container">
    <h2>Nhập kết quả khám</h2>
    <form method="post">
        <label>Chẩn đoán:</label>
        <input type="text" name="chan_doan" required>

        <label>Phương pháp điều trị:</label>
        <textarea name="dieu_tri" required></textarea>

        <button type="submit">Lưu hồ sơ</button>
    </form>
</div>

<?php include("../includes/footer.php"); ?>

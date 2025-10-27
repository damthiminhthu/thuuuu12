<?php
ob_start();
session_start();
include("../includes/connect.php");
$conn->set_charset("utf8mb4");

$loai = $_POST['loai_taikhoan'] ?? '';
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if ($username === '' || $password === '') {
    $_SESSION['error'] = '⚠️ Vui lòng nhập đầy đủ thông tin.';
    header('Location: login.php');
    exit;
}

/* ==================== BÁC SĨ ==================== */
if ($loai === 'bac_si') {
    $stmt = $conn->prepare("
        SELECT 
            tk.id AS tai_khoan_id,
            tk.ten_dang_nhap AS username,
            tk.mat_khau AS mat_khau,
            tk.is_active,
            bs.id AS bac_si_id,
            bs.ho_ten
        FROM tai_khoan_bac_si tk
        INNER JOIN bac_si bs ON bs.tai_khoan_id = tk.id
        WHERE tk.ten_dang_nhap = ?
    ");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $_SESSION['error'] = "❌ Không tìm thấy tài khoản bác sĩ ($username)!";
        header("Location: login.php");
        exit;
    }

    $acc = $result->fetch_assoc();
    $stmt->close();

    // Kiểm tra trạng thái hoạt động
    if ((int)$acc['is_active'] === 0) {
        $_SESSION['error'] = "🚫 Tài khoản bác sĩ đã bị khóa, vui lòng liên hệ quản trị viên!";
        header("Location: login.php");
        exit;
    }

    // Kiểm tra mật khẩu
    $hash = trim($acc['mat_khau']);
    if (!password_verify($password, $hash) && $password !== $hash) {
        $_SESSION['error'] = "❌ Sai mật khẩu bác sĩ!";
        header("Location: login.php");
        exit;
    }

    // Reset session an toàn
    session_unset();
    session_destroy();
    setcookie(session_name(), '', time() - 3600, '/');
    session_start();
    session_regenerate_id(true);

    // Lưu thông tin vào session
    $_SESSION['role'] = 'bac_si';
    $_SESSION['username'] = $acc['username'];
    $_SESSION['tai_khoan_id'] = (int)$acc['tai_khoan_id'];
    $_SESSION['bac_si_id'] = (int)$acc['bac_si_id'];
    $_SESSION['ten_bac_si'] = $acc['ho_ten'];

    header("Location: ../bacsi/bacsi_dashboard.php");
    exit;
}

/* ==================== ADMIN ==================== */
if ($loai === 'admin') {
    $stmt = $conn->prepare("
        SELECT id_admin, tendangnhap, matkhau
        FROM tai_khoan_admin
        WHERE tendangnhap = ?
    ");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $acc = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$acc) {
        $_SESSION['error'] = "❌ Không tìm thấy tài khoản quản trị viên!";
        header("Location: login.php");
        exit;
    }

    // Kiểm tra mật khẩu (cả hash & plaintext)
    $hash = trim($acc['matkhau']);
    if (!password_verify($password, $hash) && $password !== $hash) {
        $_SESSION['error'] = "❌ Sai mật khẩu quản trị viên!";
        header("Location: login.php");
        exit;
    }

    // Reset session an toàn
    session_unset();
    session_destroy();
    setcookie(session_name(), '', time() - 3600, '/');
    session_start();
    session_regenerate_id(true);

    $_SESSION['role'] = 'admin';
    $_SESSION['admin_id'] = $acc['id_admin'];
    $_SESSION['username'] = $acc['tendangnhap'];

    header("Location: ../admin/dashboard.php");
    exit;
}

/* ==================== BỆNH NHÂN ==================== */
if ($loai === 'benh_nhan') {
    $stmt = $conn->prepare("
        SELECT 
            t.id AS tai_khoan_id,
            t.ten_dang_nhap,
            t.mat_khau,
            b.id AS benh_nhan_id,
            b.ho_ten
        FROM tai_khoan_benh_nhan t
        LEFT JOIN benh_nhan b ON b.tai_khoan_id = t.id
        WHERE t.ten_dang_nhap = ?
    ");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $acc = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($acc && (password_verify($password, $acc['mat_khau']) || $password === $acc['mat_khau'])) {
        session_unset();
        session_destroy();
        setcookie(session_name(), '', time() - 3600, '/');
        session_start();
        session_regenerate_id(true);

        $_SESSION['role'] = 'benh_nhan';
        $_SESSION['benh_nhan_id'] = $acc['benh_nhan_id'];
        $_SESSION['ten_benh_nhan'] = $acc['ho_ten'];
        $_SESSION['username'] = $acc['ten_dang_nhap'];

        header("Location: ../index/index.php");
        exit;
    } else {
        $_SESSION['error'] = "❌ Sai tài khoản hoặc mật khẩu bệnh nhân!";
        header("Location: login.php");
        exit;
    }
}

/* ==================== LOẠI KHÔNG HỢP LỆ ==================== */
$_SESSION['error'] = "❌ Loại tài khoản không hợp lệ.";
header("Location: login.php");
exit;
?>

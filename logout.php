<?php
// ================== LOGOUT TỔNG HỢP ==================
session_start();

// Xác định loại tài khoản hiện tại để chuyển hướng về đúng trang
$redirect = "../admin/login.php"; // mặc định

if (isset($_SESSION['role'])) {
    switch ($_SESSION['role']) {
        case 'admin':
            $redirect = "../admin/login.php";
            break;
        case 'bac_si':
            $redirect = "../admin/login.php";
            break;
        case 'benh_nhan':
            $redirect = "../index/index.php";
            break;
    }
}

// Xóa toàn bộ session hiện tại
$_SESSION = [];
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
session_destroy();

// Quay lại trang phù hợp
header("Location: $redirect");
exit;
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Admin - Quản lý phòng khám</title>
    <link rel="stylesheet" href="../css/style.css">

</head>
<style>
/* Reset cơ bản */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

/* Body */
body {
  font-family: "Segoe UI", Arial, sans-serif;
  background-color: #f4f8ff;
  display: flex;
  min-height: 100vh;
  overflow-x: hidden;
}

/* Sidebar cố định */
.sidebar {
  position: fixed;
  top: 0;
  left: 0;
  width: 240px;
  height: 100vh;
  background: linear-gradient(180deg, #1565c0, #42a5f5);
  color: #fff;
  display: flex;
  flex-direction: column;
  box-shadow: 4px 0 12px rgba(0, 0, 0, 0.1);
  z-index: 10;
}

/* Tiêu đề cố định */
.sidebar h2 {
  flex-shrink: 0;
  text-align: center;
  font-size: 1.5rem;
  margin: 0;
  padding: 20px 0;
  letter-spacing: 0.5px;
  font-weight: 600;
  color: #e3f2fd;
  border-bottom: 1px solid rgba(255, 255, 255, 0.25);
}

/* Danh sách cuộn */
.sidebar ul {
  list-style: none;
  overflow-y: auto;
  flex: 1;
  padding: 10px 0;
  margin: 0;
}

/* Tùy chỉnh thanh cuộn cho sidebar */
.sidebar ul::-webkit-scrollbar {
  width: 6px;
}
.sidebar ul::-webkit-scrollbar-thumb {
  background-color: rgba(255, 255, 255, 0.3);
  border-radius: 4px;
}
.sidebar ul::-webkit-scrollbar-thumb:hover {
  background-color: rgba(255, 255, 255, 0.5);
}

/* Item */
.sidebar ul li {
  margin: 6px 0;
}

.sidebar ul li a {
  display: flex;
  align-items: center;
  color: #eaf3ff;
  text-decoration: none;
  padding: 12px 24px;
  font-size: 15px;
  font-weight: 500;
  border-radius: 8px;
  transition: 0.25s;
}

.sidebar ul li a:hover {
  background: rgba(255, 255, 255, 0.15);
  transform: translateX(3px);
  color: #ffffff;
}

.sidebar ul li a span.icon {
  font-size: 18px;
  margin-right: 10px;
}

</style>

<body>
  <div class="sidebar">
  <h2> Admin</h2>
  <ul>
    <li><a href="dashboard.php"><span class="icon"></span> Trang chủ</a></li>
    <li><a href="ql_benhnhan.php"><span class="icon"></span> Quản lý bệnh nhân</a></li>
    <li><a href="ql_bacsi.php"><span class="icon"></span> Quản lý bác sĩ</a></li>
    <li><a href="ql_taikhoan_benhnhan.php"><span class="icon"></span> Tài khoản bệnh nhân</a></li>
    <li><a href="ql_taikhoan_bacsi.php"><span class="icon"></span> Tài khoản bác sĩ</a></li>
    <li><a href="ql_chuyenkhoa.php"><span class="icon"></span> Quản lý chuyên khoa</a></li>
    <li><a href="ql_dichvu.php"><span class="icon"></span> Quản lý dịch vụ</a></li>
    <li><a href="ql_lich_ranh.php"><span class="icon"></span> Lịch rảnh bác sĩ</a></li>
    <li><a href="ql_lichhen.php"><span class="icon"></span> Lịch hẹn bệnh nhân</a></li>
    <li><a href="ql_tintuc.php"><span class="icon"></span> Quản lý tin tức</a></li>
    <li><a href="ql_taikhoan.php"><span class="icon"></span> Quản lý tài khoản hệ thống</a></li>
    <li><a href="dangxuat.php"><span class="icon"></span> Đăng xuất</a></li>
  </ul>
</div>

  <div class="main-content">
    <!-- phần nội dung dashboard -->

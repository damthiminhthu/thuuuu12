<!-- ====================== FOOTER ====================== -->
<footer class="footer">
    <div class="footer-content">
        <p>&copy; 2025 Phòng Khám Đa Khoa. Bảo lưu mọi quyền.</p>
        <p>Địa chỉ: 98 Dương Quảng Hàm, Cầu Giấy, Hà Nội</p>
        <p>Điện thoại: 0985 467 888</p>
    </div>
</footer>

<style>
.footer {
    background-color: #007BFF;        /* Màu xanh chính */
    color: white;
    text-align: center;
    font-family: Arial;
    padding: 20px 0;
    margin-top: 50px;
    width: 100%;
    border-top: 4px solid #006FE6;    /* Đường viền nổi nhẹ */
    box-shadow: 0 -3px 10px rgba(0,0,0,0.1);
}

.footer-content {
    max-width: 1200px;
    margin: auto;
}

.footer-content p {
    margin: 5px 0;
    font-size: 15px;
    letter-spacing: 0.3px;
}

/* Giúp footer luôn nằm sát đáy kể cả khi nội dung ít */
html, body {
    height: 100%;
    margin: 0;
}
body {
    display: flex;
    flex-direction: column;
}
main {
    flex: 1; /* Phần nội dung chính chiếm khoảng trống còn lại */
}
</style>

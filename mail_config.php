<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Gọi các file cần thiết của PHPMailer
require __DIR__ . '/PHPMailer/src/Exception.php';
require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';

function sendMailThongBao($toEmail, $toName, $trang_thai, $ngay, $gio_bat_dau, $gio_ket_thuc, $bac_si) {
    $mail = new PHPMailer(true);

    try {
        // Thiết lập SMTP (dùng Gmail)
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;

        // ⚠️ THAY THÔNG TIN NÀY BẰNG GMAIL CỦA BẠN
        $mail->Username   = 'thuthupl205@gmail.com';      // Gmail của bạn
        $mail->Password   = 'ibno nuxc sngl admh';         // Mật khẩu ứng dụng (App Password)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Thông tin người gửi
        $mail->setFrom('thuthupl205@gmail.com', 'Phong Kham Da Khoa');
        $mail->addAddress($toEmail, $toName);

        // Cấu hình nội dung email
        $mail->isHTML(true);
        $mail->Subject = "CAP NHAT TRANG THAI LICH HEN CUA BAN";

        if ($trang_thai === 'Đã xác nhận') {
            $mail->Body = "
                <h3>Xin chào $toName,</h3>
                <p>Lịch hẹn của bạn với bác sĩ <b>$bac_si</b> vào ngày <b>$ngay</b> từ <b>$gio_bat_dau</b> đến <b>$gio_ket_thuc</b> đã được <b>xác nhận</b>.</p>
                <p>Vui lòng đến đúng giờ. Cảm ơn bạn đã tin tưởng phòng khám!</p>
            ";
        } elseif ($trang_thai === 'Đã hủy') {
            $mail->Body = "
                <h3>Xin chào $toName,</h3>
                <p>Chúng tôi đã hủy lịch hẹn vì lý do khách quan bên phòng khám<b>$bac_si</b> vào ngày <b>$ngay</b>.</p>
                <p>Xin cảm ơn!Vui lòng chọn lại lịch khác.</p>
            ";
        }

        $mail->send();
        error_log("✅ Mail đã gửi tới $toEmail");
    } catch (Exception $e) {
        error_log("❌ Lỗi gửi mail: {$mail->ErrorInfo}");
    }
}
?>

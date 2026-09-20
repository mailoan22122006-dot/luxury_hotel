<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require_once __DIR__ . '/../vendor/src/Exception.php';
require_once __DIR__ . '/../vendor/src/PHPMailer.php';
require_once __DIR__ . '/../vendor/src/SMTP.php';

function sendOTPEmail($toEmail, $otp){

    $mail = new PHPMailer(true);

    try{
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;

        $mail->Username = 'tmthml152122@gmail.com';
        $mail->Password = 'yhtc wwpn lrsb pmep';

        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('tmthml152122@gmail.com', 'LUXURY HOTEL');
        $mail->addAddress($toEmail);

        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        $mail->Subject = 'Mã OTP xác thực';
        $mail->Body = "
            <h3>Mã OTP của bạn</h3>
            <p style='font-size:20px;color:blue;'>
                <b>$otp</b>
            </p>
            <p>OTP có hiệu lực trong 10 phút.</p>
        ";

        $mail->send();
        return true;

    } catch (Exception $e){
    echo "Lỗi gửi mail: " . $mail->ErrorInfo;
    return false;
    }
}
<?php
// forgot_password.php
session_start();

// SỬA LỖI LỆCH MÚI GIỜ
date_default_timezone_set('Asia/Ho_Chi_Minh'); 

require_once 'config/database.php';
require_once 'controllers/AuthController.php';

$authController = new AuthController($pdo);
 
$msg = '';
$msg_type = 'error';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);

    // Kiểm tra email
    $check = $authController->checkEmail($email, 'forgot'); 
    if ($check['status']) { 
        // Gửi OTP 
        $otpResult = $authController->sendOTP($email); 
        if ($otpResult['status']) { 
            $_SESSION['reset_email'] = $email; 
            // Chỉ dùng để test 
            $_SESSION['mock_otp'] = $otpResult['otp']; 
            header("Location: verify.php?purpose=forgot"); 
            exit(); 
        } else { 
            $msg = $otpResult['message']; 
        } 
    } else { 
        $msg = $check['message']; 
    } 
}

// Gọi giao diện quên mật khẩu
require_once 'views/forgot_password.php';
?>
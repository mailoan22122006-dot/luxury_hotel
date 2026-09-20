<?php
// verify.php

session_start();

require_once 'config/database.php';
require_once 'controllers/AuthController.php';

$authController = new AuthController($pdo);

$msg = '';
$msg_type = 'error';

// Xác định mục đích xác thực
$purpose = $_GET['purpose'] ?? 'register';

// Lấy email từ Session
$email = ($purpose === 'register')
    ? ($_SESSION['verify_email'] ?? '')
    : ($_SESSION['reset_email'] ?? '');

// Không có email thì quay lại
if (empty($email)) {
    header("Location: register.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $otp = trim($_POST['otp_code']);
    $result = $authController->verifyOTP($email,$otp,$purpose);

    if ($result['status']) {
        unset($_SESSION['mock_otp']);

        if ($purpose === 'register') {
            unset($_SESSION['verify_email']);
            header("Location: login.php?msg=activated");
        } else {
            header("Location: reset_password.php");
        }
        exit();
    } else {
        $msg = $result['message'];
        $msg_type = "error";
    }
}

// Gửi lại OTP
if (isset($_GET['action']) && $_GET['action'] === 'resend') {

    $result = $authController->resendOTP($email);
    if ($result['status']) {
        $_SESSION['mock_otp'] = $result['otp'];
        $msg = "Đã gửi lại mã OTP!";
        $msg_type = "success";
    } else {
        $msg = $result['message'];
    }
}

require_once 'views/verify.php';
?>


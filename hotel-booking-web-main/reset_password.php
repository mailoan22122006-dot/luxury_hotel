<?php
// reset_password.php

session_start();

require_once 'config/database.php';
require_once 'controllers/AuthController.php';

$authController = new AuthController($pdo);

$msg = '';
$msg_type = 'error';

// Chưa xác thực OTP thì không được vào
if (!isset($_SESSION['reset_email'])) {
    header("Location: forgot_password.php");
    exit();
}

$email = $_SESSION['reset_email'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $newPassword = trim($_POST['new_password']);
    $confirmPassword = trim($_POST['confirm_password']);

    $result = $authController->resetPassword(
        $email,
        $newPassword,
        $confirmPassword
    );

    if ($result['status']) {
        unset($_SESSION['reset_email']);
        unset($_SESSION['mock_otp']);
        header("Location: login.php?msg=reset_success");
        exit();
    } else {
        $msg = $result['message'];
        $msg_type = 'error';

    }
}

require_once 'views/reset_password.php';
?>


<?php
// register.php
session_start();

require_once 'config/database.php';
require_once 'controllers/AuthController.php';

$authController = new AuthController($pdo);

$msg = ''; 
$msg_type = 'error';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $sdt = trim($_POST['sdt']);
    $address = trim($_POST['address']);
    $password = trim($_POST['password']); 

    $result = $authController->register($fullname, $email, $sdt, $address, $password);
    if ($result['status']) { 
        // Gửi OTP 
        $otpResult = $authController->sendOTP($email); 
        if ($otpResult['status']) { 
            $_SESSION['verify_email'] = $result['email']; 
            header("Location: verify.php?purpose=register"); 
            exit(); 
        } else { 
            $msg = $otpResult['message']; 
        } 
    } else { 
        $msg = $result['message']; 
    }
}

require_once 'views/register.php';
?>
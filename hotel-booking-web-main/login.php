<?php
// login.php
require_once 'config/database.php';
require_once 'controllers/AuthController.php';

$authController = new AuthController($pdo);
$msg = ''; 
$msg_type = 'error';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $result = $authController->login($email, $password);

    if (is_array($result) && $result['status']) {
        // Đăng nhập thành công, chuyển hướng về trang chủ
        header("Location: index.php");
        exit();
    } else {
        $msg = $result['message']; 
        $msg_type = 'error';
    }
}
  
require_once 'views/login.php';
?>
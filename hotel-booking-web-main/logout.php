<?php
// logout.php

require_once 'config/database.php'; 
require_once 'controllers/AuthController.php';

$authController = new AuthController($pdo); 
$authController->logout();

// Đẩy người dùng về lại trang chủ
header("Location: index.php");
exit();
?> 
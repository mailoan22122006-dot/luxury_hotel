<?php
// profile.php
session_start();
require_once 'config/database.php';

$msg = '';
$msg_type = '';

// Bắt buộc đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$msg = '';
$msg_type = '';

// Lấy thông tin người dùng
$stmt_user = $pdo->prepare("SELECT * FROM users WHERE id = :id");
$stmt_user->execute([':id' => $user_id]);
$user = $stmt_user->fetch(PDO::FETCH_ASSOC);

// XỬ LÝ: CẬP NHẬT THÔNG TIN CÁ NHÂN
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $fullname = trim($_POST['fullname']);
    $sdt = trim($_POST['sdt']);
    $address = trim($_POST['address']);

    // Kiểm tra họ tên
    if (empty($fullname)) {
        $msg = "Họ và tên không được để trống.";
        $msg_type = "error";
    }
    // Kiểm tra số điện thoại
    elseif (!preg_match('/^[0-9]{10}$/', $sdt)) {
        $msg = "Số điện thoại phải gồm đúng 10 chữ số.";
        $msg_type = "error";
    }
    // Kiểm tra địa chỉ
    elseif (empty($address)) {
        $msg = "Địa chỉ không được để trống.";
        $msg_type = "error";
    } 
    
    else {

    try {
        $stmt_update = $pdo->prepare("UPDATE users SET fullname = :fullname, sdt = :sdt, address = :address WHERE id = :id");
        $stmt_update->execute([
            ':fullname' => $fullname,
            ':sdt' => $sdt,
            ':address' => $address,
            ':id' => $user_id
        ]);

        // Cập nhật lại session để tên mới hiện ngay trên thanh menu
        $_SESSION['fullname'] = $fullname;

        // Lấy thông tin cá nhân mới cập nhật
        $stmt_user = $pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt_user->execute([':id' => $user_id]);
        $user = $stmt_user->fetch(PDO::FETCH_ASSOC);
        
        $msg = "Cập nhật thông tin thành công!";
        $msg_type = "success";
    } catch (PDOException $e) {
        $msg = "Có lỗi xảy ra khi cập nhật.";
        $msg_type = "error";
    }
    }
}

// Gọi giao diện hiển thị
require_once 'views/profile.php';
?>
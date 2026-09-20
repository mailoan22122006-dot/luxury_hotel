<?php
// profile.php
session_start();
require_once 'config/database.php';
require_once 'controllers/BookingController.php';

// Bắt buộc đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$bookingController = new BookingController($pdo);
$userId = $_SESSION['user_id'];
$user_display = $_SESSION['fullname'];

// Lấy thông tin người dùng trực tiếp
$sql = "SELECT * FROM users WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Lấy lịch sử đặt phòng 
$history = $bookingController->getBookingHistory($userId);


// Gọi giao diện hiển thị
require_once 'views/booking_history.php';
?>

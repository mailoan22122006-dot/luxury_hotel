<?php
// cancel_booking.php

session_start();

require_once 'config/database.php';
require_once 'controllers/BookingController.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_fullname = $_SESSION['fullname'];

$bookingController = new BookingController($pdo);
// Kiểm tra ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Đơn đặt phòng không tồn tại.");
}

$bookingId = (int)$_GET['id'];
// Lấy chi tiết đơn
$booking = $bookingController->getBookingDetail($bookingId);

// Tính trước phí hủy và tiền hoàn
$cancelInfo = $bookingController->calculateCancelFee($bookingId);

if ($cancelInfo) {
    $booking['cancel_fee'] = $cancelInfo['cancel_fee'];
    $booking['refund_amount'] = $cancelInfo['refund_amount'];
}

if (!$booking) {
    die("Không tìm thấy đơn đặt phòng.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $reason = trim($_POST['reason']?? '');
    if ($bookingController->cancelBooking(
        $bookingId, 
        $reason, 
        $cancelInfo['cancel_fee'],
        $cancelInfo['refund_amount']
    )) {
        header("Location: booking_history.php");
        exit(); 
    } else {
        $error = "Hủy đặt phòng thất bại.";
    }
}

require_once 'views/cancel_booking.php';
?>
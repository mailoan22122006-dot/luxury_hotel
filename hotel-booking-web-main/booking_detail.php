<?php
// booking_detail.php

session_start();

require_once 'config/database.php';
require_once 'controllers/BookingController.php';

// Bắt buộc đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$bookingController = new BookingController($pdo);
$user_fullname = $_SESSION['fullname'];

// Kiểm tra ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Đơn đặt phòng không tồn tại.");
}
$bookingId = (int)$_GET['id'];
// Lấy chi tiết đơn đặt phòng
$booking = $bookingController->getBookingDetail($bookingId);
if (!$booking) {
    die("Không tìm thấy đơn đặt phòng.");
}
// Gọi giao diện
require_once 'views/booking_detail.php';
?>
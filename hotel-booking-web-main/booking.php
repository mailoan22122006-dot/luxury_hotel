<?php
// booking.php
session_start();
require_once 'config/database.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_fullname = $_SESSION['fullname'];

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

try {
    $stmt = $pdo->prepare("SELECT * FROM rooms WHERE id = :id AND status != 'hidden'");
    $stmt->execute([':id' => $id]);
    $room = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$room) {
        echo "<script>alert('Phòng không tồn tại hoặc đã ngừng kinh doanh!'); window.location.href='index.php';</script>";
        exit();
    }

} catch (PDOException $e) {
    die("Lỗi hệ thống: " . $e->getMessage());
}

// Gọi giao diện form điền thông tin (file này bạn đã làm rất chuẩn rồi)
require_once 'views/booking.php';
?>
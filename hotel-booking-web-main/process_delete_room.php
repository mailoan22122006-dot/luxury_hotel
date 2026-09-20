<?php
session_start();
require_once 'config/database.php';

// Kiểm tra quyền Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header("Location: admin_rooms.php");
    exit();
}

try {

    // Kiểm tra phòng đã có lịch sử đặt chưa
    $check = $pdo->prepare("SELECT COUNT(*) FROM bookings WHERE room_id = ?");
    $check->execute([$id]);

    if ($check->fetchColumn() > 0) {
        echo "<script>
            alert('Không thể xóa vì phòng đã có lịch sử đặt phòng.');
            window.location='admin_rooms.php';
        </script>";
        exit();
    }

    // Xóa phòng
    $stmt = $pdo->prepare("DELETE FROM rooms WHERE id = ?");
    $stmt->execute([$id]);

    echo "<script>
        alert('Xóa phòng thành công!');
        window.location='admin_rooms.php';
    </script>";

} catch (PDOException $e) {
    die("Lỗi: " . $e->getMessage());
}
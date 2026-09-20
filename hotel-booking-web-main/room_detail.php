<?php
// booking_detail.php
require_once 'config/database.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

try {
    $stmt = $pdo->prepare("SELECT * FROM rooms WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $room = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$room) {
        die("Phòng không tồn tại!");
    }
} catch (PDOException $e) {
    die("Lỗi: " . $e->getMessage());
}

// Gọi giao diện Chi tiết phòng
require_once 'views/room_detail.php';
?>
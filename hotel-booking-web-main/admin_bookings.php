<?php
// admin_bookings.php
session_start();
require_once 'config/database.php';

// Kiểm tra quyền Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

try {
    $search = $_GET['search'] ?? '';

    $sql = "SELECT b.*, u.fullname, u.sdt, r.room_number, r.room_type, bi.content as bill_content 
            FROM bookings b 
            JOIN users u ON b.user_id = u.id 
            JOIN rooms r ON b.room_id = r.id 
            LEFT JOIN bills bi ON b.id = bi.booking_id 
            WHERE 1=1";

    $params = [];

    if (!empty($search)) {
        $sql .= " AND (
        u.fullname LIKE :search
        OR u.sdt LIKE :search
        OR b.id LIKE :search
        OR r.room_number LIKE :search
        OR r.room_type LIKE :search
        )";
        $params[':search'] = "%$search%";
    }

    $sql .= " ORDER BY b.id DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Lỗi truy vấn: " . $e->getMessage());
}

// Gọi giao diện hiển thị quản trị
require_once 'views/admin_bookings.php';
?>
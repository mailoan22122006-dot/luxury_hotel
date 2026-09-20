<?php
// admin_payments.php

session_start();

require_once 'config/database.php';

// Kiểm tra quyền Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

try {
    $search = $_GET['search'] ?? '';
    $sql = "SELECT
                bi.id, 
                b.id AS booking_id,
                u.fullname,
                r.room_number,
                bi.amount,
                bi.content,                    
                bi.payment_date
            FROM bills bi
            JOIN bookings b ON bi.booking_id = b.id
            JOIN users u ON b.user_id = u.id
            JOIN rooms r ON b.room_id = r.id
            WHERE 1=1";
    $params = [];
    if ($search != '') {

        $sql .= " AND (
                    bi.id LIKE :search
                    OR b.id LIKE :search
                    OR u.fullname LIKE :search
                    OR r.room_number LIKE :search
                    OR bi.amount LIKE :search
                    OR bi.content LIKE :search
                    OR bi.payment_date LIKE :search
                  )";
        $params[':search'] = "%$search%";
    }
    $sql .= " ORDER BY bi.id DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Lỗi truy vấn: " . $e->getMessage());
}

// Gọi giao diện
require_once 'views/admin_payments.php';
?>
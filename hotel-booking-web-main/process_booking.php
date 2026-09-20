<?php
session_start();
require_once 'config/database.php';

// Kiểm tra bảo mật: Phải đăng nhập mới được đặt
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Vui lòng đăng nhập để thực hiện chức năng này!'); window.location.href='login.php';</script>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $room_id = $_POST['room_id'];
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];
    $note = $_POST['note'] ?? '';
    $payment_method = $_POST['payment_method'] ?? 'cash';
    $price_per_night = $_POST['price_per_night'];

    // 1. Kiểm tra ngày hợp lệ (Backend Validation)
    $d1 = new DateTime($check_in);
    $d2 = new DateTime($check_out);
    
    if ($d1 >= $d2) {
        echo "<script>alert('Lỗi: Ngày trả phòng phải lớn hơn ngày nhận phòng!'); history.back();</script>";
        exit();
    }

    $days = $d1->diff($d2)->days;
    $total_price = $days * $price_per_night;
    $deposit_amount = $total_price * 0.3;

    try {
        // 2. CHỐT CHẶN QUAN TRỌNG: Kiểm tra trùng lịch trong Database
        // Tìm xem có đơn nào (chờ duyệt hoặc thành công) giao thoa với khoảng thời gian khách vừa chọn không
        $sql_check = "SELECT id FROM bookings 
                      WHERE room_id = :room_id 
                      AND status IN ('pending', 'confirmed', 'checked_in') 
                      AND (check_in < :new_check_out AND check_out > :new_check_in)";
        
        $stmt_check = $pdo->prepare($sql_check);
        $stmt_check->execute([
            ':room_id' => $room_id,
            ':new_check_in' => $check_in,
            ':new_check_out' => $check_out
        ]);

        // Nếu query trả về kết quả -> Có đơn trùng lịch!
        if ($stmt_check->fetch()) {
            echo "<script>alert('Rất tiếc! Phòng này đã có người đặt trước trong khoảng thời gian bạn chọn. Vui lòng chọn ngày khác!'); history.back();</script>";
            exit();
        }

        // 3. Nếu qua được chốt chặn -> Ghi vào Database
        $sql_insert = "INSERT INTO bookings (user_id, room_id, check_in, check_out, total_price, deposit_amount, payment_method, note, status, refund_status) 
                       VALUES (:user_id, :room_id, :check_in, :check_out, :total_price, :deposit_amount, :payment_method, :note, 'pending', 'not_required')";
        $stmt_insert = $pdo->prepare($sql_insert);
        $stmt_insert->execute([
            ':user_id' => $user_id,
            ':room_id' => $room_id,
            ':check_in' => $check_in,
            ':check_out' => $check_out,
            ':total_price' => $total_price,
            ':deposit_amount' => $deposit_amount,
            ':payment_method' =>  $payment_method,
            ':note' => $note
        ]);
        $booking_id = $pdo->lastInsertId();
        
        header("Location: payment.php?booking_id=" . $booking_id);
        exit();

    } catch (PDOException $e) {
        echo "<script>alert('Lỗi hệ thống: " . $e->getMessage() . "'); history.back();</script>";
    }
} else {
    header("Location: index.php");
    exit();
}
?>
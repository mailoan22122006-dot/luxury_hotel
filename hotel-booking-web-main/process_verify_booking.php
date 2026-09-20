<?php
// process_verify_booking.php
session_start();
require_once 'config/database.php';
require_once 'controllers/BookingController.php';

$action = isset($_GET['action']) ? trim($_GET['action']) : '';
$booking_id = isset($_GET['booking_id']) ? (int)$_GET['booking_id'] : 0;

if ($booking_id <= 0 || !in_array($action, ['approve', 'cancel', 'checkin', 'checkout', 'refund'])) {
    header("Location: admin_bookings.php");
    exit();
}

try {
    // Lấy thông tin room_id từ booking trước để xử lý cập nhật trạng thái phòng kèm theo
    $roomQuery = $pdo->prepare("SELECT room_id FROM bookings WHERE id = :id");
    $roomQuery->execute([':id' => $booking_id]);
    $bookingData = $roomQuery->fetch(PDO::FETCH_ASSOC);

    if (!$bookingData) {
        die("Đơn hàng không tồn tại trên hệ thống.");
    }

    $room_id = $bookingData['room_id'];

    if ($action === 'approve') {
        // 1. Duyệt đơn hàng thành công
        $updateBooking = $pdo->prepare("UPDATE bookings SET status = 'confirmed' WHERE id = :id");
        $updateBooking->execute([':id' => $booking_id]);

        // 2. Chuyển trạng thái phòng sang 'booked' để khóa phòng
        $updateRoom = $pdo->prepare("UPDATE rooms SET status = 'booked' WHERE id = :room_id");
        $updateRoom->execute([':room_id' => $room_id]);

        echo "<script>alert('Duyệt đơn đặt phòng thành công!'); window.location.href='admin_bookings.php';</script>";
        exit();

    } elseif ($action === 'checkin') {

        // 1. Cập nhật trạng thái đơn
        $updateBooking = $pdo->prepare("UPDATE bookings SET status = 'checked_in' WHERE id = :id");
        $updateBooking->execute([':id' => $booking_id]);
        // 2. Cập nhật trạng thái phòng
        $updateRoom = $pdo->prepare("UPDATE rooms SET status = 'using' WHERE id = :room_id");
        $updateRoom->execute([':room_id' => $room_id]);
        
        echo "<script>alert('Khách đã Check In thành công!'); window.location.href='admin_bookings.php';</script>";
        exit();

    } elseif ($action === 'checkout') {

        // 1. Cập nhật trạng thái đơn
        $updateBooking = $pdo->prepare("UPDATE bookings SET status = 'checked_out' WHERE id = :id");
        $updateBooking->execute([':id' => $booking_id]);

        // Trả phòng về trạng thái trống
        $updateRoom = $pdo->prepare("UPDATE rooms SET status = 'available' WHERE id = :room_id");
        $updateRoom->execute([':room_id' => $room_id]);

        echo "<script>alert('Khách đã Check Out thành công!'); window.location.href='admin_bookings.php';</script>";
        exit();

    } elseif ($action === 'refund') {
        // 1. Cập nhật trạng thái đơn
        $stmt = $pdo->prepare("UPDATE bookings SET refund_status = 'completed' WHERE id = :id");
        $stmt->execute([':id' => $booking_id]);

        echo "<script>alert('Đã xác nhận hoàn tiền!'); window.location.href='admin_bookings.php';</script>";
        exit();
        
    } elseif ($action === 'cancel') {

        // 1. Khởi tạo BookingController
        $bookingController = new BookingController($pdo);

        // 2. Gọi hàm tính phí hủy
        $result = $bookingController->calculateCancelFee($booking_id);
        
        if (!$result) {
            die("Không thể tính phí hủy.");
        }

        $cancelFee = $result['cancel_fee'];
        $refundAmount = $result['refund_amount'];

        $refundStatus = ($refundAmount > 0)
            ? 'pending'
            : 'not_required';

        // 1. Hủy đơn hàng đặt phòng
        $updateBooking = $pdo->prepare("UPDATE bookings 
                                        SET status = 'cancelled', 
                                            cancel_reason = 'Admin hủy đơn đặt phòng',
                                            cancelled_at = NOW(),
                                            cancel_fee = :cancel_fee,
                                            refund_amount = :refund_amount,
                                            refund_status = :refund_status
                                        WHERE id = :id");
        $updateBooking->execute([
            ':id' => $booking_id,
            ':cancel_fee' => $cancelFee,
            ':refund_amount' => $refundAmount,
            ':refund_status' => $refundStatus
        ]);

        // 2. Trả trạng thái phòng về lại 'available' (phòng trống) để khách khác có thể tìm thấy
        $updateRoom = $pdo->prepare("UPDATE rooms SET status = 'available' WHERE id = :room_id");
        $updateRoom->execute([':room_id' => $room_id]);

        echo "<script>alert('Đã hủy đơn đặt phòng thành công!'); window.location.href='admin_bookings.php';</script>";
        exit(); 
    }

} catch (PDOException $e) {
    error_log("Lỗi xử lý duyệt phòng: " . $e->getMessage());
    die("Hệ thống gặp sự cố khi cập nhật trạng thái dữ liệu.");
}
?>
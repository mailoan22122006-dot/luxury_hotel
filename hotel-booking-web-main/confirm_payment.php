<?php
// confirm_payment.php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $booking_id = (int)$_POST['booking_id'];
    $content = trim($_POST['content']);

    try {

        // Lấy thông tin đơn đặt phòng
        $stmt = $pdo->prepare("
            SELECT
                total_price,
                deposit_amount,
                payment_method
            FROM bookings
            WHERE id = :id
        ");

        $stmt->execute([':id' => $booking_id]);
        $booking = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$booking) {
            die("Đơn đặt phòng không tồn tại.");
        }
        
        // Xác định số tiền phải thanh toán
        if ($booking['payment_method'] == 'bank_transfer') {
            $amount = $booking['total_price'];
        } else {
            $amount = $booking['deposit_amount'];
        }

        // Lưu thông tin hóa đơn chuyển khoản vào bảng bills theo thiết kế dữ liệu mới
        $sql = "INSERT INTO bills (booking_id, amount, content) 
                VALUES (:booking_id, :amount, :content)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':booking_id' => $booking_id,
            ':amount' => $amount,
            ':content' => $content
        ]);

        // Thông báo cho khách đơn hàng đang chờ duyệt
        echo "<script>
                alert('Hệ thống đã nhận thông báo chuyển khoản của bạn. Admin sẽ kiểm tra tài khoản và xác nhận đơn đặt phòng thành công trong giây lát!');
                window.location.href = 'index.php';
              </script>";
        exit();

    } catch (PDOException $e) {
        die($e->getMessage());
    }

}
?>
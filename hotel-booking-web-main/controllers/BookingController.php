<?php
// controllers/BookingController.php

class BookingController {
    private $pdo;

    // Hàm khởi tạo nhận kết nối database
    public function __construct($dbConnection) {
        $this->pdo = $dbConnection;
    }

    // Lấy CHI TIẾT MỘT PHÒNG
    public function getRoomDetail($roomId) {
       try {
        $sql = "SELECT
                    id,
                    room_number,
                    room_type,
                    price,
                    img,
                    description,
                    amenities,
                    status
                FROM rooms
                WHERE id = :id
                AND status = 'available'";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $roomId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Lỗi getRoomDetail(): " . $e->getMessage());
        return false;
    }
}

    // LỊCH SỬ ĐẶT PHÒNG

    public function getBookingHistory($userId){
        try {
            // Lấy lịch sử đặt phòng
            $sql_bookings = "SELECT b.*, r.room_number, r.room_type, r.img, r.price
                             FROM bookings b 
                             JOIN rooms r ON b.room_id = r.id 
                             WHERE b.user_id = :user_id 
                             ORDER BY b.id DESC";
            $stmt_bookings =$this->pdo->prepare($sql_bookings);
            $stmt_bookings->execute([':user_id' => $userId]);
            return $stmt_bookings->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }

    // CHI TIẾT MỘT ĐƠN BOOKING
    public function getBookingDetail($bookingId){
        try {
            $sql = "SELECT b.*,b.id, r.room_number,r.room_type, r.price, r.img, r.description, r.amenities
                    FROM bookings b
                    JOIN rooms r ON b.room_id=r.id
                    LEFT JOIN bills bi ON b.id = bi.booking_id
                    WHERE b.id=:id";
            $stmt=$this->pdo->prepare($sql);
            $stmt->execute([':id'=>$bookingId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }

    // TÍNH PHÍ HỦY
    public function calculateCancelFee($bookingId){
        try {
            // Lấy cấu hình hệ thống
            $stmt = $this->pdo->query("
                SELECT cancel_before_days, cancel_fee_percent
                FROM system_settings
                WHERE id = 1
            ");
            $setting = $stmt->fetch(PDO::FETCH_ASSOC);

            // Lấy thông tin đơn đặt phòng
            $stmt = $this->pdo->prepare("
                SELECT check_in, total_price, deposit_amount
                FROM bookings
                WHERE id = :id
            ");
            $stmt->execute([':id' => $bookingId]);
            $booking = $stmt->fetch(PDO::FETCH_ASSOC);
        
            if (!$booking) {return false;}
        
            // Lấy tiền đặt cọc
            $deposit = (float)$booking['deposit_amount'];

            $today = new DateTime(date('Y-m-d'));
            $checkIn = new DateTime($booking['check_in']);
        
            if ($today > $checkIn) {
                $daysBefore = 0;
            } else {
                $daysBefore = $today->diff($checkIn)->days;
            }
            if ($daysBefore >= $setting['cancel_before_days']) {
                $cancelFee = 0;
            } else {
                // Phí hủy tính trên tiền đặt cọc
                $cancelFee = $deposit * ($setting['cancel_fee_percent'] / 100);
            }
            
            $refundAmount = $deposit - $cancelFee;
            
            return [
                'cancel_fee' => $cancelFee,
                'refund_amount' => $refundAmount
            ];
        } catch (PDOException $e) {
            return false;
        }
    }


    // HỦY ĐẶT PHÒNG
    public function cancelBooking($bookingId, $reason, $cancelFee, $refundAmount) {
        try {   
            // Lấy room_id của đơn đặt phòng
            $stmt = $this->pdo->prepare("SELECT room_id FROM bookings WHERE id = :id");
            $stmt->execute([':id' => $bookingId]);
            $booking = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$booking) {
                return false;
            }
            // Cập nhật trạng thái booking
            $stmt = $this->pdo->prepare(
                "UPDATE bookings 
                 SET 
                    status = 'cancelled',
                    cancel_reason = :reason,
                    cancelled_at = NOW(), 
                    cancel_fee = :cancel_fee, 
                    refund_amount = :refund_amount, 
                    refund_status = CASE
                        WHEN :refund_amount > 0 THEN 'pending'
                        ELSE 'not_required'
                    END
                 WHERE id = :id"
            );
            $stmt->execute([
                ':id' => $bookingId,
                ':reason'        => $reason,
                ':cancel_fee'    => $cancelFee,
                ':refund_amount' => $refundAmount
            ]);
            // Trả phòng về available
            $stmt = $this->pdo->prepare(
                "UPDATE rooms
                 SET status = 'available'
                 WHERE id = :room_id"
            );
            $stmt->execute([
                ':room_id' => $booking['room_id']
            ]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
}
?>  
<?php
session_start();

require_once 'config/database.php';

try {
    // 1. Lấy danh sách loại phòng
    $typeQuery = $pdo->query("SELECT DISTINCT room_type
        FROM rooms
        WHERE status != 'hidden'
    ");
    $roomTypes = $typeQuery->fetchAll(PDO::FETCH_COLUMN);

    // 2. Thu thập dữ liệu tìm kiếm từ URL (Phương thức GET)
    $search_type = isset($_GET['room_type']) ? trim($_GET['room_type']) : '';
    $check_in = isset($_GET['check_in']) ? trim($_GET['check_in']) : '';
    $check_out = isset($_GET['check_out']) ? trim($_GET['check_out']) : '';

    // Nếu chưa nhập dữ liệu thì quay về trang chủ
    if (empty($check_in) || empty($check_out)) {
        header("Location: index.php");
        exit();
    } 
    
    // 3. Kiểm tra ngày hợp lệ
    $today = date('Y-m-d');

    if ($check_in < $today) {
        echo "
            <script>
                alert('Ngày nhận phòng không được nhỏ hơn ngày hiện tại!');
                history.back();
            </script>";
        exit();
    }
    $d1 = new DateTime($check_in);
    $d2 = new DateTime($check_out);
    
    if ($d1 >= $d2) {
        echo "
            <script>
                alert('Ngày trả phòng phải sau ngày nhận phòng ít nhất 1 ngày!');
                history.back();
            </script>";
        exit();
    }

    $days = $d1->diff($d2)->days;

    // 4. Xây dựng câu lệnh SQL lọc phòng trống nâng cao
  
    $sql = "SELECT id, room_number, room_type, price, img, description, amenities FROM rooms WHERE status != 'hidden'";
    $params = [];
    // Lọc theo loại phòng nếu khách có chọn
    if (!empty($search_type)) {
        $sql .= " AND room_type = :room_type";
        $params[':room_type'] = $search_type;
    }

    // Thuật toán kiểm tra trùng lịch đặt phòng (Overlap Booking)
    if (!empty($check_in) && !empty($check_out)) {
        $sql .= " AND id NOT IN (
            SELECT room_id FROM bookings 
            WHERE status IN ('pending', 'confirmed') 
            AND NOT (check_out <= :check_in OR check_in >= :check_out)
        )";
        $params[':check_in'] = $check_in;
        $params[':check_out'] = $check_out;
    }
    // Sắp xếp theo giá
    $sql .= " ORDER BY price ASC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {

    error_log($e->getMessage());
    $rooms = [];
    $roomTypes = [];
    echo "<script>
            alert('Hệ thống đang gặp lỗi, vui lòng thử lại sau!');
            history.back();
          </script>";
    exit();
}

$user_fullname = $_SESSION['fullname'] ?? '';

require_once "views/search_room.php";
?>
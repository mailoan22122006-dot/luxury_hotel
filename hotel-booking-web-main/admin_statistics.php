<?php
// admin_statistics.php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') { header("Location: index.php"); exit(); }
// Mặc định lấy dữ liệu từ đầu tháng đến hiện tại nếu chưa chọn
$from_date = $_GET['from_date'] ?? date('Y-m-01');
$to_date = $_GET['to_date'] ?? date('Y-m-t');
$report_type = $_GET['report_type'] ?? 'all';

try {
    $sql = "SELECT b.*, u.fullname, r.room_number, r.room_type 
            FROM bookings b 
            LEFT JOIN users u ON b.user_id = u.id 
            LEFT JOIN rooms r ON b.room_id = r.id 
            WHERE b.check_in >= :from_date AND b.check_in <= :to_date";

    if ($report_type === 'revenue') {
        $sql .= " AND b.status IN ('confirmed', 'checked_in', 'checked_out', 'cancelled')";
    }

    $sql .= " ORDER BY b.check_in DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':from_date' => $from_date, ':to_date' => $to_date]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $total_revenue = 0;
    $total_bookings = count($results);
    $confirmed_bookings = 0;

    foreach ($results as $r) {
        // Đơn thành công
        if (in_array($r['status'], ['confirmed', 'checked_in', 'checked_out'])) {
            $total_revenue += $r['total_price'];
            $confirmed_bookings++;
        }

        // Đơn hủy
        if ($r['status'] === 'cancelled') {
            $total_revenue += $r['cancel_fee'];
        }
    }

} catch (PDOException $e) {
    die("Lỗi truy vấn: " . $e->getMessage());
}

// Gọi giao diện hiển thị
require_once 'views/admin_statistics.php';

?>
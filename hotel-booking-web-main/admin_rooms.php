<?php
// admin_rooms.php
session_start();
require_once 'config/database.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') { 
    header("Location: index.php"); 
    exit(); 
}

try {
    // 1. Lấy từ khóa tìm kiếm từ URL (nếu có)
    $search = $_GET['search'] ?? '';

    // 2. Khởi tạo câu lệnh SQL mặc định
    $sql = "SELECT * FROM rooms WHERE 1=1";
    $params = [];

    // 3. Nếu admin gõ vào thanh tìm kiếm thì thêm điều kiện lọc
    if (!empty($search)) {
        $sql .= " AND (
            room_number LIKE :search 
            OR room_type LIKE :search)";
        $params[':search'] = "%$search%";
    }

    // 4. Sắp xếp phòng mới thêm lên đầu
    $sql .= " ORDER BY id DESC";

    // 5. Thực thi truy vấn
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    error_log("Lỗi tải danh sách phòng: " . $e->getMessage());
    $rooms = [];
}

// Gọi giao diện
require_once 'views/admin_rooms.php';
?>
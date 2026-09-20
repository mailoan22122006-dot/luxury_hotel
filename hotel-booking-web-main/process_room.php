<?php
// process_room.php
session_start();
require_once 'config/database.php';


// Tự động tạo thư mục uploads nếu chưa có
$upload_dir = 'uploads/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

// 1. XỬ LÝ THÊM VÀ SỬA PHÒNG (Method POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    $room_number = trim($_POST['room_number']);
    $room_type = trim($_POST['room_type']);
    $price = (float)$_POST['price'];
    $description = trim($_POST['description'] ?? '');
    $amenities = trim($_POST['amenities'] ?? '');

    // Khởi tạo biến lưu đường dẫn ảnh
    $img_path = '';
    
    // Xử lý file ảnh (nếu Admin có chọn file để tải lên)
    if (isset($_FILES['room_image']) && $_FILES['room_image']['error'] === 0) {
        $ext = pathinfo($_FILES['room_image']['name'], PATHINFO_EXTENSION);
        // Tạo tên file mới để tránh trùng lặp (vd: room_401_168000.jpg)
        $new_name = 'room_' . preg_replace('/[^A-Za-z0-9]/', '', $room_number) . '_' . time() . '.' . $ext;
        $target_file = $upload_dir . $new_name;
        
        // Di chuyển file từ bộ nhớ tạm vào thư mục uploads/
        if (move_uploaded_file($_FILES['room_image']['tmp_name'], $target_file)) {
            $img_path = $target_file; // Gán đường dẫn lưu vào Database
        } else {
            die("<script>alert('Lỗi: Không thể lưu file ảnh vào hệ thống!'); window.history.back();</script>");
        }
    }

    if ($action === 'add') {
        try {
            $sql = "INSERT INTO rooms (room_number, room_type, price, img, description, amenities, status) 
                    VALUES (:rn, :rt, :p, :img, :desc, :amen, 'available')";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':rn' => $room_number,
                ':rt' => $room_type,
                ':p' => $price,
                ':img' => $img_path,
                ':desc' => $description,
                ':amen' => $amenities
                ]);
            echo "<script>alert('Thêm phòng mới thành công!'); window.location.href='admin_rooms.php';</script>";
            exit();
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                die("<script>alert('Lỗi: Số phòng này đã tồn tại!'); window.history.back();</script>");
            }
            die("Lỗi hệ thống: " . $e->getMessage());
        }
    } 
    // --- HÀNH ĐỘNG: SỬA THÔNG TIN ---
    elseif ($action === 'edit') {
        $id = (int)$_POST['id'];
        try {
            if ($img_path !== '') {
                // Nếu Admin tải ảnh mới lên -> Cập nhật cả ảnh
                $sql = "UPDATE rooms SET room_number=:rn, room_type=:rt, price=:p, img=:img, description=:desc, amenities=:amen WHERE id=:id";
                $params = [':rn' => $room_number, ':rt' => $room_type, ':p' => $price, ':img' => $img_path, ':desc' => $description, ':amen' => $amenities, ':id' => $id];
            } else {
                // Nếu không tải ảnh mới -> Giữ nguyên ảnh cũ trong DB
                $sql = "UPDATE rooms SET room_number=:rn, room_type=:rt, price=:p, description=:desc, amenities=:amen WHERE id=:id";
                $params = [':rn' => $room_number, ':rt' => $room_type, ':p' => $price, ':desc' => $description, ':amen' => $amenities, ':id' => $id];
            }
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            
            echo "<script>alert('Cập nhật thông tin phòng thành công!'); window.location.href='admin_rooms.php';</script>";
            exit();
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                die("<script>alert('Lỗi: Số phòng này bị trùng với phòng khác!'); window.history.back();</script>");
            }
            die("Lỗi hệ thống: " . $e->getMessage());
        }
    }
}

// 2. XỬ LÝ ẨN/MỞ LẠI PHÒNG (Method GET)
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $id = (int)$_GET['id'];
    $new_status = '';

    if ($action === 'hide') {
        $new_status = 'hidden';
    } elseif ($action === 'show') {
        $new_status = 'available';
    } else {
        header("Location: admin_rooms.php");
        exit();
    }

    try {
        $sql = "UPDATE rooms SET status = :status WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':status' => $new_status, ':id' => $id]);
        
        header("Location: admin_rooms.php");
        exit();
    } catch (PDOException $e) {
        die("Lỗi cập nhật trạng thái phòng: " . $e->getMessage());
    }
}
?>
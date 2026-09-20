<?php
// admin_users.php
session_start();
require_once 'config/database.php';

// Kiểm tra quyền
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') { header("Location: index.php"); exit(); }

if (isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];
    try {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id AND role = 'customer'");
        $stmt->execute([':id' => $delete_id]);
        echo "<script>alert('Đã xóa người dùng thành công!'); window.location.href='admin_users.php';</script>";
    } catch (PDOException $e) {
        echo "<script>alert('Lỗi: Không thể xóa người dùng này.');</script>";
    }
}

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$sql = "SELECT * FROM users WHERE role = 'customer'";
$params = [];

if (!empty($search)) {
    $sql .= " AND (fullname LIKE :search OR email LIKE :search OR sdt LIKE :search)";
    $params[':search'] = "%$search%";
}

$sql .= " ORDER BY id DESC";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Lỗi truy vấn: " . $e->getMessage());
}

// Gọi giao diện hiển thị
require_once 'views/admin_users.php';
?>


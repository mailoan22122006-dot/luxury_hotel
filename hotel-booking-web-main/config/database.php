<?php
// config/database.php
date_default_timezone_set('Asia/Ho_Chi_Minh');
$host = 'localhost';
$dbname = 'dat_phong_ks';
$username = 'root';
$password = ''; // Mặc định của XAMPP thường là trống

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Kết nối Database thất bại: " . $e->getMessage());
}
?>
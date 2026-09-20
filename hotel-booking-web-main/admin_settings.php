<?php
// admin_settings.php

session_start();

require_once 'config/database.php';

// Kiểm tra quyền Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

$message = "";

try {

    if ($_SERVER['REQUEST_METHOD'] == "POST") {

        $hotel_name = trim($_POST['hotel_name']);
        $checkin_time = $_POST['checkin_time'];
        $checkout_time = $_POST['checkout_time'];
        $cancel_before_days = (int)$_POST['cancel_before_days'];
        $cancel_fee_percent = (float)$_POST['cancel_fee_percent'];
        $maintenance_mode = isset($_POST['maintenance_mode']) ? 1 : 0;

        $sql = "UPDATE system_settings
                SET
                    hotel_name=?,
                    checkin_time=?,
                    checkout_time=?,
                    cancel_before_days=?,
                    cancel_fee_percent=?,
                    maintenance_mode=?
                WHERE id=1";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            $hotel_name,
            $checkin_time,
            $checkout_time,
            $cancel_before_days,
            $cancel_fee_percent,
            $maintenance_mode
        ]);

        $message = "Cập nhật cấu hình thành công.";
    }

    $stmt = $pdo->query("SELECT * FROM system_settings WHERE id=1");
    $setting = $stmt->fetch(PDO::FETCH_ASSOC);

} catch(PDOException $e){

    die($e->getMessage());

}

require_once "views/admin_settings.php";
?>
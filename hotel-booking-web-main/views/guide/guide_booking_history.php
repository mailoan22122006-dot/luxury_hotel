<?php
session_start();

// Kiểm tra đăng nhập
$is_logged_in = isset($_SESSION['user_id']);

$user_fullname = $_SESSION['fullname'] ?? '';
$is_admin = ($_SESSION['role'] ?? '') === 'admin';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Hướng dẫn Xem lịch sử đặt phòng</title>

<style>

* { 
    margin: 0; 
    padding: 0; 
    box-sizing: border-box; 
    font-family: Arial, sans-serif; 
}
        
body { 
    background-color: #AACBFA; 
    color: #333; 
}
        
header { 
    display: flex; 
    justify-content: space-between; 
    align-items: center; 
    background-color: #E6E6E6; 
    padding: 15px 50px; 

}

header h1 { 
    font-size: 28px; 
    font-weight: bold; 
    letter-spacing: 2px; 
    margin: 0; }
        
.auth-buttons a { 
    background-color: #6399F7; 
    color: white; 
    padding: 10px 25px; 
    text-decoration: none; 
    border-radius: 25px; 
    font-weight: bold; 
    margin-left: 10px; 
    font-size: 14px; 
    display: inline-block; 
    transition: 0.3s; 
}
    
.auth-buttons a.secondary { 
    background-color: #AACBFA; 
    color: #333; 
}

.auth-buttons a:hover { 
    opacity: 0.8; 
}
 
.user-dropdown { 
    position: relative; 
    display: inline-block; 
    cursor: pointer; 
}

.user-dropdown::after { 
    content: ""; 
    position: absolute; 
    top: 100%; 
    left: 0; 
    width: 100%; 
    height: 15px;
    background: transparent; 
}

.user-dropdown:hover 

.dropdown-content { 
    display: block; 
}

.dropbtn { 
    display: flex; 
    align-items: center; 
    gap: 10px; 
    background: none; 
    border: none; 
    font-size: 15px; 
    font-weight: bold; 
    cursor: pointer; }

.dropdown-content { 
    display: none; 
    position: absolute; 
    top: 100%; right: 0; 
    background-color: #AACBFA; 
    min-width: 220px; 
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2); 
    z-index: 10; border-radius: 5px; 
    overflow: hidden; margin-top: 10px; 
}

.dropdown-content a { 
    color: #000; 
    padding: 12px 16px; 
    text-decoration: none; 
    display: block; 
    font-size: 14px; 
    font-weight: bold; 
    border-bottom: 1px solid #94bbf2; 
}

.dropdown-content a:hover { 
    background-color: #8ab3f0; 
}

.container{
    width:900px;
    margin:30px auto;
    background:#FFFFFF;
    padding:30px;
    border-radius:5px;
}

.hotel-name{
    text-align:center;
    font-size:20px;
    font-weight:bold;
}

.page-title{
    text-align:center;
    font-size:18px;
    font-weight:bold;
    margin:20px 0;
}

.content{
    line-height:1.8;
}

.back{
    display:inline-block;
    margin-top:20px;
    padding:10px 20px;
    background:#6399F7;
    color:white;
    text-decoration:none;
}

h3{
    color:#000;
}

ul{
    margin-left:20px;
}

</style>

</head>
<body>
   <header>
        <h1>LUXURY HOTEL</h1>
        
        <div class="auth-box">
            <?php if ($is_logged_in): ?>
                <div class="user-dropdown">
                    <button class="dropbtn">
                        <img src="https://cdn-icons-png.flaticon.com/512/1144/1144709.png" alt="User" style="width: 35px; height: 35px; border-radius: 50%; background-color: #AACBFA; padding: 5px; object-fit: cover;">
                        <?= htmlspecialchars($user_fullname) ?>
                    </button>
                    <div class="dropdown-content">
                        <a href="../../index.php">Trang chủ</a>
                        <a href="../../profile.php">Thông tin cá nhân</a>
                        <a href="../../booking_history.php">Lịch sử đặt phòng</a>
                        <a href="guide.php">Hướng dẫn sử dụng</a> 
                        <a href="../../logout.php" style="color: #DC3545;">Đăng xuất</a>
                    </div>
                </div>
            <?php else: ?>
                <div class="auth-buttons">
                    <a href="guide.php">HƯỚNG DẪN SỬ DỤNG</a>
                    <a href="../../register.php">ĐĂNG KÝ</a>
                    <a href="../../login.php">ĐĂNG NHẬP</a>
                </div>
            <?php endif; ?>
        </div>
    </header>
<div class="container">

    <div class="hotel-name">
        Luxury Hotel
    </div>

    <div class="page-title">
        HƯỚNG DẪN XEM LỊCH SỬ ĐẶT PHÒNG
    </div>

    <div class="content">

        <h3>Mục đích</h3>

        <p>
            Cho phép người dùng theo dõi và tra cứu các đơn đặt phòng đã thực hiện trên hệ thống.
        </p>

        <h3>Các bước thực hiện</h3>

        <p><b>Bước 1:</b> Đăng nhập vào hệ thống bằng tài khoản cá nhân.</p>

        <p><b>Bước 2:</b> Tại trang chủ, chọn chức năng <b>Lịch sử đặt phòng</b>.</p>

        <p><b>Bước 3:</b> Hệ thống hiển thị danh sách các đơn đặt phòng của người dùng.</p>

        <p><b>Bước 4:</b> Chọn đơn đặt phòng muốn xem chi tiết.</p>

        <p><b>Bước 5:</b> Hệ thống hiển thị đầy đủ thông tin của đơn đặt phòng đã chọn.</p>

        <h3>Thông tin hiển thị</h3>

        <ul>
            <li>Mã đặt phòng.</li>
            <li>Loại phòng đã đặt.</li>
            <li>Ngày nhận phòng.</li>
            <li>Ngày trả phòng.</li>
            <li>Số lượng khách.</li>
            <li>Tổng tiền thanh toán.</li>
            <li>Trạng thái đặt phòng.</li>
            <li>Trạng thái thanh toán.</li>
            <li>Ngày tạo đơn đặt phòng.</li>
        </ul>

        <h3>Lưu ý</h3>

        <ul>
            <li>Người dùng chỉ được xem lịch sử đặt phòng của chính mình.</li>
            <li>Thông tin đơn đặt phòng đã hủy vẫn được lưu trong lịch sử.</li>
            <li>Có thể xem lại thông tin thanh toán của từng đơn đặt phòng.</li>
        </ul>

        <h3>Kết quả</h3>

        <p>
            Người dùng có thể theo dõi toàn bộ các đơn đặt phòng đã thực hiện và kiểm tra trạng thái của từng đơn trên hệ thống.
        </p>

    </div>

    <a href="guide.php" class="back">
        Quay lại
    </a>

</div>

</body>
</html>
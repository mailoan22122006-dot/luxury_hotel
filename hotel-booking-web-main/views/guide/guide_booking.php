<?php
session_start();

// Kiểm tra đăng nhập
$is_logged_in = isset($_SESSION['user_id']);

$user_fullname = $_SESSION['fullname'] ?? '';
$is_admin = ($_SESSION['role'] ?? '') === 'admin';
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Hướng dẫn đặt phòng</title>

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
    width:800px;
    margin:30px auto;
    background:white;
    padding:20px;
    border-radius:10px;
}

.title{
    text-align:center;
    font-size:20px;
    font-weight:bold;
}

.content{
    margin-top:20px;
    line-height:30px;
}

.btn{
    background:#6399F7;
    border:none;
    padding:10px 20px;
    margin-top:20px;
}

.back{
    display:inline-block;
    margin-top:20px;
    padding:10px 20px;
    background:#6399F7;
    color:white;
    text-decoration:none;
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

<div class="title">
Luxury Hotel
<br>
HƯỚNG DẪN ĐẶT PHÒNG
</div>

<div class="content">

<b>Bước 1:</b> Chọn chức năng Đặt phòng.<br>

<b>Bước 2:</b> Nhập thông tin khách hàng:
<ul>
<li>Họ tên</li>
<li>Email</li>
<li>Số điện thoại</li>
</ul>

<b>Bước 3:</b> Chọn phòng muốn đặt.<br>

<b>Bước 4:</b> Chọn ngày nhận phòng và ngày trả phòng.<br>

<b>Bước 5:</b> Nhập yêu cầu đặc biệt nếu có.<br>

<b>Bước 6:</b> Nhấn nút XÁC NHẬN ĐẶT PHÒNG.<br>

<b>Bước 7:</b> Hệ thống chuyển sang giao diện Thanh toán.

</div>

<a href="guide.php" class="back">
Quay lại
</a>

</div>

</body>
</html>
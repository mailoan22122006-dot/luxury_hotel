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
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Hướng dẫn sử dụng</title>

<style>

* { 
    margin: 0; 
    padding: 0; 
    box-sizing: border-box; 
    font-family: Arial, sans-serif; 
}
        
body { 
    background-color: #F4F7F6; 
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
    width:700px;
    margin:30px auto;
    padding:20px;
    border:1px solid #666;
    background:#AACBFA;
}

.hotel-name{
    text-align:center;
    font-size:20px;
    font-weight:bold;
    margin-bottom:10px;
}

.page-title{
    text-align:center;
    margin-bottom:30px;
    font-size:18px;
    font-weight:bold;
}

label{
    display:block;
    margin-bottom:5px;
}

input[type="text"]{
    width:100%;
    height:35px;
    padding-left:10px;
    box-sizing:border-box;
    background:#FFFFFF;
    border:1px solid #999;
}

.search-btn{
    width:110px;
    height:40px;
    background:#6399F7;
    border:none;
    margin-top:15px;
    float:right;
    cursor:pointer;
    color:white;
}

.clear{
    clear:both;
}

select{
    width:100%;
    height:40px;
    margin-top:25px;
    margin-bottom:25px;
    background:#FFFFFF;
}

.row{
    display:flex;
    align-items:center;
    margin-bottom:12px;
}

.guide-name{
    flex:1;
    height:40px;
    background:#E6E6E6;
    border:1px solid #999;
    display:flex;
    align-items:center;
    justify-content:center;
}

.view-btn{
    width:70px;
    height:40px;
    margin-left:10px;
    background:#6399F7;
    border:none;
    cursor:pointer;
    color:white;
}

.view-btn:hover,
.search-btn:hover{
    opacity:0.9;
}

a{
    text-decoration:none;
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

    <div class="hotel-name">
        Luxury Hotel
    </div>

    <div class="page-title">
        HƯỚNG DẪN SỬ DỤNG
    </div>

    <label>Tìm kiếm hướng dẫn</label>

    <input type="text" placeholder="Nhập từ khóa...">

    <button class="search-btn">
        Tìm kiếm
    </button>

    <div class="clear"></div>
 
    <select>
        <option>Danh mục hướng dẫn</option>
        <option>Hướng dẫn Đăng nhập</option>
        <option>Hướng dẫn Đăng ký</option>
        <option>Hướng dẫn Quên mật khẩu</option>
        <option>Hướng dẫn Đăng xuất</option>
        <option>Hướng dẫn Tìm thông tin phòng</option>
        <option>Hướng dẫn Đặt phòng</option>
        <option>Hướng dẫn Thanh toán</option>
        <option>Hướng dẫn Hủy đặt phòng</option>
        <option>Hướng dẫn Xem lịch sử đặt phòng</option>
        <option>Hướng dẫn Chỉnh sửa thông tin cá nhân</option>
    </select>

    <div class="row">
        <div class="guide-name">
            Hướng dẫn Đăng nhập
        </div>

        <a href="guide_login.php">
            <button class="view-btn">Xem</button>
        </a>
    </div>

    <div class="row">
        <div class="guide-name">
            Hướng dẫn Đăng ký
        </div>

        <a href="guide_register.php">
            <button class="view-btn">Xem</button>
        </a>
    </div>

    <div class="row">
        <div class="guide-name">
            Hướng dẫn Quên mật khẩu
        </div>

        <a href="guide_forgot_password.php">
            <button class="view-btn">Xem</button>
        </a>
    </div>

    <div class="row">
        <div class="guide-name">
            Hướng dẫn Đăng xuất
        </div>
 
        <a href="guide_logout.php">
            <button class="view-btn">Xem</button>
        </a>
    </div>

    <div class="row">
        <div class="guide-name">
            Hướng dẫn Tìm thông tin phòng
        </div>

        <a href="guide_search_room.php">
            <button class="view-btn">Xem</button>
        </a>
    </div>

    <div class="row">
        <div class="guide-name">
            Hướng dẫn Đặt phòng
        </div>

        <a href="guide_booking.php">
            <button class="view-btn">Xem</button>
        </a>
    </div>

    <div class="row">
        <div class="guide-name">
            Hướng dẫn Thanh toán
        </div>

        <a href="guide_payment.php">
            <button class="view-btn">Xem</button>
        </a>
    </div>

    <div class="row">
        <div class="guide-name">
            Hướng dẫn Hủy đặt phòng
        </div>

        <a href="guide_cancel_booking.php">
            <button class="view-btn">Xem</button>
        </a>
    </div>

    <div class="row">
        <div class="guide-name">
            Hướng dẫn Xem lịch sử đặt phòng
        </div>

        <a href="guide_booking_history.php">
            <button class="view-btn">Xem</button>
        </a>
    </div>

    <div class="row">
        <div class="guide-name">
            Hướng dẫn Chỉnh sửa thông tin cá nhân
        </div>

        <a href="guide_profile.php">
            <button class="view-btn">Xem</button>
        </a>
    </div>

<a href="../../index.php">
    <button class="back">Quay lại</button>
</a>
</div>

</body>
</html>
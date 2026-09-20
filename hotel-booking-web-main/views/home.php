<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Dùng isset() để kiểm tra xem đã đăng nhập chưa trước khi lấy dữ liệu
$is_logged_in = isset($_SESSION['user_id']);
$user_display = isset($_SESSION['fullname']) ? $_SESSION['fullname'] : (isset($_SESSION['email']) ? $_SESSION['email'] : '');
$is_admin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Trang Chủ - Luxury Hotel</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: Arial, sans-serif; }
        body { background-color: #F4F7F6; color: #333; }
        
        /* HEADER */
        header { display: flex; justify-content: space-between; align-items: center; background-color: #E6E6E6; padding: 15px 50px; }
        header h1 { font-size: 28px; font-weight: bold; letter-spacing: 2px; margin: 0; }
        
        .auth-buttons a { background-color: #6399F7; color: white; padding: 10px 25px; text-decoration: none; border-radius: 25px; font-weight: bold; margin-left: 10px; font-size: 14px; display: inline-block; transition: 0.3s; }
        .auth-buttons a.secondary { background-color: #AACBFA; color: #333; }
        .auth-buttons a:hover { opacity: 0.8; }
        
        /* CẦU NỐI TÀNG HÌNH */
        .user-dropdown { position: relative; display: inline-block; cursor: pointer; }
        .user-dropdown::after { content: ""; position: absolute; top: 100%; left: 0; width: 100%; height: 15px; background: transparent; }
        .user-dropdown:hover .dropdown-content { display: block; }
        .dropbtn { display: flex; align-items: center; gap: 10px; background: none; border: none; font-size: 15px; font-weight: bold; cursor: pointer; }
        .dropdown-content { display: none; position: absolute; top: 100%; right: 0; background-color: #AACBFA; min-width: 220px; box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2); z-index: 10; border-radius: 5px; overflow: hidden; margin-top: 10px; }
        .dropdown-content a { color: #000; padding: 12px 16px; text-decoration: none; display: block; font-size: 14px; font-weight: bold; border-bottom: 1px solid #94bbf2; }
        .dropdown-content a:hover { background-color: #8ab3f0; }
       
        /* MAIN CONTENT */
        .main-content { padding: 40px 50px; max-width: 1200px; margin: 0 auto; }
        .slogan { font-size: 18px; font-weight: bold; margin-bottom: 20px; color: #000; }

        /* THANH TÌM KIẾM (Chỉ còn 3 ô + 1 nút) */
        .search-box { display: flex; background-color: #AACBFA; padding: 10px; border-radius: 10px; border: 2px solid #6399F7; gap: 10px; align-items: center; margin-bottom: 30px; }
        .search-box input, .search-box select { flex: 1; padding: 15px; border: none; border-radius: 5px; font-size: 14px; outline: none; background: #fff; font-weight: bold; color: #555; }
        .search-box button { padding: 15px 30px; background-color: #6399F7; color: white; border: none; border-radius: 5px; font-weight: bold; cursor: pointer; font-size: 15px; transition: 0.3s; }
        .search-box button:hover { background-color: #4A84E8; }

        /* MENU NHANH 2 CỘT (Chỉ hiện khi đăng nhập) */
        .quick-links { display: flex; gap: 20px; margin-bottom: 40px; }
        .quick-links a { flex: 1; background-color: #AACBFA; color: #000; padding: 30px 20px; text-align: center; text-decoration: none; font-weight: bold; border-radius: 8px; border: 2px solid #3b7dec; font-size: 16px; transition: 0.3s; }
        .quick-links a:hover { background-color: #8ab3f0; }

        /* DANH SÁCH PHÒNG */
        .section-title { font-size: 18px; font-weight: bold; margin-bottom: 20px; color: #000; }
        .room-grid { display: flex; gap: 20px; overflow-x: auto; padding-bottom: 15px; }
        /* Tùy chỉnh thanh cuộn ngang */
        .room-grid::-webkit-scrollbar { height: 8px; }
        .room-grid::-webkit-scrollbar-thumb { background: #AACBFA; border-radius: 4px; }
        
        .room-card { min-width: 220px; background: #fff; border: 1px solid #ddd; border-radius: 8px; text-align: center; overflow: hidden; text-decoration: none; color: inherit; display: flex; flex-direction: column; transition: transform 0.3s ease; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        .room-card:hover { transform: translateY(-5px); border-color: #6399F7; }
        .room-card img { width: 100%; height: 160px; object-fit: cover; }
        .room-card .info { padding: 20px; font-weight: bold; }
        .room-card .info .price { color: #6399F7; font-size: 15px; margin-top: 5px; display: block; }
        
        .about-section { margin-top: 50px; font-size: 16px; font-weight: bold; color: #000; padding-top: 20px; border-top: 1px solid #ddd; }
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
                        <?= htmlspecialchars($user_display) ?>
                    </button>
                    
                    <?php if ($is_admin): ?>
                        <div class="dropdown-content">
                            <a href="index.php">Trang chủ</a>
                            <a href="admin_bookings.php">Báo cáo thống kê</a>
                            <a href="admin_bookings.php">Quản lý đặt phòng</a>
                            <a href="admin_rooms.php">Quản lý phòng</a>
                            <a href="admin_users.php">Quản lý người dùng</a>
                            <a href="admin_payments.php">Quản lý thanh toán</a>
                            <a href="admin_settings.php">Quản lý cấu hình hệ thống</a>
                            <a href="logout.php" style="color: #DC3545;">Đăng xuất</a>
                        </div>
                    <?php else: ?>
                        <div class="dropdown-content">
                            <a href="index.php">Trang chủ</a>
                            <a href="profile.php">Thông tin cá nhân</a>
                            <a href="booking_history.php">Lịch sử đặt phòng</a>
                            <a href="views/guide/guide.php">Hướng dẫn sử dụng</a>
                            <a href="logout.php" style="color: #DC3545;">Đăng xuất</a>
                        </div>
                    <?php endif; ?>
            <?php else: ?>
                <div class="auth-buttons">
                    <a href="views/guide/guide.php">HƯỚNG DẪN SỬ DỤNG</a>
                    <a href="register.php">ĐĂNG KÝ</a>
                    <a href="login.php">ĐĂNG NHẬP</a>
                    
                </div>
            <?php endif; ?>
        </div>
    </header>

    <div class="main-content">
        <?php if ($is_admin): ?>
            <div style="text-align: center; margin-top: 40px; padding: 40px; background-color: #fff; border-radius: 10px; border: 2px solid #AACBFA; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
                <h2 style="font-size: 36px; color: #2C3E50; margin-bottom: 15px;">XIN CHÀO QUẢN TRỊ VIÊN</h2>
                <p style="font-size: 18px; color: #555; margin-bottom: 40px;">Chào mừng bạn quay trở lại trung tâm điều hành. Vui lòng chọn nghiệp vụ bên dưới:</p>
                
                <div style="display: flex; gap: 20px; justify-content: center; flex-wrap: wrap;">
                    <a href="admin_statistics.php" style="padding: 25px 40px; background-color: #17A2B8; color: white; text-decoration: none; border-radius: 10px; font-weight: bold; font-size: 18px; width: 250px; transition: transform 0.3s;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                        📊 Báo Cáo Thống Kê
                    </a>
                    <a href="admin_bookings.php" style="padding: 25px 40px; background-color: #6399F7; color: white; text-decoration: none; border-radius: 10px; font-weight: bold; font-size: 18px; width: 250px; transition: transform 0.3s;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                        🛎️ Quản Lý Đặt Phòng
                    </a>
                    <a href="admin_rooms.php" style="padding: 25px 40px; background-color: #28A745; color: white; text-decoration: none; border-radius: 10px; font-weight: bold; font-size: 18px; width: 250px; transition: transform 0.3s;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                        🛏️ Quản Lý Phòng
                    </a>
                    <a href="admin_users.php" style="padding: 25px 40px; background-color: #FFC107; color: #000; text-decoration: none; border-radius: 10px; font-weight: bold; font-size: 18px; width: 250px; transition: transform 0.3s;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                        👥 Quản Lý Người Dùng
                    </a>
                    <a href="admin_payments.php" style="padding: 25px 40px; background-color: #ff6607; color: #000; text-decoration: none; border-radius: 10px; font-weight: bold; font-size: 18px; width: 250px; transition: transform 0.3s;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                        💵 Quản Lý Thanh Toán
                    </a>
                    <a href="admin_settings.php" style="padding: 25px 40px; background-color: #f777e2; color: #000; text-decoration: none; border-radius: 10px; font-weight: bold; font-size: 18px; width: 250px; transition: transform 0.3s;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                        ⚙️ Quản Lý Cấu Hình Hệ Thống
                    </a>
                </div>
            </div>

        <?php else: ?>
            <div class="slogan">Trải nghiệm nghỉ dưỡng hấp dẫn, đặt phòng nhanh chóng và an toàn</div>

            <form action="search_room.php" method="GET" class="search-box">
                <input type="date" name="check_in" title="Ngày nhận phòng" value="<?= isset($_GET['check_in']) ? htmlspecialchars($_GET['check_in']) : '' ?>" required>
                <input type="date" name="check_out" title="Ngày trả phòng" value="<?= isset($_GET['check_out']) ? htmlspecialchars($_GET['check_out']) : '' ?>" required>
                
                <select name="room_type">
                    <option value="">LOẠI PHÒNG</option>
                    <?php if (!empty($roomTypes)): ?>
                        <?php foreach ($roomTypes as $type): ?>
                            <option value="<?= htmlspecialchars($type) ?>" <?= (isset($_GET['room_type']) && $_GET['room_type'] === $type) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($type) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                
                <button type="submit">TÌM KIẾM</button>
            </form>

            <?php if ($is_logged_in): ?>
                <div class="quick-links">
                    <a href="profile.php">Thông tin cá nhân</a>
                    <a href="booking_history.php">Lịch sử đặt phòng</a>
                    <a href="views/guide/guide.php">Hướng dẫn sử dụng</a>
                </div>
            <?php endif; ?>

            <div class="section-title">Phòng có view đẹp, thích hợp cho lựa chọn của bạn</div>
            
            <?php if (!empty($rooms)): ?>
                <div class="room-grid">
                    <?php foreach ($rooms as $room): ?>
    <a href="room_detail.php?id=<?= $room['id'] ?>" class="room-card"> <img src="<?= htmlspecialchars($room['img']) ?>" alt="Phòng <?= htmlspecialchars($room['room_number']) ?>">
        <div class="info">
            Phòng <?= htmlspecialchars($room['room_number']) ?>
            <span class="price"><?= number_format($room['price']) ?> VNĐ/ĐÊM</span>
        </div>
    </a>
<?php endforeach; ?>
                </div>
            <?php else: ?>
                <p style="color: #DC3545; font-weight: bold; margin-bottom: 20px;">Không tìm thấy phòng trống nào phù hợp với điều kiện tìm kiếm của bạn.</p>
            <?php endif; ?>

            <div class="about-section">
                Giới thiệu khách sạn
                <p style="font-weight: normal; font-size: 14px; margin-top: 10px; color: #555;">Luxury Hotel mang đến không gian nghỉ dưỡng đẳng cấp, tiện nghi hiện đại và dịch vụ tận tâm. Nơi dừng chân lý tưởng cho mọi chuyến đi của bạn.</p>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>
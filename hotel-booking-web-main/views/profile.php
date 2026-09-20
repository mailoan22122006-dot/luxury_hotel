<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Hồ Sơ Cá Nhân - Luxury Hotel</title>
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
        
        .container { max-width: 1200px; padding: 40px 50px; margin:0 auto; display:grid; grid-template-columns:200px 300px 1fr; column-gap:5=40px; align-items:flex-start; }

        .left-menu{ width:220px; background:#EEF6FF; border:1px solid #AACBFA; padding:0; }
        .left-menu a{ display:block; padding:14px 22px; color:#222; text-decoration:none; font-weight:bold; border-bottom:1px solid #d9e7ff; transition:.25s; }
        .left-menu a:hover{ background:#AACBFA; }
        .left-menu a.active{ color:#6399F7; }

        .avatar-box{ display:flex; justify-content:center; align-items:flex-start; }
        .avatar{ width:190px; height:190px; border-radius:50%; border:2px solid #666; background:#AACBFA; overflow:hidden; }
        .avatar img{ width:100%; height:100%; object-fit:cover; }

        .profile-card{ background:#EEF6FF; border:1px solid #AACBFA; padding:25px;}
        .profile-card h3{ text-align:center; background:#AACBFA; padding:10px; margin-bottom:20px; border-radius:4px; }

        .info-group{ margin-bottom:15px; }
        .info-group label{ display:block; margin-bottom:5px; font-weight:bold; } 
        .info-group input{ width:100%; padding:12px; border:1px solid #AACBFA; border-radius:5px; font-size:15px; }
        .info-group input:disabled{ background:#EEE; }
        
        .btn-group { display: flex; gap: 15px; margin-top: 20px; }
        .btn-group a, .btn-group button { flex: 1; padding: 12px; text-align: center; border-radius: 20px; font-size: 15px; font-weight: bold; cursor: pointer; text-decoration: none; transition: 0.3s; border: none; }
        .btn-reset-link { background-color: #6399F7; color: #000000; }
        .btn-reset-link:hover { background-color: #6399F7; }
        .btn-profile-submit { background-color: #6399F7; color: #FFFFFF; }
        .btn-profile-submit:hover { background-color: #6399F7; color: #ffffff; }

        .msg { text-align: center; margin-bottom: 15px; padding: 10px; border-radius: 5px; }
        .msg.error { background-color: #ffcccc; color: red; }

</style>
</head>
<body>
    <header>
        <h1>LUXURY HOTEL</h1>
        
        <div class="auth-box">
                <div class="user-dropdown">
                    <button class="dropbtn">
                        <img src="https://cdn-icons-png.flaticon.com/512/1144/1144709.png" alt="User" style="width: 35px; height: 35px; border-radius: 50%; background-color: #AACBFA; padding: 5px; object-fit: cover;">
                        <?= htmlspecialchars($user['fullname']) ?>
                    </button>
                    <div class="dropdown-content">
                        <a href="index.php">Trang chủ</a>
                        <a href="profile.php">Thông tin cá nhân</a>
                        <a href="booking_history.php">Lịch sử đặt phòng</a> 
                        <a href="views/guide/guide.php">Hướng dẫn sử dụng</a>
                        <a href="logout.php" style="color: #DC3545;">Đăng xuất</a>
                    </div>
                </div>
        </div>
    </header>
    <div class="container">
        <div class="left-menu">
            <a href="index.php">Trang chủ</a>
            <a href="profile.php" class="active">Thông tin cá nhân</a>
            <a href="booking_history.php">Lịch sử đặt phòng</a> 
            <a href="views/guide/guide.php">Hướng dẫn sử dụng</a>
        </div>

        <div class="avatar-box">
            <div class="avatar">
                <?php if(!empty($user['avatar'])): ?>
                    <img src="uploads/<?= htmlspecialchars($user['avatar']) ?>">
                <?php else: ?>
                    <img src="https://cdn-icons-png.flaticon.com/512/1144/1144709.png">
                <?php endif; ?>
            </div>
        </div>

        <div class="profile-card">
            <h3>THÔNG TIN CÁ NHÂN</h3>
            <?php if (!empty($msg)) : ?>
                <div class="msg <?= $msg_type ?>">
                    <?= htmlspecialchars($msg) ?>
                </div>
            <?php endif; ?>
                
        <form action="profile.php" method="POST">
                
            <input type="hidden" name="update_profile" value="1">
                
            <div class="info-group">
                <label>Họ và Tên</label>
                <input type="text" name="fullname" value="<?= htmlspecialchars($user['fullname']) ?>" required>
            </div>

            <div class="info-group">
                <label>Email</label>
                <input type="email" value="<?= htmlspecialchars($user['email']) ?>" disabled title="Không thể thay đổi email">
            </div>

            <div class="info-group">
                <label>Số điện thoại</label>
                <input type="text" name="sdt" value="<?= htmlspecialchars($user['sdt']) ?>" required>
            </div>

            <div class="info-group">
                <label>Địa chỉ</label>
                <input type="text" name="address" value="<?= htmlspecialchars($user['address']) ?>" required>
            </div>
            
            <div class="btn-group">
                <a href="reset_password.php" class="btn-reset-link">ĐỔI MẬT KHẨU</a>
                <button type="submit" class="btn-porofile-submit">CẬP NHẬT THÔNG TIN</button>
            </div>
        </form>
        </div>
    </div>
</body>
</html>
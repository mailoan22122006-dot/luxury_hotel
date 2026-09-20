<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản Lý Cấu Hình Hệ Thống - Luxury Hotel Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body { height: 100vh; display: flex; flex-direction: column; background-color: #F8FAFC; color: #1E293B; }
        
        .top-header { background-color: #0F5FF2; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e0e0e0; color: #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .top-header h1 { font-size: 20px; font-weight: bold; letter-spacing: 1px; }
        
        .user-dropdown { position: relative; display: inline-block; cursor: pointer; }
        .user-dropdown::after { content: ""; position: absolute; top: 100%; left: 0; width: 100%; height: 15px; background: transparent; }
        .user-dropdown:hover .dropdown-content { display: block; }
        .dropbtn { display: flex; align-items: center; gap: 10px; background: none; border: none; font-size: 15px; font-weight: bold; cursor: pointer; }
        .dropdown-content { display: none; position: absolute; top: 100%; right: 0; background-color: #AACBFA; min-width: 220px; box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2); z-index: 10; border-radius: 5px; overflow: hidden; margin-top: 10px; }
        .dropdown-content a { color: #000; padding: 12px 16px; text-decoration: none; display: block; font-size: 14px; font-weight: bold; border-bottom: 1px solid #94bbf2; }
        .dropdown-content a:hover { background-color: #8ab3f0; }

        .layout-wrapper { display: flex; flex: 1; overflow: hidden; }
        
       /* SIDEBAR BÊN TRÁI ĐỒNG BỘ */
        .sidebar { width: 260px; background-color: #0D163C; border-right: 1px solid #333; padding: 20px 0; display: flex; flex-direction: column; }
        .sidebar a { display: flex; align-items: center; gap: 12px; padding: 15px 25px; color: #CBD5E1; text-decoration: none; font-size: 15px; transition: 0.2s; border-radius: 0 25px 25px 0; margin-right: 10px; }
        .sidebar a:hover { color: #fff; background-color: #26336D; }
        .sidebar a.active { color: #fff; background-color: #26336D; font-weight: 600; border-left: 4px solid #fff; }
        .sidebar .btn-logout { margin-top: auto; color: #DC3545; background: none; border: none; font-weight: bold; }
        .sidebar .btn-logout:hover { color: #fff; background-color: #DC3545; }
 
        /* NỘI DUNG BÊN PHẢI */
        .main-content { flex: 1; padding: 30px; overflow-y: auto; background-color: #F8FAFC; }
        
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; border-bottom: 1px solid #E2E8F0; padding-bottom: 15px; }
        .page-header h2 { font-size: 22px; font-weight: bold; color: #1E293B; }

        .success{ margin-bottom:20px; color:green; font-weight:bold; }

        .form-group{ margin-bottom:20px; }
        .form-group label{ display:block; font-weight:bold; margin-bottom:8px; }
        .form-group input{ width:100%; padding:12px; border:1px solid #ddd; border-radius:6px; font-size:15px; }
         
        .save-btn{ margin-top:10px; padding:12px 40px; background:#4663F2; color:white; border:none; border-radius:6px; cursor:pointer; font-size:16px; }
        .save-btn:hover{ background:#3650d6; }

    </style>
</head>
<body>

    <div class="top-header">
        <h1>LUXURY HOTEL ADMIN</h1>
        <div class="user-dropdown">
            <button class="dropbtn">
                <img src="https://cdn-icons-png.flaticon.com/512/1144/1144709.png" alt="Admin" style="width: 35px; height: 35px; border-radius: 50%; background-color: #AACBFA; padding: 5px; object-fit: cover;">
                Administrator
            </button>
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
        </div>
    </div>
    <div class="layout-wrapper">
        <div class="sidebar">
            <a href="admin_statistics.php"><i class="fas fa-chart-line"></i> Báo cáo thống kê</a>
            <a href="admin_bookings.php"><i class="fas fa-receipt"></i> Quản lý đặt phòng</a>
            <a href="admin_rooms.php"><i class="fas fa-bed"></i> Quản lý phòng</a>
            <a href="admin_users.php"><i class="fas fa-user-group"></i> Quản lý người dùng</a>
            <a href="admin_payments.php"><i class="fas fa-money-check-alt"></i> Quản lý thanh toán</a>
            <a href="admin_settings.php"class="active"><i class="fas fa-cogs"></i> Quản lý cấu hình hệ thống</a>
            <a href="index.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Quay lại trang chủ</a>
        </div>

        <div class="main-content">
            <div class="page-header"> 
                <h2>⚙️ Cấu Hình Hệ Thống</h2>
            </div>
            
            <?php if($message!=""): ?>
                    <div class="success"><?= $message ?></div>
            <?php endif; ?>

            <form method="post">
                <div class="form-group">
                    <label>Tên khách sạn</label>
                    <input type="text" name="hotel_name" value="<?= htmlspecialchars($setting['hotel_name']) ?>" required>
                </div>

                <div class="form-group">
                    <label>Giờ nhận phòng</label>
                    <input type="time" name="checkin_time" value="<?= $setting['checkin_time'] ?>">
                </div>
                
                <div class="form-group">
                    <label>Giờ trả phòng</label>
                    <input type="time" name="checkout_time" value="<?= $setting['checkout_time'] ?>">
                </div>
                
                <div class="form-group">
                    <label>Số ngày được hủy miễn phí</label>
                    <input type="number" name="cancel_before_days" value="<?= $setting['cancel_before_days'] ?>">
                </div>

                <div class="form-group">
                    <label>Phí hủy (%)</label>
                    <input type="number" step="0.01" name="cancel_fee_percent" value="<?= $setting['cancel_fee_percent'] ?>">
                </div>
                
                <button class="save-btn">
                    <i class="fas fa-floppy-disk"></i>
                    Lưu cấu hình
                </button>
            </form>
        </div>
    </div>
</body>
</html>
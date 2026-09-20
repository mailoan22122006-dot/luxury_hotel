<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản Lý Phòng - Luxury Hotel Admin</title>
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
        
        /* THANH TÌM KIẾM TRONG CARD */
        .search-card { background: #fff; padding: 15px; border-radius: 8px; border: 1px solid #E2E8F0; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; }
        .search-box { display: flex; align-items: center; gap: 10px; border: 1px solid #E2E8F0; padding: 6px 12px; border-radius: 6px; width: 350px; background-color: #fff; }
        .search-box input { flex: 1; padding: 8px; border: none; outline: none; font-size: 14px; color: #333; }
        .btn-search { background: none; border: none; color: #64748B; cursor: pointer; }
        .btn-search:hover { color: #0F5FF2; }

        /* BẢNG DỮ LIỆU HIỆN ĐẠI */
        .table-container { background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
        table { width: 100%; border-collapse: collapse; text-align: left; }
        th, td { padding: 16px; border-bottom: 1px solid #E2E8F0; font-size: 14px; }
        th { background-color: #F8FAFC; color: #64748B; font-weight: 600; text-transform: uppercase; font-size: 12px; }
        tbody tr:hover { background-color: #F1F5F9; }
        
        .badge { padding: 5px 10px; font-size: 12px; font-weight: bold; border-radius: 20px; text-transform: uppercase; }
        .status-pending { background-color: rgba(255, 128, 0, 0.1); color: #FF8000; }
        .status-confirmed { background-color: rgba(40, 167, 69, 0.1); color: #28A745; }
        .status-cancelled { background-color: rgba(220, 53, 69, 0.1); color: #DC3545; }
        
        /* NÚT HÀNH ĐỘNG CÓ ICON */
        .btn-action { padding: 8px 12px; border: none; border-radius: 6px; font-size: 12px; font-weight: bold; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; color: white; transition: 0.3s; }
        .btn-approve { background-color: #28A745; }
        .btn-approve:hover { background-color: #218838; }
        .btn-cancel { background-color: #DC3545; }
        .btn-cancel:hover { background-color: #C82333; }
        .syntax-text { background-color: #F1F5F9; padding: 4px 8px; font-family: monospace; border: 1px solid #E2E8F0; border-radius: 4px; color: #444; }
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
            <a href="admin_payments.php"class="active"><i class="fas fa-money-check-alt"></i> Quản lý thanh toán</a>
            <a href="admin_settings.php"><i class="fas fa-cogs"></i> Quản lý cấu hình hệ thống</a>
            <a href="index.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Quay lại trang chủ</a>
        </div>

        <div class="main-content">
            <div class="page-header"> 
                <h2>💵 Quản Lý Thanh toán</h2>
            </div>
            <div class="search-card">
                <div style="font-size: 14px; color: #64748B;">Tổng số hóa đơn: <strong><?= count($payments) ?></strong></div>
                <form action="admin_payments.php" method="GET" class="search-box">
                    <button type="submit" class="btn-search"><i class="fas fa-search"></i></button>
                    <input type="text" name="search" placeholder="Tìm mã đơn đặt phòng, mã đơn #..." value="<?= htmlspecialchars($search) ?>">
                </form>
            </div>
             
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Mã Đơn</th>
                            <th>Mã Đơn Đặt Phòng</th>
                            <th>Họ Tên</th>
                            <th>Số Phòng</th>
                            <th>Số Tiền</th>
                            <th>Nội Dung</th>
                            <th>Ngày Thanh Toán</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($payments)): ?>
                            <?php foreach ($payments as $row): ?>
                                <tr>
                                    <td><strong><?= $row['id'] ?></strong></td>
                                    <td><strong><?= $row['booking_id'] ?></strong></td>
                                    <td><strong><?= htmlspecialchars($row['fullname']) ?></strong><br></td>
                                    <td><strong><?= htmlspecialchars($row['room_number']) ?></strong><br></td>
                                    <td><strong><?= number_format($row['amount'] ?? 0) ?> VNĐ</strong><br></td>
                                    <td><strong><?= htmlspecialchars($row['content'] ?? 'N/A') ?></strong><br></td>
                                    <td><strong><?= $row['payment_date'] ? date("d/m/Y",strtotime($row['payment_date'])): "-" ?></strong><br></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="8" style="padding: 40px; text-align: center; color: #64748B;">Chưa có dữ liệu thanh toán.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
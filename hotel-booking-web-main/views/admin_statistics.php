<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Báo Cáo Thống Kê - Luxury Hotel Admin</title>
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
        
        /* SIDEBAR BÊN TRÁI */
        .sidebar { width: 260px; background-color: #0D163C; border-right: 1px solid #333; padding: 20px 0; display: flex; flex-direction: column; transition: 0.3s; }
        .sidebar a { display: flex; align-items: center; gap: 12px; padding: 15px 25px; color: #CBD5E1; text-decoration: none; font-size: 15px; transition: 0.2s; border-radius: 0 25px 25px 0; margin-right: 10px; }
        .sidebar a:hover { color: #fff; background-color: #26336D; }
        .sidebar a.active { color: #fff; background-color: #26336D; font-weight: 600; border-left: 4px solid #fff; }
        .sidebar .btn-logout { margin-top: auto; color: #DC3545; background: none; border: none; font-weight: bold; }
        .sidebar .btn-logout:hover { color: #fff; background-color: #DC3545; }

        /* NỘI DUNG BÊN PHẢI */
        .main-content { flex: 1; padding: 30px; overflow-y: auto; background-color: #F8FAFC; }
        
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; border-bottom: 1px solid #E2E8F0; padding-bottom: 15px; }
        .page-header h2 { font-size: 22px; font-weight: bold; color: #1E293B; }
        .header-actions { display: flex; gap: 10px; }

        /* Card chung cho Form và Table */
        .card { background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); padding: 20px; margin-bottom: 20px; }

        /* BỘ LỌC TÌM KIẾM */
        .filter-form { display: flex; gap: 20px; justify-content: center; align-items: flex-end; flex-wrap: wrap; }
        .form-group { display: flex; flex-direction: column; gap: 6px; }
        .form-group label { font-weight: 600; font-size: 13px; color: #64748B; }
        .form-group input, .form-group select { padding: 11px 15px; border: 1px solid #E2E8F0; border-radius: 6px; outline: none; font-size: 14px; min-width: 190px; }
        .form-group input:focus, .form-group select:focus { border-color: #0F5FF2; box-shadow: 0 0 0 3px rgba(15, 95, 242, 0.1); }
        .btn-report { padding: 11px 30px; background-color: #0F5FF2; color: white; border: none; border-radius: 6px; font-weight: bold; cursor: pointer; transition: 0.3s; font-size: 14px; }
        .btn-report:hover { background-color: #0A4CC7; }

        /* SUMMARY CARDS */
        .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 25px; }
        .stat-card { display: flex; align-items: center; gap: 20px; background: #fff; padding: 25px; border-radius: 8px; border: 1px solid #E2E8F0; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
        .stat-icon { font-size: 32px; width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; background-color: rgba(15, 95, 242, 0.1); color: #0F5FF2; }
        .card-revenue .stat-icon { background-color: rgba(40, 167, 69, 0.1); color: #28A745; }
        .card-bookings .stat-icon { background-color: rgba(255, 193, 7, 0.1); color: #FFC107; }
        .stat-info h4 { color: #64748B; font-size: 13px; margin-bottom: 5px; text-transform: uppercase; letter-spacing: 0.5px; }
        .stat-info .value { font-size: 26px; font-weight: bold; color: #1E293B; }

        /* BẢNG DỮ LIỆU CHUẨN HIỆN ĐẠI */
        .table-container { background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
        table { width: 100%; border-collapse: collapse; text-align: left; }
        th, td { padding: 16px; border-bottom: 1px solid #E2E8F0; font-size: 14px; }
        th { background-color: #F8FAFC; color: #64748B; font-weight: 600; text-transform: uppercase; font-size: 12px; }
        tbody tr:hover { background-color: #F1F5F9; }
        
        .badge { padding: 5px 10px; font-size: 12px; font-weight: bold; border-radius: 20px; text-transform: uppercase; }
        .status-pending { background-color: rgba(255, 128, 0, 0.1); color: #FF8000; }
        .status-confirmed { background-color: rgba(40, 167, 69, 0.1); color: #28A745; }
        .status-cancelled { background-color: rgba(220, 53, 69, 0.1); color: #DC3545; }
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
            <a href="admin_statistics.php" class="active"><i class="fas fa-chart-line"></i> Báo cáo thống kê</a>
            <a href="admin_bookings.php"><i class="fas fa-receipt"></i> Quản lý đặt phòng</a>
            <a href="admin_rooms.php"><i class="fas fa-bed"></i> Quản lý phòng</a>
            <a href="admin_users.php"><i class="fas fa-user-group"></i> Quản lý người dùng</a>
            <a href="admin_payments.php"><i class="fas fa-money-check-alt"></i> Quản lý thanh toán</a>
            <a href="admin_settings.php"><i class="fas fa-cogs"></i> Quản lý cấu hình hệ thống</a>
            <a href="index.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Quay lại trang chủ</a>
        </div>

        <div class="main-content">
            
            <div class="page-header">
                <h2>📊 Trung Tâm Thống Kê</h2>
                <div class="header-actions">
                    <button class="btn-report" onclick="window.print()"><i class="fas fa-print"></i> In báo cáo</button>
                </div>
            </div>

            <div class="card">
                <form action="admin_statistics.php" method="GET" class="filter-form">
                    <div class="form-group">
                        <label>Từ ngày</label>
                        <input type="date" name="from_date" value="<?= htmlspecialchars($from_date) ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Đến ngày</label>
                        <input type="date" name="to_date" value="<?= htmlspecialchars($to_date) ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Loại báo cáo</label>
                        <select name="report_type">
                            <option value="all" <?= $report_type == 'all' ? 'selected' : '' ?>>Tất cả đơn đặt phòng</option>
                            <option value="revenue" <?= $report_type == 'revenue' ? 'selected' : '' ?>>Chỉ đơn thành công (Doanh thu)</option>
                        </select>
                    </div>
                    <button type="submit" class="btn-report"><i class="fas fa-check-circle"></i> Xem báo cáo</button>
                </form>
            </div>

            <div class="stats-grid">
                <div class="stat-card card-revenue">
                    <div class="stat-icon"><i class="fas fa-dollar-sign"></i></div>
                    <div class="stat-info">
                        <h4>Doanh thu ước tính</h4>
                        <div class="value"><?= number_format($total_revenue) ?> VNĐ</div>
                    </div>
                </div>
                <div class="stat-card card-bookings">
                    <div class="stat-icon"><i class="fas fa-file-invoice"></i></div>
                    <div class="stat-info">
                        <h4>Tổng số lượng đơn</h4>
                        <div class="value"><?= $total_bookings ?> Đơn</div>
                    </div>
                </div> 
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-check"></i></div>
                    <div class="stat-info">
                        <h4>Đơn thành công</h4>
                        <div class="value"><?= $confirmed_bookings ?> Đơn</div>
                    </div>
                </div>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Mã Đơn</th>
                            <th>Ngày check-in</th>
                            <th>Khách hàng</th>
                            <th>Phòng</th>
                            <th>Doanh thu</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($results) > 0): ?>
                            <?php foreach ($results as $row): ?>
                                <tr>
                                    <td><strong>#<?= $row['id'] ?></strong></td>
                                    <td><?= date('d/m/Y', strtotime($row['check_in'])) ?></td>
                                    <td><?= htmlspecialchars($row['fullname'] ?? 'N/A') ?></td>
                                    <td>P. <?= htmlspecialchars($row['room_number']) ?> (<?= htmlspecialchars($row['room_type']) ?>)</td>
                                    <td style="font-weight: bold;">
                                        <?php
                                            if (in_array($row['status'], ['confirmed', 'checked_in', 'checked_out'])) {
                                                echo number_format($row['total_price']) . " VNĐ";
                                            } elseif ($row['status'] == 'cancelled') {
                                                echo number_format($row['cancel_fee']) . " VNĐ";
                                            } else {
                                                echo "0 VNĐ";
                                            }
                                        ?>
                                    </td>
                                    <td> 
                                        <?php 
                                            if ($row['status'] === 'pending') echo '<span class="badge status-pending">Chờ Duyệt</span>';
                                            elseif ($row['status'] === 'confirmed') echo '<span class="badge status-confirmed">Đã Duyệt</span>';  
                                            elseif ($row['status'] === 'checked_in') echo '<span class="badge status-confirmed">Đã Nhận Phòng</span>';
                                            elseif ($row['status'] === 'checked_out') echo '<span class="badge status-confirmed">Đã Trả Phòng</span>';                                                                                     
                                            else echo '<span class="badge status-cancelled">Đã Hủy</span>';
                                        ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" style="padding: 40px; text-align: center; color: #64748B; font-weight: 500;">
                                    <i class="fas fa-inbox" style="font-size: 30px; margin-bottom: 10px; display: block;"></i>
                                    Không có dữ liệu đơn hàng trong khoảng thời gian này.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</body>
</html>
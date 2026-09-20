<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản Lý Đặt Phòng - Luxury Hotel Admin</title>
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
        th { background-color: #F8FAFC; color: #64748B; font-weight: 600; text-transform: uppercase; font-size: 12px; white-space: nowrap; }
        tbody tr:hover { background-color: #F1F5F9; }
        
        .badge { padding: 5px 10px; font-size: 12px; font-weight: bold; border-radius: 20px; text-transform: uppercase;}
        .status-pending { background-color: rgba(255, 128, 0, 0.1); color: #FF8000; }
        .status-confirmed { background-color: rgba(40, 167, 69, 0.1); color: #28A745; }
        .status-checked-in { background-color: rgba(220, 53, 69, 0.1); color: #f894f0; }
        .status-checked-out { background-color: rgba(220, 53, 69, 0.1); color: #58650e; }
        .status-cancelled { background-color: rgba(220, 53, 69, 0.1); color: #DC3545; }
        .status-refunded { background-color: rgba(220, 53, 69, 0.1); color: #4635dc; }

        /* Modal Style */
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; justify-content: center; align-items: center; }
        .modal-content { background: #fff; padding: 30px; border-radius: 12px; width: 500px; max-height: 90vh; overflow-y: auto; }
        .close-btn { float: right; cursor: pointer; font-size: 24px; color: #666; }
        .close-btn:hover { color: #000; }
        
        /* NÚT HÀNH ĐỘNG CÓ ICON */
        .btn-action { padding: 8px 12px; border: none; border-radius: 6px; font-size: 12px; font-weight: bold; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; color: white; transition: 0.3s; }
        .btn-view { background-color: #2d2384; }
        .btn-view:hover { background-color: #282188; }
        .btn-approve { background-color: #28A745; }
        .btn-approve:hover { background-color: #218838; }
        .btn-checkin { background-color: #9a3b80; }
        .btn-checkin:hover { background-color: #933a86; }
        .btn-checkout { background-color: #8f9941; }
        .btn-checkout:hover { background-color: #535c25; }
        .btn-cancel { background-color: #DC3545; }
        .btn-cancel:hover { background-color: #C82333; }
        .btn-refund { background-color: #d12e82; }
        .btn-refund:hover { background-color: #c31d91; }
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
                <a href="admin_statistics.php">Báo cáo thống kê</a>
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
            <a href="admin_bookings.php" class="active"><i class="fas fa-receipt"></i> Quản lý đặt phòng</a>
            <a href="admin_rooms.php"><i class="fas fa-bed"></i> Quản lý phòng</a>
            <a href="admin_users.php"><i class="fas fa-user-group"></i> Quản lý người dùng</a>
            <a href="admin_payments.php"><i class="fas fa-money-check-alt"></i> Quản lý thanh toán</a>
            <a href="admin_settings.php"><i class="fas fa-cogs"></i> Quản lý cấu hình hệ thống</a>
            <a href="index.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Quay lại trang chủ</a>
        </div>

        <div class="main-content">
            
            <div class="page-header">
                <h2>🛎️ Quản Lý Đơn Đặt Phòng</h2>
            </div>
            
            <div class="search-card">
                <div style="font-size: 14px; color: #64748B;">Tổng số đơn: <strong><?= count($bookings) ?></strong></div>
                <form action="admin_bookings.php" method="GET" class="search-box">
                    <button type="submit" class="btn-search"><i class="fas fa-search"></i></button>
                    <input type="text" name="search" placeholder="Tìm tên khách, SĐT, mã đơn #..." value="<?= htmlspecialchars($search) ?>">
                </form>
            </div>
            
            <div id="noteModal" class="modal">
                <div class="modal-content">
                    <span class="close-btn" onclick="document.getElementById('noteModal').style.display='none'">&times;</span>
                    <h3>Yêu Cầu Khách Hàng</h3><br>
                    <div id="noteContent" 
                        style="width:100%; padding:10px; margin-bottom:10px; border: 1px solid #ccc; border-radius: 4px; height: 80px;">
                    </div>  
                </div>
            </div>

            <div id="cancelModal" class="modal"> 
                <div class="modal-content">
                    <span class="close-btn" onclick="document.getElementById('cancelModal').style.display='none'">&times;</span>
                    <h3>Nội dung hủy đơn</h3><br>
                    <label style="font-size: 13px; font-weight: bold;">Ngày hủy:</label>
                    <div id="cancelledAt" 
                        style="width:100%; padding:10px; margin-bottom:10px; border: 1px solid #ccc; border-radius: 4px;">
                    </div>
                    <label style="font-size: 13px; font-weight: bold;">Phí hủy:</label>
                    <div id="cancelFee" 
                        style="width:100%; padding:10px; margin-bottom:10px; border: 1px solid #ccc; border-radius: 4px;">
                    </div>
                    <label style="font-size: 13px; font-weight: bold;">Phí hoàn:</label>
                    <div id="refundAmount" 
                        style="width:100%; padding:10px; margin-bottom:10px; border: 1px solid #ccc; border-radius: 4px;">
                    </div>
                    <label style="font-size: 13px; font-weight: bold;">Lý do hủy:</label>
                    <div id="cancelReason" 
                        style="width:100%; padding:10px; margin-bottom:10px; border: 1px solid #ccc; border-radius: 4px; height: 80px;">
                    </div> 
                </div>
            </div>
            
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Mã Đơn</th>
                            <th>Khách Hàng</th>
                            <th>Phòng</th>
                            <th>Thời Gian Ở</th>
                            <th>Thanh toán</th>
                            <th>Yêu cầu</th>
                            <th>Nội dung hủy</th>
                            <th>Trạng Thái</th>
                            <th>Hành Động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($bookings)): ?>
                            <?php foreach ($bookings as $b): ?>
                                <tr>
                                    <td><strong>#<?= htmlspecialchars($b['id']) ?></strong></td>
                                    <td>
                                        <strong><?= htmlspecialchars($b['fullname'] ?? 'N/A') ?></strong><br>
                                        <span style="font-size: 12px; color: #64748B;"><?= htmlspecialchars($b['sdt'] ?? 'N/A') ?></span>
                                    </td>
                                    <td>
                                        Phòng <?= htmlspecialchars($b['room_number'] ?? 'N/A') ?><br>
                                        <span style="font-size: 12px; color: #0F5FF2; font-weight: 600;"><?= htmlspecialchars($b['room_type'] ?? 'N/A') ?></span>
                                    </td> 
                                    <td style="font-size: 13px;">
                                        In:<br>
                                        <?= date('d/m/Y', strtotime($b['check_in'])) ?><br>
                                        Out:<br> 
                                        <?= date('d/m/Y', strtotime($b['check_out'])) ?>
                                    </td>
                                    <td>
                                        Tổng tiền:<br>
                                        <span style=" color: #0F5FF2; font-weight: 600;"><?= number_format($b['total_price']) ?> VNĐ</span><br>
                                        Đã thanh toán: <br>
                                        <span style=" color: #0F5FF2; font-weight: 600;">                    
                                        <?php
                                            if ($b['payment_method'] == 'bank_transfer') {
                                                echo number_format($b['total_price']) . " VNĐ";
                                            } else {
                                                echo number_format($b['deposit_amount']) . " VNĐ";
                                            }
                                        ?>
                                        </span>
                                    </td>    
                                    <td>
                                        <?php if (!empty(trim($b['note']))): ?>
                                            <button class="btn-action btn-view" onclick="showNote('<?= htmlspecialchars($b['note'], ENT_QUOTES) ?>')">
                                                <i class="fas fa-eye"></i> Xem
                                            </button>
                                        <?php else: ?>
                                            <span style="color:#94A3B8;">Không có</span>
                                        <?php endif; ?>
                                    </td>   
                                    <td>
                                        <?php if (!empty(trim($b['cancel_reason']))): ?>
                                            <button 
                                                class="btn-action btn-view" 
                                                onclick="showCancelInfo(
                                                '<?= date('d/m/Y H:i', strtotime($b['cancelled_at'])) ?>',
                                                '<?= number_format($b['cancel_fee']) ?> VNĐ',
                                                '<?= number_format($b['refund_amount']) ?> VNĐ',
                                                '<?= htmlspecialchars($b['cancel_reason'], ENT_QUOTES) ?>'
                                            )">
                                                <i class="fas fa-eye"></i> Xem
                                            </button>
                                        <?php else: ?>
                                            <span style="color:#94A3B8;">Không có</span>
                                        <?php endif; ?>
                                    </td>   
                                    <td>
                                        <?php 
                                            $status = $b['status'] ?? 'pending';
                                            $refundStatus = $b['refund_status'] ?? 'pending';
                                            if ($status === 'pending') echo '<span class="badge status-pending">pending</span>';
                                            elseif ($status === 'confirmed') echo '<span class="badge status-confirmed">confirmed</span>';
                                            elseif ($status === 'checked_in') echo '<span class="badge status-checked-in">checked-in</span>';
                                            elseif ($status === 'checked_out') echo '<span class="badge status-checked-out">checked-out</span>';
                                            elseif ($status === 'cancelled') { 
                                                echo '<span class="badge status-cancelled">cancelled</span><br>';
                                                echo '<br>';
                                                if ($refundStatus === 'completed') {
                                                    echo '<span class="badge status-refunded">refunded</span>';
                                                    }
                                            }
                                        ?>
                                    </td>
                                    <td>
                                        <?php if ($status === 'pending'): ?>
                                            <a href="process_verify_booking.php?action=approve&booking_id=<?= $b['id'] ?>" class="btn-action btn-approve"><i class="fas fa-check"></i> Xác nhận</a>
                                            <a href="process_verify_booking.php?action=cancel&booking_id=<?= $b['id'] ?>" class="btn-action btn-cancel"><i class="fas fa-times"></i> Hủy</a>
                                        <?php elseif ($status === 'confirmed'): ?>
                                            <a href="process_verify_booking.php?action=checkin&booking_id=<?= $b['id'] ?>" class="btn-action btn-checkin"><i class="fas fa-sign-in-alt"></i> Đã nhận phòng</a>
                                            <a href="process_verify_booking.php?action=cancel&booking_id=<?= $b['id'] ?>" class="btn-action btn-cancel"><i class="fas fa-times"></i> Hủy</a>
                                        <?php elseif ($status === 'checked_in'): ?>   
                                            <a href="process_verify_booking.php?action=checkout&booking_id=<?= $b['id'] ?>" class="btn-action btn-checkout"><i class="fas fa-sign-out-alt"></i> Đã trả phòng</a>
                                        <?php elseif ($status === 'cancelled'): ?>   
                                            <?php if ($refundStatus === 'pending'): ?>
                                                <a href="process_verify_booking.php?action=refund&booking_id=<?= $b['id'] ?>" class="btn-action btn-refund"><i class="fas fa-money-bill-wave"></i> Đã hoàn tiền</a>
                                            <?php else: ?>
                                                <span style="color: #94A3B8; font-size: 13px;">Đã hoàn tất</span>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span style="color: #94A3B8; font-size: 13px;">Đã hoàn tất</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="8" style="padding: 40px; text-align: center; color: #64748B;">Không tìm thấy đơn đặt phòng nào.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        // Hàm đóng Modal khi click ra ngoài
        window.onclick = function(event) { 
            let nodeModal = document.getElementById('noteModal');
            let cancelModal = document.getElementById('cancelModal');
            if (event.target == noteModal) noteModal.style.display = "none";
            if (event.target == cancelModal) cancelModal.style.display = "none";
        }

        // Hàm mở noteModal
        function showNote(note) {
            document.getElementById('noteContent').textContent = note;          
            document.getElementById('noteModal').style.display = "flex";
        }

        // Hàm mở cancelModal
        function showCancelInfo(cancelledAt, cancelFee, refundAmount, cancelReason) {
            document.getElementById("cancelledAt").textContent = cancelledAt;
            document.getElementById("cancelFee").textContent = cancelFee;
            document.getElementById("refundAmount").textContent = refundAmount;
            document.getElementById("cancelReason").textContent = cancelReason;

            document.getElementById("cancelModal").style.display = "flex";
        }



    </script>
</body>
</html>
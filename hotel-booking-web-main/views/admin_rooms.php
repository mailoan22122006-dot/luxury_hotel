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
        
        .main-content { flex: 1; padding: 30px; overflow-y: auto; }
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }
        
        /* Nút thêm mới và thao tác */
        .btn-add-modal { background: #0F5FF2; color: white; padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; font-weight: bold; }
        .btn-action { padding: 8px 12px; border: none; border-radius: 6px; cursor: pointer; font-weight: bold; color: white; display: inline-flex; align-items: center; gap: 5px; }
        .btn-edit { background-color: #FFC107; color: #000; }
        .btn-edit:hover { background-color: #e0a800; }
        
        /* THANH TÌM KIẾM TRONG CARD */
        .search-card { background: #fff; padding: 15px; border-radius: 8px; border: 1px solid #E2E8F0; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; }
        .search-box { display: flex; align-items: center; gap: 10px; border: 1px solid #E2E8F0; padding: 6px 12px; border-radius: 6px; width: 350px; background-color: #fff; }
        .search-box input { flex: 1; padding: 8px; border: none; outline: none; font-size: 14px; color: #333; }
        .btn-search { background: none; border: none; color: #64748B; cursor: pointer; }
        .btn-search:hover { color: #0F5FF2; }
        
        /* Modal Style */
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; justify-content: center; align-items: center; }
        .modal-content { background: #fff; padding: 30px; border-radius: 12px; width: 500px; max-height: 90vh; overflow-y: auto; }
        .close-btn { float: right; cursor: pointer; font-size: 24px; color: #666; }
        .close-btn:hover { color: #000; }

        /* Bảng */
        .table-container { background: #FFFFFF; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 16px; border-bottom: 1px solid #E2E8F0; text-align: left; }
        .room-img { width: 80px; height: 50px; object-fit: cover; border-radius: 4px; border: 1px solid #ccc; }

        /* NÚT XÓA CÓ ICON */
        .btn-delete { padding: 8px 12px; background-color: #DC3545; color: white; text-decoration: none; border-radius: 6px; font-size: 12px; font-weight: bold; display: inline-flex; align-items: center; gap: 6px; transition: 0.3s; }
        .btn-delete:hover { background-color: #C82333; }
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
            <a href="admin_rooms.php" class="active"><i class="fas fa-bed"></i> Quản lý phòng</a>
            <a href="admin_users.php"><i class="fas fa-user-group"></i> Quản lý người dùng</a>
            <a href="admin_payments.php"><i class="fas fa-money-check-alt"></i> Quản lý thanh toán</a>
            <a href="admin_settings.php"><i class="fas fa-cogs"></i> Quản lý cấu hình hệ thống</a>
            <a href="index.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Quay lại trang chủ</a>
        </div>

        <div class="main-content">
            <div class="page-header">
                <h2>🛏️ Quản Lý Phòng</h2>
                <button class="btn-add-modal" onclick="document.getElementById('roomModal').style.display='flex'">
                    <i class="fas fa-plus"></i> Thêm Phòng Mới
                </button>
            </div>
            <div class="search-card">
                <div style="font-size: 14px; color: #64748B;">Tổng số phòng: <strong><?= count($rooms) ?></strong></div>
                <form action="admin_rooms.php" method="GET" class="search-box">
                    <button type="submit" class="btn-search"><i class="fas fa-search"></i></button>
                    <input type="text" name="search" placeholder="Tìm mã phòng #..." value="<?= htmlspecialchars($search) ?>">
                </form>
            </div>

            <div id="roomModal" class="modal">
                <div class="modal-content">
                    <span class="close-btn" onclick="document.getElementById('roomModal').style.display='none'">&times;</span>
                    <h3>Thêm Phòng Kinh Doanh</h3>
                    <form action="process_room.php" method="POST" enctype="multipart/form-data" style="margin-top: 20px;">
                        <input type="text" name="room_number" placeholder="Số phòng (VD: 401)" required style="width:100%; padding:10px; margin-bottom:10px; border: 1px solid #ccc; border-radius: 4px;">
                        <input type="text" name="room_type" placeholder="Loại (VIP, Standard)" required style="width:100%; padding:10px; margin-bottom:10px; border: 1px solid #ccc; border-radius: 4px;">
                        <input type="number" name="price" placeholder="Giá tiền/Đêm" required style="width:100%; padding:10px; margin-bottom:10px; border: 1px solid #ccc; border-radius: 4px;">
                        
                        <label style="font-size: 13px; font-weight: bold; margin-bottom: 5px; display: block;">Chọn ảnh phòng:</label>
                        <input type="file" name="room_image" accept="image/*" required style="width:100%; padding:8px; margin-bottom:10px; border: 1px solid #ccc; border-radius: 4px;">
                        
                        <textarea name="description" placeholder="Mô tả phòng..." style="width:100%; padding:10px; margin-bottom:10px; border: 1px solid #ccc; border-radius: 4px; height: 80px;"></textarea>
                        <textarea name="amenities" placeholder="Tiện nghi (Wifi, TV...)" style="width:100%; padding:10px; margin-bottom:20px; border: 1px solid #ccc; border-radius: 4px; height: 60px;"></textarea>
                        <button type="submit" name="action" value="add" style="width:100%; padding:12px; background: #28A745; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;">Lưu Phòng</button>
                    </form>
                </div>
            </div>

            <div id="editRoomModal" class="modal">
                <div class="modal-content">
                    <span class="close-btn" onclick="document.getElementById('editRoomModal').style.display='none'">&times;</span>
                    <h3>Sửa Thông Tin Phòng</h3>
                    <form action="process_room.php" method="POST" enctype="multipart/form-data" style="margin-top: 20px;">
                        <input type="hidden" name="action" value="edit">
                        <input type="hidden" name="id" id="edit_id">
                        
                        <label style="font-size: 13px; font-weight: bold;">Số phòng:</label>
                        <input type="text" name="room_number" id="edit_number" required style="width:100%; padding:10px; margin-bottom:10px; border: 1px solid #ccc; border-radius: 4px;">
                        
                        <label style="font-size: 13px; font-weight: bold;">Loại phòng:</label>
                        <input type="text" name="room_type" id="edit_type" required style="width:100%; padding:10px; margin-bottom:10px; border: 1px solid #ccc; border-radius: 4px;">
                        
                        <label style="font-size: 13px; font-weight: bold;">Giá tiền:</label>
                        <input type="number" name="price" id="edit_price" required style="width:100%; padding:10px; margin-bottom:10px; border: 1px solid #ccc; border-radius: 4px;">
                        
                        <label style="font-size: 13px; font-weight: bold; margin-bottom: 5px; display: block;">Thay ảnh mới (Bỏ trống nếu muốn giữ ảnh cũ):</label>
                        <input type="file" name="room_image" accept="image/*" style="width:100%; padding:8px; margin-bottom:10px; border: 1px solid #ccc; border-radius: 4px;">
                        
                        <label style="font-size: 13px; font-weight: bold;">Mô tả:</label>
                        <textarea name="description" id="edit_desc" style="width:100%; padding:10px; margin-bottom:10px; border: 1px solid #ccc; border-radius: 4px; height: 80px;"></textarea>
                        
                        <label style="font-size: 13px; font-weight: bold;">Tiện nghi:</label>
                        <textarea name="amenities" id="edit_amen" style="width:100%; padding:10px; margin-bottom:20px; border: 1px solid #ccc; border-radius: 4px; height: 60px;"></textarea>
                        
                        <button type="submit" style="width:100%; padding:12px; background: #FFC107; color: #000; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;">Cập Nhật</button>
                    </form>
                </div>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Ảnh</th>
                            <th>Mã Phòng</th>
                            <th>Loại</th>
                            <th>Giá</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($rooms)): ?>
                            <?php foreach ($rooms as $r): 
                                // Xử lý chống lỗi Javascript khi truyền chuỗi có dấu nháy hoặc xuống dòng
                                $safe_desc = htmlspecialchars(str_replace(["\r", "\n"], ' ', $r['description']), ENT_QUOTES);
                                $safe_amenities = htmlspecialchars(str_replace(["\r", "\n"], ' ', $r['amenities']), ENT_QUOTES);
                            ?>
                                <tr>
                                    <td><img src="<?= htmlspecialchars($r['img']) ?>" class="room-img" alt="Ảnh"></td>
                                    <td><strong><?= htmlspecialchars($r['room_number']) ?></strong></td>
                                    <td><?= htmlspecialchars($r['room_type']) ?></td>
                                    <td style="color: #0F5FF2; font-weight: bold;"><?= number_format($r['price']) ?> VNĐ</td>
                                    <td><?= htmlspecialchars($r['status']) ?></td>
                                    <td>
                                        <button class="btn-action btn-edit" onclick="openEditModal(<?= $r['id'] ?>, '<?= $r['room_number'] ?>', '<?= $r['room_type'] ?>', <?= $r['price'] ?>, '<?= $safe_desc ?>', '<?= $safe_amenities ?>')">
                                            <i class="fas fa-edit"></i> Sửa
                                        </button>
                                        <a href="process_delete_room.php?id=<?= $r['id'] ?>" class="btn-action btn-delete" onclick="return confirm('Bạn có chắc chắn muốn xóa phòng <?= htmlspecialchars($r['room_number']) ?>?');">
                                            <i class="fas fa-trash"></i> Xóa
                                        </a>
        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="6" style="text-align: center; padding: 20px;">Chưa có phòng nào.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Hàm đóng Modal khi click ra ngoài
        window.onclick = function(event) {
            let addModal = document.getElementById('roomModal');
            let editModal = document.getElementById('editRoomModal');
            if (event.target == addModal) addModal.style.display = "none";
            if (event.target == editModal) editModal.style.display = "none";
        }

        // Hàm mở Modal Sửa và tự động điền dữ liệu
        function openEditModal(id, number, type, price, desc, amen) {
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_number').value = number;
            document.getElementById('edit_type').value = type;
            document.getElementById('edit_price').value = price;
            document.getElementById('edit_desc').value = desc;
            document.getElementById('edit_amen').value = amen;
            
            document.getElementById('editRoomModal').style.display = 'flex';
        }
    </script>
</body>
</html>
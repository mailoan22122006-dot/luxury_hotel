<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title> Lịch sử đặt phòng - Luxury Hotel</title>
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
    
        .container { max-width: 1200px; padding: 40px 50px; margin:0 auto; display:grid; grid-template-columns:200px 1fr; column-gap:50px; align-items:flex-start; }
        .left-menu{ width:220px; background:#EEF6FF; border:1px solid #AACBFA; padding:0; }
        .left-menu a{ display:block; padding:14px 22px; color:#222; text-decoration:none; font-weight:bold; border-bottom:1px solid #d9e7ff; transition:.25s; }
        .left-menu a:hover{ background:#AACBFA; }
        .left-menu a.active{ color:#6399F7;/* chữ xanh */}
        
        .booking-link{ text-decoration:none; color:inherit; display:block; }
        .history-card{ background:#EEF6FF; border:1px solid #AACBFA; padding:15px; } 
        .history-card h3{ text-align:center; background:#AACBFA; padding:10px; margin-bottom:20px; border-radius:4px; } 
        
        .booking-card { display:flex; gap:25px; background:white; border:2px solid #AACBFA; border-radius:10px; padding:20px; margin-bottom:25px; transition:.3s; cursor:pointer; }
        .booking-card:hover { transform:translateY(-3px); box-shadow:0 8px 20px rgba(0,0,0,.12); }
        .room-image { width:220px;flex-shrink:0; }
        .room-image img { width:100%; height:150px; object-fit:cover; border-radius:8px; }
        .booking-info { flex:1; display:grid; grid-template-columns:1fr 1fr; column-gap:35px; row-gap:15px; }
        .booking-info h3{ text-align:center; background:#AACBFA; padding:12px; border-radius:8px; color:#333; margin-bottom:20px; }
        .booking-info p{ display:flex; justify-content:space-between; align-items:center; padding:12px 18px; margin-bottom:15px; border:1px solid #d9e5ff; border-radius:8px; background:#fff; }
        .booking-info strong{color:#333;}
        .empty{ text-align:center; font-size:22px; color:#777; padding:80px; border:2px dashed #AACBFA; background:white; border-radius:10px; }
        
        .status-pending{ color:#FF9800; font-weight:bold; }
        .status-confirmed{ color:#28A745; font-weight:bold; }
        .status-cancelled{ color:#DC3545; font-weight:bold; }
        @media(max-width:1200px){
            .container{ width:95%;}
            .booking-card{ flex-direction:column;}
            .room-image{width:100%;}
            .room-image img{height:250px;}
            .booking-info{grid-template-columns:1fr;}
            .booking-info h3{grid-column:auto;}
        }
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
            <a href="profile.php">Thông tin cá nhân</a>
            <a href="booking_history.php" class="active">Lịch sử đặt phòng</a> 
            <a href="views/guide/guide.php">Hướng dẫn sử dụng</a>
        </div>
        <div class="history-card">
            <?php if(!empty($history)): ?> 
                <?php foreach($history as $row): ?> 
                    <a class="booking-link" 
                        href="booking_detail.php?id=<?= $row['id'] ?>"> 
                        <div class="booking-card"> 
                            <div class="room-image"> 
                                <?php if(!empty($row['img'])): ?> 
                                    <img src="<?= htmlspecialchars($row['img']) ?>"> 
                                <?php else: ?> 
                                    <img src="images/no-image.png"> 
                                <?php endif; ?> 
                            </div> 
                            <div class="booking-info"> 
                                <p> 
                                    <strong>Loại phòng:</strong> 
                                    <?= htmlspecialchars($row['room_type']) ?> 
                                </p> 
                                <p> 
                                    <strong>Số phòng:</strong> 
                                    <?= htmlspecialchars($row['room_number']) ?> 
                                </p> 
                                <p> 
                                    <strong>Ngày nhận phòng:</strong> 
                                    <?= date("d/m/Y",strtotime($row['check_in'])) ?> 
                                </p> 
                                <p> 
                                    <strong>Ngày trả phòng:</strong> 
                                    <?= date("d/m/Y",strtotime($row['check_out'])) ?> 
                                </p> 
                                <p> 
                                    <strong>Tổng tiền:</strong> 
                                    <?= number_format($row['total_price']) ?> VNĐ 
                                </p> 
                                <p> 
                                    <strong>Trạng thái:</strong> 
                                    <?php switch($row['status']){ 
                                        case "pending": 
                                            echo "Chờ xác nhận"; 
                                            break; 
                                        case "confirmed": 
                                            echo "Đã xác nhận";
                                            break;
                                        case "checked_in": 
                                            echo "Đã nhận phòng";
                                            break;
                                        case "checked_out": 
                                            echo "Đã trả phòng";
                                            break; 
                                        case "cancelled": 
                                            echo "Đã hủy"; 
                                            break; 
                                    } 
                                    ?> 
                                </p> 
                            </div> 
                        </div> 
                    </a> 
                <?php endforeach; ?> 
            <?php else: ?> 
        <div class="empty"> 
            Bạn chưa có lịch sử đặt phòng. 
        </div> <?php endif; ?> 
    </div> 
</body> 
</html>
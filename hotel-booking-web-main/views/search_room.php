<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Dùng isset() để kiểm tra xem đã đăng nhập chưa trước khi lấy dữ liệu
$is_logged_in = isset($_SESSION['user_id']);
$user_display = isset($_SESSION['fullname']) ? $_SESSION['fullname'] : (isset($_SESSION['email']) ? $_SESSION['email'] : '');
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

        /* THANH TÌM KIẾM (Chỉ còn 3 ô + 1 nút) */
        .search-box { display: flex; background-color: #AACBFA; padding: 10px; border-radius: 10px; border: 2px solid #6399F7; gap: 10px; align-items: center; margin-bottom: 30px; }
        .search-box input, .search-box select { flex: 1; padding: 15px; border: none; border-radius: 5px; font-size: 14px; outline: none; background: #fff; font-weight: bold; color: #555; }
        .search-box button { padding: 15px 30px; background-color: #6399F7; color: white; border: none; border-radius: 5px; font-weight: bold; cursor: pointer; font-size: 15px; transition: 0.3s; }
        .search-box button:hover { background-color: #4A84E8; }

        /* DANH SÁCH PHÒNG */
        .section-title { font-size: 18px; font-weight: bold; margin-bottom: 20px; color: #000; }
        .room-grid { display: flex;  flex-direction: column; gap:20; overflow-y: auto; overflow-x: hidden;  max-height: 650px; }
        
        /* Tùy chỉnh thanh cuộn dọc */
        .room-grid::-webkit-scrollbar { width: 8px; }
        .room-grid::-webkit-scrollbar-thumb { background: #AACBFA; border-radius: 4px; }
        
        .room-card { display:flex; gap:25px; background:white; border:2px solid #AACBFA; border-radius:10px; padding:20px; margin-bottom:25px; transition:.3s; cursor:pointer; text-decoration: none; color: #333;}
        .room-card:hover { transform:translateY(-3px); box-shadow:0 8px 20px rgba(0,0,0,.12); }
        .room-image { width:600px;flex-shrink:0; }
        .room-image img { width:100%; height:250px; object-fit:cover; border-radius:8px; }
        .room-card .info { flex:1; display:grid; grid-template-columns:1fr 1fr; column-gap:35px; row-gap:15px; }
        .room-card .info h3{ text-align:center; background:#AACBFA; padding:12px; border-radius:8px; color:#333; margin-bottom:20px; }
        .room-card .info p{ display:flex; justify-content:space-between; align-items:center; padding:12px 18px; margin-bottom:15px; border:1px solid #d9e5ff; border-radius:8px; background:#fff; }
        .room-card .info strong{color:#333;}
        .empty{ text-align:center; font-size:22px; color:#777; padding:80px; border:2px dashed #AACBFA; background:white; border-radius:10px; }
        
        .status-pending{ color:#FF9800; font-weight:bold; }
        .status-confirmed{ color:#28A745; font-weight:bold; }
        .status-cancelled{ color:#DC3545; font-weight:bold; }
        @media(max-width:1200px){
            .container{ width:95%;}
            .room-card{ flex-direction:column;}
            .room-image{width:100%;}
            .room-image img{height:250px;}
            .room-card .info{grid-template-columns:1fr;}
            .room-card .info h3{grid-column:auto;}
        }

    </style>
</head>

<script>
const checkIn = document.getElementById("check_in");
const checkOut = document.getElementById("check_out");

function updateCheckoutMin() {

    if (!checkIn.value) return;

    let d = new Date(checkIn.value);
    d.setDate(d.getDate() + 1);

    let minDate = d.toISOString().split("T")[0];

    checkOut.min = minDate;

    // Nếu ngày trả hiện tại nhỏ hơn ngày tối thiểu thì xóa
    if (checkOut.value && checkOut.value < minDate) {
        checkOut.value = "";
    }
}

// Khi tải trang
updateCheckoutMin();

// Khi thay đổi ngày nhận
checkIn.addEventListener("change", updateCheckoutMin);
</script>

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
                    <div class="dropdown-content">
                        <a href="index.php">Trang chủ</a>
                        <a href="profile.php">Thông tin cá nhân</a>
                        <a href="booking_history.php">Lịch sử đặt phòng</a>
                        <a href="views/guide/guide.php">Hướng dẫn sử dụng</a>
                        <a href="logout.php" style="color: #DC3545;">Đăng xuất</a>
                    </div>
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
        <form action="search_room.php" method="GET" class="search-box">
            <input type="date" id="check_in" name="check_in" title="Ngày nhận phòng" value="<?= isset($_GET['check_in']) ? htmlspecialchars($_GET['check_in']) : '' ?>" min="<?= date('Y-m-d') ?>" required>
            <input type="date" id="check_out" name="check_out" title="Ngày trả phòng" value="<?= isset($_GET['check_out']) ? htmlspecialchars($_GET['check_out']) : '' ?>" min="<?= date('Y-m-d') ?>" required>
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
        <div class="section-title">Phòng có view đẹp, thích hợp cho lựa chọn của bạn</div>
            <?php if (!empty($rooms)): ?>
                <div class="room-grid">
                    <?php foreach ($rooms as $room): ?>
                    <a href="room_detail.php?id=<?= $room['id'] ?>" class="room-card"> 
                        <div class="room-image"> 
                            <img src="<?= htmlspecialchars($room['img']) ?>" alt="Phòng <?= htmlspecialchars($room['room_number']) ?>">
                        </div>
                        <div class="info">
                            <p> 
                                <strong>Phòng:</strong> 
                                <?= htmlspecialchars($room['room_number']) ?> 
                            </p> 
                            <p> 
                                <strong>Loại phòng:</strong> 
                                <?= htmlspecialchars($room['room_type']) ?> 
                            </p> 
                            <p> 
                                <strong>Giá tiền:</strong> 
                                <?= number_format($room['price']) ?> VNĐ/ĐÊM
                            </p>
                        </div>
                    </a>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p style="color: #DC3545; font-weight: bold; margin-bottom: 20px;">Không tìm thấy phòng trống nào phù hợp với điều kiện tìm kiếm của bạn.</p>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chi tiết đặt phòng - Luxury Hotel</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body { background-color: #F8FAFC; color: #1E293B; }

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
        
        .container { max-width: 1000px; margin: 40px auto; padding: 30px; background: #fff; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .booking-box { display: grid; grid-template-columns: 2fr 1fr; gap: 40px; align-items:stretch; }
        
        .left{ padding:25px; border-right:1px solid #d7e4fd;}
        .room-images { display: flex; flex-direction: column; gap: 15px; }
        .room-images img { width: 100%; height: 220px; object-fit: cover; border-radius: 8px; border: 1px solid #E2E8F0; }
        
        .right{ padding:25px; position:relative; padding-bottom:90px;}
        .action-form{ position:absolute; left:25px; right:25px; bottom:25px;}
        .info-group { margin-bottom: 20px; }
        .info-label { font-weight: 700; color: #64748B; font-size: 14px; margin-bottom: 5px; }
        .info-value { padding: 12px; background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 6px; font-size: 16px; color: #1E293B; min-height: 45px; }
        
        .btn-book { width: 100%; padding: 15px; background-color: #0F5FF2; color: white; border: none; border-radius: 8px; font-size: 16px; font-weight: bold; cursor: pointer; transition: 0.3s; }
        .btn-book:hover { background-color: #0A4CC7; }
    </style>
</head>
<body>

   <header>
        <h1>LUXURY HOTEL</h1>
            <div class="auth-box">
                <div class="user-dropdown">
                    <button class="dropbtn">
                        <img src="https://cdn-icons-png.flaticon.com/512/1144/1144709.png" alt="User" style="width: 35px; height: 35px; border-radius: 50%; background-color: #AACBFA; padding: 5px; object-fit: cover;">
                        <?= htmlspecialchars($user_fullname) ?>
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
    <div class="booking-box">
        <div class="left">
            <div class="room-images">
                <img src="<?= htmlspecialchars($booking['img']) ?>" alt="Ảnh phòng">
            </div>
            <br>

            <div class="info-group">
                <div class="info-label">Tên phòng:</div>
                <div class="info-value">Phòng <?= htmlspecialchars($booking['room_number']) ?></div>
            </div>
            
            <div class="info-group">
                <div class="info-label">Giá phòng:</div>
                <div class="info-value"><?= number_format($booking['price']) ?> VNĐ / Đêm</div>
            </div>
            
            <div class="info-group">
                <div class="info-label">Tiện nghi:</div>
                <div class="info-value"><?= nl2br(htmlspecialchars($booking['amenities'])) ?></div>
            </div>
            
            <div class="info-group">
                <div class="info-label">Mô tả chi tiết:</div>
                <div class="info-value" style="min-height: 100px;">
                    <?= nl2br(htmlspecialchars($booking['description'])) ?>
                </div>
            </div>
        </div>    
        <div class="right">
            <div class="info-group">
                <div class="info-label">Ngày nhận phòng:</div>
                <div class="info-value"><?= date("d/m/Y",strtotime($booking['check_in'])) ?></div>
            </div>

            <div class="info-group">
                <div class="info-label">Ngày trả phòng:</div>
                <div class="info-value"><?= date("d/m/Y",strtotime($booking['check_out'])) ?></div>
            </div>

            <div class="info-group">
                <div class="info-label">Trạng thái:</div>
                <div class="info-value">
                    <?php
                        switch($booking['status']){
                            case "pending":
                                echo "Chờ xác nhận";
                                break;
                            case "confirmed":
                                echo "Đã xác nhận";
                                break;
                            case "cancelled":
                                echo "Đã hủy";
                                break;
                            case "checked_in":
                                echo "Đã nhận phòng";
                                break;
                            case "checked_out":
                                echo "Đã trả phòng";
                                break;
                        }
                    ?>
                </div>
            </div>

            <div class="info-group">
                <div class="info-label">Tổng tiền:</div>
                <div class="info-value"><?= number_format($booking['total_price']) ?> VNĐ</div>
            </div>

            <div class="info-group">
                <div class="info-label">Tiền cọc:</div>
                <div class="info-value"><?= number_format($booking['deposit_amount']) ?> VNĐ</div>
            </div>

            <div class="info-group">
                <div class="info-label">Đã thanh toán:</div>
                <div class="info-value">
                    <?php
                        if ($booking['payment_method'] == 'bank_transfer') {
                            echo number_format($booking['total_price']) . " VNĐ";
                        } else {
                            echo number_format($booking['deposit_amount']) . " VNĐ";
                        }
                    ?>
                </div>
            </div>

            <?php if($booking['status'] == 'cancelled'): ?>
                <div class="info-group">
                    <div class="info-label">Ngày hủy:</div>
                    <div class="info-value"><?= date("d/m/Y", strtotime($booking['cancelled_at'])) ?></div>
                </div>

                <div class="info-group">
                    <div class="info-label">Phí hủy:</div>
                    <div class="info-value"><?= number_format($booking['cancel_fee']) ?> VNĐ</div>
                </div>
 
                <div class="info-group">
                    <div class="info-label">Số tiền hoàn lại:</div>
                    <div class="info-value"><?= number_format($booking['refund_amount']) ?> VNĐ</div>
                </div>

                <?php if ($booking['refund_amount'] > 0): ?>
                    <div class="info-group">
                        <div class="info-label">Trạng thái hoàn tiền:</div>
                        <div class="info-value">
                            <?php
                                if($booking['refund_status'] == 'pending'){
                                    echo "Chờ hoàn tiền";
                                }else{ 
                                    echo "Đã hoàn tiền";
                                }
                            ?>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="info-group">
                    <div class="info-label">Lý do hủy:</div>
                    <div class="info-value"><?= nl2br(htmlspecialchars($booking['cancel_reason'])) ?></div>
                </div>

            <?php endif; ?>
            
            <?php if(
                $booking['status']=="pending" ||
                $booking['status']=="confirmed"
                ): ?>
                
            <form action="cancel_booking.php?id=<?= $booking['id'] ?>" class="action-form">
                <input type="hidden" name="id" value="<?= $booking['id'] ?>">
                <button type="submit" class="btn-book">
                    HỦY ĐẶT PHÒNG
                </button>
            </form>

            <?php elseif ($booking['status'] == "cancelled" || $booking['status'] == "checked_out"): ?>
            <form action="booking.php" method="GET" class="action-form">
                <input type="hidden" name="id" value="<?= $booking['room_id'] ?>">
                <button type="submit" class="btn-book">
                    ĐẶT LẠI
                </button>
            </form>
            <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>
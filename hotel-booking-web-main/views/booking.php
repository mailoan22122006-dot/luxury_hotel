
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Hủy đặt phòng - Luxury Hotel</title>
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
        
        h2 { font-size: 40px; text-align: center; color: #000; padding: 25px 0 10px 0; font-weight: bold; }
        
        .booking-layout { display: flex; flex-wrap: wrap; }
        .room-preview { flex: 1; min-width: 350px; padding: 25px; }
        .room-preview img { width: 100%; height: 280px; object-fit: cover; border-radius: 8px; border: 1px solid #AACBFA; }
        
        .booking-form-section { flex: 1; min-width: 350px; padding: 25px; background-color: #EFF6FFF; border-left: 1px solid #AACBFA; }
        
        .room-meta { margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px dashed #6399F7; }
        .room-name { font-size: 20px; font-weight: bold; margin-bottom: 5px; }
        .room-price-label { color: #6399F7; font-weight: bold; font-size: 16px; }
        
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-weight: bold; margin-bottom: 5px; font-size: 14px; }
        .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 10px; border: 1px solid #AACBFA; border-radius: 5px; font-size: 15px; background-color: #FFFFFF; }
        
        .total-summary { background-color: #FFFFFF; border: 1px solid #6399F7; padding: 15px; border-radius: 5px; margin-top: 20px; }
        
        .btn-submit-booking { display: block; width: 100%; padding: 12px; background-color: #6399F7; color: #FFFFFF; border: none; border-radius: 5px; font-size: 15px; font-weight: bold; cursor: pointer; text-align: center; margin-top: 20px; transition: 0.3s; }
        .btn-submit-booking:hover { background-color: #AACBFA; color: #000; }
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
        <h2>THÔNG TIN ĐẶT PHÒNG</h2>
        
        <div class="booking-layout">
            <div class="room-preview">
                <img src="<?= htmlspecialchars($room['img']) ?>" alt="Ảnh phòng">
                <div style="margin-top: 15px; font-size: 14px; color: #555;">
                    <p><b>Quy định phòng:</b> Nhận phòng sau 14:00, Trả phòng trước 12:00 trưa ngày kế tiếp. Nghiêm cấm mang theo vật nuôi và chất gây cháy nổ.</p>
                </div>
            </div>
            
            <div class="booking-form-section">
                <div class="room-meta">
                    <div class="room-name">Phòng <?= htmlspecialchars($room['room_number']) ?></div>
                    <div class="room-type">Loại phòng: <b><?= htmlspecialchars($room['room_type']) ?></b></div>
                    <div class="room-price-label">Đơn giá: <span id="room-price" data-price="<?= $room['price'] ?>"><?= number_format($room['price']) ?></span> VNĐ / Đêm</div>
                </div>

                <form action="process_booking.php" method="POST">
                    <input type="hidden" name="room_id" value="<?= $room['id'] ?>">
                    <input type="hidden" name="price_per_night" value="<?= $room['price'] ?>">

                    <div class="form-group">
                        <label>Tên khách hàng đặt phòng</label>
                        <input type="text" value="<?= htmlspecialchars($_SESSION['fullname']) ?>" disabled style="background-color: #E6E6E6; cursor: not-allowed;">
                    </div>

                    <div class="form-group">
                        <label>Ngày nhận phòng (Check-in)</label>
                        <input type="date" name="check_in" id="check_in" min="<?= date('Y-m-d'); ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Ngày trả phòng (Check-out)</label>
                        <input type="date" name="check_out" id="check_out" required>
                    </div>

                    <div class="form-group">
                        <label>Yêu cầu đặc biệt (Không bắt buộc)</label>
                        <textarea name="note" rows="3" placeholder="Ví dụ: Lấy phòng tầng cao, thêm gối..."></textarea>
                    </div>

                    <div class="form-group">
                        <label>Phương thức thanh toán</label>
                        <select name="payment_method" id="payment_method" required onchange="toggleQR()">
                            <option value="">-- Chọn phương thức --</option>
                            <option value="cash">Thanh toán bằng tiền mặt tại quầy</option>
                            <option value="bank_transfer">Thanh toán online</option>
                        </select>
                    </div>

                    <div class="total-summary">
                        <div style="font-size: 14px;">Số ngày ở dự kiến: <strong id="lbl-days" style="color:#000;">0 ngày</strong></div>
                        <div style="font-size: 15px; margin-top: 5px;">Tổng tiền: <strong id="lbl-total" style="color: #6399F7; font-size: 18px;">0 VNĐ</strong></div>
                        <div style="font-size: 15px; margin-top: 5px;">Tiền cọc: <strong id="lbl-deposit" style="color: #6399F7; font-size: 18px;">0 VNĐ</strong></div>
                        <div style="font-size: 17px; margin-top: 5px;">Số tiền cần thanh toán: <strong id="lbl-payment" style="color:#0F5FF2; font-size:18px;">0 VNĐ</strong></div>
                    </div>

                    <button type="submit" class="btn-submit-booking">Xác Nhận Đặt Phòng</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        const txtCheckIn = document.getElementById('check_in');
        const txtCheckOut = document.getElementById('check_out');
        const lblDays = document.getElementById('lbl-days');
        const lblTotal = document.getElementById('lbl-total');
        const lblDeposit = document.getElementById('lbl-deposit');
        const lblPayment = document.getElementById('lbl-payment');
        const pricePerNight = parseFloat(document.getElementById('room-price').getAttribute('data-price'));

        function calculateTotalPrice() {
            const date1Str = txtCheckIn.value;
            const date2Str = txtCheckOut.value;

            if (date1Str && date2Str) {
                const d1 = new Date(date1Str);
                const d2 = new Date(date2Str);

                const timeDiff = d2.getTime() - d1.getTime();
                const days = Math.ceil(timeDiff / (1000 * 3600 * 24));

                if (days > 0) {
                    const total = days * pricePerNight;
                    const deposit = total * 0.3
                    
                    lblDays.innerText = days + ' đêm';
                    lblTotal.innerText = total.toLocaleString('vi-VN') + ' VNĐ';
                    lblDeposit.innerText = deposit.toLocaleString('vi-VN') + ' VNĐ';
                    
                    // Kiểm tra phương thức thanh toán
                    const paymentMethod = document.getElementById("payment_method").value;
                        if (paymentMethod === "bank_transfer") {
                            lblPayment.innerText = total.toLocaleString('vi-VN') + " VNĐ";
                        } else if (paymentMethod === "cash") {
                            lblPayment.innerText = deposit.toLocaleString('vi-VN') + " VNĐ";
                        } else {
                            lblPayment.innerText = "0 VNĐ";
                        }
                } else {
                    lblDays.innerText = '0 đêm';
                    lblTotal.innerText = '0 VNĐ (Ngày trả phải sau ngày nhận)';
                    lblDeposit.innerText ='0 VNĐ';
                }
            }
        }

        txtCheckIn.addEventListener('change', () => {
            txtCheckOut.min = txtCheckIn.value;
            calculateTotalPrice();
        });
        txtCheckOut.addEventListener('change', calculateTotalPrice);

        function toggleQR() {
            var method = document.getElementById("payment_method").value;
            var qrSection = document.getElementById("qr_section");
            calculateTotalPrice();
            if (method === "bank_transfer") {
                qrSection.style.display = "block";
            } else {
                qrSection.style.display = "none";
            }
        }
    </script>
</body>
</html>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Xác thực mã OTP - Luxury Hotel</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: Arial, sans-serif; }
        body { background-color: #F7F7F7; color: #000; font-size: 15px; display: flex; justify-content: center; align-items: center; min-height: 100vh; }
        .form-container { background-color: #FFFFFF; border: 2px solid #AACBFA; border-radius: 10px; width: 400px; padding: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); text-align: center; }
        h1 { font-size: 30px; color: #000; margin-bottom: 20px; font-weight: bold; }
        .msg { text-align: center; margin-bottom: 15px; padding: 10px; border-radius: 5px; }
        .msg.error { background-color: #ffcccc; color: red; }
        .msg.success { background-color: #ccffcc; color: green; }
        .info-msg { background-color: #EFF6FFF; padding: 15px; border-radius: 5px; margin-bottom: 15px; font-size: 14px; border: 1px solid #6399F7; }
        .input-group { margin-bottom: 20px; }
        .input-group input { width: 100%; padding: 12px; border: 1px solid #AACBFA; border-radius: 5px; font-size: 20px; text-align: center; letter-spacing: 5px; }
        .btn-submit { width: 100%; padding: 12px; background-color: #6399F7; color: #FFFFFF; border: none; border-radius: 5px; font-size: 15px; font-weight: bold; cursor: pointer; }
        .btn-submit:hover { background-color: #AACBFA; color: #000; }
        .resend-link { text-align: right; margin-bottom: 25px; }
        .resend-link a { color: #6399F7; text-decoration: none; font-size: 14px; }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>XÁC THỰC OTP</h1>
        
        <?php if (isset($_SESSION['mock_otp'])): ?>
            <div class="info-msg">
                Mã OTP giả lập hệ thống gửi về cho bạn là: <strong style="color: #6399F7; font-size: 18px;"><?= $_SESSION['mock_otp'] ?></strong>
            </div>
        <?php endif; ?>

        <?php if (!empty($msg)): ?>
            <div class="msg <?= $msg_type ?>">
                <?= htmlspecialchars($msg) ?>
            </div>
        <?php endif; ?>

        <form action="verify.php?purpose=<?= htmlspecialchars($purpose) ?>" method="POST">
            <div class="input-group">
                <label style="display:block; margin-bottom: 10px;">Nhập mã 6 chữ số gửi đến Email của bạn:</label>
                <input type="text" name="otp_code" maxlength="6" placeholder="******" required autocomplete="off">
            </div>
            <div class="resend-link">
                <a href="verify.php?action=resend&purpose=<?= htmlspecialchars($purpose) ?>">
                    Gửi lại mã OTP
                </a>
            </div>
            <button type="submit" class="btn-submit">Xác nhận</button>
        </form>
    </div>
</body>
</html>
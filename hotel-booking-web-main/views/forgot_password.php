<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quên Mật Khẩu - Luxury Hotel</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: Arial, sans-serif; }
        body { background-color: #F4F6F9; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .auth-container { background: #fff; padding: 40px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); width: 100%; max-width: 450px; border-top: 5px solid #6399F7; }
        .auth-container h2 { text-align: center; margin-bottom: 20px; color: #2C3E50; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-weight: bold; margin-bottom: 8px; color: #555; }
        .form-group input { width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 5px; font-size: 15px; }
        .btn-submit { width: 100%; padding: 12px; background-color: #6399F7; color: white; border: none; border-radius: 5px; font-size: 16px; font-weight: bold; cursor: pointer; transition: 0.3s; }
        .btn-submit:hover { background-color: #4A84E8; }
        .msg { padding: 10px; border-radius: 5px; margin-bottom: 20px; font-size: 14px; text-align: center; }
        .msg.error { background-color: #F8D7DA; color: #721C24; border: 1px solid #F5C6CB; }
        .msg.success { background-color: #D4EDDA; color: #155724; border: 1px solid #C3E6CB; }
        .back-link { display: block; text-align: center; margin-top: 15px; color: #6399F7; text-decoration: none; font-size: 14px; }
        .back-link:hover { text-decoration: underline; }
    </style>
</head>
<body>

<div class="auth-container">
    <h2>KHÔI PHỤC MẬT KHẨU</h2>
    
    <?php if (!empty($msg)): ?>
        <div class="msg <?= $msg_type ?>"><?= $msg ?></div>
    <?php endif; ?>

    <form method="POST" action="forgot_password.php">

    <div class="form-group">
        <label>Nhập Email đã đăng ký:</label>
        <input
            type="email"
            name="email"
            required
            placeholder="ví dụ: khachhang@gmail.com">
    </div>
    <button type="submit" class="btn-submit">
        Gửi Mã OTP
    </button>
    </form>
    
    <a href="login.php" class="back-link">Quay lại trang Đăng Nhập</a>
</div>

</body>
</html>
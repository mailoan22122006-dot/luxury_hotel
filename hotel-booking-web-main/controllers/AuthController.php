<?php
// controllers/AuthController.php

class AuthController {
    private $pdo;

    public function __construct($dbConnection) {
        $this->pdo = $dbConnection;
    }

    // 1. KIỂM TRA EMAIL
    public function checkEmail($email, $purpose) {
        try {
            $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':email' => $email]);

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Đăng ký
            if ($purpose == 'register') {
                if ($user) {
                    return [
                        'status' => false,
                        'message' => 'Email này đã được sử dụng!'
                    ];
                }
            }

            // Quên mật khẩu
            if ($purpose == 'forgot') {
                if (!$user) {
                    return [
                        'status' => false,
                        'message' => 'Email không tồn tại!'
                    ];
                }
            }

            return [
                'status' => true,
                'user' => $user
            ];
        } catch (PDOException $e) {
            return [
                'status' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    // 2. XỬ LÝ ĐĂNG KÝ
    public function register($fullname, $email, $sdt, $address, $password) {
        try {
            $check = $this->checkEmail($email, 'register');
            if (!$check['status']) {
                return $check;
            }
            
            // Kiểm tra số điện thoại
            if (!preg_match('/^[0-9]{10}$/', $sdt)) {
                return [
                    'status' => false,
                    'message' => 'Số điện thoại phải gồm đúng 10 chữ số.'
                ];
            }
            // Kiểm tra mật khẩu
            if (strlen($password) < 6) {
                return [
                    'status' => false,
                    'message' => 'Mật khẩu phải có ít nhất 6 ký tự.'
                ];
            }

            $passwordHash = password_hash($password, PASSWORD_BCRYPT);
            
            // Lưu thông tin đăng ký vào Session
            $_SESSION['register_data'] = [
                ':fullname' => $fullname,
                ':email' => $email,
                ':sdt' => $sdt,
                ':address' => $address,
                ':password' => $passwordHash
            ];

            return [
                'status' => true, 
                'email' => $email
            ];

        } catch (PDOException $e) {
            return [
                'status' => false, 
                'message' => $e->getMessage()
            ];
        }
    }

    // 3. GỬI OTP
    public function sendOTP($email) { 
        try { 
            
            // Sinh mã OTP ngẫu nhiên gồm 6 chữ số
            $otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
            require_once __DIR__ . '/../server/mail_server.php';
            
            $this->pdo->prepare("DELETE FROM otp_verifications WHERE email = :email AND verified = 0")->execute([':email'=>$email]);
            // Bắt MySQL tự lấy giờ hiện tại của nó cộng thêm 10 phút (Khắc phục lỗi lệch múi giờ)
            $otpSql = "INSERT INTO otp_verifications (email, otp_code, expires_at) 
                       VALUES (:email, :otp, DATE_ADD(NOW(), INTERVAL 10 MINUTE))";
            $otpStmt = $this->pdo->prepare($otpSql);
            $otpStmt->execute([
                ':email' => $email,
                ':otp' => $otp
            ]);

            if (!sendOTPEmail($email, $otp)) {
                return [
                    'status' => false,
                    'message' => 'Không gửi được email.'
                ];
            }        

            return [
                'status' => true,
                'otp' => $otp,
                'email' => $email
            ];

        } catch (PDOException $e) {
            return [
                'status' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    

    // 4. XỬ LÝ XÁC THỰC OTP
    public function verifyOTP($email, $otpInput, $purpose) {
        try {
            $sql = "SELECT * FROM otp_verifications 
                    WHERE email = :email AND otp_code = :otp AND verified = 0 AND expires_at > NOW() 
                    ORDER BY id DESC LIMIT 1";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':email' => $email, 
                ':otp' => $otpInput
            ]);
            $otpData = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$otpData) {    
                return [
                    'status' => false, 
                    'message' => 'OTP không hợp lệ hoặc đã hết hạn!',
                ];
            }
            
            // Đánh dấu OTP đã sử dụng
            $updateOtp = "UPDATE otp_verifications SET verified = 1 WHERE id = :id";
            $this->pdo->prepare($updateOtp)->execute([':id' => $otpData['id']]);
            
            // Nếu là đăng ký thì kích hoạt tài khoản
            if ($purpose == 'register') {
                if (!isset($_SESSION['register_data'])) {
                    return [
                        'status' => false,
                        'message' => 'Không tìm thấy thông tin đăng ký.'
                    ];
                }
                
                $data = $_SESSION['register_data'];

                $sql = "INSERT INTO users (fullname, email, sdt, address, password, is_verified)
                        VALUES (:fullname, :email, :sdt, :address, :password, 1)";

                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([
                    ':fullname' => $data[':fullname'],
                    ':email'    => $data[':email'],
                    ':sdt'      => $data[':sdt'],
                    ':address'  => $data[':address'],
                    ':password' => $data[':password']
                ]);

                unset($_SESSION['register_data']);
            }
            return [ 
                'status' => true, 
            ]; 
        } catch (PDOException $e) { 
            
            return [ 
                'status' => false, 
                'message' => $e->getMessage() 
            ]; 
        } 
    }
    
    // 5. GỬI LẠI OTP
    public function resendOTP($email) { 
        try {
        // Kiểm tra email hợp lệ 
        if (empty($email)) { 
            return [ 
                'status' => false, 
                'message' => 'Email không hợp lệ!' 
            ]; 
        }        
        // Gọi lại hàm gửi OTP
        return $this->sendOTP($email);
        } catch (PDOException $e) { 
            return [ 
                'status' => false, 
                'message' => $e->getMessage() 
            ]; 
        }
    }

    // 6. XỬ LÝ ĐĂNG NHẬP
    public function login($email, $password) {
        try {
            $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Kiểm tra $user tồn tại trước khi check password
            if ($user && password_verify($password, $user['password'])) {
                if (session_status() == PHP_SESSION_NONE) { session_start(); }
                
                // Lưu thông tin vào phiên làm việc (Session)
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['fullname'] = $user['fullname'];
                $_SESSION['email'] = $user['email'];
                
                // Lưu quyền truy cập
                $_SESSION['role'] = $user['role']; 
                
                return [
                    'status' => true,
                    'role' => $user['role']
                    ];
            }
            
            // Trả về câu thông báo nếu sai email hoặc mật khẩu
            return [
                'status' => false,
                'message' => 'Email hoặc mật khẩu không chính xác.'
            ];
            
        } catch (PDOException $e) { 
            return [ 
                'status' => false, 
                'message' => $e->getMessage() 
            ]; 
        }
    }

    // 7. XỬ LÝ ĐĂNG XUẤT
    public function logout() { 
        if (session_status() == PHP_SESSION_NONE) { 
            session_start(); 
        } 
        session_unset(); 
        session_destroy(); 
        return ['status' => true];
    }

    // 8. ĐẶT LẠI MẬT KHẨU
    public function resetPassword($email, $newPassword, $confirmPassword) {
        try {
            // Kiểm tra xác nhận mật khẩu 
            if ($newPassword !== $confirmPassword) { 
                return [ 
                    'status' => false, 
                    'message' => 'Mật khẩu xác nhận không khớp!' 
                ]; 
            }
            // Mã hóa mật khẩu 
            $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT); 
            // Cập nhật mật khẩu 
            $sql = "UPDATE users SET password = :password WHERE email = :email"; 
            $stmt = $this->pdo->prepare($sql); 
            $stmt->execute([ 
                ':password' => $hashedPassword, 
                ':email' => $email 
            ]); 
            
            return [ 
                'status' => true, 
                'message' => 'Đổi mật khẩu thành công!' 
            ];
        } catch (PDOException $e) { 
            return [ 
                'status' => false, 
                'message' => $e->getMessage() 
            ]; 
        } 
    }
}
?>
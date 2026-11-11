<?php
session_start();
require 'connect.php';

// กันเข้าหน้าโดยไม่ผ่าน OTP
if (!isset($_SESSION['otp_verified']) && !isset($_POST['ajax'])) {
    header("Location: forgot.php");
    exit();
}

$error = '';
$isAjax = isset($_POST['ajax']);

if (isset($_POST['reset'])) {
    $email = $_SESSION['reset_email'] ?? '';
    $password = trim($_POST['password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');

    // Validation
    if ($password === '' || $confirm_password === '') {
        $error = "❌ กรุณากรอกรหัสผ่านให้ครบ";
    } elseif (strlen($password) < 6) {
        $error = "❌ รหัสผ่านต้องมีอย่างน้อย 6 ตัว";
    } elseif ($password !== $confirm_password) {
        $error = "❌ รหัสผ่านไม่ตรงกัน";
    } else {
        $password = mysqli_real_escape_string($conn, $password);
        
        // อัปเดตรหัสผ่าน
        $update = mysqli_query($conn, "UPDATE users SET password='$password' WHERE email='$email'");
        
        if ($update) {
            // ลบ OTP
            mysqli_query($conn, "DELETE FROM otp_code WHERE email='$email'");
            
            // ล้าง session
            session_unset();
            session_destroy();
            
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'message' => '✅ เปลี่ยนรหัสผ่านสำเร็จ']);
                exit();
            } else {
                // Redirect ไปหน้า login
                header("Location: index.php?reset=success");
                exit();
            }
        } else {
            $error = "❌ เกิดข้อผิดพลาดในการอัปเดตรหัสผ่าน";
        }
    }

    if ($isAjax) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => $error]);
        exit();
    }
}
?>
?>

<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ตั้งรหัสผ่านใหม่</title>
<link rel="icon" type="image/png" href="../images_web/489401.png">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="../css/style.css">

<style>
body {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    margin: 0;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
}

.reset-container {
    width: 100%;
    max-width: 500px;
    padding: 50px 40px;
    background: #fff;
    border-radius: 25px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
}

.reset-header {
    text-align: center;
    margin-bottom: 30px;
}

.reset-header h1 {
    font-size: 28px;
    font-weight: 700;
    color: #1f2937;
    margin: 0 0 8px 0;
}

.reset-header p {
    color: #6b7280;
    font-size: 14px;
    margin: 0;
}

.form-group {
    margin-bottom: 24px;
    position: relative;
}

.form-group label {
    display: block;
    font-size: 14px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 8px;
}

.form-group input {
    width: 100%;
    padding: 14px 16px;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    font-size: 16px;
    outline: none;
    transition: all 0.3s;
}

.form-group input:focus {
    border-color: #667eea;
    background: #f0f4ff;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-group .eye-icon {
    position: absolute;
    right: 14px;
    top: 42px;
    cursor: pointer;
    color: #9ca3af;
    font-size: 18px;
}

.error-message {
    background: #fee;
    border: 1px solid #fcc;
    border-radius: 12px;
    padding: 14px 16px;
    margin-bottom: 24px;
    color: #c5192d;
    font-size: 13px;
}

.btn-save {
    width: 100%;
    padding: 16px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    border: none;
    border-radius: 12px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-save:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
}

.btn-save:active {
    transform: translateY(0);
}

.back-link {
    text-align: center;
    margin-top: 24px;
}

.back-link a {
    color: #667eea;
    text-decoration: none;
    font-size: 14px;
    font-weight: 500;
}

.back-link a:hover {
    text-decoration: underline;
}
</style>
</head>

<body>

<div class="reset-container">
    <div class="reset-header">
        <h1><i class="fas fa-lock"></i> ตั้งรหัสผ่านใหม่</h1>
        <p>กรุณากรอกรหัสผ่านใหม่ของคุณ</p>
    </div>

    <?php if ($error): ?>
        <div class="error-message"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="form-group">
            <label for="password"><i class="fas fa-key"></i> รหัสผ่านใหม่</label>
            <input type="password" id="password" name="password" placeholder="รหัสผ่านใหม่" required minlength="6">
            <i class="eye-icon fa-regular fa-eye-slash" onclick="togglePassword('password')"></i>
        </div>

        <div class="form-group">
            <label for="confirm_password"><i class="fas fa-check-circle"></i> ยืนยันรหัสผ่าน</label>
            <input type="password" id="confirm_password" name="confirm_password" placeholder="ยืนยันรหัสผ่าน" required>
            <i class="eye-icon fa-regular fa-eye-slash" onclick="togglePassword('confirm_password')"></i>
        </div>

        <button type="submit" name="reset" class="btn-save">
            <i class="fas fa-save"></i> บันทึก
        </button>
    </form>

    <div class="back-link">
        <a href="forgot.php"><i class="fas fa-arrow-left"></i> กลับไปลืมรหัสผ่าน</a>
    </div>
</div>

<script>
// Toggle password visibility
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = event.target;
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    } else {
        field.type = 'password';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    }
}
</script>

</body>
</html>

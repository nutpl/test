<?php
session_start();
require 'connect.php';

require __DIR__ . '/../PHPMailer/PHPMailer/src/Exception.php';
require __DIR__ . '/../PHPMailer/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/../PHPMailer/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$error = '';
$isAjax = isset($_POST['ajax']);

if (isset($_POST['send_otp'])) {

    $email = mysqli_real_escape_string($conn, $_POST['email']);

    $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");

    if (mysqli_num_rows($check) > 0) {

        $otp    = rand(100000, 999999);
        $expire = date("Y-m-d H:i:s", strtotime("+5 minutes"));

        mysqli_query($conn, "DELETE FROM otp_code WHERE email='$email'");
        mysqli_query($conn, "INSERT INTO otp_code (email, otp, expires_at)
                             VALUES ('$email','$otp','$expire')");

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'nutpl.ze.11@gmail.com';
            $mail->Password   = 'tfhjnjmsapevyuvd';
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            $mail->setFrom('nutpl.ze.11@gmail.com', 'OTP System');
            $mail->addAddress($email);

            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64';
            $mail->isHTML(true);

            $mail->Subject = 'รหัส OTP รีเซ็ตรหัสผ่าน';
            $mail->Body    = "<h3>OTP ของคุณคือ: $otp</h3>
                              <p>หมดอายุภายใน 5 นาที</p>";

            $mail->send();

            $_SESSION['reset_email'] = $email;

            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'message' => '✅ ส่งรหัส OTP สำเร็จ', 'redirect_to' => 'verify']);
                exit();
            } else {
                header("Location: verify.php");
                exit();
            }

        } catch (Exception $e) {
            $error = "❌ ส่งอีเมลไม่สำเร็จ: " . $mail->ErrorInfo;
        }

    } else {
        $error = "❌ ไม่พบอีเมลนี้ในระบบ";
    }

    if ($isAjax) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => $error]);
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ลืมรหัสผ่าน</title>
<link rel="icon" type="image/png" href="../images_web/489401.png">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="../css/style.css">

<style>
body {
    background: linear-gradient(120deg, #f4f7ff, #eef3ff, #f9f6ff);
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    margin: 0;
}

.forgot-container {
    width: 100%;
    max-width: 420px;
    padding: 40px 30px;
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
}

.forgot-header {
    text-align: center;
    margin-bottom: 30px;
}

.forgot-header h1 {
    font-size: 24px;
    font-weight: 700;
    color: #1f2937;
    margin: 0 0 8px 0;
}

.forgot-header p {
    color: #6b7280;
    font-size: 14px;
    margin: 0;
}

.form-group {
    margin-bottom: 20px;
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
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    font-size: 16px;
    outline: none;
    transition: all 0.3s;
}

.form-group input:focus {
    border-color: #6c63ff;
    background: #f8f7ff;
    box-shadow: 0 0 0 3px rgba(108, 99, 255, 0.1);
}

.btn-send {
    width: 100%;
    padding: 14px;
    background: #6c63ff;
    color: white;
    border: none;
    border-radius: 12px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-send:hover {
    background: #5a52d5;
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(108, 99, 255, 0.3);
}

.btn-send:active {
    transform: translateY(0);
}

.info-message {
    background: #ecfdf5;
    border-left: 4px solid #10b981;
    border-radius: 8px;
    padding: 12px 16px;
    margin-bottom: 24px;
    font-size: 13px;
    color: #065f46;
}

.back-link {
    text-align: center;
    margin-top: 20px;
}

.back-link a {
    color: #3b82f6;
    text-decoration: none;
    font-size: 14px;
    font-weight: 500;
}

.back-link a:hover {
    text-decoration: underline;
}

.error-message {
    background: #fee;
    border: 1px solid #fcc;
    border-radius: 8px;
    padding: 12px 16px;
    margin-bottom: 20px;
    color: #c5192d;
    font-size: 14px;
}
</style>
</head>

<body>

<div class="forgot-container">
    <div class="forgot-header">
        <h1><i class="fas fa-key"></i> ลืมรหัสผ่าน</h1>
        <p>ส่งรหัส OTP ไปยังอีเมลของคุณ</p>
    </div>

    <?php if (isset($error)): ?>
        <div class="error-message">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <div class="info-message">
        <i class="fas fa-info-circle"></i> กรุณากรอกอีเมลเพื่อรับรหัส OTP สำหรับรีเซ็ตรหัสผ่าน
    </div>

    <form method="post">
        <div class="form-group">
            <label for="email"><i class="fas fa-envelope"></i> อีเมล</label>
            <input type="email" id="email" name="email" placeholder="กรอกอีเมลของคุณ" required>
        </div>

        <button type="submit" name="send_otp" class="btn-send">
            <i class="fas fa-paper-plane"></i> ส่ง OTP
        </button>
    </form>

    <div class="back-link">
        <a href="index.php"><i class="fas fa-arrow-left"></i> กลับไปล็อกอิน</a>
    </div>
</div>

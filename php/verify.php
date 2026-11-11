<?php
session_start();
require 'connect.php';

if (!isset($_SESSION['reset_email']) && !isset($_POST['ajax'])) {
    header("Location: forgot.php");
    exit();
}

$error = '';
$isAjax = isset($_POST['ajax']);

if (isset($_POST['verify'])) {
    $email = $_SESSION['reset_email'] ?? '';
    
    // รวม OTP จากทั้ง 6 ช่อง
    $otp = '';
    for ($i = 1; $i <= 6; $i++) {
        $otp .= isset($_POST['otp' . $i]) ? mysqli_real_escape_string($conn, $_POST['otp' . $i]) : '';
    }

    if (strlen($otp) !== 6 || !ctype_digit($otp)) {
        $error = "❌ กรุณากรอกรหัส OTP ให้ครบทั้ง 6 ตัว";
    } else {
        $query = mysqli_query($conn, "SELECT * FROM otp_code WHERE email='$email' AND otp='$otp'");

        if ($row = mysqli_fetch_assoc($query)) {
            if (strtotime($row['expires_at']) >= time()) {
                $_SESSION['otp_verified'] = true;
                
                if ($isAjax) {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => true, 'message' => '✅ ยืนยัน OTP สำเร็จ']);
                    exit();
                } else {
                    header("Location: reset.php");
                    exit();
                }
            } else {
                $error = "❌ OTP หมดอายุแล้ว";
            }
        } else {
            $error = "❌ OTP ไม่ถูกต้อง";
        }
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
<title>ยืนยัน OTP</title>
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

.verify-container {
    width: 100%;
    max-width: 500px;
    padding: 50px 40px;
    background: #fff;
    border-radius: 25px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    text-align: center;
}

.verify-icon {
    font-size: 60px;
    margin-bottom: 24px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.verify-header h1 {
    font-size: 28px;
    font-weight: 700;
    color: #1f2937;
    margin: 0 0 16px 0;
}

.verify-header p {
    color: #6b7280;
    font-size: 14px;
    margin: 0 0 8px 0;
}

.email-display {
    color: #374151;
    font-weight: 600;
    font-size: 15px;
    margin-bottom: 30px;
    word-break: break-all;
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

.otp-inputs {
    display: flex;
    justify-content: center;
    gap: 10px;
    margin-bottom: 32px;
}

.otp-input {
    width: 50px;
    height: 50px;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    font-size: 24px;
    font-weight: 700;
    text-align: center;
    outline: none;
    transition: all 0.3s;
    color: #1f2937;
}

.otp-input:focus {
    border-color: #667eea;
    background: #f0f4ff;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.otp-input::-webkit-outer-spin-button,
.otp-input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

.btn-confirm {
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

.btn-confirm:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
}

.btn-confirm:active {
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

<div class="verify-container">
    <div class="verify-icon">
        <i class="fas fa-envelope"></i>
    </div>
    
    <div class="verify-header">
        <h1>Verify Your Email</h1>
        <p>กรอกรหัส OTP ที่ส่งไปยัง</p>
        <p class="email-display"><?php echo htmlspecialchars($_SESSION['reset_email']); ?></p>
    </div>

    <?php if ($error): ?>
        <div class="error-message"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="otp-inputs">
            <input type="text" class="otp-input" name="otp1" maxlength="1" inputmode="numeric" required autofocus>
            <input type="text" class="otp-input" name="otp2" maxlength="1" inputmode="numeric" required>
            <input type="text" class="otp-input" name="otp3" maxlength="1" inputmode="numeric" required>
            <input type="text" class="otp-input" name="otp4" maxlength="1" inputmode="numeric" required>
            <input type="text" class="otp-input" name="otp5" maxlength="1" inputmode="numeric" required>
            <input type="text" class="otp-input" name="otp6" maxlength="1" inputmode="numeric" required>
        </div>

        <button type="submit" name="verify" class="btn-confirm">Confirm</button>
    </form>

    <div class="back-link">
        <a href="forgot.php"><i class="fas fa-arrow-left"></i> Back to Forgot Password</a>
    </div>
</div>

<script>
// Auto move to next input when digit is entered
const inputs = document.querySelectorAll('.otp-input');

inputs.forEach((input, index) => {
    input.addEventListener('input', (e) => {
        // Only allow digits
        e.target.value = e.target.value.replace(/[^0-9]/g, '');
        
        if (e.target.value.length === 1 && index < inputs.length - 1) {
            inputs[index + 1].focus();
        }
    });

    input.addEventListener('keydown', (e) => {
        if (e.key === 'Backspace' && e.target.value === '' && index > 0) {
            inputs[index - 1].focus();
        }
    });
});
</script>

</body>
</html>

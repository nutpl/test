<?php
session_start();
$login_error = $_SESSION['login_error'] ?? '';
unset($_SESSION['login_error']);
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>หน้าแรก</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../images_web/489401.png">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">

    <style>
        * {
            box-sizing: border-box;
            font-family: system-ui, -apple-system, "Segoe UI", Roboto;
        }

        body {
            margin: 0;
        }

        /* Background options - apply class to <body> to change */
        body.page-bg-gamestart { background: url('../images_web/gamestart.jpg') center/cover no-repeat fixed; }
        body.page-bg-gen1 { background: url('../images_web/generated_bg1.svg') center/cover no-repeat fixed; }
        body.page-bg-gen2 { background: url('../images_web/generated_bg2.svg') center/cover no-repeat fixed; }

        /* ปรับ modal ให้ลอยออยู่ */
        .modal-dialog {
            max-width: 420px;
        }

        .modal-content {
            border-radius: 20px;
            border: none;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
        }

        .modal-header {
            border: none;
            padding: 30px 26px 0 26px;
            background: transparent;
        }

        .modal-title {
            font-weight: 600;
            font-size: 22px;
            width: 100%;
            text-align: center;
            color: #1f2937;
        }

        .btn-close {
            position: absolute;
            right: 20px;
            top: 15px;
        }

        .modal-body {
            padding: 16px 26px 34px 26px;
        }

        .form-field {
            margin-top: 16px;
            position: relative;
        }

        .form-control {
            width: 100%;
            padding: 16px 48px 16px 16px;
            border-radius: 14px;
            border: 1px solid #e5e5eb;
            background: #fafafa;
            font-size: 16px;
            outline: none;
        }

        .form-control:focus {
            border-color: #6c63ff;
            background: #fff;
            box-shadow: none;
        }

        .form-control.error {
            border: 1.5px solid #ff4d4f !important;
            background: #fff5f5;
        }

        .error-text {
            color: #ff4d4f;
            font-size: 14px;
            margin-top: 6px;
            display: none;
        }

        .eye {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 18px;
            color: #b5b5bd;
        }

        .modal-footer {
            border: none;
            padding: 0;
            flex-direction: column;
            align-items: center;
            gap: 0;
        }

        .btn-modal {
            width: 100%;
            margin-top: 24px;
            padding: 16px;
            border-radius: 16px;
            border: none;
            background: #e9e9ee;
            color: #b5b5bd;
            font-size: 16px;
            font-weight: 600;
            cursor: not-allowed;
            transition: 0.25s;
        }

        .btn-modal.active {
            background: #6c63ff;
            color: #fff;
            cursor: pointer;
        }

        .footer-link {
            display: flex;
            justify-content: space-between;
            margin-top: 18px;
            font-size: 14px;
            text-align: center;
            width: 100%;
        }

        .footer-link a, .switch-link {
            color: #3b82f6;
            text-decoration: none;
            cursor: pointer;
        }

        .footer-link a:hover, .switch-link:hover {
            text-decoration: underline;
        }

        .footer-text {
            flex: 1;
        }

        .switch-link {
            cursor: pointer;
            font-weight: 500;
        }

        .error-alert {
            background: #fff5f5;
            border: 1px solid #ff4d4f;
            border-radius: 8px;
            padding: 12px 16px;
            margin-bottom: 16px;
            color: #ff4d4f;
            font-size: 14px;
            display: none;
        }

        .error-alert.show {
            display: block;
        }

        .success-alert {
            background: #ecfdf5;
            border: 1px solid #10b981;
            border-radius: 8px;
            padding: 12px 16px;
            margin-bottom: 16px;
            color: #065f46;
            font-size: 14px;
            display: none;
        }

        .success-alert.show {
            display: block;
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
            border-color: #6c63ff;
            background: #f0f4ff;
            box-shadow: 0 0 0 3px rgba(108, 99, 255, 0.1);
        }

        .otp-input::-webkit-outer-spin-button,
        .otp-input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
    </style>
</head>

<body class="page-bg-gen1">

<!-- NAVBAR -->
<nav class="navbar">
    <div class="nav-links">
        <a href="">หน้าแรก</a>
        <a href="./start.php">เริ่มเล่น</a>
        <a href="">กฎการเล่น</a>
    </div>

    <div class="search-container">
        <input type="text" class="search-box" placeholder="ค้นหา">
    </div>

    <!-- ปุ่มเปิด Modal -->
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#loginModal">
        เข้าสู่ระบบ
    </button>
</nav>

<!-- ================= LOGIN MODAL ================= -->
<div class="modal fade" id="loginModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">เข้าสู่ระบบ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="error-alert" id="loginErrorAlert"></div>
                <form id="loginForm" action="process-login.php" method="POST">

                    <div class="form-field">
                        <input type="text" id="login-name" name="name" class="form-control" placeholder="ชื่อผู้ใช้">
                    </div>

                    <div class="form-field">
                        <input type="password" id="login-password" name="password" class="form-control" placeholder="รหัสผ่าน">
                        <span class="fa-regular fa-eye-slash eye" id="login-togglePassword"></span>
                    </div>

                    <button type="submit" id="login-btn" class="btn-modal" disabled>เข้าสู่ระบบ</button>
                </form>

                <div class="footer-link">
                    <div class="footer-text">
                        <a href="#" data-bs-toggle="modal" data-bs-target="#forgotModal" data-bs-dismiss="modal">ลืมรหัสผ่าน?</a>
                    </div>
                    <div class="footer-text">
                        ยังไม่มีบัญชี?
                        <span class="switch-link" data-bs-toggle="modal" data-bs-target="#registerModal" data-bs-dismiss="modal">
                            สมัครสมาชิก
                        </span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- ================= REGISTER MODAL ================= -->
<div class="modal fade" id="registerModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">สมัครสมาชิก</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form id="registerForm" action="process-register.php" method="POST">

                    <div class="form-field">
                        <input type="text" id="register-name" name="name" class="form-control" placeholder="ชื่อผู้ใช้">
                    </div>

                    <div class="form-field">
                        <input type="email" id="register-email" name="email" class="form-control" placeholder="อีเมล (ต้องเป็น @gmail.com)">
                        <div id="emailError" class="error-text">กรุณาใช้อีเมล @gmail.com เท่านั้น</div>
                    </div>

                    <div class="form-field">
                        <input type="password" id="register-password" name="password" class="form-control" placeholder="รหัสผ่าน (อย่างน้อย 6 ตัว)">
                        <span class="fa-regular fa-eye-slash eye" id="register-togglePassword"></span>
                        <div id="passwordLengthError" class="error-text">รหัสผ่านต้องมีอย่างน้อย 6 ตัว</div>
                    </div>

                    <div class="form-field">
                        <input type="password" id="register-confirmPassword" name="confirmPassword" class="form-control" placeholder="ยืนยันรหัสผ่าน">
                        <span class="fa-regular fa-eye-slash eye" id="register-toggleConfirm"></span>
                        <div id="passwordMatchError" class="error-text">รหัสผ่านไม่ตรงกัน</div>
                    </div>

                    <button type="submit" id="register-btn" class="btn-modal" disabled>สมัครสมาชิก</button>
                </form>

                <div class="text-center" style="margin-top: 18px; font-size: 14px;">
                    มีบัญชีอยู่แล้ว?
                    <span class="switch-link" data-bs-toggle="modal" data-bs-target="#loginModal" data-bs-dismiss="modal">
                        เข้าสู่ระบบ
                    </span>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- ================= FORGOT PASSWORD MODAL ================= -->
<div class="modal fade" id="forgotModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-key"></i> ลืมรหัสผ่าน</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="error-alert" id="forgotErrorAlert"></div>
                <div class="success-alert" id="forgotSuccessAlert" style="display: none; background: #ecfdf5; border: 1px solid #10b981; border-radius: 8px; padding: 12px 16px; margin-bottom: 16px; color: #065f46; font-size: 14px;"></div>

                <p style="color: #6b7280; font-size: 14px; margin-bottom: 20px;">
                    <i class="fas fa-info-circle"></i> กรุณากรอกอีเมลเพื่อรับรหัส OTP สำหรับรีเซ็ตรหัสผ่าน
                </p>

                <form id="forgotForm">
                    <div class="form-field">
                        <input type="email" id="forgot-email" name="email" class="form-control" placeholder="กรอกอีเมลของคุณ" required>
                    </div>

                    <button type="submit" id="forgot-btn" class="btn-modal active">
                        <i class="fas fa-paper-plane"></i> ส่ง OTP
                    </button>
                </form>

                <div style="text-align: center; margin-top: 18px; font-size: 14px;">
                    <a href="#" data-bs-toggle="modal" data-bs-target="#loginModal" data-bs-dismiss="modal" style="color: #3b82f6; text-decoration: none; font-weight: 500;">
                        <i class="fas fa-arrow-left"></i> กลับไปล็อกอิน
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- ================= VERIFY OTP MODAL ================= -->
<div class="modal fade" id="verifyModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-envelope"></i> ยืนยัน OTP</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="error-alert" id="verifyErrorAlert"></div>
                <div class="success-alert" id="verifySuccessAlert" style="display: none;"></div>

                <p style="color: #6b7280; font-size: 14px; margin-bottom: 20px;">
                    <i class="fas fa-info-circle"></i> กรอกรหัส OTP ที่ส่งไปยัง <strong id="verify-email-display"></strong>
                </p>

                <form id="verifyForm">
                    <div style="display: flex; justify-content: center; gap: 10px; margin-bottom: 24px;">
                        <input type="text" class="otp-input" name="otp1" maxlength="1" inputmode="numeric" required autofocus>
                        <input type="text" class="otp-input" name="otp2" maxlength="1" inputmode="numeric" required>
                        <input type="text" class="otp-input" name="otp3" maxlength="1" inputmode="numeric" required>
                        <input type="text" class="otp-input" name="otp4" maxlength="1" inputmode="numeric" required>
                        <input type="text" class="otp-input" name="otp5" maxlength="1" inputmode="numeric" required>
                        <input type="text" class="otp-input" name="otp6" maxlength="1" inputmode="numeric" required>
                    </div>

                    <button type="submit" id="verify-btn" class="btn-modal active">
                        <i class="fas fa-check-circle"></i> ยืนยัน OTP
                    </button>
                </form>

                <div style="text-align: center; margin-top: 18px; font-size: 14px;">
                    <a href="#" data-bs-toggle="modal" data-bs-target="#forgotModal" data-bs-dismiss="modal" style="color: #3b82f6; text-decoration: none; font-weight: 500;">
                        <i class="fas fa-arrow-left"></i> กลับไปลืมรหัสผ่าน
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- ================= RESET PASSWORD MODAL ================= -->
<div class="modal fade" id="resetModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-lock"></i> ตั้งรหัสผ่านใหม่</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="error-alert" id="resetErrorAlert"></div>
                <div class="success-alert" id="resetSuccessAlert" style="display: none;"></div>

                <form id="resetForm">
                    <div class="form-field">
                        <input type="password" id="reset-password" name="password" class="form-control" placeholder="รหัสผ่านใหม่" required minlength="6">
                        <span class="fa-regular fa-eye-slash eye" id="reset-togglePassword"></span>
                    </div>

                    <div class="form-field">
                        <input type="password" id="reset-confirmPassword" name="confirm_password" class="form-control" placeholder="ยืนยันรหัสผ่าน" required minlength="6">
                        <span class="fa-regular fa-eye-slash eye" id="reset-toggleConfirm"></span>
                    </div>

                    <button type="submit" id="reset-btn" class="btn-modal active">
                        <i class="fas fa-save"></i> บันทึก
                    </button>
                </form>

                <div style="text-align: center; margin-top: 18px; font-size: 14px;">
                    <a href="#" data-bs-toggle="modal" data-bs-target="#verifyModal" data-bs-dismiss="modal" style="color: #3b82f6; text-decoration: none; font-weight: 500;">
                        <i class="fas fa-arrow-left"></i> กลับไปยืนยัน OTP
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

<script>
// ===================== LOGIN FORM =====================
const loginName = document.getElementById('login-name');
const loginPassword = document.getElementById('login-password');
const loginBtn = document.getElementById('login-btn');
const loginTogglePassword = document.getElementById('login-togglePassword');

function checkLoginForm() {
    if (loginName.value.trim() !== '' && loginPassword.value.trim() !== '') {
        loginBtn.disabled = false;
        loginBtn.classList.add('active');
    } else {
        loginBtn.disabled = true;
        loginBtn.classList.remove('active');
    }
}

loginName.addEventListener('input', checkLoginForm);
loginPassword.addEventListener('input', checkLoginForm);

loginTogglePassword.addEventListener('click', () => {
    const show = loginPassword.type === 'password';
    loginPassword.type = show ? 'text' : 'password';
    loginTogglePassword.classList.toggle('fa-eye');
    loginTogglePassword.classList.toggle('fa-eye-slash');
});

// ===================== REGISTER FORM =====================
const registerName = document.getElementById('register-name');
const registerEmail = document.getElementById('register-email');
const registerPassword = document.getElementById('register-password');
const registerConfirmPassword = document.getElementById('register-confirmPassword');
const registerBtn = document.getElementById('register-btn');

const emailError = document.getElementById('emailError');
const passwordLengthError = document.getElementById('passwordLengthError');
const passwordMatchError = document.getElementById('passwordMatchError');

const registerTogglePassword = document.getElementById('register-togglePassword');
const registerToggleConfirm = document.getElementById('register-toggleConfirm');

function checkRegisterForm() {
    if (
        registerName.value.trim() &&
        registerEmail.value.trim() &&
        registerPassword.value.trim() &&
        registerConfirmPassword.value.trim()
    ) {
        registerBtn.disabled = false;
        registerBtn.classList.add('active');
    } else {
        registerBtn.disabled = true;
        registerBtn.classList.remove('active');
    }
}

registerEmail.addEventListener('blur', () => {
    if (registerEmail.value && !registerEmail.value.endsWith('@gmail.com')) {
        registerEmail.classList.add('error');
        emailError.style.display = 'block';
    } else {
        registerEmail.classList.remove('error');
        emailError.style.display = 'none';
    }
});

registerPassword.addEventListener('blur', () => {
    if (registerPassword.value && registerPassword.value.length < 6) {
        registerPassword.classList.add('error');
        passwordLengthError.style.display = 'block';
    } else {
        registerPassword.classList.remove('error');
        passwordLengthError.style.display = 'none';
    }
});

registerConfirmPassword.addEventListener('blur', () => {
    if (registerConfirmPassword.value && registerPassword.value !== registerConfirmPassword.value) {
        registerConfirmPassword.classList.add('error');
        passwordMatchError.style.display = 'block';
    } else {
        registerConfirmPassword.classList.remove('error');
        passwordMatchError.style.display = 'none';
    }
});

[registerName, registerEmail, registerPassword, registerConfirmPassword].forEach(el => {
    el.addEventListener('input', checkRegisterForm);
});

registerTogglePassword.addEventListener('click', () => {
    const show = registerPassword.type === 'password';
    registerPassword.type = show ? 'text' : 'password';
    registerTogglePassword.classList.toggle('fa-eye');
    registerTogglePassword.classList.toggle('fa-eye-slash');
});

registerToggleConfirm.addEventListener('click', () => {
    const show = registerConfirmPassword.type === 'password';
    registerConfirmPassword.type = show ? 'text' : 'password';
    registerToggleConfirm.classList.toggle('fa-eye');
    registerToggleConfirm.classList.toggle('fa-eye-slash');
});

// ===================== ERROR HANDLING =====================
const loginErrorAlert = document.getElementById('loginErrorAlert');
const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));

const errorMessage = '<?php echo $login_error; ?>';
if (errorMessage) {
    loginErrorAlert.textContent = errorMessage;
    loginErrorAlert.classList.add('show');
    loginModal.show();
}

// ===================== FORGOT PASSWORD FORM =====================
const forgotForm = document.getElementById('forgotForm');
const forgotEmail = document.getElementById('forgot-email');
const forgotBtn = document.getElementById('forgot-btn');
const forgotErrorAlert = document.getElementById('forgotErrorAlert');
const forgotSuccessAlert = document.getElementById('forgotSuccessAlert');
const forgotModal = new bootstrap.Modal(document.getElementById('forgotModal'));
const verifyModal = new bootstrap.Modal(document.getElementById('verifyModal'));

forgotForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    forgotErrorAlert.style.display = 'none';
    forgotSuccessAlert.style.display = 'none';
    forgotBtn.disabled = true;
    forgotBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> กำลังส่ง...';
    
    const formData = new FormData(forgotForm);
    formData.append('send_otp', '1');
    formData.append('ajax', '1');
    
    try {
        const response = await fetch('./forgot.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            forgotSuccessAlert.textContent = result.message;
            forgotSuccessAlert.style.display = 'block';
            forgotForm.reset();
            
            setTimeout(() => {
                forgotModal.hide();
                document.getElementById('verify-email-display').textContent = forgotEmail.value;
                verifyModal.show();
                resetOTPInputs();
            }, 1500);
        } else {
            forgotErrorAlert.textContent = result.message;
            forgotErrorAlert.style.display = 'block';
            forgotBtn.disabled = false;
            forgotBtn.innerHTML = '<i class="fas fa-paper-plane"></i> ส่ง OTP';
        }
    } catch (error) {
        forgotErrorAlert.textContent = '❌ เกิดข้อผิดพลาด: ' + error.message;
        forgotErrorAlert.style.display = 'block';
        forgotBtn.disabled = false;
        forgotBtn.innerHTML = '<i class="fas fa-paper-plane"></i> ส่ง OTP';
    }
});

// ===================== VERIFY OTP FORM =====================
const verifyForm = document.getElementById('verifyForm');
const verifyBtn = document.getElementById('verify-btn');
const verifyErrorAlert = document.getElementById('verifyErrorAlert');
const verifySuccessAlert = document.getElementById('verifySuccessAlert');
const resetModal = new bootstrap.Modal(document.getElementById('resetModal'));

function resetOTPInputs() {
    document.querySelectorAll('.otp-input').forEach(input => {
        input.value = '';
    });
    document.querySelector('.otp-input').focus();
}

// Auto-advance OTP inputs
document.querySelectorAll('.otp-input').forEach((input, index) => {
    input.addEventListener('input', (e) => {
        e.target.value = e.target.value.replace(/[^0-9]/g, '');
        if (e.target.value.length === 1 && index < 5) {
            document.querySelectorAll('.otp-input')[index + 1].focus();
        }
    });
    
    input.addEventListener('keydown', (e) => {
        if (e.key === 'Backspace' && e.target.value === '' && index > 0) {
            document.querySelectorAll('.otp-input')[index - 1].focus();
        }
    });
});

verifyForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    verifyErrorAlert.style.display = 'none';
    verifySuccessAlert.style.display = 'none';
    verifyBtn.disabled = true;
    verifyBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> กำลังยืนยัน...';
    
    const formData = new FormData();
    formData.append('verify', '1');
    formData.append('ajax', '1');
    for (let i = 1; i <= 6; i++) {
        formData.append('otp' + i, document.querySelector(`[name="otp${i}"]`).value);
    }
    
    try {
        const response = await fetch('./verify.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            verifySuccessAlert.textContent = result.message;
            verifySuccessAlert.style.display = 'block';
            
            setTimeout(() => {
                verifyModal.hide();
                resetModal.show();
            }, 1500);
        } else {
            verifyErrorAlert.textContent = result.message;
            verifyErrorAlert.style.display = 'block';
            verifyBtn.disabled = false;
            verifyBtn.innerHTML = '<i class="fas fa-check-circle"></i> ยืนยัน OTP';
        }
    } catch (error) {
        verifyErrorAlert.textContent = '❌ เกิดข้อผิดพลาด: ' + error.message;
        verifyErrorAlert.style.display = 'block';
        verifyBtn.disabled = false;
        verifyBtn.innerHTML = '<i class="fas fa-check-circle"></i> ยืนยัน OTP';
    }
});

// ===================== RESET PASSWORD FORM =====================
const resetForm = document.getElementById('resetForm');
const resetPassword = document.getElementById('reset-password');
const resetConfirmPassword = document.getElementById('reset-confirmPassword');
const resetBtn = document.getElementById('reset-btn');
const resetErrorAlert = document.getElementById('resetErrorAlert');
const resetSuccessAlert = document.getElementById('resetSuccessAlert');
const resetTogglePassword = document.getElementById('reset-togglePassword');
const resetToggleConfirm = document.getElementById('reset-toggleConfirm');

resetForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    resetErrorAlert.style.display = 'none';
    resetSuccessAlert.style.display = 'none';
    
    // Client-side validation
    if (resetPassword.value.length < 6) {
        resetErrorAlert.textContent = '❌ รหัสผ่านต้องมีอย่างน้อย 6 ตัว';
        resetErrorAlert.style.display = 'block';
        return;
    }
    
    if (resetPassword.value !== resetConfirmPassword.value) {
        resetErrorAlert.textContent = '❌ รหัสผ่านไม่ตรงกัน';
        resetErrorAlert.style.display = 'block';
        return;
    }
    
    resetBtn.disabled = true;
    resetBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> กำลังบันทึก...';
    
    const formData = new FormData();
    formData.append('reset', '1');
    formData.append('ajax', '1');
    formData.append('password', resetPassword.value);
    formData.append('confirm_password', resetConfirmPassword.value);
    
    try {
        const response = await fetch('./reset.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            resetSuccessAlert.textContent = result.message;
            resetSuccessAlert.style.display = 'block';
            
            setTimeout(() => {
                resetModal.hide();
                // Clear all modals
                forgotForm.reset();
                resetForm.reset();
                verifyForm.reset();
                loginModal.show();
                window.location.href = './index.php?reset=success';
            }, 1500);
        } else {
            resetErrorAlert.textContent = result.message;
            resetErrorAlert.style.display = 'block';
            resetBtn.disabled = false;
            resetBtn.innerHTML = '<i class="fas fa-save"></i> บันทึก';
        }
    } catch (error) {
        resetErrorAlert.textContent = '❌ เกิดข้อผิดพลาด: ' + error.message;
        resetErrorAlert.style.display = 'block';
        resetBtn.disabled = false;
        resetBtn.innerHTML = '<i class="fas fa-save"></i> บันทึก';
    }
});

resetTogglePassword.addEventListener('click', () => {
    const show = resetPassword.type === 'password';
    resetPassword.type = show ? 'text' : 'password';
    resetTogglePassword.classList.toggle('fa-eye');
    resetTogglePassword.classList.toggle('fa-eye-slash');
});

resetToggleConfirm.addEventListener('click', () => {
    const show = resetConfirmPassword.type === 'password';
    resetConfirmPassword.type = show ? 'text' : 'password';
    resetToggleConfirm.classList.toggle('fa-eye');
    resetToggleConfirm.classList.toggle('fa-eye-slash');
});

</script>

</body>
</html>
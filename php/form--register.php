<?php
session_start();
$error_message = $_SESSION['register_error'] ?? '';
unset($_SESSION['register_error']);
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>สมัครสมาชิก</title>
<link rel="icon" type="image/png" href="../images_web/489401.png">

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
* {
  box-sizing: border-box;
  font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

body {
  margin: 0;
  background: #ffffff;
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
}

.container {
  width: 100%;
  max-width: 420px;
  padding: 24px 20px 32px;
}

h1 {
  text-align: center;
  margin-bottom: 24px;
  font-size: 28px;
  font-weight: 700;
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

.field {
  margin-bottom: 14px;
  position: relative;
}

input {
  width: 100%;
  padding: 16px 44px 16px 16px;
  border-radius: 14px;
  border: 1px solid #e6e6eb;
  background: #fafafa;
  font-size: 16px;
  outline: none;
}

.error {
  border: 1.5px solid #ff4d4f !important;
  background: #fff5f5;
}

.error-text {
  color: #ff4d4f;
  font-size: 14px;
  margin-top: 6px;
  display: none;
}

button {
  width: 100%;
  margin-top: 18px;
  padding: 16px;
  border-radius: 16px;
  border: none;
  background: #e9e9ee;
  color: #b5b5bd;
  font-size: 16px;
  font-weight: 600;
  cursor: not-allowed;
}

button.active {
  background: #6c63ff;
  color: #ffffff;
  cursor: pointer;
}

.eye {
  position: absolute;
  right: 14px;
  top: 50%;
  transform: translateY(-50%);
  cursor: pointer;
  color: #b5b5bd;
}

.te{
  text-align: center;
  margin-top: 16px;
}
.te a{
  color: #3b82f6;
  text-decoration: none;
}
</style>
</head>

<body>
<div class="container">
  <h1>สมัครสมาชิก</h1>

  <div class="error-alert" id="errorAlert"></div>

  <!-- 🔗 ส่งข้อมูลไป PHP -->
  <form action="./process-register.php" method="POST" id="registerForm">

    <div class="field">
      <input type="text" name="name" id="name" placeholder="ชื่อผู้ใช้" required>
    </div>

    <div class="field">
      <input type="email" name="email" id="email" placeholder="อีเมล (ต้องเป็น @gmail.com)" required>
      <div id="emailError" class="error-text">กรุณาใช้อีเมล @gmail.com เท่านั้น</div>
    </div>

    <div class="field">
      <input type="password" name="password" id="password" placeholder="รหัสผ่าน (อย่างน้อย 6 ตัว)" required>
      <i id="togglePassword" class="fa-regular fa-eye-slash eye"></i>
      <div id="passwordLengthError" class="error-text">รหัสผ่านต้องมีอย่างน้อย 6 ตัว</div>
    </div>

    <div class="field">
      <input type="password" name="confirmPassword" id="confirmPassword" placeholder="ยืนยันรหัสผ่าน" required>
      <i id="toggleConfirm" class="fa-regular fa-eye-slash eye"></i>
      <div id="passwordMatchError" class="error-text">รหัสผ่านไม่ตรงกัน</div>
    </div>

    <button type="submit" id="registerBtn" disabled>สมัครสมาชิก</button>
  </form>

  <div class="te">มีบัญชีอยู่แล้ว? <a href="./form--login.php">เข้าสู่ระบบ</a></div>
</div>

<script>
const nameEl = document.getElementById('name');
const email = document.getElementById('email');
const password = document.getElementById('password');
const confirmPassword = document.getElementById('confirmPassword');
const registerBtn = document.getElementById('registerBtn');
const errorAlert = document.getElementById('errorAlert');

const emailError = document.getElementById('emailError');
const passwordLengthError = document.getElementById('passwordLengthError');
const passwordMatchError = document.getElementById('passwordMatchError');

const togglePassword = document.getElementById('togglePassword');
const toggleConfirm = document.getElementById('toggleConfirm');

// แสดง error message ถ้ามี
const errorMsg = '<?php echo $error_message; ?>';
if (errorMsg) {
  errorAlert.textContent = errorMsg;
  errorAlert.classList.add('show');
}

/* เปิดปุ่มเมื่อกรอกครบ */
function checkForm() {
  if (
    nameEl.value.trim() &&
    email.value.trim() &&
    password.value.trim() &&
    confirmPassword.value.trim()
  ) {
    registerBtn.disabled = false;
    registerBtn.classList.add('active');
  } else {
    registerBtn.disabled = true;
    registerBtn.classList.remove('active');
  }
}

/* เตือนหลังกรอกเสร็จ */
email.addEventListener('blur', () => {
  if (email.value && !email.value.endsWith('@gmail.com')) {
    email.classList.add('error');
    emailError.style.display = 'block';
  } else {
    email.classList.remove('error');
    emailError.style.display = 'none';
  }
});

password.addEventListener('blur', () => {
  if (password.value && password.value.length < 6) {
    password.classList.add('error');
    passwordLengthError.style.display = 'block';
  } else {
    password.classList.remove('error');
    passwordLengthError.style.display = 'none';
  }
});

confirmPassword.addEventListener('blur', () => {
  if (confirmPassword.value && password.value !== confirmPassword.value) {
    confirmPassword.classList.add('error');
    passwordMatchError.style.display = 'block';
  } else {
    confirmPassword.classList.remove('error');
    passwordMatchError.style.display = 'none';
  }
});

/* เช็กความครบ */
[nameEl, email, password, confirmPassword].forEach(el => {
  el.addEventListener('input', checkForm);
});

/* รูปตา */
togglePassword.onclick = () => {
  const show = password.type === 'password';
      password.type = show ? 'text' : 'password';
      togglePassword.classList.toggle('fa-eye');
      togglePassword.classList.toggle('fa-eye-slash');
};

toggleConfirm.onclick = () => {
  const show = confirmPassword.type === 'password';
      confirmPassword.type = show ? 'text' : 'password';
      toggleConfirm.classList.toggle('fa-eye');
      toggleConfirm.classList.toggle('fa-eye-slash');
};
</script>
</body>
</html>

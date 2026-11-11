<?php
session_start();
$error_message = $_SESSION['login_error'] ?? '';
$success_message = $_SESSION['register_success'] ?? '';
unset($_SESSION['login_error']);
unset($_SESSION['register_success']);
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login</title>
<link rel="icon" type="image/png" href="../images_web/489401.png">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
*{
  box-sizing:border-box;
  font-family: system-ui, -apple-system, "Segoe UI", Roboto;
}

body{
  margin:0;
  background:#ffffff;
  display:flex;
  justify-content:center;
  align-items:center;
  height:100vh;
}

.modal{
  width:420px;
  padding:30px 26px 34px;
  background:#fff;
  border-radius:20px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
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
  background: #f6ffed;
  border: 1px solid #52c41a;
  border-radius: 8px;
  padding: 12px 16px;
  margin-bottom: 16px;
  color: #52c41a;
  font-size: 14px;
  display: none;
}

.success-alert.show {
  display: block;
}

.header{
  text-align:center;
  margin-bottom:22px;
}

.logo{
  font-weight:700;
  letter-spacing:3px;
  font-size:20px;
}

.title{
  margin-top:16px;
  font-size:22px;
  font-weight:600;
}

.field{
  margin-top:16px;
  position:relative;
}

input{
  width:100%;
  padding:16px 48px 16px 16px;
  border-radius:14px;
  border:1px solid #e5e5eb;
  background:#fafafa;
  font-size:16px;
  outline:none;
}

input:focus{
  border-color:#6c63ff;
  background:#fff;
}

.eye{
  position:absolute;
  right:16px;
  top:50%;
  transform:translateY(-50%);
  cursor:pointer;
  font-size:18px;
  color:#b5b5bd;
}

button{
  width:100%;
  margin-top:24px;
  padding:16px;
  border-radius:16px;
  border:none;
  background:#e9e9ee;
  color:#b5b5bd;
  font-size:16px;
  font-weight:600;
  cursor:not-allowed;
  transition:0.25s;
}

button.active{
  background:#6c63ff;
  color:#fff;
  cursor:pointer;
}

.footer{
  display:flex;
  justify-content:space-between;
  margin-top:18px;
  font-size:14px;
}

.footer a{
  color:#3b82f6;
  text-decoration:none;
}
</style>
</head>

<body>

<div class="modal">

  <div class="header">
    <div class="title">ล็อกอินบัญชี</div>
  </div>

  <div class="error-alert" id="errorAlert"></div>
  <div class="success-alert" id="successAlert"></div>

  <form action="./process-login.php" method="post">

    <div class="field">
      <input type="text" id="name" name="name" placeholder="ชื่อผู้ใช้">
    </div>

    <div class="field">
      <input type="password" id="password" name="password" placeholder="รหัสผ่าน">
      <span class="fa-regular fa-eye-slash eye" id="togglePassword"></span>
    </div>

    <button type="submit" id="loginBtn" name="login" disabled>เข้าสู่ระบบ</button>

  </form>

  <div class="footer">
    <a href="./forgot.php">ลืมรหัสผ่าน?</a>
    <a href="./form--register.php">ลงทะเบียน</a>
  </div>

</div>

<script>
const email = document.getElementById('name');
const password = document.getElementById('password');
const loginBtn = document.getElementById('loginBtn');
const togglePassword = document.getElementById('togglePassword');
const errorAlert = document.getElementById('errorAlert');
const successAlert = document.getElementById('successAlert');

// แสดง error message ถ้ามี
const errorMsg = '<?php echo $error_message; ?>';
if (errorMsg) {
  errorAlert.textContent = errorMsg;
  errorAlert.classList.add('show');
}

// แสดง success message ถ้ามี
const successMsg = '<?php echo $success_message; ?>';
if (successMsg) {
  successAlert.textContent = successMsg;
  successAlert.classList.add('show');
}

function checkForm(){
  if(email.value.trim() !== '' && password.value.trim() !== ''){
    loginBtn.disabled = false;
    loginBtn.classList.add('active');
  }else{
    loginBtn.disabled = true;
    loginBtn.classList.remove('active');
  }
}

email.addEventListener('input', checkForm);
password.addEventListener('input', checkForm);

togglePassword.addEventListener('click', ()=>{
  const show = password.type === 'password';
      password.type = show ? 'text' : 'password';
      togglePassword.classList.toggle('fa-eye');
      togglePassword.classList.toggle('fa-eye-slash');

});
</script>

</body>
</html>

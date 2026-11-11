<!DOCTYPE html>

<html lang="th">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>สมัครสมาชิก</title>

  <!-- Font Awesome -->

  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
  />

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

    .field {
      margin-bottom: 14px;
      position: relative;
    }

    input {
      width: 100%;
      padding: 16px;
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
      right: 16px;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
      color: #b5b5bd;
      font-size: 18px;
    }
  </style>

</head>

<body>
  <div class="container">
    <h1>สมัครสมาชิก</h1>

```
<div class="field">
  <input type="text" id="name" placeholder="ชื่อผู้ใช้" />
</div>

<div class="field">
  <input type="email" id="email" placeholder="อีเมล (ต้องเป็น @gmail.com)" />
  <div id="emailError" class="error-text">กรุณาใช้อีเมล @gmail.com เท่านั้น</div>
</div>

<div class="field">
  <input type="password" id="password" placeholder="รหัสผ่าน (อย่างน้อย 8 ตัว)" />
  <i id="togglePassword" class="fa-regular fa-eye eye"></i>
  <div id="lengthError" class="error-text">รหัสผ่านต้องมีอย่างน้อย 8 ตัวอักษร</div>
</div>

<div class="field">
  <input type="password" id="confirmPassword" placeholder="ยืนยันรหัสผ่าน" />
  <i id="toggleConfirm" class="fa-regular fa-eye eye"></i>
  <div id="passwordError" class="error-text">รหัสผ่านไม่ตรงกัน</div>
</div>

<button id="registerBtn" disabled>สมัครสมาชิก</button>
```

  </div>

  <script>
    const nameInput = document.getElementById('name');
    const email = document.getElementById('email');
    const emailError = document.getElementById('emailError');

    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirmPassword');
    const passwordError = document.getElementById('passwordError');
    const lengthError = document.getElementById('lengthError');

    const registerBtn = document.getElementById('registerBtn');
    const togglePassword = document.getElementById('togglePassword');
    const toggleConfirm = document.getElementById('toggleConfirm');

    function checkForm() {
      const filled =
        nameInput.value.trim() &&
        email.value.trim() &&
        password.value.trim() &&
        confirmPassword.value.trim();

      registerBtn.disabled = !filled;
      registerBtn.classList.toggle('active', filled);
    }

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
      if (password.value && password.value.length < 8) {
        password.classList.add('error');
        lengthError.style.display = 'block';
      } else {
        password.classList.remove('error');
        lengthError.style.display = 'none';
      }
    });

    confirmPassword.addEventListener('blur', () => {
      if (confirmPassword.value && password.value !== confirmPassword.value) {
        confirmPassword.classList.add('error');
        passwordError.style.display = 'block';
      } else {
        confirmPassword.classList.remove('error');
        passwordError.style.display = 'none';
      }
    });

    [nameInput, email, password, confirmPassword].forEach(el =>
      el.addEventListener('input', checkForm)
    );

    togglePassword.addEventListener('click', () => {
      const show = password.type === 'password';
      password.type = show ? 'text' : 'password';
      togglePassword.classList.toggle('fa-eye');
      togglePassword.classList.toggle('fa-eye-slash');
    });

    toggleConfirm.addEventListener('click', () => {
      const show = confirmPassword.type === 'password';
      confirmPassword.type = show ? 'text' : 'password';
      toggleConfirm.classList.toggle('fa-eye');
      toggleConfirm.classList.toggle('fa-eye-slash');
    });

    registerBtn.onclick = () => alert('สมัครสมาชิกสำเร็จ 🎉');
  </script>

</body>
</html>

<?php
require 'connect.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
  header("Location: register.html");
  exit;
}

/* =====================
   รับค่าจากฟอร์ม
===================== */
$name     = trim($_POST['name'] ?? '');
$email    = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$confirm  = $_POST['confirmPassword'] ?? '';

/* =====================
   ตรวจสอบข้อมูล (Backend)
===================== */

// ตรวจกรอกครบ
if ($name === '' || $email === '' || $password === '' || $confirm === '') {
  echo "<script>alert('กรุณากรอกข้อมูลให้ครบ');history.back();</script>";
  exit;
}

// ตรวจอีเมลรูปแบบ
if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !str_ends_with($email, '@gmail.com')) {
  echo "<script>alert('อีเมลต้องเป็น @gmail.com เท่านั้น');history.back();</script>";
  exit;
}

// ตรวจรหัสผ่านขั้นต่ำ
if (strlen($password) < 6) {
  echo "<script>alert('รหัสผ่านต้องมีอย่างน้อย 6 ตัว');history.back();</script>";
  exit;
}

// ตรวจรหัสผ่านตรงกัน
if ($password !== $confirm) {
  echo "<script>alert('รหัสผ่านไม่ตรงกัน');history.back();</script>";
  exit;
}

/* =====================
   ตรวจสอบสมัครซ้ำ
===================== */
$check = $conn->prepare("SELECT id FROM account WHERE email = ?");
$check->bind_param("s", $email);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
  echo "<script>alert('อีเมลนี้ถูกสมัครไปแล้ว');history.back();</script>";
  exit;
}

/* =====================
   เข้ารหัสรหัสผ่าน
===================== */
// $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

/* =====================
   บันทึกข้อมูล
===================== */
$stmt = $conn->prepare(
  "INSERT INTO account (username, email, password)
   VALUES (?, ?, ?)"
);
$stmt->bind_param("sss", $name, $email, $password);

if ($stmt->execute()) {
  echo "<script>
    alert('สมัครสมาชิกสำเร็จ 🎉');
    window.location.href = 'form--login.php';
  </script>";
} else {
  echo "<script>alert('เกิดข้อผิดพลาด กรุณาลองใหม่');history.back();</script>";
}

/* =====================
   ปิดการเชื่อมต่อ
===================== */
$stmt->close();
$check->close();
$conn->close();
?>

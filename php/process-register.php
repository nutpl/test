<?php
session_start();
require 'connect.php';

$name     = trim($_POST['name'] ?? '');
$email    = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$confirm  = $_POST['confirmPassword'] ?? '';

if ($name === '' || $email === '' || $password === '' || $confirm === '') {
  $_SESSION['register_error'] = '❌ กรุณากรอกข้อมูลให้ครบ';
  header('Location: form--register.php');
  exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !str_ends_with($email, '@gmail.com')) {
  $_SESSION['register_error'] = '❌ อีเมลต้องเป็น @gmail.com เท่านั้น';
  header('Location: form--register.php');
  exit;
}

if (strlen($password) < 6) {
  $_SESSION['register_error'] = '❌ รหัสผ่านต้องมีอย่างน้อย 6 ตัว';
  header('Location: form--register.php');
  exit;
}

if ($password !== $confirm) {
  $_SESSION['register_error'] = '❌ รหัสผ่านไม่ตรงกัน';
  header('Location: form--register.php');
  exit;
}

$check = $conn->prepare("SELECT id FROM users WHERE email = ?");
$check->bind_param("s", $email);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
  $_SESSION['register_error'] = '❌ อีเมลนี้ถูกสมัครไปแล้ว';
  header('Location: form--register.php');
  exit;
}

$check = $conn->prepare("SELECT id FROM users WHERE username = ?");
$check->bind_param("s", $name);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
  $_SESSION['register_error'] = '❌ ชื่อผู้ใช้นี้มีผู้ใช้แล้ว';
  header('Location: form--register.php');
  exit;
}

$stmt = $conn->prepare(
  "INSERT INTO users (username, email, password, role)
   VALUES (?, ?, ?, 'member')"
);
$stmt->bind_param("sss", $name, $email, $password);

if ($stmt->execute()) {
  $user_id = $stmt->insert_id;
  $_SESSION['user_id'] = $user_id;
  $_SESSION['username'] = $name;
  $_SESSION['role'] = 'member';
  header('Location: member--dashboard.php');
  exit;
} else {
  $_SESSION['register_error'] = '❌ เกิดข้อผิดพลาด กรุณาลองใหม่';
  header('Location: form--register.php');
  exit;
}

$stmt->close();
$check->close();
$conn->close();
?>

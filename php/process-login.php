<?php
session_start();
require './connect.php';

$username = trim($_POST['name']);
$password = trim($_POST['password']);

$stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    $stmt = $conn->prepare("SELECT role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $role_result = $stmt->get_result();

    if ($password === $user['password']) {
        $role = $role_result->fetch_assoc()['role'];
        $_SESSION['role'] = $role;
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        
        if ($role === 'member') {
            header('Location: member--dashboard.php');
        } else if ($role === 'admin') {
            header('Location: admin--dashboard.php');
        } else {
            header('Location: index.php');
        }
        exit;
    } else {
        $_SESSION['login_error'] = '❌ รหัสผ่านไม่ถูกต้อง';
        header('Location: index.php');
        exit;
    }
} else {
    $_SESSION['login_error'] = '❌ ไม่พบชื่อผู้ใช้';
    header('Location: index.php');
    exit;
}

$stmt->close();
$conn->close();
?>
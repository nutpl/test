<?php
session_start();
require './connect.php';

/* ---------- รับค่าจากฟอร์ม ---------- */
$Card_ID     = trim($_POST['Card_ID']);
$GameType_ID = trim($_POST['GameType_ID']);
$Card_name   = trim($_POST['Card_name']);
$Card_type   = trim($_POST['Card_type']);

/* ---------- ตรวจสอบค่าว่าง ---------- */
if (
    $Card_ID === '' ||
    $GameType_ID === '' ||
    $Card_name === '' ||
    $Card_type === ''
) {
    echo "<script>
        alert('กรุณากรอกข้อมูลให้ครบ');
        history.back();
    </script>";
    exit;
}

/* ---------- ตรวจสอบว่า GameType_ID มีอยู่จริง ---------- */
$check = $conn->prepare(
    "SELECT GameType_ID FROM cardgame_type WHERE GameType_ID = ?"
);
$check->bind_param("s", $GameType_ID);
$check->execute();
$result = $check->get_result();

if ($result->num_rows === 0) {
    echo "<script>
        alert('รหัสประเภทการ์ดไม่ถูกต้อง');
        history.back();
    </script>";
    exit;
}

/* ---------- ตรวจสอบไฟล์รูป ---------- */
if (!isset($_FILES['images_Card']) || $_FILES['images_Card']['error'] !== 0) {
    echo "<script>
        alert('กรุณาเลือกรูปการ์ด');
        history.back();
    </script>";
    exit;
}

$images_Card     = $_FILES['images_Card']['name'];  
$images_tmp      = $_FILES['images_Card']['tmp_name'];
$images_size     = $_FILES['images_Card']['size'];

/* ---------- ตรวจสอบชนิดไฟล์ ---------- */
$allow_ext = ['jpg', 'jpeg', 'png'];
$ext = strtolower(pathinfo($images_Card, PATHINFO_EXTENSION));

if (!in_array($ext, $allow_ext)) {
    echo "<script>
        alert('อนุญาตเฉพาะไฟล์ jpg, jpeg, png');
        history.back();
    </script>";
    exit;
}

/* ---------- โฟลเดอร์เก็บรูป ---------- */
$folder = 'upload_images/';
if (!is_dir($folder)) {
    mkdir($folder, 0777, true);
}

$new_image_name = uniqid() . '.' . $ext;
$image_path = $folder . $new_image_name;

/* ---------- บันทึกข้อมูลลงฐานข้อมูล ---------- */
$stmt = $conn->prepare(
    "INSERT INTO card_information
     (Card_ID, GameType_ID, Card_name, Card_type, images_Card)
     VALUES (?, ?, ?, ?, ?)"
);

$stmt->bind_param(
    "sssss",
    $Card_ID,
    $GameType_ID,
    $Card_name,
    $Card_type,
    $image_path
);

if ($stmt->execute()) {
    move_uploaded_file($images_tmp, $image_path);
    echo "<script>
        alert('เพิ่มข้อมูลสำเร็จ');
        window.location.href = './inser.php';
    </script>";
} else {
    echo "<script>
        alert('เพิ่มข้อมูลไม่สำเร็จ');
        history.back();
    </script>";
}
?>

<?php
$host = "localhost";
$user = "root";        // ปรับตามเครื่อง
$pass = "";            // ปรับตามเครื่อง
$dbname = "register_db";

$conn = new mysqli($host, $user, $pass, $dbname);
$conn->set_charset("utf8mb4");

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>

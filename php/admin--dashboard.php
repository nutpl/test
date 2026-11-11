<?php
session_start();
require './connect.php';
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
}elseif (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>หน้าแรก</title>
    <link rel="icon" type="image/png" href="../images_web/489401.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/style2.css">
    <link href="../fontawesome-free-7.1.0-web/fontawesome-free-7.1.0-web/css/fontawesome.min.css" rel="stylesheet">
    <link href="../fontawesome-free-7.1.0-web/fontawesome-free-7.1.0-web/css/brands.min.css" rel="stylesheet">
    <link href="../fontawesome-free-7.1.0-web/fontawesome-free-7.1.0-web/css/solid.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(120deg, #f4f7ff, #eef3ff, #f9f6ff);
            background-size: cover;
        }
    </style>
</head>

<body   >
    <nav class="navbar">
        <div class="nav-links">
        <a href="" class="">หน้าแรก</a>
        <a href="./start_admin.php">เริ่มเล่น</a>
        <a href="./card.php">การ์ด</a>
        <a href="">การจัดเด็ค</a>
        <a href="./indexmanager.php" style="margin-right: 100px;">จัดการข้อมูล</a>
        </div>
        <div class="search-container">
    <input 
        type="text" 
        class="search-box"
        placeholder="ค้นหา"
    >
    <i class="fa-solid fa-magnifying-glass search-icon  "></i>
</div>
        <div class="user-menu">
        <div class="user-btn account-icon" id="userBtn">
        <span class="email"><?php echo $_SESSION['username']; ?></span>
        <span class="arrow">▼</span>
    
        </div>
    

    <div class="dropdown" id="dropdownMenu">
        <a href="./admin--dashboard.php?logout=1">ออกจากระบบ</a>
    </div>
</div>

<script src="script.js"></script>
        
        
    </nav>
   
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>
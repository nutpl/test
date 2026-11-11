<?php
session_start();
require './connect.php';
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'member') {
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
    <style>
        body {
            background: linear-gradient(120deg, #f4f7ff, #eef3ff, #f9f6ff);
            background-size: cover;
        }
    </style>
</head>

<body>
    <nav class="navbar">
        <div class="nav-links">
        <a href="" class="">หน้าแรก</a>
        <a href="">เริ่มเล่น</a>
        <a href="">กฎการเล่น</a>
        <a href="">การจัดเด็ค</a>
        </div>
        <div class="search-container">
    <input 
        type="text" 
        class="search-box account-icon"
        placeholder="ค้นหา"
    >
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search search-icon" viewBox="0 0 16 16">
  <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
</svg>
</div>
        <div class="user-menu">
        <div class="user-btn account-icon" id="userBtn">
        <span class="email"><?php echo $_SESSION['username']; ?></span>
        <span class="arrow">▼</span>
    
        </div>
    

    <div class="dropdown" id="dropdownMenu">
        <a href="./member--dashboard.php?logout=1">ออกจากระบบ</a>
    </div>
</div>

<script src="script.js"></script>
        
        
    </nav>

</body>
</html>
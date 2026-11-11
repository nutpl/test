<?php
require './connect.php';

$game = mysqli_query($conn, "
    SELECT ca.CardGame_name 
    FROM cardgame_type AS ca 
    JOIN card_information AS ci 
        ON ca.GameType_ID = ci.GameType_ID 
    WHERE ca.CardGame_name LIKE '%Po%'
    LIMIT 1
");

$row = mysqli_fetch_assoc($game);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
      <link href="../fontawesome-free-7.1.0-web/fontawesome-free-7.1.0-web/css/fontawesome.min.css" rel="stylesheet">
    <link href="../fontawesome-free-7.1.0-web/fontawesome-free-7.1.0-web/css/brands.min.css" rel="stylesheet">
    <link href="../fontawesome-free-7.1.0-web/fontawesome-free-7.1.0-web/css/solid.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include './admin--dashboard.php'; ?>

    <h1 class="text-center mt-5">
        <?php 
        if($row){
            echo $row['CardGame_name'];
        } else {
            echo "ไม่พบข้อมูล";
        }
        ?>
    </h1>

</body>
</html>

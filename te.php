<?php

$open_connect = 1;
require('connect.php');


$errors = [];
$success_message = "";

if(isset($_POST['username_account']) && isset($_POST['email_account']) && isset($_POST['password_account1']) && isset($_POST['password_account2'])){
     $username_account = htmlspecialchars(mysqli_real_escape_string($connect, $_POST['username_account']));
     $email_account = htmlspecialchars(mysqli_real_escape_string($connect, $_POST['email_account']));
     $password_account1 = htmlspecialchars(mysqli_real_escape_string($connect, $_POST['password_account1']));
     $password_account2 = htmlspecialchars(mysqli_real_escape_string($connect, $_POST['password_account2']));
    
     if(empty($username_account)){
        $errors['username'] = "Username is required.";
     }elseif(empty($email_account)){
         $errors['email'] = "Invalid email format.";
     }elseif(empty($password_account1)){
        $errors['password'] = "Password is required.";
     }elseif(empty($password_account2)){
        $errors['confirm_password'] = "Confirm Password is required.";
     }elseif($password_account1 != $password_account2){
        $errors['confirm_password'] = "Passwords do not match.";
     }else{
         $query_check_email_account = "SELECT email_account FROM account WHERE email_account = '$email_account'";
         $call_back_query_check_email_account = mysqli_query($connect, $query_check_email_account);
         if(mysqli_num_rows($call_back_query_check_email_account) > 0){
            die(header('Location: /pro/php/form--register.php')); //มีผู้ใช้อีเมลนี้แล้ว
         }else{
             $password_account = $password_account1;
             $query_create_account = "INSERT INTO account VALUES ('', '$username_account', '$password_account', '$email_account', 'member')";
             $call_back_create_account = mysqli_query($connect, $query_create_account);
             if($call_back_create_account){
                 die(header('Location: /pro/php/form--login.php')); //สร้างบัญชีสำเร็จ
             }else{
                die(header('Location: /pro/php/form--register.php')); //สร้างบัญชีล้มเหลว
             }
         }
     }

}else{
    die(header('Location: /pro/php/form--register.php')); //ไม่มีข้อมูล
}

?>
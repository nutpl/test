<?php
session_start();
$open_connect = 1;
require('./php/connect.php');

if(isset($_POST['email_account']) && isset($_POST['password_account'])){
    $email_account = htmlspecialchars(mysqli_real_escape_string($connect, $_POST['email_account']));
    $password_account = htmlspecialchars(mysqli_real_escape_string($connect, $_POST['password_account']));
    $query_check_account = "SELECT * FROM account WHERE email_account = '$email_account'";
    $call_back_check_account = mysqli_query($connect, $query_check_account);
    if(mysqli_num_rows($call_back_check_account) == 1){
        $result_check_account = mysqli_fetch_assoc($call_back_check_account);
        $hash = $result_check_account['password_account'];
        $password_account = $password_account;
        if(password_verify($password_account, $hash)){
            $query_reset_login_count_account = "UPDATE account SET login_count_account = 0 WHERE email_account = '$email_account'";
            $call_back_reset = mysqli_query($connect, $query_reset_login_count_account);
            if($result_check_account['role_account'] == 'member'){ //บทบาท member
                $_SESSION['id_account'] = $result_check_account['id_account'];
                $_SESSION['role_account'] = $result_check_account['role_account'];
                die(header('Location: php/member--dashboard.php'));
            }elseif($result_check_account['role_account'] == 'admin'){ //บทบาท admin
                $_SESSION['id_account'] = $result_check_account['id_account'];
                $_SESSION['role_account'] = $result_check_account['role_account'];
                die(header('Location: admin.php'));
            }
        }else{
            $query_login_count_account = "UPDATE account SET login_count_account = login_count_account + 1 WHERE email_account = '$email_account'";
            $call_back_login_count_account = mysqli_query($connect, $query_login_count_account);
            if($result_check_account['login_count_account'] + 1 >= $limit_login_account){
                
                $query_lock_account = "UPDATE account SET lock_account = 1, ban_account = DATE_ADD(NOW(), INTERVAL $time_ban_account MINUTE) WHERE email_account = '$email_account'";
                $call_back_lock_account = mysqli_query($connect, $query_lock_account);
            }
            $_SESSION['login_error'] = '❌ รหัสผ่านไม่ถูกต้อง';
            die(header('Location: php/form--login.php'));
        }

    }else{
        $_SESSION['login_error'] = '❌ ไม่พบอีเมลนี้ในระบบ';
        die(header('Location: php/form--login.php'));
    }

}else{
    $_SESSION['login_error'] = '❌ กรุณากรอกข้อมูลให้ครบ';
    die(header('Location: php/form--login.php'));
}
?>
<?php
session_start();
include("connection.php");

if(isset($POST['login'])){
    $email=$_POST["email"];
    $password=$_POST["password"];

    $sql="SELECT * FROM register WHERE email='$email'";
    $result=mysqli_query($connection,$sql);
    if(mysqli_num_rows($result)>0){
        $user=mysqli_fetch_assoc($result);
        if(password_verify($password,$user['password'])){
            $_SESSION['user_id']=$user['id'];
            $_SESSION['email']=$user['email'];
            $_SESSION['logged_in']=true;
            header("locaction:/frontend/home.html");
            exit();

        }

    }
    else{
        $_SESSION['error']="email not matched";
        header("location:login.php");
        exit();
        
    }
}

?>
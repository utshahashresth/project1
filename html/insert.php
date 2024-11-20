<?php
include("connection.php");
if(isset($_POST["signup"])){
    $firstname=$_POST["f_name"];
    
    $lastname=$_POST["l_name"];
    $email=$_POST["email"];
    $password=$_POST["password"];
    $c_password=$_POST["c_password"];
}

    $sql= "INSERT INTO register(f_name,l_name,email,pword,c_pword)VALUES( '$firstname','$lastname',' $email','$password','$c_password')";
    try{
    mysqli_query($conn, $sql);
    echo "successfully connected";
    }
    catch(mysqli_sql_exception ){
        echo "not connected";
    }
    mysqli_close($conn);
?>
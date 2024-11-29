<?php
include("connection.php");
if(isset($_POST["signup"])){
    $firstname=$_POST["f_name"];
    
    $lastname=$_POST["l_name"];
    $email=$_POST["email"];
    $password=$_POST["password"];
    $hashpwd=password_hash($password,PASSWORD_BCRYPT);

}

    $sql= "INSERT INTO register(f_name,l_name,email,pword)VALUES( '$firstname','$lastname',' $email','$hashpwd)";
    try{
    mysqli_query($conn, $sql);
  echo("connected");
    
    }
    catch(mysqli_sql_exception){
        echo "not connected";
   
    }

    mysqli_close($conn);

?>
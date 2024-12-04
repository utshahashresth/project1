<?php
include("connection.php");

if (isset($_POST["signup"])) {
    $firstname = $_POST["f_name"];
    $lastname = $_POST["l_name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $hashpwd = password_hash($password, PASSWORD_BCRYPT);

    $sql = "INSERT INTO register(f_name, l_name, email, pword) VALUES ('$firstname', '$lastname', '$email', '$hashpwd')";
    try {
        if (mysqli_query($conn, $sql)) {
            header("Location: login.php");
            exit();
        } else {
            throw new Exception("Registration failed: " . mysqli_error($conn));
        }
    } catch (Exception $e) {
       
        echo "Sorry, an error occurred during registration. Please try again later.";
    }

    mysqli_close($conn);
}
?>
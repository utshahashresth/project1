<?php
include("connection.php");
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <link rel="stylesheet" href="css/signup.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Amiko:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="main">
    <div class="logo-container"><img src="img/img.png" alt="" class="logo">
    </div>

    <div class="second-main">
        <div class="title">
            <div class="titles">Create an account</div>
<div class="sub-title ">Already have an account? <a href="login.html">login</a></div>
        </div>
<<<<<<< Updated upstream:html/signup.php
<form action="insert.php" method="post">
=======
<form action="" method="post">
>>>>>>> Stashed changes:html/signup.html
<div class="names">
<div><input type="text" placeholder="first name" class="firstname"></div>
<div><input type="text" placeholder="last name" class="lastname"></div>
</div>

<div><input type="email" placeholder="abc@example.com" class="email"></div>
<div><input type="password" placeholder="password" class="pword"></div>
<div><input type="password" placeholder="confirm password" class="cpword"></div>
<div class="agree">
<div><input type="checkbox" name="" class="checkbox"></div>
<div class="term">I agree to the <a href="">terms and condition</a> </div>
</div>
<div class="create_account">
<div><button class="button " type="submit" name="signup">Create account</button></div>
</div>
    </div>
</form>
</div>

</body>
</html>

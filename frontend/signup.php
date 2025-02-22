<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account | Sign Up</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
 <link rel="stylesheet" href="./css/signup.css">
</head>
<body>
    <div class="container">
        <div class="logo-container">
            <img src="img/img.png" alt="Logo" class="logo">
        </div>
        
        <h1>Create an account</h1>
        <p class="subtitle">Already have an account? <a href="login.php">Sign in</a></p>

        <form action="../backend/ja.php" method="post" id="signup-form">
            <div class="form-grid">
                <div class="name-grid">
                    <div class="input-group">
                        <label for="firstname">First name</label>
                        <input type="text" id="firstname" name="f_name" required>
                    </div>
                    <div class="input-group">
                        <label for="lastname">Last name</label>
                        <input type="text" id="lastname" name="l_name" required>
                    </div>
                </div>

                <div class="input-group">
                    <label for="email">Email address</label>
                    <input type="email" id="email" name="email" required>
                    <div id="email__msg" class="error-message"></div>
                </div>

                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                    <div id="msg" class="error-message"></div>
                    <div id="validate" class="error-message"></div>
                </div>

                <div class="input-group">
                    <label for="c_password">Confirm password</label>
                    <input type="password" id="c_password" name="c_password" required>
                    <div id="message" class="error-message"></div>
                </div>

                <div class="checkbox-group">
                    <input type="checkbox" id="terms" name="checkbox" required>
                    <label for="terms">I agree to the <a href="">Terms and Conditions</a></label>
                </div>

                <button type="submit" name="submit" id="button">Create account</button>
            </div>
        </form>
    </div>
    <script src="authentication.js"></script>
</body>
</html>
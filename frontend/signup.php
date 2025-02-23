<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account | Sign Up</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            background: white;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 480px;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 24px;
        }

        .logo {
            max-width: 120px;
            height: auto;
        }

        h1 {
            font-size: 24px;
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 8px;
            text-align: center;
        }

        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 32px;
        }

        .subtitle a {
            color: #2563eb;
            text-decoration: none;
            font-weight: 500;
        }

        .subtitle a:hover {
            text-decoration: underline;
        }

        .form-grid {
            display: grid;
            gap: 20px;
        }

        .name-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .input-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .input-group label {
            font-size: 14px;
            color: #4b5563;
            font-weight: 500;
        }

        input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.2s;
        }

        input:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .error-message {
            color: #dc2626;
            font-size: 12px;
            margin-top: 4px;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 8px;
        }

        .checkbox-group input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: #2563eb;
        }

        .checkbox-group label {
            font-size: 14px;
            color: #4b5563;
        }

        .checkbox-group label a {
            color: #2563eb;
            text-decoration: none;
        }

        .checkbox-group label a:hover {
            text-decoration: underline;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #2563eb;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.2s;
            margin-top: 24px;
        }

        button:hover {
            background-color: #1d4ed8;
        }

        @media (max-width: 480px) {
            .container {
                padding: 24px;
            }

            .name-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
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
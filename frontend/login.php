<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
            max-width: 420px;
            opacity: 0;
            transform: translateY(20px);
            animation: fadeIn 0.6s ease-out forwards;
        }

        @keyframes fadeIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .logo-container {
            text-align: center;
            margin-bottom: 24px;
        }

        .logo {
            max-width: 120px;
            height: auto;
            transform: scale(0.9);
            transition: transform 0.3s ease;
        }

        .logo:hover {
            transform: scale(1);
        }

        h1 {
            font-size: 28px;
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 8px;
            text-align: center;
        }

        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 32px;
            font-size: 15px;
        }

        .form-group {
            margin-bottom: 24px;
            position: relative;
        }

        input {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 15px;
            transition: all 0.3s ease;
            background: white;
        }

        input:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }

        .password-container {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            user-select: none;
            transition: opacity 0.2s ease;
        }

        .toggle-password:hover {
            opacity: 0.7;
        }

        .error-message {
            color: #dc2626;
            font-size: 14px;
            margin-top: 8px;
            animation: shake 0.4s ease-in-out;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        .signup-link {
            text-align: center;
            margin-top: 24px;
            color: #666;
        }

        .signup-link a {
            color: #2563eb;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s ease;
        }

        .signup-link a:hover {
            color: #1d4ed8;
            text-decoration: underline;
        }

        button {
            width: 100%;
            padding: 14px;
            background-color: #2563eb;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 32px;
        }

        button:hover {
            background-color: #1d4ed8;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
        }

        button:active {
            transform: translateY(0);
            box-shadow: none;
        }

        #status {
            font-size: 14px;
            margin-top: 8px;
            transition: all 0.3s ease;
        }

        #status.error {
            color: #dc2626;
            animation: shake 0.4s ease-in-out;
        }

        #status.success {
            color: #059669;
        }

        @media (max-width: 480px) {
            .container {
                padding: 24px;
            }
        }
    </style>
</head>
<body>
    <?php 
    ?>

    <div class="container">
        <div class="logo-container">
           
        </div>
        
        <h1>Welcome back</h1>
        <p class="subtitle">Enter your email to continue</p>

        <form action="../backend/login_insert.php" method="post">
            <div class="form-group">
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    placeholder="abc@example.com" 
                    required
                >
                <div id="status"></div>
            </div>

            <div class="form-group">
                <div class="password-container">
                    <input 
                        type="password" 
                        id="password" 
                        name="pword" 
                        placeholder="Password" 
                        required
                    >
                    <span class="toggle-password" id="togglePassword">👁️</span>
                </div>
            </div>

            <?php if (!empty($error)): ?>
                <div class="error-message">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <button type="submit" name="login">Log in</button>

            <div class="signup-link">
                Don't have an account? <a href="signup.php">Sign up</a>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordField = document.getElementById('password');
            this.textContent = passwordField.type === 'password' ? '🙈' : '👁️';
            passwordField.type = passwordField.type === 'password' ? 'text' : 'password';
        });

        $(document).ready(function() {
            let timeoutId;
            
            $('#email').on('input', function() {
                clearTimeout(timeoutId);
                const email = $(this).val();
                const statusDiv = $('#status');
                
                if (!email) {
                    statusDiv.html('');
                    return;
                }
                
                timeoutId = setTimeout(function() {
                    $.ajax({
                        url: '../backend/auth_login.php',
                        type: 'POST',
                        data: { email: email },
                        success: function(response) {
                            const result = JSON.parse(response);
                            if (result.exists) {
                                statusDiv.html('✓ Email found')
                                    .removeClass('error')
                                    .addClass('success');
                            } else {
                                statusDiv.html('✗ Email is not registered')
                                    .removeClass('success')
                                    .addClass('error');
                            }
                        },
                        error: function() {
                            statusDiv.html('Error checking email')
                                .removeClass('success')
                                .addClass('error');
                        }
                    });
                }, 500);
            });
        });
    </script>
</body>
</html>
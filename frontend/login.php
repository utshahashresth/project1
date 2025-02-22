<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Your Brand</title>
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
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e9f2 100%);
            padding: 20px;
        }

        .container {
            width: 100%;
            max-width: 440px;
            background: rgba(255, 255, 255, 0.95);
            padding: 48px 40px;
            border-radius: 24px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
            backdrop-filter: blur(8px);
            opacity: 0;
            transform: translateY(20px);
            animation: fadeIn 0.8s cubic-bezier(0.22, 1, 0.36, 1) forwards;
        }

        @keyframes fadeIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .logo-container {
            text-align: center;
            margin-bottom: 32px;
        }

        .logo {
            max-width: 140px;
            height: auto;
            transform: scale(0.95);
            transition: transform 0.4s cubic-bezier(0.22, 1, 0.36, 1);
            filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.1));
        }

        .logo:hover {
            transform: scale(1.05);
        }

        h1 {
            font-size: 32px;
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 12px;
            text-align: center;
            letter-spacing: -0.5px;
        }

        .subtitle {
            text-align: center;
            color: #64748b;
            margin-bottom: 40px;
            font-size: 16px;
            font-weight: 400;
        }

        .form-group {
            margin-bottom: 24px;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #475569;
            font-size: 14px;
            font-weight: 500;
        }

        input {
            width: 100%;
            padding: 16px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 15px;
            transition: all 0.3s cubic-bezier(0.22, 1, 0.36, 1);
            background: white;
            color: #1e293b;
        }

        input:hover {
            border-color: #cbd5e1;
        }

        input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
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
            padding: 4px;
            border-radius: 6px;
            transition: all 0.2s ease;
            color: #64748b;
        }

        .toggle-password:hover {
            background: #f1f5f9;
            color: #0f172a;
        }

        button {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.22, 1, 0.36, 1);
            margin-top: 32px;
            position: relative;
            overflow: hidden;
        }

        button::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(rgba(255, 255, 255, 0.2), transparent);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(37, 99, 235, 0.2);
        }

        button:hover::after {
            opacity: 1;
        }

        button:active {
            transform: translateY(0);
        }

        .error-message {
            background: #fee2e2;
            color: #dc2626;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 14px;
            margin: 16px 0;
            display: flex;
            align-items: center;
            animation: shake 0.4s cubic-bezier(0.36, 0, 0.66, -0.56);
            border: 1px solid #fecaca;
        }

        .error-message::before {
            content: '⚠️';
            margin-right: 8px;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-4px); }
            75% { transform: translateX(4px); }
        }

        #status {
            font-size: 14px;
            margin-top: 8px;
            padding: 4px 8px;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        #status.error {
            color: #dc2626;
            background: #fee2e2;
        }

        #status.success {
            color: #059669;
            background: #d1fae5;
        }

        .signup-link {
            text-align: center;
            margin-top: 32px;
            color: #64748b;
            font-size: 15px;
        }

        .signup-link a {
            color: #3b82f6;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s ease;
            padding: 2px 4px;
            border-radius: 4px;
        }

        .signup-link a:hover {
            color: #1d4ed8;
            background: #eff6ff;
        }

        @media (max-width: 480px) {
            .container {
                padding: 32px 24px;
                border-radius: 20px;
            }

            h1 {
                font-size: 28px;
            }

            .subtitle {
                font-size: 15px;
            }
        }
    </style>
</head>
<body>
    <?php
    $error = "";
    if (isset($_SESSION['error'])) {
        $error = $_SESSION['error'];
        unset($_SESSION['error']);
    }
    ?>

    <div class="container">
        <div class="logo-container">
            <img src="img/img.png" alt="Logo" class="logo">
        </div>
        
        <h1>Welcome back</h1>
        <p class="subtitle">Sign in to manage your account</p>

        <form action="../backend/login_insert.php" method="post">
            <div class="form-group">
                <label for="email">Email address</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    placeholder="Enter your email"
                    required
                >
                <div id="status"></div>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="password-container">
                    <input 
                        type="password" 
                        id="password" 
                        name="pword" 
                        placeholder="Enter your password"
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

            <button type="submit" name="login">Sign in to account</button>

            <div class="signup-link">
                Don't have an account? <a href="signup.php">Create one</a>
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
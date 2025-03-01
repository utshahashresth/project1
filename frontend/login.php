<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finotic</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="./css/login.css">
</head>

<body>
    <div class="browser-frame">
        <div class="container">
            <div class="sidebar">
                <div class="logo">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="12" cy="12" r="10" stroke="#0D6EFD" stroke-width="2" />
                        <path d="M12 6V12L16 14" stroke="#0D6EFD" stroke-width="2" stroke-linecap="round" />
                    </svg>

                </div>
                <div class="dashboard">
                    <div class=" card balance-card">
                        <div class="balance-label">CURRENT BALANCE</div>
                        <div class="balance-amount">$24,359</div>
                    </div>

                    <div class=" card chart-card">
                        <div class="chart-label">
                            <div class="chart-percentage">34%</div>
                            <div class="chart-category">Food</div>
                        </div>
                        <svg class="donut-chart" viewBox="0 0 36 36">
                            <path d="M18 2.0845
                            a 15.9155 15.9155 0 0 1 0 31.831
                            a 15.9155 15.9155 0 0 1 0 -31.831"
                                fill="none" stroke="#E6E6E6" stroke-width="2" />
                            <path d="M18 2.0845
                            a 15.9155 15.9155 0 0 1 13.7944 7.9577
                            a 15.9155 15.9155 0 0 1 -5.7326 21.8733"
                                fill="none" stroke="#0D6EFD" stroke-width="2" stroke-dasharray="34, 100" />
                            <path d="M18 2.0845
                            a 15.9155 15.9155 0 0 1 13.7944 7.9577
                            a 15.9155 15.9155 0 0 1 -5.7326 21.8733"
                                fill="none" stroke="#FF9500" stroke-width="2" stroke-dasharray="18, 100" stroke-dashoffset="-34" />
                            <path d="M18 2.0845
                            a 15.9155 15.9155 0 0 1 13.7944 7.9577
                            a 15.9155 15.9155 0 0 1 -5.7326 21.8733"
                                fill="none" stroke="#34C759" stroke-width="2" stroke-dasharray="24, 100" stroke-dashoffset="-52" />
                            <path d="M18 2.0845
                            a 15.9155 15.9155 0 0 1 13.7944 7.9577
                            a 15.9155 15.9155 0 0 1 -5.7326 21.8733"
                                fill="none" stroke="#FF3B30" stroke-width="2" stroke-dasharray="24, 100" stroke-dashoffset="-76" />
                        </svg>
                    </div>

                    <div class=" card new-transaction transaction-card">
                        <div class="new-transaction-icon">+</div>
                        <div class="new-transaction-text">New transaction</div>
                        <div class="new-transaction-subtext">or upload .xls file</div>
                    </div>
                </div>
                <div class="sidebar-welcome">
                    <div class="welcome-title">Welcome back!</div>
                    <div class="welcome-text">Start managing your finance faster and better</div>
                </div>

                <div class="slider-controls">
                    <div class="slider-arrow">❮</div>
                    <div class="slider-dots">
                        <div class="slider-dot"></div>
                        <div class="slider-dot"></div>
                        <div class="slider-dot active"></div>
                        <div class="slider-dot"></div>
                    </div>
                    <div class="slider-arrow">❯</div>
                </div>
            </div>

            <div class="login-section">
                <div class="form-container">
                    <div class="login-title">Welcome back!</div>
                    <div class="login-subtitle">Start managing your finance faster and better</div>
                    <form action="../backend/login_insert.php" method="post">
                        <div class="form-group">
                            <input type="email" class="form-control form-control-icon" placeholder="you@example.com" name="email" id="email">
                            <svg class="form-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="2" y="4" width="20" height="16" rx="2" stroke="#999999" stroke-width="2" />
                                <path d="M2 7L12 14L22 7" stroke="#999999" stroke-width="2" />
                            </svg>
                        </div>
                        <div id="status"></div>
                        <?php if (!empty($error)): ?>
                            <div class="error-message">
                                <?= htmlspecialchars($error) ?>
                            </div>
                        <?php endif; ?>
                        <div class="form-group">
                            <input type="password" class="form-control form-control-icon" placeholder="At least 8 characters" name="pword">
                            <svg class="form-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="4" y="9" width="16" height="12" rx="2" stroke="#999999" stroke-width="2" />
                                <circle cx="12" cy="15" r="2" stroke="#999999" stroke-width="2" />
                                <path d="M12 15V17" stroke="#999999" stroke-width="2" />

                                <path d="M8 9V7C8 4.79086 9.79086 3 12 3V3C14.2091 3 16 4.79086 16 7V9" stroke="#999999" stroke-width="2" />

                            </svg>
                            <svg class="eye-icon" id="eye-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="cursor: pointer;">
                                <path d="M12 6C7.03 6 3 9.13 3 12C3 14.87 7.03 18 12 18C16.97 18 21 14.87 21 12C21 9.13 16.97 6 12 6ZM12 16C9.79 16 8 14.21 8 12C8 9.79 9.79 8 12 8C14.21 8 16 9.79 16 12C16 14.21 14.21 16 12 16ZM12 9.5C10.62 9.5 9.5 10.62 9.5 12C9.5 13.38 10.62 14.5 12 14.5C13.38 14.5 14.5 13.38 14.5 12C14.5 10.62 13.38 9.5 12 9.5Z" fill="#999999" />
                            </svg>
                        </div>

                        <div class="forgot-password">
                            <a href="#" class="forgot-link">Forgot password?</a>
                        </div>

                        <button class="btn-login" name="login">Login</button>
                    </form>
                    </div>
                    <div class="divider">
                        <span class="divider-text">or</span>
                    </div>

                    <div class="signup-prompt">
                        Don't you have an account? <a href="signup.php" class="signup-link">Sign Up</a>
                    </div>

                    <div class="footer">
                        © 2025 ALL RIGHTS RESERVED
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Toggle password visibility
        document.querySelector('#eye-icon').addEventListener('click', function() {
            let passwordInput = document.querySelector('.form-group input[type="password"]');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                this.innerHTML = '<path d="M12 4.5C7.3 4.5 3.42 7.61 2 12C3.42 16.39 7.3 19.5 12 19.5C16.7 19.5 20.58 16.39 22 12C20.58 7.61 16.7 4.5 12 4.5ZM12 17C9.24 17 7 14.76 7 12C7 9.24 9.24 7 12 7C14.76 7 17 9.24 17 12C17 14.76 14.76 17 12 17ZM12 9.5C10.62 9.5 9.5 10.62 9.5 12C9.5 13.38 10.62 14.5 12 14.5C13.38 14.5 14.5 13.38 14.5 12C14.5 10.62 13.38 9.5 12 9.5Z" fill="#999999"/>';
            } else {
                passwordInput.type = 'password';
                this.innerHTML = '<path d="M12 6C7.03 6 3 9.13 3 12C3 14.87 7.03 18 12 18C16.97 18 21 14.87 21 12C21 9.13 16.97 6 12 6ZM12 16C9.79 16 8 14.21 8 12C8 9.79 9.79 8 12 8C14.21 8 16 9.79 16 12C16 14.21 14.21 16 12 16ZM12 9.5C10.62 9.5 9.5 10.62 9.5 12C9.5 13.38 10.62 14.5 12 14.5C13.38 14.5 14.5 13.38 14.5 12C14.5 10.62 13.38 9.5 12 9.5Z" fill="#999999"/>';
            }
        });



        // Slider functionality
        const dots = document.querySelectorAll('.slider-dot');
        const prevBtn = document.querySelector('.slider-controls .slider-arrow:first-child');
        const nextBtn = document.querySelector('.slider-controls .slider-arrow:last-child');
        let currentSlide = 2; // Active slide is the third one (index 2)

        function updateSlider() {
            dots.forEach((dot, index) => {
                dot.classList.toggle('active', index === currentSlide);
            });
        }

        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                currentSlide = index;
                updateSlider();
            });
        });

        prevBtn.addEventListener('click', () => {
            currentSlide = (currentSlide - 1 + dots.length) % dots.length;
            updateSlider();
        });

        nextBtn.addEventListener('click', () => {
            currentSlide = (currentSlide + 1) % dots.length;
            updateSlider();
        });

        // Login button animation
        const loginBtn = document.querySelector('.btn-login');
        loginBtn.addEventListener('mousedown', function() {
            this.style.transform = 'scale(0.98)';
        });

        loginBtn.addEventListener('mouseup', function() {
            this.style.transform = 'scale(1)';
        });

        loginBtn.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
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
                        url: '/project1/backend/auth_login.php',
                        type: 'POST',
                        data: {
                            email: email
                        },
                        success: function(response) {
                            const result = JSON.parse(response);
                            if (result.exists) {
                                statusDiv.html('')
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
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finotic - Sign Up</title>
    <link rel="stylesheet" href="./css/signup.css">
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
                    <span class="logo-text"></span>
                </div>
                <div class="dasboard">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                        <div class="feature-title">Track Your Expenses</div>
                        <div class="feature-text">Easily monitor where your money goes with detailed expense tracking and categorization.</div>
                    </div>

                    <div class="chart-card">
                        <div class="chart-label">
                            <div class="chart-percentage">75%</div>
                            <div class="chart-category">Savings</div>
                        </div>
                        <svg class="donut-chart" viewBox="0 0 36 36">
                            <path d="M18 2.0845
                            a 15.9155 15.9155 0 0 1 0 31.831
                            a 15.9155 15.9155 0 0 1 0 -31.831"
                                fill="none" stroke="#E6E6E6" stroke-width="2" />
                            <path d="M18 2.0845
                            a 15.9155 15.9155 0 0 1 15.9155 15.9155
                            a 15.9155 15.9155 0 0 1 -15.9155 15.9155"
                                fill="none" stroke="#0D6EFD" stroke-width="2" stroke-dasharray="75, 100" />
                        </svg>
                    </div>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M21 15V19C21 19.5304 20.7893 20.0391 20.4142 20.4142C20.0391 20.7893 19.5304 21 19 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V15" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M7 10L12 15L17 10" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M12 15V3" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                    <div class="feature-title">Set Financial Goals</div>
                    <div class="feature-text">Create and track personalized savings goals to reach your financial objectives.</div>
                </div>

                <div class="slider-controls">
                    <div class="slider-arrow">❮</div>
                    <div class="slider-dots">
                        <div class="slider-dot"></div>
                        <div class="slider-dot active"></div>
                        <div class="slider-dot"></div>
                        <div class="slider-dot"></div>
                    </div>
                    <div class="slider-arrow">❯</div>
                </div>
            </div>

            <div class="signup-section">
                <div class="form-container">
                    <div class="signup-title">Create your account</div>
                    <div class="signup-subtitle">Join thousands of users managing their finances smarter</div>
                    <form action="/project1/backend/ja.php" method="post" id="signup-form">
                        <div class="form-row">

                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="First Name" name="f_name">
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" id="" placeholder="Last Name" name="l_name">
                            </div>
                        </div>

                        <div class="form-group">
                            <input type="email" class="form-control form-control-icon" placeholder="Email address" name="email" id="email">
                            <svg class="form-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="2" y="4" width="20" height="16" rx="2" stroke="#999999" stroke-width="2" />
                                <path d="M2 7L12 14L22 7" stroke="#999999" stroke-width="2" />
                            </svg>
                        </div>
                        <div id="email__msg" class="error-message"></div>
                </div>


                <div class="form-group">
                    <input type="password" class="form-control form-control-icon" placeholder="Password (min. 8 characters)" name="password" id="password">
                    <svg class="form-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="4" y="9" width="16" height="12" rx="2" stroke="#999999" stroke-width="2" />
                        <circle cx="12" cy="15" r="2" stroke="#999999" stroke-width="2" />
                        <path d="M12 15V17" stroke="#999999" stroke-width="2" />
                        <path d="M8 9V7C8 4.79086 9.79086 3 12 3V3C14.2091 3 16 4.79086 16 7V9" stroke="#999999" stroke-width="2" />
                    </svg>

                    <svg class="eye-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M2 12C2 12 5 5 12 5C19 5 22 12 22 12C22 12 19 19 12 19C5 19 2 12 2 12Z" stroke="#999999" stroke-width="2" />
                        <circle cx="12" cy="12" r="3" stroke="#999999" stroke-width="2" />
                    </svg>
                    <div id="msg" class="error-message"></div>
                    <div id="validate" class="error-message"></div>
                </div>

                <div class="form-group">
                    <input type="password" class="form-control form-control-icon" placeholder="Confirm password" name="c_password" id="c_password">
                    <svg class="form-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="4" y="9" width="16" height="12" rx="2" stroke="#999999" stroke-width="2" />
                        <circle cx="12" cy="15" r="2" stroke="#999999" stroke-width="2" />
                        <path d="M12 15V17" stroke="#999999" stroke-width="2" />
                        <path d="M8 9V7C8 4.79086 9.79086 3 12 3V3C14.2091 3 16 4.79086 16 7V9" stroke="#999999" stroke-width="2" />
                    </svg>
                    <svg class="eye-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M2 12C2 12 5 5 12 5C19 5 22 12 22 12C22 12 19 19 12 19C5 19 2 12 2 12Z" stroke="#999999" stroke-width="2" />
                        <circle cx="12" cy="12" r="3" stroke="#999999" stroke-width="2" />
                    </svg>
                </div>
                <div id="message" class="error-message"></div>

                <div class="terms-checkbox">
                    <input type="checkbox" id="terms">
                    <label for="terms" class="terms-text">
                        I agree to the <a href="#" class="terms-link">Terms of Service</a> and <a href="#" class="terms-link">Privacy Policy</a>. I understand Finotic will securely process my data as described in the Privacy Policy.
                    </label>
                </div>

                <button class="btn-signup" name="submitBtn" id="button" type="submit">Create Account</button>





                <div class="login-prompt">
                    Already have an account? <a href="login.php" class="login-link">Log In</a>
                </div>

                <div class="footer">
                    © 2025 ALL RIGHTS RESERVED
                </div>
                </form>
            </div>
        </div>
    </div>
    </div>
    <script >

const eyeIcons = document.querySelectorAll('.eye-icon');
eyeIcons.forEach(icon => {
    icon.addEventListener('click', function() {
        const passwordInput = this.previousElementSibling.previousElementSibling;
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
        } else {
            passwordInput.type = 'password';
        }
    });
});

// Slider functionality
const dots = document.querySelectorAll('.slider-dot');
const prevBtn = document.querySelector('.slider-controls .slider-arrow:first-child');
const nextBtn = document.querySelector('.slider-controls .slider-arrow:last-child');
let currentSlide = 1; // Active slide is the second one (index 1)

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

// Button animation
const signupBtn = document.querySelector('.btn-signup');
signupBtn.addEventListener('mousedown', function() {
    this.style.transform = 'scale(0.98)';
});

signupBtn.addEventListener('mouseup', function() {
    this.style.transform = 'scale(1)';
});

signupBtn.addEventListener('mouseleave', function() {
    this.style.transform = 'scale(1)';
});
// Get form elements
const signupForm = document.getElementById('signup-form');
const firstName = document.querySelector('input[name="f_name"]');
const lastName = document.querySelector('input[name="l_name"]');
const email = document.getElementById('email');
const password = document.getElementById('password');
const confirmPassword = document.getElementById('c_password');
const termsCheckbox = document.getElementById('terms');
const emailMsg = document.getElementById('email__msg');
const passwordMsg = document.getElementById('msg');
const passwordValidate = document.getElementById('validate');
const confirmPasswordMsg = document.getElementById('message');

// Email validation function
function isValidEmail(email) {
const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
return emailRegex.test(email);
}

// Password strength validation
function isStrongPassword(password) {
// At least 8 characters, 1 uppercase, 1 lowercase, 1 number, and 1 special character
const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
return passwordRegex.test(password);
}

// Real-time email validation
email.addEventListener('input', function() {
if (email.value.trim() === '') {
emailMsg.textContent = '';
} else if (!isValidEmail(email.value)) {
emailMsg.textContent = 'Please enter a valid email address';
emailMsg.style.color = 'red';
} else {
emailMsg.textContent = 'Valid email address';
emailMsg.style.color = 'green';
}
});

// Real-time password validation
password.addEventListener('input', function() {
if (password.value.trim() === '') {
passwordMsg.textContent = '';
passwordValidate.textContent = '';
} else {
if (password.value.length < 8) {
    passwordMsg.textContent = 'Password must be at least 8 characters long';
    passwordMsg.style.color = 'red';
} else {
    passwordMsg.textContent = '';
}

if (!isStrongPassword(password.value)) {
    passwordValidate.innerHTML = 'Password must contain:<br>' +
        '- At least one uppercase letter<br>' +
        '- At least one lowercase letter<br>' +
        '- At least one number<br>' +
        '- At least one special character (@$!%*?&)';
    passwordValidate.style.color = 'red';
} else {
    passwordValidate.textContent = 'Strong password';
    passwordValidate.style.color = 'green';
}
}

// Check password match if confirm password already has a value
if (confirmPassword.value.trim() !== '') {
if (password.value !== confirmPassword.value) {
    confirmPasswordMsg.textContent = 'Passwords do not match';
    confirmPasswordMsg.style.color = 'red';
} else {
    confirmPasswordMsg.textContent = 'Passwords match';
    confirmPasswordMsg.style.color = 'green';
}
}
});

// Real-time confirm password validation
confirmPassword.addEventListener('input', function() {
if (confirmPassword.value.trim() === '') {
confirmPasswordMsg.textContent = '';
} else if (password.value !== confirmPassword.value) {
confirmPasswordMsg.textContent = 'Passwords do not match';
confirmPasswordMsg.style.color = 'red';
} else {
confirmPasswordMsg.textContent = 'Passwords match';
confirmPasswordMsg.style.color = 'green';
}
});

// Form submission validation
signupForm.addEventListener('submit', function(event) {
let isValid = true;

// First name validation
if (firstName.value.trim() === '') {
isValid = false;
firstName.style.borderColor = 'red';
} else {
firstName.style.borderColor = '';
}

// Last name validation
if (lastName.value.trim() === '') {
isValid = false;
lastName.style.borderColor = 'red';
} else {
lastName.style.borderColor = '';
}

// Email validation
if (email.value.trim() === '' || !isValidEmail(email.value)) {
isValid = false;
email.style.borderColor = 'red';
emailMsg.textContent = 'Please enter a valid email address';
emailMsg.style.color = 'red';
} else {
email.style.borderColor = '';
}

// Password validation
if (password.value.trim() === '' || !isStrongPassword(password.value)) {
isValid = false;
password.style.borderColor = 'red';
} else {
password.style.borderColor = '';
}

// Confirm password validation
if (confirmPassword.value.trim() === '' || password.value !== confirmPassword.value) {
isValid = false;
confirmPassword.style.borderColor = 'red';
confirmPasswordMsg.textContent = 'Passwords do not match';
confirmPasswordMsg.style.color = 'red';
} else {
confirmPassword.style.borderColor = '';
}

// Terms checkbox validation
if (!termsCheckbox.checked) {
isValid = false;
termsCheckbox.nextElementSibling.style.color = 'red';
} else {
termsCheckbox.nextElementSibling.style.color = '';
}

// Prevent form submission if validation fails
if (!isValid) {
event.preventDefault();
}
});

// Reset field styling when focused
const formInputs = signupForm.querySelectorAll('input');
formInputs.forEach(input => {
input.addEventListener('focus', function() {
this.style.borderColor = '';
if (this === termsCheckbox) {
    this.nextElementSibling.style.color = '';
}
});
});

    </script>
</body>

</html>
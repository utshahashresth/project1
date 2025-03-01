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
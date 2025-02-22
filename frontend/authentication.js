let button = document.getElementById("button");
let message = document.getElementById("message");
let msg = document.getElementById("msg");
let email = document.getElementById("email");

msg.innerText = "";
message.innerText = "";
message.classList.remove("error_msg", "success_msg");

document.getElementById("password").addEventListener("input", validatePassword);

function validatePassword() {
   if (password.value.length < 8) {
      msg.innerText = "Password must be at least 8 characters long";
      msg.classList.add("error_msg"); 
      button.disabled = true;
      return false;
    }
    else {
      button.disabled = false;
      msg.innerText = "";
    }
 
   const strongPassword = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
  if (!strongPassword.test(password.value)) {
    msg.innerText =
      "Password must include uppercase, lowercase, number, and special character";
    msg.classList.add("error_msg");
    button.disabled = true;
    return false;
  }
}

document.getElementById("c_password").addEventListener("input", validatePasswords);

function validatePasswords() {
  const password = document.getElementById("password");
  const c_password = document.getElementById("c_password");

  message.innerText = "";
  message.classList.remove("error_msg", "success_msg");

 
  if (password.value !== c_password.value) {
    message.innerText = "Passwords do not match";
    message.classList.add("error_msg");
    return false;
  } else {
    message.innerText = "password matched sucessfully";
    message.classList.add("success_msg");
    button.disabled = false;
    return true;
  }
}
 document.getElementById("email").addEventListener("input", validateEmail);
function validateEmail() {
  
   const emailMsg = document.getElementById("email_msg");
 
  
   const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
   if (!emailRegex.test(email.value)) {
     emailMsg.innerText = "Invalid email format";
     emailMsg.classList.add("error_msg");
     button.disabled = true;
     return false;
   }
 
  
   const commonDomains = ["gmail.com", "yahoo.com", "outlook.com"];
   const emailParts = email.value.split("@");
   if (emailParts.length > 1) {
     const domain = emailParts[1];
     if (!commonDomains.includes(domain)) {
      
       emailMsg.classList.add("error_msg");
     }
   } else {
     emailMsg.innerText = "";
   }
 
   emailMsg.classList.remove("error_msg");
   button.disabled = false;
   return true;
 }
 document.getElementById('togglePassword').addEventListener('click', function() {
  const passwordField = document.getElementById('password');
  this.textContent = passwordField.type === 'password' ? '🙈' : '👁️';
  passwordField.type = passwordField.type === 'password' ? 'text' : 'password';
});
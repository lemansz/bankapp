<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="signup.css">
    
</head>
<body>
    <form action="process-signup.php" method="post" id="regForm" novalidate>
        <fieldset>
            <legend><h3>Register</h3></legend>
            <label for="surname">
               <input type="text" placeholder="Surname" name="surname" id="surname"> 
               <span class="error-message" id="surnameError"></span>
            </label>
            <label for="firstName">
               <input type="text" placeholder="First Name" name="firstName" id="firstName"> 
               <span class="error-message" id="firstNameError"></span>
            </label>
            <label for="email">
               <input type="text" placeholder="Johndoe@gmail.com" name="email" id="email"> 
               <span class="error-message" id="emailError"></span>
            </label>
            <label for="phone-number">
               <input type="text" placeholder="Phone Number" name="phoneNumber" id="phoneNumber" inputmode="numeric" maxlength="11">
               <span class="error-message" id="phoneNumberError"></span> 
            </label>
            <label for="password">
                <input type="password" placeholder="Password" id="password" name="password" class="passwordInput">
                <i id="togglePassword" class="fa fa-eye password-icon"></i>
                <span class="error-message" id="passwordError"></span>
            </label>
            <label for="confirmPassword">
                <input type="password" id="confirmPassword" placeholder="Confirm Password">
                <i id="toggleConfirmPassword" class="fa fa-eye confirm-password-icon"></i>
                <span class="error-message" id="confirmPasswordError"></span>
                <span id="email-taken" class="error-message"></span>
            </label>
            <br>
            <input type="submit" value="Submit" class="submit" id="submit">
        </fieldset>
    </form>

<script>
                
const form = document.getElementById("regForm");
function validateForm() {
const surname = document.getElementById("surname").value;
const firstName = document.getElementById("firstName").value;
const email = document.getElementById("email").value;
const phoneNumber = document.getElementById("phoneNumber").value;
const password = document.getElementById("password").value;
const confirmPassword = document.getElementById("confirmPassword").value;

const surnameError = document.getElementById("surnameError");
const firstNameError = document.getElementById("firstNameError");
const emailError = document.getElementById("emailError");
const phoneNumberError = document.getElementById("phoneNumberError");
const passwordError = document.getElementById("passwordError");
const confirmPasswordError = document.getElementById("confirmPasswordError")

surnameError.textContent = "";
firstNameError.textContent = "";
emailError.textContent = "";
phoneNumberError.textContent = "";
passwordError.textContent = "";
confirmPasswordError.textContent = "";

const regexEmail =  /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
const regexPhoneNumber = /^[0-9]{11}$/;
let isValid = true;

if (surname.trim() === "" || /\d/.test(surname)) {
    surnameError.textContent = "Please enter your Surname properly.";
   
    isValid = false;
}

if (firstName.trim() === "" || /\d/.test(firstName)) {
    firstNameError.textContent = "Please enter your First Name properly.";
    isValid = false;
}

if (email === "" || regexEmail.test(email) === false) {
    emailError.textContent = "Please enter a valid email address.";
    isValid = false;
}

if (phoneNumber === "" || regexPhoneNumber.test(phoneNumber) === false){
    phoneNumberError.textContent = "Invalid Phone Number";
    isValid = false;
}

if (password === "" || password.length < 6 || /\d/.test(password) === false ) {
    passwordError.textContent = "Please enter a password with at least 6 characters with at least number.";
    isValid = false;
}

if (confirmPassword !== password) {
    confirmPasswordError.textContent = "Password does not match";
    isValid = false;
}

return isValid;
}

form.addEventListener('submit', (e) => {
if (validateForm() === false){
    e.preventDefault();
}
})





const togglePassword = document.querySelector('#togglePassword');
const password = document.querySelector('#password');

togglePassword.addEventListener('click', function(e) {
const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
password.setAttribute('type', type);
this.classList.toggle('fa-eye-slash');
})

const toggleConfirmPassword = document.querySelector('#toggleConfirmPassword');
const confirmPassword = document.querySelector('#confirmPassword');

toggleConfirmPassword.addEventListener('click', function(e) {
const type = confirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
confirmPassword.setAttribute('type', type);
this.classList.toggle('fa-eye-slash')
})


window.onload = function() {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('error')) {
        const error = urlParams.get('error');
        if (error === 'email_taken') {
            document.getElementById('email-taken').innerHTML = "Email already taken. Please enter a different email.";
            
        }
    }
    const newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
        window.history.replaceState({}, document.title, newUrl);
    }

    </script>
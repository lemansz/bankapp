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
const regexName = /^[a-zA-Z]+$/;
let isValid = true;

if (surname.trim() === "") {
    isValid = false;
    surnameError.textContent = "Please enter your surname.";
} else if (surname.includes(" ")) {
    isValid = false;
    surnameError.textContent = "No spaces allowed in Surname.";
} else if (!regexName.test(surname)){
    isValid = false;
    surnameError.textContent = "No special characters allowed in Surname.";
}

if (firstName.trim() === "") {
    isValid = false;
    firstNameError.textContent = "Please enter your first name.";
} else if (firstName.includes(" ")) {
    isValid = false;
    firstNameError.textContent = "No spaces allowed in First name.";
} else if (!regexName.test(firstName)){
    isValid = false;
    firstNameError.textContent = "No special characters allowed in First name.";
}

if (email === "") {
    isValid = false;
    emailError.textContent = "Please enter your email address.";
} else if (!regexEmail.test(email)){
    isValid = false;
    emailError.textContent = "Please enter a valid email address.";
}

if (phoneNumber === ""){
    isValid = false;
    phoneNumberError.textContent = "Please enter your phone number.";
} else if (!regexPhoneNumber.test(phoneNumber)) {
    isValid = false;
    phoneNumberError.textContent = "Phone number must be 11 digits.";
}

if (password === "" ) {
    isValid = false;
    passwordError.textContent = "Please enter a password.";
} else if (password.length < 6) {
    isValid = false;
    passwordError.textContent = "Password must be at least 6 characters long.";
} else if (!/\d/.test(password)) {
    isValid = false;
    passwordError.textContent = "Password must include at least one number.";
}
if (confirmPassword !== password) {
    isValid = false;
    confirmPasswordError.textContent = "Passwords do not match.";
}

return isValid;
}

form.addEventListener('submit', (e) => {
if (validateForm() === false){
    e.preventDefault();
} else {
    let submit = document.getElementById('submit');
    submit.style.backgroundColor = "green";
    submit.style.color = "white";
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
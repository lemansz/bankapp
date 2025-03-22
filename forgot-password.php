<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
    input{
    padding: 1rem;
    border-radius: 8px;
    border: 1px solid #ccc;
    transition: border-color 0.3s ease;
    width: 200px;
    font-family: "Roboto", sans-serif;
    font-weight: 400;
    font-style: normal;
    }
    #emailError{
        color: red;
    }
    #send{
    margin-top: 6px;
    font-family: "Roboto", serif;
    font-weight: 400;
    font-style: normal;
    width: 100px;
    }
    </style>
</head>
<body>
    <h1>Forgot Password</h1>

    <form method="post" action="send-password-reset.php" id="forgotPasswordForm" novalidate>
        <label for="email">
           <p><input type="email" name="email" id="email"></p>
           <span id="emailError"></span> 
        </label>
        <br>
        <input type="submit" value="Send" id="send">
    </form>
    <script>
                
const form = document.getElementById("forgotPasswordForm");
function validateForm() {
const email = document.getElementById("email").value;
const emailError = document.getElementById("emailError");

emailError.textContent = "";

const regexEmail =  /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

let isValid = true;
if (email === "" || regexEmail.test(email) === false) {
    emailError.textContent = "Please enter a valid email address.";
    isValid = false;
}
return isValid;
}

form.addEventListener('submit', (e) => {
if (validateForm() === false){
    e.preventDefault();
}
})
</script>
</body>
</html>
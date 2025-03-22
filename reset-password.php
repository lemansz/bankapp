<?php
 $token = $_GET['token'];

 $token_hash = hash('sha256', $token);

 require __DIR__ . '/db.php';

 $sql = "SELECT * FROM bank_user_data WHERE reset_token_hashed = ?";

 $stmt = $conn->prepare($sql);

 $stmt->bind_param("s", $token_hash);

 $stmt->execute();

 $result = $stmt->get_result();

 $user = $result->fetch_assoc();

 if ($user === null){
    die ('token not found');
 }

 if (strtotime($user['reset_token_expires_at']) <= time()) {
    die('token has expired');
 }

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<style>
body{
    display: flex;
    align-items: center;
    justify-content: center;
}
form{
    width: 300px;
}
input{
    padding: 1rem;
    border-radius: 8px;
    border: 1px solid #ccc;
    transition: border-color 0.3s ease;
    margin-bottom: 6px;
    margin-top: 8px;
    width: 200px;
    font-family: "Roboto", sans-serif;
    font-weight: 400;
    font-style: normal;

}
input:hover{
    border-color: #333;
}
::placeholder{
    font-family: "Roboto", serif;
    font-weight: 400;
    font-style: normal;
}
#submit{
    font-family: "Roboto", serif;
    font-weight: 400;
    font-style: normal;
    width: 100px;
}
.error-message{
    color: red;
}
.password-icon{
    position: relative;
    top: -0px;
    right: 30px;
}
.confirm-password-icon {
    position: relative;
    top: -0px;
    right: 30px;
}
</style>
</head>
<body>
    

    <form method="post" action="process-reset-password.php" id="resetPasswordForm">
        <fieldset>
        <legend>New Password</legend>
            <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
    
            <label for="password">
            <input type="password" placeholder="Password" id="password" name="password" class="passwordInput">
            <i id="togglePassword" class="fa fa-eye password-icon"></i>
            <br>
            <span class="error-message" id="passwordError"></span>
            </label>
            <br>
            <label for="confirmPassword">
            <input type="password" id="confirmPassword" placeholder="Confirm Password">
            <i id="toggleConfirmPassword" class="fa fa-eye confirm-password-icon"></i>
            <br>
            <span class="error-message" id="confirmPasswordError"></span>
            <br>
            </label>
            <input type="submit" id="submit" value="Change">
        </fieldset>
    </form>
<script>
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

const form = document.getElementById("resetPasswordForm");
function validateForm() {
const password = document.getElementById("password").value;
const confirmPassword = document.getElementById("confirmPassword").value;

const passwordError = document.getElementById("passwordError");
const confirmPasswordError = document.getElementById("confirmPasswordError")
passwordError.textContent = "";
confirmPasswordError.textContent = "";
let isValid = true;

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

</script>
</body>
</html>
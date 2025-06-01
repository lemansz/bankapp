<?php
 $token = $_GET['token'];

 $token_hash = hash('sha256', $token);

 require dirname(__FILE__) . '/../db.php';

 $sql = "SELECT * FROM staff WHERE reset_token_hash = ?";

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
    <title>Reset passkey</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<style>
    @import url('https://fonts.googleapis.com/css2?family=Cute+Font&family=Parkinsans:wght@300..800&family=Red+Hat+Text:ital,wght@0,300..700;1,300..700&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Stylish&family=Varela&display=swap');

body{
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: "Roboto", sans-serif;
    font-optical-sizing: auto;
    font-style: normal;
    padding-top: 12rem;
}
form{
    border-radius: 0.75rem;
    background-color: rgb(240, 240, 240);
    width: 16.875rem;
    padding-top: 0.5rem;
    padding-left: 1rem;

}
form > input{
    transition: background-color 0.5s ease-in-out;
}
.submit:active{
    background-color : rgb(26, 149, 31);
    color: white;
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
.passkey-icon{
    position: relative;
    top: -0px;
    right: 30px;
}
.confirm-passkey-icon {
    position: relative;
    top: -0px;
    right: 30px;
}
</style>
</head>
<body>
    

    <form method="post" action="process-reset-passkey.php" id="resetPasskeyForm">
        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

        <label for="passkey">
        Enter a 6 digits passkey
        <input type="password" placeholder="Passkey" id="passkey" name="passkey" class="passkey" inputmode="numeric" maxlength="6">
        <i id="togglePasskey" class="fa fa-eye passkey-icon"></i>
        <br>
        <span class="error-message" id="passkeyError"></span>
        </label>
        <br>
        <label for="confirmPasskey">
        <input type="password" id="confirmPasskey" placeholder="Confirm Passkey" maxlength="6">
        <i id="toggleConfirmPasskey" class="fa fa-eye confirm-passkey-icon"></i>
        <br>
        <span class="error-message" id="confirmPasskeyError"></span>
        <br>
        </label>
        <input type="submit" id="submit" class="submit" value="Change">
    </form>
<script>
const togglePasskey = document.querySelector('#togglePasskey');
const passkey = document.querySelector('#passkey');

togglePasskey.addEventListener('click', function(e) {
const type = passkey.getAttribute('type') === 'password' ? 'text' : 'password';
passkey.setAttribute('type', type);
this.classList.toggle('fa-eye-slash');
})

const toggleConfirmPasskey = document.querySelector('#toggleConfirmPasskey');
const confirmPasskey = document.querySelector('#confirmPasskey');

toggleConfirmPasskey.addEventListener('click', function(e) {
const type = confirmPasskey.getAttribute('type') === 'password' ? 'text' : 'password';
confirmPasskey.setAttribute('type', type);
this.classList.toggle('fa-eye-slash')
})

const form = document.getElementById("resetPasskeyForm");

function validateForm() {
const passkey = document.getElementById("passkey").value;
const confirmPasskey = document.getElementById("confirmPasskey").value;

const passkeyError = document.getElementById("passkeyError");
const confirmPasskeyError = document.getElementById("confirmPasskeyError")
passkeyError.textContent = "";
confirmPasskeyError.textContent = "";
const regexPasskey = /^[0-9]{6}$/;
let isValid = true;

if (passkey === "" || passkey.length < 6 || regexPasskey.test(passkey) === false ) {
    passkeyError.textContent = "Passkey must be 6 digits";
    isValid = false;
}

if (confirmPasskey !== passkey) {
    confirmPasskeyError.textContent = "Passkey does not match";
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
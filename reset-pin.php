<?php
 $token = $_GET['token'];

 $token_hash = hash('sha256', $token);

 require __DIR__ . '/db.php';

 $sql = "SELECT * FROM bank_user_data WHERE reset_token_hash = ?";

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
    <title>Reset pin</title>
    <link rel="shortcut icon" href="Assets/bank-logo-index.svg" type="image/x-icon">

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
.pin-icon{
    position: relative;
    top: -0px;
    right: 30px;
}
.confirm-pin-icon {
    position: relative;
    top: -0px;
    right: 30px;
}
</style>
</head>
<body>
    

    <form method="post" action="process-reset-pin.php" id="resetPinForm">
        <fieldset>
        <legend>New Pin</legend>
            <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
    
            <label for="pin">
            Enter a 4 digits pin
            <input type="password" placeholder="Pin" id="pin" name="pin" class="pin" inputmode="numeric" maxlength="4">
            <i id="togglePin" class="fa fa-eye pin-icon"></i>
            <br>
            <span class="error-message" id="pinError"></span>
            </label>
            <br>
            <label for="confirmPin">
            <input type="password" id="confirmPin" placeholder="Confirm Pin" maxlength="4">
            <i id="toggleConfirmPin" class="fa fa-eye confirm-pin-icon"></i>
            <br>
            <span class="error-message" id="confirmPinError"></span>
            <br>
            </label>
            <input type="submit" id="submit" value="Change">
        </fieldset>
    </form>
<script>
const togglePin = document.querySelector('#togglePin');
const pin = document.querySelector('#pin');

togglePin.addEventListener('click', function(e) {
const type = pin.getAttribute('type') === 'password' ? 'text' : 'password';
pin.setAttribute('type', type);
this.classList.toggle('fa-eye-slash');
})

const toggleConfirmPin = document.querySelector('#toggleConfirmPin');
const confirmPin = document.querySelector('#confirmPin');

toggleConfirmPin.addEventListener('click', function(e) {
const type = confirmPin.getAttribute('type') === 'password' ? 'text' : 'password';
confirmPin.setAttribute('type', type);
this.classList.toggle('fa-eye-slash')
})

const form = document.getElementById("resetPinForm");

function validateForm() {
const pin = document.getElementById("pin").value;
const confirmPin = document.getElementById("confirmPin").value;

const pinError = document.getElementById("pinError");
const confirmPinError = document.getElementById("confirmPinError")
pinError.textContent = "";
confirmPinError.textContent = "";
const regexPin = /^[0-9]{4}$/;
let isValid = true;

if (pin === "" || pin.length < 4 || regexPin.test(pin) === false ) {
    pinError.textContent = "Pin must be 4 digits";
    isValid = false;
}

if (confirmPin !== pin) {
    confirmPinError.textContent = "Pin does not match";
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
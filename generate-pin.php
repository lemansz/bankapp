<?php
 session_start();
 
 require __DIR__ . '/db.php';

 if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $pin = clean_input($_POST['pin']);
    if ($pin !== false){
        $hashed_pin = password_hash($pin, PASSWORD_DEFAULT);
    }
    $sql = "UPDATE bank_user_data SET hashed_pin = ? WHERE id = ?";
   
    $stmt = $conn->stmt_init();
   
    if (!$stmt->prepare($sql)){
       die ("SQL error: " . $conn->error);
    }
    
    $stmt->bind_param('si', $hashed_pin, $_SESSION['user_id']);
    
    if ($stmt->execute()){
        $pin_generated = true;
    }
 }

 function clean_input($data){
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');

    return $data;
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
        .pin{
            width: 50px;
            padding: 10px;
            letter-spacing: 5px;
        }
        .confirmPin{
            width: 50px;
            padding: 10px;
            letter-spacing: 5px;
        }
        h3{
            color: green;
        }
        .errorMessage{
            color: red;
        }
        form{
            width: 200px;

        }
        .create{
            padding: 8px;
            border: none;
            font-weight: bold;
            background-color: rgba(128, 128, 128, 0.30);
        }
        
    </style>
</head>
<body>
    <?php if (isset($pin_generated) && $pin_generated === true){ ?>
        <p>PIN generated successfully!</p>
        <p><a href="login.php">Login</a></p>
    
    <?php } else { ?>
    <h3 >You are almost there! To complete your sign up kindly create your 4 digit pin.</h3>
    <form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post" id="createPinForm">
        <fieldset>
            <legend>Create Pin</legend>
            <label for="Pin">
                Enter a 4 digit pin
                <br><br>
                <input type="password"id="pin" maxlength="4" class="pin" name="pin">
                <i id="togglePin" class="fa fa-eye pin-icon"></i>
                <br>
                <span id="pinError" class="errorMessage"></span>
            </label>
            <br>
            <label for="">
                Confirm pin
                <br><br>
          <input type="password"id="confirmPin" class="confirmPin" maxlength="4">
          <i id="toggleConfirmPin" class="fa fa-eye confirm-pin-icon"></i>
          <br>
          <span id="confirmPinError" class="errorMessage"></span>
        </label>
        <br><br>
        <input type="submit" value="Create" class="create">
    </fieldset>
    </form>
    <?php } ?>
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

 function validatePin(){
    const pin = document.getElementById('pin').value;
    const confirmPin = document.getElementById('confirmPin').value;

    const errorPin = document.getElementById('pinError');
    const errorConfirmPin = document.getElementById('confirmPinError');

    errorPin.textContent = '';
    errorConfirmPin.textContent = '';

    const regexPin = /^[0-9]{4}$/;
    let isValid = true;

    if (pin.trim() === "" || regexPin.test(pin) === false){
        errorPin.textContent = "Invalid Pin";
        isValid = false;
    }
    
    if (confirmPin !== pin){
        errorConfirmPin.textContent = "Pin does not match";
        isValid = false;
    }
  
    return isValid;
    
 }

const form = document.getElementById("createPinForm");

form.addEventListener('submit', (e) => {
    if (validatePin() === false){
        e.preventDefault()
    }
});
</script>
</body>
</html>
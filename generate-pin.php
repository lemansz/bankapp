<?php
 session_start();
 require __DIR__ . '/db.php';

 
 if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    
    session_regenerate_id(true);
    
    $pin = clean_input($_POST['pin']);

    if (empty($pin)){
        die ("Pin required");
    } else if (!preg_match('/^\d{4}$/', $pin)){
        die ("Invalid pin");
    }
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
        @import url('https://fonts.googleapis.com/css2?family=Cute+Font&family=Parkinsans:wght@300..800&family=Red+Hat+Text:ital,wght@0,300..700;1,300..700&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Stylish&family=Varela&display=swap');
        
        *{
            box-sizing: border-box;
            font-family: 'Roboto', sans-serif;
            font-optical-sizing: auto;
            font-style: normal;
        }
        body{
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding-top: 8rem;
        }
        input:hover{
            border-color: #333;
        }
        .pin{
            width: 80px;
            padding: 10px;
            letter-spacing: 5px;
            background-color: white;
            border: none;
            border-radius: 0.5rem;
            margin-bottom: 0.6rem;
            margin-top: 0.6rem;
            border: 1px solid #ccc;
            text-align: center;
        }
        .confirmPin{
            width: 80px;
            padding: 10px;
            letter-spacing: 5px;
            background-color: white;
            border: none;
            border-radius: 0.5rem;
            margin-bottom: 0.6rem;
            margin-top: 0.6rem;
            border: 1px solid #ccc;
            text-align: center;
        }
        h3{
            color: green;
            text-align: center;
        }
        .errorMessage{
            color: red;
        }
        form{
            width: 200px;
            border-radius: 0.75rem;
            background-color: rgb(240, 240, 240);
            padding-top: 0.9rem;
            margin-top: 0.5rem;
            padding-bottom: 0.5rem;
            margin-bottom: 0.5rem;
            padding-left: 2rem;
        }
        .create{
            border-radius: 0.5rem;
            border: none;
            padding: 0.75rem;
            font-size: large;
            background-color: white;
            transition: background-color 0.5s ease-in-out;
            border: 1px solid #ccc;
        }
        .create:active{
            background-color: green;
            color: white;
        }
        .login{
            text-decoration: none;
            border: 1px solid #ccc;
            padding: 0.5rem;
            border-radius: 0.5rem;
        }
        .login:hover{
            border-color: #333;;
        }
        .login:active{
            background-color: green;
            color: white;
        }
    </style>
</head>
<body>
    <?php if (isset($pin_generated) && $pin_generated === true){ ?>

        <p style="color: green;">PIN generated successfully!</p>
        
        <p><a href="login.php" class="login">Login</a></p>
    
    <?php } else { ?>
    <h3 >You are almost there! To complete your registration kindly create your 4 digit pin.</h3>
    <form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post" id="createPinForm">
            <label for="Pin">
                Enter 4 digit Pin
                <br>
                <p></p>
                <input type="password"id="pin" maxlength="4" class="pin" name="pin" inputmode="numeric">
                <i id="togglePin" class="fa fa-eye pin-icon"></i>
                <br>
                <span id="pinError" class="errorMessage"></span>
                <br>
            </label>
            <label for="">
                Confirm pin
                <br>
          <input type="password"id="confirmPin" class="confirmPin" maxlength="4" inputmode="numeric">
          <i id="toggleConfirmPin" class="fa fa-eye confirm-pin-icon"></i>
          <br>
          <span id="confirmPinError" class="errorMessage"></span>
            <br>
        </label>
        <input type="submit" value="Create" class="create" id="create">
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
        errorConfirmPin.textContent = "Pins do not match";
        isValid = false;
    }
  
    return isValid;
    
 }

const form = document.getElementById("createPinForm");

form.addEventListener('submit', (e) => {
    if (validatePin() === false){
        e.preventDefault()
    } else {
        let create = document.getElementById('create');
        create.style.backgroundColor = 'rgb(26, 149, 31)';
        create.style.color = 'white';
    }
});
</script>
</body>
</html>
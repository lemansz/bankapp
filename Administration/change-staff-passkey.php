<?php 

 session_start();

 if (!isset($_SESSION['staff_id']))
 {
     die("Unauthorized access.");
    }
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && (!empty($_POST))){
    
    session_regenerate_id(true);
    require dirname(__FILE__) . '/../db.php';
    
    $passkey = clean_input($_POST['passkey']);

    if (empty($passkey)){
        die ("Passkey required");
    } else if (!preg_match('/^\d{6}$/', $passkey)){
        die ("Invalid passkey");
    }
    if ($passkey !== false){
        $hashed_passkey = password_hash($passkey, PASSWORD_DEFAULT);
    }
    $sql = "UPDATE staff SET staff_pass_key = ? WHERE staff_id = ?";
   
    $stmt = $conn->stmt_init();
   
    if (!$stmt->prepare($sql)){
       die ("SQL error: " . $conn->error);
    }
    
    $stmt->bind_param('si', $hashed_passkey, $_SESSION['staff_id']);
    
    if ($stmt->execute()){
        $passkey_updated = true;
        $stmt->close();
    }

 }

 function clean_input($data){
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
 }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../Assets/bank-logo-index.svg" type="image/x-icon">
    <title></title>
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

        .passkey{
            padding: 8px;
            background-color: white;
            border: none;
            border-radius: 0.5rem;
            margin-bottom: 0.6rem;
            margin-top: 0.6rem;
            border: 1px solid #ccc;
            text-align: center;
        }
        .confirmPasskey{
            padding: 8px;
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
            width: 250px;
            border-radius: 0.75rem;
            background-color: rgb(240, 240, 240);
            padding-top: 0.9rem;
            margin-top: 0.5rem;
            padding-bottom: 0.5rem;
            margin-bottom: 0.5rem;
            padding-left: 2rem;
        }
        .change{
            border-radius: 0.5rem;
            border: none;
            padding: 0.30rem;
            font-size: large;
            background-color: white;
            transition: background-color 0.5s ease-in-out;
            border: 1px solid #ccc;
        }
        .change:active{
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
<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post" id="createNewPasskeyForm">
    <label for="new-pass-key">
        <input type="text" id="passkey" class="passkey" name="passkey" inputmode="numeric" placeholder="New passkey" maxlength="6">
        <br>
        <i id="togglePasskey" class="fa fa-eye passkey-icon"></i>
        <span id="passkeyError" class="errorMessage"></span>
    </label>
    <br>
    <label for="confirm-pass-key">
        <input type="text" id="confirmPasskey" class="confirmPasskey" inputmode="numeric" placeholder="Cofirm passkey" maxlength="6">
        <i id="toggleConfirmPasskey" class="fa fa-eye confirm-passkey-icon"></i>
        <br>
        <span id="confirmPasskeyError" class="errorMessage"></span>
    </label>
    <?php 
    if (!empty($passkey_updated)){
        $_SESSION = []; 
        session_destroy();
        header("Location: staff-login.php?message=Passkey changed successfully!");
        exit;
    }
    ?>
    <br><br>
    <input type="submit" id="change" class="change" value="Change">
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

  function validatePasskey(){
    const passkey = document.getElementById('passkey').value;
    const confirmPasskey = document.getElementById('confirmPasskey').value;

    const errorPasskey = document.getElementById('passkeyError');
    const errorConfirmPasskey = document.getElementById('confirmPasskeyError');

    errorPasskey.textContent = '';
    errorConfirmPasskey.textContent = '';

    const regexPasskey = /^[0-9]{6}$/;
    let isValid = true;

    if (passkey.trim() === "" || regexPasskey.test(passkey) === false){
        errorPasskey.textContent = "Invalid passkey.";
        isValid = false;
    }
    
    if (confirmPasskey !== passkey){
        errorConfirmPasskey.textContent = "Passkeys do not match.";
        isValid = false;
    }
  
    return isValid;
    
 }

const form = document.getElementById("createNewPasskeyForm");

form.addEventListener('submit', (e) => {
    if (validatePasskey() === false){
        e.preventDefault()
    } else {
        let change = document.getElementById('change');
        change.style.backgroundColor = 'rgb(26, 149, 31)';
        change.style.color = 'white';
    }
});
</script>
<script src="check-staff-session.js"></script>
<script>
let activityTimeout;
function sendActivityUpdate() {
    const xhr = new XMLHttpRequest();
    xhr.open("GET", "check-staff-session.php?update=1&t=" + new Date().getTime(), true);
    xhr.send();
}
function activityDetected() {
    clearTimeout(activityTimeout);
    sendActivityUpdate();
    activityTimeout = setTimeout(() => {}, 60000);
}
window.addEventListener('mousemove', activityDetected);
window.addEventListener('keydown', activityDetected);
window.addEventListener('click', activityDetected);
window.addEventListener('touchstart', activityDetected);
</script>
</body>
</html>
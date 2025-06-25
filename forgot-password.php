<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot password</title>
    <link rel="shortcut icon" href="Assets/bank-logo-index.svg" type="image/x-icon">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cute+Font&family=Parkinsans:wght@300..800&family=Red+Hat+Text:ital,wght@0,300..700;1,300..700&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Stylish&family=Varela&display=swap');

body{
    display: flex;
    align-items: center;
    flex-direction: column;
    margin: 0;
    font-family: "Roboto", sans-serif;
    font-optical-sizing: auto;
    font-style: normal;
    width: 100vw;
    height: 100vh;
    box-sizing: border-box;
}
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
.send{
    margin-top: 6px;
    font-family: "Roboto", serif;
    font-weight: 400;
    font-style: normal;
    width: 100px;
    transition: background-color 0.5s ease-in-out;
    background-color: white;
}

.send:hover{
    border-color: #333;
}
.send:active{
    background-color: green;
    color: white;
}

.logo-box{
    width: 100%;
    height: 200px;
    background-image: url('./Assets/bank-logo-index.svg');
    background-position: center;
    background-repeat: no-repeat;
}

@media screen and (max-width: 600px) {
 .logo-box {
    background-image: url('Assets/bank-logo-index-mobile.svg');
 }
 body {
    align-items: center; /* center horizontally */
    justify-content: flex-start; /* push up vertically */
    padding-top: 1.5rem;
    height: auto;
 }
}
.message{
    width: 20.75rem;
    height: 6.25rem;
    border: none;
    border-radius: 0.5rem;
    background-color: rgba(0, 128, 0, 0.153);
    margin-left: auto;
    margin-right: auto;
    padding-top: 0.6rem;
    padding-bottom: 1rem;
}

.message p {
    text-align: center;
}
.dismiss-button{
    border-radius: 0.5rem;
    border: 1px solid hsl(0, 0%, 80%);
    padding: 0.60rem;
    font-size: large;
    background-color: white;
    float: right;
    margin-right: 0.5rem;
    cursor: pointer;
}

.dismiss-button:hover{
    border-color: #333;
}
.dismiss-button:active{
   background-color: hsl(116, 49%, 63%);
}
</style>
</head>
<body>
    <div class="logo-box">
        
    </div>
    <?php  if (isset($_GET['message'])) {
    $message = htmlspecialchars($_GET['message'], ENT_QUOTES, 'UTF-8');
    echo "<div class='message' id='message'>
    <p id='message'>" . htmlspecialchars($message, ENT_QUOTES, 'UTF-8') . "</p>
    <button id='dismiss-button' class='dismiss-button'>Dismiss</button>
    </div>";
    }
    ?>
    <script>

    if (document.getElementById('dismiss-button')){
        document.getElementById('dismiss-button').addEventListener('click', ()=>{
            document.getElementById('message').style.display = "none";
        })
    }

    function dismissMessage(){
        if (document.getElementById('message')){
            document.getElementById('message').style.display = "none";
        }
    }

    window.addEventListener('click', ()=>{dismissMessage()})
    </script>
    <h3>Enter associated email</h3>

    <form method="post" action="send-password-reset.php" id="forgotPasswordForm" novalidate>
        <label for="email">
           <p><input type="email" name="email" id="email" placeholder="johndoe@gmail.com"></p>
           <span id="emailError"></span> 
        </label>
        <br>
        <input type="submit" value="Send" id="send" class="send">
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
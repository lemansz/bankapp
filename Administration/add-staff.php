<?php
session_start();
session_regenerate_id(true);

if (isset($_SESSION['staff_id']) && $_SESSION['staff_role'] == "Branch Manager") {
    require "bank_stats.php";
} else {
    header("Location: staff-login.php?message=You are not authorized to access this page.");
    exit;
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Staff</title>
    <link rel="shortcut icon" href="../Assets/bank-logo-index.svg" type="image/x-icon">
</head>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Cute+Font&family=Parkinsans:wght@300..800&family=Red+Hat+Text:ital,wght@0,300..700;1,300..700&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Stylish&family=Varela&display=swap');
    *{
        font-family: 'Roboto', sans-serif;
        box-sizing: border-box;
        font-family: "Roboto", sans-serif;
        font-optical-sizing: auto;
         font-style: normal;
    }
    body
    {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        
    }
    form{
        padding-right: 2rem;
        padding-top: 2rem;
        padding-left: 2rem;
        border-radius: 0.75rem;
        background-color: rgb(240, 240, 240);
    }
    input 
    {
        padding: 1rem;
        border-radius: 4px;
        border: none;
        border: 1px solid hsl(0, 0%, 80%);
        margin-bottom: 6px;
        margin-top: 6px;
    }
    select{
        border-color: #333;
    }
    #submit{
        font-family: "Roboto", serif;
        font-weight: 400;
        font-style: normal;
        width: 100px;
        transition: background-color 0.5s ease-in-out;
    }

    #submit:active{
        background-color: green;
        color: white;
    }

    #submit:hover{
        border-color: #333;
    }
    .error-message
    {
        color: red;
    }
    .transaction-message{
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

.transaction-message p {
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

@media (max-width: 768px) {
    body {
        width: 100%; /* Ensure the body takes full width */
        padding: 0;
        margin: 0;
    }

    form {
        width: 90%; 
        max-width: 500px;
        padding: 1rem;
        margin: 0 auto; 
    }
  
    input[type="text"], 
    input[type="email"], 
    input[type="number"] {
        width: 100%; 
    }

    input[type="radio"] {
        width: auto;
    }
}
</style>
<body>
<?php  if (isset($_GET['message'])) {
    $message = htmlspecialchars($_GET['message'], ENT_QUOTES, 'UTF-8');
    echo "<div class='transaction-message' id='transaction-message'>
    <p id='message'>" . htmlspecialchars($message, ENT_QUOTES, 'UTF-8') . "</p>
    <button id='dismiss-button' class='dismiss-button'>Dismiss</button>
    </div>";
    }
?>
</div>
<script>

    if (document.getElementById('dismiss-button')){
        document.getElementById('dismiss-button').addEventListener('click', ()=>{
            document.getElementById('transaction-message').style.display = "none";
        })
    }

    function dismissMessage(){
        if (document.getElementById('message')){
            document.getElementById('transaction-message').style.display = "none";
        }
    }

    window.addEventListener('click', ()=>{dismissMessage()})
</script>
    <h2>Add staff</h2>
    <form action="process-add-staff.php" id="add-staff-form" method="post">
        <label for="staff-surname">
            <input type="text" placeholder="Surname" name="staff-surname" id="staff-surname">
            <br>
            <span id="staff-surname-error" class="error-message"></span>
        </label>
    
        <label for="staff-first-name">
            <br>
            <input type="text" placeholder="First Name" name="first-name" id="staff-first-name">
            <br>
            <span id="staff-firstname-error" class="error-message"></span>
        </label>

        <label for="staff-phone-number">
            <br>
            <input type="text" placeholder="Phone number" name="staff-phone-no" id="staff-phone-no" maxlength="11" inputmode="numeric">
            <br>
            <span id="staff-phone-no-error" class="error-message"></span>
        </label>

        <label for="staff-email">
            <br>
            <input type="text" placeholder="johndoe@gmail.com" id="staff-email" name="staff-email">
            <br>
            <span id="staff-email-error" class="error-message"></span>
        </label>
        <label for="staff-role">
            <br>
            <select name="staff-role" id="staff-role">
                <option value="Account Manager">Account Manager</option>
                <option value="Branch Manager">Branch Manager</option>
                <option value="Cashier">Cashier</option>
            </select>
        </label>

        <label for="staff-gender">
            <br><br>
            <input type="radio" id="male" name="staff-gender" value="Male" required>
            <label for="male">Male</label>
            <br>
            <input type="radio" id="female" name="staff-gender" value="Female" required>
            <label for="female">Female</label>
            <br>
        </label>

        <br><br>
        <input type="submit" value="Add" id="submit" class="submit">
    </form>

<script>
const form = document.getElementById('add-staff-form');

function validateForm()
{
    const staffSurname = document.getElementById('staff-surname').value;
    const staffFirstName = document.getElementById('staff-first-name').value;
    const staffPhoneNumber = document.getElementById('staff-phone-no').value;
    const staffEmail = document.getElementById('staff-email').value;

    const staffSurnameError = document.getElementById('staff-surname-error');
    const staffFirstNameError = document.getElementById('staff-firstname-error');
    const staffPhoneNumberError = document.getElementById('staff-phone-no-error');
    const staffEmailError = document.getElementById('staff-email-error');

    staffSurnameError.textContent = "";
    staffFirstNameError.textContent = "";
    staffPhoneNumberError.textContent = "";
    staffEmailError.textContent = "";

    const regexEmail =  /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    const regexName = /^[a-zA-Z]+$/;
    const regexPhoneNumber = /^[0-9]{11}$/;
    let isValid = true;

    if (staffSurname === ""){
        isValid = false;
        staffSurnameError.textContent = "Surname is required.";
    } else if (!regexName.test(staffSurname)){
        isValid = false;
        staffSurnameError.textContent = "Surname should contain only letters.";
    }

    if (staffFirstName === ""){
        isValid = false;
        staffFirstNameError.textContent = "First name is required.";
    } else if (!regexName.test(staffFirstName)){
        isValid = false;
        staffFirstNameError.textContent = "First name should contain only letters.";
    }

    if (staffPhoneNumber === ""){
        isValid = false;
        staffPhoneNumberError.textContent = "Phone number is required.";
    } else if (!regexPhoneNumber.test(staffPhoneNumber)){
        isValid = false;
        staffPhoneNumberError.textContent = "Phone number is invalid.";
    }

    if (staffEmail === ""){
        isValid = false;
        staffEmailError.textContent = "Email is required";
    } else if (!regexEmail.test(staffEmail)){
        isValid = false;
        staffEmailError.textContent = "Invalid email";
    }


    return isValid;    
}

form.addEventListener('submit', (e)=>{
  if (validateForm() === false){
    e.preventDefault();
 }
})

window.onload = function() {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('error')) {
        const error = urlParams.get('error');
        if (error === 'email_taken') {
            document.getElementById('staff-email-error').textContent = "Email is in use by another staff member.";
        }
    }
    const newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
    window.history.replaceState({}, document.title, newUrl);
}
</script>
</body>
</html>


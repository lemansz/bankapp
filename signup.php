<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="signup.css">
    <link rel="shortcut icon" href="Assets/bank-logo-index.svg" type="image/x-icon">
</head>
<body>
<div style="margin-left: auto; margin-right: auto; height: 100px; margin-bottom: 0.8rem;">
    <img src="Assets/bank-logo-index.svg" alt="Silver Bank Logo" class="logo">
</div>


<form action="process-signup.php" method="post" id="regForm" novalidate>
    <label for="surname">
        <input type="text" placeholder="Surname" name="surname" id="surname"> 
        <br>
        <span class="error-message" id="surnameError"></span>
    </label>
    <label for="firstName">
        <input type="text" placeholder="First Name" name="firstName" id="firstName"> 
        <br>
        <span class="error-message" id="firstNameError"></span>
    </label>
    <label for="email">
        <input type="text" placeholder="Johndoe@gmail.com" name="email" id="email">
        <br> 
        <span class="error-message" id="emailError"></span>
    </label>
    <label for="phone-number">
        <input type="text" placeholder="Phone Number" name="phoneNumber" id="phoneNumber" inputmode="numeric" maxlength="11">
        <br>
        <span class="error-message" id="phoneNumberError"></span> 
    </label>
    <label for="password">
        <input type="password" placeholder="Password" id="password" name="password" class="passwordInput">
        <i id="togglePassword" class="fa fa-eye password-icon"></i>
        <br>
        <span class="error-message" id="passwordError"></span>
    </label>
    <label for="confirmPassword">
        <input type="password" id="confirmPassword" placeholder="Confirm Password">
        <i id="toggleConfirmPassword" class="fa fa-eye confirm-password-icon"></i>
        <span class="error-message" id="confirmPasswordError"></span>
        <span id="email-taken" class="error-message"></span>
    </label>
    <br>
    <input type="submit" value="Submit" class="submit" id="submit">
</form>


<script src="signup.js"></script>
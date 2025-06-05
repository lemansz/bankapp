<?php

 $is_invalid = false;

 if ($_SERVER["REQUEST_METHOD"] === "POST") {
    require __DIR__ . "/db.php";

    $email = clean_input($_POST['email']);

    
    $sql = "SELECT * FROM bank_user_data WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    $user = $result->fetch_assoc();
    
    if (isset($user)) {
       if (password_verify(htmlspecialchars($_POST['password']), $user['hashed_password'])){
        session_start();
        session_regenerate_id(true);

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['last_activity'] = time(); // Set last activity on login
        header('Location: index.php');
        exit;
       }
    }

    $is_invalid = true;
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
    <title>Login | Silver Bank</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="style-login.css">
    <link rel="shortcut icon" href="./Assets/bank-logo-index.svg" type="image/x-icon">
</head>
<body>
    <?php  if (isset($_GET['message'])) {
    $message = htmlspecialchars($_GET['message'], ENT_QUOTES, 'UTF-8');
    echo "<div class='password-reset-message' id='password-reset-message'>
    <p id='message'>" . htmlspecialchars($message, ENT_QUOTES, 'UTF-8') . "</p>
    <button id='dismiss-button' class='dismiss-button'>Dismiss</button>
    </div>
    
    <script>
     document.getElementById('dismiss-button').addEventListener('click', ()=>{
        document.getElementById('password-reset-message').style.display = 'none';
        const url = new URL(window.location.href);
        url.search = '';
        window.history.pushState({}, '', url.href);
     })
    </script>
    ";
    }
    ?>

    <div class="logo-box">
        
    </div>
    <br>
    <form id="loginForm" method="post">
            <label for="email">
                <input type="email" placeholder="Email" name="email" id="email" 
                    value="<?= isset($_POST['email']) ? htmlspecialchars($_POST["email"]) : ""?>">
            </label>
            <br><br>
            <label for="password">
                <input type="password" placeholder="Password" name="password" id="password">
                <i id="togglePassword" class="fa fa-eye password-icon"></i>
            </label>
            <br>
            <?php if ($is_invalid):?>
            <br>
            <span style="color: red;">Invalid Login</span>
            <?php endif;?>
            <p style="margin-top: 1px;"><input type="submit" value="Login" id="login"></p>
            <a href="forgot-password.php" style="text-decoration: none; color: green;">Forgot password?</a>
            <a href="signup.php" style="text-decoration: none; color: green; margin-left: 0.8rem;">Sign up</a>
    </form>



    <script>    
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');
    togglePassword.addEventListener('click', function(e) {
    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
    password.setAttribute('type', type);
    this.classList.toggle('fa-eye-slash');
    })
    </script>
</body>
</html>
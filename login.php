<?php


 $is_invalid = false;

 if ($_SERVER["REQUEST_METHOD"] === "POST") {
    require __DIR__ . "/db.php";
    
    $sql = sprintf("SELECT * FROM bank_user_data WHERE email = '%s'", $conn->real_escape_string($_POST['email']));
    $result = $conn->query($sql);

    $user = $result->fetch_assoc();
    
    if (isset($user)) {
       if (password_verify(htmlspecialchars($_POST['password']), $user['hashed_password'])){

        session_start();

        session_regenerate_id();

        $_SESSION['user_id'] = $user['id'];
        
        header('Location: index.php');
        exit;
       }
    }

    $is_invalid = true;
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
        display: grid;
        place-items: center;
        justify-content: center;
        height: 100vh;
        overflow: hidden;
    }
    form{
        width: 200px;
        border-radius: 8px;
        transition: border-color 0.3s ease;
        width: 200px;
    }
    input{
        padding: 1rem;
        border-radius: 8px;
        border: 1px solid #ccc;
          
    }
    input:hover{
        border-color: #333;
    }
        ::placeholder{
        font-family: "Roboto", serif;
        font-weight: 400;
        font-style: normal;

    }
    #login{
        margin-top: 1rem;
    }
    .error-message{
        color: red;  
    }
    .password-icon{
    position: relative;
    bottom: 35px;
    left: 170px;

    }
    </style>
</head>
<body>
    <form id="loginForm" method="post" novalidate>
        <fieldset>
            <legend>Login</legend>
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
            <span class="error-message" id="login-error"></span>

            <?php if ($is_invalid):?>
            <span style="color: red;">Invalid Login</span>
            <?php endif;?>

            <p style="margin-top: 1px;"><input type="submit" value="Login" id="login"></p>
            <a href="forgot-password.php" style="text-decoration: none;">Forgot password?</a>
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
    </script>
</body>
</html>
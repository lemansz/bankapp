<?php

 $is_invalid = false;
 
 if ($_SERVER["REQUEST_METHOD"] === "POST") {

    require dirname(__FILE__) . '/../db.php';
    
    $sql = sprintf("SELECT * FROM staff WHERE staff_email = '%s'", $conn->real_escape_string($_POST['staff-email']));
    $result = $conn->query($sql);

    $staff = $result->fetch_assoc();
    //
    if (!empty($staff)) {
        $post_passkey = isset($_POST['passkey']) ? htmlspecialchars($_POST['passkey']) : '';
        if (password_verify($post_passkey, $staff['staff_pass_key'])) {
            if ($staff['staff_role'] === "Cashier") {
                session_start();
                $_SESSION['staff_id'] = $staff['staff_id'];
                $_SESSION['staff_role'] = $staff['staff_role'];
                $_SESSION['last_activity'] = time(); // Set last activity on login
                header('Location: staff-cashier.php');
                exit;
            } else if ($staff['staff_role'] === "Branch Manager"){
                session_start();
                $_SESSION['staff_id'] = $staff['staff_id'];
                $_SESSION['staff_role'] = $staff['staff_role'];
                $_SESSION['last_activity'] = time(); // Set last activity on login
                header('Location: admin-index.php');
                exit;
            } else if ($staff['staff_role'] === "Customer Service Representative"){
                session_start();
                $_SESSION['staff_id'] = $staff['staff_id'];
                $_SESSION['staff_role'] = $staff['staff_role'];
                $_SESSION['last_activity'] = time(); // Set last activity on login
                header('Location: customer-service-rep.php');
                exit;
            }
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
    <link rel="shortcut icon" href="../Assets/bank-logo-index.svg" type="image/x-icon">
    <title>Staff Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="style-staff-login.css">
</head>
<body>
<?php  if (isset($_GET['message'])) {
    $message = htmlspecialchars($_GET['message'], ENT_QUOTES, 'UTF-8');
    echo "<div class='passkey-reset-message' id='passkey-reset-message'>
    <p id='message'>" . htmlspecialchars($message, ENT_QUOTES, 'UTF-8') . "</p>
    <button id='dismiss-button' class='dismiss-button'>Dismiss</button>
    </div>
    
    <script>
     document.getElementById('dismiss-button').addEventListener('click', ()=>{
        document.getElementById('passkey-reset-message').style.display = 'none';
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

<form id="loginForm" method="post" novalidate>
    <label for="staff-email">
        <input type="email" placeholder="Email" name="staff-email" id="staff-email"
            value="<?= isset($_POST['staff-email']) ? htmlspecialchars($_POST["staff-email"]) : ""?>">
    </label>
    <br><br>
    <label for="passkey">
        <input type="password" placeholder="Passkey" name="passkey" id="passkey" maxlength="6" inputmode="numeric">
        <i id="togglePasskey" class="fa fa-eye passkey-icon"></i>
    </label>
    <?php if ($is_invalid):?>
    <br>
    <span style="color: red;">Invalid Login</span>
    <?php endif;?>

    <p style="margin-top: 1px;"><input type="submit" value="Login" id="login"></p>
    <a href="forgot-passkey-staff.php">Forgot passkey?</a>
</form>

<script>    
    const togglePasskey = document.querySelector('#togglePasskey');
    const passkey = document.querySelector('#passkey');
    togglePasskey.addEventListener('click', function(e) {
    const type = passkey.getAttribute('type') === 'password' ? 'text' : 'password';
    passkey.setAttribute('type', type);
    this.classList.toggle('fa-eye-slash');
    })
</script>
</body>
</html>
<?php
 session_start();

 if (isset($_SESSION['staff_id']) && $_SESSION['staff_role'] === 'Cashier') {
    
    session_regenerate_id(true);
    require dirname(__FILE__) . '/../db.php';

    $stmt = $conn->prepare("SELECT * FROM staff WHERE staff_id = ?");
    $stmt->bind_param("i", $_SESSION['staff_id']);
    $stmt->execute();
    $staff = $stmt->get_result()->fetch_assoc();
   
}  else {
    header("Location: staff-login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cashier</title>
    <link rel="shortcut icon" href="../Assets/bank-logo-index.svg" type="image/x-icon">
    <link rel="stylesheet" href="style-staff-cashier.css">
</head>
<body>
<?php  if (isset($_GET['message'])) {
    $message = htmlspecialchars($_GET['message'], ENT_QUOTES, 'UTF-8');
    echo "<div class='transaction-message' id='transaction-message'>
    <p id='message'>" . htmlspecialchars($message, ENT_QUOTES, 'UTF-8') . "</p>
    <button id='dismiss-button' class='dismiss-button'>Dismiss</button>
    </div>";
    }
?>
    <div class="parent-container">
   <div class="log-out"><a href="staff-log-out.php" title="Log out"></a></div>
   <h1 class="staff-name-role"><?= $staff['staff_surname'] . " " . $staff['staff_first_name']?> (cashier)</h1>
   

   
   <div class="operation-parents">
        <button id="deposit" class="deposit-button"><span class="deposit-span">Deposit</span></button>
        <button id="withdraw" class="withdraw-button"><span class="withdraw-span">Withdraw</span></button>
    </div>

    <br><br>
   <form id="deposit-form" class="deposit-form" method="post" action="process-deposit.php">

        <label for="account no" class="deposit-input">
          <input type="text" placeholder="Account no" oninput="showUserDeposit(this.value)" maxlength="10" id="deposit-account-no" inputmode="numeric"  name="deposit-account-no">
          <br>
          <span id="deposit-account-no-error" class="error-message"></span>
          <br>
        </label>

        <label for="amount" class="deposit-input">
            <input type="text" id="deposit-amount" class="amount" oninput="addCommasDeposit(this.value)"  placeholder="(₦) Amount" name="deposit-amount" class="amount" inputmode="numeric">
            <br>
            <span id="deposit-amout-error" class="error-message"></span>
            <br>
        </label>
        
        <span class="deposit-recipient" id="deposit-recipient"></span>

        <label for="deposit-pin" class="deposit-input">
            <br><br>
            <input type="password" placeholder="Pin" maxlength="4" name="deposit-pin" class="deposit-pin" id="deposit-pin" inputmode="numeric">
            <br>
            <span id="deposit-pin-error" class="error-message"></span>
            <i id="togglePin" class="fa fa-eye pin-icon"></i>
        </label>

        <br><input type="submit" id="deposit-btn" value="Deposit" class="deposit-btn">
        <input type="button" value="Cancel" class="cancel" id="cancel-deposit"></input>
    </form>


    <form id="withdraw-form" class="withdraw-form" method="post" action="process-withdraw.php">

        <label for="account no" class="withdraw-input">
        <input type="text" placeholder="Account no" oninput="showUserWithdraw(this.value)" maxlength="10" id="withdraw-account-no" inputmode="numeric"  name="withdraw-account-no">
        <br>
        <span id="withdraw-account-no-error" class="error-message"></span>
        <br>
        </label>

        <label for="amount" class="withdraw-input">
            <input type="text" id="withdraw-amount" class="amount" oninput="addCommasWithdraw(this.value)"  placeholder="(₦) Amount" name="withdraw-amount" class="amount" inputmode="numeric">
            <br>
            <span id="withdraw-amout-error" class="error-message"></span>
            <br>
        </label>

        <span class="withdraw-recipient" id="withdraw-recipient"></span>

        <label for="withdraw-pin" class="withdraw-input">
            <br><br>
            <input type="password" placeholder="Pin" maxlength="4" name="withdraw-pin" class="withdraw-pin" id="withdraw-pin" inputmode="numeric">
            <br>
            <span id="withdraw-pin-error" class="error-message"></span>
            <i id="togglePin" class="fa fa-eye pin-icon"></i>
        </label>

        <br><input type="submit" id="withdraw-btn" value="Withdraw" class="withdraw-btn">
        <input type="button" value="Cancel" class="cancel" id="cancel-withdraw"></input>
    </form>

    <div class="change-passkey">
        <a href="change-staff-passkey.php">Change passkey?</a>
    </div>
   
   </div>
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

</script>

<script src="staff-cashier-message.js"></script>
<script src="staff-cashier.js"></script>
</body>
</html>
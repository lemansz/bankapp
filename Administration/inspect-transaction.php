<?php

session_start();

if (!isset($_SESSION['staff_id']) || $_SESSION['staff_role'] != "Branch Manager") {
    header("Location: staff-login.php?message=You are not authorized to access this page.");
    exit;
}



$found_transaction = false;

if ($_SERVER['REQUEST_METHOD'] === "POST"){
    require dirname(__FILE__) . '/../db.php';

    $transaction_id = clean_input($_POST['transaction_id']);

    if (empty($transaction_id)){
        die ('Transaction id required.');
    }

    $sql = "SELECT * FROM transactions WHERE transaction_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $transaction_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $transaction = $result->fetch_assoc();


    if (isset($transaction)){
        $found_transaction = true;
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
    <title>Find Transaction</title>
    <link rel="stylesheet" href="style-inspect-transaction.css">
    <link rel="shortcut icon" href="../Assets/bank-logo-index.svg" type="image/x-icon">
</head>
<body>

    <div class="topnav">
    <span class="hamburger" onclick="toggleNav()">☰</span>
    
    <a href="admin-index.php">Home
        <img src="../Assets/home.svg" alt="Home">
    </a>

    <a href="add-staff.php">Add a staff
        <img src="../Assets/add-employee-icon.svg" alt="Add staff">
    </a>

    <a href="find-customer.php">Find customer
        <img src="../Assets/find-customer-icon.svg" alt="Search customer">
    </a>

    <a href="inspect-transaction.php">Inspect Transaction
        <img src="../Assets/inspection-icon.svg" alt="Inspect transaction">
    </a>

    <a href="staff-log-out.php"> Log out
        <img src="../Assets/log-out-admin.svg" alt="Log out">
    </a>
    </div>

    <h2>Find Transaction</h2>

    <form action="<?php $_SERVER['PHP_SELF']?>" method="post" id="find-transaction-form">
        <div>
            <label for="transaction_id">
                <input type="text" id="transaction_id" class="transaction_id" inputmode="numeric" name="transaction_id" placeholder="Transaction ID">
            </label>
            <input type="submit" class="find" value="Find" name="find">
        </div>

        <span id="transaction_id-error" class="error-message"></span>
    </form>

    <!--Deposit template-->

    <?php if ($found_transaction && $transaction['transaction_type'] === 'Deposit'):?>
    <div id="transaction-info-block" class="transaction-info-block">
        <div id="x-sign" class="x-sign" style="cursor: pointer;">
        <span class="x-sign-element"> &#10005</span>
        </div>
        <table id="transaction-info-table">
            <tr>
                <th>Transaction Type</th>
                <th>Beneficiary</th>
                <th>Account no</th>
                <th>Amount</th>
                <th>Date & Time</th>
            </tr>

            <tr>
                <td><?= htmlspecialchars($transaction['transaction_type'])?></td>
                <td><?= htmlspecialchars($transaction['beneficiary_name'])?></td>
                <td><?= htmlspecialchars($transaction['beneficiary_account_no'])?></td>
                <td><?= "&#x20A6;" . htmlspecialchars(number_format($transaction['transaction_amount']))?></td>
                <td><?= htmlspecialchars($transaction['transaction_date'])?></td>
            </tr>
        </table>
    </div>

    </div>
    <?php
    echo "
    <script>
    document.getElementById('x-sign').addEventListener('click', (e)=>{
    document.getElementById('transaction-info-block').style.display = 'none';
    })
    </script>"
    ?>

    <!--Withdraw template-->
    <?php elseif($found_transaction && $transaction['transaction_type'] === 'Withdraw') :?>
        <div id="transaction-info-block" class="transaction-info-block">
            <div id="x-sign" class="x-sign" style="cursor: pointer;">
               <span class="x-sign-element"> &#10005</span>
            </div>
            <table id="transaction-info-table">
                <tr>
                    <th>Transaction Type</th>
                    <th>Beneficiary</th>
                    <th>Account no</th>
                    <th>Amount</th>
                    <th>Date & Time</th>
                </tr>

                <tr>
                    <td><?= htmlspecialchars($transaction['transaction_type'])?></td>
                    <td><?= htmlspecialchars($transaction['beneficiary_name'])?></td>
                    <td><?= htmlspecialchars($transaction['beneficiary_account_no'])?></td>
                    <td><?= "&#x20A6;" . htmlspecialchars(number_format($transaction['transaction_amount']))?></td>
                    <td><?= htmlspecialchars($transaction['transaction_date'])?></td>
                </tr>
            </table>
        </div>
<?php
echo "
<script>
document.getElementById('x-sign').addEventListener('click', (e)=>{
document.getElementById('transaction-info-block').style.display = 'none';
})
</script>"
?>


<?php elseif ($found_transaction && $transaction['transaction_type'] === 'Transfer'):?>
    <div id="transaction-info-block" class="transaction-info-block">
        <div id="x-sign" class="x-sign" style="cursor: pointer;">
            <span class="x-sign-element"> &#10005</span>
        </div>
        <table id="transaction-info-table">
            <tr>
                <th>Transaction Type</th>
                <th>Sender</th>
                <th>Beneficiary</th>
                <th>Account no (Sender)</th>
                <th>Accoun no (Beneficiary)</th>
                <th>Amount</th>
                <th>Date & Time</th>
            </tr>

            <tr>
                <td><?= htmlspecialchars($transaction['transaction_type'])?></td>
                <td><?= htmlspecialchars($transaction['sender_name'])?></td>
                <td><?= htmlspecialchars($transaction['beneficiary_name'])?></td>
                <td><?= htmlspecialchars($transaction['sender_account_no'])?></td>
                <td><?= htmlspecialchars($transaction['beneficiary_account_no'])?></td>
                <td><?= "&#x20A6;" . htmlspecialchars(number_format($transaction['transaction_amount']))?></td>
                <td><?= htmlspecialchars($transaction['transaction_date'])?></td>
            </tr>
        </table>
    </div>
    <?php
    echo "
    <script>
    document.getElementById('x-sign').addEventListener('click', (e)=>{
    document.getElementById('transaction-info-block').style.display = 'none';
    })
    </script>"
    ?>

<?php elseif ($found_transaction && $transaction['transaction_type'] === 'Airtime'):?>
    <div id="transaction-info-block" class="transaction-info-block">
        <div id="x-sign" class="x-sign" style="cursor: pointer;">
         <span class="x-sign-element"> &#10005</span>
        </div>
        <table id="transaction-info-table">
            <tr>
                <th>Transaction Type</th>
                <th>Sender</th>
                <th>Beneficiary</th>
                <th>Account no (Sender)</th>
                <th>Amount</th>
                <th>Network Provider</th>
                <th>Date & Time</th>
            </tr>

            <tr>
                <td><?= htmlspecialchars($transaction['transaction_type'])?></td>
                <td><?= htmlspecialchars($transaction['sender_name'])?></td>
                <td><?= htmlspecialchars($transaction['beneficiary_phone_no'])?></td>
                <td><?= htmlspecialchars($transaction['sender_account_no'])?></td>
                <td><?= "&#x20A6;" . htmlspecialchars(number_format($transaction['transaction_amount']))?></td>
                <td><?= htmlspecialchars($transaction['network_provider']) ?></td>
                <td><?= htmlspecialchars($transaction['transaction_date'])?></td>
            </tr>
        </table>
    </div>
    <?php
    echo "
    <script>
    document.getElementById('x-sign').addEventListener('click', (e)=>{
    document.getElementById('transaction-info-block').style.display = 'none';
    })
    </script>"
    ?>



<?php elseif ($found_transaction && $transaction['transaction_type'] === 'Data'):?>
    <div id="transaction-info-block" class="transaction-info-block">
        <div id="x-sign" class="x-sign" style="cursor: pointer;">
        <span class="x-sign-element"> &#10005</span>
        </div>

        <table id="transaction-info-table">
            <tr>
                <th>Transaction Type</th>
                <th>Sender</th>
                <th>Beneficiary</th>
                <th>Account no (Sender)</th>
                <th>Amount</th>
                <th>Network Provider</th>
                <th>Date & Time</th>
            </tr>

            <tr>
                <td><?= htmlspecialchars($transaction['transaction_type'])?></td>
                <td><?= htmlspecialchars($transaction['sender_name'])?></td>
                <td><?= htmlspecialchars($transaction['beneficiary_phone_no'])?></td>
                <td><?= htmlspecialchars($transaction['sender_account_no'])?></td>
                <td><?= "&#x20A6;" . htmlspecialchars(number_format($transaction['transaction_amount']))?></td>
                <td><?= htmlspecialchars($transaction['network_provider']) ?></td>
                <td><?= htmlspecialchars($transaction['transaction_date'])?></td>
            </tr>
        </table>
    </div>
    <?php
    echo "
    <script>
    document.getElementById('x-sign').addEventListener('click', (e)=>{
    document.getElementById('transaction-info-block').style.display = 'none';
    })
    </script>"
    ?>


<?php endif; ?>

    <?php if ($_SERVER['REQUEST_METHOD'] === "POST" && !$found_transaction): ?>
        <script>
            document.getElementById('transaction_id-error').innerHTML = "Transaction not found.";
        </script>
    <?php endif; ?>

<script>
function validateForm(){
    let isValid = true;

    const transactionId = document.getElementById('transaction_id').value;
    const transactionIdError = document.getElementById('transaction_id-error');

    transactionIdError.textContent = "";

    const regexTransactionId = /[0-9]/;

    if (transactionId.trim() === ""){
        isValid = false;
        transactionIdError.textContent = "Required";
    } else if (!regexTransactionId.test(transactionId)){
        isValid = false;
        transactionIdError.textContent = "Invalid transaction ID";
    }

    return isValid;
}

document.getElementById('find-transaction-form').addEventListener('submit', (e)=>{
    if (validateForm() === false){
        e.preventDefault()
        document.getElementById('transaction_id').focus();
            }
})

function toggleNav() {
  const topnav = document.querySelector('.topnav');
  const hamburger = document.querySelector('.hamburger');
  topnav.classList.toggle('active');
  if (topnav.classList.contains('active')) {
      hamburger.textContent = "✖"; // Change to close icon
  } else {
      hamburger.textContent = "☰"; // Change back to menu icon
  }
}
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
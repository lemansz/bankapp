<?php
 session_start();
 session_regenerate_id(true);
 
 if (isset($_SESSION['staff_id']) && $_SESSION['staff_role'] == "Branch Manager") {
     require "bank_stats.php";
 } else {
     header("Location: staff-login.php?message=You are not authorized to access this page.");
     exit;
 }


 $found_customer = false;
 
 if ($_SERVER['REQUEST_METHOD'] === "POST"){
    require dirname(__FILE__) . '/../db.php';

    $account_no = clean_input($_POST['account-no']);
    
    if (empty($account_no) || !preg_match('/\d{10}$/', $account_no)){
        die ('Account no required');
    }

    $sql = "SELECT * FROM bank_user_data WHERE account_number = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $account_no);
    $stmt->execute();
    $result = $stmt->get_result();

    $customer = $result->fetch_assoc();


    if (isset($customer)){
        $found_customer = true;
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
    <title>Find Customer</title>
    <link rel="shortcut icon" href="../Assets/bank-logo-index.svg" type="image/x-icon">
    <link rel="stylesheet" href="style-find-cutomer.css">
</head>
<body>
    <div class="topnav">
    <span class="hamburger" onclick="toggleNav()">☰</span>

    <a href="admin-index.php">Home
        <img src="../Assets/home.svg" alt="Search customer">
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
    <h2 style="margin-top: 5rem;">Find Customer</h2>

    <form action="<?php $_SERVER['PHP_SELF']?>" method="post" id="find-customer-form">
        <div>
            <label for="account-no">
                <input type="text" id="account-no" class="account-no" inputmode="numeric" name="account-no" placeholder="Account no" maxlength="10">
            </label>
            <input type="submit" class="find" value="Find" name="find">
        </div>
        
        <span id="account-no-error" class="error-message"></span>
    </form>

    <?php if ($found_customer):?>

    <div id="customer-info-parent" class="customer-info-parent">

    <div class="x-sign" id="x-sign">&#10005</div>
    <div class="table-responsive">
        <table id="customer-info-table">
            <tr>
                <th>Surname</th>
                <th>First Name</th>
                <th>Account no</th>
                <th>Balance</th>
                <th>Email</th>
                <th>Phone no</th>
            </tr>

            <tr>
                <td><?= htmlspecialchars($customer['surname'])?></td>
                <td><?= htmlspecialchars($customer['first_name'])?></td>
                <td><?= htmlspecialchars($customer['account_number'])?></td>
                <td><?= "&#x20A6;" . htmlspecialchars(number_format($customer['account_balance']))?></td>
                <td><?= htmlspecialchars($customer['email'])?></td>
                <td><?= htmlspecialchars($customer['phone_number'])?></td>
            </tr>
        </table>
    </div>

    </div>
    <?php 
    echo "
    <script>
    document.getElementById('x-sign').addEventListener('click', (e)=>{
    document.getElementById('customer-info-parent').style.display = 'none';
    })
    </script>"
    ?>
    <?php endif; ?>

    <?php if ($_SERVER['REQUEST_METHOD'] === "POST" && !$found_customer): ?>
        <script>
            document.getElementById('account-no-error').innerHTML = "Customer not found.";
        </script>
    <?php endif; ?>

    <script>
        function validateForm(){
            let isValid = true;

            const accountNo = document.getElementById('account-no').value;
            const accountNoError = document.getElementById('account-no-error');

            accountNoError.textContent = "";

            const regexAccountNumber = /^[0-9]{10}$/;

            if (accountNo.trim() === ""){
                isValid = false;
                accountNoError.textContent = "Required";
            } else if (!regexAccountNumber.test(accountNo)){
                isValid = false;
                accountNoError.textContent = "Invalid account number";
            }

            return isValid;
        }

        document.getElementById('find-customer-form').addEventListener('submit', (e)=>{
            if (validateForm() === false){
                e.preventDefault()
                document.getElementById('account-no').focus();
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
</script>
</body>
</html>
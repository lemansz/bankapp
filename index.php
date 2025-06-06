<?php
session_start();

if (isset($_SESSION['user_id'])) {
    require __DIR__ . "/transactions_history.php";

    $stmt = $conn->prepare("SELECT * FROM bank_user_data WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    $history = new Transaction_history;

    $history->sender_id = $_SESSION['user_id'];
    $history->beneficary_id = $_SESSION['user_id'];
   
} 


function greet (){
 date_default_timezone_set('Africa/Lagos');
 $current_hour = date("H");

 if ($current_hour >= 6 && $current_hour < 12){
    echo "Good morning, ";
 } elseif ($current_hour >= 12 && $current_hour < 18){
    echo "Good afternoon, ";
 } elseif($current_hour >= 18 || $current_hour < 6){
    echo "Good evening, ";
 }
}
 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="style-index.css">
    <link rel="shortcut icon" href="./Assets/bank-logo-index.svg" type="image/x-icon">

</head>
<body>

    <?php if (isset($user)) : ?> 
        <div class="root-container">

        <div class="logo-box">
        
        </div>

        
        <div class="log-out">
        <a href="logout.php" title="Log out"></a>
        </div>

        <div class="greet-block">
            <h1 class="greetings" id="greetings"><?= greet()?> <span class="gold-name-flicker" style="color: black;"><?=htmlspecialchars($user['first_name'])?></span></h1>
        </div>
   
        <div class="upper-bar">
        <h2 id="account-number" class="account-number"><?= htmlspecialchars($user['account_number']) ?></h2>
        <h3 class="balance-parent"><span>Balance: </span><span id="balance-val" class="balance-val">&#x20A6;<?=htmlspecialchars(number_format($user['account_balance'], 2)) ?></span></h3>
        <i id="toggleBalance" class="fa fa-eye-slash"></i>
        <br>
        <div class="transaction_history" id="transaction_history">
           <button class="history"><a href="transaction-history-chart.php">History</a></button> <br> <?php $history->fetch_transaction_history(); ?>
        </div>
    </div>
    <script>
        const hideBalance = document.getElementById('toggleBalance');
        const balanceVal = document.getElementById('balance-val');
        hideBalance.addEventListener('click', (e) => {
            if (balanceVal.textContent === "****") {
            balanceVal.textContent = "₦<?=htmlspecialchars(number_format($user['account_balance'], 2)) ?>";
            hideBalance.classList.remove('fa-eye');
            hideBalance.classList.add('fa-eye-slash');
            } else {
            balanceVal.textContent = "****";
            hideBalance.classList.remove('fa-eye-slash');
            hideBalance.classList.add('fa-eye');
            }
        });
    </script>
    <br><br>
    <div class="operation-parents">
        <button id="transfer" class="transfer-button"><span class="transfer-span">Transfer</span></button>
        <button id="airtime" class="airtime-purchase-button"><span class="airtime-span">Airtime</span></button>
        <button id="data" class="data-button"><span class="data-span">Data</span></button>
    </div>
    
    <div class="form-operation-parent">

        <form action="transfer.php" id="transfer_form" class="transfer_form" method="post">
            <label for="accountn no" class="transfer-inputs">
                <input type="text" placeholder="Account no." oninput="showUser(this.value)" maxlength="10" id="account_no" inputmode="numeric" pattern="[0-9]*" name="recipient">
            </label>
            <br><br>
            <label for="amount" class="transfer-inputs">
                <input type="text" id="transfer-amount" class="amountt" oninput="addCommasTransfer(this.value)"  placeholder="(₦) Amount"  pattern="[0-9,]*" name="transfer-amount" class="amountt" inputmode="numeric">
            </label>
            <h3 class="recipient" id="recipient"></h3>
            
            <label for="pin" class="transfer-inputs">
                <input type="password" placeholder="Pin" maxlength="4" name="pin" class="transfer-pin" id="pin" inputmode="numeric" pattern="[0-9]*" oninput="handleTransferPin()">
                <i id="togglePin" class="fa fa-eye pin-icon"></i>
            </label>
            <br><br>
            <label for="remark" class="transfer-inputs">
                <input type="textarea" placeholder="Remark(optional)" name="remark" maxlength="60">
            </label>
            <br><br>
            <input type="hidden" name="user-balance" value="<?= htmlspecialchars($user['account_balance'])?>">
            <input type="submit" id="send" value="Send" class="send-transfer">
            <input type="button" value="Cancel" class="cancel" id="cancel" onclick="cancelTransfer()"></input>
            <p style="margin-left:20px"><a href="forgot-pin.php" style="text-decoration: none;">Forgot Pin?</a></p>
    </form>

    <form action="airtime.php" id="airtime_form" class="airtime_form" method="post">
            <div class="airtime-provider">
                <div class="select-selected">Airtel</div>
                <div class="select-provider select-hide">
                    <div class="airtime-provider-select" data-value="MTN">MTN</div>
                    <div class="airtime-provider-select" data-value="Glo">Glo</div>
                    <div class="airtime-provider-select" data-value="Airtel">Airtel</div>
                    <div class="airtime-provider-select" data-value="9mobile">9mobile</div>
                </div>
            </div>
            <input type="hidden" name="airtime-provider" id="airtime-provider-val" value="Airtel">
            <br><br>
            <div class="airtime-price">
                <input type="button" value="&#x20A6;50" id="fifty-artime" class="airtime-button">
                <input type="button" value="&#x20A6;100" id="one-hundred-airtime" class="airtime-button">
                <input type="button" value="&#x20A6;200" id="two-hundred-airtime" class="airtime-button">
                <input type="button" value="&#x20A6;500" id="five-hundred-artime" class="airtime-button">
                <input type="button" value="&#x20A6;1,000" id="one-thousand-airtime" class="airtime-button">
                <input type="button" value="&#x20A6;2,000" id="two-thousand-airtime" class="airtime-button">
            </div>
            <br>
            <input type="text" id="airtime-amount" oninput="addCommasAirtime(this.value)" placeholder="(₦) Amount"  name="airtime-amount" inputmode="numeric" pattern="[&#x20A6;0-9,]*">
            <br><br>
            <input type="text" id="airtime-phone-number" name="airtime-phone-number" inputmode="numeric" value="<?= $user['phone_number']?>" placeholder="Phone number" maxlength="11" pattern="[0-9]*">
            <br><br>
            <label for="airtime-pin">
                <input type="password" maxlength="4" placeholder="Pin" id="airtime-pin" class="airtime-pin" pattern="[0-9]*" oninput="handleAirtimePin()" inputmode="numeric">
                <i id="toggleAirtimePin" class="fa fa-eye pin-icon"></i>
            </label>
            <br><br>
            <input type="hidden" name="user-balance-airtime" value="<?= htmlspecialchars($user['account_balance'])?>">
            <input type="submit" id="buy-airtime" value="Buy"> <input type="button" value="Cancel" onclick="cancelAirtime()">
            <p><a href="forgot-pin.php" style="text-decoration: none; ">Forgot Pin?</a></p>
    </form>
    
    
    <form action="data.php" method="post" id="data_form" class="data_form" style="width: 72%;">
    <div class="data-provider">
                <div class="select-selected-data">Airtel</div>
                <div class="select-data-provider select-data-hide">
                    <div class="data-provider-select" data-value="MTN">MTN</div>
                    <div class="data-provider-select" data-value="Glo">Glo</div>
                    <div class="data-provider-select" data-value="Airtel">Airtel</div>
                    <div class="data-provider-select" data-value="9mobile">9mobile</div>
                </div>
            </div>
            <input type="hidden" name="data-provider" id="data-provider-val" value="Airtel">
        <div class="data-bundle-container">
            <div class="btn" data-value = "100">
                <span class="data-price" data-value="100MB 1day &#x20A6;100">100MB
                <br>
                1day
                <br>
                &#x20A6;100
            </span>
        </div>
        <div class="btn" data-value = "200">
            <span class="data-price" data-value="200Mb 1day &#x20A6;200">200MB
                <br>
                1day
                <br>
                &#x20A6;200
            </span>
        </div>
        <div class="btn" data-value = "350">
        <span class="data-price" data-value="1GB 1day &#x20A6;350">1GB
            <br>
            1day
            <br>
            &#x20A6;350
        </span>
    </div>  
    <div class="btn" data-value = "600">
        <span class="data-price" data-value="2.5GB 1day &#x20A6;600">2.5GB
            <br>
            2days
            <br>
            &#x20A6;600
        </span>
    </div>
        <div class="btn" data-value = "800">
            <span class="data-price" data-value="3GB 2days &#x20A6;800">3GB
                <br>
            2days
            <br>
            &#x20A6;800
        </span>
    </div>
</div>
<span id="data-plan"></span>
    <br>
    <input type="text" id="data-phone-number" name="data-phone-number" inputmode="numeric" value="<?= $user['phone_number']?>" placeholder="Phone number" maxlength="11" pattern="[0-9]*">
    <input type="hidden" name="data-plan-amount" id="data-plan-amount" value="">
    <input type="hidden" name="data-bundle" id="data-bundle" value="">
    <br><br>
    <input type="password" maxlength="4" id="data-pin" class="data-pin" placeholder="Pin" oninput="handleDataPin()" inputmode="numeric" pattern="[0-9]*">
    <i id="toggleDataPin" class="fa fa-eye pin-icon"></i>
    <br><br>
    <input type="hidden" name="user-balance-data" value="<?= htmlspecialchars($user['account_balance'])?>">
    <input type="submit" value="Buy" id="buy-data"> <input type="button" value="Cancel" onclick="cancelData()">
    <p><a href="forgot-pin.php" style="text-decoration: none;">Forgot Pin?</a></p>
    
</div>

</form>

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
    <script src="check-session.js"></script>
    <script src="eye-toggle.js"></script>
    <script src="form-operationss.js"></script>
    <script src="select-airtime.js"></script>
    <script src="select-data.js"></script>
    <script>
    
        //to show the recipient of an account number
        function showUser(str){
           
            if (str == "" || str == <?= $user['account_number'] ?>){
                document.getElementById('recipient').innerHTML = "";
                return;
            } else {
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function (){
                    if (this.readyState == 4 && this.status == 200){
                        document.getElementById('recipient').textContent = this.responseText;
                    }
                };
                xmlhttp.open("GET", "fetch-user.php?q="+str, true);
                xmlhttp.send();
            }
        }

        //to validate account number
        function validateAccountNo(){
            let foundRecipient = false;

            let recipient  = document.getElementById('recipient');

            if (recipient.innerHTML !== ""){
                foundRecipient = true;
            }
            return foundRecipient;
        }
        
        //to validate transfer amount
        function validateTransferAmount(){
            let amountIsValid = true;

            var amount = document.getElementById('transfer-amount');
            amount = parseFloat(amount.value.replace(/,/g, ""));
           
            if (amount > <?= $user['account_balance'] ?> || amount == 0 ){
                amountIsValid = false;
            }

            if (isNaN(amount)){
                amountIsValid = false;
            }
            return amountIsValid;
        }

    //runs an ajax to compare transfer pin with the hashed pin in the database
    function compareTransferPin(pin) {
            return new Promise((resolve, reject) => {
        const xhttp = new XMLHttpRequest();
        xhttp.onload = function() {
            if (this.status == 200 && this.readyState == 4) {
                var response = this.responseText;
                if (response == 'valid') {
                    resolve(true); 
                } else {
                    resolve(false); 
                }
            }
        };
        xhttp.onerror = function() {
            reject(false);
        };
        xhttp.open("POST", "verifypin.php");
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send(`pin=${pin}`);
    });

}

//to validate the pin
async function validatePin() {
    const pin = document.getElementById('pin').value;
    try {
        const isValid = await compareTransferPin(pin);
        return isValid;
    } catch (error) {
        return false;
    }
}

async function handleTransferPin() {
    const result = await validatePin();
    return result;
}


const accountNoInput = document.getElementById('account_no');
const amountInput = document.getElementById('transfer-amount');
const pinInput = document.getElementById('pin');
const send = document.getElementById('send');
send.disabled = true;

accountNoInput.addEventListener('input', updateSendButtonState);
amountInput.addEventListener('input', updateSendButtonState);
pinInput.addEventListener('input', updateSendButtonState);

async function updateSendButtonState() {
    const isPinValid = await handleTransferPin();
    if (validateAccountNo() && validateTransferAmount() && isPinValid) {
        send.disabled = false;
        send.style.backgroundColor = "rgb(26, 149, 31)";
        send.style.cursor = "pointer";
        send.style.color = "white";
    } else {
        send.disabled = true;
        send.style.backgroundColor = "hsl(0, 0.00%, 99.60%)";
        send.style.color = "rgb(145, 167, 151)";
        send.style.cursor = "not-allowed";
    }
}



//airtime logic

 async function validateAirtimeAmount(){
    let amountIsValid = true;

    var amount = document.getElementById('airtime-amount');
    amount = parseFloat(amount.value.replace(/,/g, "").replace("₦", ""));
    
    if (amount > <?= $user['account_balance'] ?> || amount == 0 ){
        amountIsValid = false;
    }

    if (isNaN(amount)){
        amountIsValid = false;
    }
    return amountIsValid;
 }

 

 function validateAirtimeProvider(){
    let netWorkIsValid = true;

    var network = document.getElementById('airtime-provider-val').value;

    if (network === ""){
        netWorkIsValid = false;
    }

    return netWorkIsValid;
 }
 
 function validateAirtimePhoneNumber(){
    let phoneIsValid = true;
    var phoneNo = document.getElementById('airtime-phone-number');

    if (phoneNo.value == "" || phoneNo.value.length < 11){
        phoneIsValid = false;
    }
    return phoneIsValid;
 }

 function compareAirtimePin(pin) {
    return new Promise((resolve, reject) => {
    const xhttp = new XMLHttpRequest();
    xhttp.onload = function() {
        if (this.status == 200 && this.readyState == 4) {
            var response = this.responseText;
        if (response == 'valid') {
            resolve(true); 
        } else {
            resolve(false); 
        }
    }
};
 xhttp.onerror = function() {
    reject(false);
 };
 xhttp.open("POST", "verifypin.php");
 xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
 xhttp.send(`pin=${pin}`);
});

}

 async function validateAirtimePin() {
    const pin = document.getElementById('airtime-pin').value;
    try {
        const isValid = await compareAirtimePin(pin);
        return isValid;
    } catch (error) {
        return false;
    }
}

 async function handleAirtimePin() {
    const result = await validateAirtimePin();
    return result;
}

const airtimeAmountInput = document.getElementById('airtime-amount');
const phoneNoAirtimeInput = document.getElementById('airtime-phone-number');
const airtimePinInput = document.getElementById('airtime-pin');

const buyAirtime = document.getElementById('buy-airtime');
buyAirtime.disabled = true;

airtimeAmountInput.addEventListener('input', updateBuyAirtimeButtonState);
phoneNoAirtimeInput.addEventListener('input', updateBuyAirtimeButtonState);
airtimePinInput.addEventListener('input', updateBuyAirtimeButtonState);

 async function updateBuyAirtimeButtonState(){
    const isPinValid = await handleAirtimePin();
    const isAmountValid = await  validateAirtimeAmount();

    if (isAmountValid && validateAirtimePhoneNumber() && validateAirtimeProvider() && isPinValid) {
        buyAirtime.disabled = false;
        buyAirtime.style.backgroundColor = "rgb(26, 149, 31)";
        buyAirtime.style.cursor = "pointer";
        buyAirtime.style.color = "white";

    } else {
        buyAirtime.disabled = true;
        buyAirtime.style.backgroundColor = "hsl(0, 0.00%, 99.60%)";
        buyAirtime.style.color = "rgb(145, 167, 151)";
        buyAirtime.style.cursor = "not-allowed";
    }
}


//for data

function validateDataBundle(){
    let bundleIsValid = true;

    var dataBundle = document.getElementById('data-bundle').value;
    var dataAmount = document.getElementById('data-plan-amount').value;

    if (dataBundle == "" || dataAmount > <?= $user['account_balance']?>){
        bundleIsValid = false;
    }

    // Add event listener to check bundle changes
    const buttons = document.querySelectorAll('.data-price');
    buttons.forEach(button => {
        button.addEventListener('click', () => {
            validateDataBundle();
            updateBuyDataButtonState();
        });
    });

    return bundleIsValid;
}

function validateDataPhoneNumber(){
    let phoneIsValid = true;
    var phoneNo = document.getElementById('data-phone-number');

    if (phoneNo.value == "" || phoneNo.value.length < 11){
        phoneIsValid = false;
    }
    return phoneIsValid;
 }

 function compareDataPin(pin) {
    return new Promise((resolve, reject) => {
    const xhttp = new XMLHttpRequest();
    xhttp.onload = function() {
        if (this.status == 200 && this.readyState == 4) {
            var response = this.responseText;
        if (response == 'valid') {
            resolve(true); 
        } else {
            resolve(false); 
        }
    }
};
 xhttp.onerror = function() {
    reject(false);
};
 xhttp.open("POST", "verifypin.php");
 xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
 xhttp.send(`pin=${pin}`);
});

}

 async function validateDataPin() {
    const pin = document.getElementById('data-pin').value;
    try {
        const isValid = await compareDataPin(pin);
        return isValid;
    } catch (error) {
        return false;
    }
}

 async function handleDataPin() {
    const result = await validateDataPin();
    return result;
}


const phoneDataInput = document.getElementById('data-phone-number');
const dataPinInput = document.getElementById('data-pin');
const dataBundle = document.getElementById('data-bundle');
const buyData = document.getElementById('buy-data');
buyData.disabled = true;

phoneDataInput.addEventListener('input', updateBuyDataButtonState);
dataBundle.addEventListener('input', updateBuyDataButtonState);
dataPinInput.addEventListener('input', updateBuyDataButtonState);

 async function updateBuyDataButtonState(){
    const isPinValid = await handleDataPin();
    if (validateDataPhoneNumber() && validateDataBundle() && isPinValid) {
        buyData.disabled = false;
        buyData.style.backgroundColor = "rgb(26, 149, 31)";
        buyData.style.cursor = "pointer";
        buyData.style.color = "white";
    } else {
        buyData.disabled = true;
        buyData.style.backgroundColor = "hsl(0, 0.00%, 99.60%)";
        buyData.style.color = "rgb(145, 167, 151)";
        buyData.style.cursor = "not-allowed";
    }
}

window.onload = function() {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('message')) {
        const message = urlParams.get('message');
        if (message === 'transaction_success') {
            document.getElementById('transaction-message').innerText = message;
        }
    }
    const newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
    window.history.replaceState({}, document.title, newUrl);
}
    // User activity tracker to keep session alive only on real activity
    let activityTimeout;
    function sendActivityUpdate() {
        const xhr = new XMLHttpRequest();
        xhr.open("GET", "check-session.php?update=1&t=" + new Date().getTime(), true);
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
<?php else: ?>
    <?= header("Location: landing-page.html"); exit; ?>
<?php endif; ?>
</body>
</html>


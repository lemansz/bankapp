 function hideDepositForm()
 {
    document.getElementById('deposit-form').style.display = "none";
    document.getElementById('deposit-account-no').value = "";
    document.getElementById('deposit-amount').value = "";
     
 }


 function hideWithdrawForm()
 {
    document.getElementById('withdraw-form').style.display = "none";
    document.getElementById('withdraw-account-no').value = "";
    document.getElementById('withdraw-amount').value = "";
    document.getElementById('withdraw-pin').value = "";
 }

document.getElementById('deposit').addEventListener('click', hideWithdrawForm)

document.getElementById('withdraw').addEventListener('click', hideDepositForm)

document.getElementById('cancel-deposit').addEventListener('click', hideDepositForm)

document.getElementById('cancel-withdraw').addEventListener('click', hideWithdrawForm)




function addCommasDeposit(value){
    var numericValue = parseFloat(value.replace(/,/g, ''));
     if (numericValue >= 0){
     document.getElementById("deposit-amount").value = numericValue.toLocaleString();
     }
 }
      
  function showUserDeposit(str){
   if (str == ""){
     document.getElementById('deposit-recipient').innerHTML = ""; return;
 } else {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function (){
        if (this.readyState == 4 && this.status == 200){
            document.getElementById('deposit-recipient').innerHTML = this.responseText;
             
        }
     };
    xhr.open("GET", "fetch-user-cashier.php?q="+str, true);
    xhr.send();
 }
 }
 
 
 
 function validateDepositForm(){
    let isValid = true;
    const depositAccountNo = document.getElementById('deposit-account-no').value;
    const depositAmount = document.getElementById('deposit-amount').value;

    var depositAccountNoError = document.getElementById('deposit-account-no-error');
    var depositAmountError = document.getElementById('deposit-amout-error');
    

    depositAccountNoError.textContent = "";
    depositAmountError.textContent = "";
    

    const regexAccountNumber = /^[0-9]{10}$/;
    const regexAmount = /[0-9],/;
    

    if (depositAccountNo.trim() === ""){
        isValid = false;
        depositAccountNoError.textContent = "Account number required."
    } else if (regexAccountNumber.test(depositAccountNo) === false) {
        isValid = false;
        depositAccountNoError.textContent = "Invalid account number."
    }
    
    if (depositAmount.trim() === ""){
        isValid = false;
        depositAmountError.textContent = "Amount required."
        
    } else if (depositAmount.replace(/,/g, "") < 1000){
        isValid = false;
        depositAmountError.textContent = "₦1000 is the minimum deposit.";
    } else if (regexAmount.test(depositAmount) === false){
        isValid = false;
        depositAmountError.textContent = "Invalid amount.";
    }
    return isValid;
}


const depositForm = document.getElementById('deposit-form');

depositForm.addEventListener('submit', (e)=>{
    if (validateDepositForm() === false) {
        e.preventDefault();
    }
})


const depositButton = document.getElementById('deposit');
depositButton.addEventListener('click', ()=>{
    document.getElementById('deposit-form').style.display = "block";
})

window.onload = function() {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('error')) {
        const error = urlParams.get('error');

        if (error === 'user_not_found') {
            depositForm.style.display = "block";
            document.getElementById('user-not-found-error').textContent = "Customer not found.";   
        }

        if (error === 'user_not_found_w') {
            withdrawForm.style.display = "block";
            document.getElementById('withdraw-pin-error').textContent = "Customer not found.";   
        }

        if (error === 'incorrect_pin_w') {
            withdrawForm.style.display = "block";
            document.getElementById('withdraw-pin-error').textContent = "Incorrect pin.";   
        }

        if (error === 'insufficient_funds') {
            withdrawForm.style.display = "block";
            document.getElementById('withdraw-pin-error').textContent = "Insufficient fund.";
        }
    }
    const newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
    window.history.replaceState({}, document.title, newUrl);
}



function addCommasWithdraw(value){
var numericValue = parseFloat(value.replace(/,/g, ''));
    if (numericValue >= 0){
    document.getElementById("withdraw-amount").value = numericValue.toLocaleString();
    }
}
    
function showUserWithdraw(str){
if (str == ""){
    document.getElementById('withdraw-recipient').innerHTML = ""; return;
} else {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function (){
        if (this.readyState == 4 && this.status == 200){
            document.getElementById('withdraw-recipient').innerHTML = this.responseText;
            
        }
    };
    xhr.open("GET", "fetch-user-cashier.php?q="+str, true);
    xhr.send();
}
}



function validateWithdrawForm(){
    let isValid = true;
    const withdrawAccountNo = document.getElementById('withdraw-account-no').value;
    const withdrawAmount = document.getElementById('withdraw-amount').value;
    const withdrawPin = document.getElementById('withdraw-pin').value;

    var withdrawAccountNoError = document.getElementById('withdraw-account-no-error');
    var withdrawAmountError = document.getElementById('withdraw-amout-error');
    var withdrawPinError = document.getElementById('withdraw-pin-error');

    withdrawAccountNoError.textContent = "";
    withdrawAmountError.textContent = "";
    withdrawPinError.textContent = "";

    const regexAccountNumber = /^[0-9]{10}$/;
    const regexAmount = /[0-9],/;
    const regPin = /^[0-9]{4}$/;

    if (withdrawAccountNo.trim() === ""){
    isValid = false;
    withdrawAccountNoError.textContent = "Account number required."
    } else if (regexAccountNumber.test(withdrawAccountNo) === false) {
    isValid = false;
    withdrawAccountNoError.textContent = "Invalid account number."
    }
    
    if (withdrawAmount.trim() === ""){
    isValid = false;
    withdrawAmountError.textContent = "Amount required."
        
    } else if (withdrawAmount.replace(/,/g, "") < 1000){
    isValid = false;
    withdrawAmountError.textContent = "₦1000 is the minimum withdrawer.";
    } else if (regexAmount.test(withdrawAmount) === false){
    isValid = false;
    withdrawAmountError.textContent = "Invalid amount.";
    }

    if (withdrawPin.trim() === ""){
    isValid = false;
    withdrawPinError.textContent = "Pin is required."
    } else if (regPin.test(withdrawPin) === false){
    isValid = false;
    withdrawPinError.textContent = "Invalid pin."
    }
    return isValid;
 }
 
 
 const withdrawForm = document.getElementById('withdraw-form');
 
 withdrawForm.addEventListener('submit', (e)=>{
    if (validateWithdrawForm() === false) {
        e.preventDefault();
    }
 })
 
 
 const withdrawButton = document.getElementById('withdraw');
 withdrawButton.addEventListener('click', ()=>{
    document.getElementById('withdraw-form').style.display = "block";
 })
 

 
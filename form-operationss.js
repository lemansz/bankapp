// This code allows users to toggle between different services

const transfer = document.getElementById('transfer');
const airtime = document.getElementById('airtime');
const data = document.getElementById('data');

const transferForm = document.getElementById("transfer_form");
const airtimeForm = document.getElementById("airtime_form");
const dataForm = document.getElementById("data_form");

function cancelTransfer (){
    document.getElementById('transfer_form').style.display = 'none';
    document.getElementById('account_no').value = '';
    document.getElementById('transfer-amount').value = '';
    document.getElementById('recipient').innerText = '';
    document.getElementById('pin').value = '';
    updateSendButtonState()
}

function cancelAirtime(){
    document.getElementById('airtime_form').style.display = 'none';
    document.getElementById('airtime-pin').value = '';
    document.getElementById('airtime-amount').value = '';
    updateBuyAirtimeButtonState();
}

function cancelData(){
    document.getElementById('data_form').style.display = 'none';
    document.getElementById('data-plan').innerText = '';
    document.getElementById('data-pin').value = '';
    updateBuyDataButtonState();
}

airtime.addEventListener('click', (e)=>{
    document.getElementById('airtime_form').style.display = 'block';
    cancelTransfer();
    cancelData();
})

transfer.addEventListener('click', (e)=>{
    document.getElementById('transfer_form').style.display = 'block';
    cancelAirtime();
    cancelData();
})

data.addEventListener('click', (e)=>{
    document.getElementById('data_form').style.display = 'block';
    cancelAirtime();
    cancelTransfer();
})

 //adding commas in amounts
 function addCommasTransfer(value){
    var numericValue = parseFloat(value.replace(/,/g, ''));
    if (numericValue >= 0){
        document.getElementById("transfer-amount").value = numericValue.toLocaleString();
    }
}
function addCommasAirtime(value){
    var numericValue = parseFloat(value.replace(/,/g, ''));
    if (numericValue >= 0){
        document.getElementById("airtime-amount").value = numericValue.toLocaleString();
    }
}

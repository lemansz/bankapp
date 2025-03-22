/**
 * This file contains the JavaScript code for the select airtime page
 * It allows the user to select airtime provider and airtime amount
 */

const airtimeProvider = document.querySelectorAll(".airtime-provider-select");
airtimeProvider.forEach((provider)=>{
    provider.addEventListener('click', ()=>{
        document.getElementById('airtime-provider-val').value = provider.dataset.value;
    })
})



document.querySelector('.select-selected').addEventListener('click', function(event) {
    event.stopPropagation();;
    this.nextElementSibling.classList.toggle('select-hide');
    this.nextElementSibling.classList.toggle('select-show');
});

document.querySelectorAll('.select-provider div').forEach(function(item) {
    item.addEventListener('click', function(event) {
        event.stopPropagation();
        document.querySelector('.select-selected').innerText = this.innerText;
        document.querySelector('.select-provider').classList.add('select-hide');
        document.querySelector('.select-provider').classList.remove('select-show');
    });
});

document.addEventListener('click', function(event) {
    if (!event.target.matches('.select-selected')) {
        document.querySelector('.select-provider').classList.add('select-hide');
        document.querySelector('.select-provider').classList.remove('select-show');
    }
});


let fifty = document.getElementById('fifty-artime');
let oneHundred = document.getElementById('one-hundred-airtime');
let twoHundred = document.getElementById('two-hundred-airtime');
let fiveHundred = document.getElementById('five-hundred-artime');
let oneThousand = document.getElementById('one-thousand-airtime');
let twoThousand = document.getElementById('two-thousand-airtime');

fifty.addEventListener('click', (e)=>{
    document.getElementById('airtime-amount').value = fifty.value;
    
})
oneHundred.addEventListener('click', (e)=>{
    document.getElementById('airtime-amount').value = oneHundred.value;
})
twoHundred.addEventListener('click', (e) => {
    document.getElementById('airtime-amount').value = twoHundred.value;
})
fiveHundred.addEventListener('click', (e) => {
    document.getElementById('airtime-amount').value = fiveHundred.value;
})
oneThousand.addEventListener('click', (e) => {
    document.getElementById('airtime-amount').value = oneThousand.value;
})
twoThousand.addEventListener('click', (e)=>{
    document.getElementById('airtime-amount').value = twoThousand.value;
})

//transfer pin
const togglePin = document.querySelector('#togglePin');
const pin = document.querySelector('#pin');

togglePin.addEventListener('click', function(e) {
const type = pin.getAttribute('type') === 'password' ? 'text' : 'password';
pin.setAttribute('type', type);
this.classList.toggle('fa-eye-slash');
})

//airtime pin
const toggleAirtimePin = document.querySelector('#toggleAirtimePin');
const airtimePin = document.querySelector('#airtime-pin');

toggleAirtimePin.addEventListener('click', function(e) {
const type = airtimePin.getAttribute('type') === 'password' ? 'text' : 'password';
airtimePin.setAttribute('type', type);
this.classList.toggle('fa-eye-slash');
})

//data pin

const toggleDataPin = document.querySelector('#toggleDataPin');
const dataPin = document.querySelector('#data-pin');

toggleDataPin.addEventListener('click', function(e) {
const type = dataPin.getAttribute('type') === 'password' ? 'text' : 'password';
dataPin.setAttribute('type', type);
this.classList.toggle('fa-eye-slash');
})
 

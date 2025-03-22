
/**
 * This file contains the javascript code for the select data page
 * It allows the user to select data provider and data bundle
 * 
 */

const dataProvider = document.querySelectorAll(".data-provider-select");
dataProvider.forEach((provider)=>{
    provider.addEventListener('click', ()=>{
        document.getElementById('data-provider-val').value = provider.dataset.value;
        console.log(document.getElementById('data-provider-val').value)
    })
})

document.querySelector('.select-selected-data').addEventListener('click', function(event) {
    event.stopPropagation();;
    this.nextElementSibling.classList.toggle('select-data-hide');
    this.nextElementSibling.classList.toggle('select-show');
});

document.querySelectorAll('.select-data-provider div').forEach(function(item) {
    item.addEventListener('click', function(event) {
        event.stopPropagation();
        document.querySelector('.select-selected-data').innerText = this.innerText;
        document.querySelector('.select-data-provider').classList.add('select-data-hide');
        document.querySelector('.select-data-provider').classList.remove('select-show');
    });
});

document.addEventListener('click', function(event) {
    if (!event.target.matches('.select-selected-data')) {
        document.querySelector('.select-data-provider').classList.add('select-data-hide');
        document.querySelector('.select-data-provider').classList.remove('select-show');
    }
});




const dataPrices = document.querySelectorAll('.btn');

 dataPrices.forEach((prices)=> {
    prices.addEventListener('click', ()=>{
        document.getElementById('data-plan').innerHTML = "<br>" + prices.innerText + "<br>";
        document.getElementById('data-plan-amount').value = prices.dataset.value;
        
    })
})


 const dataDescriptions = document.querySelectorAll('.data-price');

 dataDescriptions.forEach((descriptions)=> {
    descriptions.addEventListener('click', ()=>{
        document.getElementById('data-bundle').value = descriptions.dataset.value;
        
    })
})
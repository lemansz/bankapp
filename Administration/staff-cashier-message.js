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
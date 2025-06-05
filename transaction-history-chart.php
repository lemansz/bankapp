<?php
    session_start();
    require __DIR__ . "/transactions_history.php";

    $history = new Transaction_history;

    if (!isset($_SESSION['user_id'])) {
        header("Location: index.php");
        exit();
    }

    $history->sender_id = $_SESSION['user_id'];
    $history->beneficary_id = $_SESSION['user_id'];

    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction History</title>
    <link rel="shortcut icon" href="./Assets/bank-logo-index.svg" type="image/x-icon">
    <style>

    @import url('https://fonts.googleapis.com/css2?family=Cute+Font&family=Parkinsans:wght@300..800&family=Red+Hat+Text:ital,wght@0,300..700;1,300..700&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Stylish&family=Varela&display=swap');
    * {
        font-family: "Roboto", sans-serif;
        font-optical-sizing: auto;
        font-style: normal;
    }

   
    .transaction-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 20px;
    }

    .transaction-card {
        flex: 1 1 100%; /* Default: Full width for mobile */
        max-width: 100%; /* Default: Full width for mobile */
    }

    .no-transaction{
        text-align: center;
    }


    @media (min-width: 1024px) {
        .transaction-card {
            flex: 1 1 35%;
            max-width: 35%; 
            margin-left: auto;
            margin-right: auto;
        }
        .transaction-container {
            justify-content: center;
        }
        body{
            padding-right: 30rem;
            padding-left: 30rem;
        }
    }
    </style>
</head>
<body>

   <?php $history->transaction_history_cards()?>

   <?php
   
   ?>
   <script>

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
   <script ></script>
</body>
</html>
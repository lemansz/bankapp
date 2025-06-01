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


    @media (min-width: 768px) {
        .transaction-card {
            flex: 1 1 calc(50% - 20px); /* Two cards per row for desktop */
            max-width: calc(50% - 20px); /* Two cards per row for desktop */
        }
    }
    </style>
</head>
<body>

   <?php $history->transaction_history_cards()?>

   <?php
   
   ?>    
</body>
</html>
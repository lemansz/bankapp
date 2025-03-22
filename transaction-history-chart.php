<?php
    session_start();
    require __DIR__ . "/transactions_history.php";

    $history = new Transaction_history;

    $history->sender_id = $_SESSION['user_id'];
    $history->beneficary_id = $_SESSION['user_id'];

    $history->fetch_transaction_history()
?>
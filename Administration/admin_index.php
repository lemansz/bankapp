<?php

require "bank_stats.php";

$sum_of_transaction = new TransactionsStats;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h3>Customers: <?= UsersStats::totalUsers() ?></h3>
    <h3>Total Transactions: <?= TransactionsStats::totalTransactions() ?></h3>
    <h3>Net transactions: <?= $sum_of_transaction->sum_transactions_amount()?></h3>
    <h3>Airtime Purchases: <?= AirtimeStats::airtime_transactions()?></h3>
    <h3>Sum of Airtime purchased: <?= AirtimeStats::sum_airtime_amount() ?> </h3>

    <h3><button>Add Staff</button></h3>
</body>
</html>
<?php

if (!defined('749gl8balFjd0pdu90129%12LBUX33')) {
    http_response_code(403);
    exit('Forbidden');
}

require dirname(__FILE__) . '/../db.php';

class UsersStats
{
    public static function totalUsers()
    {

    global $conn;

    $total_users_sql = "SELECT COUNT(*) as users FROM `bank_user_data`";
    $total_users_result = $conn->query($total_users_sql);

    if ($total_users_result->num_rows > 0){
    $row = $total_users_result->fetch_assoc();
    $total_users = $row['users'];
    }
    return $total_users;
    }
}

class TransactionsStats
{
    
    public static function totalTransactions()
    {
        global $conn;

        $total_transactions_sql = "SELECT COUNT(*) as transactions FROM `transactions`";
        $total_transactions_result = $conn->query($total_transactions_sql);

        if ($total_transactions_result->num_rows > 0){
            $row = $total_transactions_result->fetch_assoc();
            $total_transactions = $row['transactions'];
        }
        return $total_transactions;
    }

    public function sum_transactions_amount()
    {
        global $conn;
        $sum_of_transaction_sql = "SELECT SUM(`transaction_amount`) as sum_of_transaction FROM `transactions`";
        $sum_of_transaction_result = $conn->query($sum_of_transaction_sql);

        if ($sum_of_transaction_result->num_rows > 0){
            $row = $sum_of_transaction_result->fetch_assoc();
            $sum_of_transaction = $row['sum_of_transaction'];
        }

        return "₦" . number_format($sum_of_transaction);
    }
}


class DepositStats
{
    public static function deposit_transactions()
    {
        global $conn;

        $deposit_transactions_sql = "SELECT COUNT(`transaction_type`) as deposit_transactions FROM `transactions` WHERE `transaction_type` = 'Deposit'";
        $total_deposit_transaction_result = $conn->query($deposit_transactions_sql);

        if ($total_deposit_transaction_result->num_rows > 0){
            $row = $total_deposit_transaction_result->fetch_assoc();
            $deposit_transactions = $row['deposit_transactions'];
        }

        return $deposit_transactions;
    }


    public static function sum_deposit_amount()
    {
        global $conn;

        $sum_deposit_sql = "SELECT SUM(`transaction_amount`) as sum_of_deposit FROM `transactions` WHERE `transaction_type` = 'Deposit'";
        $sum_deposit_result = $conn->query($sum_deposit_sql);

        if ($sum_deposit_result->num_rows > 0){
            $row = $sum_deposit_result->fetch_assoc();
            $sum_of_deposit = $row['sum_of_deposit'];
        }
        
        return $sum_of_deposit;
    }
}

class WithdrawStats
{
    public static function withdraw_transactions()
    {
        global $conn;

        $withdraw_transactions_sql = "SELECT COUNT(`transaction_type`) as withdraw_transactions FROM `transactions` WHERE `transaction_type` = 'Withdraw'";
        $total_withdraw_transaction_result = $conn->query($withdraw_transactions_sql);

        if ($total_withdraw_transaction_result->num_rows > 0){
            $row = $total_withdraw_transaction_result->fetch_assoc();
            $withdraw_transactions = $row['withdraw_transactions'];
        }

        return $withdraw_transactions;
    }

    public static function sum_withdraw_amount()
    {
        global $conn;

        $sum_withdraw_sql = "SELECT SUM(`transaction_amount`) as sum_of_withdraw FROM `transactions` WHERE `transaction_type` = 'Withdraw'";
        $sum_withdraw_result = $conn->query($sum_withdraw_sql);

        if ($sum_withdraw_result->num_rows > 0){
            $row = $sum_withdraw_result->fetch_assoc();
            $sum_of_withdraw = $row['sum_of_withdraw'];
        }
        
        return $sum_of_withdraw;

    }
}

class TransferStats
{
    public static function transfer_transactions()
    {
        global $conn;

        $transfer_transactions_sql = "SELECT COUNT(`transaction_type`) as transfer_transactions FROM `transactions` WHERE `transaction_type` = 'Transfer'";
        $total_transfer_transaction_result = $conn->query($transfer_transactions_sql);

        if ($total_transfer_transaction_result->num_rows > 0){
            $row = $total_transfer_transaction_result->fetch_assoc();
            $transfer_transactions = $row['transfer_transactions'];
        }

        return $transfer_transactions;
    }

    public static function sum_transfer_amount()
    {
        global $conn;

        $sum_transfer_sql = "SELECT SUM(`transaction_amount`) as sum_of_transfer FROM `transactions` WHERE `transaction_type` = 'Transfer'";
        $sum_transfer_result = $conn->query($sum_transfer_sql);

        if ($sum_transfer_result->num_rows > 0){
            $row = $sum_transfer_result->fetch_assoc();
            $sum_of_transfer = $row['sum_of_transfer'];
        }
        
        return $sum_of_transfer;

    }
}

class AirtimeStats
{
    
    public static function airtime_transactions()
    {
        global $conn;

        $airtime_transactions_sql = "SELECT COUNT(`transaction_type`) as airtime_transactions FROM `transactions` WHERE `transaction_type` = 'Airtime'";

        $total_airtime_transaction_result = $conn->query($airtime_transactions_sql);

        if ($total_airtime_transaction_result->num_rows > 0){
            $row = $total_airtime_transaction_result->fetch_assoc();
            $airtime_transactions = $row['airtime_transactions'];
        }

        return $airtime_transactions;
    }

    public static function sum_airtime_amount()
    {
        global $conn;

        $sum_airtime_sql = "SELECT SUM(`transaction_amount`) as sum_of_airtime FROM `transactions` WHERE `transaction_type` = 'Airtime'";
        $sum_airtime_result = $conn->query($sum_airtime_sql);

        if ($sum_airtime_result->num_rows > 0){
            $row = $sum_airtime_result->fetch_assoc();
            $sum_of_airtime = $row['sum_of_airtime'];
        }
        
        return $sum_of_airtime;
    }

    public static function airtime_provider_stats()
    {
        global $conn;
       
        $mtn_sql = "SELECT COUNT(*) AS mtn_purchase_count, SUM(transaction_amount) AS mtn_sum FROM transactions WHERE transaction_type = 'Airtime' AND network_provider = 'MTN'";
        $mtn_sql_result = $conn->query($mtn_sql);

        if ($mtn_sql_result->num_rows > 0){
            $row = $mtn_sql_result->fetch_assoc();
            $mtn_total_purchase = $row['mtn_purchase_count'];
            $mtn_sum = $row['mtn_sum'];
        }

        $glo_sql = "SELECT COUNT(*) AS glo_purchase_count, SUM(transaction_amount) AS glo_sum FROM transactions WHERE transaction_type = 'Airtime' AND network_provider = 'Glo'";
        $glo_sql_result = $conn->query($glo_sql);

        if ($glo_sql_result->num_rows > 0){
            $row = $glo_sql_result->fetch_assoc();
            $glo_total_purchase = $row['glo_purchase_count'];
            $glo_sum = $row['glo_sum'];
        }

        $airtel_sql = "SELECT COUNT(*) AS airtel_purchase_count, SUM(transaction_amount) AS airtel_sum FROM transactions WHERE transaction_type = 'Airtime' AND network_provider = 'Airtel'";
        $airtel_sql_result = $conn->query($airtel_sql);

        if ($airtel_sql_result->num_rows > 0){
            $row = $airtel_sql_result->fetch_assoc();
            $airtel_total_purchase = $row['airtel_purchase_count'];
            $airtel_sum = $row['airtel_sum'];
        }

        $mobile9_sql = "SELECT COUNT(*) AS 9mobile_purchase_count, SUM(transaction_amount) AS 9mobile_sum FROM transactions WHERE transaction_type = 'Airtime' AND network_provider = '9mobile'";
        $mobile9_sql_result = $conn->query($mobile9_sql);

        if ($mobile9_sql_result->num_rows > 0){
            $row = $mobile9_sql_result->fetch_assoc();
            $mobile9_total_purchase = $row['9mobile_purchase_count'];
            $mobile9_sum = $row['9mobile_sum'];
        }

        return [
            "mtn_sum" => $mtn_sum,
            "mtn_total" => $mtn_total_purchase,

            "glo_sum" => $glo_sum,
            "glo_total" => $glo_total_purchase,

            "airtel_sum" => $airtel_sum,
            "airtel_total" => $airtel_total_purchase,

            "9mobile_sum" => $mobile9_sum,
            "9mobile_total" => $mobile9_total_purchase

        ];
    }


}



 class DataStats {
    public static function data_transactions()
    {
        global $conn;

        $data_transactions_sql = "SELECT COUNT(`transaction_type`) as data_transactions FROM `transactions` WHERE `transaction_type` = 'Data'";

        $total_data_transaction_result = $conn->query($data_transactions_sql);

        if ($total_data_transaction_result->num_rows > 0){
            $row = $total_data_transaction_result->fetch_assoc();
            $data_transactions = $row['data_transactions'];
        }

        return $data_transactions;
    }

    public static function sum_data_amount()
    {
        global $conn;

        $sum_data_sql = "SELECT SUM(`transaction_amount`) as sum_of_data FROM `transactions` WHERE `transaction_type` = 'Data'";
        $sum_data_result = $conn->query($sum_data_sql);

        if ($sum_data_result->num_rows > 0){
            $row = $sum_data_result->fetch_assoc();
            $sum_of_data = $row['sum_of_data'];
        }
        
        return $sum_of_data;

    }


    public static function data_provider_stats()
    {
        global $conn;
           
        $mtn_sql = "SELECT COUNT(*) AS mtn_purchase_count, SUM(transaction_amount) AS mtn_sum FROM transactions WHERE transaction_type = 'Data' AND network_provider = 'MTN'";
        $mtn_sql_result = $conn->query($mtn_sql);

        if ($mtn_sql_result->num_rows > 0){
            $row = $mtn_sql_result->fetch_assoc();
            $mtn_total_purchase = $row['mtn_purchase_count'];
            $mtn_sum = $row['mtn_sum'];
        }

        $glo_sql = "SELECT COUNT(*) AS glo_purchase_count, SUM(transaction_amount) AS glo_sum FROM transactions WHERE transaction_type = 'Data' AND network_provider = 'Glo'";
        $glo_sql_result = $conn->query($glo_sql);

        if ($glo_sql_result->num_rows > 0){
            $row = $glo_sql_result->fetch_assoc();
            $glo_total_purchase = $row['glo_purchase_count'];
            $glo_sum = $row['glo_sum'];
        }

        $airtel_sql = "SELECT COUNT(*) AS airtel_purchase_count, SUM(transaction_amount) AS airtel_sum FROM transactions WHERE transaction_type = 'Data' AND network_provider = 'Airtel'";
        $airtel_sql_result = $conn->query($airtel_sql);

        if ($airtel_sql_result->num_rows > 0){
            $row = $airtel_sql_result->fetch_assoc();
            $airtel_total_purchase = $row['airtel_purchase_count'];
            $airtel_sum = $row['airtel_sum'];
        }

        $mobile9_sql = "SELECT COUNT(*) AS 9mobile_purchase_count, SUM(transaction_amount) AS 9mobile_sum FROM transactions WHERE transaction_type = 'Data' AND network_provider = '9mobile'";
        $mobile9_sql_result = $conn->query($mobile9_sql);

        if ($mobile9_sql_result->num_rows > 0){
            $row = $mobile9_sql_result->fetch_assoc();
            $mobile9_total_purchase = $row['9mobile_purchase_count'];
            $mobile9_sum = $row['9mobile_sum'];
        }

        return [
            "mtn_sum" => $mtn_sum,
            "mtn_total" => $mtn_total_purchase,

            "glo_sum" => $glo_sum,
            "glo_total" => $glo_total_purchase,

            "airtel_sum" => $airtel_sum,
            "airtel_total" => $airtel_total_purchase,

            "9mobile_sum" => $mobile9_sum,
            "9mobile_total" => $mobile9_total_purchase

        ];
    }
 }
?>
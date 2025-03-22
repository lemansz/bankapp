<?php
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
    public $sum_of_transactions;

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

    public function addCommas($number)
    {
        return number_format((float) $number);
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

        return "₦" . $this->addCommas($sum_of_transaction);
    }
}

class AirtimeStats
{
    
    public $sum_of_airtime;
    public $airtime_provider;

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

        $total_mtn_sql =  "SELECT COUNT(`transaction_type`) as mtn_airtime_transactions FROM `transactions` WHERE `transaction_type` = 'Airtime' AND `network_provider` = 'MTN'";
        $total_mtn_sql_result = $conn->query($total_mtn_sql);

        if ($total_mtn_sql_result->num_rows > 0){
            $row = $total_mtn_sql_result->fetch_assoc();

        }

    }
}

?>
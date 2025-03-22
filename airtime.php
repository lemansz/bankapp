<?php
 
 session_start();

 require __DIR__ . "/transactions.php";

 $airtime_buyer_id = $_SESSION['user_id'];

 if ($_SERVER["REQUEST_METHOD"] === "POST"){
    $airtime_provider = clean_input($_POST['airtime-provider']);
    $airtime_amount = clean_input($_POST['airtime-amount']);
    $airtime_phone_no = clean_input($_POST['airtime-phone-number']);

    if (isset($airtime_provider)){
        $airtime_provider = filter_var($airtime_provider, FILTER_SANITIZE_SPECIAL_CHARS);
    }

    if (isset($airtime_amount)){
        $airtime_amount = filter_var($airtime_amount, FILTER_SANITIZE_SPECIAL_CHARS);
        $airtime_amount = str_replace(array("₦", ","), "", $airtime_amount);
    }

    if (isset($airtime_phone_no)){
        $airtime_phone_no = filter_var($airtime_phone_no, FILTER_SANITIZE_SPECIAL_CHARS);
    }

 }


  
 function clean_input($data){
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');

    return $data;
 }

 $airtime_sql = "SELECT * FROM bank_user_data WHERE id = ?";
 $airtime_stmt = $conn->prepare($airtime_sql);
 $airtime_stmt->bind_param("i", $airtime_buyer_id);
 $airtime_stmt->execute();
 $airtime_buyer_result = $airtime_stmt->get_result();
 $airtime_buyer = $airtime_buyer_result->fetch_assoc();

$airtime = new Transactions;
$airtime->sender_id = $airtime_buyer_id;
$airtime->transaction_amount = $airtime_amount;
$airtime->transaction_type = "Airtime";
$airtime->network_provider = $airtime_provider;
$airtime->beneficiary_phone_no = $airtime_phone_no;
$airtime->transaction_date = $day;
$airtime->record_airtime_transaction();

 $airtime_buyer_balance = floatval($airtime_buyer['account_balance']);

 $newBalance = $airtime_buyer_balance - $airtime_amount;
 
 
 
 $update_airtime_buyer_sql = "UPDATE bank_user_data SET account_balance = ? WHERE id = ?";
 $update_airtime_buyer_stmt = $conn->prepare($update_airtime_buyer_sql);
 $update_airtime_buyer_stmt->bind_param("si", $newBalance,$airtime_buyer_id);
 $update_airtime_buyer_stmt->execute();

 if ($update_airtime_buyer_stmt->affected_rows > 0){
    header("Location: index.php?message=airtime purchase successful");
    exit;
 }
 $update_airtime_buyer_stmt->close();
 
?>
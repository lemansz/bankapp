<?php

 session_start();
 
 require __DIR__ . "/transactions.php";

 $sender_id = $_SESSION['user_id'];
 
 if ($_SERVER["REQUEST_METHOD"] === "POST"){
    $recipient = clean_input($_POST['recipient']);
    $str_amount = clean_input($_POST['transfer-amount']);
    $remark = clean_input($_POST['remark']);

    if (isset($recipient) && filter_var($recipient, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => '/^\d{10}$/')))){
        $recipient = filter_var($recipient,FILTER_SANITIZE_NUMBER_INT);
    }

    if (isset($str_amount)){
        $str_amount = filter_var($str_amount, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $amount = intval(str_replace(',', '', $str_amount));
    }

    if (isset($remark))
    {
        $remark = filter_var($remark, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    }
    
 }

 function clean_input($data){
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');

    return $data;
 }
 
$recipient_sql = "SELECT * FROM bank_user_data WHERE account_number = ?";
$sender_sql = "SELECT * FROM bank_user_data WHERE id = ?";


$recipient_stmt = $conn->prepare($recipient_sql);
$recipient_stmt->bind_param("s", $recipient); 

$recipient_stmt->execute();
$recipient_result = $recipient_stmt->get_result();
$receiver = $recipient_result->fetch_assoc();


$recipient_stmt->close();

$sender_stmt = $conn->prepare($sender_sql);
$sender_stmt->bind_param("s", $sender_id); 

$sender_stmt->execute();
$sender_result = $sender_stmt->get_result();
$sender = $sender_result->fetch_assoc();


$sender_stmt->close();

$sender_balance_str = $sender['account_balance'];
$receiver_balance_str = $receiver['account_balance'];

$transfer = new Transactions;
$transfer->sender_id = $sender['id'];
$transfer->beneficiary_id = $receiver['id'];
$transfer->transaction_amount = $amount;
$transfer->beneficiary_name = $receiver['surname'] . " " . $receiver['first_name'];
$transfer->sender_name = $sender['surname'] . " " . $sender['first_name'];
$transfer->sender_account_no = $sender['account_number'];
$transfer->beneficiary_account_no = $receiver['account_number'];
$transfer->transaction_type = "Transfer";
$transfer->transaction_remark = $remark;
$transfer->transaction_date = $day;
$transfer->record_transfer();


$sender_balance = floatval($sender_balance_str);
$receiver_balance = floatval($receiver_balance_str);


$new_sender_balance = $sender_balance - $amount;
$new_receiver_balance = $receiver_balance + $amount;


$update_sender_sql = "UPDATE bank_user_data SET account_balance = ? WHERE id = ?";
$update_receiver_sql = "UPDATE bank_user_data SET account_balance = ? WHERE account_number = ?";


$update_sender_stmt = $conn->prepare($update_sender_sql);
$update_sender_stmt->bind_param("ss", $new_sender_balance, $sender_id); 


$update_receiver_stmt = $conn->prepare($update_receiver_sql);
$update_receiver_stmt->bind_param("ss", $new_receiver_balance, $recipient);

$update_sender_stmt->execute();
$update_receiver_stmt->execute();


if ($update_sender_stmt->affected_rows > 0 && $update_receiver_stmt->affected_rows > 0) {
    header("Location:index.php?message=Transfer of ₦$amount to {$receiver['surname']} {$receiver['first_name']} was successful!");
    exit;
}

$update_sender_stmt->close();
$update_receiver_stmt->close();

?>
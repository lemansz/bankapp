<?php

 session_start();

 if (!isset($_SESSION['user_id'])) {
   header("Location: index.php");
   exit();
 }
 
 require __DIR__ . "/transactions.php";

 $sender_id = $_SESSION['user_id'];

 if ($_SERVER["REQUEST_METHOD"] === "POST"){

    $recipient = clean_input($_POST['recipient']);
    $str_amount = clean_input($_POST['transfer-amount']);
    $user_balance = clean_input($_POST['user-balance']);
    $remark = clean_input($_POST['remark']);

    if (str_replace((","), "", $str_amount) > $user_balance){
        die ("Insufficient balance.");
    }

    if (empty($recipient) || !preg_match('/\d{10}$/', $recipient)){
        die ("Invalid account no.");
    } 

    if (strlen($remark) > 65){
        die ("Remark is too long.");
    }

    if ($recipient == $sender_id){
        die ("You cannot transfer to yourself.");
    }

    if (empty($str_amount)){
        die ("Amount is required.");
    } else if (str_replace((","), "", $str_amount) == 0 || str_replace((","), "", $str_amount) < 0 || !preg_match('/^[0-9]+$/', str_replace((","), "", $str_amount))) {
        die ("Invalid amount.");
    }
}

 function clean_input($data){
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
 }
 
 $amount = str_replace((","), "", $str_amount);

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

$sender_balance_str =  $sender['account_balance'];
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
    $transfer->record_transfer();
    session_regenerate_id(true);
    header("Location:index.php?message=Transfer of ₦$str_amount to {$receiver['surname']} {$receiver['first_name']} was successful!");
    exit;
}

$update_sender_stmt->close();
$update_receiver_stmt->close();

?>
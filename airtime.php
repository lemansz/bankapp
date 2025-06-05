<?php

 session_start();

 if (!isset($_SESSION['user_id'])) {
   header("Location: index.php");
   exit();
}

 require __DIR__ . "/transactions.php";

 $airtime_buyer_id = $_SESSION['user_id'];

 if ($_SERVER["REQUEST_METHOD"] === "POST"){
    $airtime_provider = clean_input($_POST['airtime-provider']);
    $airtime_amount = clean_input(str_replace(array("₦", ","), "", $_POST['airtime-amount']));
    $airtime_phone_no = clean_input($_POST['airtime-phone-number']);
    $user_balance = clean_input($_POST['user-balance-airtime']);

    if (empty($airtime_provider)){
      die("Airtime proivder required.");
   }

    if (empty($airtime_amount)){
      die ("Amount is required");
    } else if ($airtime_amount > $user_balance){
      die ("Insufficient funds.");
    }

    if (empty($airtime_phone_no)){
      die ("Phone numer required");
   } else if (!preg_match('/\d{11}$/', $airtime_phone_no)){
      die ("Invalid phone number.");
  }

 }


 function clean_input($data){
   return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
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
$airtime->sender_name = $airtime_buyer['surname'] . " " . $airtime_buyer['first_name'];
$airtime->sender_account_no = $airtime_buyer['account_number'];
$airtime->transaction_type = "Airtime";
$airtime->network_provider = $airtime_provider;
$airtime->beneficiary_phone_no = $airtime_phone_no;
$airtime->transaction_date = $day;

$airtime_buyer_balance = floatval($airtime_buyer['account_balance']);

$newBalance = $airtime_buyer_balance - $airtime_amount;



$update_airtime_buyer_sql = "UPDATE bank_user_data SET account_balance = ? WHERE id = ?";
$update_airtime_buyer_stmt = $conn->prepare($update_airtime_buyer_sql);
$update_airtime_buyer_stmt->bind_param("si", $newBalance,$airtime_buyer_id);
$update_airtime_buyer_stmt->execute();

function addComma($val)
{
   return number_format($val);
}

$airtime_comma = addComma($airtime_amount);

if ($update_airtime_buyer_stmt->affected_rows > 0){
   $airtime->record_airtime_transaction();
   session_regenerate_id(true);
    header("Location: index.php?message= ₦$airtime_comma Airtime purchase for $airtime_provider was successful!");
    exit;
 }
 $update_airtime_buyer_stmt->close();
 
?>
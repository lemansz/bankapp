<?php
 session_start();
 
 require __DIR__ . "/transactions.php";

 $data_buyer_id = $_SESSION['user_id'];

 if ($_SERVER["REQUEST_METHOD"] === "POST"){
    $data_provider = clean_input($_POST['data-provider']);
    $data_phone_no = clean_input($_POST['data-phone-number']);
    $data_plan_amount = clean_input($_POST['data-plan-amount']);
    $data_bundle = clean_input($_POST['data-bundle']);

    if (isset($data_provider)){
        $data_provider = filter_var($data_provider, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    }

    if (isset($data_phone_no)){
        $data_phone_no = filter_var($data_phone_no, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    }
    
    if (isset($data_plan_amount)){
        $data_plan_amount = filter_var($data_plan_amount, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data_plan_amount = str_replace((","), "", $data_plan_amount);
    }

    if (isset($data_bundle)){
        $data_bundle = filter_var($data_bundle, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    }
 }
 

  
 function clean_input($data){
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');

    return $data;
 }

 $data_sql = "SELECT * FROM bank_user_data WHERE id = ?";
 $data_stmt = $conn->prepare($data_sql);
 $data_stmt->bind_param("i", $data_buyer_id);
 $data_stmt->execute();
 $data_buyer_result = $data_stmt->get_result();
 $data_buyer = $data_buyer_result->fetch_assoc();

 $data = new Transactions;
 $data->sender_id = $data_buyer_id;
 $data->transaction_amount = $data_plan_amount;
 $data->transaction_type = "Data";
 $data->network_provider = $data_provider;
 $data->data_bundle = $data_bundle;
 $data->beneficiary_phone_no = $data_phone_no;
 $data->transaction_date = $day;

 $data->record_data_transaction();



 $data_buyer_balance = floatval($data_buyer['account_balance']);

 $newBalance = $data_buyer_balance - $data_plan_amount;
 
 
 
 $update_data_buyer_sql = "UPDATE bank_user_data SET account_balance = ? WHERE id = ?";
 $update_data_buyer_stmt = $conn->prepare($update_data_buyer_sql);
 $update_data_buyer_stmt->bind_param("si", $newBalance,$data_buyer_id);
 $update_data_buyer_stmt->execute();

 if ($update_data_buyer_stmt->affected_rows > 0){
    header("Location: index.php?message=data purchase successful");
    exit;
 }
 $update_data_buyer_stmt->close();
 
?>

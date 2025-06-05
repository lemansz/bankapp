<?php
 session_start();

 if (!isset($_SESSION['user_id'])) {
   header("Location: index.php");
   exit();
 }
 
 require __DIR__ . "/transactions.php";

 $data_buyer_id = $_SESSION['user_id'];

 if ($_SERVER["REQUEST_METHOD"] === "POST"){
    $data_provider = clean_input($_POST['data-provider']);
    $data_phone_no = clean_input($_POST['data-phone-number']);
    $data_plan_amount = str_replace((","), "",clean_input($_POST['data-plan-amount']));
    $data_bundle = clean_input($_POST['data-bundle']);
    $user_blance = clean_input($_POST['user-balance-data']);
    
    if (empty($data_provider)){
        die ("Data provider required.");
    }

    if (empty($data_phone_no)){
        die ("Phone number required.");
    } else if (!preg_match('/\d{11}$/', $data_phone_no)){
        die ("Invalid phone number.");
    }
    
    if (empty($data_plan_amount)){
        die ("Amount cannot be empty.");
    } else if ($data_plan_amount > $user_blance){
        die ("Insufficient funds.");
    }

    if (empty($data_bundle)){
        die ("Data bundle cannot be empty");
    }
 }
 

  
 function clean_input($data){
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
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
 $data->sender_name = $data_buyer['surname'] . " " . $data_buyer['first_name'];
 $data->sender_account_no = $data_buyer['account_number'];
 $data->transaction_type = "Data";
 $data->network_provider = $data_provider;
 $data->data_bundle = $data_bundle;
 $data->beneficiary_phone_no = $data_phone_no;
 $data->transaction_date = $day;

 
 
 
 $data_buyer_balance = floatval($data_buyer['account_balance']);
 
 $newBalance = $data_buyer_balance - $data_plan_amount;
 
 
 
 $update_data_buyer_sql = "UPDATE bank_user_data SET account_balance = ? WHERE id = ?";
 $update_data_buyer_stmt = $conn->prepare($update_data_buyer_sql);
 $update_data_buyer_stmt->bind_param("si", $newBalance,$data_buyer_id);
 $update_data_buyer_stmt->execute();
 
 if ($update_data_buyer_stmt->affected_rows > 0){
    $data->record_data_transaction();
    session_regenerate_id(true);  
    header("Location: index.php?message= Purchase of $data_bundle data bundle for $data_provider was successful!");
    exit;
 }
 $update_data_buyer_stmt->close();
 
?>

<?php
 session_start();
 include __DIR__ . "/db.php";
 
 $pin = clean_input($_POST['pin']);

 if (isset($pin) && filter_var($pin, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => '/^\d{4}$/')))){
  $pin = filter_var($pin, FILTER_SANITIZE_NUMBER_INT ); 
 }


 function clean_input($data){
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
 
    return $data;
  }


  $user_id = $_SESSION['user_id'];

  $sql = "SELECT * FROM bank_user_data WHERE id = $user_id";
  
  $result = $conn->query($sql);
  
  $user = $result->fetch_assoc();
 
  $stored_hash = $user['hashed_pin'];

  if (password_verify($pin, $stored_hash)){
    echo "valid";
  } else {
    echo "invalid";  
  }

<?php

 session_start();
 include __DIR__ . "/db.php";
 
 if ($_SERVER['REQUEST_METHOD'] == "POST"){

  $pin = clean_input($_POST['pin']);

  if (empty($pin)){
  die ("Pin required.");
  } else if (!preg_match('/^\d{4}$/', $pin)){
  die ("Invalid pin.");
  }

  if (isset($pin) && filter_var($pin, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => '/^\d{4}$/')))){
  $pin = filter_var($pin, FILTER_SANITIZE_NUMBER_INT ); 
  }
   
  $user_id = $_SESSION['user_id'];

  $sql = "SELECT * FROM bank_user_data WHERE id = ?";
  
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $user_id);
  $stmt->execute();

  $user = $stmt->get_result()->fetch_assoc();
  

  $stored_hash = $user['hashed_pin'];
  
  if (password_verify($pin, $stored_hash)){
    echo "valid";
  } else {
    echo "invalid";  
  }

  $stmt->close();

} else {
  header("Location: index.php");
  exit();
}

function clean_input($data){
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
 
  return $data;
}



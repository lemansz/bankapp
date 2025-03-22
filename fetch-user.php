<?php
 include __DIR__ . "/db.php";
 
 $q = clean_input($_GET['q']);

 $q = filter_var($q, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => '/^\d{10}$/')));

 function clean_input($data){
   $data = trim($data);
   $data = stripslashes($data);
   $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');

   return $data;
}

 if (strlen($q) == 10){
   $sql = "SELECT * FROM bank_user_data WHERE account_number = $q";
   
   $result = $conn->query($sql);
   
   $user = $result->fetch_assoc();
   
   if ($user){
   echo $user['surname'] . " " . $user['first_name'];
   } else {
      echo "";
   }
}

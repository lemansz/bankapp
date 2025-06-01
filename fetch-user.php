<?php
 include __DIR__ . "/db.php";


 if ($_SERVER['REQUEST_METHOD'] == "GET"){
   $q = clean_input($_GET['q']);
   
   if (strlen($q) == 10){
      $sql = "SELECT * FROM bank_user_data WHERE account_number = ?";
      
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("s", $q);
      $stmt->execute();
   
      $result = $stmt->get_result();
      
      $user = $result->fetch_assoc();
      
      if ($user){
      echo $user['surname'] . " " . $user['first_name'];
      } else {
         echo "";
      }
   }

 }
 

 function clean_input($data){
   $data = trim($data);
   $data = stripslashes($data);
   $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');

   return $data;
}

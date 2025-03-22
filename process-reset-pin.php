<?php

require __DIR__ . '/db.php';

$token = $_POST['token'];

$token_hash = hash('sha256', $token);

$sql = "SELECT * FROM bank_user_data WHERE reset_token_hashed = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("s", $token_hash);

$stmt->execute();

$result = $stmt->get_result();

$user = $result->fetch_assoc();

if ($user === null){
   die ('token not found');
}

if (strtotime($user['reset_token_expires_at']) <= time()) {
   die('token has expired');
}

if ($_SERVER["REQUEST_METHOD"] === "POST"){
    $pin = clean_input($_POST['pin']);
    if ($pin !== false){
        $hashed_pin = password_hash($pin, PASSWORD_DEFAULT);
    }
 }


 function clean_input($data){
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');

    return $data;
 }

 $sql = "UPDATE bank_user_data SET hashed_pin = ?, 
 reset_token_hashed = NULL, reset_token_expires_at = NULL
 WHERE id = ?";

 $stmt = $conn->prepare($sql);
 $stmt->bind_param("ss", $hashed_pin, $user['id']);
 $stmt->execute();

 echo "Pin reset successful! You may now <a href='login.php'>login</a>";
 
?>
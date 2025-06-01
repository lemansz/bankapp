<?php

require dirname(__FILE__) . '/../db.php';

$token = $_POST['token'];

$token_hash = hash('sha256', $token);

$sql = "SELECT * FROM staff WHERE reset_token_hash = ?";

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
    $passkey = clean_input($_POST['passkey']);
    if ($passkey !== false){
        $hashed_passkey = password_hash($passkey, PASSWORD_DEFAULT);
    }
 }


 function clean_input($data){
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');

    return $data;
 }

 $sql = "UPDATE staff SET staff_pass_key = ?, 
 reset_token_hash = NULL, reset_token_expires_at = NULL
 WHERE staff_id = ?";

 $stmt = $conn->prepare($sql);
 $stmt->bind_param("ss", $hashed_passkey, $user['staff_id']);
 $stmt->execute();

 if ($stmt->affected_rows > 0){
   header("Location:staff-login.php?message=Password reset successful. You may now login!");
   $stmt->close();
   exit;
 }
 
?>
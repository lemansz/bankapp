<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    
    require __DIR__ . "/db.php";
    
    $email = $_POST['email'];
    
    $token = bin2hex(random_bytes(16));
    
    $token_hash = hash("sha256", $token);
    
    $expiry = date("Y-m-d H:i:s", time() + 60 * 30);
    
    
    $sql = "UPDATE bank_user_data SET reset_token_hash = ?, reset_token_expires_at = ? WHERE email = ?";
    
    $stmt = $conn->prepare($sql);
    
    $stmt->bind_param('sss', $token_hash, $expiry, $email);
    
    $stmt->execute();
    
    if ($conn->affected_rows){
        require __DIR__ . "/mailer.php";
        $mail->setFrom($_ENV["EMAIL_SENDER"]);
        $mail->addAddress($email);
        $mail->Subject = 'Password Reset';
        $mail->Body = <<<END
    
        Click <a href = "http://localhost/Bankapp/reset-password.php?token=$token">here</a>
        to reset your password.
    
        END;
       
        try{
            $mail->send();
            header("Location:forgot-password.php?message= Reset link sent, please check your inbox.");
            exit;
        } catch (Exception $e){
            header("Location:forgot-password.php?message= Reset link sent, please check your inbox.");
            exit;
        }
    }
    
} else {
    die ("<h1>Invalid request.</h1>");
} 

?>
<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $email = $_POST['email'];
    
    $token = bin2hex(random_bytes(16));
    
    $token_hash = hash("sha256", $token);
    
    $expiry = date("Y-m-d H:i:s", time() + 60 * 30);
    
    require dirname(__FILE__) . '/../db.php';
    
    $sql = "UPDATE staff SET reset_token_hash = ?, reset_token_expires_at = ? WHERE staff_email = ?";
    
    $stmt = $conn->prepare($sql);
    
    $stmt->bind_param('sss', $token_hash, $expiry, $email);
    
    $stmt->execute();
    
    if ($conn->affected_rows){
        require dirname(__FILE__) . '/../mailer.php';
        $mail->setFrom($_ENV["EMAIL_SENDER"]);
        $mail->addAddress($email);
        $mail->Subject = 'Passkey Reset';
        $mail->Body = <<<END
    
        Click <a href = "http://localhost/Bankapp/Administration/reset-passkey.php?token=$token">here</a>
        to reset your passkey.
    
        END;
       
         try{
            $mail->send();
            header("Location:forgot-passkey-staff.php?message= Reset link sent, please check your inbox.");
            exit;
        } catch (Exception $e){
            header("Location:forgot-passkey-staff.php?message= Error sending reset link. Check your network and try again.");
            exit;
        }
    }

} else {
    die ("<h1>Invalid request.</h1>");
}

?>
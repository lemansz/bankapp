<?php

 session_start();

 if (!isset($_SESSION['user_id']) && empty($_SESSION['user_id'])){
    header("Location: landing-page.html");
    exit;
 }

 session_regenerate_id(true);

$email = $_POST['email'];

$token = bin2hex(random_bytes(16));

$token_hash = hash("sha256", $token);

$expiry = date("Y-m-d H:i:s", time() + 60 * 30);

require __DIR__ . "/db.php";

$sql = "UPDATE bank_user_data SET reset_token_hash = ?, reset_token_expires_at = ? WHERE email = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param('sss', $token_hash, $expiry, $email);

$stmt->execute();

if ($conn->affected_rows){
    require __DIR__ . "/mailer.php";
    $mail->setFrom($_ENV["EMAIL_SENDER"]);
    $mail->addAddress($email);
    $mail->Subject = 'Pin Reset';
    $mail->Body = <<<END

    Click <a href = "http://localhost/Bankapp/reset-pin.php?token=$token">here</a>
    to reset your pin.

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
?>
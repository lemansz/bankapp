<?php

use PHPMailer\PHPMailer\PHPMailer;

require __DIR__ . '/vendor/autoload.php';

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = $_ENV["EMAIL_SENDER"];
    $mail->Password = $_ENV["EMAIL_PASSWORD"];
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = $_ENV["EMAIL_PORT"];

    $mail->setFrom($_ENV["EMAIL_SENDER"], 'Your Name');
} catch (Exception $e) {
    echo "Mailer configuration error: {$mail->ErrorInfo}";
}

<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

require __DIR__ . '/vendor/autoload.php';

$mail = new PHPMailer(true);

// $mail->SMTPDebug = SMTP::DEBUG_SERVER;

$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = $_ENV["EMAIL_SENDER"];
$mail->Password = $_ENV["EMAIL_PASSWORD"];
$mail->SMTPSecure = 'ssl';
$mail->Port = $_ENV["EMAIL_PORT"];

$mail->isHTML(true);

return $mail;
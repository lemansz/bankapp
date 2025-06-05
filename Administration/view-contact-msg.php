<?php
session_start();

if (isset($_SESSION['staff_id']) && $_SESSION['staff_role'] === 'Customer Service Representative') {
    session_regenerate_id(true);
    require dirname(__FILE__) . '/../db.php';
    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        header('Location: fetch-contact-msg.php');
        exit();
    }
    $id = intval($_GET['id']);
    $stmt = $conn->prepare('SELECT * FROM contact_message WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $msg = $stmt->get_result()->fetch_assoc();
    if (!$msg) {
        header('Location: fetch-contact-msg.php');
        exit();
    }
} else {
    header('Location: staff-login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Message | Silver Bank</title>
    <link rel="shortcut icon" href="../Assets/bank-logo-index.svg" type="image/x-icon">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: #f6faf8;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 540px;
            margin: 48px auto;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(33,140,90,0.10);
            padding: 36px 28px 28px 28px;
        }
        h2 {
            color: #218c5a;
            margin-bottom: 18px;
            font-size: 1.5rem;
            text-align: center;
        }
        .msg-label {
            color: #218c5a;
            font-weight: 600;
            margin-top: 18px;
            margin-bottom: 4px;
        }
        .msg-content {
            background: #e6f7ef;
            border-radius: 10px;
            padding: 18px 14px;
            font-size: 1.08rem;
            color: #222;
            margin-bottom: 18px;
            white-space: pre-line;
        }
        .back-link {
            display: inline-block;
            margin-top: 10px;
            color: #218c5a;
            text-decoration: none;
            font-weight: 500;
            border-radius: 6px;
            padding: 6px 16px;
            background: #e6f7ef;
            transition: background 0.2s;
        }
        .back-link:hover {
            background: #b2dfc7;
        }
        .msg-meta {
            color: #666;
            font-size: 0.98rem;
            margin-bottom: 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Contact Message</h2>
        <div class="msg-meta"><b>Date:</b> <?= date('Y-m-d H:i', strtotime($msg['message_date'])) ?></div>
        <div class="msg-meta"><b>Name:</b> <?= htmlspecialchars($msg['name']) ?></div>
        <div class="msg-meta"><b>Email:</b> <?= htmlspecialchars($msg['email']) ?></div>
        <div class="msg-label">Message:</div>
        <div class="msg-content"><?= nl2br(htmlspecialchars($msg['message'])) ?></div>
        <a href="fetch-contact-msg.php" class="back-link">&larr; Back to all messages</a>
        <a href="customer-service-rep.php" class="back-link" style="margin-left:10px;">Dashboard</a>
        <hr style="margin:32px 0 18px 0; border:none; border-top:1.5px solid #e6f7ef;">
        <form method="POST" style="margin-top:0;">
            <div class="msg-label">Reply to Customer:</div>
            <textarea name="reply_message" rows="5" required style="width:100%; min-height:100px; max-height:180px; resize:none; border-radius:8px; border:1px solid #b2dfc7; background:#f6faf8; font-size:1rem; padding:12px 10px; margin-bottom:12px;"></textarea>
            <button type="submit" name="send_reply" style="background:#218c5a; color:#fff; border:none; border-radius:8px; padding:10px 28px; font-size:1.08rem; font-weight:500; cursor:pointer; transition:background 0.2s;">Send Reply</button>
        </form>
        <?php
        if (isset($_POST['send_reply'])) {
            $reply = trim($_POST['reply_message']);
            if ($reply !== '') {
                require_once dirname(__FILE__) . '/../mailer.php';
                try {
                    $mail->clearAllRecipients();
                    $mail->addAddress($msg['email'], $msg['name']);
                    $mail->Subject = 'Reply to your message at Silver Bank';
                    $mail->Body = $reply;
                    $mail->isHTML(false);
                    $mail->send();
                    echo '<div style="color:#218c5a; background:#e6f7ef; border-radius:8px; padding:12px 10px; margin-top:12px; text-align:center;">Reply sent successfully to customer!</div>';
                } catch (Exception $e) {
                    echo '<div style="color:#b71c1c; background:#ffeaea; border-radius:8px; padding:12px 10px; margin-top:12px; text-align:center;">Failed to send reply. Please try again later.</div>';
                    echo $e->getMessage(); // For debugging, remove in production
                }
            }
        }
        ?>
    </div>

<script src="check-staff-session.js"></script>
<script>
let activityTimeout;
function sendActivityUpdate() {
    const xhr = new XMLHttpRequest();
    xhr.open("GET", "check-staff-session.php?update=1&t=" + new Date().getTime(), true);
    xhr.send();
}
function activityDetected() {
    clearTimeout(activityTimeout);
    sendActivityUpdate();
    activityTimeout = setTimeout(() => {}, 60000);
}
window.addEventListener('mousemove', activityDetected);
window.addEventListener('keydown', activityDetected);
window.addEventListener('click', activityDetected);
window.addEventListener('touchstart', activityDetected);
</script>
</body>
</html>

<?php

session_start();

if (isset($_SESSION['staff_id']) && $_SESSION['staff_role'] === 'Customer Service Representative') {

    session_regenerate_id(true);
    require dirname(__FILE__) . '/../db.php';

    // Fetch all messages ordered by date (newest first)
    $stmt = $conn->prepare("SELECT id, name, email, message, message_date FROM contact_message ORDER BY message_date DESC");
    $stmt->execute();
    $result = $stmt->get_result();
    $messages = $result->fetch_all(MYSQLI_ASSOC);

} else {
    header("Location: staff-login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Messages | Silver Bank</title>
    <link rel="shortcut icon" href="../Assets/bank-logo-index.svg" type="image/x-icon">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: #f6faf8;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 40px auto;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(33,140,90,0.10);
            padding: 32px 24px 24px 24px;
        }
        h1 {
            color: #218c5a;
            margin-bottom: 24px;
            font-size: 2rem;
            text-align: center;
        }
        .messages-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
        }
        .messages-table th, .messages-table td {
            padding: 14px 10px;
            text-align: left;
        }
        .messages-table th {
            background: #e6f7ef;
            color: #218c5a;
            font-weight: 600;
            border-bottom: 2px solid #b2dfc7;
        }
        .messages-table tr {
            transition: background 0.2s;
        }
        .messages-table tr:hover {
            background: #f0f9f5;
            cursor: pointer;
        }
        .messages-table td {
            border-bottom: 1px solid #f0f0f0;
            font-size: 1rem;
        }
        .scrollable-table {
            max-height: 420px;
            overflow-y: auto;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(33,140,90,0.07);
        }
        .msg-snippet {
            color: #555;
            font-style: italic;
            max-width: 260px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        @media (max-width: 600px) {
            .container { padding: 12px 2px; }
            .messages-table th, .messages-table td { padding: 10px 4px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Contact Messages</h1>
        <div class="scrollable-table">
            <table class="messages-table">
                <thead>
                    <tr>
                        <th>Date & Time</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Message</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($messages)): ?>
                    <tr><td colspan="4" style="text-align:center; color:#888;">No messages found.</td></tr>
                <?php else: ?>
                    <?php foreach ($messages as $msg): ?>
                        <tr onclick="window.location.href='view-contact-msg.php?id=<?= $msg['id'] ?>'">
                            <td><?= date('Y-m-d H:i', strtotime($msg['message_date'])) ?></td>
                            <td><?= htmlspecialchars($msg['name']) ?></td>
                            <td><?= htmlspecialchars($msg['email']) ?></td>
                            <td class="msg-snippet"><?= htmlspecialchars(mb_strimwidth($msg['message'], 0, 40, '...')) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
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
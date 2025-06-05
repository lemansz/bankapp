<?php
 session_start();

 if (isset($_SESSION['staff_id']) && $_SESSION['staff_role'] === 'Customer Service Representative') {

    session_regenerate_id(true);
    require dirname(__FILE__) . '/../db.php';

    $stmt = $conn->prepare("SELECT * FROM staff WHERE staff_id = ?");
    $stmt->bind_param("i", $_SESSION['staff_id']);
    $stmt->execute();
    $staff = $stmt->get_result()->fetch_assoc();
   
}  else {
    header("Location: staff-login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../Assets/bank-logo-index.svg" type="image/x-icon">
    <title>CSR | Silver Bank</title>
    <link rel="stylesheet" href="style-csp.css">
</head>
<body>
   <div class="parent-container">

   <div class="log-out"><a href="staff-log-out.php" title="Log out"></a></div>

   <h1 class="staff-name-role"><?= $staff['staff_surname'] . " " . $staff['staff_first_name']?> (CSP)</h1>
   
    <div class="container">
          <h2>Customer Service Representative Dashboard</h2>
          <p>Welcome to the Customer Service Representative dashboard. Here you can manage customer inquiries and messages.</p>
          <ul>
                <li><a href="fetch-contact-msg.php">View Contact Messages</a></li>
                <li><a href="change-staff-passkey.php">Change Passkey</a></li>
          </ul>
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
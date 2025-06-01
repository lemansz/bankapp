<?php

require dirname(__FILE__) . '/../db.php';

if ($_SERVER['REQUEST_METHOD'] == "GET"){
    
    if (isset($_GET['q'])) {
        $account_no = $_GET['q'];
    
        // Validate account number
        if (!preg_match('/^\d{10}$/', $account_no)) {
            echo "";
            exit;
        }
    
        // Fetch user details
        $stmt = $conn->prepare("SELECT first_name, surname, account_balance FROM bank_user_data WHERE account_number = ?");
        $stmt->bind_param("s", $account_no);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            echo  $user['surname'] . " " . $user['first_name'];
        } else {
            echo "";
        }
        $stmt->close();
        exit;
    }

}

<?php
session_start();

if (!isset($_SESSION['staff_id'])){
    die ('Unathorized access.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST'){

    require dirname(__FILE__) . '/../transactions.php';

    $recipient_account_no = clean_input($_POST['deposit-account-no']);
    $deposit_amount = clean_input(str_replace((","), "", $_POST['deposit-amount']));
    $str_deposit_amount = clean_input($_POST['deposit-amount']);

    if (empty($recipient_account_no) || empty($deposit_amount)){
        die ("All fields are required");
    }

    if (!preg_match('/\d{10}$/', $recipient_account_no)){
        die ("Invalid account no.");
    }

    if ($deposit_amount < 1000){
        die ("₦1000 is the minimum deposit.");
    }


    $fetch_recipient_sql = "SELECT * FROM bank_user_data WHERE account_number = ?";

    $fetch_recipient_stmt = $conn->prepare($fetch_recipient_sql);

    $fetch_recipient_stmt->bind_param("s", $recipient_account_no);

    $fetch_recipient_stmt->execute();

    $fetch_recipient_result = $fetch_recipient_stmt->get_result();

    $recipient = $fetch_recipient_result->fetch_assoc();

    $deposit = new Transactions;

    $deposit->beneficiary_id = $recipient['id'];
    $deposit->transaction_amount = $deposit_amount;
    $deposit->beneficiary_name = $recipient['surname'] . " " . $recipient['first_name'];
    $deposit->beneficiary_account_no = $recipient['account_number'];
    $deposit->transaction_type = "Deposit";
    $deposit->transaction_date = $day;


    if (!isset($recipient)){
        $fetch_recipient_stmt->close();
        header("Location: staff-cashier.php?error=user_not_found");
        exit;
    }

    $recipient_balance = $recipient['account_balance'];

    $new_balance = $deposit_amount + $recipient_balance;

    $update_recipient_balance_sql = "UPDATE bank_user_data SET account_balance = ? WHERE account_number = ?";
    $update_recipient_balance_stmt = $conn->prepare($update_recipient_balance_sql);
    $update_recipient_balance_stmt->bind_param("ss", $new_balance, $recipient_account_no);
    $update_recipient_balance_stmt->execute();

    if ($update_recipient_balance_stmt->affected_rows > 0){
        $deposit->record_deposit();
        $update_recipient_balance_stmt->close();
        session_regenerate_id(true);
        header("Location: staff-cashier.php?message= Deposit of ₦$str_deposit_amount was successful!");
        exit;
    }
}


function clean_input($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}
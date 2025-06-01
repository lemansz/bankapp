<?php
 session_start();

if (!isset($_SESSION['staff_id'])){
    die ('Unathorized access.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST'){

    require dirname(__FILE__) . '/../transactions.php';

    $recipient_account_no = clean_input($_POST['withdraw-account-no']);
    $withdraw_amount = clean_input(str_replace((","), "", $_POST['withdraw-amount']));
    $withdraw_pin = clean_input($_POST['withdraw-pin']);
    $str_withdraw_amount = clean_input($_POST['withdraw-amount']);

    if (empty($recipient_account_no) || empty($withdraw_amount) || empty($withdraw_pin)){
        die ("All fields are required");
    }

    if (!preg_match('/\d{10}$/', $recipient_account_no)){
        die ("Invalid account no.");
    }

    if ($withdraw_amount < 1000){
        die ("₦1000 is the minimum withdrawer.");
    }

    if (!preg_match('/^\d{4}$/', $withdraw_pin)){
        die ("Invalid pin.");
    }

    $fetch_recipient_sql = "SELECT * FROM bank_user_data WHERE account_number = ?";

    $fetch_recipient_stmt = $conn->prepare($fetch_recipient_sql);

    $fetch_recipient_stmt->bind_param("s", $recipient_account_no);

    $fetch_recipient_stmt->execute();

    $fetch_recipient_result = $fetch_recipient_stmt->get_result();

    $recipient = $fetch_recipient_result->fetch_assoc();

    $withdraw = new Transactions;

    $withdraw->beneficiary_id = $recipient['id'];
    $withdraw->transaction_amount = $withdraw_amount;
    $withdraw->beneficiary_name = $recipient['surname'] . " " . $recipient['first_name'];
    $withdraw->beneficiary_account_no = $recipient['account_number'];
    $withdraw->transaction_type = "Withdraw";
    $withdraw->transaction_date = $day;

    if (!isset($recipient)){
        $fetch_recipient_stmt->close();
        header("Location: staff-cashier.php?error=user_not_found_w");
        exit;
    }

    if ($withdraw_amount > $recipient['account_balance']) {
        $fetch_recipient_stmt->close();
        header("Location: staff-cashier.php?error=insufficient_funds");
        exit;
    }
    
    if (password_verify($withdraw_pin, $recipient['hashed_pin'])){
        $recipient_balance = $recipient['account_balance'];

        $new_balance = $recipient_balance - $withdraw_amount ;

        $update_recipient_balance_sql = "UPDATE bank_user_data SET account_balance = ? WHERE account_number = ?";
        $update_recipient_balance_stmt = $conn->prepare($update_recipient_balance_sql);
        $update_recipient_balance_stmt->bind_param("ss", $new_balance, $recipient_account_no);
        $update_recipient_balance_stmt->execute();

        if ($update_recipient_balance_stmt->affected_rows > 0){
            $withdraw->record_withdraw();
            $update_recipient_balance_stmt->close();
            session_regenerate_id(true);
            header("Location: staff-cashier.php?message= Withdrawal of ₦$str_withdraw_amount was successful!");
            exit;
        }

    } else if (!password_verify($withdraw_pin, $recipient['hashed_pin'])){
        $fetch_recipient_stmt->close();
        header("Location: staff-cashier.php?error=incorrect_pin_w");
        exit;
    }

}


function clean_input($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}
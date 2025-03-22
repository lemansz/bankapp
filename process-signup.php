
<?php
 session_start();

 require __DIR__ . "/db.php";

 if ($conn->connect_errno){
    die ("Error connecting to database" . $conn->connect_error);
 }

 if ($_SERVER["REQUEST_METHOD"] === "POST"){

    $surname = clean_input($_POST['surname']);
    $first_name = clean_input($_POST['firstName']);
    $email = clean_input($_POST['email']);
    $phone_number = clean_input($_POST['phoneNumber']);
    $password = clean_input($_POST['password']);

    if (isset($surname) && filter_var($surname, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => '/^[a-zA-Z]+$/')))){

        $surname = filter_var($surname, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    }

    if (isset($first_name) && filter_var($first_name, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => '/^[a-zA-Z]+$/')))){

        $first_name = filter_var($first_name, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    }
    
    if (isset($email) && filter_var($email, FILTER_VALIDATE_EMAIL)){

        $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    }

    if (isset($phone_number) && filter_var($phone_number, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => '/^0\d{10}$/')))){

        $phone_number = filter_var($phone_number, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $phone_number = str_replace("-", "", $phone_number);
    }
    
    if (isset($password)){
        $password = filter_var($password, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    }
 }


 function clean_input($data){
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');

    return $data;
 }

 function generate_account_no(){
    $random_digits = "477" . rand(1000000, 9999999);
    return $random_digits;
 }

 $unique = false;

 while(!$unique){
    $account_no = generate_account_no();
    $sql = "SELECT COUNT(*) as count FROM bank_user_data WHERE account_number = '$account_no'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0){
        $row = $result->fetch_assoc();
        if ($row["count"] == 0){
            $unique = true;
        }
    }
 }
 $account_balance = 0;

 $sql = "INSERT INTO bank_user_data (surname, 
 first_name, account_number, account_balance, email, phone_number, hashed_password) VALUES (?, ?, ?, ?, ?, ?, ?)";
 $stmt = $conn->stmt_init();

 if (!$stmt->prepare($sql)){
    die ("SQL error: " . $conn->error);
 }
 
 $stmt->bind_param("sssssss", $surname, $first_name, $account_no, $account_balance, $email, $phone_number, $hashed_password);

try {
    $stmt->execute();
    $user_id = $conn->insert_id;
    $_SESSION['user_id'] = $user_id;
    header("Location: generate-pin.php");
    exit;
} catch (mysqli_sql_exception $e) {
    if ($e->getCode() == 1062) {
        echo "Error: Duplicate entry for email.";
        header("Location: signup.php?error=email_taken");
        exit;
    } else {
        echo "Error: " . $e->getMessage();
    }
}

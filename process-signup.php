<?php
session_start();

require __DIR__ . "/db.php";

if ($conn->connect_errno) {
    die("Error connecting to database: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Input validation
    $surname = clean_input($_POST['surname']);
    $first_name = clean_input($_POST['firstName']);
    $email = clean_input($_POST['email']);
    $phone_number = clean_input($_POST['phoneNumber']);
    $password = $_POST['password']; 

    // Validate required fields
    if (empty($surname) || empty($first_name) || empty($email) || empty($phone_number) || empty($password)) {
        die("All fields are required.");
    }

    // Validate surname and first name
    if (!preg_match('/^[a-zA-Z]+$/', $surname) || !preg_match('/^[a-zA-Z]+$/', $first_name)) {
        die("Name fields must contain only letters.");
    }

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format.");
    }

    // Validate phone number
    if (!preg_match('/^0\d{10}$/', $phone_number)) {
        die("Invalid phone number format.");
    }

    // Check for duplicate email
    $sql = "SELECT COUNT(*) as count FROM bank_user_data WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->fetch_assoc()['count'] > 0) {
        header("Location: signup.php?error=email_taken");
        exit;
    }

    // Generate unique account number
    function generate_account_no($conn) {
        do {
            $account_no = "477" . random_int(1000000, 9999999); // Use random_int for better randomness
            $sql = "SELECT COUNT(*) as count FROM bank_user_data WHERE account_number = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $account_no);
            $stmt->execute();
            $result = $stmt->get_result();
        } while ($result->fetch_assoc()['count'] > 0);
        return $account_no;
    }

    $account_no = generate_account_no($conn);
    $account_balance = 0;

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert user into database
    $sql = "INSERT INTO bank_user_data (surname, first_name, account_number, account_balance, email, phone_number, hashed_password) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("SQL error: " . $conn->error);
    }
    $stmt->bind_param("sssssss", $surname, $first_name, $account_no, $account_balance, $email, $phone_number, $hashed_password);

    try {
        $stmt->execute();
        $user_id = $conn->insert_id;
        $_SESSION['user_id'] = $user_id;
        session_regenerate_id(true); // Prevent session fixation
        header("Location: generate-pin.php");
        exit;
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1062) {
            header("Location: signup.php?error=email_taken");
            exit;
        } else {
            error_log("Database error: " . $e->getMessage());
            die("An error occurred. Please try again later.");
        }
    }
}

// Function to clean input
function clean_input($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

<?php
require dirname(__FILE__) . '/../db.php';

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $staff_surname = clean_input($_POST['staff-surname']);
    $staff_first_name = clean_input($_POST['first-name']);
    $staff_phone_no = clean_input($_POST['staff-phone-no']);
    $staff_email = clean_input($_POST['staff-email']);
    $staff_role = clean_input($_POST['staff-role']);
    $staff_gender = clean_input($_POST['staff-gender']);

    // Validate required fields
    if (empty($staff_surname) || empty($staff_first_name) || empty($staff_email) || empty($staff_role) || empty($staff_phone_no) || empty($staff_gender)) {
        die("All fields are required.");
    }

    // Validate surname and first name
    if (!preg_match('/^[a-zA-Z]+$/', $staff_surname) || !preg_match('/^[a-zA-Z]+$/', $staff_first_name)) {
        die("Name fields must contain only letters.");
    }

    // Validate phone number
    if (!preg_match('/^0\d{10}$/', $staff_phone_no)) {
        die("Invalid phone number format.");
    }

    // Validate email
    if (!filter_var($staff_email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format.");
    }

    // Check for duplicate email
    $check_email_sql = "SELECT COUNT(*) as count FROM staff WHERE staff_email = ?";
    $check_email_stmt = $conn->prepare($check_email_sql);
    $check_email_stmt->bind_param("s", $staff_email);
    $check_email_stmt->execute();
    $result = $check_email_stmt->get_result();
    if ($result->fetch_assoc()['count'] > 0) {
        header("Location: add-staff.php?error=email_taken");
        exit;
    }

    // Generate passkey
    $pass_key = random_int(100000, 999999);

    // Determine honourific
    $honourific = ($staff_gender === "Male") ? "Mr" : "Mrs";

    // Hash passkey
    $hashed_pass_key = password_hash($pass_key, PASSWORD_DEFAULT);

    // Begin transaction
    $conn->begin_transaction();

    try {
        // Insert staff record
        $record_staff_sql = "INSERT INTO staff (staff_surname, staff_first_name, staff_phone_no, staff_email, staff_role, staff_gender, staff_pass_key) VALUES (?,?,?,?,?,?,?)";
        $stmt = $conn->prepare($record_staff_sql);

        if (!$stmt) {
            throw new Exception("SQL error: " . $conn->error);
        }

        $stmt->bind_param("sssssss", $staff_surname, $staff_first_name, $staff_phone_no, $staff_email, $staff_role, $staff_gender, $hashed_pass_key);
        $stmt->execute();

        if ($stmt->affected_rows <= 0) {
            throw new Exception("Failed to add staff member.");
        }

        // Include mailer
        require dirname(__FILE__) . '/../mailer.php';
        $mail->setFrom($_ENV["EMAIL_SENDER"]);
        $mail->isHTML(true);
        $mail->addAddress($staff_email);
        $mail->Subject = 'Staff login key';
        $mail->Body = <<<END
        <p>Congratulations! <strong>$honourific $staff_surname $staff_first_name</strong>,</p>
        <p>You have been given a role of <strong>$staff_role</strong> at Silver Bank.</p>
        <p>Your login key for Silver Bank staff portal is <b style="color: blue;">$pass_key</b>.</p>
        <p>Do not share with anyone. Best regards!</p>

        END;

        // Attempt to send email
        if (!$mail->send()) {
            throw new Exception("Failed to send email. Mailer Error: " . $mail->ErrorInfo);
        }

        // Commit transaction if everything succeeds
        $conn->commit();
        header("Location: add-staff.php?message=Staff member added successfully. Login key as been sent to staff email.");
        exit;
    } catch (Exception $e) {
        // Rollback transaction if any error occurs
        $conn->rollback();
        error_log("Error: " . $e->getMessage());
        header("Location: add-staff.php?message=An error occured please check your internet connection and try again.");
    } finally {
        // Close the statement
        if (isset($stmt)) {
            $stmt->close();
        }
    }
}

// Function to sanitize input
function clean_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}
?>
<?php

 if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    require 'db.php';

    
    $name = clean_input($_POST['name']);
    $name = preg_replace('/\s+/', ' ', $name); 
    $name = trim($name);
    $day = date('M dS, Y H:i:s');
    $email = clean_input($_POST['email']);

    
    $message = clean_input($_POST['message']);
    if (strlen($message) > 310) {
        die("Message is too long.");
    }

    
    if (empty($name) || empty($email) || empty($message)) {
        die("All fields are required.");
    }

    
    if (!preg_match("/^[a-zA-Z\s'-]+$/", $name)) {
        die("Name can only contain letters, spaces, hyphens, and apostrophes.");
    }

    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format.");
    }

    $stmt = $conn->prepare("INSERT INTO contact_message (name, email, message, message_date) VALUES (?, ?, ?, ?)");
    if ($stmt === false) {
        die("Database error: " . $conn->error);
    }

    $stmt->bind_param("ssss", $name, $email, $message, $day);

    if ($stmt->execute()) {
        header("Location:contact-us.html?success=1");
        exit();
    } else {
        header("Location:contact-us.html?error=1");
        exit();
    }

}

 function clean_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}
<?php

ob_start();
header('Content-Type: application/json');

ini_set('session.cookie_httponly', 1);
ini_set('session.use_strict_mode', 1);
ini_set('session.use_only_cookies', 1); 
ini_set('session.cookie_samesite', 'Strict'); 

session_start();


if (isset($_SESSION['staff_id'])) {
    
    if (isset($_SESSION['last_activity'])) {
        $time_since_last_activity = time() - $_SESSION['last_activity'];
        
        if ($time_since_last_activity > 5 * 60) { // 5minutes
            session_unset();
            session_destroy();
            echo json_encode(['status' => 'expired']);
            ob_end_flush();
            exit;
        }
    }
    // Only update last_activity if update=1 is set in the query string
    if (isset($_GET['update']) && $_GET['update'] == '1') {
        $_SESSION['last_activity'] = time();
    }
    echo json_encode(['status' => 'active']);
    ob_end_flush();
    exit;
} else {
    
    echo json_encode(['status' => 'expired']);
    ob_end_flush();
    exit;
}
<?php

if (isset($_SESSION['user_name']) && (isset($_SESSION['ip_user'])) && ($_SESSION['private'] == $_SESSION['private_alternative']) && ($_SESSION['ip_user'] == $_SERVER['REMOTE_ADDR'])) {
    if (isset($_SESSION['user_last_activity']) && (time() - $_SESSION['user_last_activity'] > 600)) {
        // last request was more than 30 minutes ago
        session_unset();     // unset $_SESSION variable for the run-time 
        session_destroy();   // destroy session data in storage
        session_start();
        session_regenerate_id(true);
        $redirectAdmin = $_SERVER['REQUEST_URI'];
     
    }
    $_SESSION['user_last_activity'] = time(); // update last activity time stamp
}
?>

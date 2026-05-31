<?php
    if(session_status() === PHP_SESSION_NONE){
        session_start();
    }

    if(!isset($_SESSION['user_id'])){
        $_SESSION['user_id'] = 1;
    }

    if(!isset($_SESSION['first_name'])){
        $_SESSION['first_name'] = 'Admin';
    }

    if(!isset($_SESSION['account_type'])){
        $_SESSION['account_type'] = 'admin';
    }

    function adminDisplayName(){
        return $_SESSION['first_name'] ?? 'Admin';
    }
?>
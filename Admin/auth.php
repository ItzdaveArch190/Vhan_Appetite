<?php
    if(session_status() === PHP_SESSION_NONE){
        session_start();
    }

    if(!isset($_SESSION['user_id']) || !isset($_SESSION['account_type']) || strtolower($_SESSION['account_type']) != 'admin'){
        header("Location: Login.php");
        exit();
    }

    function adminDisplayName(){
        return $_SESSION['first_name'] ?? 'Admin';
    }
?>
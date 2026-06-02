<?php
    if(session_status() === PHP_SESSION_NONE){
        session_start();
    }

    if(!isset($_SESSION['user_id'])){
        $_SESSION['user_id'] = 1;
    } 

    if(!isset($_SESSION['username'])){
        $_SESSION['username'] = 'Guest';
    }

    function staffUsername(){
        return $_SESSION['username'] ?? '';
    }
?>
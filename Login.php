<?php
session_start();
require_once('Database/database.php');
$con =  new Database();

    $error = "";

    if($_SERVER['REQUEST_METHOD'] === "POST"){

        $username = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $adminUser = $con->loginUser($username, $password);
        if($adminUser && isset($adminUser['account_type']) && strtolower($adminUser['account_type']) === 'admin'){
            $_SESSION['Owner_ID'] = $adminUser['owner_id'] ?? $adminUser['Owner_ID'] ?? null;
            $_SESSION['Email'] = $adminUser['email'] ?? $username;
            $_SESSION['username'] = trim(($adminUser['first_name'] ?? '') . ' ' . ($adminUser['last_name'] ?? ''));
            $_SESSION['account_type'] = 'admin';

            header("Location: Admin/Dashboard.php");
            exit();
        }

        $staffCredentials = $con->staffLogin();
        foreach($staffCredentials as $staff ){
            $passwordMatches = false;
            if(isset($staff['Password']) && $staff['Password'] !== ''){
                $passwordMatches = password_verify($password, $staff['Password']) || $password === $staff['Password'];
            }

            if($username === $staff['Email'] && $passwordMatches){
                $_SESSION['user_id'] = $staff['Employee_ID'];
                $_SESSION['Email'] = $staff['Email'];
                $_SESSION['username'] = $staff['username'];
                $_SESSION['account_type'] = 'staff';

                header("Location: Staff/Frontdesk.php");
                exit();
            }
        }

        $error = "Invalid email or password";
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<title>Login</title>

<style>
body{
    background: linear-gradient(to bottom right, white, orange);
}

/* From Uiverse.io by Juanes200122 */ 
.container {
    background-color: transparent;
}
@keyframes bounce {
    0%,
    100% {
    translate: 0px 36px;
    }
    50% {
        translate: 0px 46px;
    }
}
@keyframes bounce2 {
    0%,
    100% {
        translate: 0px 46px;
    }
    50% {
        translate: 0px 56px;
    }
}

@keyframes umbral {
    0% {
        stop-color: #d3a5102e;
    }
    50% {
        stop-color: rgba(211, 165, 16, 0.519);
    }
    100% {
        stop-color: #d3a5102e;
    }
}
@keyframes partciles {
    0%,
    100% {
        translate: 0px 16px;
    }
    50% {
        translate: 0px 6px;
    }
}
#particles {
    animation: partciles 2s ease-in-out infinite;
    }
    #animatedStop {
    animation: umbral 1s infinite;
    }
    #bounce {
    animation: bounce 2s ease-in-out infinite;
    translate: 0px 36px;
    }
    #bounce2 {
    animation: bounce2 2s ease-in-out infinite;
    translate: 0px 46px;
    animation-delay: 0.5s;
    }

    svg{
        position:relative;
        top: 25%;
    }
    p{
        font-family:'Brush Script MT', 'Brush Script Std', cursive;
    }
</style>
</head>

<body class="">  
    

<div class="container vh-100 d-flex justify-content-center align-items-center flex-direction-column">

<svg xmlns="http://www.w3.org/2000/svg" height="200" width="200">
        <g style="order: -1;">
        <polygon
            transform="rotate(45 100 100)"
            stroke-width="1"
            stroke="#d3a410"
            fill="none"
            points="70,70 148,50 130,130 50,150"
            id="bounce"
        ></polygon>
        <polygon
            transform="rotate(45 100 100)"
            stroke-width="1"
            stroke="#d3a410"
            fill="none"
            points="70,70 148,50 130,130 50,150"
            id="bounce2"
        ></polygon>
        <polygon
            transform="rotate(45 100 100)"
            stroke-width="2"
            stroke=""
            fill="#414750"
            points="70,70 150,50 130,130 50,150"
        ></polygon>
        <polygon
            stroke-width="2"
            stroke=""
            fill="url(#gradiente)"
            points="100,70 150,100 100,130 50,100"
        ></polygon>
        <defs>
            <linearGradient y2="100%" x2="10%" y1="0%" x1="0%" id="gradiente">
                <stop style="stop-color: #1e2026;stop-opacity:1" offset="20%"></stop>
                <stop style="stop-color:#414750;stop-opacity:1" offset="60%"></stop>
            </linearGradient>
        </defs>
        <polygon
            transform="translate(20, 31)"
            stroke-width="2"
            stroke=""
            fill="#b7870f"
            points="80,50 80,75 80,99 40,75"
        ></polygon>
        <polygon
            transform="translate(20, 31)"
            stroke-width="2"
            stroke=""
            fill="url(#gradiente2)"
            points="40,-40 80,-40 80,99 40,75"
        ></polygon>
        <defs>
            <linearGradient y2="100%" x2="0%" y1="-17%" x1="10%" id="gradiente2">
            <stop style="stop-color: #d3a51000;stop-opacity:1" offset="20%"></stop>
            <stop
                style="stop-color:#d3a51054;stop-opacity:1"
                offset="100%"
                id="animatedStop"
                ></stop>
            </linearGradient>
        </defs>
        <polygon
            transform="rotate(180 100 100) translate(20, 20)"
            stroke-width="2"
            stroke=""
            fill="#d3a410"
            points="80,50 80,75 80,99 40,75"
        ></polygon>
        <polygon
            transform="rotate(0 100 100) translate(60, 20)"
            stroke-width="2"
            stroke=""
            fill="url(#gradiente3)"
            points="40,-40 80,-40 80,85 40,110.2"
        ></polygon>
        <defs>
            <linearGradient y2="100%" x2="10%" y1="0%" x1="0%" id="gradiente3">
            <stop style="stop-color: #d3a51000;stop-opacity:1" offset="20%"></stop>
            <stop
                style="stop-color:#d3a51054;stop-opacity:1"
                offset="100%"
                id="animatedStop"
            ></stop>
        </linearGradient>
        </defs>
        <polygon
            transform="rotate(45 100 100) translate(80, 95)"
            stroke-width="2"
            stroke=""
            fill="#ffe4a1"
            points="5,0 5,5 0,5 0,0"
            id="particles"
        ></polygon>
        <polygon
            transform="rotate(45 100 100) translate(80, 55)"
            stroke-width="2"
            stroke=""
            fill="#ccb069"
            points="6,0 6,6 0,6 0,0"
            id="particles"
        ></polygon>
        <polygon
            transform="rotate(45 100 100) translate(70, 80)"
            stroke-width="2"
            stroke=""
            fill="#fff"
            points="2,0 2,2 0,2 0,0"
            id="particles"
        ></polygon>
        <polygon
            stroke-width="2"
            stroke=""
            fill="#292d34"
            points="29.5,99.8 100,142 100,172 29.5,130"
            ></polygon>
        <polygon
            transform="translate(50, 92)"
            stroke-width="2"
            stroke=""
            fill="#1f2127"
            points="50,50 120.5,8 120.5,35 50,80"
        ></polygon>
        </g>
    </svg>
    

    <div class="card p-4 shadow" style="width: 350px;">
        <h1 class="text-center mb-4">Login</h1>

        <?php if(!empty($error)): ?>
            <div class="alert alert-danger text-center">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <div class="text-center mb-3">
            <div class="text-center mb-3">
                <i class="fa-solid fa-user fa-2x"></i>
        </div>
    </div>




        <form method="POST">

            <div class="form-floating mb-3">
                <input type="email" class="form-control" name="email" placeholder="Email">
                <label>Email address</label>
            </div>

            <div class="form-floating mb-3">
                <input type="password" class="form-control" name="password" placeholder="Password">
                <label>Password</label>
            </div>

            <button type="submit" class="btn btn-primary w-100">
                Login
            </button>

            <a href="#">Forgot password</a>

        </form>

    </div>


        <!-- From Uiverse.io by Juanes200122 --> 
    


<svg xmlns="http://www.w3.org/2000/svg" height="200" width="200">
        <g style="order: -1;">
        <polygon
            transform="rotate(45 100 100)"
            stroke-width="1"
            stroke="#d3a410"
            fill="none"
            points="70,70 148,50 130,130 50,150"
            id="bounce"
        ></polygon>
        <polygon
            transform="rotate(45 100 100)"
            stroke-width="1"
            stroke="#d3a410"
            fill="none"
            points="70,70 148,50 130,130 50,150"
            id="bounce2"
        ></polygon>
        <polygon
            transform="rotate(45 100 100)"
            stroke-width="2"
            stroke=""
            fill="#414750"
            points="70,70 150,50 130,130 50,150"
        ></polygon>
        <polygon
            stroke-width="2"
            stroke=""
            fill="url(#gradiente)"
            points="100,70 150,100 100,130 50,100"
        ></polygon>
        <defs>
            <linearGradient y2="100%" x2="10%" y1="0%" x1="0%" id="gradiente">
                <stop style="stop-color: #1e2026;stop-opacity:1" offset="20%"></stop>
                <stop style="stop-color:#414750;stop-opacity:1" offset="60%"></stop>
            </linearGradient>
        </defs>
        <polygon
            transform="translate(20, 31)"
            stroke-width="2"
            stroke=""
            fill="#b7870f"
            points="80,50 80,75 80,99 40,75"
        ></polygon>
        <polygon
            transform="translate(20, 31)"
            stroke-width="2"
            stroke=""
            fill="url(#gradiente2)"
            points="40,-40 80,-40 80,99 40,75"
        ></polygon>
        <defs>
            <linearGradient y2="100%" x2="0%" y1="-17%" x1="10%" id="gradiente2">
            <stop style="stop-color: #d3a51000;stop-opacity:1" offset="20%"></stop>
            <stop
                style="stop-color:#d3a51054;stop-opacity:1"
                offset="100%"
                id="animatedStop"
                ></stop>
            </linearGradient>
        </defs>
        <polygon
            transform="rotate(180 100 100) translate(20, 20)"
            stroke-width="2"
            stroke=""
            fill="#d3a410"
            points="80,50 80,75 80,99 40,75"
        ></polygon>
        <polygon
            transform="rotate(0 100 100) translate(60, 20)"
            stroke-width="2"
            stroke=""
            fill="url(#gradiente3)"
            points="40,-40 80,-40 80,85 40,110.2"
        ></polygon>
        <defs>
            <linearGradient y2="100%" x2="10%" y1="0%" x1="0%" id="gradiente3">
            <stop style="stop-color: #d3a51000;stop-opacity:1" offset="20%"></stop>
            <stop
                style="stop-color:#d3a51054;stop-opacity:1"
                offset="100%"
                id="animatedStop"
            ></stop>
        </linearGradient>
        </defs>
        <polygon
            transform="rotate(45 100 100) translate(80, 95)"
            stroke-width="2"
            stroke=""
            fill="#ffe4a1"
            points="5,0 5,5 0,5 0,0"
            id="particles"
        ></polygon>
        <polygon
            transform="rotate(45 100 100) translate(80, 55)"
            stroke-width="2"
            stroke=""
            fill="#ccb069"
            points="6,0 6,6 0,6 0,0"
            id="particles"
        ></polygon>
        <polygon
            transform="rotate(45 100 100) translate(70, 80)"
            stroke-width="2"
            stroke=""
            fill="#fff"
            points="2,0 2,2 0,2 0,0"
            id="particles"
        ></polygon>
        <polygon
            stroke-width="2"
            stroke=""
            fill="#292d34"
            points="29.5,99.8 100,142 100,172 29.5,130"
            ></polygon>
        <polygon
            transform="translate(50, 92)"
            stroke-width="2"
            stroke=""
            fill="#1f2127"
            points="50,50 120.5,8 120.5,35 50,80"
        ></polygon>
        </g>
    </svg>


</body>
</html>

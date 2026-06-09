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

</body>
</html>

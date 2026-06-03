<?php
    require_once('auth.php');
    require_once('../Database/database.php');
    require_once('sidebar.php');
    
    $con = new Database();
    $fetchAttendance = $con->fetchPreviousAttendance();
    
    date_default_timezone_set("Asia/Manila");
    $employee_id = $_SESSION['user_id'];
    

    

    if(!isset($_SESSION['timeDisable'])){
        $_SESSION['timeDisable'] = false;
    }

    $TimeInButton_DisableActive =  $_SESSION['timeDisable'];
    $TimeOutButton_DisableActive = !$_SESSION['timeDisable'];

    if(isset($_POST['log-in'])){

        $_SESSION['timeDisable'] = true; //means true

        $LogIn = date('h:i A');
        $_SESSION['timeIN'] = $LogIn; 

        $_SESSION['message'] = 'Time In : ' . $_SESSION['timeIN'];
        $_SESSION['msg_type'] = "success";

        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }


    

    if(isset($_POST['logout'])){

        if(!isset($_SESSION['timeDisable'])){

            $_SESSION['message'] = 'You must time in first!.';
            $_SESSION['msg_type'] = 'danger';
            header("Location : " . $_SERVER['PHP_SELF']);
            exit();

        } 
        
        $_SESSION['timeDisable'] =  false;

        $currentDate = date('Y-m-d'); //Date to post

        $logOut = date('h:i A'); //time out
        $_SESSION['timeOut'] = $logOut; 

        $con->insertAttendance($employee_id, $currentDate, $_SESSION['timeIN'], $_SESSION['timeOut']);

        $_SESSION['message'] = 'Time Out : ' . $_SESSION['timeOut'];
        $_SESSION['msg_type'] = "success";

        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
        
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <title>Check Attendance</title>
    <style>
        .sidebar {
            width: 250px;
            min-height:100vh;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .owner-name{
            font-family:'Brush Script MT', 'Brush Script Std', cursive;
            font-size: 20px;
        }
        .custom-btn{
            background-color:#E69B1A;
            height: 50px;
        }
        .custom-btn:hover{
            background-color:#BC7F15;
        }
        .logout-btn{
            height: 50px;
            width: 100px;
        }
        #round-profile{
            height: 100px;
            width: 100px;
            border-radius:50px;
            border: none;
            margin:0;
        }
        .business-name-divider{
            width:auto;
            display:flex;
            justify-content:center;
            align-items:center;
            flex-direction:row;
            margin:0;
        }
        .title{
            margin:0;
        }
        .sidebar-header{
            padding-left: 10px;
            padding-right:10px;
        }
        .container{
            width: calc(100% - 250px);
        }
        body{
            margin:0;
        }

        .scrollable-box{
            overflow-y:scroll;
            -webkit-overflow-scrolling: touch;
        }


    </style>
</head>
<body>
<div class="d-flex vh-100">
    <?php renderStaffSidebar(); ?>

    <main class="container" style="margin-left: 250px; margin-top: 30px;">

        <?php if(isset($_SESSION['message'])){ ?>
            <div class="alert alert-<?php echo $_SESSION['msg_type']; ?> alert-dismissible fade show" role="alert">

                <?php echo $_SESSION['message']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>

        <?php  
                unset($_SESSION['message']);
                unset($_SESSION['msg_type']);
        } ?>


        

        <div class="container w-58 h-30 mt-3 text-center shadow-lg p-3 mb-5 bg-body-tertiary rounded">
            <h3 class="fw-bold">Attendance</h3>
            <div class="row">
                <div class="col">Check Previous your performance and get Started.</div>
            </div>
        </div>

        

        <div class="row w-100 justify-content-center">

            <div class="col">
                <div class="card shadow-lg p-3 mb-5 bg-body-tertiary rounded">

                    <div class="card-body text-center">
                        <h5 class="card-title fw-bold">Get Started</h5>
                        <p class="card-text">Start your day now!.</p>

                        <div class="d-grid gap-2">

                            <form method="POST">
                                <button id="timein" class="btn btn-primary" name="log-in" type="submit" <?php echo $TimeInButton_DisableActive ? 'disabled' : ''; ?>>
                                    Time In
                                </button>

                                <button id="timeout" class="btn btn-primary" name="logout" type="submit" <?php echo $TimeOutButton_DisableActive ? 'disabled' : '';?>
                                    >Log out
                                </button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>

            
            

                <!--    Recent schedule   -->
            <div class="col">
                <div class="card scrollable-box shadow-lg p-3 mb-5 bg-body-tertiary rounded">
                    <table class="table table-success table-hover text-center">
                        <thead>
                            <tr>
                                <th>Time In</th>
                                <th>Time Log</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($fetchAttendance as $Attendee){ ?>
                            <tr>
                                <td><?php echo $Attendee['Time_in']; ?></td>
                                <td><?php echo $Attendee['Time_out']; ?></td>
                                <td><?php echo $Attendee['Attendance_Date']; ?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </main>
</div>
</body>
<script src="../functions/window.js"></script>
</html>
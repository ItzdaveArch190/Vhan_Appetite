<?php
    require_once('auth.php');
    require_once('../Database/database.php');
    require_once('sidebar.php');
    
    $con = new Database();
    $fetchAttendance = $con->fetchPreviousAttendance();

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
    </style>
</head>
<body>
<div class="d-flex vh-100">
    <?php renderStaffSidebar(); ?>

    <main class="container" style="margin-left: 250px; margin-top: 30px;">

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
                            <button class="btn btn-primary" type="button">Time In</button>
                            <button class="btn btn-primary" <?php ?> type="button">Log out</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card shadow-lg p-3 mb-5 bg-body-tertiary rounded">
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
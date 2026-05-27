<?php
    require_once('../Database/database.php');
    $con =  new Database();
    $fetchAttendance = $con->GetAttendance();
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
            width: 350px;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            overflow: hidden; 
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
    </style>
</head>
<body>
    <div class="d-flex vh-100">
    <div class="sidebar bg-success text-white">
        <div class="sidebar-header m-2 b-7 pt-1 text-center">
            <h1 class="title">Hello, Admin</h1>
            <div class="row">
                <div class="col-sm-7 text-center">
                    <span class="owner-name">Vahn Appetite</span>
                </div>
            </div>
        </div>

        <div class="d-grid gap-2 col-10 mx-auto">
            <button onclick="toDashboard()" class="btn custom-btn" type="submit">Frontdesk</button>
            <button onclick="toManageMenu()" class="btn custom-btn" type="button">Manage Menu</button>
            <button onclick="toAttendance()" class="btn custom-btn" type="submit">Attendance Summary</button>
            <button onclick="toProducts()" class="btn custom-btn" type="submit">Products</button>
            <button class="btn custom-btn" type="submit">Sales Report</button>
            <button class="btn custom-btn" type="submit">Payroll records</button>
            <button onclick="toStaffs()" class="btn custom-btn" type="submit">Monitor Staff</button>
        </div>
    </div>
    
<main class="w-100" style="margin-left: 350px; margin-top: 30px;">   
    <div class="container w-58 h-30 mt-3 text-center shadow-lg p-3 mb-5 bg-body-tertiary rounded">
        <h3 class="fw-bold">Attendance Summary</h3>
        <div class="row">
            <div class="col">Check Previous staff performance.</div>
        </div>
    </div>

    <div class="row w-100 justify-content-center">
        <div class="col ps-4 offset-md-1">
            <div class="card shadow-lg p-3 mb-5 bg-body-tertiary rounded">
                <table class="table table-success table-hover text-center">
                    <thead>
                        <tr>
                            <th>Staff</th>
                            <th>Time In</th>
                            <th>Time Log</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($fetchAttendance as $Attendee){ ?>
                        <tr>
                            <td><?php echo $Attendee['Staff'];?></td>
                            <td><?php echo $Attendee['Time_In']; ?></td>
                            <td><?php echo $Attendee['logout']; ?></td>
                            <td><?php echo $Attendee['Date'];?></td>
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
<script src="window.js"></script>
<script>
    function toManageMenu(){
    window.location.href = "ManageMenu.php";
}

function toAttendance(){
    window.location.href ="CheckAttendance.php";
}

function toDashboard(){
    window.location.href = "Dashboard.php";
}
function toProducts(){
    window.location.href = "ViewProducts.php";
}

function toStaffs(){
    window.location.href = "MonitorStaff.php";
}
</script>
</html>
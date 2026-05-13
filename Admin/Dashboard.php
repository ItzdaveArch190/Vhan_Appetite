<?php
    require_once('../Database/database.php');
        $con = new Database();
        $DailySales =  $con->getTodaySales();
        $getOrder = $con->getOrder();
        $get_EMPLOYEE = $con->getAllEmployee();
    ?>
    
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css" />
    <title>Dashboard Panel</title>
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
            <button onclick="toDashboard()" class="btn custom-btn" type="submit">Frontdesk Kiosk</button>
            <button onclick="toManageMenu()" class="btn custom-btn" type="button">Manage Menu</button>
            <button onclick="toAttendance()" class="btn custom-btn" type="submit">Attendance Summary</button>
            <button onclick="toProducts()" class="btn custom-btn" type="submit">Products</button>
            <button class="btn custom-btn" type="submit">My Payroll</button>
            <button class="btn custom-btn" type="submit">Daily Sales Report</button>
            <button onclick="toAttendance()" class="btn custom-btn" type="submit">Attendance Summary</button>
            <button class="btn custom-btn" type="submit">Payroll records</button>
            <button class="btn custom-btn" type="submit">Admin Panel</button>
        </div>
    </div>

    <main class="w-100" style="margin-left: 350px; margin-top: 30px;">
        <div class="heading p-4 text-center">
        <h3 class="fw-bold">Admins Monitoring Dashboard</h3>
            <div class="row">
                <div class="col">
                    <p class="fw-light">Monitor peformance, stock, and team execution.</p>
                </div>
            </div>
        </div>

        <div class="container-fluid px-4">
        <div class="row g-4">

        <div class="col-md-6 col-lg-3">
            <div class="card shadow-sm border-0 p-3 shadow-lg p-3 mb-5 bg-body-tertiary rounded text-center">
                <h6>DAILY SALES</h6>
                <div class="d-flex justify-content-center align-items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" 
                        viewBox="0 0 24 24" 
                        fill="none" 
                        stroke="currentColor" 
                        stroke-width="1.50" 
                        stroke-linecap="round" 
                        stroke-linejoin="round" 
                        class="icon icon-tabler icons-tabler-outline icon-tabler-currency-peso">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M8 19v-14h3.5a4.5 4.5 0 1 1 0 9h-3.5" />
                        <path d="M18 8h-12" /><path d="M18 11h-12" />
                    </svg>
                    <h2 class="fw-bold"><?php echo $DailySales; ?></h2>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card shadow-sm border-0 p-3 shadow-lg p-3 mb-5 bg-body-tertiary rounded text-center">
                <h6>Transactions</h6>
                <h2 class="fw-bold"><?php echo $getOrder;?></h2>
            </div>
        </div>

        

        <div class="col-md-6 col-lg-3">
            <div class="card shadow-sm border-0 p-3 shadow-lg p-3 mb-5 bg-body-tertiary rounded">
                <div class="text-center">
                    <h6>EMPLOYEES</h6>
                </div>    
                    <div class=" d-flex justify-content-center align-items-center gap-5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="50" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-users-group"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M10 13a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M8 21v-1a2 2 0 0 1 2 -2h4a2 2 0 0 1 2 2v1" />
                        <path d="M15 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                        <path d="M17 10h2a2 2 0 0 1 2 2v1" />
                        <path d="M5 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                        <path d="M3 13v-1a2 2 0 0 1 2 -2h2" />
                    </svg>
                    <div class="">
                        <h2 class="fw-bold e-2"><?php echo $get_EMPLOYEE;?></h2>
                    </div>    
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card shadow-sm border-0 p-3 shadow-lg p-3 mb-5 bg-body-tertiary rounded">
                <h6>LOW STOCK</h6>
                <h2 class="fw-bold">0.00</h2>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid px-4 mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0 p-5">
                    <h5 class="fw-bold">Top Products</h5>
                    <p class="fw-light">Monitor Top selling products and the total sold.</p>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Total Sold</th>
                            </tr>
                        </thead>

                        <tbody class="">
                            <tr>
                                <td>B1T1 Premium Cheese Burger</td>
                                <td>10</td>
                            </tr>
                            <tr>
                                <td>Hungarian Sausage</td>
                                <td>7</td>
                            </tr>
                            <tr>
                                <td>B1T1 Double Decker Bacon</td>
                                <td>5</td>
                            </tr>
                        </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</main>

</body>

<script src="window.js"></script>
</html>
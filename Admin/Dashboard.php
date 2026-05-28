<?php
    require_once('auth.php');
    require_once('../Database/database.php');
    require_once('sidebar.php');
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
    
    <script src="https://unpkg.com/feather-icons"></script>
    <title>Dashboard Panel</title>
    <style>
        .sidebar {
            width: 350px;
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

        /* From Uiverse.io by vinodjangid07 */ 
    .Btn {
    display: flex;
    align-items: center;
    justify-content: flex-start;
    width: 45px;
    height: 45px;
    border: none;
    border-radius: 50%;
    cursor: pointer;
    position: relative;
    overflow: hidden;
    transition-duration: .3s;
    box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.199);
    background-color: rgb(255, 65, 65);
    }

/* plus sign */
    .sign {
    width: 100%;
    transition-duration: .3s;
    display: flex;
    align-items: center;
    justify-content: center;
    }

    .sign svg {
    width: 17px;
    }

    .sign svg path {
    fill: white;
    }
/* text */
    .text {
    position: absolute;
    right: 0%;
    width: 0%;
    opacity: 0;
    color: white;
    font-size: 1.2em;
    font-weight: 600;
    transition-duration: .3s;
    }
/* hover effect on button width */
    .Btn:hover {
    width: 125px;
    border-radius: 40px;
    transition-duration: .3s;
    }

    .Btn:hover .sign {
    width: 30%;
    transition-duration: .3s;
    padding-left: 20px;
    }
/* hover effect button's text */
    .Btn:hover .text {
    opacity: 1;
    width: 70%;
    transition-duration: .3s;
    padding-right: 10px;
    }
/* button click effect*/
    .Btn:active {
    transform: translate(2px ,2px);
    }
    </style>
</head>
<body>

    
<div class="d-flex vh-100">
    <?php renderAdminSidebar(); ?>

        <!-- From Uiverse.io by vinodjangid07 --> 
<button class="Btn">
    <div class="sign"><svg viewBox="0 0 512 512"><path d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L377.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-128 0c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM160 96L96 96c-17.7 0-32 14.3-32 32l0 256c0 17.7 14.3 32 32 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-53 0-96-43-96-96L0 128C0 75 43 32 96 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32z"></path></svg></div>
    <div class="text">Logout</div>
</button>



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

        
    </div>
</div>

<div class="container-fluid px-4 mt-4">
    <div class="row d-flex justify-content-center">
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
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>

<script src="../functions/window.js"></script>
</html>
<?php
require_once('auth.php');
require_once('../Database/database.php');
require_once('sidebar.php');

$con = new Database();
$employeeID = $_SESSION['user_id'];

$withinDayOrder = $con->getTotalOrderWithinDay($employeeID);
$todaySales     = $con->todayTotalSales($employeeID);

$cashOnHand  = $con->getCashToday($employeeID, 1);
$Gcash       = $con->getGCashToday($employeeID, 2);
$PayMaya     = $con->getMayaToday($employeeID, 3);
$BankTransfer= $con->getBankTransferToday($employeeID, 4);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="style.css">
<title>Dashboard</title>

<style>
    body{
    margin:0;
}

main{
    margin-left: 250px;
    width: calc(100% - 250px);
    padding: 20px;
}

@media (max-width: 768px) {
    main {
        margin-left: 0;
        width: 100%;
    }
}

.main-wrapper{
    max-width: 1200px;
    margin: auto;
}

.card{
    border-radius: 15px;
}

.stat-value{
    font-size: 1.8rem;
    font-weight: 700;
}

.shrtcut__btns{
    padding-top: 30px;

}

.quick-btn{
    height:100px;
    width:100%;
    max-width: 270px;
    color:black;
    justify-content:center;
    align-items:center;
    text-decoration:center;
    font-weight:500;
    background-color:white;
    border-radius:20px;
    border:1px solid #a6a69f;
    box-shadow: 0 6px 15px rgba(0,0,0,0.1);
    position:relative;
    
    transition: transform 0.2s ease-in;
}

.quick-btn:hover{
    transform: translateY(-5%);
}

.quick-btn > .icon{
    position: absolute;
    top: 5%;
    left: 7%;
    font-size: 40px;
    color: green;
}



</style>
</head>

<body>

<?php renderStaffSidebar(); ?>

<main>
<div class="main-wrapper">

    <!-- HEADER -->
    <div class="text-center mt-3 card shadow-sm p-3 mb-4 bg-body-tertiary rounded">
        <h1 class="fw-bold m-0">
            Magandang Araw, <?php echo staffUsername() . "!"; ?>
        </h1>
        <p class="fw-light">Monitor your performance</p>
    </div>

    <!-- QUICK ACTIONS -->
    <div class="row g-4">
        <div class="col card mb-4 d-flex gap-2 flex-column justify-content-center shadow-sm p-3 mb-4 bg-body-tertiary rounded">
            
                <div class="action__head">
                    <h4 class="fw-bold">Quick Actions</h4>
                    <p class="fw-light">Quick redirect to designated page.</p>
                </div>
                <div class="shrtcut__btns d-flex justify-content-center gap-3 flex-wrap">
                    <button onclick="Menu()" class="quick-btn">
                        <span class="icon"><i class="bi bi-menu-app-fill"></i></span>
                        Menu
                    </button>

                    <button onclick="Gocheckout()" class="quick-btn">
                        <span class="icon"><i class="bi bi-cart-check"></i></span>
                        Checkout
                    </button>
                    <button onclick="completedOrders()" class="quick-btn">
                        <span class="icon"><i class="bi bi-border-width"></i></span>
                        Completed Orders
                    </button>
                    <button onclick="attendance()" class="quick-btn">
                        <span class="icon"><i class="bi bi-fingerprint"></i></span>
                        Attendance</button>
            </div>
        </div>
    </div>

    <!-- PAYMENT CARDS -->
    <div class="row g-4 mb-4">

        <div class="col-md-3 text-center">
            <div class="card p-3 shadow">
                <h6>Cash</h6>
                <div class="stat-value">₱<?php echo number_format($cashOnHand,2); ?></div>
            </div>
        </div>

        <div class="col-md-3 text-center">
            <div class="card p-3 shadow">
                <h6>GCash</h6>
                <div class="stat-value">₱<?php echo number_format($Gcash,2); ?></div>
            </div>
        </div>

        <div class="col-md-3 text-center">
            <div class="card p-3 shadow">
                <h6>Maya</h6>
                <div class="stat-value">₱<?php echo number_format($PayMaya,2); ?></div>
            </div>
        </div>

        <div class="col-md-3 text-center">
            <div class="card p-3 shadow">
                <h6>Bank Transfer</h6>
                <div class="stat-value">₱<?php echo number_format($BankTransfer,2); ?></div>
            </div>
        </div>

    </div>

    <!-- STATS -->
    <div class="row g-4">

        <div class="col-md-6 text-center">
            <div class="card p-4 shadow">
                <h5>Total Orders (Today)</h5>
                <div class="stat-value"><?php echo $withinDayOrder; ?></div>
            </div>
        </div>

        <div class="col-md-6 text-center">
            <div class="card p-4 shadow">
                <h5>Sales (Today)</h5>
                <div class="stat-value">₱<?php echo number_format($todaySales,2); ?></div>
            </div>
        </div>

    </div>

</div>
</main>
    <script src="../functions/window.js"></script>
</body>
</html>

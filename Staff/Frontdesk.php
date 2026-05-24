<?php 
    session_start();
    require_once('../Database/database.php');
    $con = new Database();

    $employeeID = $_SESSION['user_id'];

    $gettotalOrder = $con->getTotalOrders($employeeID);


    if(!isset($_SESSION['user_id'])){
        header("Location: Login.php");
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
    <title>Burger Section</title>
    <link rel="stylesheet" href="Design/style.css">
</head>
<body>


    <div class="sidebar bg-success text-white">
        <div class="sidebar-header m-2 b-7 pt-1 text-center">

            <div class="d-flex flex-column justify-content-center gap-3 px-3 pt-5">
                <div class=" profile-container w-auto">
                    <img id="round-profile" name="profile" src="../images/Burger (2).png" alt="">
                </div>
                <h3 class="title"><?php echo $_SESSION['username']; ?></h3>
            </div>

            <div class="business-name-divider">
                <div class="col-sm-7 text-center">
                    <span class="owner-name">Vahn Appetite</span>
                </div>
            </div>
            <div class="display-total-order">
                <h4> Total Order: <?php echo 237;?></h4>
            </div>
        </div>

        <div class="d-grid gap-2 col-10 mx-auto">
            <button onclick="frontDesk()" class="btn custom-btn" type="submit">Frontdesk</button>
            <button onclick="Menu()" class="btn custom-btn" type="button">Menu</button>
            <button onclick="Gocheckout()" class="btn custom-btn" type="button">Checkout</button>
            <button onclick="completedOrders()" class="btn custom-btn" type="button">Completed Orders</button>
            <button onclick="attendance()" class="btn custom-btn" type="submit">Attendance</button>
        </div>

        <div class="text-start ms-3 text-white mb-5">
            <button type="button" class="btn logout-btn btn-warning"><ion-icon name="log-out-outline"></ion-icon></button>
        </div>
    </div>
</div>  

    <main class="" >
        <div class="heading text-center shadow-lg p-3 mb-5 bg-body-tertiary rounded card p-3">
            <h1 class="fw-bold">Magandang Araw!, <?php echo " " . $_SESSION['username'];?></h1>
                <div class="row">
                    <div class="col">
                    <p class="fw-light">Monitor peformance, stock, and team execution.</p>
                </div>
            </div>
        </div>

        <div class="row w-25 p-3 card shadow-lg p-3 mb-5 bg-body-tertiary rounded">
            <div class="col text-center">
                <div><h4 class="fw-bold">Total Order</h4></div>
            </div>

            <div class="col text-center">
                <h3><?php echo $gettotalOrder; ?></h3>
            </div>
        </div>
    </main>

</body>
<script src="../functions/window.js"></script>
</html>
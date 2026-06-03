<?php 
    require_once('auth.php');
    require_once('../Database/database.php');
    require_once('sidebar.php');
    $con = new Database();


    $employeeID = $_SESSION['user_id'];

    $gettotalOrder = $con->getTotalOrders($employeeID);
    $getSales = $con->fetchStaff_Income($employeeID);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <title>Burger Section</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    
<div class="d-flex">
    <?php renderStaffSidebar(); ?>

    <main>
        <div class="main-wrapper">

            <div class="heading text-center shadow-sm p-3 mb-4 bg-body-tertiary rounded">
                <h1 class="fw-bold">
                    Magandang Araw!, <?php echo staffUsername(); ?>
                </h1>
                <p class="fw-light">Monitor your personal performance.</p>
            </div>

            <div class="row g-4">

                <div class="col-md-4 text-center">
                    <div class="card shadow-sm p-3">
                        <h5>Total Order</h5>
                        <h3><?php echo $gettotalOrder; ?></h3>
                    </div>
                </div>

                <div class="col-md-4 text-center">
                    <div class="card shadow-sm p-3">
                        <h5>Sales</h5>
                        <h3><?php echo '₱' . number_format($getSales ?? 0, 2); ?></h3>
                    </div>
                </div>

                <div class="col-md-4 text-center">
                    <div class="card shadow-sm p-3">
                        <h5>Status</h5>
                        <h3>Active</h3>
                    </div>
                </div>

            </div>

        </div>
    </main>
</div>


</body>
<script src="../functions/window.js"></script>
</html>
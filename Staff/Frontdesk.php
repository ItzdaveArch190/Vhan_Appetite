<?php 
    require_once('auth.php');
    require_once('../Database/database.php');
    require_once('sidebar.php');
    $con = new Database();


    $employeeID = $_SESSION['user_id'];

    $gettotalOrder = $con->getTotalOrders($employeeID);

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
<div class="d-flex vh-100">
    <?php renderStaffSidebar(); ?>

    <main class="" >
        <div class="heading text-center shadow-lg p-3 mb-5 bg-body-tertiary rounded card p-3">
            <h1 class="fw-bold">Magandang Araw!, <?php echo " " . staffUsername();?></h1>
                <div class="row">
                    <div class="col">
                    <p class="fw-light">Monitor your personal peformance.</p>
                </div>
            </div>
        </div>

            <div class="row d-flex g-4 p-3 ">
                <div class="col text-center">
                    <div class="card shadow-lg p-3 mb-5 bg-body-tertiary rounded">
                        <h4 class="fw-bold">Total Order</h4>
                        <h3><?php echo $gettotalOrder; ?></h3>
                    </div>
                </div>

                <div class="col-4 text-center">
                    <div class="card shadow-lg p-3 mb-5 bg-body-tertiary rounded">
                        <h4 class="fw-bold"><?php echo "Sales"?></h4>
                        <h3><?php  ?></h3>
                    </div>
                </div>

                <div class="col-4 text-center">
                    <div class="card shadow-lg p-3 mb-5 bg-body-tertiary rounded">

                    </div>
                </div>
            </div>

            
        
    </main>

</div>

</body>
<script src="../functions/window.js"></script>
</html>
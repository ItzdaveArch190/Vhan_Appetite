<?php
    require_once('auth.php');
    require_once('../Database/database.php');
    require_once('sidebar.php');
    $con = new Database();


    $employeeID = $_SESSION['user_id'];
    $completedOrders = $con->getCompletedOrdersByEmployee($employeeID);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css">
    <title>Burger Section</title>
    <style>
        
    </style>
</head>
<body>
  

    <div class="d-flex v-100 justify-content-center">
        
    <?php 
        renderStaffSidebar();
    ?>
    

    <main class="" >
        <!--
        <?php if(isset($_SESSION['success_message'])){ ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['success_message']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php unset($_SESSION['success_message']); } ?>
        <-->

        <div class="row">
            <div class="col">
                <div class="card text-center p-3 shadow-lg p-3 mb-5 bg-body-tertiary rounded">
                    <h3 class="fw-bold">Completed Orders</h3>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <div class="card shadow-lg p-3 mb-5 bg-body-tertiary rounded">
                    <table class="table table-success table-hover text-center align-middle">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Items</th>
                                <th>Quantity</th>
                                <th>Payment</th>
                                <th>Total</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($completedOrders)){ ?>
                                <tr>
                                    <td colspan="6">No completed orders yet.</td>
                                </tr>
                            <?php } ?>

                            <?php foreach($completedOrders as $order){ ?>
                                <?php $orderItems = $con->getCompletedOrderItems($order['Order_ID']); ?>
                                <tr>
                                    <td><?php echo $order['Order_ID']; ?></td>
                                    <td class="text-start">
                                        <?php foreach($orderItems as $item){ ?>
                                            <div><?php echo $item['Product_Name']; ?> x<?php echo $item['Qty']; ?></div>
                                        <?php } ?>
                                    </td>
                                    <td><?php echo $order['Order_Quantity']; ?></td>
                                    <td><?php echo !empty($order['Payment_Method']) ? $order['Payment_Method'] : 'Cash'; ?></td>
                                    <td><?php echo $order['Total_amount']; ?></td>
                                    <td><?php echo $order['Completed_Date']; ?></td>
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
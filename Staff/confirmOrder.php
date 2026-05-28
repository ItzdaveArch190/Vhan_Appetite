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
    <title>Burger Section</title>
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
        .sidebar-header{
            padding-left: 10px;
            padding-right:10px;
        }
        main{

        margin-left: 350px;
        padding: 20px;
        min-height: 100vh;
        }

        body{
            margin:0;
        }
    </style>
</head>
<body>
  

    <div class="sidebar bg-success text-white">
        <div class="sidebar-header m-2 b-7 pt-1 text-center">

            <div class="d-flex flex-column justify-content-center gap-3 px-3 pt-5">
                <div class=" profile-container w-auto">
                    <img id="round-profile" name="profile" src="../images/Burger (2).png" alt="">
                </div>
                <h3 class="title"><?php echo $_SESSION['username'] ?? ''; ?></h3>
                    <h3 class="title"><?php echo staffUsername(); ?></h3>
            </div>

            <div class="business-name-divider">
                <div class="col-sm-7 text-center">
                    <span class="owner-name">Vahn Appetite</span>
                </div>
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

        <?php if(isset($_SESSION['success_message'])){ ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['success_message']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php unset($_SESSION['success_message']); } ?>

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
                    <table class="table table-hover text-center align-middle">
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
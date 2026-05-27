<?php 
    session_start();


    
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0&icon_names=waving_hand" />
    <link rel="stylesheet" href="CheckoutPage/style.css">
    <title>Burger Section</title>
    <style>
    </style>
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

    <div class="row w-100 p-3">
        
        <div class="heading card text-center p-4 shadow-lg p-3 mb-5 bg-body-tertiary rounded">
            <div class="txt">
                <h2 class="">Checkout Stage</h2>
                <p class="fw-light">Please Clarify your customers order.</p>
            </div>    
        </div>

        <div class="col ps-4">
            <div class="card shadow-lg p-3 mb-5 bg-body-tertiary rounded">
                <table class="table table-success table-hover text-center">
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Quantity</th>
                            <th>total</th>
                        </tr>
                    </thead>
                    <tbody>
                <?php

                $total = 0;
                if(!empty($_SESSION['cart'])){

                    foreach($_SESSION['cart'] as $item){
                        $totalperItem = $item['price'] * $item['quantity'];
                        $total += $totalperItem;
                        ?>
                <tr>
                    <td><?php echo $item['name']?></td>
                    <td><?php echo $item['quantity']?></td>
                    <td><?php echo $totalperItem;?></td>
                </tr>
                
                    <?php

                        }
                    }
                ?>
                <tr>
                    <td><strong>Subtotal : </strong></td>
                    <td></td>
                    <td><strong><?php echo $total;?></strong></td>
                </tr>
                    </tbody>
                </table>
                
                    <button type="submit" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#confirm-popUp">
                        Confirm Order
                    </button>
                    
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="confirm-popUp" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">
                        <span class="material-symbols-outlined">
                        waving_hand
                        </span>Are you sure to confirm order?</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Confirm Order</button>
                </div>
                </div>
            </div>
        </div>
</div>

    </main>

</body>
<script src="../functions/window.js"></script>
</html>
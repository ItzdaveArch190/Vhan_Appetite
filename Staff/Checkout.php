<?php 
    require_once('auth.php');
    require_once('../Database/database.php');
    require_once('sidebar.php');
    $con = new Database();


    if(isset($_POST['confirmOrder'])){
        try{
            $con->placeOrder($_SESSION['user_id'], $_SESSION['cart'] ?? [], 1);
            $_SESSION['cart'] = [];
            $_SESSION['success_message'] = 'Order has been confirmed successfully.';
            header("Location: confirmOrder.php");
            exit();
        } catch(Exception $e){
            $_SESSION['error_message'] = $e->getMessage();
            header("Location: Checkout.php");
            exit();
        }
    }   
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0&icon_names=waving_hand" />
    <link rel="stylesheet" href="Design/checkout.css">
    <title>Burger Section</title>
    <style>
    </style>
</head>
<body>
<div class="d-flex vh-100">
    <?php renderStaffSidebar(); ?>

    <main class="" >

    <?php if(isset($_SESSION['error_message'])){ ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['error_message']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php unset($_SESSION['error_message']); } ?>

    <div class="row d-flex flex-column w-100 ">
        <div class="col">
            <div class="heading card text-center p-4 shadow-lg p-3 mb-5 bg-body-tertiary rounded">
                <div class="txt">
                    <h2 class="">Checkout Stage</h2>
                    <p class="fw-light">Please Clarify your customers order.</p>
                </div>    
            </div>
        </div>
    
        <div class="col">
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
                
                    <button type="submit" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#confirm-popUp" <?php if(empty($_SESSION['cart'])) echo 'disabled'; ?>>
                        Confirm Order
                    </button>
                    
            </div>
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
                    This order will be submitted as paid (Cash). Continue?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <form method="POST">
                        <button type="submit" name="confirmOrder" class="btn btn-primary">Confirm Order</button>
                    </form>
                </div>
                </div>
            </div>
        </div>


    </main>

</div>

</body>
<script src="../functions/window.js"></script>
</html>
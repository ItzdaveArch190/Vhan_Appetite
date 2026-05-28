<?php
    require_once('auth.php');
    require_once('sidebar.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <title>Products</title>
    <style>
        .sidebar{
            width: 350px;
            position:fixed;
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
        main{
            margin-left: 360px;
            margin-top:40px;
            height: 100vh;
            overflow-y:auto;
        }
    </style>
</head>
<body>
    <div class="d-flex vh-100">
    <?php renderAdminSidebar(); ?>

<main>
    <div class="row g-3">
        <?php ?>
        <div class="col-4">
            <div class="card">
                <div class="">
                    <img src="../images/Burger (2).png"  style=" width: 200px; height: 200px" class="card-img-top" alt="">
                </div>    
                <div class="card-body">
                    <h6 class="card-title"><?php //Product Name?></h6>
                    <p class="card-text"><?php //price ?></p>
                    <button class="btn btn-primary" disabled></button>
                </div>
            </div>
        </div>

    </div> 
</main>
    </div>
</body>
<script src="../functions/window.js"></script>
</html>
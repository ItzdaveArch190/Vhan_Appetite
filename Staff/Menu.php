<?php 
    require_once('auth.php');
    require_once("../Database/database.php");
    require_once('sidebar.php');
    $con = new Database();


    $Burger_PriceLists = $con->getBurger_PriceList();
    $Hotdog_PriceLists = $con->getHotdog_PriceList();
    $Corndog_PriceLists = $con->getCorndog_Pricelist();
    $Beverage_Pricelists = $con->getBeverage_Pricelist();
    $totalProduct = $con->totalProduct();
    if(isset($_POST['add'])){

    $id = $_POST['product_id'];
    $name = $_POST['product_name'];
    $price = $_POST['product_price'];
    

    if(!isset($_SESSION['cart'])){
        $_SESSION['cart'] = [];
    }

    $found = false;

    foreach($_SESSION['cart'] as &$item){

        

        if($item['id'] == $id){
            $item['quantity']++; 
            $found = true;
            break;
        }
    }

    
    if(!$found){
        
        $_SESSION['cart'][] = [
            "id" => $id,
            "name" => $name,
            "price" => $price,
            "quantity" => 1
        ];
    }

    unset($item);

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

if(isset($_POST['remove'])){

    $id = $_POST['product_id'];

    foreach($_SESSION['cart'] as $key => &$item){
        if($item['id'] == $id){

            $item['quantity']--;

            // pag zero tanggalin
            if($item['quantity'] <= 0){
                unset($_SESSION['cart'][$key]);
            }

            break;
        }
    }

    unset($item);
    header("Location: " . $_SERVER['PHP_SELF']);
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="Design/menu.css">

    <title>Menu</title>
    
</head>
<body>
<div class="d-flex vh-100">
    <?php renderStaffSidebar(); ?>

<div class="nav-con">
    <nav>
        <ul>
            <li><a href="#burger-header"><i class="fa-solid fa-burger"></i></a></li>
            <li><a href="#hotdog-header"><i class="fa-solid fa-hotdog"></i></a></li>
            <li><a href="#corndog-header"><i class="fa-solid fa-drumstick-bite"></i></a></li>
            <li><a href="#beverage-header"><i class="fa-solid fa-glass-water"></i></a></li>
        </ul>
    </nav>
</div>



<main class="mt-4" >   
    <div class="fluid-container text-center burger-section" id="burger-header"> 
    <div class="row g-3">
<!--Dito ang populate ng data-->
<!--Kasama si column dyan pag nag populate-->

    <div class="burger-header card shadow-lg p-3 mb-5 rounded">
                    <div>
                        <h2 class="section-heading ">Burger Section</h2>
                        <?php foreach($totalProduct as $total){?>
                            <p class=" m-0">Total Products</p>
                            <p class=" m-0"><?php echo $total['totalproduct'];?></p>
                        <?php }?>
                    </div>
                    
        </div>


        <?php foreach($Burger_PriceLists as $burger) {?>
        <div class="col-md-3 col-lg-3 ">
            <div class="card h-200  text-center shadow-lg p-3 mb-5 bg-body-tertiary rounded" style="object-fit: contain;">
                <div class="pop p-2">
                    <button type="button" class="btn btn-primary" disabled>
                        <span class="badge text-bg-primary"><?php echo $burger['Status'];?></span>
                    </button>
                </div>
            <img src="../images/Burger (2).png" style="height: 150px; object-fit: contain;" class="card-img-top" alt="...">
                <h6 class="card-title mt-1"><?php echo $burger['Product_Name']; ?></h6>

                <div class="price">
                    <button class="btn btn-primary" disabled><?php echo $burger['Product_Price'];?></button>
                </div>

                <div class="d-flex justify-content-evenly g-2 p-2">
                    <form method="POST">
                        <input type="hidden" name="product_id" value="<?php echo $burger['Product_ID'];?>">
                        <input type="hidden" name="product_name" value="<?php echo $burger['Product_Name'];?>">
                        <input type="hidden" name="product_price" value="<?php echo $burger['Product_Price'];?>">

                        

                        <button type="submit" name="add" class="btn custombtn" <?php if($burger['Status'] != 'Available') echo 'disabled'; ?>>
                            <i class="fa-solid fa-plus"></i>
                        </button>


                        <button type="submit" name="remove" class="btn custombtn">
                            <i class="fa-solid fa-minus"></i>
                        </button>

                        <div class="container p-1 text-center">
                            <p class="h6 m-0">Stock</p>
                            <button class="btn btn-outline-primary" disabled><?php echo $burger['Stock'];?></button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    <!--Tapos eto yung baba dapat andito yung php nag mag aanesss hahahahahah-->
        <?php } ?>

    </div>
</div>

<div class="Hotdog text-center mt-4" id="hotdog-header">

    <div class="row g-3">


        <div class="hotdog-header card shadow-lg p-3 mb-5 rounded">
                    <div>
                        <h2 class="section-heading ">Hotdog Section</h2>
                        <?php foreach($totalProduct as $total){?>
                            <p class=" m-0">Total Products</p>
                            <p class=" m-0"><?php echo $total['totalproduct'];?></p>
                        <?php }?>
                    </div>
                    
        </div>
    
    <?php foreach($Hotdog_PriceLists as $hotdog) {?>
        <div class="col-md-3 col-lg-3 ">
            <div class="card h-200  text-center shadow-lg p-3 mb-5 bg-body-tertiary rounded" style="object-fit: contain;">
                <div class="pop p-2">
                    <button type="button" class="btn btn-primary" disabled>
                        <span class="badge text-bg-primary"><?php echo $hotdog['Status'];?></span>
                    </button>
                </div>
            <img src="../images/Burger (2).png" style="height: 150px; object-fit: contain;" class="card-img-top" alt="...">
                <h6 class="card-title mt-1"><?php echo $hotdog['Product_Name']; ?></h6>

                <div class="price">
                    <button class="btn btn-primary" disabled><?php echo $hotdog['Product_Price'];?></button>
                </div>

                <div class="d-flex justify-content-evenly g-2 p-2">
                    
                    <form method="POST">
                        <input type="hidden" name="product_id" value="<?php echo $hotdog['Product_ID'];?>">
                        <input type="hidden" name="product_name" value="<?php echo $hotdog['Product_Name'];?>">
                        <input type="hidden" name="product_price" value="<?php echo $hotdog['Product_Price'];?>">

                        <button type="submit" name="add" class="btn custombtn" <?php if($hotdog['Status'] != 'Available') echo 'disabled'; ?>><i class="fa-solid fa-plus"></i></button>
                        
                        <button type="submit" name="remove" class="btn custombtn"><i class="fa-solid fa-minus"></i></button>

                        <div class="container p-1 text-center">
                            <p class="h6 m-0">Stock</p>
                            <button class="btn btn-outline-primary" disabled><?php echo $hotdog['Stock'];?></button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    <!--Tapos eto yung baba dapat andito yung php nag mag aanesss hahahahahah-->
        <?php } ?>

    </div>
</div>

<div class="Corndog text-center mt-4" id="corndog-header">
    <div class="row g-3">


        <div class="corndog-header card shadow-lg p-3 mb-5 rounded">
            <h2 class="section-heading ">Corndog Section</h2>
                <?php foreach($totalProduct as $total){?>
                    <p class=" m-0">Total Products</p>
                    <p class=" m-0"><?php echo $total['totalproduct'];?></p>
                <?php }?>
        </div>


<!--tuitle-->
        <?php foreach($Corndog_PriceLists as $corndog) {?>
        <div class="col-md-3 col-lg-3 ">
            <div class="card h-200  text-center shadow-lg p-3 mb-5 bg-body-tertiary rounded" style="object-fit: contain;">
                <div class="pop p-2">
                    <button type="button" class="btn btn-primary" disabled>
                        <span class="badge text-bg-primary"><?php echo $corndog['Status'];?></span>
                    </button>
                </div>
            <img src="../images/Burger (2).png" style="height: 150px; object-fit: contain;" class="card-img-top" alt="...">
                <h6 class="card-title mt-1"><?php echo $corndog['Product_Name']; ?></h6>

                <div class="price">
                    <button class="btn btn-primary" disabled><?php echo $corndog['Product_Price'];?></button>
                </div>

                <div class="d-flex justify-content-evenly g-2 p-2">

                    <form method="POST">
                        <input type="hidden" name="product_id" value="<?php echo $corndog['Product_ID'];?>">
                        <input type="hidden" name="product_name" value="<?php echo $corndog['Product_Name'];?>">
                        <input type="hidden" name="product_price" value="<?php echo $corndog['Product_Price'];?>">
                        
                        <button type="submit" name="add" class="btn custombtn" <?php if($corndog['Status'] != 'Available') echo'disabled'?>><i class="fa-solid fa-plus"></i></button>
                        <button type="submit" name="remove" class="btn custombtn"><i class="fa-solid fa-minus"></i></button>

                        <div class="container p-1 text-center">
                            <p class="h6 m-0">Stock</p>
                            <button class="btn btn-outline-primary" disabled><?php echo $hotdog['Stock'];?></button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    <!--Tapos eto yung baba dapat andito yung php nag mag aanesss hahahahahah-->
        <?php } ?>
</div>

<div class="Beverages text-center mt-4" id="beverage-header">
    <div class="row g-3">

        <div class="beverage-header card shadow-lg p-3 mb-5 rounded">
            <h2 class="section-heading ">Beverage Section</h2>
                <?php foreach($totalProduct as $total){?>
                    <p class=" m-0">Total Products</p>
                    <p class=" m-0"><?php echo $total['totalproduct'];?></p>
                <?php }?>
        </div>

<!--tuitle-->
        <?php foreach($Beverage_Pricelists as $Beverage) {?>
        <div class="col-md-3 col-lg-3 ">
            <div class="card h-200  text-center shadow-lg p-3 mb-5 bg-body-tertiary rounded" style="object-fit: contain;">
                <div class="pop p-2">
                    <button type="button" class="btn btn-primary" disabled>
                        <span class="badge text-bg-primary"><?php echo $Beverage['Status'];?></span>
                    </button>
                </div>
            <img src="../images/Burger (2).png" style="height: 150px; object-fit: contain;" class="card-img-top" alt="...">
                <h6 class="card-title mt-1"><?php echo $Beverage['Product_Name']; ?></h6>

                <div class="price">
                    <button class="btn btn-primary" disabled><?php echo $Beverage['Product_Price'];?></button>
                </div>

                <div class="d-flex justify-content-evenly g-2 p-2">
                    <form method="POST">
                        <input type="hidden" name="product_id" value="<?php echo $Beverage['Product_ID'];?>">
                        <input type="hidden" name="product_name" value="<?php echo $Beverage['Product_Name'];?>">
                        <input type="hidden" name="product_price" value="<?php echo $Beverage['Product_Price'];?>">

                        <button type="submit" name="add" class="btn custombtn" <?php if($Beverage['Status'] != 'Available') echo'disabled'?>><i class="fa-solid fa-plus"></i></button>
                        <button type="submit" name="remove" class="btn custombtn"><i class="fa-solid fa-minus"></i></button>

                        <div class="container p-1 text-center">
                            <p class="h6 m-0">Stock</p>
                            <button class="btn btn-outline-primary" disabled><?php echo $Beverage['Stock'];?></button>
                        </div>

                    </form>    
                </div>
            </div>
        </div>
    <!--Tapos eto yung baba dapat andito yung php nag mag aanesss hahahahahah-->
        <?php } ?>
    </div>

</div>
</main>

</div>
</body>
<script src="../functions/window.js"></script>
</html>
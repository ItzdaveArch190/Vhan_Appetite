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
    

    <title>Burger Section</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Rubik+Dirt&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Rubik+Dirt&family=Stack+Sans+Headline:wght@200..700&display=swap');
        html{
            scroll-behavior: smooth;
        }
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
            z-index:999;
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

        .logout-btn{
            height: 50px;
            width: 100px;
        }
        #round-profile{
            
            height: 100px;
            width: 100px;
            border-radius:50px;
            border: none;
            margin:0;
            
        }
        .business-name-divider{
            width:auto;
            display:flex;
            justify-content:center;
            align-items:center;
            flex-direction:row;
            margin:0;
        
        }
        .title{
            margin:0;
        }
        .sidebar-header{
            padding-left: 10px;
            padding-right:10px;
        }
        main{
            margin-left: 250px;
            padding: 20px;
            padding-top: 120px;
            min-height: 100vh;
        }

        body{
            margin:0;
        }

        .nav-con{
            position: fixed;
            top: 30px;
            left: 250px;   
            width: calc(100% - 250px); 
            display: flex;
            justify-content: center;
            z-index:999;
        }

        nav{
            width: 100%;
            max-width: 800px;
        }

        nav ul{
            background: linear-gradient(to right, #D16617, #d17a3caa, #d17a3c9c , #c97f4d99);
            backdrop-filter: blur(5px);
            display:flex;
            justify-content:center;
            align-items:center;
            gap: 10%;
            list-style-type: none;
            min-height: 5em;
            width: 100%;
            max-width: 800px;
            border-radius: 10em;
            border: 1px solid transprent;
            box-shadow: 0px 1px 8px 1px rgba(0,0,0,0.5);
            -webkit-box-shadow: 0px 1px 8px 1px rgba(0,0,0,0.5);
            -moz-box-shadow: 0px 1px 8px 1px rgba(0,0,0,0.5);
            
        }
        a{
            text-decoration:none;
            color: white;
            
        }
        .fa-burger{
            font-size: 36px;
            color: white;
            transition: transform 0.1s ease-in-out;
        }
        nav .fa-burger:hover{
            transform: scale(1.5);
        }

        .fa-hotdog{
            font-size: 36px;
            color: white;
            transition: transform 0.1s ease-in-out;
        }

        nav .fa-hotdog:hover{
            transform: scale(1.5);
        }

        .fa-drumstick-bite{
            font-size: 36px;
            color: white;
            transition: transform 0.1s ease-in-out;
        }

        nav .fa-drumstick-bite:hover{
            transform: scale(1.5);
        }

        nav .fa-glass-water{
            font-size: 36px;
            color: white;
            transition: transform 0.1s ease-in-out;
        }

        nav .fa-glass-water:hover{
            transform: scale(1.5);
        }

        
        .custombtn,
        .custombtn:hover,
        .custombtn:focus,
        .custombtn:active {
            background-color: #E57C12;
            color: white;
            border: none;
        }
        
        .custombtn:active {
            background-color: #bf6710 !important;
            opacity: 1 !important;
            box-shadow: none !important;
            color: white !important;
        }

        .burger-header,
        .corndog-header,
        .hotdog-header,
        .beverage-header{
            min-height: 120px;
            
            background-size:cover;
            background-position:top center; 
            background-repeat: no-repeat;
            color:white;
            font-family: "Rubik Dirt", system-ui;
            font-weight: 900;
            font-style: oblique;
            
        }

        

        .burger-header{
            background-image: url("../images/background-burger.png");
        }
        .corndog-header{
            background-image:url("../images/corndog-retro.png");
        }
        .hotdog-header{
            background-image: url("../images/hotdog-retro.png");
        }
        .beverage-header{
            background-image: url("../images/beverage-retro.png");
        }

        .burger-header p,
        .hotdog-header p,
        .corndog-header p,
        .beverage-header p {
            font-family: "Stack Sans Headline", sans-serif;
            font-optical-sizing: auto;
            font-weight: 700;
            font-style: normal;
        }


        .icons i:hover {
            transform: none;
        }


    </style>
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
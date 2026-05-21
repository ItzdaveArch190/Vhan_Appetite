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
        .fa-burger:hover{
            transform: scale(1.5);
        }

        .fa-hotdog{
            font-size: 36px;
            color: white;
            transition: transform 0.1s ease-in-out;
        }

        .fa-hotdog:hover{
            transform: scale(1.5);
        }

        .fa-drumstick-bite{
            font-size: 36px;
            color: white;
            transition: transform 0.1s ease-in-out;
        }

        .fa-drumstick-bite:hover{
            transform: scale(1.5);
        }

        .fa-glass-water{
            font-size: 36px;
            color: white;
            transition: transform 0.1s ease-in-out;
        }

        .fa-glass-water:hover{
            transform: scale(1.5);
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
                <h3 class="title">Magandang Araw, <?php echo "Dave Besorio"; ?></h3>
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
    <div class="fluid-container burger-section" id="burger-header"> 
    <div class="row g-3">
<!--Dito ang populate ng data-->
<!--Kasama si column dyan pag nag populate-->
    <div class="Burger-header text-center" >
        <h5 style="font-family: 'Bebas Neue', sans-serif;">Burger Section</h5>
    </div>
        <?php ?>
        <div class="col-md-3 col-lg-2 ">
            <div class="card h-200  text-center shadow-lg p-3 mb-5 bg-body-tertiary rounded" style="object-fit: contain;">
            <img src="../images/Burger (2).png" style="height: 150px;" class="card-img-top" alt="...">
                <h4 class="card-title mt-1">Bacon Burger</h4>

                <div class="price">
                    <button class="btn btn-primary" disabled><?php echo "239.00";?></button>
                </div>

                <div class="d-flex justify-content-evenly g-2 p-2">
                    <button class="btn btn-primary"><i class="fa-solid fa-plus"></i></button>
                    <button class="btn btn-primary"><i class="fa-solid fa-minus"></i></button>
                </div>
            </div>
        </div>
    <!--Tapos eto yung baba dapat andito yung php nag mag aanesss hahahahahah-->
        <?php ?>
</div>

<div class="Hotdog text-center mt-4" id="hotdog-header">

    <div class="row g-3">
        <div class="hotdog-header">
            <h4 style="font-family: 'Bebas Neue', sans-serif;">Hotdog Section</h4>
        </div>
    
    <?php ?>
        <div class="col-md-3 col-lg-2">
            <div class="card h-200 w-350 text-center shadow-lg p-3 mb-5 bg-body-tertiary rounded" style="object-fit: contain;">
            <img src="../images/Burger (2).png" style="height: 150px;" class="card-img-top" alt="...">
                <h5 class="card-title mt-1">Premium Hotdog</h5>

                <div class="price">
                    <button class="btn btn-primary" disabled><?php echo "239.00";?></button>
                </div>

                <div class="d-flex justify-content-evenly g-2 p-2">
                    <button class="btn btn-primary"><i class="fa-solid fa-plus"></i></button>
                    <button class="btn btn-primary"><i class="fa-solid fa-minus"></i></button>
                </div>
            </div>
        </div>
    <?php ?>

    </div>
</div>

<div class="Corndog text-center mt-4" id="corndog-header">
    <div class="row g-3">
        <div class="Beverage-header" style="font-family: 'Bebas Neue', sans-serif;">
            <h4>Corndog</h4>
        </div>
<!--tuitle-->
        <?php ?>
        <div class="col-md-3 col-lg-2">
            <div class="card h-200 w-350 text-center shadow-lg p-3 mb-5 bg-body-tertiary rounded" style="object-fit: contain;">
            <img src="../images/Burger (2).png" style="height: 150px;" class="card-img-top" alt="...">
                <h5 class="card-title mt-1">B1T1 Classic Corndog</h5>

                <div class="price">
                    <button class="btn btn-primary" disabled><?php echo "239.00";?></button>
                </div>

                <div class="d-flex justify-content-evenly g-2 p-2">
                    <button class="btn btn-primary"><i class="fa-solid fa-plus"></i></button>
                    <button class="btn btn-primary"><i class="fa-solid fa-minus"></i></button>
                </div>
            </div>
        </div>
    <?php ?>
</div>

<div class="Beverages text-center mt-4" id="Beverages">
    <div class="row g-3">
        <div class="Beverage-header" style="font-family: 'Bebas Neue', sans-serif;">
            <h4>Beverages</h4>
        </div>
<!--tuitle-->
        <?php ?>
        <div class="col-md-3 col-lg-2">
            <div class="card h-200 w-350 text-center shadow-lg p-3 mb-5 bg-body-tertiary rounded" style="object-fit: contain;">
            <img src="../images/Burger (2).png" style="height: 150px;" class="card-img-top" alt="...">
                <h5 class="card-title mt-1">Coca-cola</h5>

                <div class="price">
                    <button class="btn btn-primary" disabled><?php echo "22.00";?></button>
                </div>

                <div class="d-flex justify-content-evenly g-2 p-2">
                    <button class="btn btn-primary"><i class="fa-solid fa-plus"></i></button>
                    <button class="btn btn-primary"><i class="fa-solid fa-minus"></i></button>
                </div>
            </div>
        </div>
        <?php ?>
    </div>

</div>
</main>

</div>
</body>
<script src="../functions/window.js"></script>
</html>
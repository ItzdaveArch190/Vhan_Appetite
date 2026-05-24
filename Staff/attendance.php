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
                <h3 class="title">Hello, <?php echo "Dave Besorio"; ?></h3>
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
                <button onclick="Menu()" class="btn custom-btn" type="submit">Menu</button>
                <button onclick="Gocheckout()" class="btn custom-btn" type="submit">Checkout</button>
                <button onclick="completedOrders()" class="btn custom-btn" type="button">Completed Orders</button>
                <button onclick="attendance()" class="btn custom-btn" type="submit">Attendance</button>
            
        </div>

        <div class="text-start ms-3 text-white mb-5">
            <button type="button" class="btn logout-btn btn-warning"><i class="fa-solid fa-arrow-right-from-bracket"></i></button>
        </div>
    </div>
</div>  

    <main class="" >
        <!--dito nyo lalagay yung content na ilalagay sa may white right side-->
        <!--Kayo bahala paano nyo gustong i-layout yung design-->
    </main>

</body>
<script src="../functions/window.js"></script>
</html>
<?php
    function renderStaffSidebar(){
?>
<div class="sidebar bg-success text-white">
    <div class="sidebar-header m-2 b-7 pt-1 text-center">

        <div class="d-flex flex-column justify-content-center gap-3 px-3 pt-5">
            <div class=" profile-container w-auto">
                <img id="round-profile" name="profile" src="../images/Burger (2).png" alt="">
            </div>
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
<?php
    }
?>
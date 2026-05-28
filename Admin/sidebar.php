<?php
    function renderAdminSidebar(){
?>
<div class="sidebar bg-success text-white">
    <div class="sidebar-header m-2 b-7 pt-1 text-center">
        <h1 class="title">Hello, <?php echo adminDisplayName(); ?></h1>
        <div class="row">
            <div class="col-sm-7 text-center">
                <span class="owner-name">Vahn Appetite</span>
            </div>
        </div>
    </div>

    <div class="d-grid gap-2 col-10 mx-auto">
        <button onclick="toDashboard()" class="btn custom-btn" type="submit">Frontdesk</button>
        <button onclick="toManageMenu()" class="btn custom-btn" type="button">Manage Menu</button>
        <button onclick="toAttendance()" class="btn custom-btn" type="submit">Attendance Summary</button>
        <button onclick="toProducts()" class="btn custom-btn" type="submit">Products</button>
        <button onclick="toSalesReport()" class="btn custom-btn" type="submit">Sales Report</button>
        <button class="btn custom-btn" type="submit">Payroll records</button>
        <button onclick="toStaffs()" class="btn custom-btn" type="submit">Monitor Staff</button>
    </div>
</div>
<?php
    }
?>
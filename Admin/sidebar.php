<?php
    function renderAdminSidebar(){
?>
<style>
    .app-sidebar {
        width: 350px;
        min-height: 100vh;
        position: fixed;
        inset: 0 auto 0 0;
        background: linear-gradient(180deg, #0f8a52 0%, #0d6b42 100%);
        color: #fff;
        display: flex;
        flex-direction: column;
        gap: 18px;
        padding: 18px 16px 20px;
        box-shadow: 12px 0 28px rgba(0, 0, 0, 0.14);
        z-index: 10;
        overflow-y: auto;
    }

    .app-sidebar__brand {
        padding: 10px 10px 2px;
    }

    .app-sidebar__eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 10px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.12);
        font-size: 11px;
        letter-spacing: 0.16em;
        text-transform: uppercase;
    }

    .app-sidebar__title {
        margin: 14px 0 2px;
        font-size: 2rem;
        line-height: 1;
        font-weight: 500;
    }

    .owner-name{
        font-family:'Brush Script MT', 'Brush Script Std', cursive;
        font-size: 18px;
        opacity: 0.95;
    }

    .app-sidebar__panel {
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 22px;
        padding: 14px;
        backdrop-filter: blur(8px);
    }

    .app-sidebar__nav {
        display: grid;
        gap: 12px;
    }

    .custom-btn,
    .custom-btn:hover,
    .custom-btn:focus,
    .custom-btn:active {
        background: linear-gradient(180deg, #efab23 0%, #e39710 100%);
        color: #1d241f;
        border: none;
        border-radius: 16px;
        min-height: 48px;
        font-weight: 600;
        box-shadow: 0 10px 18px rgba(0, 0, 0, 0.12);
    }

    .custom-btn:hover {
        transform: translateX(4px);
        filter: brightness(1.04);
    }

    .app-sidebar__footer {
        margin-top: auto;
        padding-top: 4px;
    }

    .app-sidebar__footer .Btn {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 48px;
        border: none;
        border-radius: 16px;
        cursor: pointer;
        position: relative;
        overflow: hidden;
        transition-duration: .25s;
        box-shadow: 0 10px 18px rgba(0, 0, 0, 0.12);
        background: #ff4e4e;
    }

    .app-sidebar__footer .sign {
        width: 18px;
        height: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .app-sidebar__footer .sign svg {
        width: 18px;
    }

    .app-sidebar__footer .sign svg path {
        fill: white;
    }

    .app-sidebar__footer .text {
        color: white;
        font-size: 1rem;
        font-weight: 600;
        margin-left: 10px;
    }
</style>
<aside class="app-sidebar sidebar text-white">
    <div class="app-sidebar__brand">
        <div class="app-sidebar__eyebrow">Admin Panel</div>
        <h1 class="app-sidebar__title">Hello, <?php echo adminDisplayName(); ?></h1>
        <div class="owner-name">Vahn Appetite</div>
    </div>

    <div class="app-sidebar__panel">
        <div class="app-sidebar__nav">
            <button onclick="toDashboard()" class="btn custom-btn" type="button">Dashboard</button>
            <button onclick="toManageMenu()" class="btn custom-btn" type="button">Manage Menu</button>
            <button onclick="toAttendance()" class="btn custom-btn" type="button">Attendance Summary</button>
            <button onclick="toProducts()" class="btn custom-btn" type="button">Products</button>
            <button onclick="toSalesReport()" class="btn custom-btn" type="button">Sales Report</button>
            <button class="btn custom-btn" type="button">Payroll records</button>
            <button onclick="toStaffs()" class="btn custom-btn" type="button">Monitor Staff</button>
        </div>
    </div>

    <div class="app-sidebar__footer">
        <button class="Btn" type="button">
            <div class="sign"><svg viewBox="0 0 512 512"><path d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L377.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-128 0c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM160 96L96 96c-17.7 0-32 14.3-32 32l0 256c0 17.7 14.3 32 32 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-53 0-96-43-96-96L0 128C0 75 43 32 96 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32z"></path></svg></div>
            <div class="text">Logout</div>
        </button>
    </div>
</aside>
<?php
    }
?>
<?php
    function renderStaffSidebar(){
?>
<style>
    .app-sidebar {
        width: 250px;
        min-height: 100vh;
        position: fixed;
        inset: 0 auto 0 0;
        background: linear-gradient(180deg, #0f8a52 0%, #0d6b42 100%);
        color: #fff;
        display: flex;
        flex-direction: column;
        gap: 16px;
        padding: 16px 14px 18px;
        box-shadow: 12px 0 28px rgba(0, 0, 0, 0.14);
        z-index: 10;
        overflow-y: auto;
    }

    .app-sidebar__brand {
        padding: 4px 8px 2px;
    }

    .app-sidebar__eyebrow {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 6px 10px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.12);
        font-size: 11px;
        letter-spacing: 0.16em;
        text-transform: uppercase;
        margin-bottom: 10px;
    }

    .app-sidebar__profile {
        width: 84px;
        height: 84px;
        border-radius: 24px;
        object-fit: cover;
        border: 3px solid rgba(255, 255, 255, 0.28);
        box-shadow: 0 10px 22px rgba(0, 0, 0, 0.14);
    }

    .app-sidebar__title {
        margin: 12px 0 2px;
        font-size: 1.55rem;
        line-height: 1.1;
        font-weight: 600;
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
        padding: 12px;
        backdrop-filter: blur(8px);
    }

    .app-sidebar__nav {
        display: grid;
        gap: 10px;
    }

    .custom-btn,
    .custom-btn:hover,
    .custom-btn:focus,
    .custom-btn:active {
        background: linear-gradient(180deg, #efab23 0%, #e39710 100%);
        color: #1d241f;
        border: none;
        border-radius: 14px;
        min-height: 46px;
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

    .logout-btn {
        width: 100%;
        height: 48px;
        border-radius: 14px;
        border: none;
        background: #ff4e4e;
        color: #fff;
        box-shadow: 0 10px 18px rgba(0, 0, 0, 0.12);
    }

    .logout-btn ion-icon {
        font-size: 1.1rem;
    }
</style>
<div>
    <aside class="app-sidebar sidebar text-white">
        <div class="app-sidebar__brand text-center">
            <div class="app-sidebar__eyebrow">Staff Panel</div>
            <div class="d-flex flex-column justify-content-center mt-4 align-items-center gap-2">
                <h3 class="app-sidebar__title"><?php echo staffUsername(); ?></h3>
            </div>

            <div class="business-name-divider mt-3">
                <span class="owner-name">Vahn Appetite</span>
            </div>
        </div>

        <div class="app-sidebar__panel">
            <div class="app-sidebar__nav">
                <button onclick="frontDesk()" class="btn custom-btn" type="button">Frontdesk</button>
                <button onclick="Menu()" class="btn custom-btn" type="button">Menu</button>
                <button onclick="Gocheckout()" class="btn custom-btn" type="button">Checkout</button>
                <button onclick="completedOrders()" class="btn custom-btn" type="button">Completed Orders</button>
                <button onclick="attendance()" class="btn custom-btn" type="button">Attendance</button>
            </div>
        </div>

        <div class="app-sidebar__footer">
            <a href="../logout.php" class="logout-btn d-flex align-items-center justify-content-center text-decoration-none">
                <div class="sign"><svg viewBox="0 0 512 512"><path d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L377.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-128 0c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM160 96L96 96c-17.7 0-32 14.3-32 32l0 256c0 17.7 14.3 32 32 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-53 0-96-43-96-96L0 128C0 75 43 32 96 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32z"></path></svg></div>
                <div class="text">Logout</div>
            </a>
        </div>
    </aside>
</div>
<?php
    }
?>
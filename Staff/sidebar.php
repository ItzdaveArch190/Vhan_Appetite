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
            <div class="d-flex flex-column justify-content-center align-items-center gap-2">
                <div class="profile-container w-auto">
                    <img class="app-sidebar__profile" id="round-profile" name="profile" src="../images/Burger (2).png" alt="">
                </div>
                <h3 class="app-sidebar__title"><?php echo staffUsername(); ?></h3>
            </div>

            <div class="business-name-divider mt-2">
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
            <button type="button" class="logout-btn"><ion-icon name="log-out-outline"></ion-icon></button>
        </div>
    </aside>
</div>
<?php
    }
?>
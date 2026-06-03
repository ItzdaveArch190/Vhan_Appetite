<?php
    require_once('auth.php');
    require_once('../Database/database.php');
    require_once('sidebar.php');

    $con = new Database();
    $DailySales = $con->getTodaySales();
    $getOrder = $con->getOrder();
    $get_EMPLOYEE = $con->getAllEmployee();
    $topProducts = $con->getTopSoldItems(3);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <title>Dashboard Panel</title>
    <style>
        body{
            background:
                radial-gradient(circle at top right, rgba(15, 138, 82, 0.08), transparent 28%),
                radial-gradient(circle at bottom left, rgba(227, 151, 16, 0.08), transparent 26%),
                #f7f8f4;
        }

        .dashboard-shell{
            margin-left: 350px;
            min-height: 100vh;
            padding: 28px 28px 34px;
        }

        .dashboard-hero{
            position: relative;
            overflow: hidden;
            border: 0;
            border-radius: 28px;
            background:
                radial-gradient(circle at top left, rgba(255, 255, 255, 0.08), transparent 30%),
                linear-gradient(145deg, #13181d 0%, #22282e 58%, #1c2126 100%);
            box-shadow: 0 18px 42px rgba(15, 23, 42, 0.08);
        }

        .dashboard-hero__copy{
            padding: 36px 34px 34px;
            color: #f8fafc;
        }

        .dashboard-hero__copy .dashboard-title{
            max-width: 560px;
            color: #ffffff;
            text-wrap: balance;
        }

        .dashboard-hero__copy .dashboard-copy{
            max-width: 560px;
            color: rgba(255, 255, 255, 0.86);
        }

        .dashboard-kicker{
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 7px 12px;
            border-radius: 999px;
            background: rgba(15, 138, 82, 0.18);
            color: #e6fff0;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
        }

        .dashboard-title{
            margin: 14px 0 8px;
            font-size: clamp(2.45rem, 4vw, 4rem);
            line-height: 0.98;
            font-weight: 800;
            color: #ffffff;
        }

        .dashboard-copy{
            max-width: 640px;
            color: rgba(255, 255, 255, 0.82);
            font-size: 1rem;
            line-height: 1.65;
        }

        .dashboard-hero__art{
            min-height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 22px;
            background:
                radial-gradient(circle at 70% 20%, rgba(15, 138, 82, 0.24), transparent 28%),
                radial-gradient(circle at 25% 80%, rgba(227, 151, 16, 0.18), transparent 26%),
                linear-gradient(180deg, #f5f4ee 0%, #ebe8df 100%);
        }

        .dashboard-hero__visual{
            width: min(100%, 340px);
            padding: 20px;
            border-radius: 30px;
            background: rgba(255, 255, 255, 0.78);
            border: 1px solid rgba(255, 255, 255, 0.8);
            box-shadow: 0 18px 32px rgba(15, 23, 42, 0.08);
            backdrop-filter: blur(10px);
        }

        .dashboard-hero__chart{
            display: flex;
            align-items: end;
            justify-content: center;
            gap: 12px;
            height: 132px;
            margin: 0 auto 18px;
            padding: 18px 20px;
            border-radius: 24px;
            background: linear-gradient(180deg, #ffffff 0%, #f3f6f0 100%);
            box-shadow: inset 0 0 0 1px rgba(148, 163, 184, 0.12);
        }

        .dashboard-hero__bar{
            width: 30px;
            border-radius: 10px 10px 4px 4px;
            background: linear-gradient(180deg, #0f8a52 0%, #0d6b42 100%);
            box-shadow: 0 8px 18px rgba(15, 138, 82, 0.22);
        }

        .dashboard-hero__bar--accent{
            background: linear-gradient(180deg, #e39710 0%, #c97b05 100%);
            box-shadow: 0 8px 18px rgba(227, 151, 16, 0.22);
        }

        .dashboard-hero__bar--muted{
            background: linear-gradient(180deg, #8aa3c3 0%, #647a95 100%);
            box-shadow: 0 8px 18px rgba(100, 122, 149, 0.22);
        }

        .dashboard-hero__summary{
            display: grid;
            gap: 12px;
        }

        .quick-actions-card{
            border: 0;
            border-radius: 24px;
            box-shadow: 0 14px 34px rgba(15, 23, 42, 0.08);
            background: linear-gradient(180deg, #ffffff 0%, #fbfaf6 100%);
        }

        .quick-actions-grid{
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
        }

        .quick-action-btn{
            display: flex;
            align-items: center;
            gap: 12px;
            width: 100%;
            padding: 16px 18px;
            border: 1px solid #e7ece3;
            border-radius: 18px;
            background: #ffffff;
            text-decoration: none;
            color: #1f2937;
            box-shadow: 0 8px 18px rgba(15, 23, 42, 0.04);
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        }

        .quick-action-btn:hover{
            transform: translateY(-2px);
            border-color: rgba(15, 138, 82, 0.22);
            box-shadow: 0 14px 24px rgba(15, 23, 42, 0.08);
        }

        .quick-action-btn__icon{
            width: 46px;
            height: 46px;
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(15, 138, 82, 0.10);
            color: #0d6b42;
            flex-shrink: 0;
        }

        .quick-action-btn__text strong{
            display: block;
            font-size: 1rem;
            font-weight: 800;
            color: #1f2937;
        }

        .quick-action-btn__text span{
            display: block;
            margin-top: 2px;
            color: #6b7280;
            font-size: 0.88rem;
        }

        .dashboard-hero__stat{
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 12px 14px;
            border-radius: 18px;
            background: #ffffff;
            box-shadow: inset 0 0 0 1px rgba(148, 163, 184, 0.12);
        }

        .dashboard-hero__stat-label{
            font-size: 0.78rem;
            font-weight: 800;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #6b7280;
        }

        .dashboard-hero__stat-value{
            font-size: 1.35rem;
            font-weight: 800;
            color: #1f2937;
        }

        .dashboard-hero__badge{
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 56px;
            height: 56px;
            border-radius: 18px;
            background: rgba(15, 138, 82, 0.14);
            color: #0d6b42;
            flex-shrink: 0;
        }

        .dashboard-hero__art .dashboard-hero__stat-label{
            color: #6b7280;
        }

        .dashboard-hero__art .dashboard-hero__stat-value{
            color: #172033;
        }

        .dashboard-hero__art .dashboard-hero__stat{
            background: #ffffff;
        }

        .metric-card,
        .content-card{
            border: 0;
            border-radius: 24px;
            box-shadow: 0 14px 34px rgba(15, 23, 42, 0.08);
        }

        .metric-card{
            min-height: 152px;
            background: linear-gradient(180deg, #ffffff 0%, #fafaf7 100%);
        }

        .metric-label{
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: #6b7280;
        }

        .metric-value{
            font-size: clamp(2rem, 4vw, 2.65rem);
            font-weight: 800;
            color: #1f2937;
            line-height: 1;
        }

        .metric-icon{
            width: 52px;
            height: 52px;
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(15, 138, 82, 0.12);
            color: #0d6b42;
        }

        .section-label{
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            color: #0d6b42;
        }

        .table thead th{
            border-top: none;
            border-bottom: 1px solid #e6e8eb;
            color: #1f2937;
        }

        .table tbody tr:last-child td{
            border-bottom: none;
        }

        .top-products-wrap{
            height: 100%;
        }

        .top-products-list{
            display: grid;
            gap: 14px;
        }

        .top-product-item{
            padding: 14px 16px;
            border-radius: 18px;
            background: #f8faf7;
            border: 1px solid #edf1ea;
        }

        .top-product-item strong{
            display: block;
            margin-bottom: 4px;
            color: #1f2937;
        }

        .top-product-item span{
            color: #6b7280;
            font-size: 0.95rem;
        }

        @media (max-width: 992px){
            .dashboard-shell{
                margin-left: 0;
                padding: 18px;
            }
        }
    </style>
</head>
<body>
    <div class="d-flex min-vh-100">
        <?php renderAdminSidebar(); ?>

        <main class="dashboard-shell w-100">
            <div class="card dashboard-hero mb-4">
                <div class="row g-0 align-items-stretch">
                    <div class="col-lg-8 dashboard-hero__copy">
                        <div class="dashboard-kicker">Admins Monitoring Dashboard</div>
                        <h3 class="dashboard-title">Monitor performance, stock, and team execution.</h3>
                        <p class="dashboard-copy mb-0">
                            Keep an eye on sales momentum, order flow, and staff count from a cleaner, more focused control center.
                        </p>
                    </div>
                    <div class="col-lg-4 dashboard-hero__art">
                        <div class="dashboard-hero__visual">
                            <div class="dashboard-hero__chart" aria-hidden="true">
                                <div class="dashboard-hero__bar" style="height: 72px;"></div>
                                <div class="dashboard-hero__bar dashboard-hero__bar--accent" style="height: 48px;"></div>
                                <div class="dashboard-hero__bar dashboard-hero__bar--muted" style="height: 90px;"></div>
                                <div class="dashboard-hero__bar" style="height: 58px;"></div>
                            </div>

                            <div class="dashboard-hero__summary">
                                <div class="dashboard-hero__stat">
                                    <div>
                                        <div class="dashboard-hero__stat-label">Daily sales</div>
                                        <div class="dashboard-hero__stat-value">₱<?php echo number_format((float)$DailySales, 2); ?></div>
                                    </div>
                                    <div class="dashboard-hero__badge">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M8 19v-14h3.5a4.5 4.5 0 1 1 0 9h-3.5" /><path d="M18 8h-12" /><path d="M18 11h-12" /></svg>
                                    </div>
                                </div>

                                <div class="dashboard-hero__stat">
                                    <div>
                                        <div class="dashboard-hero__stat-label">Transactions</div>
                                        <div class="dashboard-hero__stat-value"><?php echo $getOrder; ?></div>
                                    </div>
                                    <div class="dashboard-hero__badge">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M7 7h10" /><path d="M7 12h10" /><path d="M7 17h6" /></svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card quick-actions-card p-4 mb-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                    <div>
                        <div class="section-label mb-2">Quick Actions</div>
                        <h5 class="fw-bold mb-0">Shortcuts for the things you use most</h5>
                    </div>
                    <span class="text-muted">Jump straight to the next task</span>
                </div>

                <div class="quick-actions-grid">
                    <a href="DSR.php" class="quick-action-btn">
                        <div class="quick-action-btn__icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h-8" /><path d="M4 4h16v16" /><path d="M8 8h8" /></svg>
                        </div>
                        <div class="quick-action-btn__text">
                            <strong>Sales Report</strong>
                            <span>View sales reports</span>
                        </div>
                    </a>

                    <a href="ManageMenu.php" class="quick-action-btn">
                        <div class="quick-action-btn__icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M4 5h16" /><path d="M4 12h16" /><path d="M4 19h10" /></svg>
                        </div>
                        <div class="quick-action-btn__text">
                            <strong>Manage Menu</strong>
                            <span>Edit products and pricing</span>
                        </div>
                    </a>

                    <a href="CheckAttendance.php" class="quick-action-btn">
                        <div class="quick-action-btn__icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M9 11l3 3l8-8" /><path d="M21 12a9 9 0 1 1-9-9" /></svg>
                        </div>
                        <div class="quick-action-btn__text">
                            <strong>Attendance</strong>
                            <span>Review staff attendance summary</span>
                        </div>
                    </a>

                    <a href="ViewProducts.php" class="quick-action-btn">
                        <div class="quick-action-btn__icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7h16" /><path d="M6 7l1 14h10l1-14" /><path d="M9 7V4h6v3" /></svg>
                        </div>
                        <div class="quick-action-btn__text">
                            <strong>Products</strong>
                            <span>Check product inventory quickly</span>
                        </div>
                    </a>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-6 col-lg-4">
                    <div class="card metric-card p-4 h-100">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="metric-label">Daily Sales</div>
                            <div class="metric-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M8 19v-14h3.5a4.5 4.5 0 1 1 0 9h-3.5" /><path d="M18 8h-12" /><path d="M18 11h-12" /></svg>
                            </div>
                        </div>
                        <div class="metric-value">₱<?php echo number_format((float)$DailySales, 2); ?></div>
                        <div class="text-muted mt-2">Tracked from completed sales reports</div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="card metric-card p-4 h-100">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="metric-label">Transactions</div>
                            <div class="metric-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M7 7h10" /><path d="M7 12h10" /><path d="M7 17h6" /></svg>
                            </div>
                        </div>
                        <div class="metric-value"><?php echo $getOrder; ?></div>
                        <div class="text-muted mt-2">Orders processed</div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="card metric-card p-4 h-100">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="metric-label">Employees</div>
                            <div class="metric-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M8 21v-1a2 2 0 0 1 2 -2h4a2 2 0 0 1 2 2v1" /><path d="M15 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M17 10h2a2 2 0 0 1 2 2v1" /><path d="M5 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M3 13v-1a2 2 0 0 1 2 -2h2" /></svg>
                            </div>
                        </div>
                        <div class="metric-value"><?php echo $get_EMPLOYEE; ?></div>
                        <div class="text-muted mt-2">Active staff on the roster</div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card content-card p-4 h-100">
                        <div class="d-flex align-items-start justify-content-between mb-3">
                            <div>
                                <div class="section-label mb-2">Top Products</div>
                                <h5 class="fw-bold mb-2">Monitor top selling products and the total sold.</h5>
                            </div>
                        </div>

                        <table class="table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th class="text-end">Total Sold</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($topProducts)){ ?>
                                    <tr>
                                        <td colspan="2" class="text-center py-4">No sold products found yet.</td>
                                    </tr>
                                <?php } ?>
                                <?php foreach($topProducts as $item){ ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($item['Product_Name'] ?? '-'); ?></td>
                                    <td class="text-end fw-semibold"><?php echo (int)($item['Items_Sold'] ?? 0); ?></td>
                                </tr>
                                <?php } ?>
                                <tr>
                                    <td>Hungarian Sausage</td>
                                    <td class="text-end fw-semibold">7</td>
                                </tr>
                                <tr>
                                    <td>B1T1 Double Decker Bacon</td>
                                    <td class="text-end fw-semibold">5</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="../functions/window.js"></script>
    <script src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js" type="module"></script>
    <script src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js" nomodule></script>
</body>
</html>

<?php
    require_once('auth.php');
    require_once('../Database/database.php');
    require_once('sidebar.php');

    $con = new Database();
    $salesByDay = $con->getSalesReportByDay(31);
    $topItems = $con->getTopSoldItems(10);

    $totalIncome = 0;
    $totalItems = 0;
    $totalOrders = 0;
    foreach($salesByDay as $row){
        $totalIncome += (float)($row['Total_Income'] ?? 0);
        $totalItems += (int)($row['Items_Sold'] ?? 0);
        $totalOrders += (int)($row['Orders_Count'] ?? 0);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <title>Sales Report</title>
    <style>
        body{
            background:
                radial-gradient(circle at top right, rgba(15, 138, 82, 0.08), transparent 26%),
                radial-gradient(circle at bottom left, rgba(227, 151, 16, 0.08), transparent 24%),
                #f7f8f4;
        }

        .sales-shell{
            margin-left: 350px;
            min-height: 100vh;
            padding: 28px;
        }

        .sales-hero{
            border: 0;
            border-radius: 26px;
            background:
                radial-gradient(circle at top left, rgba(255,255,255,.07), transparent 30%),
                linear-gradient(145deg, #13181d 0%, #22282e 58%, #1c2126 100%);
            color: #f8fafc;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.10);
        }

        .sales-hero__body{
            padding: 30px 32px;
        }

        .sales-kicker{
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 10px;
            border-radius: 999px;
            background: rgba(15, 138, 82, 0.22);
            color: #e6fff0;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .14em;
            text-transform: uppercase;
        }

        .sales-title{
            margin: 14px 0 8px;
            font-size: clamp(2rem, 3vw, 2.8rem);
            line-height: 1;
            font-weight: 800;
        }

        .sales-copy{
            color: rgba(255,255,255,.84);
            max-width: 720px;
        }

        .summary-card,
        .table-card{
            border: 0;
            border-radius: 24px;
            box-shadow: 0 14px 34px rgba(15, 23, 42, 0.08);
            background: #fff;
        }

        .summary-metric{
            border-radius: 18px;
            background: #fff;
            border: 1px solid #e7ece3;
            padding: 16px;
        }

        .summary-metric .label{
            color: #6b7280;
            font-size: .76rem;
            letter-spacing: .12em;
            text-transform: uppercase;
            font-weight: 700;
        }

        .summary-metric .value{
            margin-top: 4px;
            color: #1f2937;
            font-size: 1.45rem;
            font-weight: 800;
        }

        .report-table thead th,
        .item-table thead th{
            font-size: .78rem;
            letter-spacing: .10em;
            text-transform: uppercase;
            color: #64748b;
            border-bottom: 1px solid #e2e8f0;
        }

        .report-table tbody td,
        .item-table tbody td{
            vertical-align: middle;
            color: #1f2937;
        }

        .date-pill,
        .product-pill{
            display: inline-block;
            padding: 6px 10px;
            border-radius: 999px;
            background: rgba(15, 138, 82, 0.08);
            color: #0d6b42;
            font-weight: 700;
            font-size: .88rem;
        }

        @media (max-width: 991px){
            .sales-shell{
                margin-left: 0;
                padding: 18px 14px 28px;
            }
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <?php renderAdminSidebar(); ?>

        <main class="sales-shell w-100">
            <div class="card sales-hero mb-4">
                <div class="sales-hero__body">
                    <span class="sales-kicker">Sales Report</span>
                    <h1 class="sales-title">Daily income and items sold</h1>
                    <p class="sales-copy mb-0">Track how much the shop earned per day and which menu items are moving fastest.</p>
                </div>
            </div>

            <div class="card summary-card mb-4">
                <div class="card-body p-3 p-md-4">
                    <div class="row g-3">
                        <div class="col-12 col-md-4">
                            <div class="summary-metric">
                                <div class="label">Total Income</div>
                                <div class="value">₱<?php echo number_format($totalIncome, 2); ?></div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="summary-metric">
                                <div class="label">Items Sold</div>
                                <div class="value"><?php echo $totalItems; ?></div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="summary-metric">
                                <div class="label">Report Days</div>
                                <div class="value"><?php echo count($salesByDay); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card table-card mb-4">
                <div class="card-body p-3 p-md-4">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                        <h5 class="mb-0">Income per day</h5>
                        <input id="salesSearch" class="form-control form-control-sm" style="max-width: 280px;" placeholder="Search date...">
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover report-table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Orders</th>
                                    <th>Items Sold</th>
                                    <th>Total Income</th>
                                </tr>
                            </thead>
                            <tbody id="salesBody">
                                <?php if(empty($salesByDay)){ ?>
                                    <tr>
                                        <td colspan="4" class="text-center py-4">No sales report found.</td>
                                    </tr>
                                <?php } ?>

                                <?php foreach($salesByDay as $row){ ?>
                                    <tr>
                                        <td><span class="date-pill"><?php echo htmlspecialchars($row['Report_Date'] ?? '-'); ?></span></td>
                                        <td><?php echo (int)($row['Orders_Count'] ?? 0); ?></td>
                                        <td><?php echo (int)($row['Items_Sold'] ?? 0); ?></td>
                                        <td>₱<?php echo number_format((float)($row['Total_Income'] ?? 0), 2); ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card table-card">
                <div class="card-body p-3 p-md-4">
                    <h5 class="mb-3">Top items sold</h5>
                    <div class="table-responsive">
                        <table class="table table-hover item-table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Items Sold</th>
                                    <th>Item Income</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($topItems)){ ?>
                                    <tr>
                                        <td colspan="3" class="text-center py-4">No sold items found.</td>
                                    </tr>
                                <?php } ?>

                                <?php foreach($topItems as $item){ ?>
                                    <tr>
                                        <td><span class="product-pill"><?php echo htmlspecialchars($item['Product_Name'] ?? '-'); ?></span></td>
                                        <td><?php echo (int)($item['Items_Sold'] ?? 0); ?></td>
                                        <td>₱<?php echo number_format((float)($item['Item_Income'] ?? 0), 2); ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

<script>
    (function(){
        const input = document.getElementById('salesSearch');
        const rows = Array.from(document.querySelectorAll('#salesBody tr'));
        if(!input) return;
        input.addEventListener('input', function(){
            const q = this.value.trim().toLowerCase();
            rows.forEach(function(row){
                const text = (row.textContent || '').toLowerCase();
                row.style.display = (!q || text.indexOf(q) !== -1) ? '' : 'none';
            });
        });
    })();
</script>
<script src="../functions/window.js"></script>
</body>
</html>
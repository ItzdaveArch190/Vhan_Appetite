<?php
    require_once('auth.php');
    require_once('../Database/database.php');
    require_once('sidebar.php');

    $con = new Database();
    $sortBy = $_GET['sort'] ?? 'latest';
    if(!in_array($sortBy, ['latest', 'oldest'])){
        $sortBy = 'latest';
    }
    $payrollRecords = $con->getPayrollRecords($sortBy);

    if(isset($_POST['markPaid'])){
        $employeeID = (int)($_POST['employee_id'] ?? 0);
        $salaryAmount = (float)($_POST['salary_amount'] ?? 0);
        $returnSort = $_POST['sort'] ?? $sortBy;

        if($employeeID > 0){
            try{
                $con->markPayrollPaid($employeeID, $salaryAmount);
                $_SESSION['success_message'] = 'Payroll marked as paid.';
                header('Location: PayrollRecords.php?sort=' . urlencode($returnSort));
                exit();
            } catch(Exception $e){
                $_SESSION['error_message'] = 'Unable to update payroll status.';
                header('Location: PayrollRecords.php?sort=' . urlencode($returnSort));
                exit();
            }
        }
    }

    $totalEmployees = count($payrollRecords);
    $totalSalary = 0;
    $paidCount = 0;
    foreach($payrollRecords as $record){
        $totalSalary += (float)($record['Salary_Amount'] ?? 0);
        if(!empty($record['Payroll_Paid_At'])){
            $paidCount++;
        }
    }
    $unpaidCount = $totalEmployees - $paidCount;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <title>Payroll Records</title>
    <style>
        body{
            background:
                radial-gradient(circle at top right, rgba(15, 138, 82, 0.08), transparent 26%),
                radial-gradient(circle at bottom left, rgba(227, 151, 16, 0.08), transparent 24%),
                #f7f8f4;
        }

        .payroll-shell{
            margin-left: 350px;
            min-height: 100vh;
            padding: 28px;
        }

        .payroll-hero{
            border: 0;
            border-radius: 26px;
            background:
                radial-gradient(circle at top left, rgba(255,255,255,.07), transparent 30%),
                linear-gradient(145deg, #13181d 0%, #22282e 58%, #1c2126 100%);
            color: #f8fafc;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.10);
        }

        .payroll-hero__body{
            padding: 30px 32px;
        }

        .payroll-kicker{
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

        .payroll-title{
            margin: 14px 0 8px;
            font-size: clamp(2rem, 3vw, 2.8rem);
            line-height: 1;
            font-weight: 800;
        }

        .payroll-copy{
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

        .payroll-table thead th{
            font-size: .78rem;
            letter-spacing: .10em;
            text-transform: uppercase;
            color: #64748b;
            border-bottom: 1px solid #e2e8f0;
        }

        .payroll-table tbody td{
            vertical-align: middle;
            color: #1f2937;
        }

        .staff-pill{
            display: inline-block;
            padding: 6px 10px;
            border-radius: 999px;
            background: rgba(15, 138, 82, 0.08);
            color: #0d6b42;
            font-weight: 700;
            font-size: .88rem;
        }

        .status-pill{
            display: inline-block;
            padding: 6px 10px;
            border-radius: 999px;
            background: rgba(227, 151, 16, 0.12);
            color: #a16207;
            font-weight: 700;
            font-size: .82rem;
        }

        @media (max-width: 991px){
            .payroll-shell{
                margin-left: 0;
                padding: 18px 14px 28px;
            }
        }
    </style>
</head>
<body>
<div class="d-flex">
    <?php renderAdminSidebar(); ?>

    <main class="payroll-shell w-100">
        <div class="card payroll-hero mb-4">
            <div class="payroll-hero__body">
                <span class="payroll-kicker">Payroll Records</span>
                <h1 class="payroll-title">Pending payroll list</h1>
                <p class="payroll-copy mb-0">Review each employee's base salary, latest duty time, and current payout status before releasing payroll.</p>
            </div>
        </div>

        <div class="card summary-card mb-4">
            <div class="card-body p-3 p-md-4">
                <div class="row g-3">
                    <div class="col-12 col-md-4">
                        <div class="summary-metric">
                            <div class="label">Employees Listed</div>
                            <div class="value"><?php echo $totalEmployees; ?></div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="summary-metric">
                            <div class="label">Total Base Salary</div>
                            <div class="value">₱<?php echo number_format($totalSalary, 2); ?></div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="summary-metric">
                            <div class="label">Unpaid Employees</div>
                            <div class="value"><?php echo $unpaidCount; ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card table-card">
            <div class="card-body p-3 p-md-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                    <h5 class="mb-0">Employee Payroll</h5>
                    <div class="d-flex gap-2 flex-wrap">
                        <form method="GET" class="m-0">
                            <select class="form-select form-select-sm" name="sort" onchange="this.form.submit()">
                                <option value="latest" <?php echo $sortBy === 'latest' ? 'selected' : ''; ?>>Latest duty date</option>
                                <option value="oldest" <?php echo $sortBy === 'oldest' ? 'selected' : ''; ?>>Oldest duty date</option>
                            </select>
                        </form>
                        <input id="payrollSearch" class="form-control form-control-sm" style="max-width: 280px;" placeholder="Search employee name...">
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover payroll-table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Base Salary</th>
                                <th>Bonus</th>
                                <th>Duty In</th>
                                <th>Duty Out</th>
                                <th>Latest Duty Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="payrollBody">
                            <?php if(empty($payrollRecords)){ ?>
                                <tr>
                                    <td colspan="8" class="text-center py-4">No payroll records found.</td>
                                </tr>
                            <?php } ?>

                            <?php foreach($payrollRecords as $record){ ?>
                                <tr>
                                    <td>
                                        <span class="staff-pill"><?php echo htmlspecialchars($record['Emp_FN'] . ' ' . $record['Emp_LN']); ?></span>
                                        <div class="small text-muted mt-1"><?php echo htmlspecialchars($record['Email'] ?? ''); ?></div>
                                    </td>
                                    <td>₱<?php echo number_format((float)($record['Salary_Amount'] ?? 0), 2); ?></td>
                                    <td><?php echo htmlspecialchars($record['BonusName'] ?? 'No bonus'); ?></td>
                                    <td><?php echo htmlspecialchars($record['Time_In'] ?? '-'); ?></td>
                                    <td><?php echo htmlspecialchars($record['Time_Out'] ?? '-'); ?></td>
                                    <td><?php echo htmlspecialchars($record['Attendance_Date'] ?? '-'); ?></td>
                                    <td>
                                        <?php if(!empty($record['Payroll_Paid_At'])){ ?>
                                            <span class="status-pill" style="background: rgba(15, 138, 82, 0.12); color: #0d6b42;">Paid</span>
                                        <?php } else { ?>
                                            <span class="status-pill">Unpaid</span>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <?php if(empty($record['Payroll_Paid_At'])){ ?>
                                            <form method="POST" class="m-0">
                                                <input type="hidden" name="employee_id" value="<?php echo (int)$record['Employee_ID']; ?>">
                                                <input type="hidden" name="salary_amount" value="<?php echo (float)($record['Salary_Amount'] ?? 0); ?>">
                                                <input type="hidden" name="sort" value="<?php echo htmlspecialchars($sortBy); ?>">
                                                <button type="submit" name="markPaid" class="btn btn-sm btn-success">Paid</button>
                                            </form>
                                        <?php } else { ?>
                                            <button type="button" class="btn btn-sm btn-outline-success" disabled>Paid</button>
                                        <?php } ?>
                                    </td>
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
        const input = document.getElementById('payrollSearch');
        const rows = Array.from(document.querySelectorAll('#payrollBody tr'));
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
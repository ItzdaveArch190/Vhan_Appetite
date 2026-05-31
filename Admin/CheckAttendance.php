<?php
require_once('auth.php');
require_once('../Database/database.php');
require_once('sidebar.php');

$con = new Database();
$fetchAttendance = $con->GetAttendance();

$totalLogs = count($fetchAttendance);
$uniqueStaff = count(array_unique(array_column($fetchAttendance, 'Staff')));
$latestDate = $totalLogs > 0 ? $fetchAttendance[$totalLogs - 1]['Date'] : 'No records yet';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <title>Attendance Summary</title>
    <style>
        body{
            background:
                radial-gradient(circle at top right, rgba(15, 138, 82, 0.08), transparent 26%),
                radial-gradient(circle at bottom left, rgba(227, 151, 16, 0.08), transparent 24%),
                #f7f8f4;
        }

        .attendance-shell{
            margin-left: 350px;
            min-height: 100vh;
            padding: 28px;
        }

        .attendance-hero{
            border: 0;
            border-radius: 26px;
            background:
                radial-gradient(circle at top left, rgba(255,255,255,.07), transparent 30%),
                linear-gradient(145deg, #13181d 0%, #22282e 58%, #1c2126 100%);
            color: #f8fafc;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.10);
        }

        .attendance-hero__body{
            padding: 30px 32px;
        }

        .attendance-kicker{
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

        .attendance-title{
            margin: 14px 0 8px;
            font-size: clamp(2rem, 3vw, 2.8rem);
            line-height: 1;
            font-weight: 800;
        }

        .attendance-copy{
            color: rgba(255,255,255,.84);
            max-width: 680px;
        }

        .summary-card{
            border: 0;
            border-radius: 22px;
            box-shadow: 0 14px 34px rgba(15, 23, 42, 0.08);
            background: linear-gradient(180deg, #ffffff 0%, #fbfaf6 100%);
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

        .table-card{
            border: 0;
            border-radius: 24px;
            box-shadow: 0 14px 34px rgba(15, 23, 42, 0.08);
            background: #fff;
        }

        .attendance-table thead th{
            font-size: .78rem;
            letter-spacing: .10em;
            text-transform: uppercase;
            color: #64748b;
            border-bottom: 1px solid #e2e8f0;
        }

        .attendance-table tbody td{
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

        @media (max-width: 991px){
            .attendance-shell{
                margin-left: 0;
                padding: 18px 14px 28px;
            }
        }
    </style>
</head>
<body>
<div class="d-flex">
    <?php renderAdminSidebar(); ?>

    <main class="attendance-shell w-100">
        <div class="card attendance-hero mb-4">
            <div class="attendance-hero__body">
                <span class="attendance-kicker">Attendance</span>
                <h1 class="attendance-title">Attendance Summary</h1>
                <p class="attendance-copy mb-0">Track staff login consistency and quickly review the latest attendance logs in one place.</p>
            </div>
        </div>

        <div class="card summary-card mb-4">
            <div class="card-body p-3 p-md-4">
                <div class="row g-3">
                    <div class="col-12 col-md-4">
                        <div class="summary-metric">
                            <div class="label">Total Logs</div>
                            <div class="value"><?php echo $totalLogs; ?></div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="summary-metric">
                            <div class="label">Active Staff</div>
                            <div class="value"><?php echo $uniqueStaff; ?></div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="summary-metric">
                            <div class="label">Latest Attendance</div>
                            <div class="value" style="font-size:1.05rem;"><?php echo htmlspecialchars($latestDate); ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card table-card">
            <div class="card-body p-3 p-md-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                    <h5 class="mb-0">Attendance Logs</h5>
                    <input id="attendanceSearch" class="form-control form-control-sm" style="max-width: 280px;" placeholder="Search staff name...">
                </div>
                <div class="table-responsive">
                    <table class="table table-hover attendance-table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Staff</th>
                                <th>Time In</th>
                                <th>Time Out</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody id="attendanceBody">
                            <?php foreach($fetchAttendance as $Attendee){ ?>
                            <tr>
                                <td><span class="staff-pill"><?php echo htmlspecialchars($Attendee['Staff']); ?></span></td>
                                <td><?php echo htmlspecialchars($Attendee['Time_In']); ?></td>
                                <td><?php echo htmlspecialchars($Attendee['logout']); ?></td>
                                <td><?php echo htmlspecialchars($Attendee['Date']); ?></td>
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
        const input = document.getElementById('attendanceSearch');
        const rows = Array.from(document.querySelectorAll('#attendanceBody tr'));
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